<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\DocumentationTemplate;
use App\Models\TemplateCart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Uri\UriTemplate\Template;

class DocumentationTemplateController extends Controller
{
    //

    public function show(User $user, DocumentationTemplate $template,  Request $request)
    {
        $title = $template->title;
        $template->loadCount('reviews');
        $template->loadAvg('reviews', 'rating');

        $isPurchased = false;
        if ($template->price ?? 0 > 0) {
            $isPurchased = $user->templatePurchases()
                ->where('documentation_template_id', $template->id)
                ->where('payment_status', 'paid')
                ->exists();
        }


        return view('user.documentation.template.show-template', compact('template', 'title', 'isPurchased'));
    }

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


            $template->preview_image_url = $template->preview_image_url;

            return $template;
        });

        return response()->json($templates);
    }

    public function viewTemplateLicence(DocumentationTemplate $template, Request $request)
    {
        $user = Auth::user();



        return view('user.documentation.template.template-licence');
    }

    public function templateCheckoutPage(User $user, Request $request)
    {
        $items = $user->templateCartItems()
            ->with('template')
            ->get();

        $subtotal = $items->sum('price');

        // Add tax, coupon, etc. later if needed
        $total = $subtotal;

        return view('user.documentation.template.template-checkout', compact('items', 'subtotal', 'total'));
    }

    public function addToCart(User $user, DocumentationTemplate $template, Request $request)
    {

        if ($template->price ?? 0 > 0) {
            $isPurchased = $user->templatePurchases()
                ->where('documentation_template_id', $template->id)
                ->where('payment_status', 'paid')
                ->exists();

            if ($isPurchased) {
                return redirect()->back()->with('error', 'Already Purchased!');
            }
        } else {
            return redirect()->back()->with('error', 'Template is Free!');
        }

        TemplateCart::firstOrCreate([
            'user_id' => $user->id,
            'documentation_template_id' => $template->id,
        ], [
            'price' => $template->price,
        ]);

        return redirect()->to(authRoute('user.template.checkout'))->with('template_title', $template->title);
    }


    public function removeFromCart(User $user, TemplateCart $cart, Request $request)
    {
        if ($cart->user_id !== $user->id) {
            abort(403);
        }

        $cart->delete();

        return redirect()
            ->back()
            ->with('success', 'Template removed from cart successfully.');
    }
}
