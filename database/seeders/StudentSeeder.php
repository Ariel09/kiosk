<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create(); // Initialize Faker

        // Create 10 students with random data
        foreach (range(1, 10) as $index) {
            Student::create([
                'firstname' => $faker->firstName, // Generate a random first name
                'middlename' => $faker->lastName, // Generate a random middle name
                'lastname' => $faker->lastName, // Generate a random last name
                'suffix' => $faker->randomElement(['', 'Jr.', 'Sr.', 'III']), // Random suffix or empty
                'contact_number' => $faker->phoneNumber, // Generate a random phone number
                'email' => $faker->email, // Generate a random email address
                'password' => bcrypt('password'), // Default password
                'role' => 'student', // Set the role
                'student_number' => 'S' . str_pad($index, 3, '0', STR_PAD_LEFT), // Format the student number
            ]);
        }
    }
}
