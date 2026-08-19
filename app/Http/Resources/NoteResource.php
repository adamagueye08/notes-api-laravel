<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'valeur' => $this->valeur,
            'type' => $this->type,
            'date_evaluation' => $this->date_evaluation,
            'commentaire' => $this->commentaire,
            'etudiant' => $this->whenLoaded('etudiant', fn () => [
                'id' => $this->etudiant->id,
                'nom' => $this->etudiant->nom,
                'prenom' => $this->etudiant->prenom,
            ]),
            'matiere' => $this->whenLoaded('matiere', fn () => [
                'id' => $this->matiere->id,
                'nom' => $this->matiere->nom,
            ]),
        ];
    }
}
