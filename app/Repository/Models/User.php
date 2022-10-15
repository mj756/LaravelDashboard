<?php

namespace App\Repository\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $dateFormat = 'U';
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'dob',
        'profileImage',
        'insertedOn',
        'updatedOn',
        'deletedOn',
        'isDeleted',
        'isSocialUser',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'isDeleted',
        'deletedOn',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'insertedOn'=>'date:Y-m-d',
        'dob'=>'date:Y-m-d',
        'updatedOn'=>'date:Y-m-d',
        'email_verified_at' => 'datetime:Y-m-d',
    ];
   
}

