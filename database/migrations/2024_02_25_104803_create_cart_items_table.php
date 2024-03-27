<?php

use App\Models\OptionValue;
use App\Models\Product;
use Domain\Cart\Models\Cart;
use Domain\Cart\Models\CartItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Для начала внешний(foreign) ключ на саму корзину
            $table->foreignIdFor(Cart::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Потом внешний(foreign) ключ на сам товар, он всегда будет присутствовать
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedBigInteger('price');

            $table->integer('quantity');

            // Чтоб уменьшить запросы к базе и упростить жизнь
            $table->string('string_option_values')
                ->nullable();

            $table->timestamps();
        });

            // Связующая таблица между cart_item и вариациями (цвет, размер и тд)
            Schema::create('cart_item_option_value', function (Blueprint $table) {
                $table->id();

                $table->foreignIdFor(CartItem::class)
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreignIdFor(OptionValue::class)
                    ->constrained()
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->timestamps();
            });
    }

    public function down(): void
    {
        if(app()->isLocal()){
            Schema::dropIfExists('cart_items');
        }
    }
};
