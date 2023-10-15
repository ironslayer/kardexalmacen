<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <?php if(isset($validation)){ ?>
            <div class="alert alert-danger">
            <?php echo $validation->listErrors(); ?>
            </div> 
            <?php } ?>

            <form action="<?php echo base_url(); ?>usuario/actualizar" method="post" autocomplete="off">

            <input type="hidden" value="<?php echo $datos['id_usuario']; ?>" name="id"/>
            

                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $datos['nombre_usuario']; ?>" autofocus required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Carnet</label>
                            <input type="text" class="form-control" id="ci" name="ci" value="<?php echo $datos['ci']; ?>"  required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Cargo</label>
                            <select class="form-select" id="cargo" name="cargo" required>
                                <option value="">Seleccionar cargo</option>

                                <option value="Administrador"
                                <?php 
                                if('Administrador'==$datos['cargo']){
                                    echo 'selected';
                                } 

                                ?>
                                >Administrador</option>
                                <option value="Mesero" 
                                <?php 
                                if('Mesero'==$datos['cargo']){
                                    echo 'selected';
                                } 

                                ?>
                                >Mesero</option>
                                <option value="Pizzero" 
                                <?php 
                                if('Pizzero'==$datos['cargo']){
                                    echo 'selected';
                                } 

                                ?>
                                >Pizzero</option>
                            </select>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $datos['usuario']; ?>"  required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" value=""  required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Repite Contraseña</label>
                            <input type="password" class="form-control" id="repassword" name="repassword" value=""  required />

                        </div>
                    </div>
                    
                </div>
                <div class="form-control-plaintext">
                    <a href="<?php echo base_url(); ?>usuario" class="btn btn-primary">Regresar</a>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>


            </form>

        </div>
    </main>