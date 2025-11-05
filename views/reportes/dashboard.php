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
                    <div class="form-group row">
                        <label for="vigencia" class="col-sm-4 col-form-label">Mes</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="vigencia">
                                <option value=202412>2024-12</option>
                                <option value=202501>2025-01</option>
                                <option value=202502>2025-02</option>
                                <option value=202503>2025-03</option>
                                <option value=202504>2025-04</option>
                                <option value=202505>2025-05</option>
                                <option value=202506>2025-06</option>
                                <option value=202507>2025-07</option>
                                <option value=202508>2025-08</option>
                                <option value=202509>2025-09</option>
                                <option value=202510>2025-10</option>
                                <option value=202511>2025-11</option>
                                <option value=202512>2025-12</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-primary" id="exportar" title="Exportar">
                                <i class="fas fa-file-download"></i>
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
    var vigencia = 0
    function init(info){
    	//Seleccionar vigencia actual y carga inicial
        vigencia = moment().format('YYYYMM')
        $('#vigencia').val(vigencia)
        conteoTramite()
        conteoEstado()
        solicitudesDia()
        ingresosDia()

        $('#vigencia').on('change', function(){
            vigencia = $(this).val()
            conteoTramite()
            conteoEstado()
            solicitudesDia()
            ingresosDia()
        })

        //Exportar en formato excel
        $('#exportar').on('click', function(){
            url = `reportes/solicitudesExport/${vigencia}`
            window.open(url, '_blank')
        })
    }

    function conteoTramite(){
        enviarPeticion('dashboard', 'conteoTramite', {vigencia: vigencia}, function(r){
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

    function conteoEstado(){
        enviarPeticion('dashboard', 'conteoEstado', {vigencia: vigencia}, function(r){
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

    function solicitudesDia(){
        enviarPeticion('dashboard', 'solicitudesDia', {vigencia: vigencia}, function(r){
            let datos = []
            let centinela = ''
            let pos = -1
            let cantidad = 0
            let promedio = 0
            for(let i = 0; i < r.data.length; i++){
                if(centinela != r.data[i].gerencia){
                    datos.push({
                        name: r.data[i].gerencia,
                        data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
                    })
                    pos++
                    centinela = r.data[i].gerencia
                }
                datos[pos].data[r.data[i].dia - 1] = r.data[i].cantidad
                cantidad += r.data[i].cantidad
            }
            for(i = 0; i < datos.length; i++){
                datos[i].name = datos[i].name + '(' +datos[i].data.reduce((a, b) => a + b, 0) + ')'
            }
            promedio = cantidad / r.data.length
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
                    text: 'Total:'+cantidad
                },
                xAxis: {
                    categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
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
                        enableMouseTracking: false
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

    function ingresosDia(){
        let datos = [
            {
                name: 'Ingresos',
                data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            },
            {
                name: 'Ingresos diferentes',
                data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
            }
        ]
        enviarPeticion('logIngresos', 'ingresosDia', {vigencia: vigencia}, function(r1){
            let cantidad1 = 0
            for(let i = 0; i < r1.data.length; i++){
                datos[0].data[r1.data[i].dia - 1] = r1.data[i].cantidad
                cantidad1 += r1.data[i].cantidad
            }
            datos[0].name = `Ingresos ${cantidad1}`

            enviarPeticion('logIngresos', 'ingresosDiferentesDia', {vigencia: vigencia}, function(r2){
                let cantidad2 = 0
                for(let i = 0; i < r2.data.length; i++){
                    datos[1].data[r2.data[i].dia - 1] = r2.data[i].cantidad
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
                        categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]
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
                            enableMouseTracking: false
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
</script>
</body>
</html>