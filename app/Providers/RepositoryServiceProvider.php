<?php

namespace App\Providers;

use App\Contracts\AttributeContract;
use App\Contracts\CategoryContract;
use App\Repositories\AttributeRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    // why do we need to do this? I should read this article: https://www.larashout.com/how-to-use-repository-pattern-in-laravel

    protected $repositories = [
        CategoryContract::class  => CategoryRepository::class,
        AttributeContract::class => AttributeRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
