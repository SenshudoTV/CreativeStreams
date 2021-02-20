<?php

namespace App\Console\Commands;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;

class ValidateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitch:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate our Twitch OAuth Token';

    /**
     * Guzzle Client
     *
     * @var GuzzleClient
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new GuzzleClient([
            'base_uri'  => 'https://id.twitch.tv',
            'headers'   => [
                'client_id'     => config('app.twitch.id'),
                'client_secret' => config('app.twitch.secret'),
                'grant_type'    => 'client_credentials',
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
        $response = $this->client->request('POST', '/oauth2/token');

        if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody()->getContents());

            if (! empty($responseBody) && ! empty($responseBody->access_token)) {
                $this->updateEnv([
                    'TWITCH_TOKEN' => $responseBody->access_token,
                ]);
            }
        }

        return 0;
    }

    /**
     * Update our .env File
     *
     * @param array $data
     * @return bool
     */
    protected function updateEnv($data = []): bool
    {
        if (! empty($data)) {
            $env = file_get_contents(base_path() . '/.env');
            $env = preg_split('/\s+/', $env);

            foreach ($data as $key => $value) {
                foreach ($env as $env_key => $env_value) {
                    $entry = explode('=', $env_value, 2);

                    if ($entry[0] == $key) {
                        $env[$env_key] = $key . '=' . $value;
                    } else {
                        $env[$env_key] = $env_value;
                    }
                }
            }

            $env = implode("\n", $env);

            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    }
}
