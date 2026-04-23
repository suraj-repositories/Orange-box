<?php

namespace App\Services\Impl;

use App\Services\MarkdownService;
use League\CommonMark\CommonMarkConverter;

class MarkdownServiceImpl implements MarkdownService
{
    public function toPlainText(string $markdown): string
    {
        $converter = new CommonMarkConverter();
        $html = $converter->convert($markdown)->getContent();
        return trim(strip_tags($html));
    }

    public  function extractHeadings($markdown)
    {
        preg_match_all('/^(#{1,6})\s+(.*)$/m', $markdown, $matches, PREG_SET_ORDER);

        $all = [];
        $h1 = null;
        $h2 = [];
        $h3 = [];

        foreach ($matches as $match) {
            $level = strlen($match[1]);
            $text = $this->cleanHeading($match[2]);

            if (!$text) continue;

            $all[] = $text;

            if ($level === 1 && !$h1) {
                $h1 = $text;
            }

            if ($level === 2) {
                $h2[] = $text;
            }

            if ($level === 3) {
                $h3[] = $text;
            }
        }

        return [
            'headings' => $all,
            'h1' => $h1,
            'h2' => $h2,
            'h3' => $h3,
        ];
    }

    private function cleanHeading($text)
    {
        $text = preg_replace('/[*_`]/', '', $text);
        $text = preg_replace('/[\x{1F000}-\x{1FFFF}]/u', '', $text);
        $text = preg_replace('/\s*\(.*?\)/', '', $text);
        $text = preg_replace('/^option\s*\d+\s*:\s*/i', '', $text);
        $text = str_replace(['&', '-'], ['and', ' '], $text);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        return $text;
    }
}
