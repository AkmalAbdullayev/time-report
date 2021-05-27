<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetTelegramWebHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
	//setWebhook
	$response = \Http::get('https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/setWebhook');
	print_r($response->object());
    }
}
