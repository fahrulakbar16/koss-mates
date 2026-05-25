<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;

class DeleteExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $boardingHouse = $this->route('boardingHouse') ?? $this->route('boarding_house');
        if (!$boardingHouse && $this->route('boardingHouseId')) {
            $boardingHouse = \App\Models\BoardingHouse::find($this->route('boardingHouseId'));
        }

        $expense = $this->route('expense');
        if (!$expense && $this->route('id')) {
            $expense = \App\Models\Expense::find($this->route('id'));
        }

        if ($expense && $boardingHouse) {
            return $expense->boarding_house_id === $boardingHouse->id;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
