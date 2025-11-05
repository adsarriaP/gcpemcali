<?php require('views/header.php');?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 id="codigo"></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="activos/misactivos/">Inicio</a></li>
                        <li class="breadcrumb-item active">OLX</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <img id="imagen" src="fotos/default.jpg" class="card-img-top" alt="...">
                        <div class="card-body text-center">                            
                            <label class="btn btn-primary" for="foto" style="cursor: pointer;">
                                <input id="foto" onChange="cargarFoto()" type="file" accept="image/*" style="display:none;"/>
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title" id="nombre"></h3>
                        </div>
                        <div class="card-body">
                            <form id="formularioPublicacion">
                                <div class="form-group">
                                    <label for="fk_olx_categorias">Categoria</label>
                                    <select class="form-control" name="fk_olx_categorias" id="fk_olx_categorias" required="required"></select>
                                </div>
                                <div class="form-group">
                                    <label for="texto">Publicación</label>
                                    <textarea class="form-control" rows="5" name="texto" id="texto" required="required"></textarea>
                                </div>                                
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-success btn-submit" id="botonGuardarPublicacion" form="formularioPublicacion">Crear publicación</button>
                            <button type="submit" class="btn btn-secondary btn-submit" id="botonActualizarPublicacion" form="formularioPublicacion">Actualizar publicación</button>
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
    var boton = ''
    var idActivo = <?=$parametros[0]?>;
    function init(info){
        //Poner imagen
        $('#imagen').attr("src",`fotos/foto_${idActivo}.jpg`);

        //LLenar información del activo
        enviarPeticion('activos', 'select', {info: {id: idActivo}}, function(r){
            $('#codigo').text(`Código: ${r.data[0].codigo}`)
            $('#nombre').text(r.data[0].descripcion)
        })

        //Llenar categorias
        llenarSelectCallback('olxCategorias', 'select', {info:{estado: 'activo'}, orden: 'nombre'}, 'fk_olx_categorias', 'nombre', 2, 'Seleccione...', 'id', function(){
            //Cargar información base
            enviarPeticion('olxPublicaciones', 'select', {info: {fk_activos: idActivo}}, function(r){
                if(r.data.length == 0){
                    $('#botonGuardarPublicacion').show()
                    $('#botonActualizarPublicacion').hide()
                }else{
                    id = r.data[0].id
                    $('#botonGuardarPublicacion').hide()
                    $('#botonActualizarPublicacion').show()
                    $('#fk_olx_categorias').val(r.data[0].fk_olx_categorias)
                    $('#texto').val(r.data[0].texto)
                }
            })  
        })

        $('.btn-submit').on('click', function(){
            boton = $(this).attr('id')
        })

        $('#formularioPublicacion').on('submit', function(e){
            e.preventDefault()
            let datos = parsearFormulario($(this))
            datos.fk_activos = idActivo
            if(boton == 'botonGuardarPublicacion'){
                enviarPeticion('olxPublicaciones', 'insert', {info: datos}, function(r){
                    toastr.success('Se creo correctamente')
                    $('#botonGuardarPublicacion').hide()
                    $('#botonActualizarPublicacion').show()
                })
            }else{                
                enviarPeticion('olxPublicaciones', 'update', {info: datos, id: id}, function(r){
                    toastr.success('Se actualizó correctamente')
                })
            }
        })
    }

    function cargarFoto(){
        toastr.info('Por favor espere', 'Cargando...', {timeOut: 0})
        let d = new Date()
        let archivo = $('#foto')[0].files[0]
        let ext = archivo.name.split('.').pop()
        if(ext == 'jpg' || ext == 'JPG' || ext == 'jpeg'){
            //se carga la Foto
            var fd = new FormData()        
            fd.append('objeto','olxPublicaciones')
            fd.append('metodo','subirFoto')
            fd.append('datos[id]',idActivo)
            fd.append('file',archivo)
            $.ajax({
                url: 'api',
                type: 'POST',
                dataType: 'json',
                data: fd,
                contentType: false,
                processData: false,
                success: function(r){
                    toastr.clear()
                    if(r.ejecuto == true){
                        toastr.success(r.msg)
                        $('#imagen').attr("src",`fotos/foto_${idActivo}.jpg?ver=${d.getTime()}`);
                    }else{
                        toastr.error(r.msg)
                    }
                },
                error: function(xhr,status){
                    console.log('Disculpe, existio un problema procesando')
                }
            })
        }else{
            toastr.error("La foto debe ser extensión jpg")
        }
    }
</script>
</body>
</html>