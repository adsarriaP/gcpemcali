<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Buscar solicitud
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Buscar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <form id="formulario">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rol">Criterio</label>
                                            <select class="form-control" name="criterio" required="required">
                                                <option value="solicitud">Solicitud</option>
                                                <option value="solicitante">Registro solicitante</option>
                                                <option value="receptor">Registro receptor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input type="number" class="form-control" name="id" required="required">
                                        </div>
                                    </div>
                                    <div class="col-md-4    ">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i>
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
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla" class="table table-bordered table-sm text-sm">
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
    var rol = ''
    var id = 0
    function init(info){
        id = info.data.usuario.id
        rol = info.data.usuario.rol
        //Buscar activo
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))            
            cargarRegistros(datos, function(){
                console.log('Cargo...')
            })
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudAll', datos, function(r){
            let fila = ''
            let botonQuienLoTiene = ''
            let botonCancelar = ''
            if(r.data.length == 0){
                toastr.error("No se encontraros registros")
                $('#contenido').html('')
            }else{
                r.data.map(registro => {
                    botonQuienLoTiene = ''
                    botonCancelar = ''
                    if((rol == 'Administrador' || id == 2295) && registro.estado != 10 && registro.estado != 11){
                        botonQuienLoTiene = `<button type="button" class="btn btn-link btn-sm" onClick="verPersonas(${registro.id}, ${registro.estado}, ${registro.clase}, ${registro.idReceptor}, ${registro.idElemento}, ${registro.dependencia})" title="Ver quien lo tiene">
                                                <i class="fas fa-user"></i>
                                            </button>`
                        botonCancelar = `<button type="button" class="btn btn-danger" onClick="cancelarSolicitud(${registro.id},${registro.idElemento})" title="Cancelar solicitud">
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
                                            <button type="button" class="btn btn-default" onClick="mostrarDetalle('solicitud',${registro.id})" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default" onClick="mostrarHistorico(${registro.id},${registro.tramite},${registro.tipo},${registro.clase})" title="Historico">
                                                <i class="fas fa-history"></i>
                                            </button>
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