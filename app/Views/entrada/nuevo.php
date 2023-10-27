<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <button type="button" class="btn btn-primary" id="toggleFormButton"><i class="fas fa-align-justify"></i> Ocultar Formulario</button>

            <form action="<?php echo base_url(); ?>entrada/insertar" method="post" autocomplete="off" id="miFormulario" action="<?php echo base_url(); ?>entrada/insertar">

                <!-- primera fila -->
                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-2">

                            <label for="nota_recepcion">Nota de recepción</label>
                            <input type="text" class="form-control" id="nota_recepcion" name="nota_recepcion" autofocus required placeholder="Ejem. 987654321" />

                        </div>
                        <div class="col-12 col-sm-1" style="display: flex; flex-direction: column; align-items: center; ">

                            <label for="c_iva">C/IVA</label>
                            <input type="checkbox" class="" id="c_iva" name="c_iva" style="margin-top: 9px"  />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required />

                        </div>
                        <div class="col-12 col-sm-3">

                            <label for="fuente">Fuente</label>
                            <input type="text" class="form-control" id="fuente" name="fuente" required value="" placeholder="Ej. Recursos Propios" />

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
                            <input type="text" step="0.01" class="form-control" id="total_precio" name="total_precio" required placeholder="Ejem. 500.50" />

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
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Agregar entrada</button>
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
                    <?php foreach($entradas as $entrada){ ?>
                            
                            <tr id="<?php echo $entrada['id_entrada']; ?>">
                                <td> <?php echo $entrada['id_entrada']; ?> </td>
                                <td> <?php echo $entrada['nro_movimiento']; ?> </td>
                                <td> <?php echo $entrada['fecha']; ?> </td>
                                
                            
                                <?php 
                                $id_unidad = '';
                                foreach($items as $item){
                                    if($item['id_item']==$entrada['id_item']){
                                        $id_unidad = $item['id_unidadmedida'];
                                        echo '<td>'.$item['descripcion'].'</td>';
                                        break;
                                    }
                                }
                              
                                foreach($unidadmedidas as $unidad){
                                    if($unidad['id_unidadmedida']==$id_unidad){
                                        echo '<td>'.$unidad['nombre_unidad'].'</td>';
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
            <br/>
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
                    url: '<?php echo base_url(); ?>item/buscaCantidadItem/'+parametros['id_item'],
                    dataType: 'json',

                    // beforesend: function() {
                    // $('#cantidad').html("Mensaje antes de Enviar");
                    // },

                    success: function(resultado) {
                    $('#cantidad_almacen').val(resultado.datos.cantidad);
                    }
                });
            }else{
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
                    rules:{
                        nota_recepcion:{
                            required: true,
                            minlength: 3,
                            maxlength: 50
                        },
                        fecha:{
                            required: true
                        },
                        fuente:{
                            required: true,
                            minlength: 5,
                            maxlength: 50
                        },
                        id_tipoentrada:{
                            required: true
                        },
                        id_proveedor:{
                            required: true
                        },
                        id_item:{
                            required: true
                        },
                        cantidad:{
                            required: true,
                            min: 1
                        },
                        total_precio:{
                            required: true,
                            min: 0.00
                        },
                        concepto:{
                            required: true,
                            minlength: 8,
                            maxlength: 50
                        },
                        id_usuario:{
                            required: true
                        },
                        id_usuario_dos:{
                            required: true
                        }
                    },
                    messages:{
                        nota_recepcion:{
                            required: "Este campo es obligatorio",
                            minlength: "Debe contener al menos 3 caracteres",
                            maxlength: "Debe contener maximo 50 caracteres"
                        },
                        fecha:{
                            required: "Este campo es obligatorio"
                        },
                        fuente:{
                            required: "Este campo es obligatorio",
                            minlength: "Debe contener al menos 5 caracteres",
                            maxlength: "Debe contener maximo 50 caracteres"
                        },
                        id_tipoentrada:{
                            required: "Este campo es obligatorio"
                        },
                        id_proveedor:{
                            required: "Este campo es obligatorio"
                        },
                        id_item:{
                            required: "Este campo es obligatorio"
                        },
                        cantidad:{
                            required: "Este campo es obligatorio",
                            min: "La cantidad mínima es 1"
                        },
                        total_precio:{
                            required: "Este campo es obligatorio",
                            min: "Debe contener al menos 0.00 caracteres"
                        },
                        concepto:{
                            required: "Este campo es obligatorio",
                            minlength: "Debe contener al menos 8 caracteres",
                            maxlength: "Debe contener maximo 50 caracteres"
                        },
                        id_usuario:{
                            required: "Este campo es obligatorio"
                        }, 
                        id_usuario_dos:{
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
                                var entrada = '<tr id="'+respuesta.data.id_entrada+'">';
                                entrada += '<td>'+respuesta.data.id_entrada+'</td>';
                                entrada += '<td>'+respuesta.data.nro_movimiento+'</td>';
                                entrada += '<td>'+respuesta.data.fecha+'</td>';
                                entrada += '<td>'+respuesta.data.id_item.descripcion+'</td>';
                                entrada += '<td>'+respuesta.data.id_unidadmedida.nombre_unidad+'</td>';
                                entrada += '<td>'+respuesta.data.cantidad+'</td>';
                                entrada += '<td>'+respuesta.data.total_precio+'</td>';
                                entrada += '<td>'+respuesta.data.precio_unitario+'</td>';
                                entrada += '<td>'+respuesta.data.costo_unitario+'</td>';
                                entrada += '<td>'+respuesta.data.importe+'</td>';

                                entrada += '<td> <a data-id="'+respuesta.data.id_entrada+'" class="btn btn-info btnEdit"><i class="far fa-file-alt"></i></a></td>';
                                
                                $('#tabla_db tbody').append(entrada);
                                $('#miFormulario')[0].reset();
                                $('#total_iva').val(respuesta.data.importeTotalIva);
                                $('#total_factura').val(respuesta.data.sumaTotales);
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
                var proveedor_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo base_url(); ?>proveedor/editar/' + proveedor_id,
                    type: 'get',
                    dataType: 'json',
                    success: function(respuesta) {
                        $('#modal_edit').modal('show');
                        $('#miFormulario_edit #txt_id').val(respuesta.data.id_proveedor);
                        $('#miFormulario_edit #txt_nombre').val(respuesta.data.nombre_proveedor);
                        $('#miFormulario_edit #txt_contacto').val(respuesta.data.contacto);
                        $('#miFormulario_edit #txt_direccion').val(respuesta.data.direccion);
                        $('#miFormulario_edit #txt_ciudad').val(respuesta.data.ciudad);
                        $('#miFormulario_edit #txt_telefono').val(respuesta.data.telefono);
                    },error: function(data) {
                        alert('No se pudo editar');
                    }
                });
            });


            $("#miFormulario_edit").validate({
                rules:  {
                    txt_nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    txt_contacto: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    txt_direccion: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    txt_ciudad: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    txt_telefono: {
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
                    },
                    txt_contacto: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_direccion: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_ciudad: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
                        maxlength: "Debe contener maximo 50 caracteres"
                    },
                    txt_telefono: {
                        required: "Este campo es obligatorio",
                        minlength: "Debe contener al menos 3 caracteres",
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
                            var proveedor = '<td>'+respuesta.data.id_proveedor+'</td>';
                            proveedor += '<td>'+respuesta.data.nombre_proveedor+'</td>';
                            proveedor += '<td>'+respuesta.data.contacto+'</td>';
                            proveedor += '<td>'+respuesta.data.direccion+'</td>';
                            proveedor += '<td>'+respuesta.data.ciudad+'</td>';
                            proveedor += '<td>'+respuesta.data.telefono+'</td>';
                            proveedor += '<td> <a data-id="'+respuesta.data.id_proveedor+'" class="btn btn-warning btnEdit"><i class="fa-solid fa-pencil"></i></a></td>';
                            proveedor += '<td> <a data-id="'+respuesta.data.id_proveedor+'" class="btn btn-danger btnDelete"><i class="fa-solid fa-trash"></i></a></td>';
                            $('#tabla_db tbody #'+respuesta.data.id_proveedor).html(proveedor);
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
                var proveedor_id = $(this).attr('data-id');
                $.get('proveedor/eliminar/'+proveedor_id, function (data){
                    $('#tabla_db tbody #'+proveedor_id).remove();
                })
            });

        });

        </script>


