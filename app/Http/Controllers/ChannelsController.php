<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChannelsResource;
use App\Models\Channels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChannelsController extends Controller
{
    /**
     * List Live Channels.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Channels::where('live', true)
            ->filterResults($request)
            ->sortable(['stream_created' => 'desc'])
            ->paginate(30);

        return ChannelsResource::collection($query);
    }

    /**
     * Fetch a random live stream.
     */
    public function random(): ChannelsResource
    {
        return new ChannelsResource(Channels::where('live', true)->inRandomOrder()->first());
    }
}
