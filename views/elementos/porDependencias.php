<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Dependencia
                        <span>
                            <small class="badge badge-ligth text-xs" id="tituloDependencia"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Elementos</li>
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
                            <form id="formulario">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="rol">Criterio</label>
                                            <select class="form-control" name="criterio" required="required">
                                                <option value="id">Código GCP</option>
                                                <option value="codigo">Código externo</option>
                                                <option value="registro">Registro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input type="text" class="form-control" name="valor" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-default btn-block" id="todos">
                                            Todos
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
                                </div>
                            </form>
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
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var idS = 0
    var dependencia = 0
    var idReceptor = 1
    function init(info){
        //Traer dependencia del jefe que esta logueado
        enviarPeticion('dependencias', 'select', {info: {jefe: info.data.usuario.id}}, function(r){
            if(r.data.length == 0){
                toastr.error("Debes estar configurado como jefe en alguna dependencia, contacta al administrador", "Sin dependencia", {
                    timeOut: 5000,
                    onHidden: function(){
                        window.location.href = 'elementos/misElementos/'
                    }
                })
            }else{
                $('#tituloDependencia').text(`${r.data[0].gerencia} -> ${r.data[0].dependencia} -> ${r.data[0].unidad}`)
                dependencia = r.data[0].id
                //Por defecto los elementos sin responsable
                cargarRegistros({criterio: 'sinResp', dependencia: dependencia}, 'crear', function(){
                    $('#cardTableTitulo').text('Sin responsable')
                })
            }
        })

        //Buscar activo
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))            
            cargarRegistros(datos, 'crear', function(){
                console.log('Cargo...')
            })
        })

        $('#todos').on('click', function(){
            cargarRegistros({criterio: 'todos', dependencia: dependencia}, 'crear', function(){                
                $('#cardTableTitulo').text('Todos')
            })
        })

        $('#sinResp').on('click', function(){
            cargarRegistros({criterio: 'sinResp', dependencia: dependencia}, 'crear', function(){
                $('#cardTableTitulo').text('Sin responsable')
            })
        })

        $('#asignados').on('click', function(){
            cargarRegistros({criterio: 'asignados', dependencia: dependencia}, 'crear', function(){
                $('#cardTableTitulo').text('Asignados')
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
                    if(dependencia != r.data[0].fk_dependencias){
                        toastr.error("El trabajador no esta en su dependencia")
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
                $('#btn_'+id).hide()
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

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('elementos', 'getElementos', datos, function(r){
            let fila = ''            
            let trabajador = ''
            let botonAsignar = ''
            if(r.data.length == 0){
                toastr.error("No se encontraros registros")
                $('#contenido').html('')
            }else{
                r.data.map(registro => {
                    trabajador = `${registro.registro} - ${registro.trabajador}`
                    botonAsignar = ''
                    if(registro.responsable == 1){
                        trabajador = ''
                        //Con esto controlo que si no es la misma dependencia no le muestre la opción
                        if(registro.idDep == dependencia){
                            botonAsignar = `<button type="button" id="btn_${registro.id}" class="btn btn-success btn-sm" onClick="crearSolicitud(${registro.id})" title="Crear solicitud">
                                                <i class="fas fa-user-plus"></i>
                                            </button>`    
                        }    
                    }
                    if(registro.en_tramite == 1){
                        botonAsignar = `<button type="button" class="btn btn-info btn-sm" onClick="mostrarDetalle('elemento',${registro.id})" title="Ver solicitud">
                                            <i class="fas fa-search"></i> En trámite
                                        </button>`
                    }
                    fila += `<tr id=${registro.id}>
                                <td>${registro.id}</td>
                                <td>${tipos[registro.fk_tipos]}</td>
                                <td>${registro.codigo}</td>
                                <td class="text-center">${clasesIconos[registro.fk_clases]}</td>
                                <td>${registro.elemento}</td>
                                <td>${registro.serie}</td>
                                <td class="text-right">$${currency(registro.valor,0)}</td>
                                <td class="text-xs">${registro.gerencia}</br>${registro.dependencia}</br>${registro.unidad}</td>
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

    function crearSolicitud(idElemento){
        id = idElemento
        $('#modalSolicitudTitulo').text(`Solicitud de asignación elemento ${idElemento}`)
        $('#modalSolicitud').modal('show')
    }
</script>
</body>
</html>