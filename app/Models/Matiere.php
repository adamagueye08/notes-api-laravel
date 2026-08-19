<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'coefficient',
        'professeur_id',
    ];

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
