<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;

class BotManController
{
    public function handle(Request $request)
    {

        // POST: incoming message from the web widget -> let BotMan handle it
        DriverManager::loadDriver(WebDriver::class);
        $config = []; 
        $botman = BotManFactory::create($config);

        // register hears handlers (or move these to a service provider if you prefer)
        $botman->hears('Hello BotMan!', function($bot) {
            $bot->reply('Hello!');
            $bot->ask('Whats your name?', function($answer, $bot) {
                $bot->say('Welcome '.$answer->getText());
            });
        });

        $botman->listen();
    }
}