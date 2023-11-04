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
                                    <input type="radio" id="entrada" name="tipo" value="entrada" checked>
                                    <label for="entrada">Entradas</label>
                                </div>
                                <div class="radio_caja">
                                    <input type="radio" id="salida" name="tipo" value="salida">
                                    <label for="salida">Salidas</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="form-control-plaintext">
                            <a href="<?php echo base_url() ?>reportes/generarExcelEntradasSalidas" class="btn btn-success"><i class="fas fa-file-excel"></i> Exportar Datos</a>
                            
                            
                            <a id="generaPdfLink" href="#" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Aceptar</a>
                        </div>
                    </div>
                </form>
                
            </div>




            <!-- modal  -->
            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body" id="modal_reporte" >
                            <!-- <iframe id="pdfFrame" width="100%" height="400"></iframe> -->
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>






                    </div>
                </div>
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
        var tipo = encodeURIComponent(document.querySelector('input[name="tipo"]:checked').value);

        // Construye la URL con los valores
        var url = "<?php echo base_url() ?>reportes/generaPdfEntradasSalidas/" + fechaInicio + "/" + fechaFin + "/" + tipo;

        // Redirige al controlador con los valores como parámetros en la URL
        window.open(url, '_blank');
    });
});
</script>





    <!-- codigo para formulario  -->
    <script>
        function ver_reporte() {
        $('#modal_reporte').html()
        $.ajax({
            url: '<?php echo base_url() ?>reportes/generaPdfEntradasSalidas',
            method: "get",
            data: $('#miFormulario').serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(resultado) {
                // $('#modal_reporte').html('<object class="PDFdoc" width="100%" height="500px" type="application/pdf" data="' + resultado + '"></object>');
                // $('#pdfFrame').attr('src', data);
                // $('#pdfFrame').attr('src',);

                // $('#modal_reporte').html('<embed src="' + resultado + '" type="application/pdf" width="100%" height="400px" />');

                // $('#modal_reporte').html('<iframe src="' + resultado + '" width="100%" height="400px"></iframe>');
                // $('#modal').modal('show');
                // alert(resultado);
            }
        });
        }
    </script>