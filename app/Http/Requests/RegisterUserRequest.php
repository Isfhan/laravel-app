<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'dob' => 'required|date_format:m/d/Y',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'interests' => 'required|array',
            'interests.*' => 'string'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {

            throw new HttpResponseException(
                response()->json(
                    [
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ],
                    422
                )
            );
        }
    }
}
