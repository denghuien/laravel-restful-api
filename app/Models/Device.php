<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $table = 'devices';

    protected $fillable = [
        'uuid', 'type', 'name', 'manufacturer', 'system', 'system_version', 'language', 'browser', 'browser_version', 'user_agent'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(UserDevice::class, 'device_id', 'uuid');
    }
}
