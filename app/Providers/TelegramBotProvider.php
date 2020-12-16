<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use function PHPUnit\Framework\once;

class TelegramBotProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '\Helpers\TelegramBot.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
