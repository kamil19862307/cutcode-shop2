<?php

namespace Support\valueObjects;

use InvalidArgumentException;
use Stringable;
use Support\Traits\Makeable;

class Price implements Stringable
{
    use Makeable;

    // Доступные валюты
    private array $currencies = [
        'RUB' => '₽'
    ];

    public function __construct(
        // Сама цена
        private readonly int $value,

        // Код валюты
        private readonly string $currency = 'RUB',

        // Сколько знаков после запятой (по умолчанию 2 знака, умножать либо делить на 100)
        private readonly int $precision = 100,
    )
    {
        if ($value < 0){
            throw new InvalidArgumentException('Price must be more than zero');
        }

        if (!isset($this->currencies[$this->currency])){
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

    public function raw(): int
    {
        return $this->value;
    }

    public function value(): float|int
    {
        return $this->value / $this->precision;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function symbol()
    {
        return $this->currencies[$this->currency];
    }

    public function __toString()
    {
        return number_format($this->value(), 0, ',', ' ')
            . ' ' . $this->symbol();
    }
}
