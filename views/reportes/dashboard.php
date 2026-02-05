<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1>
                        Dashboard
                    </h1>
                </div>
                <div class="col-sm-4">
                    <!-- Botones de exportación -->
                    <div class="form-group row mb-3">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-primary btn-sm btn-block" id="exportar" title="Exportar solicitudes">
                                <i class="fas fa-file-download"></i> Exportar Solicitudes
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-sm btn-block" onclick="exportarProductividad()" title="Exportar productividad">
                                <i class="fa fa-file-excel"></i> Exportar Productividad
                            </button>
                        </div>
                    </div>

                    <!-- Selectores de fecha -->
                    <div class="form-group row">
                        <label for="fechaInicio" class="col-sm-2 col-form-label">Desde</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control form-control-sm" id="fechaInicio">
                        </div>
                        <label for="fechaFin" class="col-sm-2 col-form-label">Hasta</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control form-control-sm" id="fechaFin">
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-info btn-sm" id="aplicarRango" title="Aplicar rango">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6"></div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="supSolicitudes"></h3>
                            <p>Solicitudes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="supPromedio"></h3>
                            <p>Solicitudes promedio por día</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="chart">
                                <div id="conteoTramite" style="height: 500px"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="chart">
                                <div id="conteoEstado" style="height: 500px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <div class="chart">
                                <div id="solicitudesDia" style="height: 500px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="chart">
                                <div id="ingresosDia" style="height: 400px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<!--Higtcharts-->
<script src="node_modules/highcharts/highcharts.js"></script>
<script src="node_modules/highcharts/highcharts-3d.js"></script>
<script src="node_modules/highcharts/modules/exporting.js"></script>
<script src="node_modules/highcharts/highcharts-more.js"></script>
<script src="node_modules/highcharts/modules/solid-gauge.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<script type="text/javascript">
    // Variables globales
    var vigencia = 0
    var fechaInicio = ''
    var fechaFin = ''
    var usarRangoFechas = false // Flag para saber qué método usar

    function init(info) {
        // ===== CONFIGURACIÓN INICIAL =====
        // Seleccionar vigencia actual
        vigencia = moment().format('YYYYMM')
        $('#vigencia').val(vigencia)
        
        // Establecer fechas por defecto (primer y último día del mes actual)
        const primerDia = moment().startOf('month').format('YYYY-MM-DD')
        const ultimoDia = moment().endOf('month').format('YYYY-MM-DD')
        
        $('#fechaInicio').val(primerDia)
        $('#fechaFin').val(ultimoDia)
        
        fechaInicio = primerDia
        fechaFin = ultimoDia

        // ===== CARGAR DATOS INICIALES (usando vigencia por defecto) =====
        cargarDatos()

        // ===== EVENTO: Cambio en el select de vigencia (mes) =====
        $('#vigencia').on('change', function() {
            vigencia = $(this).val()
            usarRangoFechas = false // Activar modo vigencia
            
            // Actualizar los campos de fecha para que reflejen el mes seleccionado
            const anio = vigencia.toString().substring(0, 4)
            const mes = vigencia.toString().substring(4, 6)
            fechaInicio = moment(`${anio}-${mes}-01`).startOf('month').format('YYYY-MM-DD')
            fechaFin = moment(`${anio}-${mes}-01`).endOf('month').format('YYYY-MM-DD')
            
            $('#fechaInicio').val(fechaInicio)
            $('#fechaFin').val(fechaFin)
            
            cargarDatos()
        })

        // ===== EVENTO: Cambio en campos de fecha =====
        $('#fechaInicio').on('change', function() {
            fechaInicio = $(this).val()
        })

        $('#fechaFin').on('change', function() {
            fechaFin = $(this).val()
        })

        // ===== EVENTO: Botón aplicar rango de fechas =====
        $('#aplicarRango').on('click', function() {
            if (!fechaInicio || !fechaFin) {
                toastr.warning('Por favor seleccione ambas fechas')
                return
            }
            
            if (fechaInicio > fechaFin) {
                toastr.warning('La fecha de inicio no puede ser mayor que la fecha de fin')
                return
            }
            
            usarRangoFechas = true // Activar modo rango personalizado
            cargarDatos()
        })

        // ===== EVENTO: Exportar solicitudes =====
        $('#exportar').on('click', function() {
            if (usarRangoFechas) {
                url = `reportes/solicitudesExport/${fechaInicio}/${fechaFin}`
            } else {
                url = `reportes/solicitudesExport/${vigencia}`
            }
            window.open(url, '_blank')
        })
    }

    // ===== FUNCIÓN PRINCIPAL: Cargar todos los datos =====
    function cargarDatos() {
        conteoTramite()
        conteoEstado()
        solicitudesDia()
        ingresosDia()
    }

    // ===== GRÁFICA: Conteo por tipo de trámite =====
    function conteoTramite() {
        const params = usarRangoFechas 
            ? { fechaInicio: fechaInicio, fechaFin: fechaFin }
            : { vigencia: vigencia }
        
        enviarPeticion('dashboard', 'conteoTramite', params, function(r) {
            let categorias = []
            let datos = []
            let total = 0
            
            r.data.map(registro => {
                categorias.push(registro.nombre)
                datos.push(registro.cantidad)
                total += registro.cantidad
            })
            
            $('#supSolicitudes').text(total)
            
            Highcharts.chart('conteoTramite', {
                title: {
                    text: 'Solicitudes por tipo de trámite'
                },
                subtitle: {
                    text: `Total: ${total}`
                },
                xAxis: {
                    categories: categorias,
                },
                yAxis: {
                    title: {
                        text: 'Cantidad'
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    type: 'column',
                    name: 'Cantidad',
                    colorByPoint: true,
                    data: datos
                }]
            })
        })
    }

    // ===== GRÁFICA: Conteo por estado =====
    function conteoEstado() {
        const params = usarRangoFechas 
            ? { fechaInicio: fechaInicio, fechaFin: fechaFin }
            : { vigencia: vigencia }
        
        enviarPeticion('dashboard', 'conteoEstado', params, function(r) {
            let categorias = []
            let datos = []
            let total = 0
            
            r.data.map(registro => {
                categorias.push(registro.nombre)
                datos.push(registro.cantidad)
                total += registro.cantidad
            })
            
            $('#supSolicitudes').text(total)
            
            Highcharts.chart('conteoEstado', {
                title: {
                    text: 'Solicitudes por estado'
                },
                subtitle: {
                    text: `Total: ${total}`
                },
                xAxis: {
                    categories: categorias,
                },
                yAxis: {
                    title: {
                        text: 'Cantidad'
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    type: 'column',
                    name: 'Cantidad',
                    colorByPoint: true,
                    data: datos
                }]
            })
        })
    }

    // ===== GRÁFICA: Solicitudes por día =====
    function solicitudesDia() {
        const params = usarRangoFechas 
            ? { fechaInicio: fechaInicio, fechaFin: fechaFin }
            : { vigencia: vigencia }
        
        enviarPeticion('dashboard', 'solicitudesDia', params, function(r) {
            let datos = []
            let cantidad = 0
            let promedio = 0
            let categorias = []

            if (usarRangoFechas) {
                // Modo rango: calcular días dinámicamente
                let inicio = moment(fechaInicio)
                let fin = moment(fechaFin)
                let numDias = fin.diff(inicio, 'days') + 1

                for (let i = 0; i < numDias; i++) {
                    categorias.push(moment(fechaInicio).add(i, 'days').format('DD/MM'))
                }

                datos.push({
                    name: 'Solicitudes',
                    data: new Array(numDias).fill(0)
                })

                for (let i = 0; i < r.data.length; i++) {
                    let diaPos = moment(r.data[i].fecha).diff(moment(fechaInicio), 'days')
                    datos[0].data[diaPos] = r.data[i].cantidad
                    cantidad += r.data[i].cantidad
                }
            } else {
                // Modo vigencia: usar días del 1 al 31
                categorias = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
                
                datos.push({
                    name: 'Solicitudes',
                    data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                })

                for (let i = 0; i < r.data.length; i++) {
                    datos[0].data[r.data[i].dia - 1] = r.data[i].cantidad
                    cantidad += r.data[i].cantidad
                }
            }

            datos[0].name = `Solicitudes (${cantidad})`
            promedio = r.data.length > 0 ? cantidad / r.data.length : 0

            $('#supSolicitudes').text(cantidad)
            $('#supPromedio').text(promedio.toFixed(1))

            Highcharts.chart('solicitudesDia', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Solicitudes por día'
                },
                subtitle: {
                    text: `Total: ${cantidad}`
                },
                xAxis: {
                    categories: categorias
                },
                yAxis: {
                    title: {
                        text: 'Cantidad'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    },
                    series: {
                        label: {
                            connectorAllowed: false
                        }
                    }
                },
                series: datos
            })
        })
    }

    // ===== GRÁFICA: Ingresos por día =====
    function ingresosDia() {
        const params = usarRangoFechas 
            ? { fechaInicio: fechaInicio, fechaFin: fechaFin }
            : { vigencia: vigencia }
        
        let categorias = []
        let numDias = 0

        if (usarRangoFechas) {
            let inicio = moment(fechaInicio)
            let fin = moment(fechaFin)
            numDias = fin.diff(inicio, 'days') + 1
            
            for (let i = 0; i < numDias; i++) {
                categorias.push(moment(fechaInicio).add(i, 'days').format('DD/MM'))
            }
        } else {
            numDias = 31
            categorias = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
        }
        
        let datos = [{
                name: 'Ingresos',
                data: new Array(numDias).fill(0)
            },
            {
                name: 'Ingresos diferentes',
                data: new Array(numDias).fill(0)
            }
        ]
        
        enviarPeticion('logIngresos', 'ingresosDia', params, function(r1) {
            let cantidad1 = 0
            
            for (let i = 0; i < r1.data.length; i++) {
                let diaPos = usarRangoFechas 
                    ? moment(r1.data[i].fecha).diff(moment(fechaInicio), 'days')
                    : r1.data[i].dia - 1
                
                datos[0].data[diaPos] = r1.data[i].cantidad
                cantidad1 += r1.data[i].cantidad
            }
            datos[0].name = `Ingresos ${cantidad1}`

            enviarPeticion('logIngresos', 'ingresosDiferentesDia', params, function(r2) {
                let cantidad2 = 0
                
                for (let i = 0; i < r2.data.length; i++) {
                    let diaPos = usarRangoFechas 
                        ? moment(r2.data[i].fecha).diff(moment(fechaInicio), 'days')
                        : r2.data[i].dia - 1
                    
                    datos[1].data[diaPos] = r2.data[i].cantidad
                    cantidad2 += r2.data[i].cantidad
                }
                datos[1].name = `Ingresos diferentes ${cantidad2}`
                
                Highcharts.chart('ingresosDia', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Ingresos por día'
                    },
                    subtitle: {
                        text: 'Detalle de ingresos al sistema por día'
                    },
                    xAxis: {
                        categories: categorias
                    },
                    yAxis: {
                        title: {
                            text: 'Cantidad'
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true
                        },
                        series: {
                            label: {
                                connectorAllowed: false
                            }
                        }
                    },
                    series: datos
                })
            })
        })
    }

    // ===== FUNCIÓN: Exportar productividad =====
    function exportarProductividad() {
        if (usarRangoFechas) {
            if (!fechaInicio || !fechaFin) {
                toastr.warning('Por favor seleccione ambas fechas')
                return
            }
            url = `reportes/productividadExport/${fechaInicio}/${fechaFin}`
        } else {
            url = `reportes/productividadExport/${vigencia}`
        }
        window.open(url, '_blank')
    }
</script>
</script>
</body>

</html>