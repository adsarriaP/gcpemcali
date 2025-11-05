<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6" id="tituloContrato"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="elementos/porContratos/">Contratos</a></li>
                        <li class="breadcrumb-item active">Dashboard contratos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">            
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="supTotalEquipos"></h3>
                            <p>Total elementos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="supSinAsignar"></h3>
                            <p>Sin asignar</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="supConDependencia"></h3>
                            <p>Con dependencia</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="supAsignados"></h3>
                            <p>Asignados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card card-outline card-success">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-sm">
                                    <thead>
                                        <tr>
                                            <th>Gerencia</th>
                                            <th>Unidad</th>
                                            <th>Subclase</th>
                                            <th>Asignado</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenido"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaSubclases" style="height: 500px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaSinAsignar" style="height: 500px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaConDependencia" style="height: 500px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaAsignados" style="height: 500px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaCruceDepSubclase" style="height: 700px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaCruceDepSubclaseAsignados" style="height: 700px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card card-outline card-success">
                                <div class="card-body">
                                    <div class="chart">
                                        <div id="graficaEquiposSinSoporte" style="height: 700px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-warning btn-block btn-lg" onClick="exportarSinSoporte()">
                                <i class="fas fa-cloud-download-alt"></i>
                            </button>
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
    var contrato = <?=$parametros[0]?>;
    function init(info){
        let tabla = []
        let subclases = []
        let sinAsignar = []
        let conDependencia = []
        let asignados = []

        //Llenar titulo contrato
        enviarPeticion('contratos', 'select', {info: {id: contrato}}, function(r){
            $('#tituloContrato').html(`<h1>${r.data[0].contrato} <button type="button" class="btn btn-primary" onClick="exportar(${contrato})">
                        <i class="fas fa-file-download"></i></button></h1>`)
        })

        enviarPeticion('dashboard', 'getbase', {contrato: contrato}, function(r){
            //Tabla
            let fila = ''
            let isAsignado = ''
            r.data.map(registro => {
                isAsignado = 'NO'
                if(registro.responsable != 1){
                    isAsignado = 'SI'
                }
                tabla.push({gerencia: registro.gerencia, unidad: registro.unidad, subclase: registro.subclase, asignado: isAsignado, cantidad: registro.cantidad})
            })
            const resultado = Object.values(
                tabla.reduce((acumulador, objeto) => {
                    // Generamos una clave única combinando `tipo` y `calidad`
                    const clave = `${objeto.gerencia}-${objeto.unidad}-${objeto.subclase}-${objeto.asignado}`;                
                    if(!acumulador[clave]){
                        // Si no existe en el acumulador, lo agregamos
                        acumulador[clave] = { ...objeto };
                    }else{
                        // Si ya existe, sumamos la cantidad
                        acumulador[clave].cantidad += objeto.cantidad;
                    }                
                    return acumulador;
                }, {})
            );
            let total = 0
            resultado.map(registro => {
                fila += `<tr>
                            <td>${registro.gerencia}</td>
                            <td>${registro.unidad}</td>
                            <td>${registro.subclase}</td>
                            <td>${registro.asignado}</td>
                            <td>${registro.cantidad}</td>
                        </tr>`
                total += registro.cantidad
            })
            fila += `<tr><td colspan=4 class="text-center">Total</td><td>${total}</td></tr>`
            $('#contenido').html(fila)


            //Graficas por subclases
            r.data.forEach(objeto => {
                const existente = subclases.find(item => item.subclase === objeto.subclase)
                if(existente){
                    existente.cantidad += objeto.cantidad
                }else{
                    subclases.push({subclase: objeto.subclase, cantidad: objeto.cantidad})
                }
            })
            pintarSubclases(subclases)

            //Grafica sin asignar
            r.data.forEach(objeto => {
                if(objeto.gerencia == ''){
                    sinAsignar.push({subclase: objeto.subclase, cantidad: objeto.cantidad})
                }
            })
            pintarSinAsignar(sinAsignar)

            //Grafica asingados por gerencia            
            r.data.forEach(objeto => {
                if(objeto.gerencia != ''){
                    const existente = conDependencia.find(item => item.gerencia === objeto.gerencia)
                    if(existente){
                        existente.cantidad += objeto.cantidad
                    }else{
                        conDependencia.push({gerencia: objeto.gerencia, cantidad: objeto.cantidad})
                    }
                }
            })
            pintarConDependencia(conDependencia)

            //Asignados
            r.data.forEach(objeto => {
                if(objeto.responsable != 1){
                    const existente = asignados.find(item => item.gerencia === objeto.gerencia)
                    if(existente){
                        existente.cantidad += objeto.cantidad
                    }else{
                        asignados.push({gerencia: objeto.gerencia, cantidad: objeto.cantidad})
                    }
                }
            })
            pintarAsignados(asignados)

            pintarCruceDepSubclase(r.data)
            pintarCruceDepSubclaseAsignados(r.data)
        })

        pintarEquiposSinSoporte()
    }

    function exportar(idContrato){
        url = `reportes/contratosExport/${idContrato}`
        window.open(url, '_blank')
    }

    function exportarSinSoporte(){
        url = `reportes/sinSoporteExport/`
        window.open(url, '_blank')
    }

    function pintarSubclases(arreglo){
        let categorias = []
        let datos = []
        let total = 0
        arreglo.map(registro => {
            categorias.push(registro.subclase)
            datos.push(registro.cantidad)
            total += registro.cantidad
        })        
        $('#supTotalEquipos').text(total)
        Highcharts.chart('graficaSubclases', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total elementos'
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
                name: 'Elementos',
                colorByPoint: true,
                data: datos
            }]
        })
    }

    function pintarSinAsignar(arreglo){
        let categorias = []
        let datos = []
        let total = 0
        arreglo.map(registro => {
            categorias.push(registro.subclase)
            datos.push(registro.cantidad)
            total += registro.cantidad
        })        
        $('#supSinAsignar').text(total)
        Highcharts.chart('graficaSinAsignar', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Elementos sin asignar'
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
                name: 'Elementos',
                colorByPoint: true,
                data: datos
            }]
        })
    }

    function pintarConDependencia(arreglo){
        let categorias = []
        let datos = []
        let total = 0
        arreglo.map(registro => {
            categorias.push(registro.gerencia)
            datos.push(registro.cantidad)
            total += registro.cantidad
        })        
        $('#supConDependencia').text(total)
        Highcharts.chart('graficaConDependencia', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Elementos con dependencia'
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
                name: 'Elementos',
                colorByPoint: true,
                data: datos
            }]
        })
    }

    function pintarAsignados(arreglo){
        let categorias = []
        let datos = []
        let total = 0
        arreglo.map(registro => {
            categorias.push(registro.gerencia)
            datos.push(registro.cantidad)
            total += registro.cantidad
        })        
        $('#supAsignados').text(total)
        Highcharts.chart('graficaAsignados', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Elementos Asignados'
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
                name: 'Elementos',
                colorByPoint: true,
                data: datos
            }]
        })
    }

    function pintarCruceDepSubclase(arreglo){
        //Aqui se filtran los datos donde la gerencia este vacia
        const datosFiltrados = arreglo.filter(d => d.gerencia.trim() !== '');        
        //Recorro el arreglo y armo las categorias eje X
        const subclases = [...new Set(datosFiltrados.map(d => d.subclase))];        
        //Recorro el arreglo y armo los datos stakeados
        const gerencias = [...new Set(datosFiltrados.map(d => d.gerencia))];
        //Se arman las series
        const series = subclases.map(
            subclase => ({
                name: subclase,
                data: gerencias.map(ger => 
                    datosFiltrados
                        .filter(d => d.gerencia === ger && d.subclase === subclase)
                        .reduce((sum, item) => sum + item.cantidad, 0)
                )
            })            
        )
        Highcharts.chart('graficaCruceDepSubclase', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Elementos con dependencia por subclase'
            },            
            xAxis: {
                categories: gerencias,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold', // Opcional: estilo
                        color: 'gray'       // Opcional: color
                    },
                    formatter: function () {
                        return this.total; // Mostrar la suma total del stack
                    }
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: series
        })
    }

    function pintarCruceDepSubclaseAsignados(arreglo){
        //Aqui se filtran los datos donde la gerencia este vacia
        const datosFiltrados = arreglo.filter(obj => obj.gerencia.trim() !== '' && obj.responsable != 1);        
        //Recorro el arreglo y armo las categorias eje X
        const subclases = [...new Set(datosFiltrados.map(d => d.subclase))];        
        //Recorro el arreglo y armo los datos stakeados
        const gerencias = [...new Set(datosFiltrados.map(d => d.gerencia))];
        //Se arman las series
        const series = subclases.map(
            subclase => ({
                name: subclase,
                data: gerencias.map(ger => 
                    datosFiltrados
                        .filter(d => d.gerencia === ger && d.subclase === subclase)
                        .reduce((sum, item) => sum + item.cantidad, 0)
                )
            })            
        )
        Highcharts.chart('graficaCruceDepSubclaseAsignados', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Elementos con dependencia'
            },            
            xAxis: {
                categories: gerencias,
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold', // Opcional: estilo
                        color: 'gray'       // Opcional: color
                    },
                    formatter: function () {
                        return this.total; // Mostrar la suma total del stack
                    }
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: series
        })
    }

    function pintarEquiposSinSoporte(){
        enviarPeticion('dashboard', 'equiposSinsoporte', {1:1}, function(r){
            let categorias = []
            let datos = []
            let total = 0
            r.data.map(registro => {
                categorias.push(registro.gerencia)
                datos.push(registro.cantidad)
                total += registro.cantidad
            })
            Highcharts.chart('graficaEquiposSinSoporte', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Equipos sin soporte'
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
                    name: 'Equipos',
                    colorByPoint: true,
                    data: datos
                }]
            })
        })
        
    }
</script>
</body>
</html>