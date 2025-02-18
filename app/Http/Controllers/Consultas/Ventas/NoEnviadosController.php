<?php

namespace App\Http\Controllers\Consultas\Ventas;

use App\Almacenes\LoteProducto;
use App\Almacenes\Producto;
use App\Http\Controllers\Controller;
use App\Ventas\Documento\Documento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Events\DocumentoNumeracion;
use App\Mantenimiento\Empresa\Empresa;
use App\Pos\DetalleMovimientoVentaCaja;
use App\Ventas\Cliente;
use App\Ventas\Documento\Detalle;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Luecano\NumeroALetras\NumeroALetras;

class NoEnviadosController extends Controller
{
    public function index()
    {
        return view('consultas.ventas.documentos_no.index');
    }

    public function getTable(Request $request){

        if($request->fecha_desde && $request->fecha_hasta)
        {
            $documentos = Documento::where('estado','!=','ANULADO')->wherein('sunat',['0','2'])->where('tipo_venta','!=',129)->whereBetween('fecha_documento', [$request->fecha_desde, $request->fecha_hasta])->orderBy('id', 'desc')->get();
        }
        else
        {
            $documentos = Documento::where('estado','!=','ANULADO')->wherein('sunat',['0','2'])->where('tipo_venta','!=',129)->orderBy('id', 'desc')->get();
        }
        
        $hoy = Carbon::now();
        
        $coleccion = collect();
        foreach($documentos as $documento){

            $transferencia = 0.00;
            $otros = 0.00;
            $efectivo = 0.00;

            if ($documento->tipo_pago_id == 1) {
                $efectivo = $documento->importe;
            }
            else if ($documento->tipo_pago_id == 2){
                $transferencia = $documento->importe ;
                $efectivo = $documento->efectivo;
            }
            else {
                $otros = $documento->importe;
                $efectivo = $documento->efectivo;
            }

            $fecha_v = $documento->created_at;
            $diff =  $fecha_v->diffInDays($hoy);

            $coleccion->push([
                'id' => $documento->id,
                'tipo_venta' => $documento->nombreTipo(),
                'tipo_venta_id' => $documento->tipo_venta,
                'tipo_pago' => $documento->tipo_pago,
                'numero_doc' =>  $documento->serie.'-'.$documento->correlativo,
                'serie' => $documento->serie,
                'correlativo' => $documento->correlativo,
                'cliente' => $documento->tipo_documento_cliente.': '.$documento->documento_cliente.' - '.$documento->cliente,
                'empresa' => $documento->empresa,
                'cotizacion_venta' =>  $documento->cotizacion_venta,
                'fecha_documento' =>  Carbon::parse($documento->fecha_documento)->format( 'd/m/Y'),
                'estado' => $documento->estado,
                'sunat' => $documento->sunat,
                'otros' => 'S/. '.number_format($otros, 2, '.', ''),
                'efectivo' => 'S/. '.number_format($efectivo, 2, '.', ''),
                'transferencia' => 'S/. '.number_format($transferencia, 2, '.', ''),
                'total' => 'S/. '.number_format($documento->total, 2, '.', ''),
                'dias' => (int)(7 - $diff < 0 ? 0  : 7 - $diff)
            ]);
        }


        return response()->json([
            'success' => true,
            'ventas' => $coleccion,
        ]);
    }

    public function obtenerLeyenda($documento)
    {
        $formatter = new NumeroALetras();
        $convertir = $formatter->toInvoice($documento->total, 2, 'SOLES');

        //CREAR LEYENDA DEL COMPROBANTE
        $arrayLeyenda = Array();
        $arrayLeyenda[] = array(  
            "code" => "1000",
            "value" => $convertir
        );
        return $arrayLeyenda;
    }

    public function obtenerProductos($id)
    {
        $detalles = Detalle::where('documento_id',$id)->get();
        $arrayProductos = Array();
        for($i = 0; $i < count($detalles); $i++){

            $arrayProductos[] = array(
                "codProducto" => $detalles[$i]->codigo_producto,
                "unidad" => $detalles[$i]->unidad,
                "descripcion"=> $detalles[$i]->nombre_producto.' - '.$detalles[$i]->codigo_lote,
                "cantidad" => (float)$detalles[$i]->cantidad,
                "mtoValorUnitario" => (float)($detalles[$i]->precio_nuevo / 1.18),
                "mtoValorVenta" => (float)($detalles[$i]->valor_venta / 1.18),
                "mtoBaseIgv" => (float)($detalles[$i]->valor_venta / 1.18), 
                "porcentajeIgv" => 18,
                "igv" => (float)($detalles[$i]->valor_venta - ($detalles[$i]->valor_venta / 1.18)),
                "tipAfeIgv" => 10,
                "totalImpuestos" =>  (float)($detalles[$i]->valor_venta - ($detalles[$i]->valor_venta / 1.18)),
                "mtoPrecioUnitario" => (float)$detalles[$i]->precio_nuevo

            );
        }

        return $arrayProductos;
    }

    public function obtenerFechaEmision($documento)
    {
        $date = strtotime($documento->fecha_documento);
        $fecha_emision = date('Y-m-d', $date); 
        $hora_emision = date('H:i:s', $date); 
        $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

        return $fecha;
    }

    public function obtenerFechaVencimiento($documento)
    {
        $date = strtotime($documento->fecha_vencimiento);
        $fecha_emision = date('Y-m-d', $date); 
        $hora_emision = date('H:i:s', $date); 
        $fecha = $fecha_emision.'T'.$hora_emision.'-05:00';

        return $fecha;
    }

    public function sunat($id)
    {
       
        $documento = Documento::findOrFail($id);
        //OBTENER CORRELATIVO DEL COMPROBANTE ELECTRONICO
        $existe = event(new DocumentoNumeracion($documento));
        if($existe[0]){
            if ($existe[0]->get('existe') == true) {
                if ($documento->sunat != '1') {
                    //ARREGLO COMPROBANTE
                    $arreglo_comprobante = array(
                        "tipoOperacion" => $documento->tipoOperacion(),
                        "tipoDoc"=> $documento->tipoDocumento(),
                        "serie" => $existe[0]->get('numeracion')->serie,
                        "correlativo" => $documento->correlativo,
                        "fechaEmision" => self::obtenerFechaEmision($documento),
                        "fecVencimiento" => self::obtenerFechaVencimiento($documento),
                        "observacion" => $documento->observacion,
                        "formaPago" => array(
                            "moneda" =>  $documento->simboloMoneda(),
                            "tipo" =>  $documento->formaPago(),
                        ),
                        "tipoMoneda" => $documento->simboloMoneda(),
                        "client" => array(
                            "tipoDoc" => $documento->tipoDocumentoCliente(),
                            "numDoc" => $documento->documento_cliente,
                            "rznSocial" => $documento->cliente,
                            "address" => array(
                                "direccion" => $documento->direccion_cliente,
                            )),
                        "company" => array(
                            "ruc" =>  $documento->ruc_empresa,
                            "razonSocial" => $documento->empresa,
                            "address" => array(
                                "direccion" => $documento->direccion_fiscal_empresa,
                            )),
                        "mtoOperGravadas" => (float)$documento->sub_total,
                        "mtoOperExoneradas" => 0,
                        "mtoIGV" => (float)$documento->total_igv,
                        
                        "valorVenta" => (float)$documento->sub_total,
                        "totalImpuestos" => (float)$documento->total_igv,
                        "subTotal" => (float)$documento->total,
                        "mtoImpVenta" => (float)$documento->total,
                        "ublVersion" => "2.1",
                        "details" => self::obtenerProductos($documento->id),
                        "legends" =>  self::obtenerLeyenda($documento),
                    );

                    //return $arreglo_comprobante;
                    //OBTENER JSON DEL COMPROBANTE EL CUAL SE ENVIARA A SUNAT
                    $data = enviarComprobanteapi(json_encode($arreglo_comprobante), $documento->empresa_id);

                    //RESPUESTA DE LA SUNAT EN JSON
                    $json_sunat = json_decode($data);
                    if ($json_sunat->sunatResponse->success == true) {
        
                        $documento->sunat = '1';

                        $data_comprobante = generarComprobanteapi(json_encode($arreglo_comprobante), $documento->empresa_id);

                        $name = $documento->serie."-".$documento->correlativo.'.pdf';

                        $pathToFile = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'.DIRECTORY_SEPARATOR.$name);

                        if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'))) {
                            mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'sunat'));
                        }

                        file_put_contents($pathToFile, $data_comprobante);

                        $arreglo_qr = array(
                            "ruc" => $documento->ruc_empresa,
                            "tipo" => $documento->tipoDocumento(),
                            "serie" => $documento->serie,
                            "numero" => $documento->correlativo,
                            "emision" => self::obtenerFechaEmision($documento),
                            "igv" => 18,
                            "total" => (float)$documento->total,
                            "clienteTipo" => $documento->tipoDocumentoCliente(),
                            "clienteNumero" => $documento->documento_cliente
                        );

                        /********************************/
                        $data_qr = generarQrApi(json_encode($arreglo_qr), $documento->empresa_id);

                        $name_qr = $documento->serie."-".$documento->correlativo.'.svg';

                        $pathToFile_qr = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'qrs'.DIRECTORY_SEPARATOR.$name_qr);

                        if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'qrs'))) {
                            mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'qrs'));
                        }

                        file_put_contents($pathToFile_qr, $data_qr);

                        /********************************/

                        $data_xml = generarXmlapi(json_encode($arreglo_comprobante), $documento->empresa_id);
                        $name_xml = $documento->serie.'-'.$documento->correlativo.'.xml';
                        $pathToFile_xml = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.$name_xml);
                        if(!file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'xml'))) {
                            mkdir(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'xml'));
                        }
                        file_put_contents($pathToFile_xml, $data_xml);

                        /********************************* */

                        $documento->nombre_comprobante_archivo = $name;
                        $documento->hash = $json_sunat->hash;
                        $documento->xml = $name_xml;
                        $documento->ruta_comprobante_archivo = 'public/sunat/'.$name;
                        $documento->ruta_qr = 'public/qrs/'.$name_qr;
                        $documento->update();


                        //Registro de actividad
                        $descripcion = "SE AGREGÓ EL COMPROBANTE ELECTRONICO: ". $documento->serie."-".$documento->correlativo;
                        $gestion = "COMPROBANTES ELECTRONICOS";
                        crearRegistro($documento , $descripcion , $gestion);

                        Session::flash('success','Documento de Venta enviada a Sunat con exito.');
                        return view('consultas.ventas.documentos_no.index',[
                            
                            'id_sunat' => $json_sunat->sunatResponse->cdrResponse->id,
                            'descripcion_sunat' => $json_sunat->sunatResponse->cdrResponse->description,
                            'notas_sunat' => $json_sunat->sunatResponse->cdrResponse->notes,
                            'sunat_exito' => true
        
                        ])->with('sunat_exito', 'success');
        
                    }else{

                        //COMO SUNAT NO LO ADMITE VUELVE A SER 0 
                        $documento->sunat = '0';
                        $documento->update(); 
                        
                        if ($json_sunat->sunatResponse->error) {
                            $id_sunat = $json_sunat->sunatResponse->error->code;
                            $descripcion_sunat = $json_sunat->sunatResponse->error->message;
        
                        
                        }else {
                            $id_sunat = $json_sunat->sunatResponse->cdrResponse->id;
                            $descripcion_sunat = $json_sunat->sunatResponse->cdrResponse->description;
                            
                        };
        
        
                        Session::flash('error','Documento de Venta sin exito en el envio a sunat.');
                        return view('consultas.ventas.documentos_no.index',[
                            'id_sunat' =>  $id_sunat,
                            'descripcion_sunat' =>  $descripcion_sunat,
                            'sunat_error' => true,
        
                        ])->with('sunat_error', 'error');
                    }
                }else{
                    $documento->sunat = '1';
                    $documento->update();
                    Session::flash('error','Documento de venta fue enviado a Sunat.');
                    return redirect()->route('consultas.ventas.documentos_no.index')->with('sunat_existe', 'error');
                }
            }else{
                Session::flash('error','Tipo de Comprobante no registrado en la empresa.');
                return redirect()->route('consultas.ventas.documentos_no.index')->with('sunat_existe', 'error');
            }
        }else{
            Session::flash('error','Empresa sin parametros para emitir comprobantes electronicos');
            return redirect()->route('consultas.ventas.documentos_no.index');
        }
        
    }

    public function edit($id)
    {
        $this->authorize('haveaccess','documento_compra.index');
        $empresas = Empresa::where('estado', 'ACTIVO')->get();
        $clientes = Cliente::where('estado', 'ACTIVO')->get();
        $productos = Producto::where('estado', 'ACTIVO')->get();
        $documento = Documento::findOrFail($id);
        $detalles = Detalle::where('documento_id',$id)->with(['lote','lote.producto'])->get();

        return view('consultas.ventas.documentos_no.edit',[
            'documento' => $documento,
            'detalles' => $detalles,
            'empresas' => $empresas,
            'clientes' => $clientes,
            'productos' => $productos
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('haveaccess','documento_venta.index');
        ini_set("max_execution_time", 60000);
        try{
            
            DB::beginTransaction();
            $data = $request->all();

            $rules = [
                'fecha_documento_campo'=> 'required',
                'fecha_atencion_campo'=> 'required',
                'tipo_venta'=> 'required',
                'forma_pago'=> 'required',
                'tipo_pago_id'=> 'nullable',
                'efectivo'=> 'required',
                'importe'=> 'required',
                'empresa_id'=> 'required',
                'cliente_id'=> 'required',
                'observacion' => 'nullable',
                'igv' => 'required_if:igv_check,==,on|numeric|digits_between:1,3',

            ];

            $message = [
                'fecha_documento_campo.required' => 'El campo Fecha de Emisión es obligatorio.',
                'tipo_venta.required' => 'El campo tipo de venta es obligatorio.',
                'forma_pago.required' => 'El campo forma de pago es obligatorio.',
                //'tipo_pago_id.required' => 'El campo modo de pago es obligatorio.',
                'importe.required' => 'El campo importe es obligatorio.',
                'efectivo.required' => 'El campo efectivo es obligatorio.',
                'fecha_atencion_campo.required' => 'El campo Fecha de Entrega es obligatorio.',
                'empresa_id.required' => 'El campo Empresa es obligatorio.',
                'cliente_id.required' => 'El campo Cliente es obligatorio.',
                'igv.required_if' => 'El campo Igv es obligatorio.',
                'igv.digits' => 'El campo Igv puede contener hasta 3 dígitos.',
                'igv.numeric' => 'El campo Igv debe se numérico.',


            ];


            $validator =  Validator::make($data, $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => true,
                    'data' => array('mensajes' => $validator->getMessageBag()->toArray())
                ]);

            }

            $documento = Documento::find($id);
            $documento->fecha_documento = $request->get('fecha_documento_campo');
            $documento->fecha_atencion =  $request->get('fecha_atencion_campo');
            $documento->fecha_vencimiento =  $request->get('fecha_vencimiento_campo');
            //EMPRESA
            $empresa = Empresa::findOrFail($request->get('empresa_id'));
            $documento->ruc_empresa =  $empresa->ruc;
            $documento->empresa =  $empresa->razon_social;
            $documento->direccion_fiscal_empresa =  $empresa->direccion_fiscal;
            $documento->empresa_id = $request->get('empresa_id'); //OBTENER NUMERACION DE LA EMPRESA
            //CLIENTE
            $cliente = Cliente::findOrFail($request->get('cliente_id'));

            $documento->tipo_documento_cliente =  $cliente->tipo_documento;
            $documento->documento_cliente =  $cliente->documento;
            $documento->direccion_cliente =  $cliente->direccion;
            $documento->cliente =  $cliente->nombre;
            $documento->cliente_id = $request->get('cliente_id');

            $documento->tipo_venta = $request->get('tipo_venta');
            $documento->forma_pago = $request->get('forma_pago');
            $documento->observacion = $request->get('observacion');
            $documento->user_id = auth()->user()->id;
            $documento->sub_total = $request->get('monto_sub_total');
            $documento->total_igv = $request->get('monto_total_igv');
            $documento->total = $request->get('monto_total');
            $documento->igv = $request->get('igv') ? $request->get('igv') : 18;
            $documento->moneda = 1;

            $documento->tipo_pago_id = $request->get('tipo_pago_id');
            $documento->importe = $request->get('importe');
            $documento->efectivo = $request->get('efectivo');

            if ($request->get('igv_check') == "on") {
                $documento->igv_check = "1";
            };
            $numero_doc = $documento->id;
            $documento->numero_doc = 'VENTA-'.$numero_doc;
            $documento->update();
            //Llenado de los articulos
            $productosJSON = $request->get('productos_tabla[]');
            $productotabla = json_decode($productosJSON);
            foreach ($productotabla as $producto) {
                $lote = LoteProducto::findOrFail($producto->lote_id);
                Detalle::create([
                    'documento_id' => $documento->id,
                    'lote_id' => $producto->lote_id, //LOTE
                    'codigo_producto' => $lote->producto->codigo,
                    'unidad' => $lote->producto->getMedida(),
                    'nombre_producto' => $lote->producto->nombre,
                    'codigo_lote' => $lote->codigo_lote,
                    'cantidad' => $producto->cantidad,
                    'precio_unitario' => $producto->precio_unitario,
                    'precio_inicial' => $producto->precio_inicial,
                    'precio_nuevo' => $producto->precio_nuevo,
                    'dinero' => $producto->dinero,
                    'descuento' => $producto->descuento,
                    'valor_unitario' => $producto->valor_unitario,
                    'valor_venta' => $producto->valor_venta,
                ]);

                $lote->cantidad =  $lote->cantidad - $producto->cantidad;
                $lote->update();
            }

            // $detalle = new DetalleMovimientoVentaCaja();
            // $detalle->cdocumento_id = $documento->id;
            // $detalle->mcaja_id = movimientoUser()->id;
            // $detalle->save();

            // $envio_prev = self::sunat($documento->id);
            // if(!$envio_prev['success'])
            // {
            //     DB::rollBack();
            //     return response()->json([
            //         'success' => false,
            //         'mensaje'=> $envio_prev['mensaje']
            //     ]);
            // }
            

            $documento = Documento::find($documento->id);
            $documento->nombre_comprobante_archivo = $documento->serie.'-'.$documento->correlativo.'.pdf';
            $documento->update();

            //Registro de actividad
            $descripcion = "SE AGREGÓ EL DOCUMENTO DE VENTA CON LA FECHA: ". Carbon::parse($documento->fecha_documento)->format('d/m/y');
            $gestion = "DOCUMENTO DE VENTA";
            crearRegistro($documento , $descripcion , $gestion);

            DB::commit();
            Session::flash('success','Documento de venta modificado.');
            return response()->json([
                'success' => true,
                'documento_id'=> $documento->id
            ]);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje'=> 'Ocurrio un error porfavor volver a intentar, si el error persiste comunicarse con el administrador del sistema.',
                'excepcion' => $e->getMessage()
            ]);
            //return redirect()->route('ventas.documento.index');
        }
    }

    public function getLot()
    {
        $this->authorize('haveaccess','nota_salida.index');
        return datatables()->query(
            DB::table('lote_productos')
            ->join('productos_clientes','productos_clientes.producto_id','=','lote_productos.producto_id')
            ->join('productos','productos.id','=','lote_productos.producto_id')
            ->join('categorias','categorias.id','=','productos.categoria_id')
            ->join('tabladetalles','tabladetalles.id','=','productos.medida')
            ->select('lote_productos.*','productos.nombre','productos.codigo_barra','productos_clientes.cliente','productos_clientes.moneda','tabladetalles.simbolo as unidad_producto',
                    'productos_clientes.monto as precio_venta','categorias.descripcion', DB::raw('DATE_FORMAT(lote_productos.fecha_vencimiento, "%d/%m/%Y") as fecha_venci'))
            ->where('lote_productos.cantidad_logica','>',0)
            ->where('lote_productos.estado','1')
            ->where('productos_clientes.cliente','121')
            ->where('productos_clientes.moneda','1')
            ->orderBy('lote_productos.id','ASC')
            ->where('productos_clientes.estado','ACTIVO')
        )->toJson();
    }

    //CAMBIAR CANTIDAD LOGICA DEL LOTE
    public function quantity(Request $request)
    {
        $data = $request->all();
        $producto_id = $data['producto_id'];
        $cantidad = $data['cantidad'];
        $condicion = $data['condicion'];
        $mensaje = '';
        $lote = LoteProducto::findOrFail($producto_id);
        //DISMINUIR
        if ($lote->cantidad_logica >= $cantidad && $condicion == '1' ) {
            $nuevaCantidad = $lote->cantidad_logica - $cantidad;
            $lote->cantidad_logica = $nuevaCantidad;
            $lote->update();
            $mensaje = 'Cantidad aceptada';
        }
        //AUMENTAR
        if ($condicion == '0' ) {
            $nuevaCantidad = $lote->cantidad_logica + $cantidad;
            $lote->cantidad_logica = $nuevaCantidad;
            $lote->update();
            $mensaje = 'Cantidad regresada';
        }

        return $mensaje;
    }

    //DEVOLVER CANTIDAD LOGICA AL CERRAR VENTANA
    public function returnQuantity(Request $request)
    {
        $data = $request->all();
        $cantidades = $data['cantidades'];
        $productosJSON = $cantidades;
        $productotabla = json_decode($productosJSON);
        $mensaje = '';
        foreach ($productotabla as $detalle) {
            //DEVOLVEMOS CANTIDAD AL LOTE Y AL LOTE LOGICO
            $lote = LoteProducto::findOrFail($detalle->lote_id);
            $lote->cantidad_logica = $lote->cantidad_logica + $detalle->cantidad;
            //$lote->cantidad =  $lote->cantidad_logica;
            $lote->estado = '1';
            $lote->update();
            $mensaje = 'Cantidad devuelta';
        };

        return $mensaje;
    }

    //DEVOLVER CANTIDAD LOGICA AL CERRAR VENTANA EDIT
    public function returnQuantityEdit(Request $request)
    {
        $data = $request->all();
        $cantidades = $data['cantidades'];
        $productosJSON = $cantidades;
        $productotabla = json_decode($productosJSON);
        $id = $data['nota_id'];
        $mensaje = '';
        foreach ($productotabla as $detalle) {
            //DEVOLVEMOS CANTIDAD AL LOTE Y AL LOTE LOGICO
            $lote = LoteProducto::findOrFail($detalle->lote_id);
            //$cantidadmovimiento = DB::table("movimiento_nota")->where('lote_id',$lote->id)->where('producto_id',$lote->producto_id)->where('nota_id',$id)->where('movimiento','SALIDA')->first()->cantidad;
            $movimiento = DB::table("movimiento_nota")->where('lote_id',$lote->id)->where('producto_id',$lote->producto_id)->where('nota_id',$id)->where('movimiento','SALIDA')->first();
            if($movimiento)
            {
                $cantidadmovimiento = $movimiento->cantidad;

                if($cantidadmovimiento > $detalle->cantidad)
                {
                    $mover = $cantidadmovimiento - $detalle->cantidad;
                    $lote->cantidad_logica = $lote->cantidad_logica - $mover;
                }
                else 
                {
                    $mover = $detalle->cantidad - $cantidadmovimiento;
                    $lote->cantidad_logica = $lote->cantidad_logica + $mover;
                }


                
                //$lote->cantidad =  $lote->cantidad_logica;
                $lote->estado = '1';
                $lote->update();
            }
            else{
                $lote = LoteProducto::findOrFail($detalle->lote_id);
                $lote->cantidad_logica = $lote->cantidad_logica + $detalle->cantidad;
                //$lote->cantidad =  $lote->cantidad_logica;
                $lote->estado = '1';
                $lote->update();
                $mensaje = 'Cantidad devuelta';
            }
            $mensaje = 'Cantidad devuelta';
        };

        return $mensaje;
    }

    //DEVOLVER CANTIDAD LOGICA DEL LOTE ELIMINADO
    public function returnQuantityLoteInicio(Request $request)
    {
        $data = $request->all();
        $cantidades = $data['cantidades'];
        $productosJSON = $cantidades;
        $productotabla = json_decode($productosJSON);
        $mensaje = '';
        foreach ($productotabla as $detalle) {
            //DEVOLVEMOS CANTIDAD AL LOTE Y AL LOTE LOGICO
            $lote = LoteProducto::findOrFail($detalle->lote_id);
            $lote->cantidad_logica = $lote->cantidad_logica - $detalle->cantidad;
            $lote->estado = '1';
            $lote->update();
            $mensaje = 'Cantidad devuelta';
        };

        return $mensaje;
    }

    //DEVOLVER LOTE
    public function returnLote(Request $request)
    {
        $data = $request->all();
        $lote_id = $data['lote_id'];         
        $lote = LoteProducto::find($lote_id);

        if($lote)
        {
        return response()->json([
            'success' => true,
            'lote' => $lote,
        ]);
        }
        else{
        return response()->json([
            'success' => false,
        ]);
        }
    }

    //ACTUALIZAR LOTE E EDICION DE CANTIDAD
    public function updateLote(Request $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->all();
            $lote_id = $data['lote_id'];         
            $cantidad_sum = $data['cantidad_sum'];         
            $cantidad_res = $data['cantidad_res'];         
            $lote = LoteProducto::find($lote_id);
        
            if($lote)
            {
                $lote->cantidad_logica = $lote->cantidad_logica + ($cantidad_sum - $cantidad_res);
                $lote->update();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'lote' => $lote,
                ]);
            }
            else{
                DB::rollBack();
                return response()->json([
                    'success' => false,
                ]);
            }
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
            ]);
        }
    }
}
