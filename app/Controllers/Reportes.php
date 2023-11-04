<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SalidaModel;
use App\Models\TsalidaModel;
use App\Models\EntradaModel;
use App\Models\TentradaModel;
use App\Models\ProveedorModel;
use App\Models\ProductoModel;
use App\Models\UsuarioModel;
use App\Models\ItemModel;
use App\Models\UnidadesModel;
use TCPDF;




class Reportes extends BaseController

{
    protected $salida;
    protected $tsalida;
    protected $tentrada;
    protected $entrada;
    protected $proveedor;
    protected $producto;
    protected $usuario;
    protected $item;
    protected $unidadmedida;

    // protected $reglas;


    public function __construct()
    {
        $this->salida = new SalidaModel();
        $this->tsalida = new TsalidaModel();
        $this->tentrada = new TentradaModel();
        $this->entrada = new EntradaModel();
        $this->proveedor = new ProveedorModel();
        $this->producto = new ProductoModel();
        $this->usuario = new UsuarioModel();
        $this->item = new ItemModel();
        $this->unidadmedida = new UnidadesModel();
    }
    //--------------------------------------------------------------------
    //--------------------reporte de kardex disico valorado--------------------
    public function vistaKardexFisicoValorado()
    {
        //obtenemos los datos de todos los items activos ycon total movimiento > 0
        $info = $this->item->where('activo', 1)->where('total_movimiento >', 0)->findAll();
        // Obtenemos el año actual
        $anio_actual = date("Y");

        // Construimos las fechas del presente año
        $fecha_inicio = $anio_actual . '-01-01';
        $fecha_fin = $anio_actual . '-12-31';

        $data = [
            'titulo' => 'Reporte de Kardex Físico Valorado',
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'datos' => $info,
        ];


        echo view('header');
        echo view('reportes/reporteKardexFisicoValorado', $data);
        echo view('footer');
    }

    public function generaPdfKardexFisicoValorado($fecha_inicio, $fecha_fin, $id_item)
    {
        //obtenemos los datos del item
        $item = $this->item->where('id_item', $id_item)->first();
        //obtenemos la unidad de medida del item
        $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();
        //obtenemos todos los datos de la tabla entrada con el id_item y activo = 1
        $this->entrada->where('id_item', $id_item)->where('activo', 1)->findAll();
        //filtra las entradas por fecha inicio y fecha fin
        $entradas = $this->entrada->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();

        //obtenemos todos los datos de la tabla salida con el id_item y activo = 1
        $this->salida->where('id_item', $id_item)->where('activo', 1)->findAll();
        //filtra las salidas por fecha inicio y fecha fin
        $salidas = $this->salida->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();

        //remplazamos '-' por '/' en las fechas
        $fecha_inicio = str_replace('-', '/', $fecha_inicio);
        $fecha_fin = str_replace('-', '/', $fecha_fin);


        // Cargar la base de datos para hacer consultas
        $db = \Config\Database::connect();

        // Crear la consulta
        $query = $db->query('SELECT nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe
        FROM entrada
        WHERE id_item = ' . $id_item . ' and activo = 1
        UNION ALL
        SELECT nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe
        FROM salida
        WHERE id_item = ' . $id_item . ' and activo = 1
        ORDER BY nro_movimiento ASC;');

        // Obtener los resultados
        $results = $query->getResultArray();
        //--------------------------------------------------------------------
        // realizamos el pdf

        $pdf = new \FPDF('P', 'mm', 'letter');
        $pdf->AddPage();



        $margin = 10; // Márgenes de 10 mm
        $pageWidth = 215.9; // Ancho de la página en mm (8.5 pulgadas a mm)
        $pageHeight = 279.4; // Alto de la página en mm (11 pulgadas a mm)

        $pdf->SetMargins($margin, $margin, $margin);

        // Dibuja una línea en el margen superior
        $pdf->Line($margin, $margin, $pageWidth - $margin, $margin);

        // Dibuja una línea en el margen izquierdo
        $pdf->Line($margin, $margin, $margin, $pageHeight - $margin);

        // Dibuja una línea en el margen derecho
        $pdf->Line($pageWidth - $margin, $margin, $pageWidth - $margin, $pageHeight - $margin);

        // Dibuja una línea en el margen inferior
        $pdf->Line($margin, $pageHeight - $margin, $pageWidth - $margin, $pageHeight - $margin);



        $pdf->SetTitle("Kardex Fisico Valorado");
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(15, 5, "MASTER PIZZA", 0, 1, 'L');
        $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(195, 5, utf8_decode("KARDEX FÍSICO VALORADO"), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(195, 5, "(Expresado en Bolivianos)", 0, 1, 'C');
        // $pdf->image(base_url() . 'assets/img/logo_master_pizza.jpg', 188, 10, 18, 18, 'JPG');
        //dibuja una linea debajo
        $pdf->Line(10, 30, 206, 30);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(1, 5, "", 0, 0, 'L');
        $pdf->Cell(40, 5, utf8_decode("CÓDIGO/ID"), 0, 0, 'L');
        $pdf->Cell(123, 5, utf8_decode("ARTÍCULO"), 0, 0, 'L');
        $pdf->Cell(30, 5, utf8_decode("UNIDAD"), 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(2, 5, "", 0, 0, 'L');
        $pdf->Cell(38, 5, $item['id_item'], 1, 0, 'L');
        $pdf->Cell(2, 5, "", 0, 0, 'L');
        $pdf->Cell(121, 5, utf8_decode($item['descripcion']), 1, 0, 'L');
        $pdf->Cell(2, 5, "", 0, 0, 'L');
        $pdf->Cell(28, 5, utf8_decode($unidad['nombre_unidad']), 1, 0, 'L');
        $pdf->Cell(2, 5, "", 0, 0, 'L');
        $pdf->Ln(6);
        //dibuja una linea debajo
        // $pdf->Line(10, 41, 206, 41);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(16, 16, "Fecha", 1, 0, 'C');
        $pdf->Cell(8, 16, "E/S", 1, 0, 'C');
        $pdf->Cell(27, 16, "Referencia", 1, 0, 'C');
        // $pdf->Cell(10, 16, "No. E/S", 1, 0, 'C');

        //------

    
       
        // recuerda la posición x e y antes de escribir la multicelda
        $xPos = $pdf->GetX();
        $yPos = $pdf->GetY();
        $pdf->MultiCell(10, 8, "No. E/S", 1, 'C');
        // $pdf->Cell(15, 16, "Costo Ponderado", 1, 0, 'C');


        // devuelve la posición para la siguiente celda al lado de la multicelda
        // y ajusta la x con el ancho de la multicelda
        $pdf->SetXY($xPos + 10, $yPos);


        //-------


        //------

    
    
        // recuerda la posición x e y antes de escribir la multicelda
        $xPos = $pdf->GetX();
        $yPos = $pdf->GetY();
        $pdf->MultiCell(15, 5.31, "Costo Promedio", 1, 'C');
        // $pdf->Cell(15, 16, "Costo Ponderado", 1, 0, 'C');


        // devuelve la posición para la siguiente celda al lado de la multicelda
        // y ajusta la x con el ancho de la multicelda
        $pdf->SetXY($xPos + 15, $yPos);


        //-------





        $pdf->Cell(40, 8, "ENTRADAS", 1, 0, 'C');
        $pdf->Cell(40, 8, "SALIDAS", 1, 0, 'C');
        $pdf->Cell(40, 8, "SALDOS", 1, 0, 'C');
        $pdf->Cell(0, 8, "", 0, 1);
        $pdf->Cell(76, 8, "", 0, 0);
        $pdf->Cell(20, 8, "Cantidad", 1, 0, 'C');
        $pdf->Cell(20, 8, "Valor", 1, 0, 'C');
        $pdf->Cell(20, 8, "Cantidad", 1, 0, 'C');
        $pdf->Cell(20, 8, "Valor", 1, 0, 'C');
        $pdf->Cell(20, 8, "Cantidad", 1, 0, 'C');
        $pdf->Cell(20, 8, "Valor", 1, 1, 'C');

        $saldoCantidad = 0;
        $saldoValor = 0;
        $costoPromedio = 0;

        $sumaCantidadEntradas = 0;
        $sumaValorEntradas = 0;
        $sumaCantidadSalidas = 0;
        $sumaValorSalidas = 0;

        foreach ($results as $result) {
            //salto de linea
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(16, 8, str_replace('-', '/', $result['fecha']), 1, 0, 'C');
            $pdf->Cell(8, 8, $result['e_s'], 1, 0, 'C');
            if($result['e_s'] == 'E'){
                //buscamos la entrada con activo = 1 con $result['nro_movimiento'] y $result['id_item']
                $entrada = $this->entrada->where('nro_movimiento', $result['nro_movimiento'])->where('id_item', $result['id_item'])->where('activo', 1)->first();

                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(27, 2.62, utf8_decode($entrada['concepto']), 1, 'L');
                // $pdf->Cell(15, 16, "Costo Ponderado", 1, 0, 'C');
        
        
                // devuelve la posición para la siguiente celda al lado de la multicelda
                // y ajusta la x con el ancho de la multicelda
                $pdf->SetXY($xPos + 27, $yPos);

                // $pdf->Cell(27, 8, utf8_decode($entrada['concepto']), 1, 0, 'L');
                // $pdf->Cell(27, 8, "Entrada", 1, 0, 'L');


                $pdf->Cell(10, 8, $entrada['id_entrada'], 1, 0, 'C');

                $saldoCantidad += $entrada['cantidad'];
                $saldoValor += $entrada['importe'];
                $costoPromedio = $saldoValor / $saldoCantidad;
                $sumaCantidadEntradas += $entrada['cantidad'];
                $sumaValorEntradas += $entrada['importe'];

                $pdf->Cell(15, 8, number_format($costoPromedio, 3), 1, 0, 'R');

                $pdf->Cell(20, 8, number_format($entrada['cantidad'], 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($entrada['importe'], 3), 1, 0, 'R');
                $pdf->Cell(20, 8, "", 1, 0, 'C');
                $pdf->Cell(20, 8, "", 1, 0, 'C');
                $pdf->Cell(20, 8, number_format($saldoCantidad, 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($saldoValor, 3), 1, 1, 'R');


            }else{
                //buscamos la salida con activo = 1 con $result['nro_movimiento'] y $result['id_item']
                $salida = $this->salida->where('nro_movimiento', $result['nro_movimiento'])->where('id_item', $result['id_item'])->where('activo', 1)->first();

                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(27, 2.62, utf8_decode($salida['concepto']), 1, 'L');
                // $pdf->Cell(15, 16, "Costo Ponderado", 1, 0, 'C');
        
        
                // devuelve la posición para la siguiente celda al lado de la multicelda
                // y ajusta la x con el ancho de la multicelda
                $pdf->SetXY($xPos + 27, $yPos);


                // $pdf->Cell(27, 8, utf8_decode($salida['concepto']), 1, 0, 'L');
                // $pdf->Cell(27, 8, "Salida", 1, 0, 'L');



                $pdf->Cell(10, 8, $salida['id_salida'], 1, 0, 'C');

                $saldoCantidad -= $salida['cantidad'];
                $saldoValor -= $salida['importe'];
                $costoPromedio = $saldoValor / $saldoCantidad;
                $sumaCantidadSalidas += $salida['cantidad'];
                $sumaValorSalidas += $salida['importe'];

                $pdf->Cell(15, 8, number_format($costoPromedio, 3), 1, 0, 'R');

                $pdf->Cell(20, 8, "", 1, 0, 'C');
                $pdf->Cell(20, 8, "", 1, 0, 'C');
                $pdf->Cell(20, 8, number_format($salida['cantidad'], 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($salida['importe'], 3), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($saldoCantidad, 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($saldoValor, 3), 1, 1, 'R');
            }

        }
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->Cell(76, 9, "TOTAL", 1, 0, 'C');
        $pdf->Cell(20, 9, number_format($sumaCantidadEntradas, 2), 1, 0, 'R');
        $pdf->Cell(20, 9, number_format($sumaValorEntradas, 3), 1, 0, 'R');
        $pdf->Cell(20, 9, number_format($sumaCantidadSalidas, 2), 1, 0, 'R');
        $pdf->Cell(20, 9, number_format($sumaValorSalidas, 3), 1, 0, 'R');
        $pdf->Cell(20, 9, "", 1, 0);
        $pdf->Cell(20, 9, "", 1, 1);











        $this->response->setHeader('Content-Type', 'application/pdf');
        //ahora devolvemos el pdf para que lo reciba el ajax
        $pdf->Output("reporteEntradas.pdf", "I");
    }

    //--------------------------------------------------------------------
    //--------------------reporte de resumen kardex--------------------
    public function vistaResumenKardex()
    {
    }
    //--------------------------------------------------------------------
    //--------------------reporte general--------------------
    public function vistaReporteGeneral()
    {
    }
    //--------------------------------------------------------------------
    //--------------------reporte de entrada y salida--------------------
    public function vistaReporteEntradaSalida()
    {
        // Obtenemos el año actual
        $anio_actual = date("Y");

        // Construimos las fechas del presente año
        $fecha_inicio = $anio_actual . '-01-01';
        $fecha_fin = $anio_actual . '-12-31';

        $data = [
            'titulo' => 'Reporte de Entradas y Salidas',
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ];

        echo view('header');
        echo view('reportes/reporteEntradaSalida', $data);
        echo view('footer');
    }

    public function generaPdfEntradasSalidas($fecha_inicio, $fecha_fin, $tipo)
    {

        //preguntamos si el tipo de reporte es entrada o salida
        if ($tipo == 'entrada') {
            //obtenemos  todos los datos de las entradas con activo = 1
            $this->entrada->where('activo', 1)->findAll();
            //convertimos fecha inicio y fecha fin al timestamp
            // $fecha_inicioTimestamp = strtotime($fecha_inicio . ' 00:00:00');
            // $fecha_finTimestamp = strtotime($fecha_fin . ' 23:59:59');
            // ahora filtramos todas las entradas que tengan la fecha_alta entre los rangos de fecha_inicio y fecha_fin
            $entradas = $this->entrada->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
            //remplazamos '-' por '/' en las fechas
            $fecha_inicio = str_replace('-', '/', $fecha_inicio);
            $fecha_fin = str_replace('-', '/', $fecha_fin);

            //imprimimos entradas
            // print_r($entradas);

            // realizamos el pdf

            $pdf = new \FPDF('P', 'mm', 'letter');
            $pdf->AddPage();



            $margin = 10; // Márgenes de 10 mm
            $pageWidth = 215.9; // Ancho de la página en mm (8.5 pulgadas a mm)
            $pageHeight = 279.4; // Alto de la página en mm (11 pulgadas a mm)

            $pdf->SetMargins($margin, $margin, $margin);

            // Dibuja una línea en el margen superior
            $pdf->Line($margin, $margin, $pageWidth - $margin, $margin);

            // Dibuja una línea en el margen izquierdo
            $pdf->Line($margin, $margin, $margin, $pageHeight - $margin);

            // Dibuja una línea en el margen derecho
            $pdf->Line($pageWidth - $margin, $margin, $pageWidth - $margin, $pageHeight - $margin);

            // Dibuja una línea en el margen inferior
            $pdf->Line($margin, $pageHeight - $margin, $pageWidth - $margin, $pageHeight - $margin);



            $pdf->SetTitle("Reporte de Entradas");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 1, 'L');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("RESUMEN DE ENTRADAS AL ALMACÉN"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(196, 5, "Del " . $fecha_inicio . " al " . $fecha_fin, 0, 1, 'C');
            // $pdf->image(base_url() . 'assets/img/logo_master_pizza.jpg', 188, 10, 18, 18, 'JPG');
            //dibuja una linea debajo
            $pdf->Line(10, 30, 205, 30);

            //creamos una tabla de 6 columnas
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->Cell(14, 10, 'No', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Fecha', 1, 0, 'C');
            $pdf->Cell(67, 10, 'Referencia', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Imp. Factura (Bs.)', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Imp. IVA (Bs.)', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Importe (Bs.)', 1, 0, 'C');

            $cont = 1;
            //contador de los precios unitarios
            $total_precios = 0;
            //contador de los costos unitarios
            $total_importes = 0;
            //contador de los precios unitarios iva
            $total__iva = 0;
            foreach ($entradas as $entrada) {
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 7);

                $total_precios += $entrada['total_precio'];
                $total_importes += $entrada['importe'];
                $total__iva += number_format($entrada['total_precio'] - $entrada['importe'], 2);

                $pdf->Cell(14, 7, $cont, 1, 0, 'C');
                $cont++;
                $pdf->Cell(25, 7, str_replace('-', '/', $entrada['fecha']), 1, 0, 'C');

                //buscamos la descripcion de la entrada en items
                $item = $this->item->where('id_item', $entrada['id_item'])->first();
                //creamos la columna $item['descripcion'] pero con formato utf8  para que no salgan caracteres raros
                $pdf->Cell(67, 7, utf8_decode($item['descripcion']), 1, 0, 'L');
                // $pdf->Cell(67, 7, $item['descripcion'], 1, 0, 'L');

                $pdf->Cell(30, 7, number_format($entrada['total_precio'], 2), 1, 0, 'C');
                $pdf->Cell(30, 7, number_format($entrada['total_precio'] - $entrada['importe'], 2), 1, 0, 'C');
                $pdf->Cell(30, 7, number_format($entrada['importe'], 2), 1, 0, 'C');
            }
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);

            $pdf->Cell(106, 8, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(30, 8, number_format($total_precios, 2), 1, 0, 'C');
            $pdf->Cell(30, 8, number_format($total__iva, 2), 1, 0, 'C');
            $pdf->Cell(30, 8, number_format($total_importes, 2), 1, 0, 'C');






            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteEntradas.pdf", "I");
        } else {

            //obtenemos  todos los datos de las entradas con activo = 1
            $this->salida->where('activo', 1)->findAll();
            //convertimos fecha inicio y fecha fin al timestamp
            // $fecha_inicioTimestamp = strtotime($fecha_inicio . ' 00:00:00');
            // $fecha_finTimestamp = strtotime($fecha_fin . ' 23:59:59');
            // ahora filtramos todas las entradas que tengan la fecha_alta entre los rangos de fecha_inicio y fecha_fin
            $salidas = $this->salida->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
            //remplazamos '-' por '/' en las fechas
            $fecha_inicio = str_replace('-', '/', $fecha_inicio);
            $fecha_fin = str_replace('-', '/', $fecha_fin);

            //imprimimos entradas
            // print_r($entradas);

            // realizamos el pdf

            $pdf = new \FPDF('P', 'mm', 'letter');
            $pdf->AddPage();



            $margin = 10; // Márgenes de 10 mm
            $pageWidth = 215.9; // Ancho de la página en mm (8.5 pulgadas a mm)
            $pageHeight = 279.4; // Alto de la página en mm (11 pulgadas a mm)

            $pdf->SetMargins($margin, $margin, $margin);

            // Dibuja una línea en el margen superior
            $pdf->Line($margin, $margin, $pageWidth - $margin, $margin);

            // Dibuja una línea en el margen izquierdo
            $pdf->Line($margin, $margin, $margin, $pageHeight - $margin);

            // Dibuja una línea en el margen derecho
            $pdf->Line($pageWidth - $margin, $margin, $pageWidth - $margin, $pageHeight - $margin);

            // Dibuja una línea en el margen inferior
            $pdf->Line($margin, $pageHeight - $margin, $pageWidth - $margin, $pageHeight - $margin);



            $pdf->SetTitle("Reporte de Salidas");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 1, 'L');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("RESUMEN DE SALIDAS AL ALMACÉN"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(195, 5, "Del " . $fecha_inicio . " al " . $fecha_fin, 0, 1, 'C');
            // $pdf->image(base_url() . 'assets/img/logo_master_pizza.jpg', 188, 10, 18, 18, 'JPG');
            //dibuja una linea debajo
            $pdf->Line(10, 30, 205, 30);

            //creamos una tabla de 6 columnas
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->Cell(14, 10, 'No', 1, 0, 'C');
            $pdf->Cell(25, 10, 'Fecha', 1, 0, 'C');
            $pdf->Cell(127, 10, 'Referencia', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Importe (Bs.)', 1, 0, 'C');

            $cont = 1;

            //contador de importes
            $total_importes = 0;

            foreach ($salidas as $salida) {
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 7);

                $total_importes += $salida['importe'];


                $pdf->Cell(14, 7, $cont, 1, 0, 'C');
                $cont++;
                $pdf->Cell(25, 7, str_replace('-', '/', $salida['fecha']), 1, 0, 'C');

                //buscamos la descripcion de la entrada en items
                $item = $this->item->where('id_item', $salida['id_item'])->first();
                //creamos la columna $item['descripcion'] pero con formato utf8  para que no salgan caracteres raros
                $pdf->Cell(127, 7, utf8_decode($item['descripcion']), 1, 0, 'L');
                // $pdf->Cell(67, 7, $item['descripcion'], 1, 0, 'L');

                $pdf->Cell(30, 7, number_format($salida['importe'], 2), 1, 0, 'C');
            }
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);

            $pdf->Cell(166, 8, 'TOTAL', 1, 0, 'L');

            $pdf->Cell(30, 8, number_format($total_importes, 2), 1, 0, 'C');



            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteSalidas.pdf", "I");
        }
    }
    //--------------------------------------------------------------------
    //--------------------reporte de administracion--------------------

    public function vistaReporteAdministracion()
    {
    }
}
