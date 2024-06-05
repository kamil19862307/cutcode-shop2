<?php

namespace Support;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

class Transaction
{
    public static function run(
        // Замыкание. Все механизмы, которые будут внутри в рамках транзакций, которые нужно выполнить
        Closure $callback,

        // Колбек, который будет вызываться если всё ОК, но его может и не быть
        Closure $finished = null,

        // Если событие с ошибкой catch выпал. То выполнить это.
        Closure $onError = null
    )
    {
        try {
            DB::beginTransaction();

            // Это с хелпером tap
//            return tap($callback(), function ($result) use ($finished){
//                if (!is_null($finished)){
//                    $finished($result);
//                }
//
//                DB::commit();
//            });

            // Без хелпера tap
            $result = $callback();

            DB::commit();

            if (!is_null($finished)){
                $finished($result);
            }

            return $result;

        } catch (Throwable $e){
            DB::rollBack();

            if (!is_null($onError)){
                $onError($e);
            }

            throw $e;
        }
    }
}
