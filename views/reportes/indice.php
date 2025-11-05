<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Reportes
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Reportes</li>
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
                                        <tr>
                                            <th>Reporte</th>
                                            <th>Archivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Elementos obsoletos pendientes de llevar al almacén</td>
                                            <td>
                                                <a href="reportes/reportesObsoletosPendienteAlmacen/" class='btn btn-default' target="_blank">
                                                    <i class="fas fa-file-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Elementos obsoletos entregados al almacén</td>
                                            <td>
                                                <a href="reportes/reportesObsoletosEntregadosAlmacen/" class='btn btn-default' target="_blank">
                                                    <i class="fas fa-file-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Elementos obsoletos sin solicitud</td>
                                            <td>
                                                <a href="reportes/reportesObsoletosSinSolicitud/" class='btn btn-default' target="_blank">
                                                    <i class="fas fa-file-download"></i>
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
</script>
</body>
</html>