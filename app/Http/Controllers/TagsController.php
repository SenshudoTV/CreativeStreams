<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagsCollectionResource;
use App\Http\Resources\TagsResource;
use App\Models\Tags;

class TagsController extends Controller
{
    /**
     * List Tags
     *
     * @return TagsCollectionResource
     */
    public function index()
    {
        return new TagsCollectionResource(Tags::orderBy('tag', 'ASC')->get());
    }
}
