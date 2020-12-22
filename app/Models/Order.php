<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'notes'
    ];

    public function orderItens()
    {
        return $this->hasMany('App\OrderItem');
    }
    
}
