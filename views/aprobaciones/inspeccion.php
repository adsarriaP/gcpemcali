<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Inspeccionar elemento
                        <span>
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Inspeccionar</li>
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
                                            <th>Solicitud</th>
                                            <th>Tramite</th>
                                            <th>Tipo</th>
                                            <th>Código</th>
                                            <th></th>
                                            <th>Descripción</th>
                                            <th>Serie</th>
                                            <th>Solicitante</th>
                                            <th>Planta</th>
                                            <th>Días</th>
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
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    var id = 0
    var tramite = 0
    var elemento = 0
    function init(info){
        //Cargar registros
        cargarRegistros({criterio: 'inspeccionar', estado: 4}, function(){
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
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            let colorBadge = ''
            r.data.map(registro => {
                colorBadge = getColor(registro.tiempo)
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${tramites[registro.tramite]}</td>
                            <td>${tipos[registro.tipo]}</td>
                            <td>${registro.codigo}</td>
                            <td class="text-center">${clasesIconos[registro.clase]}</td>
                            <td>${registro.elemento}</td>
                            <td>${registro.serie}</td>
                            <td>${registro.registro} - ${registro.nombre}</td>
                            <td>${registro.planta}</td>
                            <td class="text-center">
                                <span class="badge badge-${colorBadge}">${registro.tiempo}</span>                                
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle('solicitud',${registro.id})" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id},${registro.tramite})" title="Aprobar">
                                                <i class="fas fa-check text-success"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm text-danger" onClick="cancelarSolicitud(${registro.id},${registro.idElemento})" title="Cancelar solicitud">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarHistorico(${registro.id},${registro.tramite},${registro.tipo},${registro.clase})" title="Historico">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>`
            })
            $('#contenido').append(fila)
            callback()
            $('#conteo_total').text(`Total: ${r.data.length || 0}`)
        })
    }

    function aprobar(idSolicitud, tramite){
        let mensaje = ['','','','servible','inservible']
        Swal.fire({
            title: `Aprobar ${tramites[tramite]} registrado en solicitud #${idSolicitud}`,
            input: 'textarea',
            inputPlaceholder: `Escriba el concepto técnico donde especifique que el elemento esta ${mensaje[tramite]}`,
            inputValidator: (value) => {
                if (!value) {
                    return 'Por favor, escribe el concepto';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {                
                let datos = {                    
                    concepto: result.value,
                    estado: 5
                }
                enviarPeticion('solicitudes', 'setEstado', {info: datos, id: idSolicitud}, function(r){
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }
</script>
</body>
</html>