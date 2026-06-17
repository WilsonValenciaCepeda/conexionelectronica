<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
        'imagen',
        'en_oferta',
        'precio_oferta'
    ];

    protected $casts = [
        'en_oferta' => 'boolean',
        'precio' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
    ];

    public function carrito()
    {
        return $this->hasMany(Carrito::class);
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function getPrecioFinalAttribute()
    {
        if ($this->en_oferta && $this->precio_oferta) {
            return $this->precio_oferta;
        }
        return $this->precio;
    }

    public function getEstaEnOfertaAttribute()
    {
        return $this->en_oferta && $this->precio_oferta && $this->precio_oferta < $this->precio;
    }
}