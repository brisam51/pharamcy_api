<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePharamcyRequest extends FormRequest
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
           "name" => "sometimes|required|string|max:255",
            //"national_id" => "sometimes|required",
           // "address" => "sometimes|required|string|max:255",
            //"email" => "sometimes|required|email|unique:pharamcies,email," . $this->route('pharamcy')->id . "|max:255",
           // "logo" => "sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
           // "subscription_start_date" => "sometimes|required|date",
           // "subscription_end_date" => "sometimes|required|date|after:subscription_start_date",
           // "status" => "sometimes|required|in:active,inactive",
           // "user_id" => "sometimes|required|exists:users,id",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "The name field is required.",
            // "national_id.required" => "The national ID field is required.",
            // "address.required" => "The address field is required.",
            // "email.required" => "The email field is required.",
            // "email.email" => "The email field must be a valid email address.",
            // "email.max" => "The email field must not exceed 255 characters.",
            // "logo.image" => "The logo field must be an image.",
            // "logo.mimes" => "The logo field must be a file of type: jpeg, png, jpg, gif.",
            // "logo.max" => "The logo field must not exceed 2048 kilobytes.",
            // "subscription_start_date.required" => "The subscription start date field is required.",
            // "subscription_start_date.date" => "The subscription start date field must be a valid date.",
            // "subscription_end_date.required" => "The subscription end date field is required.",
            // "subscription_end_date.date" => "The subscription end date field must be a valid date.",
            // "subscription_end_date.after" => "The subscription end date must be after the subscription start date.",
            // "status.required" => "The status field is required.",
            // "status.in" => "The selected status is invalid.",
            // "user_id.required" => "The user ID field is required.",
            // "user_id.exists" => "The selected user ID is invalid.",
            // "user_id.unique" => "The selected user ID has already been taken.",
        ];
    }
}
