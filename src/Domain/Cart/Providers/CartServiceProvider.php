<?php

namespace Domain\Cart\Providers;

use Domain\Cart\CartManager;
use Domain\Cart\StorageIdentities\SessionIdentityStorage;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // зарегаем синглтон с корзиной и вернём инстанс с уже с указанным кртменеджером
        // где передадим сессию с айдишником
        $this->app->singleton(CartManager::class, function (){
            return new CartManager(new SessionIdentityStorage());
        });

    }

    public function register(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
    }
}
