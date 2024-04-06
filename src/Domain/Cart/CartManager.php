<?php

namespace Domain\Cart;

use App\Models\Product;
use Domain\Cart\Contracts\CartIdentityStorageContract;
use Domain\Cart\Models\Cart;
use Domain\Cart\Models\CartItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Support\valueObjects\Price;

class CartManager
{
    public function __construct(protected CartIdentityStorageContract $identityStorage)
    {
    }

    private function cacheKey(): string
    {
        return str('cart_' . $this->identityStorage->get())
            // преобразуем в слаг с нижним подчёркиванием
            ->slug('_')
            // и вернём строку
            ->value();
    }

    private function forgetCache(): void
    {
        Cache::forget($this->cacheKey());
    }

    public function storedData(string $id): array
    {
        // По умолчанию массив со сторейдж айди

        $data = [
            'storage_id' => $id
        ];

        // Если авторизованный пользователь, то в $data добавим юзер айди на сохранение
        if (auth()->check())
            $data['user_id'] = auth()->id();

        return $data;
    }

    public function updateStorageId(string $old, string $current): void
    {
        Cart::query()
            ->where('storage_id', $old)
            ->update($this->storedData($current));
    }

    private function stringedOptionValues(array $optionValues = []): string
    {
        sort($optionValues);

        return implode(';', $optionValues);
    }

    // товар, количество и массив с выбранными опциями (id => value)
    public function add(Product $product, $quantity = 1, array $optionValues = []): Model|Builder
    {
        // Сначала получим сущность корзины, либо создадим, либо проабдейтим (чтобы при добавлении менять
        // таймстемпы на апдейтед и если придёт id аутентифицированного пользователя, мы её также
        // в последующем на одну из добавлений проставим)
        $cart = Cart::query()
            ->updateOrCreate([
                // storage_id у нас всегда присутствует, в отличии от пользователя (может быть и гостем)
                // Параметры на create и update это также сторейдж айди и юзер айди
                'storage_id' => $this->identityStorage->get()
            ], $this->storedData($this->identityStorage->get()));


        // Родительская сущность у нас есть, теперь переходим к самому товару в корзине
        $cartItem = $cart->cartItems()->updateOrCreate([
            'product_id' => $product->getKey(),
            'string_option_values' => $this->stringedOptionValues($optionValues)
        ], [
            'price' => $product->price,
            'quantity' => DB::raw("quantity + $quantity"),
            'string_option_values' => $this->stringedOptionValues($optionValues)
        ]);

        $cartItem->optionValues()->sync($optionValues);

        $this->forgetCache();

        return $cart;
    }

    public function quantity(CartItem $item, int $quantity = 1): void
    {


        $item->update([
            'quantity' => $quantity
        ]);

        $this->forgetCache();
    }

    public function delete(CartItem $item): void
    {
        $item->delete();

        $this->forgetCache();
    }

    public function truncate(): void
    {
        $this->get()?->delete();

        $this->forgetCache();
    }

    // Получаем текущюю корзину
    private function get()
    {
        return Cache::remember($this->cacheKey(), now()->addHour(), function (){
            // Возвращаем запрос
            return Cart::query()
                // иегрлоад, нам потребуются все cartItems. cartItems она у нас родительская сущность, это все товары
                // точно потребуются
                ->with('cartItems')
                // условие где storage_id наш соотведственно идентификатор $this->identityStorage->get()
                ->where('storage_id', $this->identityStorage->get())
                // Проверим что пользователь авторизован и если да, то добавим запрос на выбор корзины именно текущего
                // пользователя. 'Или где' orWhere, малоли, может впоследующем изменим логику и будем работать
                // исключительно от user_id и он у нас должен быть всегда авторизован для работы с корзиной,
                // поэтому поставили здесь 'или' orWhere
                ->when(auth()->check(), fn(Builder $query) => $query->orWhere('user_id', auth()->id()))
                // Выполняем запрос и получаем первый результат
                ->first() ?? false;
        });
    }

    public function items(): Collection
    {
        // Если текущей корзины вообще нет, то возвращаем сформированную самостоятельно коллекцию, чтоб
        // всегда были коллекции
        if (!$this->get()){
            return collect();
        }

        return CartItem::query()
            ->with(['product', 'optionValues.option'])
            // Только товары текущей корзины
            ->whereBelongsTo($this->get())
            ->get();
    }

    public function cartItems(): Collection
    {
        // Если есть, то возвращаем cartItems, а если нет, то коллекцию, чтоб на выходе всегда была коллекция.
        return $this->get()?->cartItems ?? collect([]);
    }

    public function count(): int
    {
        // Используем sum, а не count потому что у нас есть и quantity в таблице, может быть несколько
        // одинаковых товаров. В рамках коллекции мы указали что нам нужна сумма, а в анонимной
        // функции указали что будем считать по полю quantity
        return $this->cartItems()->sum(function ($item){
            return $item->quantity;
        });
    }

    public function amount(): Price
    {
        // Получаем сумму всех сум
        return Price::make(
            $this->cartItems()->sum(function ($item){
                return $item->amount->raw();
            })
        );
    }
}
