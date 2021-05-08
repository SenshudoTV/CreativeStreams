<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',
        'tag',
        'tag_safe',
        'is_tag',
        'is_hashtag',
        'is_category',
        'is_blacklisted',
        'count',
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
        'is_tag'        => 'boolean',
        'is_hashtag'    => 'boolean',
        'is_category'   => 'boolean',
        'is_blacklisted'=> 'boolean',
        'count'         => 'integer',
    ];

    public static function resetCount()
    {
        self::where('count', '>', '0')->update(['count' => 0]);
    }
}
