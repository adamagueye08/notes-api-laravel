<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEtudiantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $etudiant = $this->route('etudiant');

        return [
            'matricule' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('etudiants', 'matricule')->ignore($etudiant?->id)],
            'nom' => 'sometimes|required|string|max:100',
            'prenom' => 'sometimes|required|string|max:100',
            'classe' => 'sometimes|required|string|max:50',
            'date_naissance' => 'nullable|date|before:today',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
