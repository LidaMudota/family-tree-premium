<?php

namespace App\Http\Requests\Tree;

use Illuminate\Foundation\Http\FormRequest;

class StoreRelationshipRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'person_id' => ['required', 'integer', 'exists:people,id'],
            'relative_id' => ['required', 'integer', 'exists:people,id'],
            'type' => ['required', 'in:father,mother,brother,sister,partner,child'],
        ];
    }
}
