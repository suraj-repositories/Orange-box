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
}
