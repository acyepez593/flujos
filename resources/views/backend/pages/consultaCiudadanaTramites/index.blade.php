@extends('backend.layouts.publicMaster')

@section('title')
    {{ __('Consulta Trámites') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">

    <style>
        #buscarTramite{
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
        #overlay{	
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;  
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% { 
                transform: rotate(360deg); 
            }
        }
        .is-hide{
            display:none;
        }

        .timeline {
            background: linear-gradient(to right, transparent 0%, transparent 38px, rgb(230, 230, 230) 38px, rgb(230, 230, 230) 40px, transparent 40px, transparent 100%);
        }
        .timeline-icon {
            background-color: rgb(225, 228, 232);
            color: #788793;
        }
        .timeline-badge {
            width: 28px;
            height: 24px;
            margin-left: 0px;
            padding: 15px;
        }
        .align-items-center {
            align-items: center !important;
        }

        .justify-content-center {
            justify-content: center !important;
        }
        .fa-rotate-45 {
            transform: rotate(45deg);
        }
        .fa, .fas, .far, .fal, .fad, .fab {
            -moz-osx-font-smoothing: grayscale;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }
        .far {
            font-family: "FontAwesome";
            font-weight: 400;
        }

        .logoSppat{
            margin-left: auto;
            margin-right: auto;
            max-width: 60%;
        }
    </style>
@endsection

@section('admin-content')
<div class="main-content-inner">

    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                @include('backend.layouts.partials.messages')
                    <div id="success_alert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                        <span class="message"></span>
                        <button type="button" class="close" onclick="$('#success_alert').hide()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <span class="message"></span>
                        <button type="button" class="close" onclick="$('#error_alert').hide()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card">
                        <img class="logoSppat" src="data:image/jpeg;base64,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" />
                        
                        <h5 class="card-header">Consulta de trámites</h5>
                        <div class="card-body">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="proceso_id">Seleccione la Protección:</label>
                                        <select id="proceso_id" name="proceso_id" class="form-control selectpicker" data-live-search="true">
                                            <option value="">Seleccione un Proceso</option>
                                            @foreach ($procesos as $key => $value)
                                                <option value="{{ $value->id }}" {{ ($key) == 0 ? 'selected' : '' }}>{{ $value->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="tramite_id">Ingrese el Número de Trámite</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control @error('tramite_id') is-invalid @enderror" id="tramite_id" name="tramite_id" value="{{ old('tramite_id') }}" required>
                                            @error('tramite_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="offset-md-3"></div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <strong>ReCaptcha:</strong>
                                            <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="buscarTramite" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
        <!-- Modal Ver Detalle -->
        <div class="modal fade" id="modalVerDetalle" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Trazabilidad Trámite</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tab-pane container">
                        <div id="detalleTrazabilidad"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
</div>

@endsection

@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     <script src='https://www.google.com/recaptcha/api.js'></script>
     
     <script>

        $(document).ready(function() {

            $( "#buscarTramite" ).on( "click", function() {
                $("#overlay").fadeIn(300);
                $("#detalleTrazabilidad").empty();
                
                let proceso_id = $('#proceso_id').val();
                let tramite_id = $('#tramite_id').val();
                let g_recaptcha = $('#g-recaptcha-response').val();

                $.ajax({
                    url: "{{url('/consultaTramiteSppat')}}",
                    method: "POST",
                    data: {
                        proceso_id: proceso_id,
                        tramite_id: tramite_id,
                        g_recaptcha: g_recaptcha,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json'
                })
                .done(function(response) {
                    $("#overlay").fadeOut(300);
                    trazabilidad = response.trazabilidad;
                    construirTrazabilidad(trazabilidad);
                    $("#modalVerDetalle").modal('show');
                    $('#success_alert .message').html('¡Consulta exitosa!');
                    $('#success_alert').show();
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    $("#overlay").fadeOut(300);
                    console.log("Response Text:", jqXHR.responseText);
                    let error_alert = JSON.parse(jqXHR.responseText);
                    $('#error_alert .message').html(error_alert.message);
                    $('#error_alert').show();
                });
                
            });

            $('#success_alert').hide();
            $('#error_alert').hide();

        });

        let trazabilidad = [];

        function construirTrazabilidad(trazabilidad){
            let html_trazabilidad = '<div class="px-4 mb-2 timeline">';

            for (let traz of trazabilidad) {
                switch (traz.tipo) {
                    case "CREACION":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-circle fa-2x" style="color: blue;"></i>'+
                            '</div> '+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> ' + traz.funcionario_actual_nombre + ' creó el trámite.'+
                            '</div>'+
                        '</div>';
                        break;
                    case "CAMBIO SECCION":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-square fa-2x"></i>'+
                            '</div>'+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> ' + traz.funcionario_actual_nombre + ' recibió el trámite para completar la actividad "' + traz.secuencia_proceso_nombre + '"'+
                            '</div>'+
                        '</div>';
                        break;
                    case "CONDICIONAL":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-square fa-rotate-45 fa-2x"></i>'+
                            '</div>'+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> ' + traz.funcionario_actual_nombre + ' Aprobar: Si'+
                            '</div>'+
                        '</div>';
                        break;
                    case "FINALIZACION":
                        html_trazabilidad += '<div class="py-3 d-flex" read-only="true">'+
                            '<div class="badge timeline-badge mr-1 rounded d-flex justify-content-center align-items-center timeline-icon">'+
                                '<i class="far fa-circle fa-2x" style="color: red;"></i>'+
                            '</div> '+
                            '<div class="flex-grow-1">'+
                                '<strong>'+ moment(traz.created_at).format("YYYY-MM-DD HH:mm")+'</strong> Finalizó el trámite.'+
                            '</div>'+
                        '</div>';
                        break;
                }
            }
            html_trazabilidad += '</div>';
            $("#detalleTrazabilidad").append(html_trazabilidad);
        }
        
     </script>
@endsection