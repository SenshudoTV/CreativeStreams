<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => (! empty($this->tag_id)) ? $this->tag_id : $this->id,
            'tag'       => (($this->is_hashtag && empty($this->tag_id)) ? '#' : '') . $this->tag,
            'is_tag'    => $this->is_tag,
            'is_hashtag'=> $this->is_hashtag,
            'count'     => $this->count,
        ];
    }
}
