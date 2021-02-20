<?php

namespace App\Models;

use Database\Factories\TagsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

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
        'count'         => 'integer',
    ];

    protected static function newFactory()
    {
        return TagsFactory::new();
    }

    public static function resetCount()
    {
        self::where('count', '>', '0')->update(['count' => 0]);
    }
}
