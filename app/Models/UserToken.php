<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    public $table = 'users_tokens';

    protected $fillable = [
        'token_id', 'user_id'
    ];

    protected $casts = [
        'expired_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
}
