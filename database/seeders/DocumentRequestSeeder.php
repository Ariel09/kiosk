<?php

namespace Database\Seeders;

use App\Models\DocumentRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of document types
        $documentTypes = ['Diploma', 'Card/Form 137', 'Good Moral', 'TOR'];
        
        // Array of statuses
        $statuses = ['on_hold', 'paid', 'released'];

        // Number of requests to seed
        $numberOfRequests = 40; // You can adjust this number

        // Loop through and create document requests
        for ($i = 0; $i < $numberOfRequests; $i++) {
            // Select a random document type
            $documentType = $documentTypes[array_rand($documentTypes)];

            // Select a random status
            $status = $statuses[array_rand($statuses)];

            // Get the current count of requests for the same document type
            $currentCount = DocumentRequest::where('document_type', $documentType)->count();

            // Create a new queue number based on the current count
            $queueNumber = $documentType . '-' . ($currentCount + 1);

            // Seed the document request
            DocumentRequest::create([
                'user_id' => null, // or use a random user ID
                'name' => 'User ' . ($i + 1),
                'contact' => '0912345678' . $i,
                'email' => 'user' . ($i + 1) . '@example.com',
                'document_type' => $documentType,
                'year_level' => 'Year ' . rand(1, 4),
                'status' => $status,
                'queue_number' => $queueNumber,
            ]);
        }
    }
}
