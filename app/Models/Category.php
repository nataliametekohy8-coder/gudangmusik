<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = ['name'];

    /**
     * Relasi: satu kategori memiliki banyak produk.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

