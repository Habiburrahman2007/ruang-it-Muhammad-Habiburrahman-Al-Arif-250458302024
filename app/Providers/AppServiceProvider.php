<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use App\Http\Middleware\CheckBanned;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentColor; 

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

    
    public function boot(): void
    {
        if (class_exists(\Livewire\Livewire::class)) {
        
        \Livewire\Livewire::addPersistentMiddleware([
            CheckBanned::class,
        ]);
    }
    }
}
