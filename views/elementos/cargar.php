<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Cargar elementos
                        <a href="views/elementos/plantilla.xlsx" class="btn btn-success">
                            <i class="fas fa-download"></i> Plantilla
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Cargar</li>
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
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">              
                                            <th>Acción</th>
                                            <th>Contrato</th>
                                            <th>Archivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Cargar activos</td>
                                            <td></td>
                                            <td class="text-center">
                                                <a class='btn btn-default btn-file'>
                                                    <i class='fas fa-upload'></i>
                                                    <input type='file' onchange="cargarArchivo(this,1)" accept=".xls,.xlsx">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cargar elementos controlados</td>
                                            <td></td>
                                            <td class="text-center">
                                                <a class='btn btn-default btn-file'>
                                                    <i class='fas fa-upload'></i>
                                                    <input type='file' onchange="cargarArchivo(this,2)" accept=".xls,.xlsx">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cargar elementos AO</td>
                                            <td>
                                                <select class="form-control" id="fk_contratos" required="required"></select>
                                            </td>
                                            <td class="text-center">
                                                <a class='btn btn-default btn-file'>
                                                    <i class='fas fa-upload'></i>
                                                    <input type='file' onchange="cargarArchivo(this,3)" accept=".xls,.xlsx">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
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
    function init(info){
        llenarSelect('contratos', 'select', {info:{estado: 'activo'}, orden: 'contrato'}, 'fk_contratos', 'contrato', 2)
    }

    function cargarArchivo(input, tipo){
        let contrato = 1
        if(tipo == 3){
            if($('#fk_contratos').val() == 1){
                toastr.error("Para el caso de los AO debes escoger un contrato")
                return
            }else{
                contrato = $('#fk_contratos').val()
            }
        }
        let archivo = input.files[0]
        let ext = archivo.name.split('.').pop();
        if(ext == 'xls' || ext == 'xlsx'){
            if(confirm('Desea cargar el archivo?')){
                toastr.info('Por favor espere', 'Cargando...', {timeOut: 0})
                var fd = new FormData();
                fd.append('objeto','elementos')
                fd.append('metodo','cargar')
                fd.append('datos[tipo]',tipo)
                fd.append('datos[contrato]',contrato)
                fd.append("archivo", input.files[0])
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
                            toastr.success(respuesta.mensajeError)
                        }else{
                            toastr.error(respuesta.mensajeError)
                        }                       
                    },
                    error: function(xhr, status){
                        console.log('Ocurrio un error')
                    }
                })
            }
        }else{
            toastr.error('Tipo de archivo no permitido, el archivo debe ser extensión xls')
        }
    }
</script>
</body>
</html>