<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EtudiantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'matricule' => $this->matricule,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'classe' => $this->classe,
            'date_naissance' => $this->date_naissance,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ];
    }
}
