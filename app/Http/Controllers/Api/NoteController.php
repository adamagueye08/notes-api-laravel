<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Etudiant;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Note::with(['etudiant', 'matiere']);

        if ($user->isEtudiant()) {
            $query->whereHas('etudiant', fn ($q) => $q->where('user_id', $user->id));
        } elseif ($user->isProfesseur()) {
            $query->whereHas('matiere', fn ($q) => $q->where('professeur_id', $user->id));
        }

        if ($request->filled('etudiant_id')) {
            $query->where('etudiant_id', $request->query('etudiant_id'));
        }

        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->query('matiere_id'));
        }

        return NoteResource::collection($query->paginate(20));
    }

    public function store(StoreNoteRequest $request)
    {
        $note = Note::create($request->validated());

        return new NoteResource($note->load(['etudiant', 'matiere']));
    }

    public function show(Request $request, Note $note)
    {
        $user = $request->user();

        if ($user->isEtudiant() && $note->etudiant->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        if ($user->isProfesseur() && $note->matiere->professeur_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        return new NoteResource($note->load(['etudiant', 'matiere']));
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note->update($request->validated());

        return new NoteResource($note->load(['etudiant', 'matiere']));
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json(null, 204);
    }

    /**
     * Bulletin d'un étudiant : toutes ses notes, avec moyenne générale pondérée par coefficient.
     */
    public function bulletin(Request $request, Etudiant $etudiant)
    {
        $user = $request->user();

        if ($user->isEtudiant() && $etudiant->user_id !== $user->id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        $notes = $etudiant->notes()->with('matiere')->get();

        $totalPoints = $notes->sum(fn ($note) => $note->valeur * $note->matiere->coefficient);
        $totalCoefficients = $notes->sum(fn ($note) => $note->matiere->coefficient);
        $moyenne = $totalCoefficients > 0 ? round($totalPoints / $totalCoefficients, 2) : null;

        return response()->json([
            'etudiant' => [
                'id' => $etudiant->id,
                'nom' => $etudiant->nom,
                'prenom' => $etudiant->prenom,
                'classe' => $etudiant->classe,
            ],
            'notes' => NoteResource::collection($notes),
            'moyenne_generale' => $moyenne,
        ]);
    }
}
