<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'country'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
