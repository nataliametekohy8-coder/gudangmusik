<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'brand',
        'stock',
        'min_stock',
        'buy_price',
        'sell_price',
    ];

    /**
     * Relasi: produk dimiliki oleh satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: produk dimiliki oleh satu supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi: satu produk memiliki banyak transaksi stok.
     */
    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }
}

