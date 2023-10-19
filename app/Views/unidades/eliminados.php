<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="row">
                <div class="col-4">
                    <div class="text-center d-flex">
                       
                        <a href="<?php echo base_url() ?>unidades" class="btn btn-warning"><i class="fa-solid fa-arrow-rotate-left"></i> Regresar</a>

                    </div>
                </div>
            </div>

            <br/>

            <div class="table-responsive">
                <table id="tabla_db" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $dato){ ?>
                            
                            <tr id="<?php echo $dato['id_unidadmedida']; ?>">
                                <td> <?php echo $dato['id_unidadmedida']; ?> </td>
                                <td> <?php echo $dato['nombre_unidad']; ?> </td>


                                <td> <a data-id="<?php echo $dato['id_unidadmedida']; ?>" class="btn btn-primary btnReturn"><i class="fas fa-arrow-alt-circle-up"></i></a></td>


                            </tr>

                        <?php } ?>
                    </tbody>
                </table>

            </div>

            
        </div>
    </main>

    <script>
            $(document).ready(function() {
                $('#tabla_db').DataTable();

                $('body').on('click', '.btnReturn', function(){
                    var unidad_id = $(this).attr('data-id');
                    $.get('<?php echo base_url() ?>unidades/reingresar/'+unidad_id, function (data){
                        $('#tabla_db tbody #'+unidad_id).remove();
                    })
                    // alert(unidad_id);
                });
               
            } );
    </script>
