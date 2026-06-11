<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'message' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
            'message.min' => 'Please enter at least 10 characters in your message.',
        ]);

        $recipients = config('admin.emails') ?: [config('mail.from.address')];

        try {
            Mail::to($recipients)->send(new ContactMessage($validated));
        } catch (\Throwable $e) {
            Log::error('Failed to send contact form submission: ' . $e->getMessage(), [
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            return back()
                ->withInput()
                ->with('error', 'We could not send your message right now. Please try again or email us directly.');
        }

        return back()->with('success', 'Thank you for contacting us. We will get back to you soon.');
    }
}
