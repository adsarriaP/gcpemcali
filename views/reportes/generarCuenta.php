<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Generar cuenta personal
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="elementos/misElementos/">Inicio</a></li>
                        <li class="breadcrumb-item active">Generar cuenta</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card card-outline card-primary">
                        <div class="card-body">
                            <form id="formulario">
                                <div class="form-group">
                                    <label>Registro</label>
                                    <input type="text" name="valor" class="form-control" required="required">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8" id="contenido"></div>
            </div>
        </div>
    </section>
</div>

<?php require('views/footer.php');?>

<script type="text/javascript">
    var id
    var boton
    function init(info){
        //Buscar usuario
        $('#formulario').on('submit', function(e){
            e.preventDefault()
            $('#contenido').empty()
            let datos = parsearFormulario($(this))
            cargarRegistros({info: {registro: datos.valor}}, function(){
                console.log('Se cargo...')
            })
        })
    }

    function cargarRegistros(datos, callback){
        enviarPeticion('usuarios', 'getUsuario', datos, function(r){
            let contenido = ''
            r.data.map(registro => {
                contenido = `<div class="card card-outline card-primary">
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                                <th>Dependencia</th>
                                                <td class="p-0">
                                                    <table class="table table-sm table-bordered m-0">
                                                        <tr>
                                                            <th>Gerencia</th>
                                                            <td>${registro.gerencia}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Dependencia</th>
                                                            <td>${registro.dependencia}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Unidad</th>
                                                            <td>${registro.unidad}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nombre</th>
                                                <td>${registro.nombre}</td>
                                            </tr>
                                            <tr>
                                                <th>Rol</th>
                                                <td>${registro.rol}</td>
                                            </tr>
                                            <tr>
                                                <th>login</th>
                                                <td>${registro.login}</td>
                                            </tr>
                                            <tr>
                                                <th>Registro</th>
                                                <td>${registro.registro}</td>
                                            </tr>
                                            <tr>
                                                <th>Cédula</th>
                                                <td>${registro.cedula}</td>
                                            </tr>
                                            <tr>
                                                <th>Correo</th>
                                                <td>${registro.correo}</td>
                                            </tr>
                                            <tr>
                                                <th>Telefono</th>
                                                <td>${registro.telefono}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-4">
                                        </div>
                                        <div class="col-4">
                                            <a class="btn btn-success btn-block" href="elementos/generar/${registro.id}" target="_blank">
                                                Cuenta personal
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>`
            })            
            $('#contenido').html(contenido)
            callback()
        })
    }
</script>
</body>
</html>