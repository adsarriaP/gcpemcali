<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Contratos
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
                    <div class="card card-outline card-primary">
                        <div class="card-body">                            
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="rol">Contrato</label>
                                        <select class="form-control" id="contrato"></select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-default btn-block" id="todos">
                                        Todos
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-default btn-block" id="sinDep">
                                        Sin dependencia
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-secondary btn-block" id="sinResp">
                                        Sin responsable
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-success btn-block" id="asignados">
                                        Asignados
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-success btn-block" id="botonMostrarModalgestores" title="Gestores de apoyo">
                                        <i class="fas fa-people-arrows"></i>
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-success btn-block" id="botonEstadisticas" title="Estadisticas">
                                        <i class="fas fa-chart-bar"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title" id="cardTableTitulo"></h3>&nbsp;
                            <span>
                                <small class="badge badge-secondary text-xs" id="conteo_total"></small>
                            </span>
                        </div>
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
                                            <th>Dependencia</th>
                                            <th>Responsable</th>
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

    <div class="modal fade" id="modalDependencias">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalDependenciasTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioDependencias">
                        <div class="form-group">
                            <label for="gerencia">Gerencia</label>
                            <select class="form-control" id="gerencia" required="required"></select>
                        </div>
                        <div class="form-group">
                            <label for="dependencia">Dependencia</label>
                            <select class="form-control" id="dependencia" required="required"></select>
                        </div>
                        <div class="form-group">
                            <label for="unidad">Unidad</label>
                            <select class="form-control" name="fk_dependencias" id="unidad" required="required"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-secondary" form="formularioDependencias">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="row">
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="botonSolicitud" form="formularioSolicitud" disabled="disabled">Crear</button>
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
                                    <input type="text" class="form-control" value="Apoyo" readonly>
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

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var dependencia = 0
    var idReceptor = 1
    var idGestor = 0
    var permisos = {}
    function init(info){
        //Cargar contratos
        enviarPeticion('contratosGestores', 'getGestores', {criterio: 'gestor', valor: info.data.usuario.id, estado: 'Activo'}, function(r){
            if(r.data.length == 0){
                toastr.error("No tienes contratos asociados", "Sin contrato", {
                    timeOut: 5000,
                    onHidden: function(){
                        window.location.href = 'elementos/misElementos/'
                    }
                })
            }else{
                r.data.map(registro => {
                    permisos[registro.idContrato] = registro.rol
                    $('#contrato').append(`<option value="${registro.idContrato}">${registro.contrato}</option>`)
                })
            }
        })

        $('#todos').on('click', function(){
            cargarRegistros({criterio: 'contratoTodos', contrato: $('#contrato').val()}, 'crear', function(){
                $('#cardTableTitulo').text('Todos')
            })
        })

        $('#sinDep').on('click', function(){
            cargarRegistros({criterio: 'contratoSinDep', contrato: $('#contrato').val()}, 'crear', function(){
                $('#cardTableTitulo').text('Sin dependencia')
            })
        })

        $('#sinResp').on('click', function(){
            cargarRegistros({criterio: 'contratoSinResp', contrato: $('#contrato').val()}, 'crear', function(){
                $('#cardTableTitulo').text('Sin responsable')
            })
        })

        $('#asignados').on('click', function(){
            cargarRegistros({criterio: 'contratoAsignados', contrato: $('#contrato').val()}, 'crear', function(){
                $('#cardTableTitulo').text('Asignados')
            })
        })        

        //Llenar gerencias
        llenarSelect('dependencias', 'getGerencias', {1:1}, 'gerencia', 'gerencia', 1, 'Seleccione...', 'gerencia')
        $('#gerencia').on('change', function(){
            llenarSelect('dependencias', 'getDep', {gerencia:$(this).val()}, 'dependencia', 'dependencia', 1, 'Seleccione...', 'dependencia')            
        })
        $('#dependencia').on('change', function(){
            llenarSelect('dependencias', 'getUnidades', {gerencia:$('#gerencia').val(), dep:$(this).val()}, 'unidad', 'unidad', 1)
        })
        //Actualizar dependencia
        $('#formularioDependencias').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            enviarPeticion('elementos', 'update', {info: datos, id: id}, function(r){
                toastr.success('Se actualizó correctamente')
                cargarRegistros({criterio: 'id', valor: id}, 'actualizar', function(){
                    $('#modalDependencias').modal('hide')
                })
            })
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
                    if(dependencia != 1 && dependencia != r.data[0].fk_dependencias){
                        toastr.error("El trabajador no esta en esa dependencia")
                        idReceptor = 1
                        $('#botonSolicitud').prop('disabled', true);
                        $('#nombreReceptor').val(r.data[0].nombre)
                    }else{
                        idReceptor = r.data[0].id
                        $('#botonSolicitud').prop('disabled', false);
                        $('#nombreReceptor').val(r.data[0].nombre)    
                    }
                }
            })
        })

        //Fomulario para ingresar la solicitud
        $('#formularioSolicitud').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_tramites = 1
            datos.fk_elementos = id
            datos.receptor = idReceptor
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

        //Logica de gestores
        $('#botonMostrarModalgestores').on('click', function(){
            if(permisos[$('#contrato').val()] == 'Supervisor'){
                $('#modalGestoresTitulo').text('Gestores contrato # ' + $('#contrato').find('option:selected').text())
                $('#contenidoGestores').empty()
                cargarRegistrosGestores({criterio: 'contrato', valor: $('#contrato').val()}, 'crear', function(){
                    $('#modalGestores').modal('show')
                })
            }else{
                toastr.error("Para ese contrato no tienes permisos de supervisor", "Sin permisos")
            }
        })

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
            datos.fk_contratos = $('#contrato').val()            
            datos.gestor = idGestor
            datos.rol = 'Apoyo'
            enviarPeticion('contratosGestores', 'guardarGestor', {info: datos}, function(r){
                cargarRegistrosGestores({criterio: 'id', valor: r.insertId}, 'crear', function(){
                    toastr.success('Se agrego correctamente')
                })
            })
        })

        //Ir a estadisticas
        $('#botonEstadisticas').on('click', function(){
            window.location.href = 'reportes/contratos/'+$('#contrato').val()
        })
    }

    function cargarRegistros(datos, accion, callback){
        if(accion == 'crear'){
            $('#contenido').html('<tr><td colspan=10 class="text-center"><img src="dist/img/lg2.gif" style="height: 200px;"></td></tr>')
        }
        enviarPeticion('elementos', 'getElementos', datos, function(r){
            let fila = ''
            let dependencia = ''
            let trabajador = ''
            let botonAsignar = ''
            if(r.data.length == 0){
                toastr.error("No se encontraros registros")
                $('#contenido').html('')
            }else{
                r.data.map(registro => {
                    dependencia = ''
                    if(registro.idDep != 1){
                        dependencia = `${registro.gerencia}</br>${registro.dependencia}</br>${registro.unidad}`
                    }

                    trabajador = `${registro.registro} - ${registro.trabajador}`
                    if(registro.responsable == 1){
                        trabajador = ''
                    }
                    
                    botonAsignar = '<table><tr>'
                    if(registro.idDep == 1){
                        botonAsignar += `<td>
                                            <button type="button" class="btn btn-success btn-sm" onClick="asignarDep(${registro.id})" title="Asignar dependencia">
                                                <i class="fas fa-building"></i>
                                            </button>
                                        </td>`
                    }else{
                        if(registro.responsable == 1){
                            botonAsignar += `<td>
                                                <button type="button" class="btn btn-success btn-sm" onClick="asignarDep(${registro.id})" title="Asignar dependencia">
                                                    <i class="fas fa-building"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onClick="crearSolicitud(${registro.id},${registro.idDep})" title="Crear solicitud">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                            </td>`
                        }
                    }
                    botonAsignar += '</tr></table>'
                    if(registro.en_tramite == 1){
                        botonAsignar = `<table><tr><td>                                                
                                            <button type="button" class="btn btn-info btn-sm" onClick="mostrarDetalle('elemento',${registro.id})" title="Ver solicitud">
                                                    <i class="fas fa-search"></i> En trámite
                                            </button>
                                        </td></tr></table>`
                    }
                    fila += `<tr id=${registro.id}>
                                <td>${registro.id}</td>
                                <td>${tipos[registro.fk_tipos]}</td>
                                <td>${registro.codigo}</td>
                                <td class="text-center">${clasesIconos[registro.fk_clases]}</td>
                                <td>${registro.elemento}</td>
                                <td>${registro.serie}</td>
                                <td class="text-right">$${currency(registro.valor,0)}</td>
                                <td class="text-xs">${dependencia}</td>
                                <td>${trabajador}</td>
                                <td>
                                    ${botonAsignar}
                                </td>
                            </tr>`
                })
                if(accion == 'crear'){
                    $('#contenido').html(fila)
                }else{
                    $('#'+r.data[0].id).replaceWith(fila)
                }
            }
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function crearSolicitud(idElemento, dep){
        id = idElemento
        dependencia = dep
        $('#modalSolicitudTitulo').text(`Solicitud de asignación elemento ${idElemento}`)
        $('#botonSolicitud').prop('disabled', true);
        $('#nombreReceptor').val('')
        $('#modalSolicitud').modal('show')
    }

    function asignarDep(idElemento){
        id = idElemento
        $('#modalDependenciasTitulo').text('Asignar dependencia')
        $('#modalDependencias').modal('show')
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
                if(registro.rol == 'Apoyo'){
                    if(registro.estado == 'Activo'){
                        botonOpcion = ` <button type="button" class="btn btn-danger btn-xs" onClick="cambiarEstadoGestor(${registro.id},'Cancelado')" title="Borrar gestor">
                                        &nbsp;<i class="fas fa-times"></i>&nbsp;
                                    </button>`
                    }else{
                        botonOpcion = ` <button type="button" class="btn btn-success btn-xs" onClick="cambiarEstadoGestor(${registro.id},'Activo')" title="Activar gestor">
                                        &nbsp;<i class="fas fa-check"></i></i>&nbsp;
                                    </button>`
                    }    
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