<?php

namespace App\Models;

use Database\Factories\ChannelsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'stream_created',
        'live',
        'title',
        'game_id',
        'avatar',
        'thumbnail',
        'views',
        'viewers',
        'partner',
        'tags',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'stream_create' => 'datetime',
        'live'          => 'boolean',
        'views'         => 'integer',
        'viewers'       => 'integer',
        'partner'       => 'boolean',
        'tags'          => 'array',
    ];

    protected static function newFactory()
    {
        return ChannelsFactory::new();
    }

    public static function setAllOffline()
    {
        // TODO: Update All Offline
    }
}
