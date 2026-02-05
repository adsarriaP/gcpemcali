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
                                    <div class="col-md-8">
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
                                </div>
                                <!-- Botones de filtro rápido -->
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                        <button type="button" class="btn btn-secondary btn-block btn-sm" id="sinResp" title="Mostrar elementos sin responsable migrados">
                                            <i class="fas fa-user-slash"></i> Sin resp. migrados
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                        <button type="button" class="btn btn-secondary btn-block btn-sm" id="sinRespNew" title="Mostrar elementos sin responsable nuevos">
                                            <i class="fas fa-user-plus"></i> Sin resp. nuevos
                                        </button>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
                                        <button type="button" class="btn btn-warning btn-block btn-sm" id="reintegrados" title="Mostrar elementos reintegrados">
                                            <i class="fas fa-undo"></i> Reintegrados
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
                                <table id="tabla" class="table table-bordered table-sm text-sm" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 8%;">Código GCP</th>
                                            <th style="width: 8%;">Tipo</th>
                                            <th style="width: 8%;">Código externo</th>
                                            <th style="width: 4%;">Clase</th>
                                            <th style="width: 15%;">Descripción</th>
                                            <th style="width: 8%;">Inventario</th>
                                            <th style="width: 8%;">Serie</th>
                                            <th style="width: 10%;">Valor</th>
                                            <th style="width: 10%;">Dependencia</th>
                                            <th style="width: 10%;">Responsable</th>
                                            <th style="width: 8%;">Migración</th>
                                            <th style="width: 8%;">Estado</th>
                                            <th style="width: 12%;">Opciones</th>
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
                            <div class="col-sm-12 mt-3">
                                <div class="form-group">
                                    <label for="motivo">Motivo</label>
                                    <textarea
                                        class="form-control"
                                        name="motivo"
                                        id="motivo"
                                        rows="3"
                                        placeholder="Escribe el motivo de esta asignación..."></textarea>
                                </div>
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

    <div class="modal fade" id="modalHistorico">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHistoricoTitulo">Histórico del Elemento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Registro</th>
                                    <th>Información</th>
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

    <div class="modal fade" id="modalReintegro">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalReintegroTitulo">Reintegrar Elemento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioReintegro">
                        <div class="form-group">
                            <label for="motivoReintegro">Motivo del Reintegro <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control" 
                                id="motivoReintegro" 
                                name="motivo" 
                                rows="4" 
                                placeholder="Escribe el motivo por el cual reintegras este elemento..." 
                                required>
                            </textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="botonConfirmarReintegro" form="formularioReintegro">Reintegrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalActivarDesdeReintegro">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalActivarTitulo">Activar Elemento desde Reintegro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioActivarDesdeReintegro">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="registroActivar">Registro <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="registroActivar" required>
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="botonBuscarActivar">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Nombre del Responsable</label>
                                <input type="text" class="form-control" id="nombreActivar" readonly>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="motivoActivar">Motivo de Activación (Opcional)</label>
                            <textarea 
                                class="form-control" 
                                id="motivoActivar" 
                                name="motivo" 
                                rows="3" 
                                placeholder="Escribe el motivo de la activación...">
                            </textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="botonConfirmarActivar" form="formularioActivarDesdeReintegro" disabled>Activar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('views/footer.php');?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var dependencia = 0
    var idReceptor = 1
    var idResponsable = 1
    var nombreResponsable = ''
    var idGestor = 0
    var ultimaBusqueda = {} // Variable para guardar la última búsqueda
    
    function inicializarDataTable(){
        // Destruir DataTable si ya existe
        if($.fn.DataTable.isDataTable('#tabla')){
            $('#tabla').DataTable().destroy()
        }
        
        // Inicializar DataTable sin reinicializar filas
        $('#tabla').DataTable({
            searching: true,
            paging: true,
            ordering: true,
            info: true,
            responsive: false,
            scrollX: false,
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copiar',
                    className: 'btn btn-sm btn-info'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-danger'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-sm btn-secondary'
                }
            ],
            columnDefs: [
                { targets: [0, 1, 2, 3], className: 'text-center' },
                { targets: [7], className: 'text-right' },
                { targets: [12], className: 'text-center', orderable: false, searchable: false }
            ]
        })
    }
    
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
            ultimaBusqueda = datos // Guardar la búsqueda actual
            cargarRegistros(datos, 'crear', function(){
                console.log('Cargo...')
            })
        })

        $('#sinResp').on('click', function(){
            $('#sinResp, #sinRespNew, #reintegrados').prop('disabled', true)
            ultimaBusqueda = {criterio: 'sinRespDM'} // Guardar búsqueda
            cargarRegistros(ultimaBusqueda, 'crear', function(){
                $('#cardTableTitulo').text('Sin responsable migrados')
            })
        })
        $('#sinRespNew').on('click', function(){
            $('#sinResp, #sinRespNew, #reintegrados').prop('disabled', true)
            ultimaBusqueda = {criterio: 'sinRespDMNew'} // Guardar búsqueda
            cargarRegistros(ultimaBusqueda, 'crear', function(){
                $('#cardTableTitulo').text('Sin responsable nuevos')
            })
        })
        $('#reintegrados').on('click', function(){
            $('#sinResp, #sinRespNew, #reintegrados').prop('disabled', true)
            ultimaBusqueda = {criterio: 'reintegrados'} // Guardar búsqueda
            cargarRegistros(ultimaBusqueda, 'crear', function(){
                $('#cardTableTitulo').text('Elementos Reintegrados')
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
            datos.accion = 'Asignación de dependencia'
            enviarPeticion('elementos', 'updateHistorico', {info: datos, id: id}, function(r){
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
                    nombreResponsable = ''
                    dependencia = 0
                    $('#botonResponsable').prop('disabled', true);
                    $('#nombreResponsable').val('')
                }else{
                    idResponsable = r.data[0].id
                    nombreResponsable = r.data[0].nombre
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
            datos.nombre_responsable = nombreResponsable
            datos.accion = 'Asignación sin solicitud'
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
            datos.accion = 'Edición de elemento'
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
        
        // Destruir DataTable si existe antes de cargar nuevos datos
        if($.fn.DataTable.isDataTable('#tabla')){
            $('#tabla').DataTable().destroy()
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
                        dependencia = `<div class="font-weight-bold text-primary">${registro.gerencia}</div><div class="text-secondary small">${registro.dependencia}</div><div class="text-secondary small">${registro.unidad}</div>`
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
                                        <button type="button" class="btn btn-info btn-sm" onClick="verHistorico(${registro.id})" title="Ver histórico">
                                            <i class="fas fa-history"></i>
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
                    botonAsignar += '</tr></table>'
                    if(registro.en_tramite == 1){
                        botonAsignar = `<table><tr><td>                                                
                                            <button type="button" class="btn btn-info btn-sm" onClick="mostrarDetalle('elemento',${registro.id})" title="Ver solicitud">
                                                    <i class="fas fa-search"></i> En trámite
                                            </button>
                                        </td></tr></table>`
                    }
                    if(registro.estado == 'Reintegrado'){
                        botonAsignar = `<table><tr><td>
                                            <button type="button" class="btn btn-success btn-sm" onClick="activarDesdeReintegro(${registro.id})" title="Activar desde Reintegro">
                                                <i class="fas fa-check-circle"></i> Activar
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" onClick="verHistorico(${registro.id})" title="Ver histórico">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td></tr></table>`
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
                }else if(accion == 'actualizar'){
                    // Destruir DataTable antes de actualizar
                    if($.fn.DataTable.isDataTable('#tabla')){
                        $('#tabla').DataTable().destroy()
                    }
                    // Reemplazar todo el tbody con los nuevos datos
                    $('#contenido').html(fila)
                }
            }
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
            
            // Inicializar DataTable después de cargar los datos
            setTimeout(function(){
                if($.fn.DataTable.isDataTable('#tabla')){
                    $('#tabla').DataTable().destroy()
                }
                
                // Reinicializar DataTable con los nuevos datos
                $('#tabla').DataTable({
                    searching: true,
                    paging: true,
                    ordering: true,
                    info: true,
                    responsive: false,
                    scrollX: false,
                    pageLength: 25,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Copiar',
                            className: 'btn btn-sm btn-info'
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-sm btn-success'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-sm btn-danger'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Imprimir',
                            className: 'btn btn-sm btn-secondary'
                        }
                    ],
                    columnDefs: [
                        { targets: [0, 1, 2, 3], className: 'text-center' },
                        { targets: [7], className: 'text-right' },
                        { targets: [12], className: 'text-center', orderable: false, searchable: false }
                    ]
                })
                
                // Habilitar botones después de que el DataTable esté listo
                $('#sinResp, #sinRespNew, #reintegrados').prop('disabled', false)
            }, 100)
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

    function actualizarResponsable(idElemento){
        id = idElemento
        idResponsable = 1
        nombreResponsable = ''
        $('#modalResponsableTitulo').text(`Asignación responsable ${idElemento}`)
        $('#botonResponsable').prop('disabled', true)
        $('#nombreResponsable').val('')
        $('#responsable').val('')
        $('#motivo').val('')
        $('#modalResponsable').modal('show')
    }

    function mostrarModalEditarElementos(idElemento){
        id = idElemento
        llenarFormulario('formularioElementos', 'elementos', 'select', {info:{id: idElemento}}, function(r){
            $('#modalElementosTitulo').text('Editar elemento')
            $('#modalElementos').modal('show')
        })
    }

    function verHistorico(idElemento){
        $('#modalHistoricoTitulo').text(`Histórico del elemento #${idElemento}`)
        $('#tablaHistorico').html('<tr><td colspan="5" class="text-center"><img src="dist/img/lg2.gif" style="height: 100px;"></td></tr>')
        $('#modalHistorico').modal('show')
        
        enviarPeticion('elementosHistorico', 'getHistorico', {fk_elementos: idElemento}, function(r){
            let filas = ''
            if(r.data.length == 0){
                filas = '<tr><td colspan="5" class="text-center">No hay registros históricos</td></tr>'
            }else{
                r.data.map((registro, index) => {
                    // Parsear el JSON de información
                    let info = ''
                    try {
                        let infoObj = JSON.parse(registro.informacion)
                        info = '<ul style="margin:0; padding-left:20px;">'
                        
                        // Formatear cada campo del JSON
                        for(let campo in infoObj){
                            let valor = infoObj[campo]
                            let nombreCampo = campo
                            
                            // Traducir nombres de campos a español
                            if(campo === 'accion') nombreCampo = '🔹 Acción'
                            else if(campo === 'fk_dependencias') nombreCampo = 'Dependencia ID'
                            else if(campo === 'responsable') nombreCampo = 'Responsable ID'
                            else if(campo === 'nombre_responsable') nombreCampo = '👤 Nombre Responsable'
                            else if(campo === 'motivo') nombreCampo = 'Motivo'
                            else if(campo === 'observaciones') nombreCampo = 'Motivo'
                            else if(campo === 'estado') nombreCampo = 'Estado'
                            else if(campo === 'fk_tipos') nombreCampo = 'Tipo'
                            else if(campo === 'codigo') nombreCampo = 'Código'
                            else if(campo === 'elemento') nombreCampo = 'Elemento'
                            else if(campo === 'fk_clases') nombreCampo = 'Clase'
                            else if(campo === 'inventario') nombreCampo = 'Inventario'
                            else if(campo === 'serie') nombreCampo = 'Serie'
                            else if(campo === 'valor') nombreCampo = 'Valor'
                            
                            // Resaltar campos importantes con color
                            if(campo === 'accion'){
                                info += `<li style="color: #007bff; font-weight: bold;"><strong>${nombreCampo}:</strong> ${valor}</li>`
                            }else if(campo === 'nombre_responsable'){
                                info += `<li style="color: #28a745; font-weight: bold;"><strong>${nombreCampo}:</strong> ${valor}</li>`
                            }else if(campo === 'motivo'){
                                info += `<li style="color: #ff9800; font-weight: bold;"><strong>${nombreCampo}:</strong> ${valor}</li>`
                            }else{
                                info += `<li><strong>${nombreCampo}:</strong> ${valor}</li>`
                            }
                        }
                        info += '</ul>'
                    } catch(e) {
                        info = registro.informacion
                    }
                    
                    let fecha = moment(registro.fecha_creacion).format('YYYY-MM-DD HH:mm:ss')
                    
                    filas += `<tr>
                                <td>${index + 1}</td>
                                <td>${fecha}</td>
                                <td>${registro.nombre}</td>
                                <td>${registro.registro}</td>
                                <td class="text-left">${info}</td>
                            </tr>`
                })
            }
            $('#tablaHistorico').html(filas)
        })
    }

    function reintegrar(idElemento){
        // Mostrar modal con campo de motivo
        id = idElemento
        $('#motivoReintegro').val('') // Limpiar textarea
        $('#modalReintegroTitulo').text(`Reintegrar Elemento #${idElemento}`)
        $('#modalReintegro').modal('show')
    }

    // Manejar el formulario de reintegro
    $('#formularioReintegro').on('submit', function(e){
        e.preventDefault()
        let motivo = $('#motivoReintegro').val().trim()
        
        if(!motivo){
            toastr.error('El motivo es obligatorio')
            return
        }
        
        // Enviar petición con motivo
        enviarPeticion('elementos', 'updateHistorico', {
            info: {
                responsable: 1, 
                estado: 'Reintegrado', 
                accion: 'Reintegro',
                motivo: motivo
            }, 
            id: id
        }, function(r){
            if(r.ejecuto){
                toastr.success('Se actualizó correctamente')
                $('#modalReintegro').modal('hide')
                // Recargar toda la tabla con búsqueda actual
                let datosBusqueda = {criterio: 'id', valor: id}
                cargarRegistros(datosBusqueda, 'crear', function(){
                    
                })
            }else{
                toastr.error(r.mensajeError || 'Error al actualizar')
            }
        })
    })

    var idElementoActivar = 0
    var idResponsableActivar = 1
    var nombreResponsableActivar = ''
    var dependenciaActivar = 0

    function activarDesdeReintegro(idElemento){
        idElementoActivar = idElemento
        idResponsableActivar = 1
        nombreResponsableActivar = ''
        dependenciaActivar = 0
        $('#modalActivarTitulo').text(`Activar Elemento #${idElemento}`)
        $('#registroActivar').val('')
        $('#nombreActivar').val('')
        $('#motivoActivar').val('')
        $('#botonConfirmarActivar').prop('disabled', true)
        $('#modalActivarDesdeReintegro').modal('show')
    }

    // Buscar responsable para activación
    $('#botonBuscarActivar').on('click', function(){
        enviarPeticion('usuarios', 'select', {info: {registro: $('#registroActivar').val()}}, function(r){
            if(r.data.length == 0){
                toastr.error("El registro no existe en la base de datos")
                idResponsableActivar = 1
                nombreResponsableActivar = ''
                dependenciaActivar = 0
                $('#botonConfirmarActivar').prop('disabled', true)
                $('#nombreActivar').val('')
            }else{
                idResponsableActivar = r.data[0].id
                nombreResponsableActivar = r.data[0].nombre
                dependenciaActivar = r.data[0].fk_dependencias
                $('#botonConfirmarActivar').prop('disabled', false)
                $('#nombreActivar').val(r.data[0].nombre)
            }
        })
    })

    // Formulario para activar desde reintegro
    $('#formularioActivarDesdeReintegro').on('submit', function(e){
        e.preventDefault()
        
        if(idResponsableActivar == 1){
            toastr.error('Debe seleccionar un responsable válido')
            return
        }
        
        let motivo = $('#motivoActivar').val().trim()
        
        // Preparar datos
        let datosActivar = {
            id: idElementoActivar,
            responsable: idResponsableActivar,
            fk_dependencias: dependenciaActivar,
            nombre_responsable: nombreResponsableActivar
        }
        
        // Agregar motivo si existe
        if(motivo){
            datosActivar.motivo = motivo
        }
        
        // Enviar petición
        enviarPeticion('elementos', 'activarDesdeReintegro', datosActivar, function(r){
            if(r.ejecuto){
                toastr.success('Elemento activado correctamente')
                $('#modalActivarDesdeReintegro').modal('hide')
                // Recargar toda la tabla con búsqueda actual
                let datosBusqueda = {criterio: 'id', valor: idElementoActivar}
                cargarRegistros(datosBusqueda, 'crear', function(){
                    
                })
            }else{
                toastr.error(r.mensajeError || 'Error al activar el elemento')
            }
        })
    })
</script>

<style>
    /* Estilos para DataTable */
    #tabla thead th {
        background-color: #007bff !important;
        color: white !important;
        padding: 10px !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #0056b3 !important;
    }

    #tabla tbody td {
        padding: 8px !important;
        vertical-align: middle !important;
    }

    #tabla tbody tr:hover {
        background-color: #f5f5f5 !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-right {
        text-align: right !important;
    }

    /* DataTable wrapper */
    .dataTables_wrapper .dataTables_filter {
        float: right !important;
        margin-bottom: 10px !important;
    }

    .dataTables_wrapper .dataTables_info {
        float: left !important;
        margin-bottom: 10px !important;
    }

    .dt-buttons {
        margin-bottom: 15px !important;
        display: inline-block !important;
    }

    .dt-buttons button {
        margin-right: 5px !important;
    }

    /* Desabilitar botones durante carga */
    .btn:disabled {
        cursor: not-allowed !important;
        opacity: 0.65 !important;
    }
</style>
</body>
</html>