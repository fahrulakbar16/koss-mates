<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User can only update their own profile (handled in controller)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user?->id)],
            'profile_photo_url' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'delete_photo' => ['required', 'in:true,false,1,0'],
            // Tenant fields
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'id_card_number' => ['required', 'string', 'max:50'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female'],
            'emergency_contact' => ['required', 'string', 'max:20'],
            'is_moved' => ['sometimes','required', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.email' => 'Email harus berupa format email yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'profile_photo_url.image' => 'Foto profil harus berupa gambar.',
            'profile_photo_url.mimes' => 'Foto profil harus berformat: jpeg, jpg, atau png.',
            'profile_photo_url.max' => 'Foto profil maksimal 2MB.',
            'delete_photo.in' => 'Nilai delete_photo tidak valid.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'id_card_number.max' => 'Nomor KTP maksimal 50 karakter.',
            'birth_date.date' => 'Tanggal lahir harus berupa format tanggal yang valid.',
            'gender.in' => 'Jenis kelamin harus male atau female.',
            'emergency_contact.max' => 'Kontak darurat maksimal 20 karakter.',
        ];
    }
}

