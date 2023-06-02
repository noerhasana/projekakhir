<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'no_hp',
        'email',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'avatar',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'role',
        'avatar_url',
    ];

    public function getAvatarUrlAttribute()
    {
        return ($this->avatar == null)
            ? env('APP_URL').'/images/default-avatar.jpg'
            : Storage::url($this->avatar);
    }

    public function getRoleAttribute()
    {
        return $this->roles()->get(['name'])->pluck('name');
    }


    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function pemesanan() 
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function komentar() 
    {
        return $this->hasMany(KomentarProduk::class);
    }

    public function rating() 
    {
        return $this->hasMany(UserRatingProduk::class);
    }   

    public function alamat()
    {
        return $this->hasMany(AlamatPelanggan::class);
    }
}
