<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePharamcyRequest extends FormRequest
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
            "name"=> "required|string|max:255",
            "national_id"=> "required",
            "address"=> "required|string|max:255",
            "phone"=> "required",    
            "email"=> "required|email|unique:pharamcies|max:255",
            "logo"=> "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            "subscription_start_date"=> "required|date",
            "subscription_end_date"=> "required|date|after:subscription_start_date",
            "status"=> "required|in:active,inactive",
            "user_id"=> "required|exists:users,id",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "The name field is required.",
            "name.string" => "The name field must be a string.",
            "name.max" => "The name field must not exceed 255 characters.",
            "national_id.required" => "The national ID field is required.",
           // "national_id.string" => "The national ID field must be a string.",
           // "national_id.max" => "The national ID field must not exceed 255 characters.",
            "address.required" => "The address field is required.",
            "address.string" => "The address field must be a string.",
            "address.max" => "The address field must not exceed 255 characters.",
            "phone.required" => "The phone field is required.",
           // "phone.string" => "The phone field must be a string.",
           // "phone.max" => "The phone field must not exceed 255 characters.",
            "email.required" => "The email field is required.",
            "email.email" => "The email field must be a valid email address.",
            "email.max" => "The email field must not exceed 255 characters.",
            "logo.image" => "The logo field must be an image.",
            "logo.mimes" => "The logo field must be a file of type: jpeg, png, jpg, gif.",
            "logo.max" => "The logo field must not exceed 2048 kilobytes.",
            "subscription_start_date.required" => "The subscription start date field is required.",
            "subscription_start_date.date" => "The subscription start date field must be a valid date.",
            "subscription_end_date.required" => "The subscription end date field is required.",
            "subscription_end_date.date" => "The subscription end date field must be a valid date.",
            "subscription_end_date.after" => "The subscription end date must be a date after the subscription start date.",
            "status.required" => "The status field is required.",
            "status.in" => "The status field must be either active or inactive.",
            "user_id.required" => "The user ID field is required.",
           // "user_id.exists" => "The selected user ID is invalid.",
            "user_id.integer" => "The user ID field must be an integer.",
            "user_id.exists" => "The selected user ID does not exist in the users table.",

        ];
    }
}
