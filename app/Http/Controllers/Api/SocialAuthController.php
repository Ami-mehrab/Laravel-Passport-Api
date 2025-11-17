<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google callback
     */
    // *** THIS IS THE FIX ***
    public function handleGoogleCallback()
    {
        // !! THIS IS THE URL YOU REQUESTED !!
        // This works because your 'frontend' folder is inside 'public'
        $frontendUrl = 'http://localhost:8000/frontend'; 

        try {
            // Get user from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Find or create user
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update Google ID if it's not already set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->avatar = $user->avatar ?? $googleUser->getAvatar(); // Update avatar if it was missing
                    $user->save();
                }
            } else {
                // Create new user if they don't exist
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)), // Create a random password
                    'avatar' => $googleUser->getAvatar(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(), // Auto-verify email from Google
                ]);
            }

            // Generate an API token
            $token = $user->createToken('API Token')->accessToken;

            // Prepare user data to send to frontend
            $userData = urlencode(json_encode([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->google_id,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'email_verified_at' => $user->email_verified_at,
            ]));

            // Redirect to frontend callback page with the token and user data
            return redirect()->away(
                $frontendUrl . '/google-callback.html' .
                '?token=' . $token .
                '&user=' . $userData
            );

        } catch (\Exception $e) {
            // Log error if authentication fails
            Log::error('Google Auth Failed: ' . $e->getMessage());
            $errorMessage = urlencode('Authentication failed. Please try again.');
            
            // Redirect to frontend callback page with an error
            return redirect()->away(
                $frontendUrl . '/google-callback.html' .
                '?error=' . $errorMessage
            );
        }
    }

    
}