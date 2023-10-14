<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <?php if(isset($validation)){ ?>
            <div class="alert alert-danger">
            <?php echo $validation->listErrors(); ?>
            </div> 
            <?php } ?>

            <form action="<?php echo base_url(); ?>proveedor/actualizar" method="post" autocomplete="off">

            <input type="hidden" value="<?php echo $datos['id_proveedor']; ?>" name="id"/>
            

                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $datos['nombre_proveedor']; ?>" autofocus required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Contacto</label>
                            <input type="text" class="form-control" id="contacto" name="contacto" value="<?php echo $datos['contacto']; ?>"  required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $datos['direccion']; ?>"  required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo $datos['ciudad']; ?>"  required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $datos['telefono']; ?>"  required />

                        </div>
                    </div>
                    
                </div>
                <div class="form-control-plaintext">
                    <a href="<?php echo base_url(); ?>proveedor" class="btn btn-primary">Regresar</a>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>


            </form>

        </div>
    </main>