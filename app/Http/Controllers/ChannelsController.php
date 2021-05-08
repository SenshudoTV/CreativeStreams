<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelsCollectionResource;
use App\Http\Resources\ChannelsResource;
use App\Models\Channels;
use App\Models\Tags;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    /**
     * List Live Channels.
     */
    public function index(Request $request): ChannelsCollectionResource
    {
        $query = Channels::where('live', true);

        $query->where(function ($query) use ($request) {
            if ($request->has('filter')) {
                $filters = $request->get('filter');

                if (array_key_exists('tag', $filters)) {
                    $tagIDs = explode(',', $filters['tag']);

                    if (! empty($tagIDs)) {
                        foreach ($tagIDs as $tagID) {
                            $tag = Tags::where('id', $tagID)->first();

                            if (! $tag->is_blacklisted) {
                                if ($tag->is_tag) {
                                    $query->orWhere('tags', 'LIKE', "%$tag->tag_id%");
                                }

                                if ($tag->is_hashtag) {
                                    $query->orWhere('title', 'LIKE', "%#$tag->tag%");
                                }

                                if ($tag->is_category) {
                                    $query->orWhere('game_id', '=', $tag->tag_id);
                                }
                            }
                        }
                    }
                }
            }
        });

        if ($request->has('order')) {
            $order = $request->get('order');

            $orderOptions = [
                'viewers-desc' => ['viewers', 'DESC'],
                'viewers-asc'  => ['viewers', 'ASC'],
                'created-desc' => ['stream_created', 'DESC'],
                'created-asc'  => ['stream_created', 'ASC'],
            ];

            if (array_key_exists($order, $orderOptions)) {
                $query->orderBy($orderOptions[$order][0], $orderOptions[$order][1]);
            } else {
                $query->orderBy('stream_created', 'ASC');
            }
        } else {
            $query->orderBy('stream_created', 'ASC');
        }

        return new ChannelsCollectionResource($query->paginate(30));
    }

    /**
     * Fetch a random live stream.
     */
    public function random(): ChannelsResource
    {
        return new ChannelsResource(Channels::where('live', true)->inRandomOrder()->first());
    }
}
