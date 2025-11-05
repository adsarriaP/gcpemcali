<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Firmar
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="aprobaciones/recogerFirmas/">Recoger</a></li>
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
    				<div class="card card-outline card-primary">
    					<div class="table-responsive">
        					<table class="table">
        						<tr>
            						<th style="width:30%">Solicitud:</th>
            						<td id="solicitud"></td>
          						</tr>
        						<tr>
            						<th style="width:30%">Trámite:</th>
            						<td id="tramite"></td>
          						</tr>
        						<tr>
            						<th>Tipo elemento:</th>
            						<td id="te"></td>
          						</tr>
          						<tr>
            						<th>Código:</th>
            						<td id="codigo"></td>
          						</tr>
          						<tr>
            						<th>Descripción:</th>
            						<td id="descripcion"></td>
          						</tr>
          						<tr>
            						<th>Nombre receptor:</th>
            						<td id="receptor"></td>
          						</tr>
          					</table>
          				</div>
    					<div class="card-body text-center">
    						<canvas id="signature-pad" width="800px" height="400px" style="border: 1px solid #000;"></canvas>
        					</br>
    						<button class="btn btn-secondary btn-lg" id="borrar">Borrar</button>
    						<button class="btn btn-success btn-lg" id="guardar">Guardar Firma</button>
    						<button class="btn btn-success btn-lg" id="comprobante" style="display: none;">Comprobante</button>
    						<button class="btn btn-success btn-lg" id="terminar" style="display: none;">Terminar</button>
    					</div>
    				</div>
    			</div>
        	</div>
        	<div class="row">
        		<div class="col">
        			
        		</div>
        	</div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<!--script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script-->
<script src="node_modules/signature_pad/dist/signature_pad.umd.min.js"></script>
<script type="text/javascript">
	var id = <?=$parametros[0]?>;
	var elemento = 0
	var receptor = 0
	function init(info){		
		//Cargar información base
		enviarPeticion('solicitudes', 'getSolicitudes', {criterio: 'id', id: id}, function(r){
			$('#solicitud').text(r.data[0].id)
			$('#tramite').text(tramites[r.data[0].tramite])
			$('#te').text(tipos[r.data[0].tipo])
			$('#codigo').text(r.data[0].codigo)
			$('#descripcion').text(r.data[0].elemento)			
			elemento = r.data[0].idElemento
			receptor = r.data[0].idReceptor
			enviarPeticion('usuarios', 'select', {info: {id: r.data[0].idReceptor}}, function(r){
				$('#receptor').text(r.data[0].nombre)
			})
		})

        // Inicializar el Signature Pad
		const canvas = document.getElementById('signature-pad')
		const signaturePad = new SignaturePad(canvas)

		// Limpiar la firma
		$('#borrar').click(function() {
    		signaturePad.clear()
		});

		// Guardar la firma
		$('#guardar').click(function() {
    		if (signaturePad.isEmpty()) {
    			toastr.error("Debe firmar")
        		return
    		}
    		// Convertir la firma a imagen en base64
    		const dataUrl = signaturePad.toDataURL()

    		// Enviar la imagen al servidor
    		enviarPeticion('firmas', 'guardar', {id: id, firma: dataUrl}, function(r){
    			toastr.success(r.mensaje)
    			$('#terminar').fadeIn();
    			$('#comprobante').fadeIn();
    			//window.open(`aprobaciones/comprobante/${id}`, '_blank')
    		})
		})

		$('#comprobante').click(function(){
			window.open(`aprobaciones/comprobante/${id}`, '_blank')
		})

		//Terminar trámite
		$('#terminar').click(function(){
			enviarPeticion('solicitudes', 'setFirma', {info: {modo_recepcion: 'Firma'}, id: id}, function(r){
                window.location.href = 'aprobaciones/recogerFirmas/'
            })
		})
    }
</script>
</body>
</html>