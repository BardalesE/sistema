<?php

namespace App\Ventas\Documento;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'cotizacion_documento_detalles';
    protected $fillable = [
        'documento_id',
        'lote_id',
        'codigo_producto',
        'unidad',
        'nombre_producto',
        'codigo_lote',
        'cantidad',
        'precio_unitario',
        'precio_inicial',
        'precio_nuevo',
        'descuento',
        'dinero',
        'valor_unitario',
        'valor_venta',
        'estado'
    ];

    public function documento()
    {
        return $this->belongsTo('App\Ventas\Documento\Documento');
    }

    public function lote()
    {
        return $this->belongsTo('App\Almacenes\LoteProducto','lote_id');
    }
}
