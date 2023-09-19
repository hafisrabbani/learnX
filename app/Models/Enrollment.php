<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function matkulByMahasiswa()
    {
        return $this->hasMany(MataKuliah::class, 'id_matkul', 'id');
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
