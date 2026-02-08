<?php

namespace App\Services\Impl;

use App\Services\GitService;

class GitServiceImpl implements GitService{

    public function loadGitPageContent($link){
            return '
                <h1>Hello hi bye</h1>
            ';
    }
}
