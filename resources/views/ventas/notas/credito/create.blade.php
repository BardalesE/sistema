@extends('layout') @section('content')

@section('ventas-active', 'active')
@section('documento-active', 'active')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10 col-md-10">
       <h2  style="text-transform:uppercase"><b>REGISTRAR NUEVA NOTA DE CRÉDITO</b></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Panel de Control</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('ventas.documento.index')}}">Documentos</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Nota de crédito</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">

                <div class="ibox-content">

                    <form action="{{route('ventas.notas.store')}}" method="POST" id="enviar_documento">
                        {{csrf_field()}}
                        <input type="hidden" name="documento_id" value="{{old('documento_id', $documento->id)}}">
                        <input type="hidden" name="tipo_nota" value="{{ $documento->tipo_nota }}">
                        <div class="row">
                            <div class="col-12 col-md-5 b-r">
                                <div class="row">
                                    <div class="col-12">
                                        <p style="text-transform:uppercase"><strong><i class="fa fa-caret-right"></i> Información de nota de crédito</strong></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Tipo Nota de Crédito</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <select name="cod_motivo" id="cod_motivo" class="select2_form form-control">
                                            <option value=""></option>
                                            @foreach(cod_motivos() as $item)
                                                <option value="{{ $item->simbolo }}">{{ $item->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Motivo</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <textarea name="des_motivo" id="des_motivo" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-7">
                                <div class="row">
                                    <div class="col-12">
                                        <p style="text-transform:uppercase"><strong><i class="fa fa-caret-right"></i> Información de cliente</strong></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group row">
                                            <div class="col-12 col-md-5">
                                                <label class="required">Cliente ID</label>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <input type="text" class="form-control" value="{{ $documento->clienteEntidad->id }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <div class=" form-group row">
                                            <div class="col-12 col-md-5">
                                                <label class="required">Tipo Doc. / Nro. Doc</label>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="text" class="form-control" value="{{ $documento->clienteEntidad->tipo_documento }}" readonly>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <input type="text" class="form-control" value="{{ $documento->clienteEntidad->documento }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-12 col-md-4">
                                                <label class="required">Nombre / Razón Social</label>
                                            </div>
                                            <div class="col-12 col-md-8">
                                                <input type="text" class="form-control" name="cliente" id="cliente" value="{{ $documento->clienteEntidad->nombre }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">{{ $documento->tipo_documento_cliente }}</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="documento_cliente" value="{{ $documento->tipo_documento_cliente }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row d-none">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Serie Nota</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="serie_nota" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-group row d-none">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Nro. Nota</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="numero_nota" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Emisión de Nota</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="date" class="form-control" name="fecha_emision" value="{{ $fecha_hoy }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Fecha Documento</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="date" class="form-control" name="fecha_documento" value="{{ $documento->fecha_documento }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Serie doc. afectado</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="serie_doc" value="{{ $documento->serie }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Nro. doc. afectado</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="numero_doc" value="{{ $documento->correlativo }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Tipo Pago</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control text-uppercase" name="tipo_pago" value="{{ $documento->formaPago() }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Sub Total</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="sub_total" value="{{ $documento->sub_total }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">IGV {{$documento->igv }}%</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="total_igv" value="{{ $documento->total_igv }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Total</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="total" value="{{ $documento->total }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12 col-md-5">
                                        <label class="required">Nuevo Total</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" name="nuevo_total" value="{{ $documento->total }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="prueba()"><i class="fa fa-refresh"></i></button>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="tbl-detalles" class="table table-hover tbl-detalles" style="width: 100%; text-transform:uppercase;">
                                                <thead>
                                                    <th></th>
                                                    <th>Cant.</th>
                                                    <th>Descripcion</th>
                                                    <th>P. Unit</th>
                                                    <th>Total</th>
                                                    <th>Opciones</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
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

                                <a href="{{route('ventas.documento.index')}}" id="btn_cancelar"
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
@include('ventas.documentos.modal')
@include('ventas.documentos.modalLote')

@stop
@push('styles')
<link href="{{ asset('Inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
<link href="{{ asset('Inspinia/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<!-- DataTable -->
<link href="{{asset('Inspinia/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

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

<script>

    $(document).ready(function() {

        $(".select2_form").select2({
            placeholder: "SELECCIONAR",
            allowClear: true,
            height: '200px',
            width: '100%',
        });

        actualizarData('{{ $documento->id }}')
        viewData();
        aceptarData();
        cancelarData();
        eliminarData();
    });

    function actualizarData(id) {
        let url = '{{ route("ventas.getDetalles",":id") }}';
        url = url.replace(':id',id);

        dibujarTabla();
        var t = $('.tbl-detalles').DataTable();
        t.clear().draw();
        $.ajax({
            dataType: 'json',
            type: 'get',
            url: url,
        }).done(function(result) {
            let detalles = result.detalles;
            for(let i = 0; i < detalles.length; i++)
            {
                agregarTabla(detalles[i]);
            }
            sumaTotal();
        });
    }

    function prueba()
    {   var t = $('.tbl-detalles').DataTable();
        t.rows().data().each(function(el, index) { 
            console.log(el);
        })
    }
    function sumaTotal()
    {
        let t = $('.tbl-detalles').DataTable();
        let total = 0;
        let detalles = [];
        t.rows().data().each(function(el, index) { 
            let cantidad = el[1]; 
            let descripcion = el[2]; 
            let precio_nuevo = el[3]; 
            let total_venta = el[1] * el[3];

            let detalle = { 
                cantidad: cantidad, 
                descripcion: descripcion,
                precio_nuevo: precio_nuevo,
                total_venta: total_venta,
            }

            detalles.push(detalle); 
        });

        t.clear().draw(); 
        if(detalles.length> 0)
        {
            for(let i = 0; i < detalles.length; i++) { 
                agregarTabla(detalles[i]); 
            }
        } 

        t.rows().data().each(function(el, index) { 
            total=Number(el[4]) + total
        });

        conIgv(convertFloat(total),convertFloat(18)) 
    }

    function conIgv(total, igv) {
        let subtotal = total / (1 + (igv / 100));
        let igv_calculado = total - subtotal;
        $('#sub_total').val((Math.round(subtotal * 10) / 10).toFixed(2))
        $('#total_igv').val((Math.round(igv_calculado * 10) / 10).toFixed(2))
        $('#total').val((Math.round(total * 10) / 10).toFixed(2))
        //Math.round(fDescuento * 10) / 10
    }

    //AGREGAR EL DETALLE A LA TABLA
    function agregarTabla($detalle) {
        var t = $('.tbl-detalles').DataTable();
        t.row.add([
            '',
            Number($detalle.cantidad).toFixed(2),
            $detalle.descripcion,
            Number($detalle.precio_nuevo).toFixed(2),
            Number($detalle.total_venta).toFixed(2),
            '',
        ]).draw(false);
        //cargarProductos()
    }

    function dibujarTabla()
    {
        $('#tbl-detalles').dataTable().fnDestroy();
        $('#tbl-detalles').DataTable({
            "ordering" : false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            /*"processing": true,
            "serverSide": true,
            "ajax": url,
            "columns": [
                { visible: false},
                { data: 'cantidad', className: 'cantidad', sWidth: '15%' },
                { data: 'descripcion', className: 'descripcion', sWidth: '40%' },
                { data: 'precio_nuevo', className: 'precio_nuevo', sWidth: '15%' },
                { data: 'total_venta', className: 'total_venta',sWidth: '15%' },
                {
                    defaultContent: '<div class="btn-group">' +
                        '<button id="editar" type="button" class="btn btn-sm btn-primary">' +
                        '<span class="glyphicon glyphicon-pencil" > </span>' +
                        '</button>' +
                        '<button id="eliminar" type="button" class="btn btn-sm btn-danger">' +
                        '<span class="glyphicon glyphicon-trash" > </span>' +
                        '</button>' +
                        '<button id="aceptar" type="button" class="btn btn-sm btn-success" style="display:none;">' +
                        '<span class="glyphicon glyphicon-ok" > </span>' +
                        '</button>' +
                        '<button id="cancelar" type="button" class="btn btn-sm btn-warning" style="display:none;">' +
                        '<span class="glyphicon glyphicon-remove" > </span>' +
                        '</button>' +
                        '</div>',
                    className: 'text-center',
                    sWidth: '15%'
                },
            ],*/
            "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [1],
                },
                {
                    "targets": [2],
                },
                {
                    "targets": [3],
                },
                {
                    "targets": [4],
                },
                {
                    "targets": [5],
                    data: null,
                    defaultContent: '<div class="btn-group">' +
                        '<button id="editar" type="button" class="btn btn-sm btn-primary">' +
                        '<span class="glyphicon glyphicon-pencil" > </span>' +
                        '</button>' +
                        '<button id="eliminar" type="button" class="btn btn-sm btn-danger">' +
                        '<span class="glyphicon glyphicon-trash" > </span>' +
                        '</button>' +
                        '<button id="aceptar" type="button" class="btn btn-sm btn-success" style="display:none;">' +
                        '<span class="glyphicon glyphicon-ok" > </span>' +
                        '</button>' +
                        '<button id="cancelar" type="button" class="btn btn-sm btn-warning" style="display:none;">' +
                        '<span class="glyphicon glyphicon-remove" > </span>' +
                        '</button>' +
                        '</div>',
                }
            ],
            'bAutoWidth': false,
            'aoColumns': [{
                    sWidth: '0%'
                },
                {
                    sWidth: '15%',                 
                    sClass: 'cantidad'
                },
                {
                    sWidth: '40%',                 
                    sClass: 'descripcion'
                },
                {
                    sWidth: '15%',                 
                    sClass: 'precio_nuevo'
                },
                {
                    sWidth: '15%',                    
                    sClass: 'total_venta'
                },
                {
                    sWidth: '15%',
                    sClass: 'text-center'
                },
            ],
            "language": {
                "url": "/Spanish.json"
            },
            "order": [
                [1, 'asc']
            ],
        });
    }

    function viewData() {
        $("#tbl-detalles").on('click', '#editar', function() {

            //var data = $("#propuestas").dataTable().fnGetData($(this).closest('tr'));

            $(this).parents("tr").find(".cantidad").each(function() {
                var cont = $(this).html();
                var input = '<input type="text" class="form-control cantidad_input" value="' + cont + '" onkeypress="return isNumber(event)"/>';
                $(this).html(input);
            });

            $(this).parents("tr").find(".precio_nuevo").each(function() {
                var cont = $(this).html();
                var input = '<input type="text" class="form-control precio_nuevo_input" value="' + cont + '" onkeypress="return isNumber(event)"/>';
                $(this).html(input);
            });

            $(this).parent().find("#editar").hide();
            $(this).parent().find("#eliminar").hide();
            $(this).parent().find("#aceptar").show();
            $(this).parent().find("#cancelar").show();
        });
    }

    function aceptarData() {
        $("#tbl-detalles").on('click', '#aceptar', function() {

            var data = $("#tbl-detalles").dataTable().fnGetData($(this).closest('tr'));
            let table = $('#tbl-detalles').DataTable();
            let index = table.row($(this).parents('tr')).index();
            $(this).parents("tr").find(".cantidad").each(function() {
                let con = $(this).find('.cantidad_input').val();
                $(this).html(con);

                table.cell({
                    row: index,
                    column: 1
                }).data(con).draw();
            });

            $(this).parents("tr").find(".precio_nuevo").each(function() {
                let con = $(this).find('.precio_nuevo_input').val();
                $(this).html(con);

                table.cell({
                    row: index,
                    column: 3
                }).data(con).draw();
            });

            $(this).parent().find("#editar").show();
            $(this).parent().find("#eliminar").show();
            $(this).parent().find("#aceptar").hide();
            $(this).parent().find("#cancelar").hide();

            sumaTotal();
        });
        
    }

    function cancelarData() {
        $("#tbl-detalles").on('click', '#cancelar', function() {

            var data = $("#tbl-detalles").dataTable().fnGetData($(this).closest('tr'));
            let index = table.row($(this).parents('tr')).index();
            $(this).parents("tr").find(".cantidad").each(function() {
                let con = $(this).find('.cantidad_input').val();
                $(this).html(con);

                table.cell({
                    row: index,
                    column: 1
                }).data(con).draw();
            });

            $(this).parents("tr").find(".precio_nuevo").each(function() {
                let con = $(this).find('.precio_nuevo_input').val();
                $(this).html(con);

                table.cell({
                    row: index,
                    column: 3
                }).data(con).draw();
            });

            $(this).parent().find("#editar").show();
            $(this).parent().find("#eliminar").show();
            $(this).parent().find("#aceptar").hide();
            $(this).parent().find("#cancelar").hide();

            sumaTotal();
        });
    }

    function eliminarData()
    {
        $("#tbl-detalles").on('click', '#eliminar', function() {
            let table = $('#tbl-detalles').DataTable();
            $(this).parents('tr').remove();

            sumaTotal();
        });
    }

</script>
@endpush