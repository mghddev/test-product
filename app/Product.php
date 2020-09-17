<?php
namespace App;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category',
        'productName',
        'price',
        'description',
        'quantity',
        'created_at',
        ];
}
