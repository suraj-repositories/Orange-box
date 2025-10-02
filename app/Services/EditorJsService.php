<?php

namespace App\Services;

interface EditorJsService
{
    function jsonToPlainText($json, $size = 256);

}
