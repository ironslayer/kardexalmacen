<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <?php if(isset($validation)){ ?>
            <div class="alert alert-danger">
            <?php echo $validation->listErrors(); ?>
            </div> 
            <?php } ?>

            <form action="<?php echo base_url(); ?>tentrada/insertar" method="post" autocomplete="off">

                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-6">

                            <label for="">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" autofocus required />

                        </div>
                    </div>
   
                </div>
                <div class="form-control-plaintext">
                    <a href="<?php echo base_url(); ?>tentrada" class="btn btn-primary">Regresar</a>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>


            </form>

        </div>
    </main>