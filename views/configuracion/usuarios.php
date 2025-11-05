<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Usuarios
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <form id="formulario">
                                <div class="form-group">
                                    <label>Registro</label>
                                    <input type="text" name="valor" class="form-control" required="required">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8" id="contenido"></div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalUsuarios">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalUsuariosTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioUsuarios">
                        <div class="form-group">
                            <label for="rol">Rol</label>
                            <select class="form-control" name="rol" id="rol" required="required">
                                <option value="Administrador">Administrador</option>
                                <option value="Jefe">Jefe</option>
                                <option value="Inspeccion">Inspección</option>
                                <option value="Validador">Validador</option>
                                <option value="Almacen">Almacén</option>
                                <option value="Activos">Activos</option>
                                <option value="GestorContrato">Gestor contrato</option>
                                <option value="Trabajador">Trabajador</option>
                                <option value="MesaAyuda">Mesa de ayuda</option>
                                <option value="FacilitadorArea">Facilitador área</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="clase">Clase</label>
                            <select class="form-control" name="clase" id="clase" required="required"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" form="formularioUsuarios">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

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
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    function init(info){
        //Llenar clase
        llenarSelect('clases', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'clase', 'nombre', 2, 'Ninguna')

        //Buscar usuario
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            $('#contenido').empty()
            let datos = parsearFormulario($(this))
            cargarRegistros({info: {registro: datos.valor}}, function(){
                console.log('Se cargo...')
            })
        })

        $('#formularioUsuarios').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))            
            enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
                toastr.success('Se actualizó correctamente')
                cargarRegistros({info: {id: id}}, function(){
                    $('#modalUsuarios').modal('hide')
                })
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
            enviarPeticion('usuarios', 'update', {info: datos, id: id}, function(r){
                toastr.success('Se actualizó correctamente')
                cargarRegistros({info: {id: id}}, function(){
                    $('#modalDependencias').modal('hide')
                })
            })
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('usuarios', 'getUsuario', datos, function(r){
            let contenido = ''
            r.data.map(registro => {
                contenido = `<div class="card card-outline card-primary">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                                <th>Dependencia</th>
                                                <td class="p-0">
                                                    <table class="table table-sm table-bordered m-0">
                                                        <tr>
                                                            <th>Gerencia</th>
                                                            <td>${registro.gerencia}</td>
                                                            <td rowspan=3 class="text-center">
                                                                <button class="btn btn-primary btn-sm" onClick="mostrarModalDependencia(${registro.id})" title="Cambiar dependencia">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Dependencia</th>
                                                            <td>${registro.dependencia}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Unidad</th>
                                                            <td>${registro.unidad}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nombre</th>
                                                <td>${registro.nombre}</td>
                                            </tr>
                                            <tr>
                                                <th>Rol</th>
                                                <td>${registro.rol}</td>
                                            </tr>
                                            <tr>
                                                <th>Clase</th>
                                                <td>${clases[registro.clase]}</td>
                                            </tr>
                                            <tr>
                                                <th>login</th>
                                                <td>${registro.login}</td>
                                            </tr>
                                            <tr>
                                                <th>Registro</th>
                                                <td>${registro.registro}</td>
                                            </tr>
                                            <tr>
                                                <th>Cédula</th>
                                                <td>${registro.cedula}</td>
                                            </tr>
                                            <tr>
                                                <th>Correo</th>
                                                <td>${registro.correo}</td>
                                            </tr>
                                            <tr>
                                                <th>Telefono</th>
                                                <td>${registro.telefono}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-3">
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-primary btn-block" onClick="mostrarModalEditarUsuarios(${registro.id})" title="Editar">
                                                Editar
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <a class="btn btn-success btn-block" href="elementos/generar/${registro.id}" target="_blank">
                                                Cuenta personal
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>`
            })            
            $('#contenido').html(contenido)
            callback()
        })
    }

    function mostrarModalEditarUsuarios(idUsuario){
        id = idUsuario
        llenarFormulario('formularioUsuarios', 'usuarios', 'select', {info:{id: idUsuario}}, function(r){
            $('#modalUsuariosTitulo').text('Editar usuario')
            $('#modalUsuarios').modal('show')
        })
    }

    function mostrarModalDependencia(idUsuario){
        id = idUsuario
        $('#modalDependenciasTitulo').text('Cambiar dependencia')
        $('#modalDependencias').modal('show')
    }
</script>
</body>
</html>