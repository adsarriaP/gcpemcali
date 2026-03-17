<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Aceptar elemento
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Asignación</li>
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
                                <table class="table table-bordered table-sm text-sm" id="tablaSolicitudes">
                                    <thead>
                                        <tr>
                                            <th>Solicitud</th>
                                            <th>Tramite</th>
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

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var elemento = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'receptor', estado: 1}, function(){
            inicializarDataTable()
        })

        //Fomulario para cancelar
        $('#formularioCancelar').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.estado = 11
            enviarPeticion('solicitudes', 'cancelar', {info: datos, id: id, elemento: elemento}, function(r){
                $('#modalCancelar').modal('hide')
                $(`#${id}`).hide('slow')
            })
        })


    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            let botonAceptar = ''
            let solicitudesProcesadas = {}
            r.data.map(registro => {
                console.log('registro----',registro)
                if(solicitudesProcesadas[registro.id]){
                    return
                }
                solicitudesProcesadas[registro.id] = true
                if(registro.tramite == 1){//Asignación
                    botonAceptar = `<a class="btn btn-default btn-sm" href="aprobaciones/aceptarAsignacionProceso/${registro.id}" title="Aprobar">
                                        Firmar ya
                                    </a></br></br>
                                    <button type="button" class="btn btn-default btn-sm" onClick="firmarAlmacen(${registro.id})" title="Aprobar">
                                        Firmar en almacén
                                    </button>`    
                }else{//Traspaso
                    botonAceptar = `<button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id})" title="Aprobar">
                                        <i class="fas fa-check text-success"></i>
                                    </button>`
                }
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${registro.nombretramite}</td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle('solicitud',${registro.id})" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>                                        
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="verElementos(${registro.id})" title="Ver elementos">
                                                Ver elementos
                                            </button>
                                        </td>
                                        <td>
                                            ${botonAceptar}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm text-danger" onClick="cancelarSolicitud(${registro.id},${registro.idElemento})" title="Cancelar solicitud">
                                                <i class="fas fa-trash-alt"></i>
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
            $('#conteo_total').text(`Total: ${Object.keys(solicitudesProcesadas).length || 0}`)
        })
    }

    function inicializarDataTable(){
        if($.fn.DataTable.isDataTable('#tablaSolicitudes')){
            $('#tablaSolicitudes').DataTable().destroy()
        }

        $('#tablaSolicitudes').DataTable({
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
            "order": [[0, 'desc']],
            "language":{
                "decimal":        "",
                "emptyTable":     "Sin datos para mostrar",
                "info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
                "infoEmpty":      "Mostrando 0 to 0 of 0 entries",
                "infoFiltered":   "(Filtrado de _MAX_ total registros)",
                "infoPostFix":    "",
                "thousands":      ".",
                "lengthMenu":     "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search":         "Buscar:",
                "zeroRecords":    "Ningún registro encontrado",
                "paginate": {
                    "first":      "Primero",
                    "last":       "Último",
                    "next":       "Sig",
                    "previous":   "Ant"
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            }
        })
    }

    function verElementos(idSolicitud){
        enviarPeticion('solicitudes', 'getSolicitudAll', {criterio: 'solicitud', id: idSolicitud}, function(r){
            let filas = ''
            let botonAceptar = ''
            r.data.map(registro => {
                if(registro.tramite == 1){//Asignación
                    botonAceptar = `<a class="btn btn-default btn-sm" href="aprobaciones/aceptarAsignacionProceso/${registro.id}" title="Aprobar">
                                        Firmar ya
                                    </a></br></br>
                                    <button type="button" class="btn btn-default btn-sm" onClick="firmarAlmacen(${registro.id})" title="Aprobar">
                                        Firmar en almacén
                                    </button>`
                }else{//Traspaso
                    botonAceptar = `<button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id})" title="Aprobar">
                                        <i class="fas fa-check text-success"></i>
                                    </button>`
                }

                filas += `<tr>
                            <td>${registro.codigo}</td>
                            <td class="text-center">${clasesIconos[registro.clase]}</td>
                            <td>${registro.elemento}</td>
                            <td>${registro.serie}</td>
                            <td>
                                 <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm text-danger" onClick="cancelarSolicitud(${registro.id},${registro.idElemento})" title="Cancelar solicitud">
                                                <i class="fas fa-trash-alt"></i>
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

            Swal.fire({
                title: `Elementos de la solicitud #${idSolicitud}`,
                html: `<div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm text-sm" id="tablaElementosSolicitud">
                                <thead>
                                    <tr>
                                        <th>Código externo</th>
                                        <th>Clase</th>
                                        <th>Descripción</th>
                                        <th>Serie</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>${filas}</tbody>
                            </table>
                        </div>`,
                width: '70%',
                didOpen: () => {
                    $('#tablaElementosSolicitud').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": false,
                        "lengthChange": false,
                        "pageLength": 10,
                        "language": {
                            "emptyTable": "Sin datos para mostrar",
                            "search": "Buscar:",
                            "zeroRecords": "Ningún registro encontrado",
                            "paginate": {
                                "next": "Sig",
                                "previous": "Ant"
                            }
                        }
                    })
                }
            })
        })
    }

    function aprobar(idSolicitud){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de <strong>aprobar el traspaso</strong> registrado en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){                
                enviarPeticion('solicitudes', 'setEstado', {info: {estado: 3}, id: idSolicitud}, function(r){
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }

    function firmarAlmacen(idSolicitud){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de <strong>aceptar la asignación</strong> registrado en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){                
                enviarPeticion('solicitudes', 'setEstado', {info: {estado: 2}, id: idSolicitud}, function(r){
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }

    function retirarElemento(idSolicitud, idElemento){
        id = idSolicitud
        elemento = idElemento
        $('#modalCancelar').modal('show')
    }
</script>
</body>
</html>