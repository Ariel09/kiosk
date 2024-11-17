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
            // Remove the `document_id` column if it exists
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');

            // Add the `program` column
            $table->string('program')->after('year_level')->nullable();
            $table->text('remarks')->after('program')->nullable();

            // Remove the `name`, `contact`, and `email` columns
            $table->dropColumn(['name', 'contact', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_requests', function (Blueprint $table) {
            // Restore the `document_id` column with foreign key constraint
            $table->unsignedBigInteger('document_id')->nullable()->after('id');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

            // Restore the `name`, `contact`, and `email` columns
            $table->string('name')->after('user_id');
            $table->string('contact')->after('name');
            $table->string('email')->after('contact');

            // Remove the `program` column
            $table->dropColumn('program');
        });
    }
};
