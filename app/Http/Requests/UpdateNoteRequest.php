<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'etudiant_id' => 'sometimes|required|exists:etudiants,id',
            'matiere_id' => 'sometimes|required|exists:matieres,id',
            'valeur' => 'sometimes|required|numeric|min:0|max:20',
            'type' => 'sometimes|required|in:devoir,examen,controle',
            'date_evaluation' => 'sometimes|required|date',
            'commentaire' => 'nullable|string|max:255',
        ];
    }
}
