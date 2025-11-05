<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    private FileService $fileService;
    public function __construct(FileService $fileService){
        $this->fileService = $fileService;
    }

    //
    public function testing(){
       dd("on test");
    }

}
