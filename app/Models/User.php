<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasFactory;

    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'name', 'email', 'password', 'role', 'favorite_group_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'last_login', 'forget_user'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'favorite_group_id', 'id')->withDefault();
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

