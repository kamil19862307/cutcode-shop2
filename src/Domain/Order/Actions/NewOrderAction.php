<?php

namespace Domain\Order\Actions;

use App\Http\Requests\OrderFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\DTOs\OrderCustomerDTO;
use Domain\DTOs\OrderDTO;
use Domain\Order\Models\Order;

final class NewOrderAction
{
//    public function __invoke(OrderFormRequest $request): Order
    public function __invoke(OrderDTO $order, OrderCustomerDTO $customer, bool $create_account): Order
    {
        $registerAction = app(RegisterNewUserContract::class);

//        $customer = $request->get('customer');
//
//        if ($request->boolean('create_account')){
//            $registerAction(NewUserDTO::make(
//                $customer['first_name'] . ' ' . $customer['last_name'],
//                $customer['email'],
//                $request->get('password')
//            ));
//        }

        if ($create_account){
            $registerAction(NewUserDTO::make(
                $customer->fullName(),
                $customer->email,
                $order->password
            ));
        }

        return Order::query()->create([
//           'user_id' => auth()->id(),
//            'payment_method_id' => $request->get('payment_method_id'),
//            'delivery_type_id' => $request->get('delivery_type_id'),
            'payment_method_id' => $order->payment_method_id,
            'delivery_type_id' => $order->delivery_type_id,
        ]);
    }
}
