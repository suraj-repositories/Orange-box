<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    //
    public function index()
    {
        $faqs = Faq::active()->ordered()->paginate(30);

        $collection = $faqs->getCollection();

        $half = ceil($collection->count() / 2);

        $leftFaqs = $collection->slice(0, $half);
        $rightFaqs = $collection->slice($half);

        return view('user.faq.show-faqs', compact('faqs', 'leftFaqs', 'rightFaqs'));
    }
}
