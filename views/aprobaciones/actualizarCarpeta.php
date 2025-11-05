<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Actualizar carpeta
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Carpeta</li>
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
                                            <th>Código</th>                            
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
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 8}, function(){
            console.log('Cargo...')
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            let botonesArchivo = ''
            r.data.map(registro => {
                botonesArchivo = ''
                if(registro.tramite == 1){
                    botonesArchivo = `<td>
                                        <button type="button" class="btn btn-default btn-sm" onClick="downloadDocument(${registro.id})" title="Ver archivo">
                                            <i class="fas fa-file-import"></i>
                                        </button>
                                    </td>`
                }else{
                    botonesArchivo = `<td>
                                        <a href="aprobaciones/generar/${registro.id}" class="btn btn-default btn-sm" title="Generar reporte" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </td>`
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
                                        ${botonesArchivo}
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id},${registro.tipo},${registro.tramite},${registro.idElemento},${registro.idReceptor})" title="Aprobar">
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
            $('#contenido').append(fila)
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function aprobar(idSolicitud, tipo, tramite, elemento, receptor){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de que ya actualizó la carpeta con la información registrada en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let estado = 0
                if(tramite == 1){//Asignación
                    enviarPeticion('solicitudes', 'setEstado', {info: {estado: 10}, id: idSolicitud}, function(r){
                        $(`#${idSolicitud}`).hide('slow')
                    })
                }else if(tramite == 3){//Traspaso
                    //Ejecutar traspaso
                    enviarPeticion('solicitudes', 'asignar', {elemento: elemento, receptor: receptor, solicitud: idSolicitud, estado: 10}, function(r){
                        $(`#${idSolicitud}`).hide('slow')
                    })
                }else if(tramite == 4){                    
                    //Ejecutar reintegro
                    enviarPeticion('solicitudes', 'descargar', {info: {estado: 10}, id: idSolicitud, elemento: elemento}, function(r){
                        $(`#${idSolicitud}`).hide('slow')
                    })
                }
            }
        })
    }
</script>
</body>
</html>