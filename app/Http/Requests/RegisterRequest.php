<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'password' => 'nullable|string|min:8|confirmed',
            'job_title' => 'nullable|string',
            'company_name' => 'nullable|string',
            'company_number' => 'nullable|string',
            'specialty' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'full_mailing_address' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'linkedin' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'topic_id' => 'nullable|exists:topics,id',
            'cv' => 'nullable|file|mimes:pdf|max:5120',
            'role' => 'required|in:attendee,organizer,sponsor,speaker,company',
        ];
    }
     public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is already registered',
            'phone.nullable' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered',
            'password.confirmed' => 'Password confirmation does not match',
            'job_title.nullable' => 'Job title is not valid',
            'company_name.nullable' => 'Company name is not valid',
            'company_number.nullable' => 'Company number is not valid',
            'specialty.nullable' => 'Specialty is not valid',
            'country.nullable' => 'Country is not valid',
            'city.nullable' => 'City is not valid',
            'address.nullable' => 'Address is not valid',
            'linkedin.nullable' => 'LinkedIn profile is not valid',
            'role.required' => 'Role is required'
                    ];

    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }

}
