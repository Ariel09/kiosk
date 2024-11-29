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
        Schema::table('document_request_document', function (Blueprint $table) {
            $table->integer('quantity')->after('document_id')->nullable(); // Add quantity field
            $table->decimal('price', 10, 2)->after('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_request_document', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'price']); // Remove added columns
        });
    }
};
