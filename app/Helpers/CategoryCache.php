<?php

namespace App\Helpers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryCache
{
    const CACHE_KEY = 'categories_all';
    const CACHE_TTL = 3600; 

    
    public static function all()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Category::all();
        });
    }

    
    public static function flush()
    {
        return Cache::forget(self::CACHE_KEY);
    }
}
