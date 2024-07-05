<?php

namespace Domain\DTOs;

use Support\Traits\Makeable;

class OrderDTO
{
    use Makeable;

    public function __construct(
        readonly readonly int $payment_method_id,
        readonly readonly int $delivery_type_id,
        readonly readonly string $password,
    )
    {

    }

}
