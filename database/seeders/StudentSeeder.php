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
            // Create a user first
            $user = User::create([
                'name' => 'student', // Default name
                'email' => $faker->unique()->safeEmail, // Generate a random email address
                'password' => bcrypt('password'), // Default password
            ]);

            // Assign the Spatie role 'student' to the user
            $user->assignRole('student');

            // Create a corresponding student linked to the user
            Student::create([
                'user_id' => $user->id, // Link to the created user
                'student_number' => 'S' . str_pad($index, 3, '0', STR_PAD_LEFT), // Format the student number
                'firstname' => $faker->firstName, // Generate a random first name
                'middlename' => $faker->lastName, // Generate a random middle name
                'lastname' => $faker->lastName, // Generate a random last name
                'suffix' => $faker->randomElement(['', 'Jr.', 'Sr.', 'III']), // Random suffix or empty
                'contact_number' => $faker->phoneNumber, // Generate a random phone number
            ]);
        }
    }
}
