<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Recibir en almacen
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Recibir almacen</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">   
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Solicitud</th>
                                            <th>Trámite</th>
                                            <th>Tipo</th>
                                            <th>Código</th>
                                            <th></th>
                                            <th>Descripción</th>
                                            <th>Serie</th>
                                            <th>Días</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenido"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    function inicializarDataTable(){
        if($.fn.DataTable.isDataTable('#tabla')){
            $('#tabla').DataTable().destroy()
        }

        $('#tabla').DataTable({
            dom: 'Bfrtip',
            searching: true,
            search: {
                smart: true
            },
            lengthMenu: [50, 100, 200],
            pageLength: 50,
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ],
            columnDefs: [
                { targets: [8], orderable: false, searchable: false }
            ]
        })
    }

    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 6}, function(){
            inicializarDataTable()
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${tramites[registro.tramite]}</td>
                            <td>${tipos[registro.tipo]}</td>
                            <td>${registro.codigo}</td>
                            <td class="text-center">${clasesIconos[registro.clase]}</td>
                            <td>${registro.elemento}</td>
                            <td>${registro.serie}</td>
                            <td class="text-center">${registro.tiempo}</td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle('solicitud',${registro.id})" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id},${registro.tipo})" title="Recibido">
                                                <i class="fas fa-check text-success"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarHistorico(${registro.id},${registro.tramite},${registro.tipo},${registro.clase})" title="Historico">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>`
            })
            $('#contenido').html(fila)
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function aprobar(idSolicitud, tipo){
         Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro que <strong>recibió</strong> el elemento registrado en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let estado = 0
                if(tipo == 1){//Activo
                    estado = 7
                }else{ //Controlado o AO
                    estado = 8
                }
                enviarPeticion('solicitudes', 'setEstado', {info: {estado: estado}, id: idSolicitud}, function(r){
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }
</script>
</body>
</html>