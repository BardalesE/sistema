<?php

namespace App\Http\Controllers\Consultas\Kardex;

use App\Almacenes\NotaSalidad;
use App\Http\Controllers\Controller;
use App\Ventas\Documento\Documento;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    public function ventas()
    {
        return view('consultas.kardex.venta');
    }

    public function getTableVentas(Request $request){

        if($request->fecha_desde && $request->fecha_hasta)
        {
            $documentos = Documento::where('estado','!=','ANULADO')->whereBetween('fecha_documento', [$request->fecha_desde, $request->fecha_hasta])->orderBy('id', 'desc')->get();
        }
        else
        {
            $documentos = Documento::where('estado','!=','ANULADO')->orderBy('id', 'desc')->get();
        }
        

        
        $coleccion = collect();
        foreach($documentos as $documento){
            foreach($documento->detalles as $detalle)
            {
                $coleccion->push([
                    'codigo' => $detalle->lote->producto->codigo,
                    'cantidad' => $detalle->cantidad,
                    'producto' => $detalle->lote->producto->nombre,
                    'costo' => $detalle->lote->detalle_compra ? $detalle->lote->detalle_compra->precio : 0.00,
                    'precio' => $detalle->precio_nuevo,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'ventas' => $coleccion,
        ]);
    }

    public function notas()
    {
        return view('consultas.kardex.nota');
    }

    public function getTableNotas(Request $request){
        try
        {
            if($request->fecha_desde && $request->fecha_hasta)
            {
                $data = NotaSalidad::where('estado','!=','ANULADO')->whereBetween('fecha' , [$request->fecha_desde, $request->fecha_hasta])->orderBy('id', 'desc')->get();
            }
            else
            {
                $data = NotaSalidad::where('estado','!=','ANULADO')->orderBy('id', 'desc')->get();
            }

            $coleccion = collect();
            foreach($data as $nota){
                foreach($nota->detalles as $detalle)
                {
                    $coleccion->push([
                        'codigo' => $detalle->lote->producto->codigo,
                        'cantidad' => $detalle->cantidad,
                        'motivo' => $nota->destino,
                        'producto' => $detalle->lote->producto->nombre,
                        'costo' => $detalle->lote->detalle_compra ? $detalle->lote->detalle_compra->precio : 0.00,
                        'precio' => $detalle->lote->producto->precio_venta_minimo,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'notas' => $coleccion
            ]);
        }
        catch(Exception $e)
        {
            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}
