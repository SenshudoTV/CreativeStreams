<?php

namespace App\QueryBuilders;

use App\Models\Tags;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ChannelsQueryBuilder extends Builder
{
    public function filterResults(Request $request)
    {
        return $this->where(function ($query) use ($request) {
            if ($request->has('tags')) {
                $tags = $request->get('tags');
                $tagIDs = explode(',', $tags);

                if (! empty($tagIDs)) {
                    foreach ($tagIDs as $tagID) {
                        $tag = Tags::where('id', $tagID)->first();

                        if (! $tag->is_blacklisted) {
                            if ($tag->is_tag) {
                                $query->orWhere('tags', 'LIKE', "%$tag->tag_id%");
                            }

                            if ($tag->is_hashtag) {
                                $query->orWhere('title', 'LIKE', "%#$tag->tag%");
                            }

                            if ($tag->is_category) {
                                $query->orWhere('game_id', $tag->tag_id);
                            }
                        }
                    }
                }
            }
        });
    }
}
