<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductJsonProperties implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Product $product)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // и после того как он создан, нужно сгенерировать характеристики в Json_properties
        // будет достаточно массива с ключём с названием характеристики и значением. Поэтому из
        // коллекции делаю массив с ключами

        $properties = $this->product->properties
            ->mapWithKeys(fn($property) => [$property->title => $property->pivot->value]);

        // updateQuietly чтобы не дёргать события, так как на updated также может висеть событие, которое будет
        // генерировать properties, что в целом и будет. И указывам json_properties чтобы наполнился новым
        // массивом
        $this->product->updateQuietly(['json_properties' => $properties]);
    }

    public function uniqueId()
    {
        return $this->product->getKey();
    }
}
