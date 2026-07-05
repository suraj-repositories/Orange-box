<?php

namespace App\Services;

interface MarkdownService
{
    function toPlainText(string $markdown): string;

    function extractHeadings($markdown);

    public function render(?string $content): string;
}
