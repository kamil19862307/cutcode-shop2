<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Выдаст exeption, если мы гдо-то забудем игрлоды (Eager loader)
        //Выдаст exeption, если будем сохранять какое либо поле которого нет в свойстве $fillable[]
        Model::shouldBeStrict(!app()->isProduction());

//        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        if (app()->isProduction()) {
            //Это в целом коннект - от его открытия до его завершения (уберём)
//             DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {
//                 logger()
//                      ->channel('telegram')
//                      ->debug('Метод: whenQueryingForLongerThan, вызвал это исключение: ' . $connection->totalQueryDuration());
//                 });

            //Сообщит, если запрос (один имеется ввиду) к базе дольше чем указанное количество миллисекунд
            DB::listen(function ($query) {
            // $query->sql;
            // $query->bindings;
            // $query->time;

                if ($query->time > 100) {
                    logger()
                        ->channel('telegram')
                        ->debug('DB::listen - запрос дольше указанного времени: ' . $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()->channel('telegram')->debug('whenRequestLifecycleIsLongerThan' . request()->url());
                }
            );
        }
    }
}
