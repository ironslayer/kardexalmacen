<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="row">
                <div class="col-4">
                    <div class="text-center d-flex">
                       
                        <a href="<?php echo base_url() ?>item" class="btn btn-warning"><i class="fa-solid fa-arrow-rotate-left"></i> Regresar</a>

                    </div>
                </div>
            </div>

            <br/>

            <div class="table-responsive">
                <table id="tabla_db" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Costo Unitario</th>
                            <th>Importe</th>
                            <th width="5%">Reponer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $dato){ ?>
                            
                            <tr id="<?php echo $dato['id_item']; ?>">
                                <td> <?php echo $dato['id_item']; ?> </td>
                                <td> <?php echo $dato['descripcion']; ?> </td>
                                <td> <?php 
                                foreach($datos2 as $dato2){
                                    if($dato2['id_unidadmedida']==$dato['id_unidadmedida']){
                                        echo $dato2['nombre_unidad'];
                                    }
                                }
                                 ?> 
                                </td>
                                <td> <?php echo $dato['cantidad']; ?> </td>
                                <td> <?php echo $dato['costo_unitario']; ?> </td>
                                <td> <?php echo $dato['importe']; ?> </td>


                                <td> <a data-id="<?php echo $dato['id_item']; ?>" class="btn btn-primary btnReturn"><i class="fas fa-arrow-alt-circle-up"></i></a></td>


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
                    var proveedor_id = $(this).attr('data-id');
                    $.get('<?php echo base_url() ?>item/reingresar/'+proveedor_id, function (data){
                        $('#tabla_db tbody #'+proveedor_id).remove();
                        // miTabla.ajax.reload();
                    })
                    // miTabla.ajax.reload();

                    // alert(proveedor_id);
                });
                // reaload table    
                // miTabla.ajax.reload();
            
               
            } );
    </script>
