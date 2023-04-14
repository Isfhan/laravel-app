<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\AgeChecker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
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

        // Generate verification token
        $data['verification_token'] = Str::random(64);

        // Save user in DB
        $user = User::create($data);

        // Send success response to client
        return response()->json(
            [
                'message' => 'User registered successfully',
                'user' => $user,
            ],
            201
        );
    }


    public function login(LoginUserRequest $request)
    {
        // Attempt to authenticate user
        if (Auth::attempt($request->only('email', 'password'))) {

            // Get User
            $user = Auth::user();

            // Check User is not verified
            if (!$user->verified) {

                // Send Login failed response to client
                return response()->json(
                    [
                        'message' => 'Login failed',
                        'errors' => 'User is not verified'
                    ],
                    401
                );
            }

            
            $token = $user->createToken('auth-token')->plainTextToken;

            // Send success response to client
            return response()->json(
                [
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ],
                200
            );
        } else {

            // Send Login failed response to client
            return response()->json(
                [
                    'message' => 'Login failed',
                    'errors' => 'Incorrect Email or Password'
                ],
                401
            );
        }
    }


    public function profile(Request $request)
    {
        // Retrieve authenticated user
        $user = $request->user();

        // Send success response to client
        return response()->json(
            [
                'message' => '',
                'user' => $user,
            ],
            200
        );

    }
}
