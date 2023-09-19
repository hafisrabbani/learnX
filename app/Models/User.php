<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function assignRole(string $role)
    {
        $this->role = $role;
        $this->save();
    }

    public function getRole()
    {
        return $this->role;
    }

    public function isAdmin()
    {
        return $this->getRole() === 'admin';
    }

    public function isMahasiswa()
    {
        return $this->getRole() === 'mahasiswa';
    }

    public function isDosen()
    {
        return $this->getRole() === 'dosen';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function hasRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class, 'dosen_id');
    }

    public function mataKuliahByMahasiswa()
    {
        return $this->belongsToMany(MataKuliah::class, 'enrollments', 'id_user', 'id_matkul');
    }
}
