<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_request_document', function (Blueprint $table) {
            $table->id();

            // Foreign keys for document_request and document
            $table->foreignId('document_request_id')->constrained('document_requests')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_request_document');
    }
};
