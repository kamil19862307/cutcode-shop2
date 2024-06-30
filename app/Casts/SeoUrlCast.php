<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SeoUrlCast implements CastsAttributes
{

    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        return parse_url($value, PHP_URL_PATH) ?? '/';
    }
}
