<?php

namespace App\Services\Impl;

use App\Services\SuggestionService;

class SuggestionServiceImpl implements SuggestionService
{
    function suggestUsername($username = ""): array {
        return [
            'test','test2','test3'
        ];
    }
}
