<?php

namespace Domain\Cart\Models;

use App\Models\OptionValue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Casts\PriceCast;
use Support\valueObjects\Price;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'price',
        'quantity',
        'string_option_values'
    ];

    // И каст
    protected $casts = [
        'price' => PriceCast::class
    ];

    // ->raw() чтобы вернуть integer
    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn() => Price::make(
                $this->price->raw() * $this->quantity
            )
        );
    }

    // Связь на родителькую корзину
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    // Связь и на товар
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Связь и на option_values
    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }
}
