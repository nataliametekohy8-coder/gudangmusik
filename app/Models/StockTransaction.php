<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'note',
        'transaction_date',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
        ];
    }

    /**
     * Relasi: transaksi stok milik satu produk.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi: transaksi stok dicatat oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

