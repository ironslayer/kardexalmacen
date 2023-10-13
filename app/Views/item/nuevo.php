<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <?php if(isset($validation)){ ?>
            <div class="alert alert-danger">
            <?php echo $validation->listErrors(); ?>
            </div> 
            <?php } ?>

            <form action="<?php echo base_url(); ?>item/insertar" method="post" autocomplete="off">

 
                <!-- primera fila -->
                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo set_value('descripcion'); ?>" autofocus required />

                        </div>

                    </div>
                </div>
                <!-- segunda fila -->

                <div class="form-control-plaintext">
                    <div class="row">

                        <div class="col-12 col-sm-6">

                            <label for="">Unidad</label>
                            <select name="id_unidadmedida" id="id_unidadmedida" class="form-select" required>
                                <option value="">Seleccionar unidad</option>
                                <?php foreach ($unidades as $unidad) { ?>
                                    <option value="<?php echo $unidad['id_unidadmedida']; ?>"><?php echo $unidad['nombre_unidad']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>
                <!-- tercera fila -->
                <div class="form-control-plaintext">
                    <div class="row">

                        <div class="col-12 col-sm-6">

                            <label for="">Producto/Categoria</label>
                            <select name="id_producto" id="id_producto" class="form-select" required>
                                <option value="">Seleccionar producto</option>
                                <?php foreach ($productos as $producto) { ?>
                                    <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre_producto']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>



                <div class="form-control-plaintext">
                    <a href="<?php echo base_url(); ?>item" class="btn btn-primary">Regresar</a>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>


            </form>

        </div>
    </main>