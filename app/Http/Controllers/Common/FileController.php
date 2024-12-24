<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\File;

class FileController extends Controller
{

    public function destroy(File $file){
        $file->delete();
        return redirect()->back()->with('success', 'File Deleted Successfully!');
    }

}
