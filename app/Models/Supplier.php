<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'phone', 'address'];

    /**
     * Relasi: satu supplier memasok banyak produk.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

