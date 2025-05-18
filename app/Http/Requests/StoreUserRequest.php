<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|max:255|unique:users',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'medical_council_id' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password|min:8',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required',
            'national_id.required' => 'National ID is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'status.required' => 'Status is required',
            'password_confirmation.required_with' => 'Password confirmation is required',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw (new \Illuminate\Validation\ValidationException($validator))
            ->errorBag($this->errorBag)->redirectTo($this->getRedirectUrl());
    }
}
