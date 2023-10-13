        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Kardex de Almacén <?php  echo date('Y'); ?> Version 1.0</div>
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
<script src="<?php echo base_url(); ?>js/simple-datatables.min.js"></script>
<script src="<?php echo base_url(); ?>js/datatables-simple-demo.js"></script>
<script>
    // Obtén una referencia al elemento modal
    var modalConfirma = document.getElementById('modalConfirma');

    // Agrega un evento que se active cuando el modal se muestra
    modalConfirma.addEventListener('show.bs.modal', function (e) {
        // Encuentra el botón con la clase 'btn-ok' dentro del modal
        var btnOk = modalConfirma.querySelector('.btn-ok');
        
        // Obtiene el atributo 'data-href' del elemento relacionado (e.relatedTarget)
        var dataHref = e.relatedTarget.getAttribute('data-href');
        
        // Asigna el valor del atributo 'data-href' como el valor del atributo 'href' del botón
        btnOk.setAttribute('href', dataHref);
    });

</script>
</body>

</html>