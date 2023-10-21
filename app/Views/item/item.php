<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="row">
                <div class="col-4">
                    <div class="text-center d-flex">
                        <!-- Button add modal -->
                        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#modal_agregar" id="botonAgregar">
                            <i class="fa-solid fa-plus"></i></i> Agregar
                        </button>
                        
                        <a href="<?php echo base_url() ?>item/eliminados" class="btn btn-warning"><i class="fa-solid fa-delete-left"></i> Eliminados</a>

                    </div>
                </div>
            </div>

            <br />

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
                            <th width="5%">Editar</th>
                            <th width="5%">Borrar</th>
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

                                <td> <a data-id="<?php echo $dato['id_item']; ?>" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>

                                <td> <a data-id="<?php echo $dato['id_item']; ?>" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>



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
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                


                <form action=" <?php echo base_url(); ?>item/insertar" method="post" autocomplete="off" id="miFormulario">

                    <div class="modal-body">

                        <div class="moda-content">
                            <label for="descripcion">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" autofocus required placeholder="Ej. CHAMPIÑONES EN LATA (400 GRAMOS)"/>
                        </div>
                        <div class="moda-content">
                        <label for="">Unidad de Medida</label>
                            <select name="id_unidadmedida" id="id_unidadmedida" class="form-select" required>
                                <option value="">Seleccionar unidad</option>
                                <?php foreach ($datos2 as $unidad) { ?>
                                    <?php if($unidad['activo']==1){?>
                                    <option value="<?php echo $unidad['id_unidadmedida']; ?>"><?php echo $unidad['nombre_unidad']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <div class="moda-content">
                        <label for="">Producto/Categoria</label>
                            <select name="id_producto" id="id_producto" class="form-select" required>
                                <option value="">Seleccionar producto</option>
                                <?php foreach ($datos3 as $producto) { ?>
                                    <?php if($producto['activo']==1){?>
                                    <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre_producto']; ?></option>
                                <?php }} ?>
                            </select>
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
                    <h5 class="modal-title" id="exampleModalLabel">Editar Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                


                <form action="<?php echo site_url('item/actualizar'); ?>" method="post" autocomplete="off" id="miFormulario_edit" name="miFormulario_edit">

                    <div class="modal-body">

                        <input type="hidden" name="txt_id" id="txt_id"/>

                        <div class="moda-content">
                            <label for="txt_descripcion">Descripción</label>
                            <input type="text" class="form-control" id="txt_descripcion" name="txt_descripcion" autofocus required placeholder="Ej. CHAMPIÑONES EN LATA (400 GRAMOS)"/>
                        </div>
                        <div class="moda-content">
                        <label for="txt_id_unidadmedida">Unidad de Medida</label>
                            <select name="txt_id_unidadmedida" id="txt_id_unidadmedida" class="form-select" required>
                                <option value="">Seleccionar Unidad</option>
                                    <?php foreach ($datos2 as $unidad) { ?>
                                        <?php if($unidad['activo']==1){?>
                                        <option value="<?php echo $unidad['id_unidadmedida']; ?>" >
                                        <?php echo $unidad['nombre_unidad']; ?>
                                        </option>
                                    <?php }} ?>
                            </select>
                        </div>
                        <div class="moda-content">
                        <label for="txt_id_producto">Producto/Categoria</label>
                            <select name="txt_id_producto" id="txt_id_producto" class="form-select" required>
                                <option value="">Seleccionar producto</option>
                                <?php foreach ($datos3 as $producto) { ?>
                                    <?php if($producto['activo']==1){?>
                                    <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre_producto']; ?></option>
                                <?php }} ?>
                            </select>
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
                // miTabla.reset();

                //AGREGAR
                $('#miFormulario').validate({
                    rules:{
                        descripcion:{
                            required: true,
                            minlength: 8,
                            maxlength: 50
                        }
                        
                    },
                    messages:{
                        descripcion:{
                            required: "Este campo es obligatorio",
                            minlength: "Debe contener al menos 8 caracteres",
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
                                var item = '<tr id="'+respuesta.data.id_item+'">';
                                item += '<td>'+respuesta.data.id_item+'</td>';
                                item += '<td>'+respuesta.data.descripcion+'</td>';
                                item += '<td>'+respuesta.data.id_unidadmedida.nombre_unidad+'</td>';
                                item += '<td>'+respuesta.data.cantidad+'</td>';
                                item += '<td>'+respuesta.data.costo_unitario+'</td>';
                                item += '<td>'+respuesta.data.importe+'</td>';
                                item += '<td> <a data-id="'+respuesta.data.id_item+'" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>';
                                item += '<td> <a data-id="'+respuesta.data.id_item+'" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>';
                                $('#tabla_db tbody').append(item);
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
                var item_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo base_url(); ?>item/editar/' + item_id,
                    type: 'get',
                    dataType: 'json',
                    success: function(respuesta) {
                        $('#modal_edit').modal('show');
                        $('#miFormulario_edit #txt_id').val(respuesta.data.id_item);
                        $('#miFormulario_edit #txt_descripcion').val(respuesta.data.descripcion);
                        // $('#miFormulario_edit #txt_id_unidadmedida').val(respuesta.data.id_unidadmedida.nombre_unidad);
                        // $('#miFormulario_edit #txt_id_producto').val(respuesta.data.id_producto.nombre_producto);

                    },error: function(data) {
                        alert('No se pudo editar');
                    }
                });
            });


            $("#miFormulario_edit").validate({
                rules:  {
                    txt_descripcion: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    }
                },
                messages: {
                    txt_descripcion: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 8 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    }
                },
                errorPlacement: function(error, element) {
                    error.css("color", "red"); // Cambia el color a rojo
                    error.insertAfter(element);
                },
                submitHandler: function(form){
                    var form_action = $("#miFormulario_edit").attr("action");
                    $.ajax({
                        data: $('#miFormulario_edit').serialize(),
                        url: form_action,
                        type: "POST",
                        dataType: "json",
                        success: function(respuesta){
                            var item = '<td>'+respuesta.data.id_item+'</td>';
                            item += '<td>'+respuesta.data.descripcion+'</td>';
                            item += '<td>'+respuesta.data.id_unidadmedida.nombre_unidad+'</td>';
                            item += '<td>'+respuesta.data.cantidad+'</td>';
                            item += '<td>'+respuesta.data.costo_unitario+'</td>';
                            item += '<td>'+respuesta.data.importe+'</td>';
                            item += '<td> <a data-id="'+respuesta.data.id_item+'" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>';
                            item += '<td> <a data-id="'+respuesta.data.id_item+'" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>';
                            $('#tabla_db tbody #'+respuesta.data.id_item).html(item);
                            $('#miFormulario_edit')[0].reset();
                            $('#modal_edit').modal('hide');
                        },
                        error: function(data){
                            console.log('Error:', data);
                        }
                    });
                }
            });

            // DELETE

            $('body').on('click', '.btnDelete', function(){
                var item_id = $(this).attr('data-id');
                $.get('item/eliminar/'+item_id, function (data){
                    $('#tabla_db tbody #'+item_id).remove();
                })
            });

        });

        </script>