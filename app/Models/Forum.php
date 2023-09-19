<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function threads()
    {
        return $this->hasMany(ForumThread::class, 'forum_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function getWithMateriAndMatkul($id)
    {
        return $this->with('materi')->with('matkul')->whereHas('materi', function ($query) use ($id) {
            $query->where('id_matkul', $id);
        })->get();
    }
}
