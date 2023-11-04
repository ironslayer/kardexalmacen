<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">


            <div class="text-center">
                <h4 class="mt-4"><?php echo $titulo; ?></h4>
            </div>

            <div class="row justify-content-center">

                <form class="col-12 col-sm-8 col-md-6 p-4 rounded borde_reporte" id="miFormulario" name="miFormulario" autocomplete="off">

                    <div class="form-control-plaintext">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <label for="fecha_inicio">Desde</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $fecha_inicio ?>" autofocus required />
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="fecha_fin">Hasta</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $fecha_fin ?>" required />
                            </div>
                        </div>
                        <br />
                        <div class="text-center">
                            <div class="radio_contenedor">
                                <div class="radio_caja">
                                    <label for="id_item">Items con movimiento</label>

                                    <select name="id_item" id="id_item" class="form-select"  required>
                                        <option value="">Seleccionar item del Almacén</option>
                                        <?php foreach ($datos as $item) { ?>
                                            <option value="<?php echo $item['id_item']; ?>"><?php echo $item['descripcion']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="form-control-plaintext">
                            <a href="<?php echo base_url() ?>reportes/generarExcel" class="btn btn-success"><i class="fas fa-file-excel"></i> Exportar Datos</a>


                            <a id="generaPdfLink" href="#" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Aceptar</a>
                        </div>
                    </div>
                </form>

            </div>






    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtén el enlace
            var generaPdfLink = document.getElementById('generaPdfLink');

            // Agrega un evento clic al enlace
            generaPdfLink.addEventListener('click', function(event) {
                // Evita que el enlace funcione de inmediato
                event.preventDefault();

                // Obtén los valores de los campos del formulario
                var fechaInicio = encodeURIComponent(document.getElementById('fecha_inicio').value);
                var fechaFin = encodeURIComponent(document.getElementById('fecha_fin').value);
                var id_item = encodeURIComponent(document.getElementById('id_item').value);
                //validar que se seleccione un item
                if (id_item == "") {
                    alert("Debe seleccionar un item");
                    return false;
                }
                // Construye la URL con los valores
                var url = "<?php echo base_url() ?>reportes/generaPdfKardexFisicoValorado/" + fechaInicio + "/" + fechaFin + "/" + id_item;

                // Redirige al controlador con los valores como parámetros en la URL
                window.open(url, '_blank');
            });
        });
    </script>

