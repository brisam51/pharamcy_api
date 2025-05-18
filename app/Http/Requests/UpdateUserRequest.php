<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user');
        return [
            'full_name' => 'sometimes|required|string|max:255',
            'national_id' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'medical_council_id' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'contract_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],

            'status' => [
                'sometimes',
                'required',
                Rule::in(['active', 'inactive']),
            ],
            'address' => 'sometimes|nullable|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'sometimes|required|string|min:8|confirmed',
            'password_confirmation' => 'sometimes|required_with:password|same:password|min:8',
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
            'photo.image' => 'Photo must be an image',
            'photo.mimes' => 'Photo must be a file of type: jpeg, png, jpg, gif',
            'photo.max' => 'Photo may not be greater than 2MB',

        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw (new \Illuminate\Validation\ValidationException($validator))
            ->errorBag($this->errorBag)->redirectTo($this->getRedirectUrl());
    }

    protected function prepareForValidation()
    {
        // Get all input except files
        $inputs = collect($this->except(array_keys($this->allFiles())))
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })->toArray();

        // Merge with files to maintain file uploads
        $this->replace(array_merge($inputs, $this->allFiles()));
    }
}
