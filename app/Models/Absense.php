<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absense extends Model
{
    use HasFactory;
    protected $table = 'absences';
    protected $guarded = ['id'];

    public function absenseDetail()
    {
        return $this->hasMany(AbsenseDetail::class, 'absence_id')->get();
    }
}
