<?php

namespace App\Console\Commands;

use App\Models\Channels;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;

class FetchChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channels:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all live creative channels';

    /**
     * Blacklisted Tags
     *
     * @var array
     */
    protected $blacklist;

    /**
     * Global Tags
     *
     * @var array
     */
    protected $tags;

    /**
     * Total Requests Made
     *
     * @var integer
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

        $this->blacklist = [
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
            'supportistkeinmord',
            'testy',
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
            'twitchkittens',
            'vagina',
            'wank',
            'washhands',
            'whore',
            'wow',
            'wtf',
            'xoxo',
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Channels::setAllOffline();

        $this->fetchStreams();

        return 0;
    }

    /**
     * Fetch live creative streams from Twitch API
     *
     * @param string $cursor Pagination Cursor
     * @return void
     */
    private function fetchStreams(string $cursor = null): void
    {
        if (! empty($cursor)) {
            $cursor = '&after=' . $cursor;
        }

        // Reference
        $catIDs = [
            'Art' => 509660,
            'Beauty & Body Art' => 509661,
            'Creative' => 488191,
            'Food & Drink' => 509662,
            'Makers & Crafting' => 509673,
            'Music & Performing Arts' => 26936,
            'Science & Technology' => 509670
        ];

        $gameIds = '&game_id=' . implode('&game_id=', $catIDs);

        $client = new GuzzleClient([
            'base_uri'  => 'https://api.twitch.tv/helix/',
            'headers'   => [
                'Client-ID' => config('app.twitch.id'),
                'Authorization' => 'Bearer ' . config('app.twitch.token'),
            ],
        ]);

        $response = $client->request('GET', 'streams?first=100' . $gameIds . $cursor);

        if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody()->getContents());

            if (! empty($responseBody) && ! empty($responseBody->data)) {
                $this->requestCount += 1;

                foreach ($responseBody->data as $stream) {
                    $user = [
                        'slug'      => null,
                        'partner'   => false,
                        'avatar'    => null,
                        'views'     => 0,
                    ];

                    $userResponse = $client->request('GET', 'users?id=' . $stream->user_id);
                    $this->requestCount += 1;

                    $userResponseBody = json_decode($userResponse->getBody()->getContents());

                    if (! empty($userResponseBody) && ! empty($userResponseBody->data)) {
                        if (! empty($userResponseBody->data[0]->login)) {
                            $user = [
                                'slug'      => $userResponseBody->data[0]->login,
                                'partner'   => (! empty($userResponseBody->data[0]->broadcaster_type) && $userResponseBody->data[0]->broadcaster_type  == "partner") ? true : false,
                                'avatar'    => $userResponseBody->data[0]->profile_image_url,
                                'views'     => $userResponseBody->data[0]->view_count,
                            ];
                        }
                    }

                    $this->populateTags($stream->title, (! empty($stream->tag_ids) ? $stream->tag_ids : array()));

                    $channels = Channels::updateOrCreate([
                        'id'                    => $stream->user_id,
                    ], [
                        'id'                    => $stream->user_id,
                        'name'                  => $stream->user_name,
                        'slug'                  => $user['slug'],
                        'stream_created'        => Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $stream->started_at),
                        'live'                  => true,
                        'title'                 => $stream->title,
                        'game_id'               => $stream->game_id,
                        'avatar'                => $user['avatar'],
                        'thumbnail'             => str_replace(['{width}','{height}'], [600, 337], $stream->thumbnail_url),
                        'views'                 => $user['views'],
                        'viewers'               => $stream->viewer_count,
                        'partner'               => $user['partner'],
                        'tags'                  => json_encode($stream->tag_ids),
                    ]);

                    // TODO: Loop Function & Store Tags
                }
            }
        }
    }

    /**
     * Populate the global Tags Variable for processing
     *
     * @param string $title Stream Title
     * @param array $tag_ids Stream Known Tags
     * @return void
     */
    private function populateTags(string $title = null, array $tag_ids = []): void
    {
        preg_match_all("/(?<=^|\P{L})(#\b\p{L}[\p{L}\d_]+)/u", $title, $matches);

        if (! empty($matches)) {
            foreach ($matches[0] as $key => $hash) {
                $hashtag = str_replace('#', '', strtolower($hash));

                if (in_array($hashtag, $this->blacklist) !== true) {
                    if (! array_key_exists($hashtag, $this->tags)) {
                        $this->tags[$hashtag] = [
                            'is_hashtag'    => true,
                            'is_tag'        => false,
                            'count'         => 1,
                        ];
                    } else {
                        $this->tags[$hashtag]['count'] += 1;
                    }
                }
            }
        }
        
        if (! empty($tag_ids)) {
            foreach ($tag_ids as $tag) {
                if (! array_key_exists($tag, $this->tags)) {
                    $this->tags[$tag] = [
                        'is_hashtag'    => false,
                        'is_tag'        => true,
                        'count'         => 1,
                    ];
                } else {
                    $this->tags[$tag] += 1;
                }
            }
        }
    }
}
