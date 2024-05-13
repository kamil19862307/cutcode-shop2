<?php

namespace Domain\Order\Actions;

use App\Http\Requests\OrderFormRequest;
use Domain\Order\Models\Order;

class NewOrderAction
{
    public function __invoke(OrderFormRequest $request): Order
    {
        // TODO: Implement __invoke() method.
    }
}
