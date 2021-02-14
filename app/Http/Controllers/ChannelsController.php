<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelsCollectionResource;
use App\Models\Channels;

class ChannelsController extends Controller
{
    /**
     * List Live Channels
     *
     * @return json
     */
    public function index()
    {
        return new ChannelsCollectionResource(Channels::where('live', true)->paginate(30));
    }
}
