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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('user_id')->nullable(); // Optional if user is not always logged in
    $table->string('name');
    $table->string('contact');
    $table->string('email');
    $table->string('document_type');
    $table->string('year_level');
    $table->string('status')->default('on_hold');
    $table->string('queue_number')->nullable();
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
