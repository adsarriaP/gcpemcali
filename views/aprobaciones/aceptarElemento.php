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
                                <table class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Solicitud</th>
                                            <th>Trámite</th>
                                            <th>Tipo</th>
                                            <th>Código externo</th>
                                            <th colspan=2>Descripción</th>
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

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var elemento = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'receptor', estado: 1}, function(){
            console.log('Cargo...')
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
            $('#contenido').append(fila)
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
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
</script>
</body>
</html>