<?php

namespace Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Support\valueObjects\Price;

class PriceCast implements CastsAttributes
{
    public function get($model, string $key, mixed $value, array $attributes): Price
    {
        return Price::make($value);
    }

    public function set($model, string $key, mixed $value, array $attributes): int
    {
        if(!$value instanceof Price){
            $value = Price::make($value);
        }
        return $value->raw();
    }
}
