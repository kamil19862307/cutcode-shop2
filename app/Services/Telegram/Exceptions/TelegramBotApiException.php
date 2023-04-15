<?php

namespace App\Services\Telegram\Exceptions;

use Exception;
use Illuminate\Http\Request;

class TelegramBotApiException extends Exception
{
    public function report()
    {
        //отсюда можем отправлять в сентри, телескоп либо в сингканал
    }

    public function render(Request $request)
    {
        return response()->json([]);
    }
}
