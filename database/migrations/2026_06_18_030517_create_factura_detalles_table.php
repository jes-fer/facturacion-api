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
         Schema::create('factura_detalles', function (Blueprint $table) {
        $table->id();
        
        // Relación con factura y producto
        $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
        $table->foreignId('producto_id')->constrained('productos');
        
        $table->integer('cantidad');
        $table->decimal('precio_unitario', 10, 2); // Precio al momento de facturar
        $table->decimal('subtotal', 10, 2);        // cantidad * precio_unitario
        $table->decimal('iva', 10, 2);             // subtotal * 0.16
        $table->decimal('total', 10, 2);           // subtotal + iva
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_detalles');
    }
};
