<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagsCollectionResource;
use App\Http\Resources\TagsResource;
use App\Models\Tags;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagsController extends Controller
{
    /**
     * List Tags.
     *
     * @return TagsCollectionResource
     */
    public function index(): AnonymousResourceCollection
    {
        return TagsResource::collection(
            Tags::where('is_blacklisted', false)
                ->where('count', '>', 0)
                ->orderBy('tag', 'ASC')->get()
        );
    }
}
