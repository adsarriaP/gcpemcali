<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Contratos
                        <button id="botonMostrarModalContratos" type="button" class="btn btn-primary">
                            Crear
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Contratos</li>
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
                                <table id="tabla" class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Contrato</th>                                            
                                            <th>Estado</th>
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

    <div class="modal fade" id="modalContratos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalContratosTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioContratos">
                        <div class="form-group">
                            <label for="contrato">Número</label>
                            <input type="text" class="form-control" name="contrato" id="contrato" required="required">
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado" required="required">
                                <option value="Activo">Activo</option>
                                <option value="Terminado">Terminado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-submit" id="botonGuardarContratos" form="formularioContratos">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarContratos" form="formularioContratos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGestores">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalGestoresTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioGestores">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="gestor">Registro</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="gestor">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-success" id="botonBuscarGestor">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="rol">Rol</label>
                                    <input type="text" class="form-control" value="Supervisor" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-block" id="botonAgregarGestor" disabled="disabled" title="Agregar gestor">
                                    Guardar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" id="nombreSupervisor" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped table-sm text-sm">
                        <thead>
                            <tr class="text-center">
                                <th>Nombre</th>
                                <th>Registro</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="contenidoGestores"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var boton = ''
    var idGestor = 0
    function init(info){
        //Cargar registros
        cargarRegistros({info: {1:1}, nodefault:1}, 'crear', function(){
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 50,
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
        })        

        $('#botonMostrarModalContratos').on('click', function(){
            $('#formularioContratos')[0].reset()
            $('#modalContratosTitulo').text('Nueva Contrato')
            $('#botonGuardarContratos').show()
            $('#botonActualizarContratos').hide()
            $('#modalContratos').modal('show')
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioContratos').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            //datos.supervisor = idSupervisor
            if(boton == 'botonGuardarContratos'){
                enviarPeticion('contratos', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    cargarRegistros({info: {id: r.insertId}}, 'crear', function(){
                        $('#modalContratos').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('contratos', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({info: {id: id}}, 'actualizar', function(){
                        $('#modalContratos').modal('hide')
                    })
                })
            }
        })

        //Logica de gestores
        //Buscar gestor
        $('#botonBuscarGestor').on('click', function(){
            enviarPeticion('usuarios', 'select', {info: {registro: $('#gestor').val()}}, function(r){
                if(r.data.length == 0){
                    toastr.error("El registro no existe en la base de datos")
                    idGestor = 1
                    $('#botonAgregarGestor').prop('disabled', true);
                    $('#nombreSupervisor').val('')
                }else{
                    idGestor = r.data[0].id
                    $('#botonAgregarGestor').prop('disabled', false);
                    $('#nombreSupervisor').val(r.data[0].nombre)
                }
            })
        })

        //Guardar gestores
        $('#formularioGestores').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_contratos = id
            datos.gestor = idGestor
            datos.rol = 'Supervisor'
            enviarPeticion('contratosGestores', 'insert', {info: datos}, function(r){
                cargarRegistrosGestores({criterio: 'id', valor: r.insertId}, 'crear', function(){
                    toastr.success('Se agrego correctamente')
                })
            })
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('contratos', 'select', datos, function(r){
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Terminado': 'danger'
            }
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.contrato}</td>                            
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]}">
                                    ${registro.estado}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default btn-sm" onClick="mostrarModalEditarContratos(${registro.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm" onClick="mostrarModalGestores(${registro.id},'${registro.contrato}')" title="Gestores del contrato">
                                    <i class="fas fa-people-arrows"></i>
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

    function mostrarModalEditarContratos(idContratos){
        id = idContratos
        llenarFormulario('formularioContratos', 'contratos', 'select', {info:{id: idContratos}}, function(r){
            $('#modalContratosTitulo').text('Editar contrato')
            $('#botonGuardarContratos').hide()
            $('#botonActualizarContratos').show()
            $('#modalContratos').modal('show')
        })
    }

    function mostrarModalGestores(idContratos, nombreContrato){
        id = idContratos
        $('#modalGestoresTitulo').text(`Gestores contrato # ${nombreContrato}`)
        $('#contenidoGestores').empty()
        cargarRegistrosGestores({criterio: 'contrato', valor: idContratos}, 'crear', function(){
            $('#modalGestores').modal('show')
        })
    }

    function cargarRegistrosGestores(datos, accion, callback){
        enviarPeticion('contratosGestores', 'getGestores', datos, function(r){
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
            let botonOpcion = ''
            r.data.map(registro => {
                botonOpcion = ''
                if(registro.estado == 'Activo'){
                    botonOpcion = ` <button type="button" class="btn btn-danger btn-xs" onClick="cambiarEstadoGestor(${registro.id},'Cancelado')" title="Borrar gestor">
                                        &nbsp;<i class="fas fa-times"></i>&nbsp;
                                    </button>`
                }else{
                    botonOpcion = ` <button type="button" class="btn btn-success btn-xs" onClick="cambiarEstadoGestor(${registro.id},'Activo')" title="Activar gestor">
                                        &nbsp;<i class="fas fa-check"></i></i>&nbsp;
                                    </button>`
                }
                fila += `<tr id=g_${registro.id}>
                            <td>${registro.nombre}</td>
                            <td>${registro.registro}</td>
                            <td>${registro.rol}</td>
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]}">
                                    ${registro.estado}
                                </span>
                            </td>
                            <td>
                                ${botonOpcion}
                            </td>
                        </tr>`
            })
            if(accion == 'crear'){
                $('#contenidoGestores').append(fila)
            }else{
                $('#g_'+r.data[0].id).replaceWith(fila)
            }
            callback()
        })
    }

    function cambiarEstadoGestor(idRegistroGestor, estado){
        enviarPeticion('contratosGestores', 'update', {info: {estado: estado}, id: idRegistroGestor}, function(r){
            cargarRegistrosGestores({criterio: 'id', valor: idRegistroGestor}, 'actualizar', function(){
                toastr.success('Se actualizó correctamente')
            })
        })
    }
</script>
</body>
</html>