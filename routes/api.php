<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EtudiantController;
use App\Http\Controllers\Api\MatiereController;
use App\Http\Controllers\Api\NoteController;
use Illuminate\Support\Facades\Route;

// --- Authentification (publique) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Routes protégées (Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Étudiants : lecture accessible à tous les rôles connectés (filtrée dans le contrôleur pour un étudiant)
    Route::middleware('role:admin,professeur,etudiant')->group(function () {
        Route::get('/etudiants', [EtudiantController::class, 'index']);
        Route::get('/etudiants/{etudiant}', [EtudiantController::class, 'show']);
        Route::get('/etudiants/{etudiant}/bulletin', [NoteController::class, 'bulletin']);
    });
    // Écriture étudiants : admin uniquement
    Route::middleware('role:admin')->group(function () {
        Route::post('/etudiants', [EtudiantController::class, 'store']);
        Route::put('/etudiants/{etudiant}', [EtudiantController::class, 'update']);
        Route::delete('/etudiants/{etudiant}', [EtudiantController::class, 'destroy']);
    });

    // Matières : lecture pour tous les connectés
    Route::middleware('role:admin,professeur,etudiant')->group(function () {
        Route::get('/matieres', [MatiereController::class, 'index']);
        Route::get('/matieres/{matiere}', [MatiereController::class, 'show']);
    });
    // Écriture matières : admin uniquement
    Route::middleware('role:admin')->group(function () {
        Route::post('/matieres', [MatiereController::class, 'store']);
        Route::put('/matieres/{matiere}', [MatiereController::class, 'update']);
        Route::delete('/matieres/{matiere}', [MatiereController::class, 'destroy']);
    });

    // Notes : lecture pour tous les connectés (filtrée par rôle dans le contrôleur)
    Route::middleware('role:admin,professeur,etudiant')->group(function () {
        Route::get('/notes', [NoteController::class, 'index']);
        Route::get('/notes/{note}', [NoteController::class, 'show']);
    });
    // Écriture notes : admin et professeur
    Route::middleware('role:admin,professeur')->group(function () {
        Route::post('/notes', [NoteController::class, 'store']);
        Route::put('/notes/{note}', [NoteController::class, 'update']);
        Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
    });
});
