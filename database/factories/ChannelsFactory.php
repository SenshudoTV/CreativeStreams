<?php

namespace Database\Factories;

use App\Models\Channels;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ChannelsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Channels::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->fake->unique()->name;
        $live = $this->faker->boolean();

        return [
            'name'              => $name,
            'slug'              => Str::slug($name),
            'stream_created'    => $live ? Carbon::now() : null,
            'live'              => $live,
            'title'             => $this->faker->text(180),
            'game_id'           => 0,
        ];
    }
}
