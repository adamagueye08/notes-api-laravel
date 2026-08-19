<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMatiereRequest;
use App\Http\Requests\UpdateMatiereRequest;
use App\Http\Resources\MatiereResource;
use App\Models\Matiere;

class MatiereController extends Controller
{
    public function index()
    {
        return MatiereResource::collection(Matiere::with('professeur')->paginate(15));
    }

    public function store(StoreMatiereRequest $request)
    {
        $matiere = Matiere::create($request->validated());

        return new MatiereResource($matiere);
    }

    public function show(Matiere $matiere)
    {
        return new MatiereResource($matiere->load('professeur'));
    }

    public function update(UpdateMatiereRequest $request, Matiere $matiere)
    {
        $matiere->update($request->validated());

        return new MatiereResource($matiere);
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();

        return response()->json(null, 204);
    }
}
