<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                Rule::unique('events')->where(function ($query) {
                    return $query
                        ->where('start_time', $this->start_time)
                        ->where('location', $this->location);
                }),
            ],
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'capacity' => 'required|integer',
            'location' => 'required|string',
            'type' => 'required|in:online,in-person',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'venue_name' => 'nullable|string',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'price_type' => 'required|in:free,paid',
            'price' => 'required_if:price_type,paid|numeric|min:0',
            'latitude' => 'required_if:type,in-person|nullable|numeric',
            'longitude' => 'required_if:type,in-person|nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required',
            'title.unique' => 'This event already exists with same time and location',
            'category.required' => 'Category is required',
            'capacity.required' => 'Capacity is required',
            'type.required' => 'Event type is required',
            'start_time.required' => 'Start time is required',
            'start_time.after_or_equal' => 'Start time must be now or in the future',
            'end_time.required' => 'End time is required',
            'end_time.after' => 'End time must be after start time',
            'price.required_if' => 'Price is required for paid events',
            'image.image' => 'Uploaded file must be an image',
            'image.mimes' => 'Image must be JPG, JPEG, or PNG file',
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
