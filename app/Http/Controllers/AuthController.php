<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'student_number' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find the student by the provided student number
        $student = Student::where('student_number', $request->student_number)->first();

        if (!$student) {
            return back()->withErrors([
                'student_number' => 'Invalid student number.',
            ])->withInput();
        }

        // Get the associated user
        $user = $student->user;

        // Check if the user exists and the password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Log the user in
            Auth::login($user);

            // Redirect to the intended page or dashboard
            return redirect()->intended('/');
        }

        // If the credentials don't match, show an error
        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout(); // Logs out the user
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
