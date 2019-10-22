<?php
namespace App\Providers;

use App\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
/**
* Bootstrap any application services.
*
* @return void
*/
public function boot()
{

    View::composer('layouts.frontend.partial.footer',function ($view){


        $view->with('categories', Category::all());

    });


Schema::defaultStringLength(191);
}

/**
* Register any application services.
*
* @return void
*/
public function register()
{
//
}
}
