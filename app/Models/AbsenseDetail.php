<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenseDetail extends Model
{
    use HasFactory;
    protected $table = 'absence_details';
    protected $fillable = [
        'user_id',
        'absence_id',
        'is_absence',
        'role'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->first();
    }
}
