<?php

namespace Database\Seeders;

use App\Models\Cashier;
use App\Models\DocumentRequest;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get users with the role 'student'
        $studentUsers = User::role('student')->get();

        if ($studentUsers->isEmpty()) {
            $this->command->warn('No users with the role of student found. Seeder skipped.');
            return;
        }

        // Create 5 cashier records
        foreach (range(1, 5) as $index) {
            Cashier::create([
                'user_id' => $studentUsers->random()->id, // Assign a random student user
                'program' => 'Program ' . $index, // Custom program logic
                'year_level' => 'Year Level ' . $index, // Custom year level logic
                'status' => 'on_hold', // Default status
                'queue_number' => 'Q' . str_pad($index, 3, '0', STR_PAD_LEFT),
                'amount' => rand(100, 500), // Random amount for demo
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'remarks' => 'Remarks for record ' . $index,
            ]);
        }
    }
}
