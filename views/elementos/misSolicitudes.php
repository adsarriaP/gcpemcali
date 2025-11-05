<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Mis solicitudes
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Mis solicitudes</li>
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
                                    <div class="col-md-3">
                                        <label for="filtroCie">Solicitud</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="id">
                                            <span class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-default btn-block" id="solicitante">
                                            Solicitante
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-default btn-block" id="receptor">
                                            Receptor
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-default btn-block" id="jefe">
                                            Jefe
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
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Solicitud</th>
                                            <th>Trámite</th>
                                            <th>Tipo</th>
                                            <th>Código externo</th>
                                            <th colspan=2>Descripción</th>
                                            <th>Serie</th>
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
</div>

<?php require('views/footer.php');?>

<script src="dist/js/solicitudes.js"></script>
<script type="text/javascript">
    function init(info){
        //Cargar por defecto donde soy solicitante
        cargarRegistros({criterio: 'solicitante'}, function(){
            console.log('Cargo...')
        })

        //Buscar activo
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))            
            datos.criterio = 'id'
            cargarRegistros(datos, function(){
                console.log('Cargo...')
            })
        })

        $('#solicitante').on('click', function(){
            cargarRegistros({criterio: 'solicitante'}, function(){
                console.log('Cargo...')
            })
        })

        $('#receptor').on('click', function(){
            cargarRegistros({criterio: 'receptor'}, function(){
                console.log('Cargo...')
            })
        })

        $('#jefe').on('click', function(){
            cargarRegistros({criterio: 'jefeGrabado'}, function(){
                console.log('Cargo...')
            })
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){            
            let fila = ''
            let botonComprobante = ''
            let botonQuienLoTiene = ''
            let botonCancelar = ''
            if(r.data.length == 0){
                toastr.error("No se encontraros registros")
                $('#contenido').html('')
            }else{
                r.data.map(registro => {
                    botonComprobante = ''
                    botonQuienLoTiene = ''
                    botonCancelar = ''
                    //Se muestra el boton en caso de que la solicitud este en almacen
                    if(registro.estado == 6){
                        botonComprobante = `<a href="aprobaciones/generar/${registro.id}" class="btn btn-default btn-sm" title="Generar reporte" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>`
                    }
                    //Mostrar quien lo tiene en caso de que no este finalizada
                    if(registro.estado <= 10){
                        botonQuienLoTiene = `<button type="button" class="btn btn-link btn-sm" onClick="verPersonas(${registro.id}, ${registro.estado}, ${registro.clase}, ${registro.idReceptor}, ${registro.idElemento}, ${registro.dependencia})" title="Ver quien lo tiene">
                                                <i class="fas fa-user"></i>
                                            </button>`
                    }
                    //Mostrar boton cancelar en caso de que no haya aceptado el jefe
                    if(registro.estado <= 3){
                        botonCancelar = `<button type="button" class="btn btn-danger btn-sm" onClick="cancelarSolicitud(${registro.id},${registro.idElemento})" title="Cancelar solicitud">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>`
                    }
                    fila += `<tr id=${registro.id}>
                                <td>${registro.id}</td>
                                <td>${tramites[registro.tramite]}</td>
                                <td>${tipos[registro.tipo]}</td>
                                <td>${registro.codigo}</td>
                                <td class="text-center">${clasesIconos[registro.clase]}</td>
                                <td>${registro.elemento}</td>
                                <td>${registro.serie}</td>
                                <td class="text-center">
                                    <table>
                                        <tr>
                                            <td>
                                                <span class="badge badge-${colores[registro.estado]}">
                                                    ${estados[registro.estado]}
                                                </span>
                                            </td>
                                            <td>
                                                ${botonQuienLoTiene}
                                            </td>
                                        </tr>
                                    </table>
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
                                                <button type="button" class="btn btn-default btn-sm" onClick="mostrarHistorico(${registro.id},${registro.tramite},${registro.tipo},${registro.clase})" title="Historico">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                            </td>
                                            <td>
                                                ${botonComprobante}
                                            </td>
                                            <td>
                                                ${botonCancelar}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>`
                })
                $('#contenido').html(fila)
            }
            callback()
        })
    }

    function verPersonas(idSolicitud, estado, clase, receptor, elemento, dependencia){
        enviarPeticion('solicitudes', 'getQuienLoTiene', {solicitud: idSolicitud, estado: estado, clase: clase, receptor: receptor, elemento: elemento, dependencia: dependencia}, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr>
                            <td>${registro.nombre}</td>
                            <td>${registro.login}@emcali.com.co</td>
                            <td>${registro.telefono}</td>
                        </tr>`
            })
            Swal.fire({
                title: `Quien tiene la solicitud #${idSolicitud}`,
                html: ` <table class="table table-bordered text-left text-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                            <tbody>${fila}</tbody>
                        </table>`
            })
        })
    }
</script>
</body>
</html>