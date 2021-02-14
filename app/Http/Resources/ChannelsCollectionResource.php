<?php

namespace App\Http\Resources;

use App\Http\Resources\ChannelsResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChannelsCollectionResource extends ResourceCollection
{
    /**
     * The resoruce that this resource collects
     *
     * @var string
     */
    public $collects = ChannelsResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
