<?php

namespace App\Http\Resources;

use App\Models\Tags;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ChannelsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $category = [
            26936   => 'Music & Performing Arts',
            417752  => 'Talk Shows & Podcasts',
            488191  => 'Creative',
            509660  => 'Art',
            509661  => 'Beauty & Body Art',
            509662  => 'Food & Drink',
            509670  => 'Science & Technology',
            509673  => 'Makers & Crafting',
        ];

        $tags = (! empty($tags)) ? $tags : [];

        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'live'      => $this->live,
            'title'     => $this->title,
            'category'  => $category[$this->game_id],
            'avatar'    => $this->avatar,
            'thumbnail' => $this->thumbnail,
            'viewers'   => $this->viewers,
            'partner'   => $this->partner,
            'tags'      => $this->populateTags($this->title, $tags),
        ];
    }

    private function populateTags(string $title, array $tagArr): array
    {
        $tags = [];

        if (! empty($title)) {
            preg_match_all("/(?<=^|\P{L})(#\b\p{L}[\p{L}\d_]+)/u", $title, $matches);

            if (! empty($matches)) {
                $hashs = $matches[0];

                if (! empty($hashs)) {
                    $tagResource = Tags::where(function ($query) use ($hashs) {
                        foreach ($hashs as $hash) {
                            $query->orWhere('tag_safe', Str::slug($hash));
                        }
                    })->get();

                    if ($tagResource !== null) {
                        foreach ($tagResource as $tag) {
                            $tags[] = new TagsResource($tag);
                        }
                    }
                }
            }
        }

        if (! empty($tagArr)) {
            $tagResource = Tags::where(function ($query) use ($tagArr) {
                foreach ($tagArr as $tag) {
                    $query->orWhere('tag_id', $tag);
                }
            })->get();

            if ($tagResource !== null) {
                foreach ($tagResource as $tag) {
                    $tags[] = new TagsResource($tag);
                }
            }
        }

        return $tags;
    }
}
