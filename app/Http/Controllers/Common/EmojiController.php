<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmojiController extends Controller
{
    //
    public function getEmojis()
    {
        $emojis = config('emojis');
        if (empty($emojis)) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch data!'
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $emojis
        ]);
    }
}
