<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_matkul');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_matkul');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'id_matkul');
    }
}
