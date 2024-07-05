<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Domain\DTOs\OrderCustomerDTO;
use Domain\DTOs\OrderDTO;
use Domain\Order\Actions\NewOrderAction;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use Domain\Order\Processes\AssignCustomer;
use Domain\Order\Processes\AssignProducts;
use Domain\Order\Processes\ChangeStateToPending;
use Domain\Order\Processes\CheckProductQuantities;
use Domain\Order\Processes\ClearCart;
use Domain\Order\Processes\DecreaseProductsQuantities;
use Domain\Order\Processes\OrderProcess;
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

    /**
     * @throws \Throwable
     */
    public function handle(OrderFormRequest $request, NewOrderAction $action): RedirectResponse
    {
//        $order = $action($request);

        $order = $action(
            OrderDTO::make(...$request->only(['payment_method_id', 'delivery_type_id', 'password'])),
            OrderCustomerDTO::fromArray($request->get('customer')),
            $request->boolean('create_account')
        );

        (new OrderProcess($order))->processes([
            // Процессы в ходе оформления заказа
            new CheckProductQuantities(),
            new AssignCustomer(request('customer')),
            new AssignProducts(),
            new ChangeStateToPending(),
            new DecreaseProductsQuantities(),
            new ClearCart()
        ])->run();

        return redirect()->route('home');
    }
}
