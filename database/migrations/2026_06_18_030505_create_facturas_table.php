<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
        $table->id(); // Clave primaria autoincremental
        
        // Relación con clientes — el foreign key
        $table->foreignId('cliente_id')->constrained('clientes');
        
        $table->string('folio')->unique(); // Número de factura único ej: FAC-001
        $table->date('fecha');
        
        // Totales — decimal(10,2) = hasta 99,999,999.99
        $table->decimal('subtotal', 10, 2);
        $table->decimal('iva', 10, 2);      // Aquí es donde vive el tema de los centavos
        $table->decimal('total', 10, 2);
        
        $table->enum('estatus', ['pendiente', 'pagada', 'cancelada'])->default('pendiente');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
