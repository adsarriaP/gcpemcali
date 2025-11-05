function mostrarDetalle(criterio, idSolicitud){
    enviarPeticion('solicitudes', 'getSolicitudAll', {criterio: criterio, id: idSolicitud}, function(r){
        let tabla = ''
        let labelRegistro = ''
        r.data.map(registro => {
            labelRegistro = '<th>Receptor</th><td></td>'
            if(registro.receptorRegistro != 1){
                labelRegistro = `<th>Receptor</th><td>${registro.receptor}</br>Registro: ${registro.receptorRegistro}</td>`
            }
            tabla = `   <table class="table table-bordered table-striped table-sm text-sm">
                        <thead>
                            <tr class="text-center">
                                <th>Campo</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            <tr><th>Solicitud</th><td>${registro.id}</td></tr>
                            <tr><th>Tramite</th><td>${tramites[registro.tramite]}</td></tr>
                            <tr><th>Tipo</th><td>${tipos[registro.tipo]}</td></tr>
                            <tr><th>Clase</th><td>${clasesIconos[registro.clase]} ${clases[registro.clase]}</td></tr>
                            <tr><th>Código elemento</th><td>${registro.codigo}</td></tr>
                            <tr><th>Descripción</th><td>${registro.elemento}</td></tr>
                            <tr><th>Solicitante</th><td>${registro.solicitante}</br>Registro: ${registro.solicitanteRegistro}</br>Teléfono: ${registro.solicitanteTelefono}</td></tr>
                            <tr>${labelRegistro}</tr>
                            <tr><th>Jefe</th><td>${registro.jefe}</td></tr>
                            <tr><th>Estado</th><td><span class="badge badge-${colores[registro.estado]}">${estados[registro.estado]}</span></td></tr>
                            <tr><th>Fecha de creación</th><td>${registro.fecha_creacion}</td></tr>
                        </tbody>`
        })
        Swal.fire({
            title: `Información solicitud`,
            html: tabla
        })
    })
}

function cancelarSolicitud(idSolicitud, idElemento){
    Swal.fire({
        title: `Cancelar solicitud #${idSolicitud}`,
        input: 'textarea',
        inputPlaceholder: 'Escribe el motivo de la cancelación',
        inputValidator: (value) => {
            if (!value) {
                return 'Por favor, escribe un motivo';
            }
        },
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            let datos = {                    
                observaciones: result.value,
                estado: 11
            }
            enviarPeticion('solicitudes', 'cancelar', {info: datos, id: idSolicitud, elemento: idElemento}, function(r){
                //Borrar registro
                $(`#${idSolicitud}`).hide('slow')
            })
        }
    })
}

function mostrarHistorico(id, tramite, tipo, clase){
    let estadosHistorico = [
        '',
        [//Asignación
            '',
            [//Activo
                '',
                '',                
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','Creo solicitud','Aceptó elemento','','','','','Entregó elemento','Actualizó SAP','','Actualizó carpeta','Anuló']//Sillas
            ],
            [//Controlado
                '',
                '',
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló']//Sillas
            ],
            [//AO
                '',
                '',
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','Creo solicitud','Aceptó elemento','','','','','','Entregó elemento','','Actualizó carpeta','Anuló']//Sillas
            ]
        ],
        '',
        [//Traspaso
            '',
            [//Activo
                '',
                '',
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','','Aceptó elemento','Aprobó jefe','Realizó inspección','','Aprobó inspección','Actualizó SAP','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','Creo solicitud','','Aceptó elemento','','','','Aprobó jefe','Actualizó SAP','','Actualizó carpeta','Anuló']//Sillas
            ],
            [//Controlado
                '',
                '',
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló']//Sillas
            ],
            [//AO
                '',
                '',
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//muebles
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','Creo solicitud','','Aceptó elemento','','','','','Aprobó jefe','','Actualizó carpeta','Anuló']//Sillas
            ]
        ],
        [//Reintegro
            '',
            [//Activo
                '',
                '',
                ['','','','Creo solicitud','','','Aprobó jefe','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//muebles
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','','','Creo solicitud','','','Aprobó jefe','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','Recibió en almacén','Actualizó SAP','','Actualizó carpeta','Anuló']//Sillas
            ],
            [//Controlado
                '',
                '',
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//maquinaria y equipo
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//muebles
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','','Recibió en almacén','','Actualizó carpeta','Anuló'],//equipos de computo
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//Equipos de comunicaciones
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','','Recibió en almacén','','Actualizó carpeta','Anuló'],//Vehiculos
                ['','','','Creo solicitud','','','Aprobó jefe','','Recibió en almacén','','Actualizó carpeta','Anuló'],//Equipos de laboratorio
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','Aprobó inspección','','Recibió en almacén','','Actualizó carpeta','Anuló']//Sillas
            ],
            [//AO
                '',
                '',
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//maquinaria y equipo
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//muebles
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','','','','Aprobó inspección','Cambio elemento','Anuló'],//equipos de computo
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//Equipos de comunicaciones
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','','','','Aprobó inspección','Cambio elemento','Anuló'],//Vehiculos
                ['','','','Creo solicitud','','','','','','Aprobó jefe','Cambio elemento','Anuló'],//Equipos de laboratorio
                ['','','','Creo solicitud','Aprobó jefe','Realizó inspección','','','','Aprobó inspección','Cambio elemento','Anuló']//Sillas
            ]
        ]
    ]
    enviarPeticion('solicitudesHistorico', 'getHistorico', {solicitud: id}, function(r){
        let fila = ''
        r.data.map(registro => {
            let cambio = JSON.parse(registro.informacion)

            //Aqui se define si se muestra el concepto técnico o no
            let ct = ''
            if(cambio.concepto != undefined){
                ct = cambio.concepto
            }

            let observacion = ''
            if(cambio.observaciones != undefined){
                observacion = cambio.observaciones
            }
            fila += `<tr>
                        <td>${registro.nombre}</td>
                        <td>${estadosHistorico[tramite][tipo][clase][cambio.estado]}</td>
                        <td>${registro.fecha_creacion}</td>
                        <td>${ct}</td>
                        <td>${observacion}</td>
                    </tr>`
        })
        Swal.fire({
            title: `Historico para la solicitud #${id}`,
            html: ` <table class="table table-bordered table-sm text-sm">
                        <thead>
                            <tr>
                                <th>Quien</th>
                                <th>Acción</th>
                                <th>Cuando</th>
                                <th>Concepto técnico</th>
                                <th>observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">${fila}</tbody>
                    </table>`
        })
    })
}

function cargarArchivo(input, id){
    toastr.info('Por favor espere', 'Cargando...', {timeOut: 0})
    let archivo = input.files[0]
    let ext = archivo.name.split('.').pop();
    if(ext == 'pdf' || ext == 'PDF'){
        var fd = new FormData();
        fd.append('objeto','archivos')
        fd.append('metodo','cargarDocumento')
        fd.append('datos[id]',id)
        fd.append('file',archivo)
        $.ajax({                
            url:'api',
            type: "POST",
            dataType: 'json',
            data: fd,
            processData: false,
            contentType: false,
            success: function(respuesta){
                toastr.clear()
                if(respuesta.ejecuto == true){
                    toastr.success(respuesta.msg)
                }else{
                    toastr.error(respuesta.msg)
                }                       
            },
            error: function(xhr, status){
                console.log('Ocurrio un error')
            }
        })
    }else{
        toastr.error('El archivo debe tener extensión pdf')
    }
}

function downloadDocument(idSolicitud) {
    enviarPeticion('archivos', 'getDocumento', {id: idSolicitud}, function(r){
        if (r.file) {
            // Decodificar base64 a bytes binarios
            const binaryString = atob(r.file)
            const len = binaryString.length
            const bytes = new Uint8Array(len)
            for (let i = 0; i < len; i++) {
                bytes[i] = binaryString.charCodeAt(i)
            }

            // Crear un Blob a partir de los bytes
            const blob = new Blob([bytes], { type: 'application/pdf' })
            const blobUrl = URL.createObjectURL(blob)

            // Abrir el PDF en una nueva pestaña
            const newTab = window.open(blobUrl, '_blank')

            // Limpia la URL temporal después de abrirla
            setTimeout(() => URL.revokeObjectURL(blobUrl), 1000)
        }
    })
}

function getColor(tiempo){
    if(tiempo >= 6){
        colorBadge = 'danger'
    }else if(tiempo >= 3){
        colorBadge = 'warning'
    }else{
        colorBadge = 'success'
    }
    return colorBadge
}