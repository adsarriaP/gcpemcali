<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Buscar elemento
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
                <div class="col">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <form id="formulario">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="rol">Criterio</label>
                                            <select class="form-control" name="criterio" required="required">
                                                <option value="registro">Registro</option>
                                                <option value="codigo">Codigo externo</option>
                                                <option value="serie">Serie</option>
                                                <option value="dependencia">Dependencia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Valor</label>
                                            <input type="text" class="form-control" name="valor" required="required">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
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
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id = 0
    var dependencia = 0
    var idReceptor = 1
    var rol = ''
    function init(info){
        rol = info.data.usuario.rol
        //Buscar activo
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            cargarRegistros(datos, 'crear', function(){
                console.log('Cargo...')
            })
        })
    }

    function cargarRegistros(datos, accion, callback){
        enviarPeticion('elementos', 'getElementos', datos, function(r){
            let fila = ''
            let trabajador = ''
            let color = 'default'
            let botonDevolver = ''
            let mensaje = ''
            if(r.data.length == 0){
                toastr.error("No se encontraros registros")
                $('#contenido').html('')
            }else{
                r.data.map(registro => {
                    color = 'default'
                    mensaje = ''
                    if(registro.devolver == 1){
                        color = 'secondary'
                        mensaje = '<span class="badge bg-danger">Sin soporte - Reintegrar</span>'
                    }

                    botonDevolver = ''
                    if(rol == 'Administrador' || rol == 'GestorContrato'){
                        if((registro.fk_tipos == 1 || registro.fk_tipos == 2) && registro.fk_clases == 4 && registro.devolver == 0 && registro.id < 45079){
                            botonDevolver = `<button type="button" class="btn btn-danger" onClick="devolver(${registro.id})" title="Marcar sin soporte">
                                                <i class="fas fa-unlock-alt"></i>
                                            </button>`
                        }
                    }
                	trabajador = `${registro.registro} - ${registro.trabajador}`
                	if(registro.responsable == 1){
                		trabajador = ''
                	}
                    fila += `<tr id=${registro.id} class="table-${color}">
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
                                    ${botonDevolver}
                                    ${mensaje}
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
        })
    }
    
    function devolver(idElemento){
        Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de marcar el elemento con código #${idElemento} sin soporte`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){                
                enviarPeticion('elementos', 'updateHistorico', {info: {devolver: 1}, id: idElemento}, function(r){
                    cargarRegistros({criterio: 'id', valor: idElemento}, 'actualizar', function(){})
                })
            }
        })
    }
</script>
</body>
</html>