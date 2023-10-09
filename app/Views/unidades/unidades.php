<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div>
                <p>
                    <a href="<?php echo base_url() ?>unidades/nuevo" class="btn btn-info">Agregar</a>
                    <a href="<?php echo base_url() ?>unidades/eliminados" class="btn btn-warning">Eliminados</a>

                </p>
            </div>
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="5%"></th>
                            <th width="5%"></th>

                        </tr>
                    </thead>    


                    <tbody>
                        <?php foreach($datos as $dato){ ?>
                            
                            <tr>
                                <td> <?php echo $dato['id_unidadmedida']; ?> </td>
                                <td> <?php echo $dato['nombre_unidad']; ?> </td>

                                <td> <a href="<?php echo base_url().'unidades/editar/'.$dato['id_unidadmedida']; ?>" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></a></td>

                                <td> <a href="<?php echo base_url().'unidades/eliminar/'.$dato['id_unidadmedida']; ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>




                            </tr>

                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </main>