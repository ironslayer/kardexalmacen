<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="row">
                <div class="col-4">
                    <div class="text-center d-flex">
                        <!-- Button add modal -->
                        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#modal_agregar" id="botonAgregar">
                            <i class="fa-solid fa-plus"></i> Agregar
                        </button>

                        <a href="<?php echo base_url() ?>unidades/eliminados" class="btn btn-warning"><i class="fa-solid fa-delete-left"></i> Eliminados</a>

                    </div>
                </div>
            </div>

            <br />

            <div class="table-responsive">
                <table id="tabla_db" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="5%">Editar</th>
                            <th width="5%">Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $dato) { ?>

                            <tr id="<?php echo $dato['id_unidadmedida']; ?>">
                                <td> <?php echo $dato['id_unidadmedida']; ?> </td>
                                <td> <?php echo $dato['nombre_unidad']; ?> </td>

                                <td> <a data-id="<?php echo $dato['id_unidadmedida']; ?>" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>

                                <td> <a data-id="<?php echo $dato['id_unidadmedida']; ?>" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>




                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>


    <!-- modal1 AGREGAR-->
    <div class="modal fade" id="modal_agregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Unidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>



                <form action=" <?php echo base_url(); ?>unidades/insertar" method="post" autocomplete="off" id="miFormulario">

                    <div class="modal-body">

                        <div class="moda-content">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" autofocus required placeholder="Ej. Kilogramo" />
                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="resetForm">Cancelar</button>
                            

                            <button type="submit" class="btn btn-success">Agregar</button>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- modal2 EDITAR -->
    <div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Unidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>



                <form action="<?php echo site_url('unidades/actualizar'); ?>" method="post" autocomplete="off" id="miFormulario_edit" name="miFormulario_edit">

                    <div class="modal-body">

                        <input type="hidden" name="txt_id" id="txt_id" />

                        <div class="moda-content">
                            <label for="txt_nombre">Nombre</label>
                            <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" autofocus required placeholder="Ej. Kilogramo" />
                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="resetFormEdit">Cancelar</button>
                            

                            <button type="submit" class="btn btn-success">Modificar</button>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- codigo para formulario y tabla -->
    <script>
        $(document).ready(function() {
            var miTabla = $('#tabla_db').DataTable({
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
            //AGREGAR
            $('#miFormulario').validate({
                rules: {
                    nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    }
                },
                messages: {
                    nombre: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    }
                },
                errorPlacement: function(error, element) {
                    error.css("color", "red"); // Cambia el color a rojo
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    var form_action = $("#miFormulario").attr("action");
                    $.ajax({
                        type: "POST",
                        url: form_action,
                        data: $("#miFormulario").serialize(),
                        dataType: "json",
                        success: function(respuesta) {
                            var unidad = '<tr id="' + respuesta.data.id_unidadmedida + '">';
                            unidad += '<td>' + respuesta.data.id_unidadmedida + '</td>';
                            unidad += '<td>' + respuesta.data.nombre_unidad + '</td>';
                            unidad += '<td> <a data-id="' + respuesta.data.id_unidadmedida + '" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>';
                            unidad += '<td> <a data-id="' + respuesta.data.id_unidadmedida + '" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>';
                            $('#tabla_db tbody').append(unidad);
                            $('#miFormulario')[0].reset();
                            $('#modal_agregar').modal('hide');
                            // miTabla.ajax.reload();

                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

            // RESET FORMULARIO AGREGAR

            $('#resetForm').on('click', function() {
                $('#miFormulario').validate().resetForm();
                $('#miFormulario')[0].reset();
            });

            // RESET FORMULARIO EDITAR

            $('#resetFormEdit').on('click', function() {
                $('#miFormulario_edit').validate().resetForm();
                $('#miFormulario_edit')[0].reset();
            });

            // EDIT

            $('body').on('click', '.btnEdit', function() {
                var unidad_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo base_url(); ?>unidades/editar/' + unidad_id,
                    type: 'get',
                    dataType: 'json',
                    success: function(respuesta) {
                        $('#modal_edit').modal('show');
                        $('#miFormulario_edit #txt_id').val(respuesta.data.id_unidadmedida);
                        $('#miFormulario_edit #txt_nombre').val(respuesta.data.nombre_unidad);
                    },
                    error: function(data) {
                        alert('No se pudo editar');
                    }
                });
            });


            $("#miFormulario_edit").validate({
                rules: {
                    txt_nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    }
                },
                messages: {
                    txt_nombre: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    }
                },
                errorPlacement: function(error, element) {
                    error.css("color", "red"); // Cambia el color a rojo
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    var form_action = $("#miFormulario_edit").attr("action");
                    $.ajax({
                        data: $('#miFormulario_edit').serialize(),
                        url: form_action,
                        type: "POST",
                        dataType: "json",
                        success: function(respuesta) {
                            var unidad = '<td>' + respuesta.data.id_unidadmedida + '</td>';
                            unidad += '<td>' + respuesta.data.nombre_unidad + '</td>';
                            unidad += '<td> <a data-id="' + respuesta.data.id_unidadmedida + '" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>';
                            unidad += '<td> <a data-id="' + respuesta.data.id_unidadmedida + '" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>';
                            $('#tabla_db tbody #' + respuesta.data.id_unidadmedida).html(unidad);
                            $('#miFormulario_edit')[0].reset();
                            $('#modal_edit').modal('hide');
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

            // DELETE

            $('body').on('click', '.btnDelete', function() {
                var unidad_id = $(this).attr('data-id');
                $.get('unidades/eliminar/' + unidad_id, function(data) {
                    $('#tabla_db tbody #' + unidad_id).remove();
                })
            });

        });
    </script>