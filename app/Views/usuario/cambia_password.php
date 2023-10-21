<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="text-center">
                <h4 class="mt-4"><?php echo $titulo; ?></h4>
            </div>



            <div class="row justify-content-center">

                <form class="col-12 col-sm-6 p-4 rounded bordered_password" action="<?php echo base_url(); ?>usuario/actualizar_password" method="post" autocomplete="off">

                    <div class="text-center">

                        <?php if (isset($validation)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $validation->listErrors(); ?>
                            </div>
                        <?php } ?>

                    </div>

                    <div class="form-control-plaintext">
                        <div class="row">
                            <div class="col-12">

                                <label for="">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $usuario['usuario']; ?>" disabled />

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                <label for="">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre_usuario']; ?>" disabled />

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">

                                <label for="">Contraseña</label>
                                <input type="password" placeholder="Introduzca contraseña" class="form-control" id="password" name="password" value="" required />

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                <label for="">Confirma contraseña</label>
                                <input type="password" placeholder="Repita la contraseña" class="form-control" id="repassword" name="repassword" value="" required />

                            </div>
                        </div>

                    </div>
                    <div class="text-center">
                        <div class="form-control-plaintext">
                            <a href="<?php echo base_url() ?>usuario" class="btn btn-warning"><i class="fa-solid fa-arrow-rotate-left"></i> Regresar</a>

                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-plus"></i> Guardar</button>
                        </div>
                    </div>

                    <div class="text-center">
                        <?php if (isset($mensaje)) { ?>
                            <div class="alert alert-success">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>
                    </div>

                </form>
            </div>

        </div>
    </main>