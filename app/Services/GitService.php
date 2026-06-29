<?php

namespace App\Services;

use App\Models\Documentation;
use App\Models\DocumentationRelease;
use App\Models\User;

interface GitService
{

    function loadGitPageContent($link);

    function loadEntireDocumentation(
        string $githubUrl,
        Documentation $documentation,
        DocumentationRelease $release,
        User $user
    );
}
