<?php

namespace App\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'middle_name' => ['nullable', 'string', 'max:120'],
            'birth_last_name' => ['nullable', 'string', 'max:120'],
            'gender' => ['required', 'in:male,female,unknown'],
            'life_status' => ['required', 'in:alive,deceased,unknown'],
            'birth_date_precision' => ['required', 'in:full,month_year,year,unknown'],
            'birth_date' => ['nullable', 'date'],
            'birth_year' => ['nullable', 'integer', 'min:1', 'max:'.date('Y')],
            'birth_month' => ['nullable', 'integer', 'between:1,12'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'death_date_precision' => ['required', 'in:full,month_year,year,unknown'],
            'death_date' => ['nullable', 'date'],
            'death_year' => ['nullable', 'integer', 'min:1', 'max:'.date('Y')],
            'death_month' => ['nullable', 'integer', 'between:1,12'],
            'death_place' => ['nullable', 'string', 'max:255'],
            'summary_note' => ['nullable', 'string', 'max:240'],
            'full_note' => ['nullable', 'string', 'max:3000'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }
}
