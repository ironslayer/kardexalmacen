<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <div class="text-center">
                <h4 class="mt-4"><?php echo $titulo; ?></h4>
            </div>



            <div class="row justify-content-center">

                <form class="col-12 col-sm-6 p-4 rounded bordered_form" action="<?php echo base_url(); ?>usuario/actualizar" method="post" autocomplete="off">

                    <div class="text-center">

                        <?php if (isset($validation)) { ?>
                            <div class="alert alert-danger">
                                <?php echo $validation->listErrors(); ?>
                            </div>
                        <?php } ?>

                    </div>

                    <input type="hidden" value="<?php echo $datos['id_usuario']; ?>" name="id" />


                    <div class="form-control-plaintext">
                        <div class="row">
                            <div class="col-12">

                                <label for="">Nombre</label>
                                <input type="text" placeholder="Ej. CARLOS PEREZ GALVEZ" class="form-control" id="nombre" name="nombre" value="<?php echo $datos['nombre_usuario']; ?>" autofocus required />

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                <label for="">Carnet</label>
                                <input type="text" placeholder="Ej. 7865453" class="form-control" id="ci" name="ci" value="<?php echo $datos['ci']; ?>" required />

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                <label for="">Cargo</label>
                                <select class="form-select" id="cargo" name="cargo" required>
                                    <option value="">Seleccionar cargo</option>

                                    <option value="Administrador" <?php
                                                                    if ('Administrador' == $datos['cargo']) {
                                                                        echo 'selected';
                                                                    }

                                                                    ?>>Administrador</option>
                                    <option value="Mesero" <?php
                                                            if ('Mesero' == $datos['cargo']) {
                                                                echo 'selected';
                                                            }

                                                            ?>>Mesero</option>
                                    <option value="Pizzero" <?php
                                                            if ('Pizzero' == $datos['cargo']) {
                                                                echo 'selected';
                                                            }

                                                            ?>>Pizzero</option>
                                </select>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                <label for="">Usuario</label>
                                <input type="text" placeholder="Ej. carlos365" class="form-control" id="usuario" name="usuario" value="" required />

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

                                <label for="">Repite Contraseña</label>
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


                </form>

            </div>

        </div>
    </main>