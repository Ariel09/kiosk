<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class RegistrarController extends Controller
{
    /**
     * Handle the document request submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestDocument(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'document_type' => 'required|string',
            'year_level' => 'required|string|max:10',
        ]);

        // Generate a unique queue number
        $queueNumber = strtoupper(Str::random(5));

        // Create a new document request
        $documentRequest = DocumentRequest::create([
            'user_id' => Auth::id(), // Assuming the user is logged in
            'document_type' => $request->document_type,
            'status' => 'on_hold',
            'queue_number' => $queueNumber,
        ]);

        // You can also send an email notification here if needed
        // Mail::to($request->email)->send(new DocumentRequestSubmitted($documentRequest));

        return response()->json([
            'message' => 'Request submitted successfully.',
            'queue_number' => $queueNumber,
        ]);
    }
}