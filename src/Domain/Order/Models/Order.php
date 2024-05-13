<?php

namespace Domain\Order\Models;

use Domain\Auth\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Support\Casts\PriceCast;
use Domain\Order\Enums\OrderStatuses;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_type_id',
        'payment_method_id',
        'amount',
        'status'
    ];

    protected $casts = [
        'amount' => PriceCast::class
    ];

    protected $attributes = [
        'status' => 'new'
    ];

    public function status(): Attribute
    {
        return Attribute::make(
            // Гет и стрелочная функция в которой текущее значение нашего стейта статус, и далее
            // идём от нашего енама OrderStatuses. Тоесть мы енам создаём из значения из OrderStatuses енама.
            // Тем самым мы создали каст, который в себе в рамках модели таит енам, но нам нужен стейт.
            // Поэтому далее вызываем createState и передаём себя ($this) текущий заказ
            get: fn(string $value) => OrderStatuses::from($value)->createState($this)
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryType(): BelongsTo
    {
        return $this->belongsTo(DeliveryType::class);
    }

    public function paymentMethods(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderCustomer(): HasOne
    {
        return $this->hasOne(OrderCustomer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
