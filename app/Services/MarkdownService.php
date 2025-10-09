<?php

namespace App\Services;

interface MarkdownService
{
    function toPlainText(string $markdown): string;

}
