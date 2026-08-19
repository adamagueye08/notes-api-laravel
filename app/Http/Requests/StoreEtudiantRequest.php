<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEtudiantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // géré par le middleware 'role' sur la route
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|string|max:50|unique:etudiants,matricule',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'classe' => 'required|string|max:50',
            'date_naissance' => 'nullable|date|before:today',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
