<?php

namespace App\Jobs;

use App\Models\Channels;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessChannels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
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
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Channels::setAllOffline();

        $this->fetchStreams();
    }

    /**
     * Undocumented function
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

        $request = new GuzzleRequest(
            'GET',
            'https://api.twitch.tv/helix/streams?first=100'.$gameIds.$cursor,
            [
                'Client-ID' => config('app.twitch.id'),
                'Authorization' => 'Bearer ' . config('app.twitch.token'),
            ]
        );

        $response = new GuzzleResponse();

        if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody());

            dd($responseBody);
        }
    }

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
