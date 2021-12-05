<?php

namespace App\Models;

use App\QueryBuilders\ChannelsQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Channels extends Model
{
    use Sortable;

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
        'stream_created' => 'datetime',
        'live'           => 'boolean',
        'views'          => 'integer',
        'viewers'        => 'integer',
        'partner'        => 'boolean',
        'tags'           => 'array',
    ];

    public $sort = [
        'viewers',
        'stream_created',
    ];

    public function newEloquentBuilder($query)
    {
        return new ChannelsQueryBuilder($query);
    }

    public static function setAllOffline()
    {
        self::where('live', true)->update([
            'live'              => false,
            'title'             => null,
            'stream_created'    => null,
            'game_id'           => 0,
            'viewers'           => 0,
            'tags'              => null,
        ]);
    }
}
