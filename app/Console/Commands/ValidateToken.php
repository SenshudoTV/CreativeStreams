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
     * Guzzle Client.
     *
     * @var GuzzleClient
     */
    protected $client;

    /**
     * Twitch Config File loaded.
     *
     * @var bool
     */
    protected $configLoaded = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (file_exists(base_path() . ' /twitch.json')) {
            $env = file_get_contents(base_path() . '/twitch.json');
            $env = json_decode($env, true);

            $this->client = new GuzzleClient([
                'base_uri'  => 'https://id.twitch.tv/oauth2/',
                'headers'   => [
                    'Authorization' => 'Bearer ' . $env['twitch_token'],
                ],
            ]);

            $this->configLoaded = true;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->configLoaded) {
            $validateResponse = $this->client->request('GET', 'validate');

            if ($validateResponse->getStatusCode() === 200) {
                $validateResponseBody = json_decode($validateResponse->getBody()->getContents());

                if (! empty($validateResponseBody) && ! empty($validateResponseBody->expires_in)) {
                    if ($validateResponseBody->expires_in <= 86400) {
                        $tokenResponse = $this->client->post('token', [
                            'form_params' => [
                                'client_id'     => config('app.twitch.id'),
                                'client_secret' => config('app.twitch.secret'),
                                'grant_type'    => 'client_credentials',
                            ],
                            'headers' => [
                                'Content-Type' => 'application/x-www-form-urlencoded',
                            ],
                        ]);

                        if ($tokenResponse->getStatusCode() === 200) {
                            $tokenResponseBody = json_decode($tokenResponse->getBody()->getContents());

                            if (! empty($tokenResponseBody) && ! empty($tokenResponseBody->access_token)) {
                                $this->updateEnv([
                                    'twitch_token' => $tokenResponseBody->access_token,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return 0;
    }

    /**
     * Update our .env File.
     *
     * @param array $data
     */
    protected function updateEnv($data = []): bool
    {
        if (! empty($data)) {
            $env = file_get_contents(base_path() . '/twitch.json');
            $env = json_decode($env, true);

            foreach ($data as $key => $value) {
                foreach ($env as $env_key => $env_value) {
                    if ($env_key === $key) {
                        $env[$env_key] = $value;
                    } else {
                        $env[$env_key] = $env_value;
                    }
                }
            }

            file_put_contents(base_path() . '/twitch.json', json_encode($env));

            return true;
        } else {
            return false;
        }
    }
}
