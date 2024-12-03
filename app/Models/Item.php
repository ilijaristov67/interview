<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'amount',
    ];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }
}
