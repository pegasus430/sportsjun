<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Payum\LaravelPackage\Storage\EloquentStorage;

class payumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
        
          $this->app->resolving('payum.builder', function(\Payum\Core\PayumBuilder $payumBuilder) {
        $payumBuilder
        // this method registers filesystem storages, consider to change them to something more
        // sophisticated, like eloquent storage
        ->addDefaultStorages()
        ->addStorage(Payment::class, new EloquentStorage(Payment::class))
        ->addGateway('paypal_ec', [
            'factory' => 'paypal_express_checkout',
            'username' => 'ngambsastephane-facilitator_api1.gmail.com',
            'password' => 'SKU587Y9PEY29A73',
            'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AUYKM8TVb9eGNBNzGoer8hjdgtnO',
            'sandbox' => true
            ]);
        });

        
 
    }
}
