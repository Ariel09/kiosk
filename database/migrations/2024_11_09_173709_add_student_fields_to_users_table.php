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
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_number')->nullable()->unique()->after('id');
            $table->string('firstname')->after('student_number');
            $table->string('middlename')->nullable()->after('firstname');
            $table->string('lastname')->after('middlename');
            $table->string('suffix')->nullable()->after('lastname');
            $table->string('contact_number')->nullable()->after('suffix');
            $table->renameColumn('name', 'role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_number', 'firstname', 'middlename', 'lastname', 'suffix', 'contact_number']);
            $table->string('name')->after('id');
        });
    }
};
