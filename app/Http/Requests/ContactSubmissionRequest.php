<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ContactSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:10',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (Str::contains($value, '@gmail.com')) {
                        $fail('Gmail addresses are not allowed.');
                    }
                },
            ],
            'phone' => 'required|regex:/^\+[1-9]\d{1,14}$/',
            'message' => 'required|min:10',
            'street' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg',
            'files.*' => 'mimes:pdf',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.min' => 'Your name must be at least 2 characters.',
            'name.max' => 'Your name cannot exceed 10 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid phone number with country code (e.g., +1234567890).',
            'message.required' => 'Please enter a message.',
            'message.min' => 'Your message must be at least 10 characters.',
            'street.required' => 'Please enter your street address.',
            'state.required' => 'Please enter your state.',
            'zip.required' => 'Please enter your ZIP code.',
            'country.required' => 'Please enter your country.',
            'images.*.image' => 'The uploaded file must be an image.',
            'images.*.mimes' => 'Only JPG images are allowed.',
            'files.*.mimes' => 'Only PDF files are allowed.',
        ];
    }
}
