<?php

namespace App\Http\Controllers;

use App\Models\Document;
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
            'user_id' => 'required|exists:users,id',
            'documents' => 'required|array', // Expecting an array of documents
            'documents.*.document_type' => 'required|exists:documents,id', // Validate document type ID
            'year_level' => 'required|string|max:10',
            'program' => 'required|string',
        ]);

        // Generate a unique queue number
        $queueNumber = strtoupper(Str::random(5));

        // Calculate the total amount from the selected documents' price
        $totalAmount = 0;

        foreach ($request->documents as $doc) {
            // Find the document by its ID (ensure this exists)
            $document = Document::findOrFail($doc['document_type']);

            // Add the document's price to the total amount
            $totalAmount += $document->price;
        }

        // Create a new document request with the total amount
        $documentRequest = DocumentRequest::create([
            'user_id' => $request->user_id,
            'year_level' => $request->year_level,
            'program' => $request->program,
            'status' => 'on_hold',
            'queue_number' => $queueNumber,
            'amount' => $totalAmount,  // Save the total amount
        ]);

        // Loop through each document in the request and save it to the pivot table
        foreach ($request->documents as $doc) {
            // Find the document by its ID (ensure this exists)
            $document = Document::findOrFail($doc['document_type']);

            // Attach the document to the document request (no quantity for now)
            $documentRequest->documents()->attach($document->id);
        }


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
