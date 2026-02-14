<?php

namespace App\Services\Impl;

use App\Services\GitService;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class GitServiceImpl implements GitService
{

    public function loadGitPageContent($link)
    {
        return $this->getRawContent($link);
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

        if ($host !== 'github.com') {
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
}
