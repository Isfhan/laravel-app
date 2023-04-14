<?php

namespace App\Http\Controllers;

use App\Models\User;

class VerificationController extends Controller
{
    public function verify($token)
    {
        // Find user by verification token
        $user = User::where('verification_token', $token)->first();

        // Check verification token exists in DB
        if (!$user) {

            // Send Login failed response to client
            return response()->json(
                [
                    'message' => 'Verification failed',
                    'errors' => 'Invalid verification token'
                ],
                404
            );
        }

        // Update user verification status
        $user->verified = true;

        // Clear verification token after successful verification
        $user->verification_token = null;

        // Save User in DB
        $user->save();

        // Send success response to client
        return response()->json(
            [
                'message' => 'User verified successfully',
                'errors' => null,
            ],
            200
        );
    }
}
