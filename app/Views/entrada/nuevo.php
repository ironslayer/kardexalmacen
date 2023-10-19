<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h4 class="mt-4"><?php echo $titulo; ?></h4>

            <?php if (isset($validation)) { ?>
                <div class="alert alert-danger">
                    <?php echo $validation->listErrors(); ?>
                </div>
            <?php } ?>

            <div>
                <p>

                    <a href="<?php echo base_url() ?>item" class="btn btn-warning">Guardar</a>

                </p>
            </div>

            <form action="<?php echo base_url(); ?>entrada/guardar" method="post" autocomplete="off">


                <!-- primera fila -->
                <div class="form-control-plaintext">
                    <div class="row">
                        <div class="col-12 col-sm-2">

                            <label for="">Nota de recepción</label>
                            <input type="text" class="form-control" id="nota_recepcion" name="nota_recepcion" autofocus required placeholder="Ejem. 987654321" />

                        </div>
                        <div class="col-12 col-sm-1" style="display: flex; flex-direction: column; align-items: center; ">

                            <label for="">C/IVA</label>
                            <input type="checkbox" class="" id="c_iva" name="c_iva" style="margin-top: 9px" required />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required />

                        </div>
                        <div class="col-12 col-sm-2">

                            <label for="">Fuente</label>
                            <input type="text" class="form-control" id="fuente" name="fuente" required disabled value="Recursos Propios" />

                        </div>
                        <div class="col-12 col-sm-2">

                            <label for="">Tipo de entrada</label>
                            <select name="id_tipoentrada" id="id_tipoentrada" class="form-select" required>
                                <option value="">Seleccionar entrada</option>
                                <?php foreach ($tipo_entradas as $tentrada) { ?>
                                    <option value="<?php echo $tentrada['id_tipoentrada']; ?>"><?php echo $tentrada['nombre_entrada']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="col-12 col-sm-2">

                            <label for="">Proveedor</label>
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

                        <div class="col-12 col-sm-2">

                            <label for="cantidad_almacen">Cantidad en Almacén</label>
                            <input type="text" class="form-control" id="cantidad_almacen" name="cantidad_almacen" disabled />

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="">Cantidad a ingresar</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ejem. 150"/>

                        </div>

                        <div class="col-12 col-sm-2">

                            <label for="">Costo Total (Bs.)</label>
                            <input type="text" step="0.01" class="form-control" id="total_precio" name="total_precio" required placeholder="Ejem. 500.50"/>

                        </div>



                    </div>
                </div>
                <!-- tercera fila -->
                <div class="form-control-plaintext">
                    <div class="row">

                        <div class="col-12 col-sm-5">
                            <label for="">Concepto</label>
                            <input type="text" class="form-control" id="concepto" name="concepto" required placeholder=" Ejem. Compra de Salsas de tomate en promoción."/>
                        </div>

                        <div class="col-12 col-sm-3">

                            <label for="">Autorizado por</label>
                            <select name="id_usuario" id="id_usuario" class="form-select" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="col-12 col-sm-3">

                            <label for="">Entregado a</label>
                            <select name="id_usuario" id="id_usuario" class="form-select" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($usuarios as $usuario) { ?>
                                    <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>



                <div class="form-control-plaintext">
                    <button type="submit" class="btn btn-success">Agregar entrada</button>
                </div>

                <div class="row">
                    <table id="tablaentradas" class="table table-hover table-striped table-sm table-responsive tablaentradas" width="100%">
                        <thead>
                            <thead class="table-dark">
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                                <th>Total (Bs.)</th>
                                <th>Precio U. (Bs.)</th>
                                <th>Cosoto U. (Bs.)</th>
                                <th>Sub Total (Bs.)</th>
                                <th width="2%"></th>
                                <th width="2%"></th>
                            </thead>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <label for="" style="font-weight: bold; font-size: 15px; text-align: center;">Importe Total IVA</label>
                            <input type="text" id="total" name="total" size="10" readonly="true" value="0" style="font-weight: bold; font-size: 15px; text-align: center;" disabled/>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="" style="font-weight: bold; font-size: 15px; text-align: center;">Importe Total Factura</label>
                            <input type="text" id="total" name="total" size="10" readonly="true" value="0" style="font-weight: bold; font-size: 15px; text-align: center;" disabled/>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="" style="font-weight: bold; font-size: 15px; text-align: center;">Importe Total</label>
                            <input type="text" id="total" name="total" size="10" readonly="true" value="0" style="font-weight: bold; font-size: 15px; text-align: center;" disabled/>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </main>

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