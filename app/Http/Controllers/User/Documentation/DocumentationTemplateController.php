<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\DocumentationTemplate;
use App\Models\TemplateCart;
use App\Models\TemplatePurchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class DocumentationTemplateController extends Controller
{
    //
    public function index(User $user, Request $request)
    {
        $type = $request->get('type', 'top');

        $query = DocumentationTemplate::query()
            ->withCount('reviews')
            ->withCount([
                'purchases as purchases_count' => function ($query) {
                    $query->where('payment_status', 'paid');
                }
            ])
            ->withAvg('reviews', 'rating')
            ->where('is_active', true);

        match ($type) {
            'popular' => $query
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count'),

            'free' => $query
                ->where(function ($query) {
                    $query->where('price', 0)
                        ->orWhereNull('price');
                })
                ->orderBy('sort_order'),

            'premium' => $query
                ->where('price', '>', 0)
                ->orderByDesc('price'),

            'my' => $query
                ->whereHas('purchases', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->where('payment_status', 'paid');
                }),

            'purchased' => $query
                ->has('purchases')
                ->orderByDesc('purchases_count'),

            default => $query
                ->orderByDesc('reviews_count')
                ->orderByDesc('purchases_count'),
        };

        $templates = $query->paginate(12)->withQueryString();
        $title = ucfirst($type) . ' Templates';
        return view('user.documentation.template.templates-list', compact(
            'templates',
            'type',
            'title'
        ));
    }


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

        $template->loadCount('purchases');
        $template->loadCount('documentations');

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

    public function viewTemplateLicence(User $user, DocumentationTemplate $template, Request $request)
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

        return redirect()->to(authRoute('user.template.checkout.page'))->with('template_title', $template->title);
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

    public function checkout(User $user)
    {
        $items = TemplateCart::with('template')
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($items as $item) {

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->template->title,
                    ],
                    'unit_amount' => (int) ($item->price * 100),
                ],
                'quantity' => 1,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',

            'success_url' => route('template.checkout.success', [
                'user' => $user->username,
            ]) . '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => route('template.checkout.cancel', [
                'user' => $user->username,
            ]),

            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(User $user, Request $request)
    {
        $sessionId = $request->session_id;

        if (!$sessionId) {
            return redirect()->to(authRoute('user.dashboard'));
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return redirect()
                ->to(authRoute('user.dashboard'))
                ->with('error', 'Payment not completed.');
        }

        $cartItems = TemplateCart::with('template')
            ->where('user_id', $user->id)
            ->get();

        foreach ($cartItems as $item) {

            TemplatePurchase::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'documentation_template_id' => $item->documentation_template_id,
                ],
                [
                    'price' => $item->price,
                    'transaction_id' => $sessionId,
                    'payment_status' => 'paid',
                    'purchased_at' => Carbon::now(),
                ]
            );
        }

        TemplateCart::where('user_id', $user->id)->delete();

        return redirect()
            ->to(authRoute('user.templates.index', ['type' => 'my']))
            ->with('success', 'Templates purchased successfully.');
    }

    public function cancel(User $user)
    {
        return redirect()
            ->to(authRoute('user.template.checkout.page'))
            ->with('error', 'Payment cancelled.');
    }
}
