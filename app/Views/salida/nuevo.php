<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <button type="button" class="btn btn-primary" id="toggleFormButton"><i class="fas fa-align-justify"></i> Ocultar Formulario</button>
            <!-- enlace para ir entrada_salida -->
            <a href="<?php echo base_url(); ?>entrada_salida" class="btn btn-warning"><i class="fas fa-eraser"></i> Editar</a>

            <form action="<?php echo base_url(); ?>salida/insertar" method="post" autocomplete="off" id="miFormulario">


                <!-- primera fila -->

                <div class="form-control-plaintext">
                    <div class="row">

                        <div class="col-12 col-sm-5">

                            <label for="id_item">Item</label>

                            <select name="id_item" id="id_item" class="form-select" onchange="buscaCantidadItem();" required autofocus>
                                <option value="">Seleccionar item del Almacén</option>
                                <?php foreach ($items as $item) { ?>

                                    <?php if ($item['cantidad'] > 0) { ?>

                                        <option value="<?php echo $item['id_item']; ?>"><?php echo $item['descripcion']; ?></option>

                                <?php }
                                } ?>
                            </select>



                        </div>

                        <div class="col-12 col-sm-3">

                            <label for="cantidad_almacen">Cantidad actual en Almacén</label>
                            <input type="text" class="form-control" id="cantidad_almacen" name="cantidad_almacen" disabled />

                        </div>

                        <div class="col-12 col-sm-4">

                            <label for="cantidad">Cantidad a salir</label>
                            <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad <= a la cantidad actual" />

                        </div>




                    </div>
                </div>
                <!-- segunda fila -->
                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-3">

                            <label for="nota_entrega">Nota de entrega</label>
                            <input type="number" class="form-control" id="nota_entrega" name="nota_entrega" required placeholder="Ej. 987654321" />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required />

                        </div>


                        <div class="col-12 col-sm-3">

                            <label for="id_tiposalida">Tipo de Salida</label>
                            <select name="id_tiposalida" id="id_tiposalida" class="form-select" required>
                                <option value="">Seleccionar salida</option>
                                <?php foreach ($tipo_salidas as $tsalida) { ?>
                                    <option value="<?php echo $tsalida['id_tiposalida']; ?>"><?php echo $tsalida['nombre_salida']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="col-12 col-sm-4">

                            <label for="destino">Destino</label>
                            <input type="text" class="form-control" id="destino" name="destino" required placeholder="Ej. Venta a clientes" />

                        </div>


                    </div>
                </div>
                <!-- tercera fila -->
                <div class="form-control-plaintext">
                    <div class="row">

                        <div class="col-12 col-sm-5">
                            <label for="concepto">Concepto</label>
                            <input type="text" class="form-control" id="concepto" name="concepto" required placeholder=" Ej. Consumo de cajas." />
                        </div>

                        <div class="col-12 col-sm-3">

                            <label for="id_usuario">Autorizado por</label>
                            <select name="id_usuario" id="id_usuario" class="form-select" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
                                <?php } ?>
                            </select>

                        </div>


                        <div class="col-12 col-sm-4">

                            <label for="id_usuario_dos">Entregado por</label>
                            <select name="id_usuario_dos" id="id_usuario_dos" class="form-select" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="form-control-plaintext">
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Agregar salida</button>
                </div>
            </form>

            <div class="table-responsive">
                <table id="tabla_db" class="table table-bordered table-striped table-hover ">
                    <thead class="table-dark">
                        <tr>
                            <th width="2%">ID</th>
                            <th width="2%">Nro. Mov.</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Costo U. (Bs.)</th>
                            <th>Sub Total (Bs.)</th>
                            <th width="2%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas as $salida) { ?>

                            <tr id="<?php echo $salida['id_salida']; ?>">
                                <td> <?php echo $salida['id_salida']; ?> </td>
                                <td> <?php echo $salida['nro_movimiento']; ?> </td>
                                <td> <?php echo $salida['fecha']; ?> </td>


                                <?php
                                $id_unidad = '';
                                foreach ($items as $item) {
                                    if ($item['id_item'] == $salida['id_item']) {
                                        $id_unidad = $item['id_unidadmedida'];
                                        echo '<td>' . $item['descripcion'] . '</td>';
                                        break;
                                    }
                                }

                                foreach ($unidadmedidas as $unidad) {
                                    if ($unidad['id_unidadmedida'] == $id_unidad) {
                                        echo '<td>' . $unidad['nombre_unidad'] . '</td>';
                                        break;
                                    }
                                }

                                ?>

                                <td> <?php echo $salida['cantidad']; ?> </td>
                                <td> <?php echo $salida['costo_unitario']; ?> </td>
                                <td> <?php echo $salida['importe']; ?> </td>
                                <td> <a data-id="<?php echo $salida['id_salida']; ?>" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br />
            <div class="row">

                <div class="col-12 col-sm-4 offset-sm-8" style="text-align: right;">
                    <label for="" style="font-weight: bold; font-size: 15px; text-align: center;">Importe Total</label>
                    <input type="text" id="total_importe" name="total" size="10" readonly="true" value=" <?php echo $sumaImportes ?> " style="font-weight: bold; font-size: 15px; text-align: center;" disabled />
                </div>

            </div>


        </div>

        <!-- modal2 EDITAR -->
        <div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Salida</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    <form action="<?php echo site_url('salida/actualizar'); ?>" method="post" autocomplete="off" id="miFormulario_edit" name="miFormulario_edit">

                        <div class="modal-body">

                            <input type="hidden" name="txt_id" id="txt_id" />


                            <!-- primera fila -->

                            <div class="form-control-plaintext">
                                <div class="row">

                                    <div class="col-12 col-sm-5" id="opciones_items">

                                        

                                    </div>

                                    <div class="col-12 col-sm-3">

                                        <label for="cantidad_almacen">Cantidad actual en Almacén</label>
                                        <input type="text" class="form-control" id="cantidad_almacen" name="cantidad_almacen" disabled />

                                    </div>

                                    <div class="col-12 col-sm-4">

                                        <label for="txt_cantidad">Cantidad a salir</label>
                                        <input type="text" class="form-control" id="txt_cantidad" name="txt_cantidad" placeholder="Cantidad <= a la cantidad actual" disabled />

                                    </div>




                                </div>
                            </div>
                            <!-- segunda fila -->
                            <div class="form-control-plaintext">
                                <div class="row">
                                    <div class="col-12 col-sm-3">

                                        <label for="txt_nota_entrega">Nota de entrega</label>
                                        <input type="number" class="form-control" id="txt_nota_entrega" name="txt_nota_entrega" required placeholder="Ej. 987654321" />

                                    </div>

                                    <div class="col-12 col-sm-2">

                                        <label for="txt_fecha">Fecha</label>
                                        <input type="date" class="form-control" id="txt_fecha" name="txt_fecha" required />

                                    </div>


                                    <div class="col-12 col-sm-3" id="opciones_tiposalidas" >

                                        

                                    </div>
                                    <div class="col-12 col-sm-4">

                                        <label for="txt_destino">Destino</label>
                                        <input type="text" class="form-control" id="txt_destino" name="txt_destino" required placeholder="Ej. Venta a clientes" />

                                    </div>


                                </div>
                            </div>
                            <!-- tercera fila -->
                            <div class="form-control-plaintext">
                                <div class="row">

                                    <div class="col-12 col-sm-5">
                                        <label for="txt_concepto">Concepto</label>
                                        <input type="text" class="form-control" id="txt_concepto" name="txt_concepto" required placeholder=" Ej. Consumo de cajas." />
                                    </div>

                                    <div class="col-12 col-sm-3" id="opciones_usuario1" >

                                       

                                    </div>


                                    <div class="col-12 col-sm-4" id="opciones_usuario2" >

                                        

                                    </div>
                                </div>
                            </div>

                            <!-- pie -->
                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="resetFormEdit">Cancelar</button>

                                <button type="submit" class="btn btn-success">Modificar</button>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <!-- Código para obtener solo la cantidad del item -->

    <script>
        function buscaCantidadItem() {
            // alert("hola");
            var parametros = {
                "id_item": $('#id_item').val()

            };
            if (parametros['id_item'] != "") {
                $.ajax({
                    // data: parametros,
                    url: '<?php echo base_url(); ?>item/buscaCantidadItem/' + parametros['id_item'],
                    dataType: 'json',

                    // beforesend: function() {
                    // $('#cantidad').html("Mensaje antes de Enviar");
                    // },

                    success: function(resultado) {
                        $('#cantidad_almacen').val(resultado.datos.cantidad);
                        $('#cantidad').val(resultado.datos.cantidad);
                        $('#cantidad').attr('max', resultado.datos.cantidad);
                        $('#cantidad').attr('min', 1);
                        $('#cantidad').attr('title', 'El valor debe estar entre 1 y ' + resultado.datos.cantidad);

                    }
                });
            } else {
                $('#cantidad_almacen').val("");
                $('#cantidad').val("");
            }
        }
    </script>

    <!-- Mostrar y ocultar formulario -->
    <script>
        $(document).ready(function() {
            $("#toggleFormButton").click(function() {
                $("#miFormulario").toggle(); // Alternar la visibilidad del formulario
                if ($("#miFormulario").is(":visible")) {
                    $(this).html('<i class="fas fa-align-justify"></i> Ocultar Formulario');
                } else {
                    $(this).html('<i class="fas fa-align-justify"></i> Mostrar Formulario');
                }
            });
        });
    </script>


    <!-- codigo para formulario y tabla -->
    <script>
        $(document).ready(function() {
            var miTabla = $('#tabla_db').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total salidas)",
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
                    id_item: {
                        required: true,
                    },

                    nota_entrega: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    fecha: {
                        required: true
                    },
                    id_tiposalida: {
                        required: true
                    },
                    destino: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    concepto: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    },
                    id_usuario: {
                        required: true
                    },
                    id_usuario_dos: {
                        required: true
                    }
                },
                messages: {
                    id_item: {
                        required: "Este campo es obligatorio",
                    },

                    nota_entrega: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    fecha: {
                        required: "Este campo es obligatorio"
                    },
                    id_tiposalida: {
                        required: "Este campo es obligatorio"
                    },
                    destino: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 5 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    concepto: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 8 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    id_usuario: {
                        required: "Este campo es obligatorio"
                    },
                    id_usuario_dos: {
                        required: "Este campo es obligatorio"
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
                            var salida = '<tr id="' + respuesta.data.id_salida + '">';
                            salida += '<td>' + respuesta.data.id_salida + '</td>';
                            salida += '<td>' + respuesta.data.nro_movimiento + '</td>';
                            salida += '<td>' + respuesta.data.fecha + '</td>';
                            salida += '<td>' + respuesta.data.descripcion + '</td>';
                            salida += '<td>' + respuesta.data.id_unidadmedida.nombre_unidad + '</td>';
                            salida += '<td>' + respuesta.data.cantidad + '</td>';
                            salida += '<td>' + respuesta.data.costo_unitario + '</td>';
                            salida += '<td>' + respuesta.data.importe + '</td>';
                            salida += '<td> <a data-id="' + respuesta.data.id_salida + '" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>';
                            $('#tabla_db tbody').append(salida);
                            $('#miFormulario')[0].reset();
                            $('#total_importe').val(respuesta.data.sumaImportes);
                            location.reload();

                            // $('#modal_agregar').modal('hide');

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
                var salida_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo base_url(); ?>salida/editar/' + salida_id,
                    type: 'get',
                    dataType: 'json',
                    success: function(respuesta) {
                        $('#modal_edit').modal('show');
                        $('#miFormulario_edit #txt_id').val(respuesta.data.id_salida);
                        $('#miFormulario_edit #opciones_items').html(respuesta.data.opcionesItem);
                        $('#miFormulario_edit #txt_cantidad').val(respuesta.data.cantidad);
                        $('#miFormulario_edit #txt_nota_entrega').val(respuesta.data.nota_entrega);
                        $('#miFormulario_edit #txt_fecha').val(respuesta.data.fecha);
                        $('#miFormulario_edit #opciones_tiposalidas').html(respuesta.data.opcionesTipoSalida);
                        $('#miFormulario_edit #txt_destino').val(respuesta.data.destino);
                        $('#miFormulario_edit #txt_concepto').val(respuesta.data.concepto);
                        $('#miFormulario_edit #opciones_usuario1').html(respuesta.data.opcionesUsuario1);
                        $('#miFormulario_edit #opciones_usuario2').html(respuesta.data.opcionesUsuario2);
                        
                    },
                    error: function(data) {
                        alert('No se pudo editar');
                    }
                });
            });


            $("#miFormulario_edit").validate({
                rules: {
                    txt_nota_entrega: {
                        required: true,
                        min: 1
                    },
                    txt_fecha: {
                        required: true
                    },
                    txt_tiposalida: {
                        required: true
                    },
                    txt_destino: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    txt_concepto: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    },
                    txt_usuario1: {
                        required: true
                    },
                    txt_usuario2: {
                        required: true
                    }
                },
                messages: {
                    txt_nota_entrega: {
                        required: "Este campo es obligatorio",
                        min: "Debe contener al menos 1 digito"
                    },
                    txt_fecha: {
                        required: "Este campo es obligatorio"
                    },
                    txt_tiposalida: {
                        required: "Este campo es obligatorio"
                    },
                    txt_destino: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 5 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_concepto: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 8 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_usuario1: {
                        required: "Este campo es obligatorio"
                    },
                    txt_usuario2: {
                        required: "Este campo es obligatorio"
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
                            var salida = '<td>' + respuesta.data.id_salida + '</td>';
                            salida += '<td>' + respuesta.data.nro_movimiento + '</td>';
                            salida += '<td>' + respuesta.data.fecha + '</td>';
                            salida += '<td>' + respuesta.data.id_item.descripcion + '</td>';
                            salida += '<td>' + respuesta.data.id_unidadmedida.nombre_unidad + '</td>';
                            salida += '<td>' + respuesta.data.cantidad + '</td>';
                            salida += '<td>' + respuesta.data.costo_unitario + '</td>';
                            salida += '<td>' + respuesta.data.importe + '</td>';
                            salida += '<td> <a data-id="' + respuesta.data.id_salida + '" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>';
                            
                            $('#tabla_db tbody #' + respuesta.data.id_salida).html(salida);
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
                var proveedor_id = $(this).attr('data-id');
                $.get('proveedor/eliminar/' + proveedor_id, function(data) {
                    $('#tabla_db tbody #' + proveedor_id).remove();
                })
            });

        });
    </script>