<?php

namespace App\Providers;

use App\Models\Animal;
use App\Models\MarketplaceChat;
use App\Models\MarketplaceListing;
use App\Policies\AnimalPolicy;
use App\Policies\MarketplaceChatPolicy;
use App\Policies\MarketplaceListingPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Animal::class => AnimalPolicy::class,
        MarketplaceListing::class => MarketplaceListingPolicy::class,
        MarketplaceChat::class => MarketplaceChatPolicy::class,
    ];

    public function boot()
    {
        Paginator::useBootstrap();
    }
}
