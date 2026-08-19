<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatiereResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'code' => $this->code,
            'coefficient' => $this->coefficient,
            'professeur' => $this->whenLoaded('professeur', fn () => [
                'id' => $this->professeur->id,
                'name' => $this->professeur->name,
            ]),
        ];
    }
}
