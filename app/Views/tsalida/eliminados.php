<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div>
                <p>
     
                    <a href="<?php echo base_url() ?>tsalida" class="btn btn-warning">Tipos de Salida</a>

                </p>
            </div>
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="5%"></th>


                        </tr>
                    </thead>    


                    <tbody>
                        <?php foreach($datos as $dato){ ?>
                            
                            <tr>
                                <td> <?php echo $dato['id_tiposalida']; ?> </td>
                                <td> <?php echo $dato['nombre_salida']; ?> </td>
    


                                <td> <a href="#" data-href="<?php echo base_url().'tsalida/reingresar/'.$dato['id_tiposalida']; ?>" data-bs-toggle="modal" data-bs-target="#modalConfirma" title="Reingresar registro" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-up"></i></a></td>




                            </tr>

                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </main>

        <!-- modal -->
        <div class="modal fade" id="modalConfirma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reingresar registro</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Desea reingresar este registro?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary btn-ok">Si</a>
            </div>
            </div>
        </div>
    </div>