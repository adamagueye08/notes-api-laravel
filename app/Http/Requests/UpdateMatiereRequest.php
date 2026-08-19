<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMatiereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $matiere = $this->route('matiere');

        return [
            'nom' => 'sometimes|required|string|max:100',
            'code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('matieres', 'code')->ignore($matiere?->id)],
            'coefficient' => 'sometimes|required|integer|min:1|max:10',
            'professeur_id' => 'nullable|exists:users,id',
        ];
    }
}
