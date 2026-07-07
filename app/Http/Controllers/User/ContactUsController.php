<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactUsController extends Controller
{

 public function __construct(public FileService $fileService)
    {

    }
    //
    public function index()
    {
        return view('user.contact-us.contact-us-form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'], // 10MB
        ]);

        DB::beginTransaction();

        try {

            $attachment = null;

            if ($request->hasFile('attachment')) {
                $attachment = $this->fileService->uploadFile($request->file('attachment'), 'contact-us', 'public');
            }

            ContactMessage::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'subject'    => $request->subject,
                'category'   => $request->category,
                'message'    => $request->message,
                'attachment' => $attachment,
            ]);

            DB::commit();

            return back()->with([
                'success' => 'Your message has been sent successfully. We will get back to you soon.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withInput()->with([
                'error' => 'Something went wrong. Please try again.',
            ]);
        }
    }
}
