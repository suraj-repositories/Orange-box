<?php

namespace App\Services\Impl;

use App\Services\EditorJsService;

class EditorJsServiceImpl implements EditorJsService
{
    function jsonToPlainText($json, $size = 256)
    {
        $data = is_string($json) ? json_decode($json, true) : $json;
        if (!$data || empty($data['blocks'])) return '';

        $text = '';
        foreach ($data['blocks'] as $block) {
            switch ($block['type']) {
                case 'paragraph':
                case 'header':
                    $text .= ' ' . strip_tags($block['data']['text'] ?? '');
                    break;
                case 'list':
                    foreach ($block['data']['items'] ?? [] as $item) {
                        $text .= ' ' . strip_tags($item);
                        if (strlen($text) >= $size) break 2;
                    }
                    break;
                case 'quote':
                    $text .= ' ' . strip_tags($block['data']['text'] ?? '');
                    break;
                case 'code':
                    $text .= ' ' . strip_tags($block['data']['code'] ?? '');
                    break;
                case 'image':
                    $text .= ' ' . strip_tags($block['data']['caption'] ?? '[Image]');
                    break;
            }
            if (strlen($text) >= $size) break;
        }

        $plain = trim(preg_replace('/\s+/', ' ', $text));
        return mb_substr($plain, 0, $size);
    }
}
