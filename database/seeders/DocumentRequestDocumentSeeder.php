<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentRequestDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get a few existing DocumentRequests and Documents (Adjust the counts as needed)
        $documentRequests = DocumentRequest::all(); // Get all document requests
        $documents = Document::all(); // Get all documents

        // If there are any document requests and documents
        if ($documentRequests->isNotEmpty() && $documents->isNotEmpty()) {
            foreach ($documentRequests as $documentRequest) {
                // Attach random documents to the document request
                $documentRequest->documents()->attach(
                    $documents->random(5)->pluck('id')->toArray() // Attach 5 random documents to each document request
                );
            }
        }
    }
}
