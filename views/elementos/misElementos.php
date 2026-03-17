<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Mis Elementos                        
                        <a class="btn btn-primary" id="enlace" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_activos"></small>
                            <small class="badge badge-warning text-xs" id="conteo_controlados"></small>
                            <small class="badge badge-secondary text-xs" id="conteo_ao"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Mis Elementos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Código GCP</th>
                                            <th>Tipo</th>
                                            <th>Código externo</th>
                                            <th colspan=2>Descripción</th>
                                            <th>Serie</th>
                                            <th>Valor</th>
                                            <th>Ubicación</th>
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

    <div class="modal fade" id="modalSolicitud">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalSolicitudTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioSolicitud">
                        <div class="form-group">
                            <label for="fk_tramites">Trámite</label>
                            <select class="form-control" name="fk_tramites" id="fk_tramites" required="required">
                                <option value=3>Traspaso entre funcionarios</option>
                                <option value=4>Reintegrar a almacén</option>
                            </select>
                        </div>
                        <div class="row" id="panelReceptor">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="receptor">Registro receptor</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="receptor">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="botonBuscarReceptor">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombreReceptor" readonly>
                            </div>
                        </div>
                        <div class="form-group panelPlanta" style="display: none;">
                            <label for="fk_plantas">Planta (*)</label>
                            <select class="form-control" id="fk_plantas" required="required"></select>
                        </div>
                        <div class="form-group panelPlanta" style="display: none;">
                            <label for="telefono">Teléfono (*)</label>
                            <input type="number" class="form-control" id="telefono" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="botonSolicitud" form="formularioSolicitud" disabled="disabled">Crear</button>
                </div>
            </div>
        </div>
    </div>

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

    <div class="modal fade" id="modalUso">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalUsoTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioUso">
                        <div class="form-group">
                            <label for="observaciones">Donde está </label><small>(Max 255 caracteres)</small>
                            <textarea class="form-control" rows="3" name="uso" maxlength="255"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" form="formularioUso">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var idS = 0
    var idReceptor = 1
    var idUsuario = 0
    function init(info){
        idUsuario = info.data.usuario.id
        //Cambiar href
        $("#enlace").attr("href", `elementos/generar/${info.data.usuario.id}`);

        //Cargar registro
        cargarRegistros({criterio: 'responsable'}, 'crear', function(){
            
        })

        //LLenar info de ubicación
        llenarSelectCallback('plantas', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_plantas', 'nombre', 2, 'Seleccione...', 'id', function(){
            //Cargar información base
            enviarPeticion('usuarios', 'getUsuario', {info: {id: info.data.usuario.id}}, function(r){
                $('#telefono').val(r.data[0].telefono)
                $('#fk_plantas').val(r.data[0].fk_plantas)
            })
        })

        //Mostrar receptor
        $('#fk_tramites').on('change', function(){
            if($(this).val() == 3){//Traspaso
                $('#panelReceptor').show()
                $('#receptor').prop('required', true)
                $('.panelPlanta').hide()
                $('#botonSolicitud').prop('disabled', true);
            }else{//Reintegro
                $('#panelReceptor').hide()
                $('#receptor').prop('required', false)
                $('.panelPlanta').show()
                $('#botonSolicitud').prop('disabled', false);
                idReceptor = 1
            }
        })

        //Buscar receptor
        $('#botonBuscarReceptor').on('click', function(){
            enviarPeticion('usuarios', 'select', {info: {registro: $('#receptor').val()}}, function(r){
                if(r.data.length == 0){
                    toastr.error("El registro no existe en la base de datos")
                    idReceptor = 1
                    $('#botonSolicitud').prop('disabled', true);
                    $('#nombreReceptor').val('')
                }else{
                    idReceptor = r.data[0].id
                    $('#botonSolicitud').prop('disabled', false);
                    $('#nombreReceptor').val(r.data[0].nombre)
                }
            })
        })

        //Fomulario para ingresar la solicitud
        $('#formularioSolicitud').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_elementos = id
            datos.receptor = idReceptor            
            if(datos.fk_tramites == 4 && $('#fk_plantas').val() == 1){
                //Esto error sale cuando escoge un reintegro y no escoge la planta
                toastr.error("Deber seleccionar una planta válida")
            }else{
                //Crear solicitud
                enviarPeticion('usuarios', 'update', {info: {fk_plantas: $('#fk_plantas').val(), telefono: $('#telefono').val()}, id:idUsuario}, function(r){
                    enviarPeticion('solicitudes', 'crear', datos, function(r){
                        $('#modalSolicitud').modal('hide')
                        Swal.fire({
                            icon: 'success',
                            title: 'Confimación',
                            text: `Se creo correctamente la solicitud número #${r.insertId}`
                        }).then((result) => {
                            if(result.value){                        
                                window.location.href = 'elementos/misSolicitudes/'
                            }
                        })
                    })
                })
            }
        })

        //Fomulario uso
        $('#formularioUso').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            enviarPeticion('elementos', 'update', {info: datos, id: id}, function(r){
                $('#modalUso').modal('hide')
                $(`#uso_${id}`).text(datos.uso)
            })
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('elementos', 'getElementos', datos, function(r){
            let fila = ''
            let color = 'default'
            let botonTramitar = ''
            let botonTienda = ''
            let totales = {
                1: 0,
                2: 0,
                3: 0
            }
            let mensaje = ''
            r.data.map(registro => {
                color = 'default'
                mensaje = ''
                if(registro.devolver == 1){
                    color = 'secondary'
                    mensaje = '<span class="badge bg-danger">Sin soporte - Reintegrar</span>'
                }
                botonTramitar = `  <table>
                                        <tr>
                                            <td>                                                
                                                <button type="button" class="btn btn-info btn-sm" onClick="mostrarDetalle('elemento',${registro.id})" title="Ver solicitud">
                                                    <i class="fas fa-search"></i> En trámite
                                                </button>
                                            </td>
                                        </tr>
                                    </table>`
                botonTienda = ''
                if(registro.en_tramite == 0){
                    botonTramitar = `<table>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-default btn-sm" onClick="crearSolicitud(${registro.id})" title="Crear solicitud">
                                                    <i class="fas fa-project-diagram"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-default btn-sm" onClick="cambiarUso(${registro.id})" title="Cambiar donde está">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>`
                    botonTienda = ` <a class="btn btn-default btn-sm" href="elementos/publicaciones/${registro.id}" title="Gestionar publicación">
                                        <i class="fas fa-store"></i>
                                    </a>`
                }
                fila += `<tr id=${registro.id} class="table-${color}">
                            <td>${registro.id}</td>
                            <td>${tipos[registro.fk_tipos]}</td>
                            <td>${registro.codigo}</td>
                            <td class="text-center">${clasesIconos[registro.fk_clases]}</td>
                            <td>${registro.elemento}</td>
                            <td>${registro.serie}</td>                            
                            <td class="text-right">$${currency(registro.valor,2)}</td>
                            <td id="uso_${registro.id}">${registro.uso}</td>
                            <td>
                                ${mensaje}
                                ${botonTramitar}
                            </td>
                        </tr>`
                totales[registro.fk_tipos]++
            })
            if(accion == 'crear'){
                $('#contenido').html(fila)
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            callback()
            $('#conteo_activos').text(`Activos: ${totales[1]}`)
            $('#conteo_controlados').text(`Controlados: ${totales[2]}`)
            $('#conteo_ao').text(`AO: ${totales[3]}`)
        })
    }

    function crearSolicitud(idActivo){
        id = idActivo
        $('#modalSolicitudTitulo').text(`Crear solicitud elemento código GCP ${idActivo}`)
        $('#modalSolicitud').modal('show')
    }

    function cambiarUso(idActivo){
        id = idActivo
        $('#modalUsoTitulo').text(`Cambiar donde está el elemento con código #${idActivo}`)
        $('#modalUso').modal('show')
    }
</script>
</body>
</html>