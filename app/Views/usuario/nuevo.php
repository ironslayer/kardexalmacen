<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            
            <div class="text-center">
                <h4 class="mt-4"><?php echo $titulo; ?></h4>
            </div>

            

            <div class="row justify-content-center">

            <form class="col-12 col-sm-6 p-4 rounded bordered_form" action="<?php echo base_url(); ?>usuario/insertar" method="post" autocomplete="off">


                <div class="text-center">

                    <?php if (isset($validation)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $validation->listErrors(); ?>
                        </div>
                    <?php } ?>

                </div>


                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 ">

                            <label for="">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. CARLOS PEREZ GALVEZ" value="<?php echo set_value('nombre'); ?>" autofocus required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ">

                            <label for="">Carnet</label>
                            <input type="text" class="form-control" id="ci" name="ci" placeholder="Ej. 7865453" value="<?php echo set_value('ci'); ?>" required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ">
                            <label for="cargo">Cargo</label>
                            <select class="form-select" id="cargo" name="cargo" required>
                                <option value="">Seleccionar cargo</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Mesero">Mesero</option>
                                <option value="Pizzero">Pizzero</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ">

                            <label for="">Usuario</label>
                            <input type="text" class="form-control" id="usuario" placeholder="Ej. carlos365" name="usuario" value="<?php echo set_value('usuario'); ?>" required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ">

                            <label for="">Contraseña</label>
                            <input type="password" class="form-control" id="password" placeholder="Introduzca contraseña" name="password" value="<?php echo set_value('password'); ?>" required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 ">

                            <label for="">Repite Contraseña</label>
                            <input type="password" class="form-control" id="repassword" placeholder="Repita la contraseña" name="repassword" value="<?php echo set_value('repassword'); ?>" required />

                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="form-control-plaintext">
                    <a href="<?php echo base_url() ?>usuario" class="btn btn-warning"><i class="fa-solid fa-arrow-rotate-left"></i> Regresar</a>

                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-plus"></i> Agregar</button>
                    </div>
                </div>


            </form>

            </div>

        </div>
    </main>