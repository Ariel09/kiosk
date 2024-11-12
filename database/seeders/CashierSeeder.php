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
        // Create 5 cashier records, assuming there are users already created
        foreach (range(1, 5) as $index) {
            Cashier::create([
                'user_id' => User::inRandomOrder()->first()->id, // Assign a random user to the cashier
                'program' => 'Program ' . $index, // Or any other logic to assign program
                'year_level' => 'Year Level ' . $index, // You can customize this based on your use case
                'status' => 'on_hold', // Default status
                'queue_number' => 'Q' . str_pad($index, 3, '0', STR_PAD_LEFT),
                'amount' => rand(100, 500), // Random amount for the demo
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'remarks' => 'Remarks for record ' . $index,
            ]);
        }
    }
}
