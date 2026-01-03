<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Properti ini menentukan kolom mana saja yang boleh diisi (Mass Assignment).
     * Pastikan menggunakan simbol '$' sebelum nama variabel.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'is_subscribed',
        'subscription_end_date',
        'profile_data'
    ];

    /**
     * Kolom yang harus disembunyikan saat data diubah menjadi array atau JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut ke tipe data tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_subscribed' => 'boolean',
        'subscription_end_date' => 'date',
    ];
}
