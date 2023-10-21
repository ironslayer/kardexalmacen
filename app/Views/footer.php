        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Kardex de Almacén <?php echo date('Y'); ?> Version 1.0</div>
                    <!-- <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div> -->
                </div>
            </div>
        </footer>

        </div>
        </div>

        <script src="<?php echo base_url(); ?>js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>js/scripts.js"></script>


        <script>
            var modalConfirma = $('#modalConfirma');

            // Agrega un evento que se active cuando el modal se muestra
            modalConfirma.on('show.bs.modal', function(e) {
                // Encuentra el botón con la clase 'btn-ok' dentro del modal
                var btnOk = modalConfirma.find('.btn-ok');

                // Obtiene el atributo 'data-href' del elemento relacionado (e.relatedTarget)
                var dataHref = $(e.relatedTarget).data('href');

                // Asigna el valor del atributo 'data-href' como el valor del atributo 'href' del botón
                btnOk.attr('href', dataHref);
            });
        </script>


        </body>

        </html>