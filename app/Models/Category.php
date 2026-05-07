<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function getColorClassAttribute()
    {
        return preg_match('/^(#|rgb\(|hsl\()/i', $this->color) ? '' : $this->color;
    }

    public function getColorStyleAttribute()
    {
        return preg_match('/^(#|rgb\(|hsl\()/i', $this->color) ? 'background-color: ' . $this->color . ';' : '';
    }

    public function articles() {
        return $this->hasMany(Article::class);
    }
}
