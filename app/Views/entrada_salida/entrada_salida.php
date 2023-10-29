<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">


            <h4 class="mt-4"><?php echo $titulo; ?></h4>


            <!-- enlace para ir a entrada -->
            <a href="<?php echo base_url(); ?>entrada/nuevo" class="btn btn-light"><i class="fas fa-undo-alt"></i> Regresar a Entradas</a>
            <!-- enlace para ir a salida -->
            <a href="<?php echo base_url(); ?>salida/nuevo" class="btn btn-light"><i class="fas fa-undo-alt"></i> Regresar a Salidas</a>


            <!-- primera fila -->

            <div class="">
                <div class="row">

                    <div class="col-12 col-sm-5">

                        <label for="id_item">Items con movimientos</label>

                        <select name="id_item" id="id_item" class="form-select" onchange="buscaEntradasSalidasItem();" required autofocus>
                            <option value="">Seleccionar item del Almacén</option>
                            <?php foreach ($items as $item) { ?>

                                <option value="<?php echo $item['id_item']; ?>"><?php echo $item['descripcion']; ?></option>

                            <?php
                            } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-1">
                           
                    </div>
                    <div class="col-12 col-sm-2" id="e_entrada" style="background-color: #CCFFCA; display: none; justify-content: center; align-items: center; border: 1px solid #000;">
                        E = Entrada
                    </div>
                    <div class="col-12 col-sm-1">
                           
                    </div>
                    <div class="col-12 col-sm-2" id="s_salida" style="background-color: #F9DFD8; display: none; justify-content: center; align-items: center; border: 1px solid #000;" >
                        S = Salida
                    </div>
                </div>
            </div>

            <br/>

            <div class="" id="miTablaItem">

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


                                    <div class="col-12 col-sm-3" id="opciones_tiposalidas">



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

                                    <div class="col-12 col-sm-3" id="opciones_usuario1">



                                    </div>


                                    <div class="col-12 col-sm-4" id="opciones_usuario2">



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
                            lengthMenu: [10, 15, 20, 50],
                        });

                    }
                });
            } else {
                $('#miTablaItem').html("");
                //ocultamos e_entrada y s_salida
                $('#e_entrada').hide();
                $('#s_salida').hide();
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