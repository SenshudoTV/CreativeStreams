<?php

namespace Database\Factories;

use App\Models\Tags;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tags::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tag = $this->faker->boolean();

        return [
            'tag'           => $this->faker->citySuffix,
            'tag_id'        => $this->faker->uuid,
            'is_tag'        => $tag,
            'is_hashtag'    => !$tag,
        ];
    }
}
