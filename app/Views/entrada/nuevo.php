<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <button type="button" class="btn btn-primary" id="toggleFormButton"><i class="fas fa-align-justify"></i> Ocultar Formulario</button>

            <!-- enlace para ir entrada_salida -->
            <a href="<?php echo base_url(); ?>entrada_salida" class="btn btn-warning"><i class="fas fa-eraser"></i> Editar</a>

            <form autocomplete="" id="miFormulario">

                <!-- primera fila -->
                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-2">

                            <label for="nota_recepcion">Nota de recepción</label>
                            <input type="number" class="form-control" id="nota_recepcion" name="nota_recepcion" autofocus required placeholder="Ejem. 987654321" />

                        </div>
                        <div class="col-12 col-sm-1" style="display: flex; flex-direction: column; align-items: center; ">

                            <label for="c_iva">C/IVA</label>
                            <input type="checkbox" class="" id="c_iva" name="c_iva" style="margin-top: 9px" />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required value="<?= $fecha  ?>" />

                        </div>
                        <div class="col-12 col-sm-3">

                            <label for="fuente">Fuente</label>
                            <input type="text" class="form-control" id="fuente" name="fuente" required value="Recursos Propios" placeholder="Ej. Recursos Propios" />

                        </div>
                        <div class="col-12 col-sm-2">

                            <label for="id_tipoentrada">Tipo de entrada</label>
                            <select name="id_tipoentrada" id="id_tipoentrada" class="form-select" required>
                                <option value="">Seleccionar entrada</option>
                                <?php foreach ($tipo_entradas as $tentrada) { ?>
                                    <option value="<?php echo $tentrada['id_tipoentrada']; ?>"><?php echo $tentrada['nombre_entrada']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="col-12 col-sm-2">

                            <label for="id_proveedor">Proveedor</label>
                            <select name="id_proveedor" id="id_proveedor" class="form-select" required>
                                <option value="">Seleccionar proveedor</option>
                                <?php foreach ($proveedores as $proveedor) { ?>
                                    <option value="<?php echo $proveedor['id_proveedor']; ?>"><?php echo $proveedor['nombre_proveedor']; ?></option>
                                <?php } ?>
                            </select>

                        </div>

                    </div>
                </div>
                <!-- segunda fila -->

                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-5">

                            <label for="id_item">Item</label>

                            <select name="id_item" id="id_item" class="form-select" onchange="buscaCantidadItem();" required>
                                <option value="">Seleccionar item del Almacén</option>
                                <?php foreach ($items as $item) { ?>
                                    <option value="<?php echo $item['id_item']; ?>"><?php echo $item['descripcion']; ?></option>
                                <?php } ?>
                            </select>



                        </div>

                        <div class="col-12 col-sm-3">

                            <label for="cantidad_almacen">Cantidad actual en Almacén</label>
                            <input type="text" class="form-control" id="cantidad_almacen" name="cantidad_almacen" disabled />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="cantidad">Cantidad a ingresar</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ejem. 150" />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="total_precio">Costo Total (Bs.)</label>
                            <input type="number" step="0.01" class="form-control" id="total_precio" name="total_precio" required placeholder="Ejem. 500.50" />

                        </div>



                    </div>
                </div>
                <!-- tercera fila -->
                <div class="form-control-plaintext">
                    <div class="row">

                        <div class="col-12 col-sm-5">
                            <label for="concepto">Concepto</label>
                            <input type="text" class="form-control" id="concepto" name="concepto" required placeholder=" Ejem. Compra de Salsas de tomate en promoción." />
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
                        <div class="col-12 col-sm-3">

                            <label for="id_usuario_dos">Entregado a</label>
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
                    <button type="submit" class="btn btn-success" id="agregarEntradaButton"><i class="fas fa-plus"></i> Agregar entrada</button>
                </div>
            </form>

            <div class="table-responsive">
                <table id="tabla_db" class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="2%">ID</th>
                            <th width="2%">Nro. Mov.</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Total (Bs.)</th>
                            <th>Precio U. (Bs.)</th>
                            <th>Costo U. (Bs.)</th>
                            <th>Sub Total (Bs.)</th>
                            <th width="2%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $entrada) { ?>

                            <tr id="<?php echo $entrada['id_entrada']; ?>">
                                <td> <?php echo $entrada['id_entrada']; ?> </td>
                                <td> <?php echo $entrada['nro_movimiento']; ?> </td>
                                <td> <?php echo $entrada['fecha']; ?> </td>


                                <?php
                                $id_unidad = '';
                                foreach ($items as $item) {
                                    if ($item['id_item'] == $entrada['id_item']) {
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

                                <td> <?php echo $entrada['cantidad']; ?> </td>
                                <td> <?php echo $entrada['total_precio']; ?> </td>
                                <td> <?php echo $entrada['precio_unitario']; ?> </td>
                                <td> <?php echo $entrada['costo_unitario']; ?> </td>
                                <td> <?php echo $entrada['importe']; ?> </td>
                                <td> <a data-id="<?php echo $entrada['id_entrada']; ?>" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br />
            <div class="row">
                <div class="col-12 col-sm-4">
                    <label for="" style="font-weight: bold; font-size: 15px; text-align: center;">Importe Total IVA</label>
                    <input type="text" id="total_iva" name="total" size="10" readonly="true" value=" <?php echo $importeTotalIva ?> " style="font-weight: bold; font-size: 15px; text-align: center;" disabled value="" />
                </div>
                <div class="col-12 col-sm-4">
                    <label for="" style="font-weight: bold; font-size: 15px; text-align: center;">Importe Total Factura</label>
                    <input type="text" id="total_factura" name="total" size="10" readonly="true" value=" <?php echo $sumaTotales ?> " style="font-weight: bold; font-size: 15px; text-align: center;" disabled />
                </div>
                <div class="col-12 col-sm-4">
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
                        <h5 class="modal-title" id="exampleModalLabel">Editar Entrada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    <form action="<?php echo site_url('entrada/actualizar'); ?>" method="post" autocomplete="on" id="miFormulario_edit" name="miFormulario_edit">

                        <div class="modal-body">

                            <input type="hidden" name="txt_id" id="txt_id" />
                            <!-- primera fila -->
                            <div class="form-control-plaintext moda-content">
                                <div class="row">
                                    <div class="col-12 col-sm-2 ">

                                        <label for="txt_nota_recepcion">Nota de recepción</label>
                                        <input type="number" class="form-control" id="txt_nota_recepcion" name="txt_nota_recepcion" autofocus required placeholder="Ejem. 987654321" />

                                    </div>

                                    <!-- check del iva -->
                                    <div class="col-12 col-sm-1" style="display: flex; flex-direction: column; align-items: center; " id="marcado_iva">



                                    </div>

                                    <div class="col-12 col-sm-2">

                                        <label for="txt_fecha">Fecha</label>
                                        <input type="date" class="form-control" id="txt_fecha" name="txt_fecha" required />

                                    </div>
                                    <div class="col-12 col-sm-3">

                                        <label for="txt_fuente">Fuente</label>
                                        <input type="text" class="form-control" id="txt_fuente" name="txt_fuente" required value="" placeholder="Ej. Recursos Propios" />

                                    </div>
                                    <!-- tipo entrada -->
                                    <div class="col-12 col-sm-2" id="opciones_tipoentradas">



                                    </div>
                                    <!-- proveedor -->
                                    <div class="col-12 col-sm-2" id="opciones_proveedores">



                                    </div>

                                </div>
                            </div>
                            <!-- segunda fila -->
                            <div class="form-control-plaintext moda-content">
                                <div class="row">

                                    <!-- item -->
                                    <div class="col-12 col-sm-5" id="opciones_item">




                                    </div>

                                    <div class="col-12 col-sm-3">

                                        <label for="txt_cantidad_almacen">Cantidad actual en Almacén</label>
                                        <input type="text" class="form-control" id="txt_cantidad_almacen" name="txt_cantidad_almacen" disabled />

                                    </div>

                                    <div class="col-12 col-sm-2">

                                        <label for="txt_cantidad">Cantidad a ingresar</label>
                                        <input type="number" class="form-control" id="txt_cantidad" name="txt_cantidad" disabled />

                                    </div>

                                    <div class="col-12 col-sm-2">

                                        <label for="txt_total_precio">Costo Total (Bs.)</label>
                                        <input type="text" step="0.01" class="form-control" id="txt_total_precio" name="txt_total_precio" disabled />

                                    </div>



                                </div>
                            </div>

                            <!-- tercera fila -->

                            <div class="form-control-plaintext moda-content">
                                <div class="row">

                                    <div class="col-12 col-sm-5">
                                        <label for="txt_concepto">Concepto</label>
                                        <input type="text" class="form-control" id="txt_concepto" name="txt_concepto" required placeholder=" Ejem. Compra de Salsas de tomate en promoción." />
                                    </div>

                                    <div class="col-12 col-sm-3" id="opciones_usuario1">



                                    </div>
                                    <div class="col-12 col-sm-3" id="opciones_usuario2">



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
                    }
                });
            } else {
                $('#cantidad_almacen').val("");
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

    <script>
        document.getElementById('agregarEntradaButton').addEventListener('click', function() {
            // Validar el formulario utilizando Bootstrap
            if ($('#miFormulario')[0].checkValidity()) {
                // Obtener los datos del formulario en formato JSON
                var formData = {
                    nota_recepcion: $('#nota_recepcion').val(),
                    c_iva: $('#c_iva').is(':checked') ? 1 : 0,
                    fecha: $('#fecha').val(),
                    fuente: $('#fuente').val(),
                    id_tipoentrada: $('#id_tipoentrada').val(),
                    id_proveedor: $('#id_proveedor').val(),
                    id_item: $('#id_item').val(),
                    cantidad: $('#cantidad').val(),
                    total_precio: $('#total_precio').val(),
                    concepto: $('#concepto').val(),
                    id_usuario: $('#id_usuario').val(),
                    id_usuario_dos: $('#id_usuario_dos').val(),
                    // Agregar el resto de campos aquí
                };

                // Enviar los datos mediante AJAX
                $.ajax({
                    url: '<?php echo base_url(); ?>entrada/insertar',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta.status) {
                            // var entrada = '<tr id="' + respuesta.data.id_entrada + '">';
                            // entrada += '<td>' + respuesta.data.id_entrada + '</td>';
                            // entrada += '<td>' + respuesta.data.nro_movimiento + '</td>';
                            // entrada += '<td>' + respuesta.data.fecha + '</td>';
                            // entrada += '<td>' + respuesta.data.id_item.descripcion + '</td>';
                            // entrada += '<td>' + respuesta.data.id_unidadmedida.nombre_unidad + '</td>';
                            // entrada += '<td>' + respuesta.data.cantidad + '</td>';
                            // entrada += '<td>' + respuesta.data.total_precio + '</td>';
                            // entrada += '<td>' + respuesta.data.precio_unitario + '</td>';
                            // entrada += '<td>' + respuesta.data.costo_unitario + '</td>';
                            // entrada += '<td>' + respuesta.data.importe + '</td>';

                            // entrada += '<td> <a data-id="' + respuesta.data.id_entrada + '" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>';

                            // $('#tabla_db tbody').append(entrada);
                            // $('#miFormulario')[0].reset();
                            // $('#total_iva').val(respuesta.data.importeTotalIva);
                            // $('#total_factura').val(respuesta.data.sumaTotales);
                            // $('#total_importe').val(respuesta.data.sumaImportes);
                            location.reload();
                        } 
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Manejar errores aquí
                    }
                });
            } else {
                // El formulario no es válido, mostrar errores de validación
                $('#miFormulario')[0].reportValidity();
            }
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
                },
                lengthMenu: [100, 200, 500],
            });

            //AGREGAR
            $('#miFormulario').validate({
                rules: {
                    nota_recepcion: {
                        required: true,
                        min: 1
                    },
                    fecha: {
                        required: true
                    },
                    fuente: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    id_tipoentrada: {
                        required: true
                    },
                    id_proveedor: {
                        required: true
                    },
                    id_item: {
                        required: true
                    },
                    cantidad: {
                        required: true,
                        min: 1
                    },
                    total_precio: {
                        required: true,
                        min: 0.10
                    },
                    concepto: {
                        required: true,
                        minlength: 8,
                        maxlength: 250
                    },
                    id_usuario: {
                        required: true
                    },
                    id_usuario_dos: {
                        required: true
                    }
                },
                messages: {
                    nota_recepcion: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 1 dígito"
                    },
                    fecha: {
                        required: "Este campo es obligatorio"
                    },
                    fuente: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 5 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    id_tipoentrada: {
                        required: "Este campo es obligatorio"
                    },
                    id_proveedor: {
                        required: "Este campo es obligatorio"
                    },
                    id_item: {
                        required: "Este campo es obligatorio"
                    },
                    cantidad: {
                        required: "Este campo es obligatorio",
                        min: "La cantidad mínima es 1"
                    },
                    total_precio: {
                        required: "Este campo es obligatorio",
                        min: "La cantidad mínima es 0.10"
                    },
                    concepto: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 8 caracteres",
                        maxlength: "Debe contener maximo 250 caracteres"
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
                }
                // ,
                // submitHandler: function(form) {
                //     var form_action = $("#miFormulario").attr("action");
                //     $.ajax({
                //         type: "POST",
                //         url: form_action,
                //         data: $("#miFormulario").serialize(),
                //         dataType: "json",
                //         success: function(respuesta) {
                //             if (respuesta.status) {
                //                 var entrada = '<tr id="' + respuesta.data.id_entrada + '">';
                //                 entrada += '<td>' + respuesta.data.id_entrada + '</td>';
                //                 entrada += '<td>' + respuesta.data.nro_movimiento + '</td>';
                //                 entrada += '<td>' + respuesta.data.fecha + '</td>';
                //                 entrada += '<td>' + respuesta.data.id_item.descripcion + '</td>';
                //                 entrada += '<td>' + respuesta.data.id_unidadmedida.nombre_unidad + '</td>';
                //                 entrada += '<td>' + respuesta.data.cantidad + '</td>';
                //                 entrada += '<td>' + respuesta.data.total_precio + '</td>';
                //                 entrada += '<td>' + respuesta.data.precio_unitario + '</td>';
                //                 entrada += '<td>' + respuesta.data.costo_unitario + '</td>';
                //                 entrada += '<td>' + respuesta.data.importe + '</td>';

                //                 entrada += '<td> <a data-id="' + respuesta.data.id_entrada + '" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>';

                //                 // $('#tabla_db tbody').append(entrada);
                //                 // $('#miFormulario')[0].reset();
                //                 // $('#total_iva').val(respuesta.data.importeTotalIva);
                //                 // $('#total_factura').val(respuesta.data.sumaTotales);
                //                 // $('#total_importe').val(respuesta.data.sumaImportes);
                //                 location.reload();
                //             } else {
                //                 alert("Algo salió  mal");
                //             }



                //         },
                //         error: function(data) {
                //             console.log('Error:', data);
                //         }
                //     });
                // }
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
                var entrada_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo base_url(); ?>entrada/editar/' + entrada_id,
                    type: 'get',
                    dataType: 'json',
                    success: function(respuesta) {
                        $('#modal_edit').modal('show');
                        $('#miFormulario_edit #txt_id').val(respuesta.data.id_entrada);
                        $('#miFormulario_edit #txt_nota_recepcion').val(respuesta.data.nota_recepcion);
                        $('#miFormulario_edit #marcado_iva').html(respuesta.data.opcion_iva);
                        $('#miFormulario_edit #txt_fecha').val(respuesta.data.fecha);
                        $('#miFormulario_edit #txt_fuente').val(respuesta.data.fuente);
                        $('#miFormulario_edit #opciones_tipoentradas').html(respuesta.data.opcionesTipoEntrada);
                        $('#miFormulario_edit #opciones_proveedores').html(respuesta.data.opcionesProveedor);
                        $('#miFormulario_edit #opciones_item').html(respuesta.data.opcionesItem);
                        $('#miFormulario_edit #txt_cantidad').val(respuesta.data.cantidad);
                        $('#miFormulario_edit #txt_total_precio').val(respuesta.data.total_precio);
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
                    txt_nota_recepcion: {
                        required: true,
                        min: 1
                    },
                    txt_fecha: {
                        required: true
                    },
                    txt_fuente: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    txt_id_tipoentrada: {
                        required: true
                    },
                    txt_id_proveedor: {
                        required: true
                    },
                    txt_concepto: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    },
                    txt_id_usuario: {
                        required: true
                    },
                    txt_id_usuario_dos: {
                        required: true
                    }
                },
                messages: {
                    txt_nota_recepcion: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 1 dígito",
                    },
                    txt_fecha: {
                        required: "Este campo es obligatorio"
                    },
                    txt_fuente: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 5 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_id_tipoentrada: {
                        required: "Este campo es obligatorio"
                    },
                    txt_id_proveedor: {
                        required: "Este campo es obligatorio"
                    },
                    txt_concepto: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 8 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_id_usuario: {
                        required: "Este campo es obligatorio"
                    },
                    txt_id_usuario_dos: {
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
                            var entrada = '<td>' + respuesta.data.id_entrada + '</td>';
                            entrada += '<td>' + respuesta.data.nro_movimiento + '</td>';
                            entrada += '<td>' + respuesta.data.fecha + '</td>';
                            entrada += '<td>' + respuesta.data.id_item.descripcion + '</td>';
                            entrada += '<td>' + respuesta.data.id_unidadmedida.nombre_unidad + '</td>';
                            entrada += '<td>' + respuesta.data.cantidad + '</td>';
                            entrada += '<td>' + respuesta.data.total_precio + '</td>';
                            entrada += '<td>' + respuesta.data.precio_unitario + '</td>';
                            entrada += '<td>' + respuesta.data.costo_unitario + '</td>';
                            entrada += '<td>' + respuesta.data.importe + '</td>';
                            entrada += '<td> <a data-id="' + respuesta.data.id_entrada + '" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>';

                            $('#tabla_db tbody #' + respuesta.data.id_entrada).html(entrada);
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