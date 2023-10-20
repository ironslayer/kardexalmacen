<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="row">
                <div class="col-4">
                    <div class="text-center d-flex">
                       
                        <a href="<?php echo base_url() ?>producto" class="btn btn-warning"><i class="fa-solid fa-arrow-rotate-left"></i> Regresar</a>

                    </div>
                </div>
            </div>

            <br/>

            <div class="table-responsive">
                <table id="tabla_db" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="5%">Reponer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $dato){ ?>
                            
                            <tr id="<?php echo $dato['id_producto']; ?>">
                                <td> <?php echo $dato['id_producto']; ?> </td>
                                <td> <?php echo $dato['nombre_producto']; ?> </td>


                                <td> <a data-id="<?php echo $dato['id_producto']; ?>" class="btn btn-primary btnReturn"><i class="fas fa-arrow-alt-circle-up"></i></a></td>


                            </tr>

                        <?php } ?>
                    </tbody>
                </table>

            </div>

            
        </div>
    </main>

    <script>
            $(document).ready(function() {
                var miTabla= $('#tabla_db').DataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Unidades",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar: ",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente <i class='fas fa-angle-double-right'></i>",
                            "previous": "<i class='fas fa-angle-double-left'></i> Anterior"
                        }
                    }
                });

                $('body').on('click', '.btnReturn', function(){
                    var entrada_id = $(this).attr('data-id');
                    $.get('<?php echo base_url() ?>producto/reingresar/'+entrada_id, function (data){
                        $('#tabla_db tbody #'+entrada_id).remove();
                        // miTabla.ajax.reload();
                    })
                    // miTabla.ajax.reload();

                    // alert(entrada_id);
                });
                // reaload table    
                // miTabla.ajax.reload();
            
               
            } );
    </script>
