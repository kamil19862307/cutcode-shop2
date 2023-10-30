<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ProductController extends Controller
{
    public function __invoke(Product $product): View|Application|Factory
    {
        $product->load(['optionValues.option']);

        $options = $product->optionValues->mapToGroups(function ($item){
            return [$item->option->title => $item];
        });

        $viewed = session('viewed', []);

        if(!empty(session('viewed'))){
            $viewed = Product::query()
                ->where(function ($q) use ($product){
                    $q->whereIn('id', session('viewed'))
                        ->where('id', '!=', $product->id);
                })
                ->limit(4)
                ->get();
        }

        session()->put('viewed.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $options,
            'viewed' => $viewed
        ]);
    }
}
