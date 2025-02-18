@extends('layout') @section('content')

@section('compras-active', 'active')
@section('documento-active', 'active')

<div class="row wrapper border-bottom white-bg page-heading">

    <div class="col-lg-12">
       <h2  style="text-transform:uppercase"><b>MODIFICAR DOCUMENTO DE COMPRA # {{$documento->id}}</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('compras.documento.index')}}">Documentos de Compra</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Modificar</strong>
            </li>

        </ol>
    </div>



</div>


<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">

                <div class="ibox-content">

                    <form action="{{route('compras.documento.update', $documento->id)}}" method="POST" id="enviar_orden">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-sm-6 b-r">
                                <h4 class=""><b>Documento de compra</b></h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Modificar datos del documento de compra:</p>
                                    </div>
                                </div>

                                <div class="form-group row">

                                    <div class="col-lg-6 col-xs-12" id="fecha_documento">
                                        <label class="required">Fecha de Emisión</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_documento" name="fecha_emision"
                                                class="form-control {{ $errors->has('fecha_emision') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha_emision', getFechaFormato($documento->fecha_emision, 'd/m/Y'))}}"
                                                autocomplete="off" readonly required>
                                            @if ($errors->has('fecha_emision'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_emision') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-xs-12" id="fecha_entrega">
                                        <label class="required">Fecha de Entrega</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" id="fecha_entrega_campo" name="fecha_entrega"
                                                class="form-control {{ $errors->has('fecha_entrega') ? ' is-invalid' : '' }}"
                                                value="{{old('fecha_entrega', getFechaFormato($documento->fecha_entrega, 'd/m/Y'))}}"
                                                autocomplete="off" required readonly>
                                            @if ($errors->has('fecha_entrega'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fecha_entrega') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="required">Empresa: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('empresa_id') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('empresa_id')}}"
                                        name="empresa_id" id="empresa_id"  disabled>
                                        <option></option>
                                        @foreach ($empresas as $empresa)
                                        <option value="{{$empresa->id}}"  @if($empresa->id == '1' ) {{'selected'}} @endif >{{$empresa->razon_social}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Modificar Proveedor:</p>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="required">Ruc / Dni: </label>
                                 
                                    <select
                                        class="select2_form form-control {{ $errors->has('proveedor_id') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('proveedor_id',$documento->proveedor_id)}}"
                                        name="proveedor_id" id="proveedor_id" required>
                                        <option></option>
                                        @foreach ($proveedores as $proveedor)
                                        @if($proveedor->ruc)
                                        <option value="{{$proveedor->id}}" @if(old('proveedor_id',$documento->proveedor_id)
                                            == $proveedor->id ) {{'selected'}} @endif
                                            >{{$proveedor->ruc}}</option>
                                        @else
                                        @if($proveedor->dni)
                                        <option value="{{$proveedor->id}}" @if(old('proveedor_id',$documento->proveedor_id)
                                            == $proveedor->id ) {{'selected'}} @endif
                                            >{{$proveedor->dni}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label class="required">Razon Social: </label>
                                    <select
                                        class="select2_form form-control {{ $errors->has('proveedor_razon') ? ' is-invalid' : '' }}"
                                        style="text-transform: uppercase; width:100%" value="{{old('proveedor_razon')}}"
                                        name="proveedor_razon" id="proveedor_razon" required>
                                        <option></option>
                                        @foreach ($proveedores as $proveedor)
                                            @if($proveedor->ruc)
                                            <option value="{{$proveedor->id}}" @if(old('proveedor_id',$documento->proveedor_id)==$proveedor->id )
                                                {{'selected'}} @endif >{{$proveedor->descripcion}}
                                            </option>
                                            @else
                                            @if($proveedor->dni)
                                            <option value="{{$proveedor->id}}" @if(old('proveedor_id',$documento->proveedor_id)==$proveedor->id )
                                                {{'selected'}} @endif >{{$proveedor->descripcion}}
                                            </option>
                                            @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>




                            </div>

                            <div class="col-sm-6">


                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="required">Modo de Compra: </label>
                                        <select
                                            class="select2_form form-control {{ $errors->has('modo_compra') ? ' is-invalid' : '' }}"
                                            style="text-transform: uppercase; width:100%"
                                            value="{{old('modo_compra',$documento->modo_compra)}}" name="modo_compra"
                                            id="modo_compra" required>
                                            <option></option>
                                            @foreach (modo_compra() as $modo)
                                            <option value="{{$modo->descripcion}}" @if(old('modo_compra',$documento->
                                                modo_compra)==$modo->
                                                descripcion ) {{'selected'}} @endif
                                                >{{$modo->simbolo.' - '.$modo->descripcion}}</option>
                                            @endforeach
                                            @if ($errors->has('modo_compra'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('modo_compra') }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required">Moneda: </label>
                                        <select
                                            class="select2_form form-control {{ $errors->has('moneda') ? ' is-invalid' : '' }}"
                                            style="text-transform: uppercase; width:100%"
                                            value="{{old('moneda',$documento->moneda)}}" name="moneda" id="moneda" required>
                                            <option></option>
                                            @foreach (tipos_moneda() as $moneda)
                                            <option value="{{$moneda->descripcion}}" @if(old('moneda',$documento->
                                                moneda)==$moneda->
                                                descripcion ) {{'selected'}} @endif
                                                >{{$moneda->simbolo.' - '.$moneda->descripcion}}</option>
                                            @endforeach
                                            @if ($errors->has('moneda'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('moneda') }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    
                                    
                                    </div>


                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class="required">Tipo: </label>
                                        <select
                                            class="select2_form form-control {{ $errors->has('tipo_compra') ? ' is-invalid' : '' }}"
                                            style="text-transform: uppercase; width:100%" value="{{old('tipo_compra')}}"
                                            name="tipo_compra" id="tipo_compra" required onchange="activarNumero()">
                                            <option></option>
                                            @foreach (tipo_compra() as $modo)
                                            <option value="{{$modo->descripcion}}" @if(old('tipo_compra',$documento->tipo_compra)==$modo->
                                                descripcion ) {{'selected'}} @endif
                                                >{{$modo->descripcion}}</option>
                                            @endforeach
                                            @if ($errors->has('tipo_compra'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tipo_compra') }}</strong>
                                            </span>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required" id="numero_comprobante">Nº: </label>
                                        <input type="text" id="numero_tipo" name="numero_tipo" class="form-control {{ $errors->has('numero_tipo') ? ' is-invalid' : '' }}" value="{{old('numero_tipo',$documento->numero_tipo)}}" required >
                                        
                                        @if ($errors->has('numero_tipo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('numero_tipo') }}</strong>
                                        </span>
                                        @endif


                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class=""  id="campo_tipo_cambio">Tipo de Cambio (S/.):</label>
                                        <input type="text" id="tipo_cambio" name="tipo_cambio" class="form-control {{ $errors->has('tipo_cambio') ? ' is-invalid' : '' }}" value="{{old('tipo_cambio',$documento->tipo_cambio)}}" disabled>
                                        @if ($errors->has('tipo_cambio'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tipo_cambio') }}</strong>
                                        </span>
                                        @endif

                                    </div>

                                    <div class="col-md-6">
                                        <label id="igv_requerido">IGV (%):</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" id="igv_check" name="igv_check">
                                                </span>
                                            </div>
                                            <input type="text" value="{{old('igv',$documento->igv)}}" maxlength="3"
                                                class="form-control {{ $errors->has('igv') ? ' is-invalid' : '' }}"
                                                name="igv" id="igv"  onkeyup="return mayus(this)" required>
                                                @if ($errors->has('igv'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('igv') }}</strong>
                                                </span>
                                                @endif

                                        </div>
                                    </div>


                                </div>






                                <div class="form-group">
                                    <label>Observación:</label>
                                    <textarea type="text" placeholder=""
                                        class="form-control {{ $errors->has('observacion') ? ' is-invalid' : '' }}"
                                        name="observacion" id="observacion"  onkeyup="return mayus(this)"
                                        value="{{old('observacion', $documento->observacion)}}">{{old('observacion',$documento->observacion)}}</textarea>
                                    @if ($errors->has('observacion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('observacion') }}</strong>
                                    </span>
                                    @endif








                                </div>





                                <input type="hidden" id="productos_tabla" name="productos_tabla[]">

                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class=""><b>Detalle de Documento de Compra</b></h4>
                                    </div>
                                    <div class="panel-body">


                                    <div class="row">
                                                <div class="col-lg-6 col-xs-12 b-r">
                                                    <div class="form-group row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <label class="required">Producto:</label>
                                                            <select class="select2_form form-control"
                                                                style="text-transform: uppercase; width:100%" name="producto_id"
                                                                id="producto_id" onchange="cargarPresentacion(this)">
                                                                <option></option>
                                                                @foreach ($productos as $producto)
                                                                <option value="{{$producto->id}}">{{$producto->nombre}} - {{$producto->codigo_barra}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback"><b><span id="error-producto"></span></b>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <label class="required">Costo Flete:</label>
                                                            <input type="text" id="costo_flete" name="costo_flete" class="form-control">
                                                            <div class="invalid-feedback"><b><span id="error-costo-flete"></span></b></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-form-label required" for="amount">Precio:</label>
                                                                <input type="text" id="precio" class="form-control">
                                                                <div class="invalid-feedback"><b><span id="error-precio"></span></b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                            <label class="col-form-label required">Cantidad:</label>
                                                            <input type="text" id="cantidad" class="form-control">
                                                            <div class="invalid-feedback"><b><span id="error-cantidad"></span></b>
                                                            </div>


                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-lg-6 col-xs-12">

                                                    <div class="form-group row" >
                                                        <div class="col-md-6" id="fecha_vencimiento_campo">
                                                            <label class="required">Fecha de vencimiento:</label>
                                                            <div class="input-group date">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control"  autocomplete="off" readonly>
                                                                <div class="invalid-feedback"><b><span id="error-fecha_vencimiento"></span></b></div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="required">Lote:</label>
                                                            <input type="text" id="lote" name="lote" class="form-control" onkeypress="return mayus(this);">
                                                            <div class="invalid-feedback"><b><span id="error-lote"></span></b></div>
                                                        </div>
                                                    </div>

                                                       

                                                    <div class="form-group row">
                                                        <div class="col-lg-6 col-xs-12">
                                                            <label class="col-form-label" for="amount">&nbsp;</label> <a class="btn btn-block btn-success " onclick="limpiarDetalle()" style='color:white;'> <i class="fa fa-paint-brush"></i> LIMPIAR</a>
                                                        </div>

                                                        <div class="col-lg-6 col-xs-12">
                                                            <label class="col-form-label" for="amount">&nbsp;</label>
                                                            <a class="btn btn-block btn-warning enviar_producto" style='color:white;'> <i class="fa fa-plus"></i> AGREGAR</a>
                                                        </div>


                                                    </div>
                                                   

                                                
                                                </div>




                                            </div>




                                            <hr>

                                        <div class="table-responsive">
                                            <table
                                                class="table dataTables-orden-detalle table-striped table-bordered table-hover"
                                                style="text-transform:uppercase">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th class="text-center">ACCIONES</th>
                                                        <th class="text-center">CANTIDAD</th>
                                                        <th class="text-center">PRODUCTO</th>
                                                        <th class="text-center">FECHA. VENC</th>
                                                        <th class="text-center">COSTO FLETE</th>
                                                        <th class="text-center">PRECIO</th>
                                                        <th class="text-center">TOTAL</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot style="text-transform:uppercase">
                                                    <tr>
                                                        <th colspan="7" style="text-align:right">Sub Total:</th>
                                                        <th class="text-center"><span id="subtotal">0.0</span></th>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="7" class="text-center">IGV <span
                                                                id="igv_int"></span>:</th>
                                                        <th class="text-center"><span id="igv_monto"></span></th>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="7" class="text-center">TOTAL:</th>
                                                        <th class="text-center"><span id="total"></span></th>

                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>






                                    </div>
                                </div>
                            </div>

                        </div>






                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">

                            <div class="col-md-6 text-left" style="color:#fcbc6c">
                                <i class="fa fa-exclamation-circle"></i> <small>Los campos marcados con asterisco
                                    (<label class="required"></label>) son obligatorios.</small>
                            </div>

                            <div class="col-md-6 text-right">
                                <a href="{{route('compras.orden.index')}}" id="btn_cancelar"
                                    class="btn btn-w-m btn-default">
                                    <i class="fa fa-arrow-left"></i> Regresar
                                </a>
                                <button type="submit" id="btn_grabar" class="btn btn-w-m btn-primary">
                                    <i class="fa fa-save"></i> Grabar
                                </button>
                            </div>

                        </div>

                    </form>

                </div>


            </div>
        </div>

    </div>
</div>


@include('compras.documentos.modal')

@stop

@push('styles')
<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}"
    rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
<style>
.select2-container--open {
    z-index: 9999999
}
</style>
@endpush

@push('scripts')
<!-- Data picker -->
<script src="{{ asset('Inspinia/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- Date range use moment.js same as full calendar plugin -->
<script src="{{ asset('Inspinia/js/plugins/fullcalendar/moment.min.js') }}"></script>
<!-- Date range picker -->
<script src="{{ asset('Inspinia/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('Inspinia/js/plugins/select2/select2.full.min.js') }}"></script>

<!-- DataTable -->
<script src="{{asset('Inspinia/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('Inspinia/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Chosen -->
<script src="{{asset('Inspinia/js/plugins/chosen/chosen.jquery.js')}}"></script>
<script>
//IGV
$(document).ready(function() {
    $('#lote').val('LT-{{ $fecha_actual }}');
    $('#fecha_vencimiento').val('{{$fecha_5}}');
    $('#costo_flete').val('0.00');

    if ($("#igv_check").prop('checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
    }
    //TIPO DE CAMBIO

    if ("{{old('moneda',$documento->moneda)}}" == "SOLES") {
        $('#tipo_cambio').attr('disabled',true)
        $("#tipo_cambio").attr("required", false);
        $("#campo_tipo_cambio").removeClass("required")
    }else{
        $('#tipo_cambio').attr('disabled',false)
        $("#tipo_cambio").attr("required", true);
        $("#campo_tipo_cambio").addClass("required")
    }

})

$("#igv_check").click(function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        $('#igv').val('18')
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        sumaTotal()

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
    }
});

$("#igv").on("change", function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        sumaTotal()

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
        sumaTotal()
    }
});

if ("{{$documento->igv}}") {
    $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        $('#igv').val('18')
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        // sumaTotal()
}else{
    $('#igv').attr('disabled', true)
    $('#igv_requerido').removeClass("required")
    $('#igv').prop('required', false)
    $('#igv').val('')
    $('#igv_int').text('')
}


//Select2
$(".select2_form").select2({
    placeholder: "SELECCIONAR",
    allowClear: true,
    height: '200px',
    width: '100%',
});

$('#fecha_documento .input-group.date , #fecha_vencimiento_campo .input-group.date , #fecha_entrega .input-group.date , #fecha_vencimiento_campo_editar .input-group.date ').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    language: 'es',
    format: "dd/mm/yyyy",
})

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false
})

// Solo campos numericos
$('#tipo_cambio , #costo_flete , #precio , #flete_table').keyup(function() {
    var val = $(this).val();1
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});


function activarNumero(){
    $('#numero_tipo').val('')
    $('#numero_comprobante').addClass('required')
    $('#numero_tipo').prop('required', true)
    $('#numero_tipo').prop('disabled', false)
}


$('#numero_tipo').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#cantidad_editar').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#precio_editar').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$('#tipo_cambio').keyup(function() {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});

$('#cantidad').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('#igv').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});


function validarFecha() {
    var enviar = false
    var productos = registrosproductos()

    if ($('#fecha_documento_campo').val() == '') {
        toastr.error('Ingrese Fecha de Documento de la Orden.', 'Error');
        $("#fecha_documento_campo").focus();
        enviar = true;
    }

    if ($('#fecha_entrega_campo').val() == '') {
        toastr.error('Ingrese Fecha de Entrega de la Orden.', 'Error');
        $("#fecha_entrega_campo").focus();
        enviar = true;
    }

    if (productos == 0) {
        toastr.error('Ingrese al menos 1 Producto.', 'Error');
        enviar = true;
    }

    return enviar
}

$('#enviar_orden').submit(function(e) {
    e.preventDefault();
    var correcto = validarFecha()
    if (correcto == false) {

        Swal.fire({
            title: 'Opción Guardar',
            text: "¿Seguro que desea guardar cambios?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                cargarproductos()
                this.submit();

            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'La Solicitud se ha cancelado.',
                    'error'
                )
            }
        })
    }



})

$("#igv_check").click(function() {
    if ($("#igv_check").is(':checked')) {
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)
        $('#igv').val('18')
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)
        sumaTotal()

    } else {
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)
        $('#igv').val('')
        $('#igv_int').text('')
        sumaTotal()
    }
});


$(document).ready(function() {

    // DataTables
    table = $('.dataTables-orden-detalle').DataTable({
        "dom": 'lTfgitp',
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": false,
        "language": {
            "url": "{{asset('Spanish.json')}}"
        },

        "columnDefs": [{
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {

                "targets": [1],
                className: "text-center",
                render: function(data, type, row) {
                    return "<div class='btn-group'>" +
                        "<a class='btn btn-warning btn-sm modificarDetalle' id='editar_producto' style='color:white;' title='Modificar'><i class='fa fa-edit'></i></a>" +
                        "<a class='btn btn-danger btn-sm' id='borrar_producto' style='color:white;' title='Eliminar'><i class='fa fa-trash'></i></a>" +
                        "</div>";
                }
            },
            {
                "targets": [2],        
                className: "text-center",
            },
            {
                "targets": [3],
            
            },
            {
                "targets": [4],
                className: "text-center",
            },

            {
                "targets": [5],
                className: "text-center",
            },
            {
                "targets": [6],
                className: "text-center",
            },

            {
                "targets": [7],
                className: "text-center",
            },

            {
                "targets": [8],
                className: "text-center",
                visible: false
            },
            {
                "targets": [9],
                className: "text-center",
                visible: false
            },
                    


        ],
    });

    @if(old('igv_check', $documento->igv_check))
        $("#igv_check").attr('checked', true);
        $('#igv').attr('disabled', false)
        $('#igv_requerido').addClass("required")
        $('#igv').prop('required', true)        
        var igv = ($('#igv').val()) + ' %'
        $('#igv_int').text(igv)


    @else
        $("#igv_check").attr('checked', false);
        $('#igv').attr('disabled', true)
        $('#igv_requerido').removeClass("required")
        $('#igv').prop('required', false)

    @endif

    obtenerTabla()
    sumaTotal()

})

function obtenerTabla() {
    var t = $('.dataTables-orden-detalle').DataTable();
    @foreach($detalles as $detalle)
    
    t.row.add([
        "{{$detalle->producto_id}}",
        '',
        "{{$detalle->cantidad}}",
        "{{$detalle->producto->nombre.' - '.$detalle->lote}}",
        "{{$detalle->fechaFormateada()}}",
        "{{$detalle->costo_flete}}",
        "{{$detalle->precio}}",
        ("{{$detalle->precio}}" * "{{$detalle->cantidad}}").toFixed(2),
        "{{$detalle->lote}}",
        '1'
    ]).draw(false);
    @endforeach
}
//Editar Registro
$(document).on('click', '#editar_producto', function(event) {
    var data = table.row($(this).parents('tr')).data();
    console.log(data);
    $('#indice').val(table.row($(this).parents('tr')).index());
    $('#producto_id_editar').val(data[0]).trigger('change');
    $('#precio_editar').val(data[6]);
    $('#costo_flete_editar').val(data[5]);
    $('#fecha_vencimiento_editar').val(data[4]);
    $('#lote_editar').val(data[8]);
    $('#editable_lote').val(data[10]);
    $('#cantidad_editar').val(data[2]);
    
    //MOSTRAR TABLA SI ES INGRESO POR PRIMERA VEZ DEL LOTE
    if (data[9]=='' && data[8] != '') {
        $('#modalLote').hide(); 
        $('#editarLote').hide(); 
        $('#editarRegistro').show(); 
    }else{
        if (data[9]!='1') { 
            $('#modalLote').show(); 
            $('#editarLote').show(); 
            $('#editarRegistro').hide(); 
        } else { 
            $('#modalLote').hide(); 
            $('#editarLote').hide(); 
            $('#editarRegistro').show(); 
        }   
    }
    $('#modal_editar_orden').modal('show');

})

//Borrar registro de productos
$(document).on('click', '#borrar_producto', function(event) {

    Swal.fire({
        title: 'Opción Eliminar',
        text: "¿Seguro que desea eliminar Producto?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: "#1ab394",
        confirmButtonText: 'Si, Confirmar',
        cancelButtonText: "No, Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            table.row($(this).parents('tr')).remove().draw();
            sumaTotal()
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelado',
                'La Solicitud se ha cancelado.',
                'error'
            )
        }
    })



});

//Validacion al ingresar tablas
$(".enviar_producto").click(function() {
    limpiarErrores()
    var enviar = false;
    if ($('#producto_id').val() == '') {
        toastr.error('Seleccione Producto.', 'Error');
        enviar = true;
        $('#producto_id').addClass("is-invalid")
        $('#error-producto').text('El campo Producto es obligatorio.')
    } else {
        var existe = buscarproducto($('#producto_id').val())
        if (existe == true) {
            toastr.error('Producto ya se encuentra ingresado.', 'Error');
            enviar = true;
        }
    }
    if ($('#precio').val() == '') {

        toastr.error('Ingrese el Producto del Producto.', 'Error');
        enviar = true;

        $("#precio").addClass("is-invalid");
        $('#error-precio').text('El campo Precio es obligatorio.')
    }

    if ($('#cantidad').val() == '') {
        toastr.error('Ingrese cantidad del Producto.', 'Error');
        enviar = true;

        $("#cantidad").addClass("is-invalid");
        $('#error-cantidad').text('El campo Cantidad es obligatorio.')
    }

    if ($('#costo_flete').val() == '') {
        toastr.error('Ingrese Costo de Flete del Producto.', 'Error');
        enviar = true;

        $("#costo_flete").addClass("is-invalid");
        $('#error-costo_flete').text('El campo Costo de Flete es obligatorio.')
    }

    if ($('#lote').val() == '') {
        toastr.error('Ingrese el Lote del Producto.', 'Error');
        enviar = true;

        $("#lote").addClass("is-invalid");
        $('#error-lote').text('El campo Lote es obligatorio.')
    }

    if ($('#fecha_vencimiento').val() == '') {
        toastr.error('Ingrese la Fecha de Vencimiento del Producto.', 'Error');
        enviar = true;

        $("#fecha_vencimiento").addClass("is-invalid");
        $('#error-fecha_vencimiento').text('El campo Fecha de Vencimiento es obligatorio.')
    }



    if (enviar != true) {

        Swal.fire({
            title: 'Opción Agregar',
            text: "¿Seguro que desea agregar Producto?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: "#1ab394",
            confirmButtonText: 'Si, Confirmar',
            cancelButtonText: "No, Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                var descripcion_producto = obtenerproducto($('#producto_id').val())
                var detalle = {
                    producto_id: $('#producto_id').val(),
                    descripcion: descripcion_producto.nombre+' - '+$('#lote').val(),
                    costo_flete: $('#costo_flete').val(),
                    precio: $('#precio').val(),
                    cantidad: $('#cantidad').val(),
                    lote: $('#lote').val(),
                    fecha_vencimiento: $('#fecha_vencimiento').val(),
                }
                // limpiarDetalle()
                agregarTabla(detalle);
                sumaTotal()

            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'La Solicitud se ha cancelado.',
                    'error'
                )
            }
        })

    }
})

function limpiarDetalle() {
    $('#presentacion').val('')
    $('#precio').val('')
    $('#cantidad').val('')
    $('#costo_flete').val('')
    $('#producto_id').val($('#producto_id option:first-child').val()).trigger('change');
    $('#lote').val('')
    $('#fecha_vencimiento').val('')
}

function limpiarErrores() {
    $('#cantidad').removeClass("is-invalid")
    $('#error-cantidad').text('')

    $('#precio').removeClass("is-invalid")
    $('#error-precio').text('')

    $('#producto_id').removeClass("is-invalid")
    $('#error-producto').text('')

    $('#costo_flete').removeClass("is-invalid")
    $('#error-costo_flete').text('')

    $('#fecha_vencimiento').removeClass("is-invalid")
    $('#error-fecha_vencimiento').text('')

    $('#lote').removeClass("is-invalid")
    $('#error-lote').text('')
}

//OBTENER EL producto POR SU ID 
function obtenerproducto(id) {
    var producto = "";
    $.ajax({
      url: '{{ route("getProducto", ":id") }}'.replace(':id', id),
      async: false,  
      success:function(data) {
        producto = (data) ? data : toastr.error('El Producto no se encuentra en Base de Datos.', 'Error'); 
      }
   });
   return producto;
}
//AGREGAR EL CAMPO PRESENTACION Y PRECIO DEL PRODUCTO
function cargarPresentacion(producto) {
    $('#precio').val(obtenerproducto(producto.value).precio_venta_minimo)
}

$("#moneda").on("change", function() {
    var val = $(this).val();
    if (val == "SOLES") {
        $('#tipo_cambio').attr('disabled',true)
        $('#tipo_cambio').val('')
        $("#tipo_cambio").attr("required", false);
        $("#campo_tipo_cambio").removeClass("required")

    }else{
        $('#tipo_cambio').attr('disabled',false)
        $('#tipo_cambio').val('')
        $("#tipo_cambio").attr("required", true);
        $("#campo_tipo_cambio").addClass("required")
    }
});

function productoPresentacion(producto) {
    var presentacion = ""
    @foreach($productos as $producto)
    if ("{{$producto->id}}" == producto) {
        presentacion = "{{$producto->presentacion}}"
    }
    @endforeach
    return presentacion
}


function agregarTabla($detalle) {

    var t = $('.dataTables-orden-detalle').DataTable();
    t.row.add([
        $detalle.producto_id,
        '',
        $detalle.cantidad,
        $detalle.descripcion,
        $detalle.fecha_vencimiento,
        $detalle.costo_flete,
        $detalle.precio,
        ($detalle.cantidad * $detalle.precio).toFixed(2),
        $detalle.lote,
        editable($detalle.editable)
    ]).draw(false);
    cargarproductos()

}

//EDITABLE SIRVE PARA MANEJAR EL AGREGAR LOTES EN UN PRODUCTO
function editable(editable) {
    if (editable) {
        return editable
    }else{
        return ''
    }
}

function buscarproducto(id) {
    var existe = false;
    table.rows().data().each(function(el, index) {
        (el[0] == id && $('#lote').val() == el[9]) ? existe = true : ''
    });
    return existe
}


function cargarproductos() {

    var productos = [];
    var table = $('.dataTables-orden-detalle').DataTable();
    var data = table.rows().data();
    data.each(function(value, index) {
        let fila = {
            producto_id: value[0],
            cantidad: value[2],
            nombre: value[3],
            costo_flete: value[5],
            precio: value[6],
            fecha_vencimiento: value[4],
            lote: value[8],
        };

        productos.push(fila);

    });

    $('#productos_tabla').val(JSON.stringify(productos));
}

function obtenerPresentacion($descripcion) {
    var presentacion = ""
    @foreach($presentaciones as $presentacion)
    if ("{{$presentacion->descripcion}}" == $descripcion) {
        presentacion = "{{$presentacion->simbolo}}"
    }
    @endforeach
    return presentacion;
}

function registrosproductos() {
    var registros = table.rows().data().length;
    return registros
}


function sumaTotal() {
    var subtotal = 0;
    table.rows().data().each(function(el, index) {
        subtotal = Number(el[7]) + subtotal
    });

    var igv = $('#igv').val()
    if (!igv) {
        sinIgv(subtotal)   
    }else{
        conIgv(subtotal)
    }
}

function sinIgv(subtotal) {
    // calular igv (calcular la base)
    let igv = 18;
    var calcularIgv = igv/100
    var base = subtotal / (1 + calcularIgv)
    var nuevo_igv = subtotal - base;
    $('#igv_int').text(igv+'%')
    $('#subtotal').text(base.toFixed(2))
    $('#igv_monto').text(nuevo_igv.toFixed(2))
    $('#total').text(subtotal.toFixed(2))

}

function conIgv(subtotal) {
    // calular igv (calcular la base)
    var igv = $('#igv').val()
    ///////////////////////////////

    if (igv) {
        var calcularIgv = igv/100
        var base = subtotal / (1 + calcularIgv)
        var nuevo_igv = subtotal - base;
        $('#igv_int').text(igv+'%')
        $('#subtotal').text(base.toFixed(2))
        $('#igv_monto').text(nuevo_igv.toFixed(2))
        $('#total').text(subtotal.toFixed(2))

    }else{
        toastr.error('Ingrese Igv.', 'Error');
    }

}

$(document).on("change", "#proveedor_razon", function () {
   id = $(this).val();
   if($("#proveedor_id").val() != id){
      $("#proveedor_id").select2('val',id);
   }
});

$(document).on("change", "#proveedor_id", function () {
   id = $(this).val();
   if($("#proveedor_razon").val() != id){
       $("#proveedor_razon").select2('val',id);
   }

 });
</script>
@endpush