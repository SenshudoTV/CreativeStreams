<?php

namespace App\Console\Commands;

use App\Models\Channels;
use App\Models\Tags;
use Arr;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Str;

class FetchChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitch:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all live creative channels';

    /**
     * Guzzle Client.
     *
     * @var GuzzleClient
     */
    protected $client;

    /**
     * Blacklisted Tags.
     *
     * @var array
     */
    protected $blacklist = [
        'affiliate',
        'affiliated',
        'afk',
        'alertcoronavi',
        'anal',
        'anus',
        'arse',
        'ass',
        'balls',
        'ballsack',
        'bastard',
        'bestlightsontwitch',
        'biatch',
        'bitch',
        'bloody',
        'blowjob',
        'bollock',
        'bollok',
        'boner',
        'boob',
        'bugger',
        'bum',
        'butt',
        'buttplug',
        'cancer',
        'clitoris',
        'clubquarantine',
        'cmon',
        'cock',
        'coon',
        'cornavirus',
        'coronaparty',
        'covid',
        'covid19',
        'crap',
        'cunt',
        'damn',
        'dick',
        'dildo',
        'dyke',
        'fag',
        'faggot',
        'feck',
        'felching',
        'fellate',
        'fellatio',
        'flange',
        'fuck',
        'fucking',
        'fudgepacker',
        'gay',
        'gaystreamer',
        'goddamn',
        'handsupwillneverdie',
        'hell',
        'hentai',
        'homo',
        'jerk',
        'jizz',
        'knobend',
        'labia',
        'lesbian',
        'live',
        'lmao',
        'lmfao',
        'mature',
        'muff',
        'nigga',
        'nigger',
        'nude',
        'omg',
        'pathtoaffilate',
        'partner',
        'penis',
        'piss',
        'poop',
        'porn',
        'poundsign',
        'prick',
        'pube',
        'pussy',
        'quaranstream',
        'quarantine',
        'quarantineandchill',
        'quarantunes',
        'quedateencasa',
        'queer',
        'queerbeat',
        'raidparty',
        'razerstreamer',
        'restezchezvous',
        'scrotum',
        'sex',
        'sh1t',
        'shit',
        'slut',
        'smallstreamer',
        'smallstreamers',
        'smegma',
        'spunk',
        'streamers',
        'streambuddys',
        'streamraiders',
        'supportistkeinmord',
        'testy',
        'thehateisreal',
        'tit',
        'tosser',
        'trans',
        'translifeline',
        'transtagnow',
        'turd',
        'twat',
        'twitch',
        'twitchaffiliate',
        'twitchde',
        'twitchdj',
        'twitchfam',
        'twitchkittens',
        'twitchtv',
        'vagina',
        'wank',
        'washhands',
        'whore',
        'wow',
        'wtf',
        'xoxo',
    ];

    /**
     * Global Tags.
     *
     * @var array
     */
    protected $tags = [
        [
            'tag_id'        => '509660',
            'tag'           => 'Art',
            'tag_safe'      => 'art',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '509661',
            'tag'           => 'Beauty & Body Art',
            'tag_safe'      => 'beauty-body-art',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '488191',
            'tag'           => 'Creative',
            'tag_safe'      => 'creative',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '509662',
            'tag'           => 'Food & Drink',
            'tag_safe'      => 'food-drink',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '509673',
            'tag'           => 'Makers & Crafting',
            'tag_safe'      => 'makers-crafting',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '26936',
            'tag'           => 'Music & Performing Arts',
            'tag_safe'      => 'music-performing-arts',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '509670',
            'tag'           => 'Science & Technology',
            'tag_safe'      => 'science-technology',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
        [
            'tag_id'        => '417752',
            'tag'           => 'Talk Shows & Podcasts',
            'tag_safe'      => 'talk-shows-podcasts',
            'is_tag'        => false,
            'is_hashtag'    => false,
            'is_category'   => true,
            'count'         => 0,
        ],
    ];

    /**
     * Total Requests Made.
     *
     * @var int
     */
    protected $requestCount = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $env = file_get_contents(base_path() . '/twitch.json');
        $env = json_decode($env, true);

        $this->client = new GuzzleClient([
            'base_uri'  => 'https://api.twitch.tv/helix/',
            'headers'   => [
                'Client-ID'     => config('app.twitch.id'),
                'Authorization' => 'Bearer ' . $env['twitch_token'],
            ],
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Channels::setAllOffline();
        Tags::resetCount();

        $this->expandBlacklist();
        $this->fetchStreams();

        return 0;
    }

    /**
     * Expand the existing blacklist with database blacklisted tags.
     */
    private function expandBlacklist(): void
    {
        $tags = Tags::where('is_blacklisted', true)->get();

        collect($tags)
            ->each(function ($tag) {
                if (! in_array($tag->tag, $this->blacklist)) {
                    $this->blacklist[] = $tag->tag;
                }
            });
    }

    /**
     * Fetch live creative streams from Twitch API.
     *
     * @param string $cursor Pagination Cursor
     */
    private function fetchStreams(string $cursor = null): void
    {
        if (! empty($cursor)) {
            $cursor = '&after=' . $cursor;
        }

        $catIDs = [
            'Art'                     => 509660,
            'Beauty & Body Art'       => 509661,
            'Creative'                => 488191,
            'Food & Drink'            => 509662,
            'Makers & Crafting'       => 509673,
            'Music & Performing Arts' => 26936,
            'Science & Technology'    => 509670,
            'Talk Shows & Podcasts'   => 417752,
        ];

        $gameIds = '&game_id=' . implode('&game_id=', $catIDs);

        $response = $this->client->request('GET', 'streams?first=100' . $gameIds . $cursor);

        if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody()->getContents());

            if (! empty($responseBody) && ! empty($responseBody->data)) {
                $this->requestCount++;

                foreach ($responseBody->data as $stream) {
                    $user = [
                        'slug'      => null,
                        'partner'   => false,
                        'avatar'    => null,
                        'views'     => 0,
                    ];

                    $userResponse = $this->client->request('GET', 'users?id=' . $stream->user_id);
                    $this->requestCount++;

                    if ($userResponse->getStatusCode() === 200) {
                        $userResponseBody = json_decode($userResponse->getBody()->getContents());

                        if (! empty($userResponseBody) && ! empty($userResponseBody->data)) {
                            if (! empty($userResponseBody->data[0]->login)) {
                                $user = [
                                    'slug'      => $userResponseBody->data[0]->login,
                                    'partner'   => (! empty($userResponseBody->data[0]->broadcaster_type) && $userResponseBody->data[0]->broadcaster_type == 'partner') ? true : false,
                                    'avatar'    => $userResponseBody->data[0]->profile_image_url,
                                    'views'     => $userResponseBody->data[0]->view_count,
                                ];
                            }
                        }
                    }

                    $this->populateHashtags($stream->title);

                    $tagsEmpty = (empty($stream->tag_ids) && $stream->tag_ids === null);

                    $this->populateTags(
                        ! $tagsEmpty ? $stream->tag_ids : [],
                        $stream->game_id
                    );

                    Channels::updateOrCreate([
                        'id'                    => $stream->user_id,
                    ], [
                        'name'                  => $stream->user_name,
                        'slug'                  => $user['slug'],
                        'stream_created'        => Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $stream->started_at),
                        'live'                  => true,
                        'title'                 => $stream->title,
                        'game_id'               => $stream->game_id,
                        'avatar'                => $user['avatar'],
                        'thumbnail'             => str_replace(
                            ['{width}', '{height}'],
                            [600, 337],
                            $stream->thumbnail_url
                        ),
                        'views'                 => $user['views'],
                        'viewers'               => $stream->viewer_count,
                        'partner'               => $user['partner'],
                        'tags'                  => ! $tagsEmpty ? json_encode($stream->tag_ids) : null,
                    ]);
                }

                if ($this->shouldWait()) {
                    sleep(60);
                    $this->requestCount = 0;
                }

                if (! empty($responseBody->pagination) && property_exists($responseBody->pagination, 'cursor')) {
                    $this->fetchStreams($responseBody->pagination->cursor);
                } else {
                    $this->updateTags();
                }
            } else {
                $this->updateTags();
            }
        }
    }

    /**
     * Should wait if request count is greater than 600 to prevent hitting API Rate-Limit.
     *
     * @return bool
     */
    private function shouldWait()
    {
        if ($this->requestCount >= 600) {
            return true;
        }

        return false;
    }

    /**
     * Populate global Tags for processing.
     *
     * @param array $tag_ids Stream Known Tags
     */
    private function populateTags(array $tag_ids = [], int | null $game_id = 0): void
    {
        $searchTags = [];

        if (! empty($game_id)) {
            if (in_array($game_id, array_column($this->tags, 'tag_id'))) {
                $key = array_search($game_id, array_column($this->tags, 'tag_id'));

                $this->tags[$key]['count']++;
            }
        }

        if (! empty($tag_ids)) {
            foreach ($tag_ids as $tag) {
                if (! in_array($tag, array_column($this->tags, 'tag_id'))) {
                    if (! in_array($tag, $searchTags)) {
                        $name = null;

                        $tagDB = Tags::where('tag_id', $tag)->first();

                        if ($tagDB) {
                            if (! empty($tagDB->tag)) {
                                $name = $tagDB->tag;
                            } else {
                                $searchTags[] = $tag;
                            }
                        } else {
                            $searchTags[] = $tag;
                        }

                        $this->tags[] = [
                            'tag'           => $name,
                            'tag_safe'      => Str::slug($name),
                            'tag_id'        => $tag,
                            'is_tag'        => true,
                            'is_hashtag'    => false,
                            'count'         => 1,
                        ];
                    }
                } else {
                    $key = array_search($tag, array_column($this->tags, 'tag_id'));

                    $this->tags[$key]['count']++;
                }
            }
        }

        if (! empty($searchTags)) {
            $tagChunks = array_chunk($searchTags, 100, false);

            foreach ($tagChunks as $tags) {
                $tagResponse = $this->client->request('GET', 'tags/streams?tag_id=' . implode('&tag_id=', $tags));
                $this->requestCount++;

                if ($tagResponse->getStatusCode() === 200) {
                    $tagResponseBody = json_decode($tagResponse->getBody()->getContents());

                    if (! empty($tagResponseBody) && ! empty($tagResponseBody->data)) {
                        foreach ($tagResponseBody->data as $tag) {
                            if (in_array($tag->tag_id, array_column($this->tags, 'tag_id'))) {
                                $key = array_search($tag->tag_id, array_column($this->tags, 'tag_id'));

                                $this->tags[$key]['tag']      = $tag->localization_names->{'en-us'};
                                $this->tags[$key]['tag_safe'] = Str::slug($tag->localization_names->{'en-us'});
                            }
                        }
                    }
                }

                if ($this->shouldWait()) {
                    sleep(60);
                    $this->requestCount = 0;
                }
            }
        }
    }

    /**
     * Find hashtags within the streams title for population.
     *
     * @param array $title Stream Title
     */
    private function populateHashtags(string $title = null): void
    {
        if (! empty($title)) {
            preg_match_all("/(?<=^|\P{L})(#\b\p{L}[\p{L}\d_]+)/u", $title, $matches);

            if (! empty($matches)) {
                foreach ($matches[0] as $hash) {
                    $hashtag = str_replace('#', '', strtolower($hash));

                    if (! empty($hashtag) && ! in_array($hashtag, $this->blacklist)) {
                        if (! in_array($hashtag, array_column($this->tags, 'tag'))) {
                            $this->tags[] = [
                                'tag'           => $hashtag,
                                'tag_safe'      => Str::slug($hashtag),
                                'tag_id'        => null,
                                'is_tag'        => false,
                                'is_hashtag'    => true,
                                'count'         => 1,
                            ];
                        } else {
                            $key = array_search($hashtag, array_column($this->tags, 'tag'));

                            $this->tags[$key]['count']++;
                        }
                    }
                }
            }
        }
    }

    /**
     * Update Tags Table.
     */
    private function updateTags(): void
    {
        if (! empty($this->tags)) {
            foreach ($this->tags as $tag) {
                if (! empty($tag['tag'])) {
                    Tags::updateOrCreate([
                        'tag_safe'    => $tag['tag_safe'],
                    ], Arr::except($tag, ['tag_safe']));
                }
            }
        }
    }
}
