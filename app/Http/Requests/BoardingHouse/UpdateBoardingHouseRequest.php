<?php

namespace App\Http\Requests\BoardingHouse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardingHouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in controller via Gate
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $boardingHouse = $this->route('boardingHouse');
        $cluster = $this->cluster_id ?? ($boardingHouse ? $boardingHouse->cluster_id : null);
        $ignoreId = is_object($boardingHouse) ? $boardingHouse->id : $boardingHouse;

        return [
            'owner_id' => ['required', 'exists:users,id'],
            'cluster_id' => ['nullable', 'exists:clusters,id'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'number' => [
                'required',
                'numeric',
                \Illuminate\Validation\Rule::unique('boarding_houses')->where(function ($query) use ($cluster) {
                    return $query->where('cluster_id', $cluster);
                })->ignore($ignoreId)
            ],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P,C'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'remove_thumbnail' => ['nullable', 'boolean'],
            'persentasi_pemilik' => ['required', 'integer', 'min:0', 'max:100'],
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
            'owner_id.required' => 'Pemilik wajib dipilih.',
            'owner_id.exists' => 'Pemilik yang dipilih tidak valid.',
            'cluster_id.exists' => 'Cluster yang dipilih tidak valid.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB.',
            'number.required' => 'Nomor kos wajib diisi.',
            'number.numeric' => 'Nomor kos harus berupa angka.',
            'number.unique' => 'Nomor kos ini sudah terdaftar di cluster yang dipilih.',
            'address.required' => 'Alamat wajib diisi.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'gender.required' => 'Gender wajib dipilih.',
            'gender.in' => 'Gender harus salah satu dari: L, P, C.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus antara -90 dan 90.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus antara -180 dan 180.',
        ];
    }
}
