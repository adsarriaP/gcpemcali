<?php require('views/header.php');?>
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

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
                        <button type="button" class="btn btn-success" id="botonAbrirSolicitud" disabled="disabled" title="Crear solicitud para elementos seleccionados">
                            <i class="fas fa-project-diagram"></i>
                        </button>
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
                                            <th class="text-center" style="width: 35px;">
                                                <input type="checkbox" id="checkTodos" title="Seleccionar todos">
                                            </th>
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
                            <label>Elementos seleccionados</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-sm mb-2">
                                    <thead>
                                        <tr>
                                            <th>Código GCP</th>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th>Serie</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenidoElementosSeleccionados"></tbody>
                                </table>
                            </div>
                            <small class="text-muted" id="resumenElementosSeleccionados"></small>
                        </div>
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

<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var idS = 0
    var idReceptor = 1
    var idUsuario = 0
    var minimoElementosSolicitud = 2
    var tablaMisElementos = null
    var elementosInfo = {}

    function inicializarDataTable(){
        if($.fn.DataTable.isDataTable('#tabla')){
            $('#tabla').DataTable().destroy()
        }
        tablaMisElementos = $('#tabla').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            dom: 'Bfrtip',
            order: [[1, 'asc']],
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="far fa-file-excel"></i> Exportar Excel',
                    className: 'btn btn-success btn-sm',
                    title: 'Mis_Elementos',
                    exportOptions: {
                        columns: [1,2,3,5,6,7,8]
                    }
                }
            ],
            columnDefs: [
                { targets: [0, 9], orderable: false, searchable: false }
            ],
            language: {
                decimal: '',
                emptyTable: 'No hay información',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                thousands: ',',
                lengthMenu: 'Mostrar _MENU_ registros',
                loadingRecords: 'Cargando...',
                processing: 'Procesando...',
                search: 'Buscar:',
                zeroRecords: 'No se encontraron resultados',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            }
        })
    }

    function obtenerElementosSeleccionados(){
        let ids = []
        $('.checkElemento:checked').each(function(){
            ids.push(parseInt($(this).val()))
        })
        return ids
    }

    function actualizarEstadoBotonAbrirSolicitud(){
        let cantidadSeleccionados = obtenerElementosSeleccionados().length
        $('#botonAbrirSolicitud').prop('disabled', cantidadSeleccionados < minimoElementosSolicitud)
    }

    function actualizarEstadoBotonSolicitud(){
        let cantidadSeleccionados = obtenerElementosSeleccionados().length
        let cumpleCantidad = cantidadSeleccionados >= minimoElementosSolicitud
        let requiereReceptor = $('#fk_tramites').val() == 3
        let cumpleReceptor = !requiereReceptor || idReceptor > 1
        $('#botonSolicitud').prop('disabled', !(cumpleCantidad && cumpleReceptor))
    }

    function renderElementosSeleccionadosEnModal(){
        let seleccionados = obtenerElementosSeleccionados()
        let filas = ''
        seleccionados.map(idElemento => {
            let elemento = elementosInfo[idElemento]
            if(elemento){
                filas += `<tr>
                            <td>${elemento.id}</td>
                            <td>${tipos[elemento.fk_tipos]}</td>
                            <td>${elemento.elemento}</td>
                            <td>${elemento.serie}</td>
                            <td class="text-right">$${currency(elemento.valor,2)}</td>
                        </tr>`
            }
        })
        if(filas == ''){
            filas = `<tr><td colspan="5" class="text-center text-muted">No hay elementos seleccionados</td></tr>`
        }
        $('#contenidoElementosSeleccionados').html(filas)
        $('#resumenElementosSeleccionados').text(`Total seleccionados: ${seleccionados.length}`)
    }

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
                actualizarEstadoBotonSolicitud()
            }else{//Reintegro
                $('#panelReceptor').hide()
                $('#receptor').prop('required', false)
                $('.panelPlanta').show()
                idReceptor = 1
                actualizarEstadoBotonSolicitud()
            }
        })

        //Buscar receptor
        $('#botonBuscarReceptor').on('click', function(){
            enviarPeticion('usuarios', 'select', {info: {registro: $('#receptor').val()}}, function(r){
                if(r.data.length == 0){
                    toastr.error("El registro no existe en la base de datos")
                    idReceptor = 1
                    $('#nombreReceptor').val('')
                    actualizarEstadoBotonSolicitud()
                }else{
                    idReceptor = r.data[0].id
                    $('#nombreReceptor').val(r.data[0].nombre)
                    actualizarEstadoBotonSolicitud()
                }
            })
        })

        //Seleccionar todos los elementos disponibles
        $('#checkTodos').on('change', function(){
            $('.checkElemento').prop('checked', $(this).is(':checked'))
            actualizarEstadoBotonAbrirSolicitud()
            actualizarEstadoBotonSolicitud()
        })

        //Actualizar conteo al seleccionar por fila
        $('#tabla').on('change', '.checkElemento', function(){
            let total = $('.checkElemento').length
            let seleccionados = $('.checkElemento:checked').length
            $('#checkTodos').prop('checked', total > 0 && total == seleccionados)
            actualizarEstadoBotonAbrirSolicitud()
            actualizarEstadoBotonSolicitud()
            if($('#modalSolicitud').hasClass('show')){
                renderElementosSeleccionadosEnModal()
            }
        })

        //Abrir modal de solicitud para seleccion múltiple
        $('#botonAbrirSolicitud').on('click', function(){
            let seleccionados = obtenerElementosSeleccionados()
            if(seleccionados.length < minimoElementosSolicitud){
                toastr.warning(`Debe seleccionar al menos ${minimoElementosSolicitud} elementos para crear la solicitud`)
                return
            }
            $('#modalSolicitudTitulo').text(`Crear solicitud para ${seleccionados.length} elementos`)
            renderElementosSeleccionadosEnModal()
            $('#modalSolicitud').modal('show')
            actualizarEstadoBotonSolicitud()
        })

        //Fomulario para ingresar la solicitud
        $('#formularioSolicitud').on('submit', function(e){
            e.preventDefault()
            let elementosSeleccionados = obtenerElementosSeleccionados()
            if(elementosSeleccionados.length < minimoElementosSolicitud){
                toastr.warning(`Debe seleccionar al menos ${minimoElementosSolicitud} elementos para crear la solicitud`)
                return
            }
            let datos = parsearFormulario($(this))
            datos.receptor = idReceptor
            if(datos.fk_tramites == 4 && $('#fk_plantas').val() == 1){
                //Esto error sale cuando escoge un reintegro y no escoge la planta
                toastr.error("Deber seleccionar una planta válida")
            }else{
                //Crear solicitud
                enviarPeticion('usuarios', 'update', {info: {fk_plantas: $('#fk_plantas').val(), telefono: $('#telefono').val()}, id:idUsuario}, function(r){
                    enviarPeticion('solicitudes', 'crearMultiple', {solicitud: datos, elementos: elementosSeleccionados}, function(r){
                        $('#modalSolicitud').modal('hide')
                        Swal.fire({
                            icon: 'success',
                            title: 'Confimación',
                            text: `Se creó la solicitud #${r.insertId} con ${r.cantidad} elementos` 
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
            elementosInfo = {}
            r.data.map(registro => {
                elementosInfo[registro.id] = registro
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
                    let check = `<input type="checkbox" class="checkElemento" value="${registro.id}" title="Seleccionar elemento">`
                    botonTramitar = `<table>
                                        <tr>
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
                    fila += `<tr id=${registro.id} class="table-${color}">
                                <td class="text-center">${check}</td>
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
                }else{
                    fila += `<tr id=${registro.id} class="table-${color}">
                                <td class="text-center"></td>
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
                }
                totales[registro.fk_tipos]++
            })
            if(accion == 'crear'){
                $('#contenido').html(fila)
                inicializarDataTable()
            }else{
                $('#'+r.data[0].id).replaceWith(fila)
            }
            callback()
            $('#checkTodos').prop('checked', false)
            actualizarEstadoBotonAbrirSolicitud()
            actualizarEstadoBotonSolicitud()
            $('#conteo_activos').text(`Activos: ${totales[1]}`)
            $('#conteo_controlados').text(`Controlados: ${totales[2]}`)
            $('#conteo_ao').text(`AO: ${totales[3]}`)
        })
    }

    function cambiarUso(idActivo){
        id = idActivo
        $('#modalUsoTitulo').text(`Cambiar donde está el elemento con código #${idActivo}`)
        $('#modalUso').modal('show')
    }
</script>
</body>
</html>