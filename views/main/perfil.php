<?php require('views/header.php');?>

<div class="content-wrapper">
	<section class="content-header">
		<div class="container">
			<div class="row mb-2">
				<div class="col-sm-6">
            		<h1>Perfil</h1>
          		</div>
          		<div class="col-sm-6">
            		<ol class="breadcrumb float-sm-right">
              			<li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
              			<li class="breadcrumb-item active">Perfil</li>
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
    					<div class="card-body">
							<div class="table-responsive">
            					<table class="table">
            						<tr>
                						<th style="width:30%">Dependencia:</th>
                						<td id="dependencia"></td>
              						</tr>
            						<tr>
                						<th style="width:30%">Rol:</th>
                						<td id="rol"></td>
              						</tr>
            						<tr>
                						<th>Nombre:</th>
                						<td id="nombre"></td>
              						</tr>
              						<tr>
                						<th>Registro:</th>
                						<td id="registro"></td>
              						</tr>
              						<tr>
                						<th>Cédula:</th>
                						<td id="cedula"></td>
              						</tr>
              						<tr>
                						<th>Login:</th>
                						<td id="login"></td>
              						</tr>
              						<tr>
                						<th>Correo:</th>
                						<td id="correo"></td>
              						</tr>
            					</table>
			                </div>
							<form id="formularioDatos">
								<div class="row">
			        				<div class="col-md-6">
			            				<div class="form-group">
			        						<label for="fk_plantas">Planta (*)</label>
			            					<select class="form-control" name="fk_plantas" id="fk_plantas" required="required"></select>
			        					</div>
			        				</div>
			        				<div class="col-md-6">
			            				<div class="form-group">
			        						<label for="telefono">Teléfono (*)</label>
			            					<input type="number" class="form-control" name="telefono" id="telefono" required="required">
			        					</div>
			        				</div>
			        			</div>
              					<div class="row">
                        			<div class="col text-right">
                        				(*) Obligatorios
                        			</div>
                        		</div>
                				<div class="form-group text-center">
                    				<button type="submit" class="btn btn-default">
                    					Actualizar
                    				</button>
                				</div>
        					</form>
    					</div>
    				</div>
    			</div>
    		</div>
		</div>
	</section>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
	var id
	function init(info){
		id = info.data.usuario.id

		//LLenar plantas
        llenarSelectCallback('plantas', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_plantas', 'nombre', 2, 'Seleccione...', 'id', function(){
    		//Cargar información base
			enviarPeticion('usuarios', 'getUsuario', {info: {id: id}}, function(r){
				$('#dependencia').html(`<b>Gerencia:</b> ${r.data[0].gerencia}<br><b>Dependencia:</b> ${r.data[0].dependencia}<br><b>Unidad:</b> ${r.data[0].unidad}`)
				$('#rol').text(r.data[0].rol)
				$('#nombre').text(r.data[0].nombre)
				$('#registro').text(r.data[0].registro)
				$('#cedula').text(r.data[0].cedula)
				$('#login').text(r.data[0].login)
				$('#correo').text(r.data[0].correo)
				$('#fk_plantas').val(r.data[0].fk_plantas)
				$('#telefono').val(r.data[0].telefono)
			})
        })

		//Actualizar datos
        $('#formularioDatos').on('submit', function(e){
            e.preventDefault()
        	let datos = parsearFormulario($(this))            	
        	enviarPeticion('usuarios', 'update', {info: datos, id:id}, function(r){
            	toastr.success('Actualización correcta')
        	})
        })
	}
</script>
</body>
</html>