<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // GET /api/productos — lista todos los productos activos
    public function index()
    {
        return response()->json(Producto::where('activo', true)->get(), 200);
    }

    // POST /api/productos — crea un nuevo producto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'clave_sat'   => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            // IVA por defecto 16% si no se manda
            'iva'         => 'nullable|numeric|min:0|max:100',
            'activo'      => 'nullable|boolean',
        ]);

        $producto = Producto::create($validated);

        return response()->json($producto, 201);
    }

    // GET /api/productos/{id}
    public function show(Producto $producto)
    {
        return response()->json($producto, 200);
    }

    // PUT /api/productos/{id}
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'clave_sat'   => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'precio'      => 'sometimes|numeric|min:0',
            'iva'         => 'nullable|numeric|min:0|max:100',
            'activo'      => 'nullable|boolean',
        ]);

        $producto->update($validated);

        return response()->json($producto, 200);
    }

    // DELETE /api/productos/{id} — desactiva en lugar de borrar
    public function destroy(Producto $producto)
    {
        // Soft delete — NO borrar, solo desactiva
        // Nunca borrar productos ocn hsitorial
        $producto->update(['activo' => false]);

        return response()->json(['message' => 'Producto desactivado correctamente'], 200);
    }
}