<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FacturaController;

// Rutas de la API — cada una genera automáticamente:
// GET    /clientes         → index   (listar todos)
// POST   /clientes         → store   (crear nuevo)
// GET    /clientes/{id}    → show    (ver uno)
// PUT    /clientes/{id}    → update  (editar)
// DELETE /clientes/{id}    → destroy (eliminar)

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('facturas', FacturaController::class);