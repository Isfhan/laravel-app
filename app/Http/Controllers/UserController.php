<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AgeChecker;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;


class UserController extends Controller
{
    protected $ageChecker;

    public function __construct(AgeChecker $ageChecker)
    {
        $this->ageChecker = $ageChecker;
    }


    public function register(RegisterUserRequest $request)
    {
        // Validate request data
        $validated = $request->validated();

        // Validate user age 
        $ageIsValid = $this->ageChecker->isAgeValid($request->input('dob'), 12);

        // Check if user age is not passing our criteria 
        if (!$ageIsValid) {

            // Send validation failed response to client
            return response()->json(
                [
                    'message' => 'Validation failed',
                    'errors' => [
                        'dob' => 'User must be at least greater than 12 years old to register'
                    ]
                ],
                422
            );
        }

        // Extract required data from request
        $data = $request->safe()->only(
            [
                'first_name',
                'last_name',
                'address',
                'dob',
                'email',
                'password',
                'interests'
            ]
        );

        // Hash the password
        $data['password'] = Hash::make($request->input('password'));

        // Save user in DB
        $user = User::create($data);

        // Send success response to client
        return response()->json(
            [
                'message' => 'User registered successfully',
                'user' => $user
            ],
            201
        );
    }
}
