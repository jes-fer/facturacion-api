<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'clave_sat',
        'descripcion',
        'precio',
        'iva',
        'activo',
    ];

    // Convierte automáticamente estos campos al tipo correcto
    protected $casts = [
        'precio' => 'decimal:2',
        'iva'    => 'decimal:2',
        'activo' => 'boolean',
    ];
}