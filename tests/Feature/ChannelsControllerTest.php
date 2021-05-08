<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testChannelList()
    {
        $this->seed();

        $this->get(route('channels.list'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'  => [
                    '*' => [],
                ],
                'meta'  => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active',
                        ],
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]);
    }
}
