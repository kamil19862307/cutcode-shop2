<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class IndexController extends Controller
{
    public function index(): View
    {
//        abort(404);
//        throw new TelegramBotApiException('123');
//        logger()->channel('telegram')->info('123');
        return view('index');
    }
}
