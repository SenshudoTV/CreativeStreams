<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagsCollectionResource;
use App\Models\Tags;

class TagsController extends Controller
{
    /**
     * List Tags.
     *
     * @return TagsCollectionResource
     */
    public function index()
    {
        return new TagsCollectionResource(Tags::where('is_blacklisted', false)->orderBy('tag', 'ASC')->get());
    }
}
