<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Recoger firmas
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Firmar</li>
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
                                            <th>Nombre</th>
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

    <div class="modal fade" id="modalDetalle">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalDetalleTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                    
                    <table class="table table-bordered table-striped table-sm text-sm">
                        <thead>
                            <tr class="text-center">
                                <th>Campo</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody id="contenidoDetalle"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'todas', estado: 2}, 'crear',function(){
            console.log('Cargo...')
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            let botonModo = ''
            r.data.map(registro => {
                botonModo = ''
                if(registro.modo == 'Pendiente'){
                    botonModo = `<a class="btn btn-default btn-sm" href="aprobaciones/firmar/${registro.id}" title="Firmar digitalmente">
                                    <i class="fas fa-signature"></i>
                                </a>
                                <a class="btn btn-default btn-sm" href="aprobaciones/comprobante/${registro.id}" target="_blank" title="Generar formato">
                                    <i class="fas fa-file-signature"></i>
                                </a>
                                <a class="btn btn-default btn-sm btn-file" title="Cargar archivo firmado">
                                    <i class='fas fa-upload'></i>
                                    <input type='file' onchange="cargarArchivo(this,${registro.id})" accept=".pdf">
                                </a>`
                }else if(registro.modo == 'Firma'){
                    botonModo = `<a class="btn btn-success btn-sm" href="aprobaciones/comprobante/${registro.id}" target="_blank" title="Generar formato">
                                    <i class="fas fa-file-signature"></i>
                                </a>
                                <button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id},${registro.idElemento},${registro.idReceptor})" title="Aprobar">
                                    <i class="fas fa-check text-success"></i>
                                </button>`
                }else{
                    botonModo = `<a class="btn btn-default btn-sm btn-file" title="Cargar archivo firmado">
                                    <i class='fas fa-upload'></i>
                                    <input type='file' onchange="cargarArchivo(this,${registro.id})" accept=".pdf">
                                </a>
                                <button type="button" class="btn btn-default btn-sm" onClick="downloadDocument('${registro.id}.pdf')" title="Ver archivo">
                                    <i class="fas fa-file-download"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id},${registro.idElemento},${registro.idReceptor})" title="Aprobar">
                                    <i class="fas fa-check text-success"></i>
                                </button>`
                }
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${tramites[registro.tramite]}</td>
                            <td>${tipos[registro.tipo]}</td>
                            <td>${registro.codigo}</td>
                            <td>${registro.elemento}</td>
                            <td class="text-center">${registro.tiempo}</td>
                            <td>
                                <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle('solicitud',${registro.id})" title="ver detalle">
                                    <i class="fas fa-search"></i>
                                </button>
                                ${botonModo}
                                <!--button type="button" class="btn btn-default btn-sm" onClick="cancelar(${registro.id},${registro.tramite},${registro.idElemento})" title="Cancelar">
                                    <i class="fas fa-times text-danger"></i>
                                </button-->
                                <button type="button" class="btn btn-default btn-sm" onClick="mostrarHistorico(${registro.id},${registro.tramite})" title="Historico">
                                    <i class="fas fa-history"></i>
                                </button>
                            </td>
                        </tr>`
            })
            if(accion == 'crear'){
                $('#contenido').append(fila)    
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }            
            callback()
        })
    }

    function cancelar(id, tramite, elemento){
         Swal.fire({
            icon: 'warning',
            title: 'Confirmación',
            html: `Esta seguro de <strong>cancelar ${tramites[tramite]}</strong> registrado en la solicitud #${id}`,
            showCancelButton: true
        }).then((result) => {
            if(result.value){
                enviarPeticion('solicitudes', 'setEstado', {info: {estado: 9}, id: id, elemento: elemento}, function(r){
                    $(`#${id}`).hide('slow')
                })
            }
        })
    }

    function cargarArchivo(input, id){
        toastr.info('Por favor espere', 'Cargando...', {timeOut: 0})
        let archivo = input.files[0]
        let ext = archivo.name.split('.').pop();
        if(ext == 'pdf' || ext == 'PDF'){
            var fd = new FormData();
            fd.append('objeto','archivos')
            fd.append('metodo','cargarDocumento')
            fd.append('datos[id]',id)
            fd.append('file',archivo)
            $.ajax({                
                url:'api',
                type: "POST",
                dataType: 'json',
                data: fd,
                processData: false,
                contentType: false,
                success: function(respuesta){
                    toastr.clear()
                    if(respuesta.ejecuto == true){
                        toastr.success(respuesta.msg)
                        //Actualizo la solicitud con modo de recepción
                        enviarPeticion('solicitudes', 'update', {info: {modo_recepcion: 'Archivo'}, id: id}, function(r){
                            cargarRegistros({criterio: 'id', id: id}, 'Actualizar', function(){
                                console.log('Se actualizo')
                            })
                        })
                    }else{
                        toastr.error(respuesta.msg)
                    }                       
                },
                error: function(xhr, status){
                    console.log('Ocurrio un error')
                }
            })
        }else{
            toastr.error('El archivo debe tener extensión pdf')
        }
    }

    function downloadDocument(archivo, nombreExport, is_dw, owner) {
        enviarPeticion('archivos', 'getDocumento', {archivo: archivo}, function(response){
            if (response.file) {
                // Decodificar base64 a bytes binarios
                const binaryString = atob(response.file)
                const len = binaryString.length
                const bytes = new Uint8Array(len)
                for (let i = 0; i < len; i++) {
                    bytes[i] = binaryString.charCodeAt(i)
                }

                // Crear un Blob a partir de los bytes
                const blob = new Blob([bytes], { type: 'application/pdf' })
                const blobUrl = URL.createObjectURL(blob)

                // Abrir el PDF en una nueva pestaña
                const newTab = window.open(blobUrl, '_blank')

                if(is_dw){
                    // Descargar automáticamente el archivo con un nombre específico
                    if (newTab) {
                        newTab.onload = function () {
                            newTab.document.title = archivo // Cambia el título de la pestaña
                            const link = newTab.document.createElement("a")
                            link.href = blobUrl
                            link.download = nombreExport // Nombre del archivo a descargar
                            link.click()
                        };
                    }
                }
                // Limpia la URL temporal después de abrirla
                setTimeout(() => URL.revokeObjectURL(blobUrl), 1000)
            }
        })
    }

    function aprobar(id, elemento, receptor){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de <strong>aprobar la asignación</strong> registrado en la solicitud #${id}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                enviarPeticion('solicitudes', 'asignar', {elemento: elemento, receptor: receptor, solicitud: id}, function(r){
                    $(`#${id}`).hide('slow')
                })
            }
        })
    }
</script>
</body>
</html>