<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Collection;

interface SuggestionService
{
    function suggestUsername($username = ""): array;
}
