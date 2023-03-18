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
        Model::preventLazyLoading(!app()->isProduction());

        //Выдаст exeption, если будем сохранять какое либо поле которого нет в свойстве $fillable[]
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        //Сообщит, если запрос (один имеется ввиду) к базе дольше чем указанное количество миллисекунд
        DB::whenQueryingForLongerThan(500, function (Connection $connection, QueryExecuted $event) {
            logger()->channel('telegram')->debug('whenQueryingForLongerThan' . $connection->query()->toSql());
        });

        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function (){
                logger()->channel('telegram')->debug('whenRequestLifecycleIsLongerThan' . request()->url());
            }
        );
    }
}
