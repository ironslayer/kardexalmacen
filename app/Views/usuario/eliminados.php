<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="row">
                <div class="col-4">
                    <div class="text-center d-flex">
                       
                        <a href="<?php echo base_url() ?>usuario" class="btn btn-warning"><i class="fa-solid fa-arrow-rotate-left"></i> Regresar</a>

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
                            <th>Carnet</th>
                            <th>Cargo</th>
                            <th>Usuario</th>
                            <th width="5%">Reponer</th>


                        </tr>
                    </thead>    


                    <tbody>
                        <?php foreach($datos as $dato){ ?>
                            
                            <tr>
                                <td> <?php echo $dato['id_usuario']; ?> </td>
                                <td> <?php echo $dato['nombre_usuario']; ?> </td>
                                <td> <?php echo $dato['ci']; ?> </td>
                                <td> <?php echo $dato['cargo']; ?> </td>
                                <td> <?php echo $dato['usuario']; ?> </td>


                                <td> <a href="#" data-href="<?php echo base_url().'usuario/reingresar/'.$dato['id_usuario']; ?>" data-bs-toggle="modal" data-bs-target="#modalConfirma" title="Reingresar registro" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-up"></i></a></td>

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
        } );
    </script>

    