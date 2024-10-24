<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;

class LoginController extends Controller
{
    // Redirect to Google for authentication
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback from Google
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        // Check if the user already exists in your database
        $existingUser = User::where('google_id', $user->getId())->first();

        if ($existingUser) {
            // Log in the user
            Auth::login($existingUser, true);
        } else {
            // Create a new user if not found
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
                // Add other fields as necessary
            ]);

            Auth::login($newUser, true);
        }

        // Redirect to a desired location after login
        return redirect()->intended('/home');
    }
}
