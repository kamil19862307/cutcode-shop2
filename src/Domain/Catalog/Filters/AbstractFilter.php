<?php

namespace Domain\Catalog\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Stringable;

abstract class AbstractFilter implements Stringable
{
    public function __invoke(Builder $query, $next)
    {
        $this->apply($query);

        $next($query);
    }

    // Название фильтра Цена, Бренды
    abstract public function title(): string;

    // Ключ filters['price'], filters['brands']
    abstract public function key(): string;

    // Сам запрос
    abstract public function apply(Builder $query): Builder;

    // Значения
    abstract public function values(): array;

    // Разные вьюхи для разных фильтров
    abstract public function view(): string;

    // Формирую запрос + отображать + проверять активность.
    public function requestValue(string $index = null, mixed $default = null): mixed
    {
        return request('filters.' . $this->key() . ($index ? ".$index" : ""), $default);
    }

    // Получить name инпута
    public function name(string $index = null): string
    {
        // name="filters[key]?[index]"
        return str($this->key())
            ->wrap('[', ']')
            ->prepend('filters')
            ->when($index, fn($str) => $str->append("[$index]"))
            ->value();
    }

    // Получить id инпута
    public function id(string $index = null): string
    {
        return str($this->name($index))
            ->replace(
                ['[', ']'],
                ['-', '']
            )
            ->value();
    }

    public function __toString(): string
    {
        return view($this->view(), [
            'filter' => $this
        ])->render();
    }
}
