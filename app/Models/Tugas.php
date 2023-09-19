<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_tugas',
        'id_matkul',
        'deskripsi',
        'deadline',
        'attachment',
    ];
}
