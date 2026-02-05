<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-compatible" content="ie=edge">
    <meta name="author" content="Víctor Hugo Hernández">
    <meta name="copyright" content="GTI">
    <title>GCP</title>
    <base href="/gcp/">
    
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    
    <style>
        body.login-page {
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-flex-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100vw;
            min-height: 100vh;
            gap: 24px;
            position: relative;
        }
        .login-side-img {
            position: fixed;
            max-width: none;
            width: 48vw;
            min-width: 300px;
            max-height: 96vh;
            opacity: 0.14;
            object-fit: contain;
            pointer-events: none;
            z-index: 0;
            filter: blur(0.3px);
        }
        .login-side-img.left { top: 50%; transform: translateY(-50%); left: -10vw; }
        .login-side-img.right {
            top: 4vh;
            right: -10vw;
            transform: rotate(-18deg) scale(0.72);
            transform-origin: center center;
            opacity: 0.12;
        }
        @media (max-width: 991.98px) {
            .login-side-img { display: none; }
        }
        .login-box { 
            position: relative; 
            z-index: 3000;
            width: 440px;
            max-width: 92vw;
            margin: 0 auto;
        }
        @media (min-width: 1200px){ 
            .login-box { width: 500px; } 
        }
        .login-box .card {
            border: 0;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
            border-radius: .75rem;
            overflow: hidden;
            min-height: 460px;
        }
        .login-card-body { padding: 2rem 2rem; }
        .login-box .card-header { border-bottom: 0 !important; }
        
        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: .5rem 0;
        }
        .login-brand img {
            max-height: 84px;
            width: auto;
            object-fit: contain;
        }
        
        .login-title {
            text-align: center;
            font-family: inherit;
            font-weight: 700;
            color: #000000;
            font-size: 1.6rem;
            font-weight: 800;
            margin: .25rem 0 0.5rem;
        }

        .janus-title { display: block; gap: 0; align-items: baseline; letter-spacing: 0; font-size: 2rem; margin-bottom: 1.25rem; }
        .janus-letter { display:inline-block; color: rgba(0,0,0,0.85); transition: color 0.3s ease, transform 0.3s ease; font-weight:800; font-size:inherit; vertical-align:baseline; }
        .janus-dot { display:inline-block; color: #000000; margin: 0; padding: 0; font-size:inherit; vertical-align:baseline; }
        .janus-letter.active {
            color: #295c1e;
            transform: translateY(-2px) scale(1.01);
        }

        .features { margin-top: 0; }
        .features.wrap-right { max-width: 520px; margin-left: auto; }
        .feature-item { display:flex; align-items:flex-start; gap:1rem; margin-bottom:1.25rem; }
        .feature-item .icon { width:48px; height:48px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#1f4b1f; background: rgba(67,185,74,0.06); border: 2px solid rgba(67,185,74,0.12); flex:0 0 48px; }
        .feature-item h5 { margin:0 0 .25rem 0; font-size:1rem; }
        .feature-item p { margin:0; color:#607080; }
        
        .input-group-text.btn-toggle-pass {
            cursor: pointer;
            background: #fff;
        }
        .btn-success {
            border-radius: .5rem;
        }

        .form-control:focus {
            border-color: #43b94a !important;
            box-shadow: 0 0 0 2px rgba(67,185,74,0.15) !important;
            outline: 2px solid #43b94a !important;
            caret-color: #43b94a !important;
        }
        .input-group-text.btn-toggle-pass:focus {
            border-color: #43b94a !important;
            box-shadow: 0 0 0 2px rgba(67,185,74,0.15) !important;
            outline: 2px solid #43b94a !important;
        }
        
        .card-kit {
            border: none !important;
            border-radius: 16px !important;
            background: #ffffff !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06) !important;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .card-kit:hover {
            border-color: #43b94a !important;
            box-shadow: 0 12px 32px rgba(0,0,0,0.08) !important;
        }
        .card-header-clean {
            background: #ffffff !important;
            border-bottom: 0 !important;
        }
        .login-card-body { background: #ffffff; }
        .btn-kit {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }
        .btn-kit-dark {
            background: #295c1e !important;
            color: #fff !important;
            border: none !important;
            border-radius: 50px !important;
            font-weight: 700;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px rgba(41,92,30,0.10);
            padding: 12px 0;
            transition: background 0.2s, color 0.2s;
        }
        .btn-kit-dark:hover, .btn-kit-dark:focus {
            background: #43b94a !important;
            color: #fff !important;
        }
        @media (min-width: 992px) {
            .login-box {
                transform: translateX(-32px);
                transition: transform 0.25s ease;
            }
        }
        @media (min-width: 1400px) {
            .login-box {
                transform: translateX(-56px);
            }
        }
        .bg-pajaro-right {
            position: fixed;
            top: 12%;
            right: -2vw;
            width: 820px;
            max-width: 62vw;
            opacity: 0.14;
            z-index: 0;
            object-fit: contain;
            pointer-events: none;
            transform: translateY(-6%);
        }
        .bg-pajaro-left {
            position: fixed;
            top: 52%;
            left: -2vw;
            width: 880px;
            max-width: 66vw;
            opacity: 0.13;
            z-index: 0;
            object-fit: contain;
            pointer-events: none;
            transform: translateY(-50%);
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-flex-container container-fluid">
        <div class="row w-100 align-items-center">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="px-2 features-wrap">
                    <div class="features wrap-right">
                        <h2 class="display-4 font-weight-bold janus-title">
                            <span class="janus-letter" data-letter="G">G</span>
                            <span class="janus-dot">.</span>
                            <span class="janus-letter" data-letter="C">C</span>
                            <span class="janus-dot">.</span>
                            <span class="janus-letter" data-letter="P">P</span>
                        </h2>
                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-database"></i></div>
                            <div>
                                <h5>Gestión Centralizada de Activos</h5>
                                <p>Administra todo el inventario de elementos patrimoniales en un solo lugar, con trazabilidad completa de asignaciones, traslados y reintegros.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-user-check"></i></div>
                            <div>
                                <h5>Control de Responsabilidad</h5>
                            <p>Asigna responsables a cada elemento, gestiona solicitudes de traslado y mantén un histórico detallado de todos los movimientos.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-history"></i></div>
                            <div>
                                <h5>Trazabilidad e Histórico</h5>
                                <p>Registra automáticamente cada cambio realizado en los elementos, incluyendo usuario, fecha, motivo y acción ejecutada.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="icon"><i class="fas fa-chart-line"></i></div>
                            <div>
                                <h5>Reportes y Dashboard</h5>
                                <p>Genera reportes detallados de productividad, solicitudes y estados de elementos para una mejor toma de decisiones.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex justify-content-center align-items-stretch">
                <div class="login-box">
                    <div class="card card-kit">
                        <div class="card-header text-center login-brand card-header-clean" style="background:#fff;">
                            <a href="#">
                                <img src="dist/img/logo-emcali.webp" alt="Logotipo EMCALI" style="display:block;max-height:84px;">
                            </a>
                        </div>

                        <div class="card-body login-card-body">
                            <p class="login-title">Iniciar Sesión</p>

                            <form id="formulario" action="#" method="post">
                                <div class="form-group">
                                    <label for="inputUsuario" class="font-weight-bold mb-1">Usuario o Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="login" placeholder="Usuario o Email" autocomplete="username" autofocus id="inputUsuario">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword" class="font-weight-bold mb-1">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="Contraseña" autocomplete="current-password" id="inputPassword">
                                        <div class="input-group-append">
                                            <div class="input-group-text btn-toggle-pass" id="togglePassword" title="Ver/Ocultar contraseña">
                                                <span class="fas fa-eye" id="iconEye"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col">
                                        <button type="submit" class="btn btn-kit btn-kit-dark btn-block" id="botonEnviar">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Ingresar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <img src="dist/img/PajaroEMCALIcolor.png" alt="Pajaro EMCALI Derecha" class="bg-pajaro-right">
        <img src="dist/img/PajaroEMCALIcolor%20%282%29.png" alt="Pajaro EMCALI Izquierda" class="bg-pajaro-left">
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/funciones.js"></script>
    
    <script>
        // Animación de las letras
        $(document).ready(function() {
            var letters = $('.janus-letter');
            var index = 0;
            var interval = 800;

            function animateLetters() {
                letters.removeClass('active');
                $(letters[index]).addClass('active');
                index = (index + 1) % letters.length;
            }

            setInterval(animateLetters, interval);
            animateLetters();
        });

        // Toggle password visibility
        $('#togglePassword').on('click', function() {
            var passwordField = $('#inputPassword');
            var icon = $('#iconEye');
            
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Form submission
        $('#formulario').on('submit', function(e) {
            e.preventDefault();
            $('#botonEnviar').prop('disabled', true);
            var datos = parsearFormulario('formulario');
            var peticion = enviarPeticionPura('usuarios', 'login', datos);
            
            peticion.then(function(resultado) {
                if (resultado.estado) {
                    var rol = resultado.datos.rol || '';
                    var nombreCompleto = resultado.datos.nombre_completo || 'Usuario';
                    
                    sessionStorage.setItem('mostrarBienvenida', 'true');
                    sessionStorage.setItem('nombreUsuario', nombreCompleto);
                    
                    if (rol === 'PS') {
                        window.location.href = 'ps/informacion/';
                    } else {
                        window.location.href = 'main/inicio/';
                    }
                } else {
                    toastr.error(resultado.mensaje);
                    $('#botonEnviar').prop('disabled', false);
                }
            }).catch(function(error) {
                toastr.error('Error al intentar iniciar sesión');
                $('#botonEnviar').prop('disabled', false);
            });
        });
    </script>
</body>
</html>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/funciones.js"></script>
    <script type="text/javascript"> 
        $(function(){
            //Se crea evento para recibir ingreso
            $('#formulario').on('submit', function(e){
                e.preventDefault()
                $('#botonEnviar').prop('disabled', true)
                toastr.info("Autenticando...")
                let datos = parsearFormulario($(this))
                enviarPeticionPura('usuarios', 'login', {info:datos}, function(r){                    
                    if(r.ejecuto == true){
                        window.location.href = 'elementos/misElementos/'
                    }else{
                        toastr.remove()
                        toastr.error(r.mensajeError)
                        $('#botonEnviar').prop('disabled', false)
                    }
                })
            })
        })
    </script>
</body>
</html>