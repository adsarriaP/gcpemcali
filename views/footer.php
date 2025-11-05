	<footer class="main-footer bg-light">
        <div class="row">
            <div class="col-md-6">
                <strong>Copyright &copy; 2024 <a href="https://www.emcali.com.co/" target="_blank">EMCALI EICE ESP</a>.</strong> All rights reserved.
            </div>            
            <div class="col-md-6 text-right text-muted">
                 Desarrollado por <a href="https://www.emcali.com.co/" target="_blank"><img src="dist/img/loguito.png" alt="Emcali"></a>
            </div>
        </div>
	</footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Custom number -->
<script src="plugins/customd-jquery-number/jquery.number.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- moment -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/moment/locale/es.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/funciones.js"></script>
<script type="text/javascript">
    var tramites = ['', 'Asignación', 'Reasignación', 'Traspaso', 'Reintegro']
    var tipos = ['', 'Activo', 'Controlado', 'Arrendamiento Operativo']
    var clases = ['', '', 'Maquinaria y equipo', 'Muebles y enseres', 'Equipos de computo', 'Equipos de comunicaciones', 'Vehículos', 'Equipos de laboratorio', 'Sillas']
    var clasesIconos = ['', '', '<i class="fas fa-tools"></i>', '<i class="fas fa-box-open"></i>', '<i class="fas fa-laptop"></i>', '<i class="fas fa-network-wired"></i>', '<i class="fas fa-car-side"></i>', '<i class="fas fa-vial"></i>', '<i class="fas fa-chair"></i>']
    var estados = ['','Aceptar elemento', 'Recoger almacen','Aprobar jefe','Realizar inspección','Aprobar inspección','Llevar a almacen','Actualizar SAP','Actualizar carpeta','Reposición','Ejecutada','Anulada']
    var colores = ['secondary','warning','warning','warning','warning','warning','warning','warning','warning','warning','success','danger']
	$(function(){
        enviarPeticion('helpers', 'getSession', {1:1}, function(r){
            if(r.data.length == 0){
                window.location.href = 'main/login/'
            }else{
                $('#menu_user').text(r.data.usuario.login)
                let menu
                let menuAdmin = `<li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-tools"></i>
                                        <p>
                                            Configuración
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="configuracion/usuarios/" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Usuarios</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="configuracion/dependencias/" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Dependencias</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="configuracion/plantas/" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Plantas</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="configuracion/contratos/" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Contratos</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="elementos/cargar/" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Cargar</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>`
                let menuCuentaPersonal = `<li class="nav-item has-treeview">
                                            <a href="#" class="nav-link">
                                                <i class="fas fa-diagnoses"></i>
                                                <p>
                                                    Cuenta personal
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <li class="nav-item">
                                                    <a href="elementos/misElementos/" class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>Mis elementos</p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="elementos/misSolicitudes/" class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>Mis solicitudes</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>`
                let menuElementosAbre = `<li class="nav-item has-treeview">
                                        <a href="#" class="nav-link">
                                            <i class="fas fa-box"></i>
                                            <p>
                                                Elementos
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">`
                let menuElementosDep =      `<li class="nav-item">
                                                <a href="elementos/porDependencias/" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Por dependencia</p>
                                                </a>
                                            </li>`
                let menuElementosCon =      `<li class="nav-item">
                                                <a href="elementos/porContratos/" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Por contrato</p>
                                                </a>
                                            </li>`
                let menuElementosDM =      `<li class="nav-item">
                                                <a href="elementos/datosMaestros/" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Datos maestros</p>
                                                </a>
                                            </li>`
                let menuElementosCierra =`</ul>
                                    </li>`
                let menuAprobacionesAbre = `<li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-project-diagram"></i>
                                                    <p>
                                                        Aprobaciones
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="aprobaciones/aceptarElemento/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Aceptar elemento</p>
                                                        </a>
                                                    </li>`
                let menuRecoger =                    `<li class="nav-item">
                                                        <a href="aprobaciones/recogerElemento/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Entregar elemento</p>
                                                        </a>
                                                    </li>`
                let menuJefe =                      `<li class="nav-item">
                                                        <a href="aprobaciones/aprobarJefe/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Aprobar jefe</p>
                                                        </a>
                                                    </li>`
                let menuInspeccion =                `<li class="nav-item">
                                                        <a href="aprobaciones/inspeccion/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Inspecciones</p>
                                                        </a>
                                                    </li>`
                let menuValidador =                 `<li class="nav-item">
                                                        <a href="aprobaciones/aprobarInspeccion/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Aprobar inspección</p>
                                                        </a>
                                                    </li>`
                let menuAlmacen =                   `<li class="nav-item">
                                                        <a href="aprobaciones/recibirAlmacen/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Recibir almacen</p>
                                                        </a>
                                                    </li>`
                let menuActivos =                   `<li class="nav-item">
                                                        <a href="aprobaciones/actualizarSAP/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Actualizar SAP</p>
                                                        </a>
                                                    </li>`
                let menuCarpeta =                   `<li class="nav-item">
                                                        <a href="aprobaciones/actualizarCarpeta/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Actualizar carpeta</p>
                                                        </a>
                                                    </li>`
                let menuAprobacionesCierra =    `</ul>
                                            </li>`
                let menuDashboardsAbre = `  <li class="nav-item has-treeview">
                                                <a href="#" class="nav-link">
                                                    <i class="fas fa-chart-bar"></i>
                                                    <p>
                                                        Dashboards
                                                        <i class="right fas fa-angle-left"></i>
                                                    </p>
                                                </a>
                                                <ul class="nav nav-treeview">`
                let menuDashboardsGeneral =         `<li class="nav-item">
                                                        <a href="reportes/dashboard/" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>General</p>
                                                        </a>
                                                    </li>`
                let menuDashboardsContrato =        `<li class="nav-item">
                                                        <a href="reportes/contratos/2" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Tablero contrato</p>
                                                        </a>
                                                    </li>`
                let menuDashboardsCierra =`     </ul>
                                            </li>`
                let menuBuscarElemento =`<li class="nav-item">
                                            <a href="elementos/buscar/" class="nav-link">
                                                <i class="fab fa-searchengin"></i>
                                                <p>Buscar Elemento</p>
                                            </a>
                                        </li>`                
                let menuBuscarSolicitud =`<li class="nav-item">
                                            <a href="solicitudes/buscar/" class="nav-link">
                                                <i class="fas fa-search-location"></i>
                                                <p>Buscar solicitud</p>
                                            </a>
                                        </li>`
                let menuGenerarCuenta =`<li class="nav-item">
                                            <a href="reportes/generarCuenta/" class="nav-link">
                                                <i class="fas fa-file-alt"></i>
                                                <p>Generar Cuenta</p>
                                            </a>
                                        </li>`
                let menuEstadisticaContrato =`<li class="nav-item">
                                            <a href="reportes/contratos/2" class="nav-link">
                                                <i class="fas fa-chart-bar"></i>
                                                <p>Tablero contrato</p>
                                            </a>
                                        </li>`
                let menuReportes =      `<li class="nav-item">
                                            <a href="reportes/indice/" class="nav-link">
                                                <i class="far fa-file-excel"></i>
                                                <p>Reportes</p>
                                            </a>
                                        </li>`
                if(r.data.usuario.rol == 'Administrador'){
                    menu = menuAdmin + menuCuentaPersonal + menuElementosAbre + menuElementosDep + menuElementosCon + menuElementosDM + menuElementosCierra + menuAprobacionesAbre + menuRecoger + menuJefe + menuInspeccion + menuValidador + menuAlmacen +  menuActivos + menuCarpeta + menuAprobacionesCierra + menuDashboardsAbre + menuDashboardsGeneral + menuDashboardsContrato + menuDashboardsCierra + menuBuscarElemento + menuBuscarSolicitud + menuGenerarCuenta + menuReportes
                }else if(r.data.usuario.rol == 'Jefe'){
                    menu = menuCuentaPersonal + menuElementosAbre + menuElementosDep + menuElementosCon + menuElementosCierra + menuAprobacionesAbre + menuJefe + menuValidador + menuAprobacionesCierra + menuBuscarSolicitud + menuGenerarCuenta + menuReportes
                }else if(r.data.usuario.rol == 'Inspeccion'){
                    menu = menuCuentaPersonal + menuAprobacionesAbre + menuInspeccion + menuAprobacionesCierra + menuBuscarElemento + menuBuscarSolicitud + menuEstadisticaContrato
                }else if(r.data.usuario.rol == 'Validador'){
                    menu = menuCuentaPersonal + menuAprobacionesAbre + menuValidador + menuAprobacionesCierra + menuBuscarSolicitud
                }else if(r.data.usuario.rol == 'Almacen'){
                    menu = menuCuentaPersonal + menuAprobacionesAbre + menuRecoger + menuCarpeta + menuAprobacionesCierra + menuBuscarSolicitud + menuGenerarCuenta
                }else if(r.data.usuario.rol == 'Activos'){
                    menu = menuCuentaPersonal + menuAprobacionesAbre + menuRecoger + menuAlmacen + menuActivos + menuAprobacionesCierra + menuBuscarElemento + menuBuscarSolicitud + menuGenerarCuenta
                }else if(r.data.usuario.rol == 'GestorContrato'){
                    menu = menuCuentaPersonal + menuElementosAbre + menuElementosCon + menuElementosCierra + menuAprobacionesAbre + menuRecoger + menuAprobacionesCierra + menuBuscarElemento + menuBuscarSolicitud + menuReportes
                }else if(r.data.usuario.rol == 'MesaAyuda'){
                    menu = menuBuscarElemento + menuBuscarSolicitud + menuEstadisticaContrato
                }else if(r.data.usuario.rol == 'FacilitadorArea'){
                    menu = menuCuentaPersonal + menuAprobacionesAbre + menuAprobacionesCierra + menuBuscarElemento + menuBuscarSolicitud + menuGenerarCuenta
                }else{
                    menu = menuCuentaPersonal + menuAprobacionesAbre + menuAprobacionesCierra
                }
                $('#menu').html(menu)
                $('#salir').on('click', function(){
                    enviarPeticion('helpers', 'destroySession', {1:1}, function(r){
                        window.location.href = 'main/login/'
                    })
                })
            }
            init(r)
            $(':input[required]').css('box-shadow','1px 1px red')
        })
    })  
</script>