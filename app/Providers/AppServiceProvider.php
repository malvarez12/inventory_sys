<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\Breadcrumbs\Breadcrumbs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\TransactionsTable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    public function boot(): void
{
    Livewire::component('transactions-table', TransactionsTable::class);
}
    
}



// public function boot(): void
// {
//     Paginator::useBootstrapFive();

//     Request::macro('breadcrumbs', function (){
//         return new Breadcrumbs($this);
//     });

// }