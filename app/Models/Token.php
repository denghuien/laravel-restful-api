<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $table = 'tokens';

    protected $fillable = [
        'uuid', 'device_id', 'data', 'revoked', 'parent_id', 'expired_at'
    ];

    protected $casts = [
        'expired_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(UserToken::class, 'token_id', 'uuid');
    }
}
