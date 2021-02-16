<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelsCollectionResource;
use App\Http\Resources\ChannelsResource;
use App\Models\Channels;

class ChannelsController extends Controller
{
    /**
     * List Live Channels
     *
     * @return ChannelsCollectionResource
     */
    public function index()
    {
        return new ChannelsCollectionResource(Channels::where('live', true)
            ->orderBy('stream_created', 'DESC')
            ->paginate(30));
    }

    /**
     * Fetch a random live stream
     *
     * @return ChannelsResource
     */
    public function random()
    {
        return new ChannelsResource(Channels::where('live', true)->inRandomOrder()->first());
    }
}
