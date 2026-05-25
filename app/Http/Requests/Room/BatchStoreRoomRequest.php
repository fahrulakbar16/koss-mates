<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class BatchStoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'count' => ['required', 'integer', 'min:1', 'max:50'],
            'start_number' => ['nullable', 'integer', 'min:1'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'prices' => ['nullable', 'array'],
            'prices.*.duration' => ['required_with:prices', 'integer', 'min:1'],
            'prices.*.price' => ['required_with:prices', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'count.required' => 'Jumlah kamar wajib diisi.',
            'count.integer' => 'Jumlah kamar harus berupa angka.',
            'count.min' => 'Minimal 1 kamar.',
            'count.max' => 'Maksimal 50 kamar sekaligus.',
            'start_number.integer' => 'Nomor awal harus berupa angka.',
            'capacity.integer' => 'Kapasitas harus berupa angka.',
            'capacity.min' => 'Kapasitas minimal 1.',
        ];
    }
}
