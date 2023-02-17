<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'panvat' => 'nullable|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_photo' => 'nullable|image',
            'profile_photo' => 'nullable|image',
            'office_number' => 'required|string|max:255',
            'password' => 'required|string|same:confirm_password|min:8',
            'confirm_password' => 'required|string|min:8',
            'role' => 'required|string|max:255|in:wholeseller,distributor',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
            'status' => false,
        ], 422);

        throw new HttpResponseException($response);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'phone.required' => 'Phone is required',
            'address.required' => 'Address is required',
            'email.required' => 'Email is required',
            'panvat.required' => 'Panvat is required',
            'organization_name.required' => 'Organization name is required',
            'organization_photo.required' => 'Organization photo is required',
            'profile_photo.required' => 'Profile photo is required',
            'office_number.required' => 'Office number is required',
            'password.required' => 'Password is required',
            'confirm_password.required' => 'Confirm password is required',
        ];
    }
}
