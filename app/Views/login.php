<?php 

    $user_session = session();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Kardex de Almacen</title>
    <!-- <link rel="icon" href="https://pos.codigosdeprogramacion.com/images/favicon.png" sizes="32x32"> -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/style.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>js/all.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/misestilos.css">

 

</head>

<body class="fondo_login centrar_todo">
    <?php print_r($user_session->nombre_usuario); ?>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                
                    
                
                <div class="container">
                    
                    <div class="row justify-content-center">
                        
                        <div class="col-lg-5">
                            <br/><br/><br/><br/>
                                <div class="col-lg-5">
                                    <img src="<?php echo base_url(); ?>assets/img/nombre_master_pizza.png" alt="nombre_master_pizza">
                                </div>
                            
                            <div class="card shadow-lg border-0 rounded-lg ">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">
                                        Kardex de Almacén
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" action=" <?php echo base_url(); ?>usuario/valida" autocomplete="off">
                                        <div class="form-group">
                                            <label for="usuario">Usuario</label>
                                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Ingresa tu usuario" />
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Contraseña</label>
                                            <input class="form-control" id="password" name="password" type="password" placeholder="Ingresa tu contraseña" />
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" type="submit">Ingresar</button>
                                        </div>

                                        <?php if (isset($validation)) { ?>
                                            <div class="alert alert-danger">
                                                <?php echo $validation->listErrors(); ?>
                                            </div>
                                        <?php } ?>

                                        <?php if (isset($error)) { ?>
                                            <div class="alert alert-danger">
                                                <?php echo $error; ?>
                                            </div>
                                        <?php } ?>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
    </div>
    <script src="<?php echo base_url(); ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>js/scripts.js"></script>
    <script src="<?php echo base_url(); ?>js/simple-datatables.min.js"></script>
    <script src="<?php echo base_url(); ?>js/datatables-simple-demo.js"></script>
</body>

</html>