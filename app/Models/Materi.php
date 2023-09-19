<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_materi',
        'id_matkul',
        'deskripsi',
        'attachment',
    ];

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul');
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
}
