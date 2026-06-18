<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // GET /api/clientes — devuelve todos los clientes
    public function index()
    {
        return response()->json(Cliente::all(), 200);
    }

    // POST /api/clientes — crea un nuevo cliente
    public function store(Request $request)
    {
        // Validamos los datos antes de guardar
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'rfc'       => 'required|string|max:13|unique:clientes',
            'email'     => 'nullable|email',
            'telefono'  => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json($cliente, 201); // 201 = Created
    }

    // GET /api/clientes/{id} — devuelve un cliente específico
    public function show(Cliente $cliente)
    {
        // Laravel automáticamente busca el cliente por ID
        return response()->json($cliente, 200);
    }

    // PUT /api/clientes/{id} — actualiza un cliente
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre'    => 'sometimes|string|max:255',
            'rfc'       => 'sometimes|string|max:13|unique:clientes,rfc,' . $cliente->id,
            'email'     => 'nullable|email',
            'telefono'  => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ]);

        $cliente->update($validated);

        return response()->json($cliente, 200);
    }

    // DELETE /api/clientes/{id} — elimina un cliente
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente'], 200);
    }
}