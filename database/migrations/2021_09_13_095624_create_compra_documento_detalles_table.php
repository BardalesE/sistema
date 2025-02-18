<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraDocumentoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_documento_detalles', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('documento_id')->unsigned();
            $table->foreign('documento_id')
                  ->references('id')->on('compra_documentos')
                  ->onDelete('cascade');

            $table->unsignedInteger('producto_id')->unsigned();
            $table->string('codigo_producto')->nullable();
            $table->string('descripcion_producto');
            $table->string('presentacion_producto')->nullable();
            $table->string('medida_producto');

            $table->BigInteger('cantidad');
            $table->date('fecha_vencimiento');

            $table->string('lote');
            $table->unsignedInteger('lote_id')->unsigned()->nullable();
            $table->foreign('lote_id')->references('id')->on('lote_productos')->onDelete('cascade');

            $table->unsignedDecimal('precio', 15,4);
            $table->unsignedDecimal('costo_flete', 15,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra_documento_detalles');
    }
}
