<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TagsCollectionResource extends ResourceCollection
{
    /**
     * The resoruce that this resource collects.
     *
     * @var string
     */
    public $collects = TagsResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
