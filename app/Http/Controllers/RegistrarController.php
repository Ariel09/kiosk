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
            // Create a connection to the printer
            $connector = new WindowsPrintConnector("TM-U220"); // Change to your printer connection type
            $printer = new Printer($connector);

            // Center the header text
            $printer->setJustification(Printer::JUSTIFY_CENTER); // Center the text

            // Print the school title as header
            $printer->setEmphasis(true); // Bold the text
            $printer->text("Saint Ignatius Academy\n");
            $printer->setEmphasis(false); // Turn off bold
            $printer->text("est. 2013\n\n"); // Add the establishment year with a line break

            // Print the title "Queue Number"
            $printer->setEmphasis(true); // Bold the text
            $printer->text("Queue Number:\n");
            $printer->setEmphasis(false); // Turn off bold

            // Set text to double-width and double-height (largest available size for many printers)
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);

            // Format the current date and time
            $currentDateTime = now()->format('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS
            $formattedQueueNumber = implode(' ', str_split($queueNumber)); // Adds space between characters

            // Print the queue number and the current date/time
            $printer->text($formattedQueueNumber . "\n");

            // Reset text size to normal for the date and time
            $printer->selectPrintMode();
            $printer->setJustification(Printer::JUSTIFY_CENTER); // Center the date/time
            $printer->text("Date & Time: " . $currentDateTime . "\n");

            // Add extra line feeds for spacing
            $printer->feed(3); // Adds 3 line breaks before cutting

            // Cut the receipt
            $printer->cut();

            // Close the printer connection
            $printer->close();

               } catch (\Exception $e) {
                   Log::error('Error printing queue number: ' . $e->getMessage());
                   // Optionally return an error response or continue without printing
               }

               return response()->json([
                   'message' => 'Request submitted successfully.',
                   'queue_number' => $queueNumber,
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