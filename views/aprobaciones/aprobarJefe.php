<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>
                        Aprobar Jefe
                        <span>                            
                            <small class="badge badge-success text-xs" id="conteo_total"></small>
                        </span>
                    </h1>
                    <div id="labelDependencias"></div>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Aprobar solicitud</li>
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
                                <table class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Solicitud</th>
                                            <th>Trámite</th>
                                            <th>Tipo</th>
                                            <th>Código</th>
                                            <th colspan=2>Descripción</th>
                                            <th>Serie</th>
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
    var elemento = 0
    var dependencia = 0
    var jefe = 0
    function init(info){
        jefe = info.data.usuario.id
        //Traer dependencia del jefe que esta logueado
        enviarPeticion('dependencias', 'select', {info:{jefe: jefe}}, function(r){
            if(r.data.length == 0){
                toastr.error("Debes estar configurado como jefe en alguna dependencia, contacta al administrador", "Sin dependencia", {
                    timeOut: 5000,
                    onHidden: function(){
                        window.location.href = 'elementos/misElementos/'
                    }
                })
            }else{
                r.data.map(registro =>{
                    $('#labelDependencias').append(`<small class="badge badge-secondary text-xs">${registro.gerencia} -> ${registro.dependencia} -> ${registro.unidad}</small><br>`)
                })
                let deps = r.data.map(obj => obj.id).join(", ");
                /*let hijas = r.data.filter(obj => obj.hijas).map(obj => obj.hijas).join(", ");
                
                if(hijas != ''){//Este caso es cuando es un superior
                    //Primero selecciono los jefes inferiores
                    enviarPeticion('dependencias', 'getInferiores', {dependencias: hijas}, function(z){
                        cargarRegistros({criterio: 'superior', dependencia: deps, jefes: z.data[0].jefes, estado: 3}, function(){})    
                    })
                }else{*/
                    //Este caso es cuando es un jefe
                    cargarRegistros({criterio: 'jefe', dependencia: deps, estado: 3}, function(){})
                //}
            }
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('solicitudes', 'getSolicitudes', datos, function(r){
            let fila = ''
            r.data.map(registro => {
                fila += `<tr id=${registro.id}>
                            <td>${registro.id}</td>
                            <td>${tramites[registro.tramite]}</td>
                            <td>${tipos[registro.tipo]}</td>
                            <td>${registro.codigo}</td>
                            <td class="text-center">${clasesIconos[registro.clase]}</td>
                            <td>${registro.elemento}</td>
                            <td>${registro.serie}</td>
                            <td class="text-center">${registro.tiempo}</td>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="mostrarDetalle('solicitud',${registro.id})" title="ver detalle">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" onClick="aprobar(${registro.id},${registro.tramite},${registro.tipo},${registro.clase})" title="Aprobar">
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

    function aprobar(idSolicitud, tramite, tipo, clase){
         Swal.fire({
            icon: 'question',
            title: 'Confirmación',
            html: `Esta seguro de <strong>aprobar ${tramites[tramite]}</strong> registrado en la solicitud #${idSolicitud}`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.value){
                let estado = 0
                if(tramite == 3){//Traspaso
                    if(tipo == 1){//Activo
                        if(clase ==  6){//Requieren inspección
                            estado = 4
                        }else{//No requiere inspección
                            estado = 7
                        }
                    }else{//Los controlados y AO no requieren inspección
                        estado = 8
                    }
                }else if(tramite == 4){//Reintegro
                    if(tipo == 1){//Activo
                        if(clase == 4 || clase == 5 || clase == 6 || clase == 8){
                            estado = 4
                        }else{
                            estado = 6
                        }
                    }else if(tipo == 2){//Controlado
                        if(clase == 4 || clase == 6 || clase == 8){
                            estado = 4
                        }else{
                            estado = 6
                        }
                    }else{//AO
                        if(clase == 4 || clase == 6){
                            estado = 4
                        }else{
                            estado = 9
                        }
                    }
                }                    
                enviarPeticion('solicitudes', 'setEstado', {info: {jefe: jefe, estado: estado}, id: idSolicitud}, function(r){
                    $(`#${idSolicitud}`).hide('slow')
                })
            }
        })
    }
</script>
</body>
</html>