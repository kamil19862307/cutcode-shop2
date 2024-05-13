<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use DomainException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function index(): Factory|View|Application
    {
        // Берём все товары из корзины
        $items = cart()->items();

        if ($items->isEmpty())
            throw new DomainException('Корзина пуста');

        return view('order.index', [
            // Передаём все товары
            'items' => $items,

            // Передаём все методы оплаты
            'payments' => PaymentMethod::query()->get(),

            // Передаём все типы доставки
            'deliveries' => DeliveryType::query()->get()
        ]);
    }

    public function handle(OrderFormRequest $request): RedirectResponse
    {
        return redirect()->route('home');
    }
}
