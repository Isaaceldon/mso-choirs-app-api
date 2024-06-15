<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $tabernacle_id
 * @property string $name
 * @property integer $members
 * @property string $created_at
 * @property string $updated_at
 * @property Tabernacle $tabernacle
 * @property Song[] $songs
 */
class Choir extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['tabernacle_id', 'name', 'members', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tabernacle()
    {
        return $this->belongsTo('App\Models\Tabernacle');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function songs()
    {
        return $this->hasMany('App\Models\Song');
    }
}
