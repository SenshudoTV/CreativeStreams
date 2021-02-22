<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelsCollectionResource;
use App\Http\Resources\ChannelsResource;
use App\Models\Channels;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    /**
     * List Live Channels
     *
     * @return ChannelsCollectionResource
     */
    public function index(Request $request): ChannelsCollectionResource
    {
        $query = Channels::where('live', true);

        $query->where(function ($query) use ($request) {
            if ($request->has('filter')) {
                $filters = $request->get('filter');

                if (array_key_exists('tag', $filters)) {
                    $tags = explode(',', $filters['tag']);

                    if (! empty($tags)) {
                        $query->orWhere(function ($query) use ($tags) {
                            if (is_array($tags)) {
                                foreach ($tags as $tag) {
                                    $query->orWhere('tags', 'LIKE', "%$tag%");
                                }
                            } else {
                                $query->orWhere('tags', 'LIKE', "%$tags%");
                            }
                        });
                    }
                }

                if (array_key_exists('hashtag', $filters)) {
                    $hashs = explode(',', $filters['hashtag']);

                    if (! empty($hashs)) {
                        $query->orWhere(function ($query) use ($hashs) {
                            if (is_array($hashs)) {
                                foreach ($hashs as $hash) {
                                    $query->orWhere('title', 'LIKE', "%#$hash%");
                                }
                            } else {
                                $query->orWhere('title', 'LIKE', "%#$hashs%");
                            }
                        });
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
     * Fetch a random live stream
     *
     * @return ChannelsResource
     */
    public function random(): ChannelsResource
    {
        return new ChannelsResource(Channels::where('live', true)->inRandomOrder()->first());
    }
}
