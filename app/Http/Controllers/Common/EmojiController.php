<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Emoji;
use App\Models\EmojiCategory;
use Illuminate\Http\Request;

class EmojiController extends Controller
{
    //
    public function getEmojis()
    {
        $emojis = EmojiCategory::with('emojis')->get();

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
