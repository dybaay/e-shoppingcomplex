<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Shop;
use App\Observers\ShopObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::useModel('Category', \App\Models\Category::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Shop::observe(ShopObserver::class);

        if(Schema::hasTable('categories')) {

            $categories = cache()->remember('categories','3600', function(){
                return Category::whereNull('parent_id')->get();
            });

            view()->share('categories', $categories);
        }

    }
}
