<?php

namespace App\Http\Controllers\Admin\Docs;

use App\Http\Controllers\Controller;
use App\Models\TemplatePurchase;
use Illuminate\Http\Request;

class TemplatePurchaseController extends Controller
{
    //
    public function index(Request $request)
    {
        $hasFilter = collect(['search', 'from_date', 'to_date'])
            ->some(fn($field) => $request->filled($field));

        $transactions = TemplatePurchase::with(['template', 'user.details'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('payment_status', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%")

                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhereHas('details', function ($details) use ($search) {
                                    $details->whereRaw(
                                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                                        ["%{$search}%"]
                                    )
                                        ->orWhere('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%")
                                        ->orWhere('contact', 'like', "%{$search}%");
                                });
                        })

                        ->orWhereHas('template', function ($template) use ($search) {
                            $template->where('title', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('purchased_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('purchased_at', '<=', $request->to_date);
            })
            ->latest('purchased_at')
            ->paginate(10)
            ->withQueryString();

        $title = "Template Purchases";
        return view('admin.docs.template-purchase.purchase-transactions', compact('hasFilter', 'transactions', 'title'));
    }
}
