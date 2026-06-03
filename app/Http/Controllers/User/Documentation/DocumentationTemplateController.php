<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\DocumentationTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentationTemplateController extends Controller
{
    //

    public function get(User $user, Request $request)
    {
        $type = $request->type ?? 'free';
        $perPage = $request->per_page ?? 9;

        $query = DocumentationTemplate::query()
            ->where('is_active', 1);

        if ($type === 'free') {
            $query->where(function ($q) {
                $q->where('price', 0)
                    ->orWhereNull('price');
            });
        }

        if ($type === 'premium') {
            $query->where('price', '>', 0);
        }

        if ($type === 'my') {
            $query->whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('payment_status', 'paid');
            });
        }

        $templates = $query
            ->with(['purchases' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('payment_status', 'paid');
            }])
            ->orderBy('sort_order')
            ->paginate($perPage);

        $templates->getCollection()->transform(function ($template) {

            $template->is_selectable =
                ($template->price == 0 || $template->price === null)
                || $template->purchases->isNotEmpty();

            return $template;
        });

        return response()->json($templates);
    }
}
