<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    //
    public function __construct(public FileService $fileService) {}


    public function index(Request $request)
    {

        $hasFilter = collect(['search', 'from_date', 'to_date'])
            ->some(fn($field) => $request->filled($field));

        $contactMessages = ContactMessage::when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        })->when($request->filled('from_date'), function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->from_date);
        })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->latest()
            ->paginate(10)->withQueryString();

        $title = "Contact Messages";

        return view('admin.contact.contact-messages-list', compact('title', 'contactMessages', 'hasFilter'));
    }


    public function updateStatus(Request $request, ContactMessage $contact)
    {
        $validator = Validator::make($request->all(), [
            'status' => [
                'required',
                Rule::in([
                    ContactMessage::STATUS_PENDING,
                    ContactMessage::STATUS_READ,
                    ContactMessage::STATUS_REPLIED,
                    ContactMessage::STATUS_CLOSED,
                ]),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'status' => $contact->status
            ]);
        }

        $contact->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'status' => $contact->status
        ]);
    }

    public function destroy(ContactMessage $contact)
    {
        if (!empty($contact->attachment)) {
            $this->fileService->deleteIfExists($contact->attachment);
        }

        $contact->delete();

        return redirect()->back()->with('success', 'Contact message deleted successfully!');
    }
}
