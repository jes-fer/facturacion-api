<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Producto;
use App\Models\FacturaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    // GET /api/facturas
    public function index()
    {
        // Traemos la factura con su cliente y sus detalles
        $facturas = Factura::with(['cliente', 'detalles.producto'])->get();
        return response()->json($facturas, 200);
    }

    // POST /api/facturas
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id'            => 'required|exists:clientes,id',
            'fecha'                 => 'required|date',
            'productos'             => 'required|array|min:1',
            'productos.*.id'        => 'required|exists:productos,id',
            'productos.*.cantidad'  => 'required|integer|min:1',
        ]);

        // DB::transaction — si algo falla, revierte todo
        // Nunca quedará una factura sin detalles ni viceversa
        $factura = DB::transaction(function () use ($validated) {

            $subtotalTotal = 0;
            $ivaTotal = 0;
            $detalles = [];

            foreach ($validated['productos'] as $item) {
                $producto = Producto::findOrFail($item['id']);

                $subtotal = $producto->precio * $item['cantidad'];

                // Aquí está el cálculo correcto de centavos
                // round(..., 2) evita errores de punto flotante
                $iva = round($subtotal * ($producto->iva / 100), 2);
                $total = round($subtotal + $iva, 2);

                $subtotalTotal += $subtotal;
                $ivaTotal += $iva;

                $detalles[] = [
                    'producto_id'     => $producto->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => round($subtotal, 2),
                    'iva'             => $iva,
                    'total'           => $total,
                ];
            }

            // Generamos folio automático — FAC-0001, FAC-0002...
            $folio = 'FAC-' . str_pad(Factura::count() + 1, 4, '0', STR_PAD_LEFT);

            // Creamos la factura
            $factura = Factura::create([
                'cliente_id' => $validated['cliente_id'],
                'folio'      => $folio,
                'fecha'      => $validated['fecha'],
                'subtotal'   => round($subtotalTotal, 2),
                'iva'        => round($ivaTotal, 2),
                'total'      => round($subtotalTotal + $ivaTotal, 2),
                'estatus'    => 'pendiente',
            ]);

            // Guardamos los detalles
            $factura->detalles()->createMany($detalles);

            return $factura->load(['cliente', 'detalles.producto']);
        });

        return response()->json($factura, 201);
    }

    // GET /api/facturas/{id}
    public function show(Factura $factura)
    {
        return response()->json(
            $factura->load(['cliente', 'detalles.producto']),
            200
        );
    }

    // PUT /api/facturas/{id} — solo permite cambiar estatus
    public function update(Request $request, Factura $factura)
    {
        $validated = $request->validate([
            'estatus' => 'required|in:pendiente,pagada,cancelada',
        ]);

        $factura->update($validated);

        return response()->json($factura, 200);
    }

    // DELETE — no se permiten eliminar facturas, solo cancelar
    public function destroy(Factura $factura)
    {
        return response()->json([
            'message' => 'Las facturas no se eliminan. Use estatus cancelada.'
        ], 403); // 403 = Forbidden
    }
}