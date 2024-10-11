<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Mail::raw($request->body, function ($message) use ($request) {
            $message->to($request->to)
                ->subject($request->subject);
        });

        return redirect()->back()->with('success', 'Email sent successfully!');
    }
}