<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Sample data for documents
        $documents = [
            [
                'document_name' => 'TOR',
                'description' => 'Transcript of Records',
                'price' => 500,
            ],
            [
                'document_name' => 'Card/Form 137',
                'description' => 'Student permanent record form',
                'price' => 300,
            ],
            [
                'document_name' => 'Form 138',
                'description' => 'Student grade report form',
                'price' => 200,
            ],
            [
                'document_name' => 'Good Moral',
                'description' => 'Certificate of Good Moral Character',
                'price' => 150,
            ],
            [
                'document_name' => 'COE',
                'description' => 'Certificate of Employment',
                'price' => 100,
            ],
        ];

        // Insert data into the documents table
        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
