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
        Schema::table('document_requests', function (Blueprint $table) {
            // Create the `document_id` field as a foreign key
            $table->unsignedBigInteger('document_id')->after('id')->nullable();

            // Set the foreign key constraint to reference the `id` column in the `documents` table
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->nullable()->after('queue_number');
            $table->date('payment_date')->nullable()->after('amount');
            // Optionally drop the old `document_type` column if it's no longer needed
            $table->dropColumn('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            // Roll back changes: Drop foreign key and remove `document_id` field
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');
            $table->dropColumn('amount');
            $table->dropColumn('payment_date');

            // Optionally restore the old `document_type` column if needed
            $table->string('document_type')->nullable();
        });
    }
};
