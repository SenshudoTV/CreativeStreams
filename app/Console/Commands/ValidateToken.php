<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
     * Http Client.
     */
    protected $client;

    /**
     * Twitch Config File loaded.
     */
    protected bool $configLoaded = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (File::exists(base_path() . '/twitch.json')) {
            $env = json_decode(File::get(base_path() . '/twitch.json'));

            $this->client = Http::withOptions(['base_uri' => 'https://id.twitch.tv/oauth2/'])
                ->withToken($env->twitch_token)
                ->acceptJson();

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
            $validateResponse = $this->client->get('validate');

            if ($validateResponse->successful()) {
                $validateResponseBody = $validateResponse->object();

                if (! empty($validateResponseBody) && ! empty($validateResponseBody->expires_in)) {
                    if ($validateResponseBody->expires_in <= 86400) {
                        $tokenResponse = $this->client
                            ->asForm()
                            ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
                            ->post('token', [
                                'client_id'     => config('services.twitch.id'),
                                'client_secret' => config('services.twitch.secret'),
                                'grant_type'    => 'client_credentials',
                            ]);

                        if ($tokenResponse->successful()) {
                            $tokenResponseBody = $tokenResponse->object();

                            if (! empty($tokenResponseBody) && ! empty($tokenResponseBody->access_token)) {
                                $this->updateEnv([
                                    'twitch_token' => $tokenResponseBody->access_token,
                                ]);
                            }
                        } else {
                            return Command::FAILURE;
                        }
                    }
                }
            } else {
                return Command::FAILURE;
            }

            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }
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
