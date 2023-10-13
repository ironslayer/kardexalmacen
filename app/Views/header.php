<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Kardex de Almacen</title>
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/style.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>js/all.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Kardex de Almacén</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form> -->
        <!-- Rol de usuario-->
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <!-- primer subnav -->

                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#subMovimiento" aria-expanded="false" aria-controls="subMovimiento">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-shopping-basket"></i></div>
                            Movimientos
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="subMovimiento" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo base_url(); ?>entrada">Entrada al Almacén</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>salida">Salida del Almacén</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>tipoentrada">Tipo de Ingreso</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>tiposalida">Tipo de Egreso</a>
                            </nav>
                        </div>

                        <!-- segundo subnav -->


                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#subItem" aria-expanded="false" aria-controls="subItem">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-truck"></i></div>
                            Items
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="subItem" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo base_url(); ?>item">Items</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>producto">Productos/Categorias</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>unidades">Unidad de Medida</a>
                            </nav>
                        </div>


                        <!-- tercer subnav -->

                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#subAdministracion" aria-expanded="false" aria-controls="subAdministracion">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                            Administración
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="subAdministracion" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo base_url(); ?>usuario">Personal</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>proveedor">Proveedor</a>
                            </nav>
                        </div>

                        <!-- cuarto subnav -->

                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#subReportes" aria-expanded="false" aria-controls="subReportes">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            Reportes
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="subReportes" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo base_url(); ?>producto">Reporte1</a>
                                <a class="nav-link" href="<?php echo base_url(); ?>unidades">Reporte2</a>
                            </nav>
                        </div>

                        <!-- quinto subnav -->

                        <a class="nav-link" href="<?php echo base_url(); ?>graficos">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-line"></i></div>Gráficos
                        </a>

                        <!-- ------ -->

                    </div>


                </div>

            </nav>
        </div>