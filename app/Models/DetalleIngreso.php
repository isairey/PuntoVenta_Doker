<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    use HasFactory;
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'idingreso',
        'idarticulo',
        'cantidad',
        'precio'
    ];
    public $timestamps = false;
}
