<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Entregar elemento
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Recoger</li>
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

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var elemento = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 2}, 'crear',function(){
            console.log('Cargo...')
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${tramites[registro.tramite]}</td>
                            <td>${tipos[registro.tipo]}</td>
                            <td>${registro.codigo}</td>
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
                                            <button type="button" class="btn btn-default btn-sm" onClick="downloadDocument(${registro.id})" title="Ver archivo">
                                                <i class="fas fa-file-import"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <a class="btn btn-default btn-sm" href="aprobaciones/comprobante/${registro.id}" target="_blank" title="Descargar formato">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-default btn-sm btn-file" title="Cargar archivo firmado">
                                                <i class="fas fa-upload"></i>
                                                <input type='file' onchange="cargarArchivo(this,${registro.id})" accept=".pdf">
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="asignar(${registro.id},${registro.tipo},${registro.tramite},${registro.idElemento},${registro.idReceptor})" title="Aprobar">
                                                <i class="fas fa-check text-success"></i>
                                            </button>
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

    function asignar(idSolicitud, tipo, tramite, elemento, receptor){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de <strong>aprobar la asignación</strong> registrado en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let estado = 0
                if(tramite == 1){
                    if(tipo == 1){//Si es Activo
                        estado = 7
                    }else{//Si es controlado o AO
                        estado = 8
                    }
                    enviarPeticion('archivos', 'existDocumento', {id: idSolicitud}, function(r){
                        enviarPeticion('solicitudes', 'asignar', {elemento: elemento, receptor: receptor, solicitud: idSolicitud, estado: estado}, function(r){
                            $(`#${idSolicitud}`).hide('slow')
                        })
                    })
                }
            }
        })
    }
</script>
</body>
</html>