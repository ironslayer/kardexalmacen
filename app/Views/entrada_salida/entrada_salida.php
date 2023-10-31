<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">


            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <div class="text-center">
                <!-- enlace para ir a entrada -->
                <a href="<?php echo base_url(); ?>entrada/nuevo" class="btn btn-outline-dark"><i class="fas fa-undo-alt"></i> Regresar a Entradas</a>
                <!-- enlace para ir a salida -->
                <a href="<?php echo base_url(); ?>salida/nuevo" class="btn btn-outline-dark"><i class="fas fa-undo-alt"></i> Regresar a Salidas</a>
            </div>



            <!-- primera fila -->

            <div class="">
                <div class="row">

                    <div class="col-12 col-sm-6">

                        <label for="id_item">Items con movimientos</label>

                        <select name="id_item" id="id_item" class="form-select" onchange="buscaEntradasSalidasItem();" required autofocus>
                            <option value="">Seleccionar item del Almacén</option>
                            <?php foreach ($items as $item) { ?>

                                <option value="<?php echo $item['id_item']; ?>"><?php echo $item['descripcion']; ?></option>

                            <?php
                            } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <br />
                        <a class="fila_verde" id="e_entrada" style="text-decoration: none;color: #000;padding: 10px;background-color: #CCFFCA; display: none; justify-content: center; align-items: center; border: 1px solid #000;"> E = Entrada</a>
                        <a class="fila_roja" id="s_salida" style="text-decoration: none;color: #000;padding: 10px;background-color: #F9DFD8; display: none; justify-content: center; align-items: center; border: 1px solid #000;"> S = Salida</a>
                    </div>


                </div>
            </div>

            <br />

            <div class="" id="miTablaItem">

            </div>

            <!-- Agregamos botones despues de miTablaItem para editar o eliminar la ultima fila -->
            <div class="text-center" id="botones">

            </div>

            <!-- modal EDITAR1 -->
            <div class="modal fade" id="modal_edit_uno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar Entrada</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>



                        <form action="<?php echo site_url('entrada_salida/actualizar_entrada'); ?>" method="post" autocomplete="off" id="miFormulario_edit_uno" name="miFormulario_edit_uno">

                            <div class="modal-body">

                                <input type="hidden" name="txt_id_uno" id="txt_id_uno" />
                                <!-- primera fila -->
                                <div class="form-control-plaintext moda-content">
                                    <div class="row">
                                        <div class="col-12 col-sm-2 ">

                                            <label for="txt_nota_recepcion_uno">Nota de recepción</label>
                                            <input type="number" class="form-control" id="txt_nota_recepcion_uno" name="txt_nota_recepcion_uno" autofocus required placeholder="Ejem. 987654321" />

                                        </div>

                                        <!-- check del iva -->
                                        <div class="col-12 col-sm-1" style="display: flex; flex-direction: column; align-items: center; " id="marcado_iva_uno">



                                        </div>

                                        <div class="col-12 col-sm-2">

                                            <label for="txt_fecha_uno">Fecha</label>
                                            <input type="date" class="form-control" id="txt_fecha_uno" name="txt_fecha_uno" required />

                                        </div>
                                        <div class="col-12 col-sm-3">

                                            <label for="txt_fuente_uno">Fuente</label>
                                            <input type="text" class="form-control" id="txt_fuente_uno" name="txt_fuente_uno" required value="" placeholder="Ej. Recursos Propios" />

                                        </div>
                                        <!-- tipo entrada -->
                                        <div class="col-12 col-sm-2" id="opciones_tipoentradas_uno">



                                        </div>
                                        <!-- proveedor -->
                                        <div class="col-12 col-sm-2" id="opciones_proveedores_uno">



                                        </div>

                                    </div>
                                </div>
                                <!-- segunda fila -->
                                <div class="form-control-plaintext moda-content">
                                    <div class="row">

                                        <!-- item -->
                                        <div class="col-12 col-sm-5" id="opciones_item_uno">




                                        </div>

                                        <div class="col-12 col-sm-3">

                                            <label for="txt_cantidad_almacen_uno">Penúltima Cantidad en Almacén</label>
                                            <input type="text" class="form-control" id="txt_cantidad_almacen_uno" name="txt_cantidad_almacen_uno" disabled />

                                        </div>

                                        <div class="col-12 col-sm-2">

                                            <label for="txt_cantidad_uno">Cantidad a ingresar</label>
                                            <input type="number" class="form-control" id="txt_cantidad_uno" name="txt_cantidad_uno" />

                                        </div>

                                        <div class="col-12 col-sm-2">

                                            <label for="txt_total_precio_uno">Costo Total (Bs.)</label>
                                            <input type="text" step="0.01" class="form-control" id="txt_total_precio_uno" name="txt_total_precio_uno" />

                                        </div>



                                    </div>
                                </div>

                                <!-- tercera fila -->

                                <div class="form-control-plaintext moda-content">
                                    <div class="row">

                                        <div class="col-12 col-sm-5">
                                            <label for="txt_concepto_uno">Concepto</label>
                                            <input type="text" class="form-control" id="txt_concepto_uno" name="txt_concepto_uno" required placeholder=" Ejem. Compra de Salsas de tomate en promoción." />
                                        </div>

                                        <div class="col-12 col-sm-3" id="opciones_usuario1_uno">



                                        </div>
                                        <div class="col-12 col-sm-3" id="opciones_usuario2_uno">



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

            <!-- modal EDITAR2 -->
            <div class="modal fade" id="modal_edit_dos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar Salida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>



                        <form action="<?php echo site_url('entrada_salida/actualizar_salida'); ?>" method="post" autocomplete="off" id="miFormulario_edit_dos" name="miFormulario_edit_dos">

                            <div class="modal-body">

                                <input type="hidden" name="txt_id_dos" id="txt_id_dos" />


                                <!-- primera fila -->

                                <div class="form-control-plaintext">
                                    <div class="row">

                                        <div class="col-12 col-sm-5" id="opciones_items_dos">



                                        </div>

                                        <div class="col-12 col-sm-3">

                                            <label for="cantidad_almacen_dos">Penúltima Cantidad en Almacén</label>
                                            <input type="text" class="form-control" id="cantidad_almacen_dos" name="cantidad_almacen_dos" disabled />

                                        </div>

                                        <div class="col-12 col-sm-4">

                                            <label for="txt_cantidad_dos">Cantidad a salir</label>
                                            <input type="text" class="form-control" id="txt_cantidad_dos" name="txt_cantidad_dos" placeholder="Cantidad <= a la cantidad actual"  />

                                        </div>




                                    </div>
                                </div>
                                <!-- segunda fila -->
                                <div class="form-control-plaintext">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">

                                            <label for="txt_nota_entrega_dos">Nota de entrega</label>
                                            <input type="number" class="form-control" id="txt_nota_entrega_dos" name="txt_nota_entrega_dos" required placeholder="Ej. 987654321" />

                                        </div>

                                        <div class="col-12 col-sm-2">

                                            <label for="txt_fecha_dos">Fecha</label>
                                            <input type="date" class="form-control" id="txt_fecha_dos" name="txt_fecha_dos" required />

                                        </div>


                                        <div class="col-12 col-sm-3" id="opciones_tiposalidas_dos">



                                        </div>
                                        <div class="col-12 col-sm-4">

                                            <label for="txt_destino_dos">Destino</label>
                                            <input type="text" class="form-control" id="txt_destino_dos" name="txt_destino_dos" required placeholder="Ej. Venta a clientes" />

                                        </div>


                                    </div>
                                </div>
                                <!-- tercera fila -->
                                <div class="form-control-plaintext">
                                    <div class="row">

                                        <div class="col-12 col-sm-5">
                                            <label for="txt_concepto_dos">Concepto</label>
                                            <input type="text" class="form-control" id="txt_concepto_dos" name="txt_concepto_dos" required placeholder=" Ej. Consumo de cajas." />
                                        </div>

                                        <div class="col-12 col-sm-3" id="opciones_usuario1_dos">



                                        </div>


                                        <div class="col-12 col-sm-4" id="opciones_usuario2_dos">



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

    <script>
        function buscaEntradasSalidasItem() {
            // alert("hola");
            var parametros = {
                "id_item": $('#id_item').val()

            };
            if (parametros['id_item'] != "") {
                $.ajax({
                    // data: parametros,
                    url: '<?php echo base_url(); ?>entrada_salida/buscaEntradasSalidasItem/' + parametros['id_item'],
                    dataType: 'json',

                    // beforesend: function() {
                    // $('#cantidad').html("Mensaje antes de Enviar");
                    // },   

                    success: function(resultado) {
                        //mostramos e_entrada y s_salida
                        $('#e_entrada').show();
                        $('#s_salida').show();
                        $('#botones').html(resultado.data.botones);
                        $('#miTablaItem').html(resultado.data.tabla);
                        $('#miTablaItem table').DataTable({
                            language: {
                                "decimal": "",
                                "emptyTable": "No hay información",
                                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                                "infoFiltered": "(Filtrado de _MAX_ total salidas)",
                                "infoPostFix": "",
                                "thousands": ",",
                                "lengthMenu": "Mostrar _MENU_ unidades por página",
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
                            lengthMenu: [100, 15, 20, 50],
                        });

                    }
                });
            } else {
                $('#miTablaItem').html("");
                //ocultamos e_entrada, s_salida y botones
                $('#e_entrada').hide();
                $('#s_salida').hide();
                $('#botones').html("");
            }
        }
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
                    url: '<?php echo base_url(); ?>entrada_salida/editar/' + salida_id,
                    type: 'get',
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta.status) {
                            $('#modal_edit_uno').modal('show');
                            $('#miFormulario_edit_uno #txt_id_uno').val(respuesta.data.id_entrada);
                            $('#miFormulario_edit_uno #txt_nota_recepcion_uno').val(respuesta.data.nota_recepcion);
                            $('#miFormulario_edit_uno #marcado_iva_uno').html(respuesta.data.opcion_iva);
                            $('#miFormulario_edit_uno #txt_fecha_uno').val(respuesta.data.fecha);
                            $('#miFormulario_edit_uno #txt_fuente_uno').val(respuesta.data.fuente);
                            $('#miFormulario_edit_uno #opciones_tipoentradas_uno').html(respuesta.data.opcionesTipoEntrada);
                            $('#miFormulario_edit_uno #opciones_proveedores_uno').html(respuesta.data.opcionesProveedor);
                            $('#miFormulario_edit_uno #opciones_item_uno').html(respuesta.data.opcionesItem);
                            $('#miFormulario_edit_uno #txt_cantidad_almacen_uno').val(respuesta.data.penultimaCantidad);
                            $('#miFormulario_edit_uno #txt_cantidad_uno').val(respuesta.data.cantidad);
                            $('#miFormulario_edit_uno #txt_total_precio_uno').val(respuesta.data.total_precio);
                            $('#miFormulario_edit_uno #txt_concepto_uno').val(respuesta.data.concepto);
                            $('#miFormulario_edit_uno #opciones_usuario1_uno').html(respuesta.data.opcionesUsuario1);
                            $('#miFormulario_edit_uno #opciones_usuario2_uno').html(respuesta.data.opcionesUsuario2);
                        } else {
                            $('#modal_edit_dos').modal('show');
                            $('#miFormulario_edit_dos #txt_id_dos').val(respuesta.data.id_salida);
                            $('#miFormulario_edit_dos #opciones_items_dos').html(respuesta.data.opcionesItem);
                            $('#miFormulario_edit_dos #cantidad_almacen_dos').val(respuesta.data.penultimaCantidad);
                            $('#miFormulario_edit_dos #txt_cantidad_dos').val(respuesta.data.cantidad);
                            
                            $('#miFormulario_edit_dos #txt_nota_entrega_dos').val(respuesta.data.nota_entrega);
                            $('#miFormulario_edit_dos #txt_fecha_dos').val(respuesta.data.fecha);
                            $('#miFormulario_edit_dos #opciones_tiposalidas_dos').html(respuesta.data.opcionesTipoSalida);
                            $('#miFormulario_edit_dos #txt_destino_dos').val(respuesta.data.destino);
                            $('#miFormulario_edit_dos #txt_concepto_dos').val(respuesta.data.concepto);
                            $('#miFormulario_edit_dos #opciones_usuario1_dos').html(respuesta.data.opcionesUsuario1);
                            $('#miFormulario_edit_dos #opciones_usuario2_dos').html(respuesta.data.opcionesUsuario2);

                            $('#miFormulario_edit_dos #txt_cantidad_dos').attr('max', respuesta.data.penultimaCantidad);
                            $('#miFormulario_edit_dos #txt_cantidad_dos').attr('min', 1);
                            $('#miFormulario_edit_dos #txt_cantidad_dos').attr('title', 'El valor debe estar entre 1 y ' + respuesta.data.penultimaCantidad);
                        }

                        

                    },
                    error: function(data) {
                        alert('No se pudo editar');
                    }
                });
            });

            //validacion de formulario EDITAR uno

            $("#miFormulario_edit_uno").validate({
                rules:{
                        txt_nota_recepcion_uno:{
                            required: true,
                            min: 1
                        },
                        txt_fecha_uno:{
                            required: true
                        },
                        txt_fuente_uno:{
                            required: true,
                            minlength: 5,
                            maxlength: 50
                        },
                        txt_id_tipoentrada_uno:{
                            required: true
                        },
                        txt_id_proveedor_uno:{
                            required: true
                        },
                        txt_id_item_uno:{
                            required: true
                        },
                        txt_cantidad_uno:{
                            required: true,
                            min: 1
                        },
                        txt_total_precio_uno:{
                            required: true,
                            min: 0.10
                        },
                        txt_concepto_uno:{
                            required: true,
                            minlength: 8,
                            maxlength: 50
                        },
                        txt_id_usuario_uno:{
                            required: true
                        },
                        txt_id_usuario_dos_uno:{
                            required: true
                        }
                    },
                    messages:{
                        txt_nota_recepcion_uno:{
                            required: "Este campo es obligatorio",
                            min: "Debe contener al menos 1 dígito diferente de 0",
                        },
                        txt_fecha_uno:{
                            required: "Este campo es obligatorio"
                        },
                        txt_fuente_uno:{
                            required: "Este campo es obligatorio",
                            minlength: "Debe contener al menos 5 caracteres",
                            maxlength: "Debe contener maximo 50 caracteres"
                        },
                        txt_id_tipoentrada_uno:{
                            required: "Este campo es obligatorio"
                        },
                        txt_id_proveedor_uno:{
                            required: "Este campo es obligatorio"
                        },
                        txt_id_item_uno:{
                            required: "Este campo es obligatorio"
                        },
                        txt_cantidad_uno:{
                            required: "Este campo es obligatorio",
                            min: "La cantidad mínima es 1"
                        },
                        txt_total_precio_uno:{
                            required: "Este campo es obligatorio",
                            min: "Debe contener al menos 0.10"
                        },
                        txt_concepto_uno:{
                            required: "Este campo es obligatorio",
                            minlength: "Debe contener al menos 8 caracteres",
                            maxlength: "Debe contener maximo 50 caracteres"
                        },
                        txt_id_usuario_uno:{
                            required: "Este campo es obligatorio"
                        }, 
                        txt_id_usuario_dos_uno:{
                            required: "Este campo es obligatorio"
                        }
                    },
                errorPlacement: function(error, element) {
                    error.css("color", "red"); // Cambia el color a rojo
                    error.insertAfter(element);
                },
                submitHandler: function(form){
                    var form_action = $("#miFormulario_edit_uno").attr("action");
                    $.ajax({
                        data: $('#miFormulario_edit_uno').serialize(),
                        url: form_action,
                        type: "POST",
                        dataType: "json",
                        success: function(respuesta){
                            //validamos si el status en true
                            if(respuesta.status){
                                var entrada = '<td>'+respuesta.data.nro_movimiento+'</td>';
                                entrada += '<td>'+respuesta.data.fecha+'</td>';
                                entrada += '<td>'+respuesta.data.id_item+'</td>';
                                entrada += '<td>'+respuesta.data.e_s+'</td>';
                                entrada += '<td>'+respuesta.data.cantidad+'</td>';
                                entrada += '<td>'+respuesta.data.costo_unitario+'</td>';
                                entrada += '<td>'+respuesta.data.importe+'</td>';

                                //actualizamos la última fila de la tabla
                                $('#miTablaItem table tbody tr:last').html(entrada);
                                //resetamos el formulario
                                $('#miFormulario_edit_uno')[0].reset();
                                //ocultamos el modal
                                $('#modal_edit_uno').modal('hide');
                            }else{
                                alert("Algo falló!!");
                            }
                        },
                        error: function(data){
                            // console.log('Error:', data);
                        }
                    });
                }
            });


            //validacion de formulario EDITAR dos

            $("#miFormulario_edit_dos").validate({
                rules: {
                    txt_cantidad_dos: {
                        required: true,
                        min: 1
                    },
                    txt_nota_entrega_dos: {
                        required: true,
                        min: 1
                    },
                    txt_fecha_dos: {
                        required: true
                    },
                    txt_id_tiposalida_dos: {
                        required_dos: true
                    },
                    txt_destino_dos: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    txt_concepto_dos: {
                        required: true,
                        minlength: 8,
                        maxlength: 50
                    },
                    txt_id_usuario_dos: {
                        required: true
                    },
                    txt_id_usuario_dos_dos: {
                        required: true
                    }
                },
                messages: {
                    txt_cantidad_dos: {
                        required: "Este campo es obligatorio",
                        min: "La cantidad mínima es 1"
                    },
                    txt_nota_entrega_dos: {
                        required: "Este campo es obligatorio",
                        min: "Debe contener al menos 1 digito diferente de 0"
                    },
                    txt_fecha_dos: {
                        required: "Este campo es obligatorio"
                    },
                    txt_id_tiposalida_dos: {
                        required: "Este campo es obligatorio"
                    },
                    txt_destino_dos: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 5 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_concepto_dos: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 8 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_id_usuario_dos: {
                        required: "Este campo es obligatorio"
                    },
                    txt_id_usuario_dos_dos: {
                        required: "Este campo es obligatorio"
                    }
                },
                errorPlacement: function(error, element) {
                    error.css("color", "red"); // Cambia el color a rojo
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    var form_action = $("#miFormulario_edit_dos").attr("action");
                    $.ajax({
                        data: $('#miFormulario_edit_dos').serialize(),
                        url: form_action,
                        type: "POST",
                        dataType: "json",
                        //recibimos la respuesta y verificamos cualquier cosa
                        success: function(respuesta) {
                            //validamos si el status en true
                            if (respuesta.status) {
                                var salida = '<td>' + respuesta.data.nro_movimiento + '</td>';
                                salida += '<td>' + respuesta.data.fecha + '</td>';
                                salida += '<td>' + respuesta.data.id_item + '</td>';
                                salida += '<td>' + respuesta.data.e_s + '</td>';
                                salida += '<td>' + respuesta.data.cantidad + '</td>';
                                salida += '<td>' + respuesta.data.costo_unitario + '</td>';
                                salida += '<td>' + respuesta.data.importe + '</td>';


                                //actualizamos la última fila de la tabla
                                $('#miTablaItem table tbody tr:last').html(salida);
                                //resetamos el formulario
                                $('#miFormulario_edit_dos')[0].reset();
                                //ocultamos el modal
                                $('#modal_edit_dos').modal('hide');
                            } else {
                                alert("Algo falló!!");
                            }
                        },
                        error: function(data) {
                            // console.log('Error:', data);
                        }
                        
                    });
                }
            });

            // DELETE

            // $('body').on('click', '.btnDelete', function() {
            //     var proveedor_id = $(this).attr('data-id');
            //     $.get('proveedor/eliminar/' + proveedor_id, function(data) {
            //         $('#tabla_db tbody #' + proveedor_id).remove();
            //     })
            // });

        });
    </script>