<?php

namespace App\Services\Impl;

use App\Models\Documentation;
use App\Models\DocumentationPage;
use App\Models\DocumentationRelease;
use App\Models\User;
use App\Services\GitService;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GitServiceImpl implements GitService
{

    public function loadGitPageContent($link)
    {
        return $this->getRawContent($link);
    }

    public function loadEntireDocumentation(
        string $githubUrl,
        Documentation $documentation,
        DocumentationRelease $release,
        User $user
    ) {

        $context = $this->extractFolderContext($githubUrl);

        $tree = $this->getRepositoryTree(
            $context['owner'],
            $context['repo'],
            $context['branch']
        );

        $tree = $this->filterTree(
            $tree,
            $context['path']
        );

        $tree = $this->filterMarkdownTree($tree);

        $tree = $this->buildTree($tree);

        DB::transaction(function () use (
            $tree,
            $documentation,
            $release,
            $user,
            $context
        ) {

            $this->createDocumentationTree(
                $tree,
                $documentation,
                $release,
                $user,
                $context
            );
        });
    }

    public function convertImageUrls($content, $owner, $repoName, $branch, $directory)
    {
        $baseUrl = "https://raw.githubusercontent.com/$owner/$repoName/$branch/";

        return preg_replace_callback(
            '/<(img|source)[^>]+(src|srcset)=["\']([^"\']+)["\']/i',
            function ($matches) use ($baseUrl, $directory) {

                $tagType = $matches[1];
                $attr    = $matches[2];
                $url     = $matches[3];

                if (preg_match('/^https?:\/\//', $url)) {
                    return $matches[0];
                }

                $path = ltrim($url, '/');

                if (!empty($directory)) {
                    $path = trim($directory, '/') . '/' . $path;
                }

                $newUrl = $baseUrl . $path;

                return str_replace($url, $newUrl, $matches[0]);
            },
            $content
        );
    }


    public function convertToRawUrl(string $githubUrl): string
    {
        if (str_starts_with($githubUrl, 'https://raw.githubusercontent.com/')) {
            return $githubUrl;
        }

        $this->validateGithubHost($githubUrl);

        $segments = $this->parsePathSegments($githubUrl);

        if ($this->isRepositoryRoot($segments)) {
            return $this->buildReadmeRawUrl($segments[0], $segments[1]);
        }

        if ($this->isBlobFile($segments)) {
            $this->ensureMarkdownFile($segments);

            return $this->buildFileRawUrl($segments);
        }

        throw new InvalidArgumentException('Unsupported GitHub URL format.');
    }

    public function getRawContent(string $githubUrl): string
    {
        $this->validateGithubHost($githubUrl);
        $segments = $this->parsePathSegments($githubUrl);

        $rawUrl = $this->convertToRawUrl($githubUrl);

        $response = Http::timeout(15)->get($rawUrl);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch Markdown file.' . $rawUrl);
        }

        $content = $response->body();

        [$owner, $repo, $branch, $directory] = $this->extractFileContext($segments);

        return $this->convertImageUrls($content, $owner, $repo, $branch, $directory);
    }


    private function validateGithubHost(string $url): void
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (!($host == 'github.com' || $host == 'raw.githubusercontent.com')) {
            Log::info($host);
            throw new InvalidArgumentException('Invalid GitHub URL.');
        }
    }

    private function parsePathSegments(string $url): array
    {
        $path = trim(parse_url($url, PHP_URL_PATH), '/');
        return explode('/', $path);
    }

    private function isRepositoryRoot(array $segments): bool
    {
        return count($segments) === 2;
    }

    private function isBlobFile(array $segments): bool
    {
        return count($segments) >= 5 && $segments[2] === 'blob';
    }

    private function ensureMarkdownFile(array $segments): void
    {
        $filePath = implode('/', array_slice($segments, 4));

        if (!str_ends_with(strtolower($filePath), '.md')) {
            throw new InvalidArgumentException('Only Markdown (.md) files are allowed.');
        }
    }

    private function buildReadmeRawUrl(string $owner, string $repo): string
    {
        return "https://raw.githubusercontent.com/{$owner}/{$repo}/main/README.md";
    }

    private function buildFileRawUrl(array $segments): string
    {
        $owner  = $segments[0];
        $repo   = $segments[1];
        $branch = $segments[3];
        $file   = implode('/', array_slice($segments, 4));

        return "https://raw.githubusercontent.com/{$owner}/{$repo}/{$branch}/{$file}";
    }

    private function extractFileContext(array $segments): array
    {
        if ($this->isRepositoryRoot($segments)) {
            return [$segments[0], $segments[1], 'main', ''];
        }

        $owner  = $segments[0];
        $repo   = $segments[1];
        $branch = $segments[3];

        $filePath = implode('/', array_slice($segments, 4));
        $directory = dirname($filePath);

        if ($directory === '.') {
            $directory = '';
        }

        return [$owner, $repo, $branch, $directory];
    }

    private function extractFolderContext(string $url): array
    {
        $segments = $this->parsePathSegments($url);

        if (
            count($segments) < 5 ||
            $segments[2] !== 'tree'
        ) {
            throw new InvalidArgumentException('Invalid GitHub folder URL.');
        }

        return [
            'owner'  => $segments[0],
            'repo'   => $segments[1],
            'branch' => $segments[3],
            'path'   => implode('/', array_slice($segments, 4)),
        ];
    }

    private function getFolderContents(string $owner, string $repo, string $branch, string $path): array
    {

        $url = "https://api.github.com/repos/$owner/$repo/contents/$path";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'Laravel'
        ])->get($url, [
            'ref' => $branch
        ]);

        if ($response->failed()) {
            throw new Exception('Unable to fetch folder.');
        }

        return $response->json();
    }

    private function getRepositoryTree(
        string $owner,
        string $repo,
        string $branch
    ): array {

        $url = "https://api.github.com/repos/{$owner}/{$repo}/git/trees/{$branch}";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'Laravel',
        ])->get($url, [
            'recursive' => 1
        ]);

        if ($response->failed()) {
            throw new \Exception('Unable to fetch repository tree.');
        }

        $tree = $response->json('tree');

        if (!$tree) {
            throw new \Exception('Repository tree is empty.');
        }

        return $tree;
    }

    private function filterTree(array $tree, string $rootPath): array
    {
        $rootPath = trim($rootPath, '/');

        return array_values(array_map(function ($item) use ($rootPath) {

            $item['relative_path'] = ltrim(
                substr($item['path'], strlen($rootPath)),
                '/'
            );

            return $item;
        }, array_filter($tree, function ($item) use ($rootPath) {

            return str_starts_with($item['path'], $rootPath . '/');
        })));
    }

    private function filterMarkdownTree(array $tree): array
    {
        return array_values(array_filter($tree, function ($item) {

            if ($item['type'] === 'tree') {
                return true;
            }

            return str_ends_with(
                strtolower($item['path']),
                '.md'
            );
        }));
    }

    private function buildTree(array $items): array
    {
        $tree = [];
        foreach ($items as $item) {

            $this->insertNode(
                $tree,
                explode('/', $item['relative_path']),
                $item
            );
        }

        $this->sortTree($tree);

        return array_values($tree);
    }

    private function insertNode(
        array &$nodes,
        array $parts,
        array $gitItem
    ): void {

        $current = array_shift($parts);

        if (!isset($nodes[$current])) {
            $name = empty($parts)
                ? pathinfo($current, PATHINFO_FILENAME)
                : $current;
            $nodes[$current] = [
                'name' => $name,
                'type' => empty($parts)
                    ? ($gitItem['type'] === 'tree'
                        ? 'folder'
                        : 'file')
                    : 'folder',
                'children' => []
            ];
        }

        if (empty($parts)) {

            if ($gitItem['type'] === 'blob') {

                $nodes[$current]['path'] = $gitItem['path'];
                $nodes[$current]['sha'] = $gitItem['sha'];
                $nodes[$current]['size'] = $gitItem['size'] ?? null;
            }

            return;
        }

        $this->insertNode(
            $nodes[$current]['children'],
            $parts,
            $gitItem
        );
    }

    private function sortTree(array &$nodes): void
    {
        uasort($nodes, function ($a, $b) {

            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'folder' ? -1 : 1;
            }

            return strcasecmp(
                $a['name'],
                $b['name']
            );
        });

        foreach ($nodes as &$node) {

            if (!empty($node['children'])) {
                $this->sortTree($node['children']);
            }
        }
    }

    private function createDocumentationTree(
        array $nodes,
        Documentation $documentation,
        DocumentationRelease $release,
        User $user,
        array $gitContext,
        ?int $parentId = null
    ): void {

        $sortOrder = 0;

        foreach ($nodes as $node) {

            $page = DocumentationPage::create([
                'user_id' => $user->id,
                'documentation_id' => $documentation->id,
                'release_id' => $release->id,
                'parent_id' => $parentId,
                'title' => $node['name'],
                'slug' => Str::slug($node['name']),
                'type' => $node['type'],
                'sort_order' => $sortOrder++,
                'git_link' => isset($node['path'])
                    ? $this->buildRawFileUrl(
                        $gitContext['owner'],
                        $gitContext['repo'],
                        $gitContext['branch'],
                        $node['path']
                    )
                    : null,
                'content_format' => 'markdown',
            ]);

            if ($node['type'] === 'file') {

                $markdown = $this->downloadMarkdown(
                    $gitContext['owner'],
                    $gitContext['repo'],
                    $gitContext['branch'],
                    $node['path']
                );

                $markdown = $this->convertImageUrls(
                    $markdown,
                    $gitContext['owner'],
                    $gitContext['repo'],
                    $gitContext['branch'],
                    dirname($node['path'])
                );

                $page->content = $markdown;

                $page->save();

                $this->generateSections($page);
            }

            if (!empty($node['children'])) {

                $this->createDocumentationTree(
                    array_values($node['children']),
                    $documentation,
                    $release,
                    $user,
                    $gitContext,
                    $page->id
                );
            }
        }
    }

    private function buildRawFileUrl(
        string $owner,
        string $repo,
        string $branch,
        string $path
    ): string {

        return sprintf(
            'https://raw.githubusercontent.com/%s/%s/%s/%s',
            $owner,
            $repo,
            $branch,
            ltrim($path, '/')
        );
    }

    private function downloadMarkdown(
        string $owner,
        string $repo,
        string $branch,
        string $path
    ): string {

        $url = $this->buildRawFileUrl(
            $owner,
            $repo,
            $branch,
            $path
        );

        // Log::info($url);
        $response = Http::timeout(30)->get($url);

        if ($response->failed()) {
            throw new Exception("Unable to download {$path}");
        }

        return $response->body();
    }

    private function importMarkdownContent(
        DocumentationPage $page,
        array $node,
        array $context
    ): void {

        if ($page->type === 'file') {

            $markdown = $this->downloadMarkdown(
                $context['owner'],
                $context['repo'],
                $context['branch'],
                $node['path']
            );

            $markdown = $this->convertImageUrls(
                $markdown,
                $context['owner'],
                $context['repo'],
                $context['branch'],
                dirname($node['path'])
            );

            $page->update([
                'content' => $markdown,
                'content_format' => 'markdown'
            ]);

            $this->generateSections($page);
        }

        $children = $page->children()->orderBy('sort_order')->get()->values();

        foreach ($children as $index => $child) {

            $childNode = array_values($node['children'])[$index];

            $this->importMarkdownContent(
                $child,
                $childNode,
                $context
            );
        }
    }

    private function generateSections(
        DocumentationPage $page
    ) {
        $page->sections()->delete();

        $markdown = $page->content;

        preg_match_all(
            '/^(#{1,6})\s+(.*)$/m',
            $markdown,
            $matches,
            PREG_OFFSET_CAPTURE
        );
    }
}
