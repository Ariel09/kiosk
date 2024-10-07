<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class RegistrarController extends Controller
{
    public function showKiosk()
    {
        return view('kiosk');
    }
    public function getLatestQueueNumber()
    {
        // Fetch the latest document request that has a queue number
        $latestRequest = DocumentRequest::whereNotNull('queue_number')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

        return response()->json([
            'queue_number' => $latestRequest ? $latestRequest->queue_number : 'No Queue',
        ]);
    }
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
            'user_id' => auth()->id(), // Optional, if user authentication is used
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'document_type' => $request->document_type,
            'year_level' => $request->year_level,
            'status' => 'on_hold',
            'queue_number' => $queueNumber,
        ]);

        try {
            // Printing the queue number
            $connector = new WindowsPrintConnector("TM-U220"); // Change to your printer connection type
            $printer = new Printer($connector);

            $printer->setTextSize(2, 2); // Set text size
            $printer->text("Queue Number:\n");
            $printer->text($queueNumber . "\n");
            $printer->feed(3); // Add a line break
            $printer->close(); // Close the printer connection

        } catch (\Exception $e) {
            Log::error('Error printing queue number: ' . $e->getMessage());
            // You can decide to return an error message or just log it
        }

        // Return a JSON response to the front-end
        return response()->json([
            'message' => 'Request submitted successfully.',
            'queue_number' => $queueNumber,
            'printing_status' => 'Attempted to print queue number.',
        ]);
    }
    public function getWaitingList()
    {
        // Fetch the latest 10 queue numbers that are in 'on_hold' or 'processing' status
        $waitingList = DocumentRequest::whereNotNull('queue_number')
                                       ->where('status', 'on_hold')
                                       ->orderBy('created_at', 'desc')
                                       ->limit(10)
                                       ->pluck('queue_number');

        return response()->json([
            'queue_numbers' => $waitingList,
        ]);
    }

}
