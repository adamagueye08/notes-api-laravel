<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEtudiantRequest;
use App\Http\Requests\UpdateEtudiantRequest;
use App\Http\Resources\EtudiantResource;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $query = Etudiant::query();

        if ($request->user()->isEtudiant()) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('classe')) {
            $query->where('classe', $request->query('classe'));
        }

        return EtudiantResource::collection($query->paginate(15));
    }

    public function store(StoreEtudiantRequest $request)
    {
        $etudiant = Etudiant::create($request->validated());

        return new EtudiantResource($etudiant);
    }

    public function show(Request $request, Etudiant $etudiant)
    {
        if ($request->user()->isEtudiant() && $etudiant->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        return new EtudiantResource($etudiant);
    }

    public function update(UpdateEtudiantRequest $request, Etudiant $etudiant)
    {
        $etudiant->update($request->validated());

        return new EtudiantResource($etudiant);
    }

    public function destroy(Etudiant $etudiant)
    {
        $etudiant->delete();

        return response()->json(null, 204);
    }
}
