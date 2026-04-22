<?php

namespace App\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return ['title' => ['required', 'string', 'max:180'], 'description' => ['nullable', 'string', 'max:2000']];
    }
}
