<?php

namespace App\Models;

use App\Jobs\ProductJsonProperties;
use Domain\Catalog\Facades\Sorter;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pipeline\Pipeline;
use Laravel\Scout\Attributes\SearchUsingFullText;
//use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'price',
        'thumbnail',
        'on_home_page',
        'sorting',
        'text',
        'json_properties',
        'quantity'
    ];

    protected $casts = [
        'price' => PriceCast::class,
        'json_properties' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Product $product){
            ProductJsonProperties::dispatch($product)
                ->delay(now()->addSeconds(10));
        });
    }

    protected function thumbnailDir(): string
    {
        return 'products';
    }

//    #[SearchUsingPrefix(['id'])]
    #[SearchUsingFullText(['title', 'text'])]
    public function toSearchableArray(): array
    {
        return [
//            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
        ];
    }

    public function scopeFiltered(Builder $query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through(filters())
            ->thenReturn();
    }

    public function scopeSorted(Builder $query)
    {
        Sorter::run($query);
    }

    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(8);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)
            ->withPivot('value');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }
}
