<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <br />

            <div class="row">
                <div class="col-3">
                    <!-- Contenedor vacío en la columna izquierda -->
                </div>
                <div class="col-6">
                    <!-- Colocamos la imagen del nombre de la empresa -->
                    <img src="<?php echo base_url(); ?>assets/img/nombre_master_pizza.png" alt="nombre_master_pizza" class="img-fluid">
                </div>
                <div class="col-3">
                    <!-- Contenedor vacío en la columna derecha -->
                </div>
            </div>

            <br />

            <div class="row">
                <div class="col-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <?php echo $totalItems; ?> Total de Items
                        </div>
                        <a href="<?php echo base_url(); ?>item" class="card-footer text-white" style="text-decoration: none;">Ver detalles <i class="fa-solid fa-angle-right"></i></a>

                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <?php echo $totalEntradas; ?> Total Entradas
                        </div>
                        <a href="<?php echo base_url(); ?>entrada/nuevo" class="card-footer text-white" style="text-decoration: none;">Ver detalles <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <?php echo $totalSalidas; ?> Total Salidas
                        </div>
                        <a href="<?php echo base_url(); ?>salida/nuevo" class="card-footer text-white" style="text-decoration: none;">Ver detalles <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- ---------------------------------------------------------------->

            <div id="grafica1" class="row">

                <script>
                    $('#grafica1').highcharts({



                        title: {
                            text: 'Movimiento de productos/categorías en la última semana',
                            align: 'left'
                        },



                        yAxis: {
                            title: {
                                text: 'Cantidad de movimientos'
                            }
                        },

                        xAxis: {
                            categories: <?php echo $diasSemanaEsp; ?>,
                            accessibility: {
                                description: 'Months of the year'
                            }
                        },

                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle'
                        },

                        plotOptions: {
                            series: {
                                label: {
                                    connectorAllowed: false
                                },
                            }
                        },

                        series: [
                            <?php echo $series1; ?>
                        ],

                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }]
                        }




                    });
                </script>

            </div>
            <!-- ---------------------------------------------------------------->
            <div id="grafica2" class="row">

                <script>
                    $('#grafica2').highcharts({
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Entradas y Salidas por Producto/Categoría',
                            align: 'left'
                        },

                        xAxis: {
                            categories: <?php echo $nombre_prod; ?>,
                            crosshair: true,
                            accessibility: {
                                description: ''
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Cantidad de Entradas y Salidas'
                            }
                        },
                        tooltip: {
                            valueSuffix: ''
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [{
                                name: 'Entradas',
                                data: <?php echo $cantidadEntradas; ?>
                            },
                            {
                                name: 'Salidas',
                                data: <?php echo $cantidadSalidas; ?>
                            }
                        ]
                    });
                </script>

            </div>
            <!-- ---------------------------------------------------------------->

            <div id="grafica3" class="row">

                <script>
                    $('#grafica3').highcharts({

                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'Items con movimiento'
                        },
                        tooltip: {
                            valueSuffix: '%'
                        },

                        plotOptions: {
                            series: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: [{
                                    enabled: true,
                                    distance: 20
                                }, {
                                    enabled: true,
                                    distance: -40,
                                    format: '{point.percentage:.1f}%',
                                    style: {
                                        fontSize: '1.2em',
                                        textOutline: 'none',
                                        opacity: 0.7
                                    },
                                    filter: {
                                        operator: '>',
                                        property: 'percentage',
                                        value: 10
                                    }
                                }]
                            }
                        },
                        series: [{
                            name: 'Percentage',
                            colorByPoint: true,
                            data: [


                                <?php echo $dataItemsPorcentajes; ?>

                            ]
                        }]



                    });
                </script>
            </div>
            <!-- ---------------------------------------------------------------->

            <!-- <div id="grafica4" class="row">

                <script>
                    $('#grafica4').highcharts({

                    });
                </script>
            </div> -->


            <!-- ---------------------------------------------------------------->

    </main>