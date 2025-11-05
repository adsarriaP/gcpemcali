<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Aprobar asignación</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="aprobaciones/aceptarAsignacion">Aceptar</a></li>
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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Solicitud</th>
                                            <th>Trámite</th>
                                            <th>Tipo</th>
                                            <th>Código externo</th>
                                            <th colspan=2>Descripción</th>
                                            <th>Serie</th>
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

    <section class="content">
        <div class="container">
        	<div class="row">
        		<div class="col">
		        	<div class="card">
		            	<div class="card-header">
		            		<h3 class="card-title">Pasos para aceptar la asignación</h3>
		              	</div>
		              	<div class="card-body table-responsive p-0">
		                	<table class="table table-striped table-valign-middle">
		                		<tbody>
		                  			<tr>
		                    			<td>
		                    				<p>Paso 1</p>
		                    				<p class="text-muted">Descarga el formato usando el botón que está a la derecha <i class="fas fa-hand-point-right text-success"></i>, fírmalo preferiblemente con firma digitalizada, recuerda que debemos cuidar el planeta, si no tienes firma digitalizada imprímelo, fírmalo físicamente y luego escanéalo. Sigue al paso 2.
		                    				</p>
		                    			</td>
		                    			<td id="botonDescarga"></td>
		                    		</tr>
		                    		<tr>
		                    			<td>
		                    				<p>Paso 2</p>
		                    				<p class="text-muted">Importa el documento que generaste en el paso anterior usando el botón que está a la derecha <i class="fas fa-hand-point-right text-success"></i>. Sigue al paso 3.
		                    				</p>
		                    			</td>
		                    			<td id="botonCarga"></td>
		                    		</tr>
		                    		<tr>
		                    			<td>
		                    				<p>Paso 3</p>
		                    				<p class="text-muted">Verifica el archivo que cargaste usando el botón que está a la derecha <i class="fas fa-hand-point-right text-success"></i>, debe ser consistente y estar firmado. Si no cumple con estos criterios repite los pasos 1 y 2. Sigue al paso 4.
		                    				</p>
		                    			</td>
		                    			<td id="botonVerificacion"></td>
		                    		</tr>
		                    		<tr>
		                    			<td>
		                    				<p>Paso 4</p>
		                    				<p class="text-muted">Cierra el proceso una vez cumplido lo pasos anteriores usando el botón que esta a la derecha <i class="fas fa-hand-point-right text-success"></i>.
		                    				</p>
		                    			</td>
		                    			<td id="botonCierre"></td>
		                    		</tr>
                                </tbody>
		                	</table>
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
	var id = <?=$parametros[0]?>;
	function init(info){
		//Cargar datos de la solicitud
		enviarPeticion('solicitudes', 'getSolicitudes', {criterio: 'id', id: id}, function(r){
			let fila = ''
            //let botonesArchivo = ''
            r.data.map(registro => {
                fila = `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${tramites[registro.tramite]}</td>
                            <td>${tipos[registro.tipo]}</td>
                            <td>${registro.codigo}</td>
                            <td class="text-center">${clasesIconos[registro.clase]}</td>
                            <td>${registro.elemento}</td>
                            <td>${registro.serie}</td>
                        </tr>`
            })
            $('#contenido').html(fila)
		})

		//Crear boton de descarga
		$('#botonDescarga').html(`<a class="btn btn-success btn-lg" href="aprobaciones/comprobante/${id}" target="_blank" title="Descargar formato">
                    				<i class="fas fa-file-download"></i>
                    			</a>`)
		$('#botonCarga').html(`<a class="btn btn-success btn-lg btn-file" title="Cargar archivo firmado">
                                    <i class="fas fa-upload text-light"></i>
                                    <input type='file' onchange="cargarArchivo(this,${id})" accept=".pdf">
                                </a>`)
		$('#botonVerificacion').html(`<button type="button" class="btn btn-success btn-lg" onClick="downloadDocument(${id})" title="Ver archivo">
                                        <i class="fas fa-search"></i>
                                    </button>`)
		$('#botonCierre').html(`<button type="button" class="btn btn-success btn-lg" onClick="aprobar(${id})" title="Aprobar">
                                    <i class="fas fa-check"></i>
                                </button>`)
	}

	function aprobar(idSolicitud){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de <strong>aprobar la asignación</strong> registrado en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){                
                //primero pregunto si existe el archivo                
                enviarPeticion('archivos', 'existDocumento', {id: idSolicitud}, function(r){
                    enviarPeticion('solicitudes', 'setEstado', {info: {estado: 2}, id: idSolicitud}, function(r){
                        window.location.href = 'aprobaciones/aceptarAsignacion/'
                    })
                })
            }
        })
    }
</script>
</body>
</html>