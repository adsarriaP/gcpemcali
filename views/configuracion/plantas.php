<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Plantas
                        <button id="botonMostrarModalPlantas" type="button" class="btn btn-primary">
                            Crear
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="solicitudes/missolicitudes/">Inicio</a></li>
                        <li class="breadcrumb-item active">Plantas</li>
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
                                <table id="tabla" class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Nombre</th>                                            
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

    <div class="modal fade" id="modalPlantas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalPlantasTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioPlantas">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required="required">
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado" required="required">
                                <option value="Activo">Activo</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-submit" id="botonGuardarPlantas" form="formularioPlantas">Guardar</button>
                    <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarPlantas" form="formularioPlantas">Actualizar</button>
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
        //Cargar registro
        cargarRegistros({info:{1:1}, nodefault:1}, 'crear', function(){
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

        $('#botonMostrarModalPlantas').on('click', function(){
            $('#formularioPlantas')[0].reset()
            $('#modalPlantasTitulo').text('Nueva Plantas')
            $('#botonGuardarPlantas').show()
            $('#botonActualizarPlantas').hide()
            $('#modalPlantas').modal('show')
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioPlantas').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            if(boton == 'botonGuardarPlantas'){
                enviarPeticion('plantas', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    cargarRegistros({info: {id: r.insertId}}, 'crear', function(){
                        $('#modalPlantas').modal('hide')
                    })
                })
            }else{                
                enviarPeticion('plantas', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                    cargarRegistros({info: {id: id}}, 'actualizar', function(){
                        $('#modalPlantas').modal('hide')
                    })
                })
            }
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('plantas', 'select', datos, function(r){
            let fila = ''
            let colores = {
                'Activo': 'success',
                'Cancelado': 'danger'
            }
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.nombre}</td>
                            <td class="text-center">
                                <span class="badge badge-${colores[registro.estado]}">
                                    ${registro.estado}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-default btn-sm" onClick="mostrarModalEditarPlantas(${registro.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
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

    function mostrarModalEditarPlantas(idPlantas){
        id = idPlantas
        llenarFormulario('formularioPlantas', 'plantas', 'select', {info:{id: idPlantas}}, function(r){
            $('#modalPlantasTitulo').text('Editar Plantas')
            $('#botonGuardarPlantas').hide()
            $('#botonActualizarPlantas').show()
            $('#modalPlantas').modal('show')
        })
    }
</script>
</body>
</html>