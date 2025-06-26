<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barangs';

    public $fillable = [
        'nama',
        'barcode',
        'satuan',
        'version',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'barang_id', 'id');
    }
    
    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                return $this->stocks()->sum('balance');
            }
        );
    }
}
