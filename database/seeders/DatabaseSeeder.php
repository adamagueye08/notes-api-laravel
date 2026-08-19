<?php

namespace Database\Seeders;

use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Comptes de test (mot de passe identique pour tous : password)
        $admin = User::create([
            'name' => 'Admin Ecole',
            'email' => 'admin@ecole.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $prof = User::create([
            'name' => 'Prof Diop',
            'email' => 'prof@ecole.test',
            'password' => Hash::make('password'),
            'role' => 'professeur',
        ]);

        $userEtudiant = User::create([
            'name' => 'Fatou Sall',
            'email' => 'etudiant@ecole.test',
            'password' => Hash::make('password'),
            'role' => 'etudiant',
        ]);

        $etudiant = Etudiant::create([
            'user_id' => $userEtudiant->id,
            'matricule' => 'ETU-2026-001',
            'nom' => 'Sall',
            'prenom' => 'Fatou',
            'classe' => 'Terminale S',
            'date_naissance' => '2007-03-12',
        ]);

        Etudiant::create([
            'matricule' => 'ETU-2026-002',
            'nom' => 'Ndiaye',
            'prenom' => 'Moussa',
            'classe' => 'Terminale S',
            'date_naissance' => '2007-07-01',
        ]);

        $maths = Matiere::create([
            'nom' => 'Mathématiques',
            'code' => 'MATH01',
            'coefficient' => 4,
            'professeur_id' => $prof->id,
        ]);

        $physique = Matiere::create([
            'nom' => 'Physique-Chimie',
            'code' => 'PHYS01',
            'coefficient' => 3,
            'professeur_id' => $prof->id,
        ]);

        Note::create([
            'etudiant_id' => $etudiant->id,
            'matiere_id' => $maths->id,
            'valeur' => 15.5,
            'type' => 'devoir',
            'date_evaluation' => '2026-06-10',
        ]);

        Note::create([
            'etudiant_id' => $etudiant->id,
            'matiere_id' => $physique->id,
            'valeur' => 12,
            'type' => 'examen',
            'date_evaluation' => '2026-06-15',
        ]);
    }
}
