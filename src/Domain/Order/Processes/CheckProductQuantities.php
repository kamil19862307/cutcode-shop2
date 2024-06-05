<?php

namespace Domain\Order\Processes;

use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Exeptions\OrderProcessException;
use Domain\Order\Models\Order;

class CheckProductQuantities implements OrderProcessContract
{

    public function handle(Order $order, $next)
    {
        foreach (cart()->items() as $item) {
            if ($item->product->quantity < $item->quantity){
                throw new OrderProcessException('Не хватает товара на складе');
            }
        }

        return $next($order);
    }
}
