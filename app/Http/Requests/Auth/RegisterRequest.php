<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Autorizar el request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'type'     => ['required', 'in:client,company'], // nuevo campo
            
            'nit'     => ['required_if:type,company', 'string', 'max:50', 'unique:companies,nit'],
            'phone'   => ['required_if:type,company', 'string', 'max:50'],
            'address' => ['required_if:type,company', 'string', 'max:255'],

            'company_name' => ['required_if:type,company', 'string', 'max:255'],
            'company_email' => ['required_if:type,company', 'string', 'email', 'max:255'],
        ];
    }
}
