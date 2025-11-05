<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Datos maestros
                        <button type="button" class="btn btn-success" id="exportar" title="Exportar">
                            <i class="fas fa-file-download"></i>
                        </button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Datos maestros</li>
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
                                                <option value="serie">Serie</option>
                                                <option value="inventario">Inventario</option>
                                                <option value="registro">Registro</option>
                                                <option value="migracion">Migración</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input type="text" class="form-control" name="valor" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-secondary btn-block" id="sinResp">
                                            Sin responsable migrados
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-secondary btn-block" id="sinRespNew">
                                            Sin responsable nuevos
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
                                            <th>Inventario</th>
                                            <th>Serie</th>
                                            <th>Valor</th>
                                            <th>Dependencia</th>
                                            <th>Responsable</th>
                                            <th>Migración</th>
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

    <div class="modal fade" id="modalResponsable">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalResponsableTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioResponsable">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="receptor">Registro</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="responsable">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="botonBuscarResponsable">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="nombreResponsable" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="botonResponsable" form="formularioResponsable" disabled="disabled">Asignar sin solicitud</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalElementos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalElementosTitulo"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioElementos">
                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <input type="text" class="form-control" name="codigo" id="codigo" required="required">
                        </div>
                        <div class="form-group">
                            <label for="sn">sn</label>
                            <input type="number" class="form-control" name="sn" id="sn" required="required">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" required="required">
                        </div>
                        <div class="form-group">
                            <label for="inventario">Inventario</label>
                            <input type="text" class="form-control" name="inventario" id="inventario" required="required">
                        </div>
                        <div class="form-group">
                            <label for="serie">Serie</label>
                            <input type="text" class="form-control" name="serie" id="serie" required="required">
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="number" step="0.01" class="form-control" name="valor" id="valor" required="required">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" form="formularioElementos">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HISTÓRICO -->
    <div class="modal fade" id="modalHistorico">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Histórico de cambios - Elemento #<span id="historicoId"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Inventario</th>
                                    <th>Serie</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaHistorico"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
    var idResponsable = 1
    var idGestor = 0
    function init(info){
        //Exportar en formato excel
        $('#exportar').on('click', function(){
            url = `elementos/exportarTodoCSV/1`
            window.open(url, '_blank')
        })

        //Buscar activo
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            cargarRegistros(datos, 'crear', function(){
                console.log('Cargo...')
            })
        })

        $('#sinResp').on('click', function(){
            cargarRegistros({criterio: 'sinRespDM'}, 'crear', function(){
                $('#cardTableTitulo').text('Sin responsable migrados')
            })
        })
        $('#sinRespNew').on('click', function(){
            cargarRegistros({criterio: 'sinRespDMNew'}, 'crear', function(){
                $('#cardTableTitulo').text('Sin responsable nuevos')
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

        //Buscar responsable
        $('#botonBuscarResponsable').on('click', function(){
            enviarPeticion('usuarios', 'select', {info: {registro: $('#responsable').val()}}, function(r){
                if(r.data.length == 0){
                    toastr.error("El registro no existe en la base de datos")
                    idResponsable = 1
                    dependencia = 0
                    $('#botonResponsable').prop('disabled', true);
                    $('#nombreResponsable').val('')
                }else{
                    idResponsable = r.data[0].id
                    dependencia = r.data[0].fk_dependencias
                    $('#botonResponsable').prop('disabled', false);
                    $('#nombreResponsable').val(r.data[0].nombre)
                }
            })
        })

        //Fomulario para asignar responsable
        $('#formularioResponsable').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_dependencias = dependencia
            datos.responsable = idResponsable
            enviarPeticion('elementos', 'updateHistorico', {info: datos, id: id}, function(r){
                cargarRegistros({criterio: 'id', valor: id}, 'actualizar', function(){
                    $('#modalResponsable').modal('hide')
                })
            })
        })

        //Actualizar elemento
        $('#formularioElementos').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            enviarPeticion('elementos', 'updateHistorico', {info: datos, id: id}, function(r){
                toastr.success('Se actualizó correctamente')
                cargarRegistros({criterio: 'id', valor: id}, 'actualizar', function(){
                    $('#modalElementos').modal('hide')
                })
            })
        })
    }

    function cargarRegistros(datos, accion, callback){
        if(accion == 'crear'){
            $('#contenido').html('<tr><td colspan=13 class="text-center"><img src="dist/img/lg2.gif" style="height: 200px;"></td></tr>')
        }
        enviarPeticion('elementos', 'getElementos', datos, function(r){
            let fila = ''
            let dependencia = ''
            trabajador = ''
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
                    botonAsignar += `<td>
                                        <button type="button" class="btn btn-danger btn-sm" onClick="actualizarResponsable(${registro.id})" title="Actualizar sin solicitud">
                                            <i class="fas fa-street-view"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onClick="mostrarModalEditarElementos(${registro.id})" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onClick="reintegrar(${registro.id})" title="Reintegrar">
                                            <i class="fas fa-truck-loading"></i>
                                        </button>
                                    </td>`
                                    botonAsignar += `<td>
                                        <button type="button" class="btn btn-info btn-sm" onClick="verHistorico(${registro.id})" title="Ver histórico">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </td>`
                    botonAsignar += '</tr></table>'
                    if(registro.en_tramite == 1){
                        botonAsignar = `<table><tr><td>
                                            <button type="button" class="btn btn-info btn-sm" onClick="mostrarDetalle('elemento',${registro.id})" title="Ver solicitud">
                                                    <i class="fas fa-search"></i> En trámite
                                            </button>
                                        </td></tr></table>`
                    }
                    if(registro.estado == 'Reintegrado'){
                        botonAsignar = ''
                    }
                    fila += `<tr id=${registro.id}>
                                <td>${registro.id}</td>
                                <td>${tipos[registro.fk_tipos]}</td>
                                <td>${registro.codigo}</td>
                                <td class="text-center">${clasesIconos[registro.fk_clases]}</td>
                                <td>${registro.elemento}</td>
                                <td>${registro.inventario}</td>
                                <td>${registro.serie}</td>
                                <td class="text-right">$${currency(registro.valor,2)}</td>
                                <td class="text-xs">${dependencia}</td>
                                <td>${trabajador}</td>
                                <td>${registro.responsable2}</td>
                                <td>${registro.estado}</td>
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

    function mostrarModalEditarElementos(idElemento) {
        id = idElemento;
        $('#modalElementosTitulo').text('Editar elemento #' + id);

        enviarPeticion('elementos', 'select', {info: {id: id}}, function(r) {
            if (r.data && r.data.length > 0) {
                let e = r.data[0];
                $('#codigo').val(e.codigo);
                $('#sn').val(e.sn);
                $('#descripcion').val(e.descripcion);
                $('#inventario').val(e.inventario);
                $('#serie').val(e.serie);
                $('#valor').val(e.valor);
                $('#modalElementos').modal('show');
            } else {
                toastr.error('No se encontró el elemento');
            }
        });
    }

    function verHistorico(idElemento) {
    $('#historicoId').text(idElemento);
    $('#tablaHistorico').html('<tr><td colspan="8" class="text-center"><img src="dist/img/lg2.gif" height="40"></td></tr>');

    enviarPeticion('elementos', 'getHistorico', {id: idElemento}, function(r) {
        let filas = '';
        if (!r.data || r.data.length === 0) {
            filas = '<tr><td colspan="8" class="text-center text-muted">No hay cambios registrados</td></tr>';
        } else {
            r.data.forEach(h => {
                let d = h.datos_anteriores || {};
                let fecha = new Date(h.fecha_modificacion).toLocaleString('es-CO');
                filas += '<tr>' +
                    '<td>' + fecha + '</td>' +
                    '<td>' + (h.usuario_registro || '') + ' - ' + (h.usuario_nombre || '') + '</td>' +
                    '<td>' + (d.codigo || '') + '</td>' +
                    '<td>' + (d.descripcion || '') + '</td>' +
                    '<td>' + (d.inventario || '') + '</td>' +
                    '<td>' + (d.serie || '') + '</td>' +
                    '<td class="text-right">$' + currency(d.valor || 0, 2) + '</td>' +
                    '<td>' + (d.estado || '') + '</td>' +
                '</tr>';
            });
        }
        $('#tablaHistorico').html(filas);
        $('#modalHistorico').modal('show');
    });
}
</script>
</body>
</html>