<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Dependencias                        
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Dependencias</li>
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
                                            <th>ID</th>
                                            <th>Gerencia</th>
                                            <th>Dependencia</th>
                                            <th>Unidad</th>
                                            <th>Jefe</th>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th>Nombre de dependencia:</th>
                                <td id="dependenciaActual"></td>
                            </tr>
                            <tr>
                                <th>Jefe actual:</th>
                                <td id="jefeActual"></td>
                            </tr>
                        </table>
                    </div>
                    <form id="formularioDependencias">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="jefe">Registro del nuevo jefe</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="jefe">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="botonBuscarJefe">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombreJefe" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-secondary" form="formularioDependencias" disabled="disabled">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var boton = ''
    var idJefe = 1
    function init(info){
        //Cargar registro
        cargarRegistros({1:1}, 'crear', function(){
            $("#tabla").DataTable({
                "lengthMenu": [ 50, 100, 200 ],
                "pageLength": 100,
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

        //Buscar jefe
        $('#botonBuscarJefe').on('click', function(){
            enviarPeticion('usuarios', 'select', {info: {registro: $('#jefe').val()}}, function(r){
                if(r.data.length == 0){
                    toastr.error("El registro no existe en la base de datos")
                    idJefe = 1                    
                    $('#nombreJefe').val('')
                    $(':input[type="submit"]').prop('disabled', true);
                }else{                    
                    idJefe = r.data[0].id
                    $('#nombreJefe').val(r.data[0].nombre)
                    $(':input[type="submit"]').prop('disabled', false);
                }                
            })
        })

        $('#formularioDependencias').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.jefe = idJefe
            enviarPeticion('dependencias', 'update', {info: datos, id: id}, function(r){
                toastr.success('Se actualizó correctamente')
                cargarRegistros({id: id}, 'actualizar', function(){
                    $('#modalDependencias').modal('hide')
                })
            })
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('dependencias', 'getDependencias', datos, function(r){
            let fila = ''            
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${registro.gerencia}</td>
                            <td>${registro.dependencia}</td>
                            <td>${registro.unidad}</td>
                            <td>${registro.registro} - ${registro.jefe}</td>
                            <td>
                                <button class="btn btn-default btn-sm" onClick="mostrarModalEditar(${registro.id},'${registro.unidad}','${registro.jefe}')" title="Cambiar jefe">
                                    <i class="fas fa-user-edit"></i>
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

    function mostrarModalEditar(idDep, nombreDep, JefeDep){
        id = idDep
        $('#modalDependenciasTitulo').text('Modificar dependencia')
        $('#dependenciaActual').text(nombreDep)
        $('#jefeActual').text(JefeDep)
        $('#modalDependencias').modal('show')
    }
</script>
</body>
</html>