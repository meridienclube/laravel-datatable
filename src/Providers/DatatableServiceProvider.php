<?php

namespace ConfrariaWeb\Datatable\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DatatableServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'datatable');
        Blade::component('datatable::components.datatable', 'datatable');
    }

    public function register()
    {

    }

}
