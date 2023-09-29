<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTS extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 't_t_s';
}
