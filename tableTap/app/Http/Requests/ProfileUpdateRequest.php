<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ensure only authorized users can update their profiles
    }

    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:owners,username,' . Auth::id()],
            'email' => ['nullable', 'email', 'max:255', 'unique:owners,email,' . Auth::id()],
            'storeName' => ['nullable', 'string', 'max:255'],
            'storeType' => ['nullable', 'in:Restaurant,Cafe,Tavern,Grill,Kebab,Bar,Other'],
            'address' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'], // Only letters and spaces
            'city' => ['nullable', 'string', 'regex:/^[a-zA-Z\s]+$/', 'max:255'], // Only letters and spaces
            'postalCode' => ['nullable', 'regex:/^[0-9]{5}$/'], // 5-digit postal code
            'phone' => ['nullable', 'regex:/^\d{10}$/'], // 10-digit phone number
        ];
    }
}
