<?php

namespace Zhiyi\Plus\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // dd($this->app);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Overtrue\EasySms\EasySms::class, function ($app) {
            return new \Overtrue\EasySms\EasySms(
                $app->config['sms']
            );
        });
        $this->app->extend(\Illuminate\Notifications\ChannelManager::class, function ($channel) {
            $channel->extend('sms', function ($app) {
                return $app->make(\Zhiyi\Plus\Notifications\Channels\SmsChannel::class);
            });

            return $channel;
        });
    }
}
