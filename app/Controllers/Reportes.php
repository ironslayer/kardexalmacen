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
        $pdf->SetFont('Arial', '', 7.5);
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

            if ($result['e_s'] == 'E') {

                //buscamos la entrada con activo = 1 con $result['nro_movimiento'] y $result['id_item']
                $entrada = $this->entrada->where('nro_movimiento', $result['nro_movimiento'])->where('id_item', $result['id_item'])->where('activo', 1)->first();
                $pdf->SetFont('Arial', '', 7);

                $cellWidth = 27; // Ancho de celda envuelta
                $cellHeight = 3; // Altura de una línea normal de celda
                //salto de linea
                // Comprobar si el texto se desborda
                if ($pdf->GetStringWidth($entrada['concepto']) < $cellWidth) {
                    // Si no, no hacer nada
                    $line = 1;
                } else {
                    // Si es así, calcular la altura necesaria para la celda envuelta
                    // dividiendo el texto para que quepa en el ancho de la celda
                    // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                    $textLength = strlen($entrada['concepto']);    // Longitud total del texto
                    $errMargin = 9;        // Margen de error del ancho de la celda, por si acaso
                    $startChar = 0;        // Posición de inicio de caracteres para cada línea
                    $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                    $textArray = array();    // para mantener las cadenas de cada línea
                    $tmpString = "";        // para mantener la cadena de una línea (temporal)

                    while ($startChar < $textLength) { // bucle hasta el final del texto
                        // bucle hasta que se alcance el máximo de caracteres
                        while (
                            $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                            ($startChar + $maxChar) < $textLength
                        ) {
                            $maxChar++;
                            $tmpString = substr($entrada['concepto'], $startChar, $maxChar);
                        }
                        // mueve startChar a la próxima línea
                        $startChar = $startChar + $maxChar;
                        // luego agréguelo al array para saber cuántas líneas son necesarias
                        array_push($textArray, $tmpString);
                        // restablecer maxChar y tmpString
                        $maxChar = 0;
                        $tmpString = '';
                    }
                    // obtener el número de líneas
                    $line = count($textArray);
                }

                ///-------
                $pdf->Cell(16, ($line * $cellHeight), str_replace('-', '/', $result['fecha']), 1, 0, 'C');
                $pdf->Cell(8, ($line * $cellHeight), $result['e_s'], 1, 0, 'C');


                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                // $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($entrada['concepto']), 1, 'L');
                $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($entrada['concepto']), 1, 'C');


                // devuelve la posición para la siguiente celda al lado de la multicelda
                // y ajusta la x con el ancho de la multicelda

                $pdf->SetXY($xPos + $cellWidth, $yPos);

                // $pdf->Cell(27, 8, utf8_decode($entrada['concepto']), 1, 0, 'L');
                // $pdf->Cell(27, 8, "Entrada", 1, 0, 'L');


                $pdf->Cell(10, ($line * $cellHeight), $entrada['id_entrada'], 1, 0, 'C');

                $saldoCantidad += $entrada['cantidad'];
                $saldoValor += $entrada['importe'];
                $costoPromedio = $saldoValor / $saldoCantidad;
                $sumaCantidadEntradas += $entrada['cantidad'];
                $sumaValorEntradas += $entrada['importe'];

                $pdf->Cell(15, ($line * $cellHeight), number_format($costoPromedio, 3), 1, 0, 'R');

                $pdf->Cell(20, ($line * $cellHeight), number_format($entrada['cantidad'], 2), 1, 0, 'R');
                $pdf->Cell(20, ($line * $cellHeight), number_format($entrada['importe'], 3), 1, 0, 'R');
                $pdf->Cell(20, ($line * $cellHeight), "", 1, 0, 'C');
                $pdf->Cell(20, ($line * $cellHeight), "", 1, 0, 'C');
                $pdf->Cell(20, ($line * $cellHeight), number_format($saldoCantidad, 2), 1, 0, 'R');
                $pdf->Cell(20, ($line * $cellHeight), number_format($saldoValor, 3), 1, 1, 'R');
            } else {

                //buscamos la salida con activo = 1 con $result['nro_movimiento'] y $result['id_item']
                $salida = $this->salida->where('nro_movimiento', $result['nro_movimiento'])->where('id_item', $result['id_item'])->where('activo', 1)->first();

                $cellWidth2 = 27; // Ancho de celda envuelta
                $cellHeight2 = 3; // Altura de una línea normal de celda

                // Comprobar si el texto se desborda
                if ($pdf->GetStringWidth($salida['concepto']) < $cellWidth2) {
                    // Si no, no hacer nada
                    $line2 = 1;
                } else {
                    // Si es así, calcular la altura necesaria para la celda envuelta
                    // dividiendo el texto para que quepa en el ancho de la celda
                    // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                    $textLength = strlen($salida['concepto']);    // Longitud total del texto
                    $errMargin = 9;        // Margen de error del ancho de la celda, por si acaso
                    $startChar = 0;        // Posición de inicio de caracteres para cada línea
                    $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                    $textArray = array();    // para mantener las cadenas de cada línea
                    $tmpString = "";        // para mantener la cadena de una línea (temporal)

                    while ($startChar < $textLength) { // bucle hasta el final del texto
                        // bucle hasta que se alcance el máximo de caracteres
                        while (
                            $pdf->GetStringWidth($tmpString) < ($cellWidth2 - $errMargin) &&
                            ($startChar + $maxChar) < $textLength
                        ) {
                            $maxChar++;
                            $tmpString = substr($salida['concepto'], $startChar, $maxChar);
                        }
                        // mueve startChar a la próxima línea
                        $startChar = $startChar + $maxChar;
                        // luego agréguelo al array para saber cuántas líneas son necesarias
                        array_push($textArray, $tmpString);
                        // restablecer maxChar y tmpString
                        $maxChar = 0;
                        $tmpString = '';
                    }
                    // obtener el número de líneas
                    $line2 = count($textArray);
                }

                ////---------
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(16, ($line2 * $cellHeight2), str_replace('-', '/', $result['fecha']), 1, 0, 'C');
                $pdf->Cell(8, ($line2 * $cellHeight2), $result['e_s'], 1, 0, 'C');


                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell($cellWidth2, $cellHeight2, utf8_decode($salida['concepto']), 1, 'C');
                // $pdf->Cell(27, 10, utf8_decode($salida['concepto']), 1, 'L');



                // devuelve la posición para la siguiente celda al lado de la multicelda
                // y ajusta la x con el ancho de la multicelda

                $pdf->SetXY($xPos + $cellWidth2, $yPos);


                // $pdf->Cell(27, 8, utf8_decode($salida['concepto']), 1, 0, 'L');
                // $pdf->Cell(27, 8, "Salida", 1, 0, 'L');



                $pdf->Cell(10, ($line2 * $cellHeight2), $salida['id_salida'], 1, 0, 'C');

                $saldoCantidad -= $salida['cantidad'];
                $saldoValor -= $salida['importe'];
                $costoPromedio = $saldoValor / $saldoCantidad;
                $sumaCantidadSalidas += $salida['cantidad'];
                $sumaValorSalidas += $salida['importe'];

                $pdf->Cell(15, ($line2 * $cellHeight2), number_format($costoPromedio, 3), 1, 0, 'R');

                $pdf->Cell(20, ($line2 * $cellHeight2), "", 1, 0, 'C');
                $pdf->Cell(20, ($line2 * $cellHeight2), "", 1, 0, 'C');
                $pdf->Cell(20, ($line2 * $cellHeight2), number_format($salida['cantidad'], 2), 1, 0, 'R');
                $pdf->Cell(20, ($line2 * $cellHeight2), number_format($salida['importe'], 3), 1, 0, 'R');
                $pdf->Cell(20, ($line2 * $cellHeight2), number_format($saldoCantidad, 2), 1, 0, 'R');
                $pdf->Cell(20, ($line2 * $cellHeight2), number_format($saldoValor, 3), 1, 1, 'R');
            }
        }
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->Cell(76, 9, "TOTAL", 1, 0, 'C');
        $pdf->Cell(20, 9, number_format($sumaCantidadEntradas, 2), 1, 0, 'R');
        $pdf->Cell(20, 9, number_format($sumaValorEntradas, 3), 1, 0, 'R');
        $pdf->Cell(20, 9, number_format($sumaCantidadSalidas, 2), 1, 0, 'R');
        $pdf->Cell(20, 9, number_format($sumaValorSalidas, 3), 1, 0, 'R');
        $pdf->Cell(40, 9, "", 1, 1);


        $this->response->setHeader('Content-Type', 'application/pdf');
        //ahora devolvemos el pdf al navegador
        $pdf->Output("reporteKardexFisicoValorado.pdf", "I");
    }






    //--------------------------------------------------------------------
    //--------------------reporte de resumen kardex--------------------
    public function vistaResumenKardex()
    {
        // Obtenemos el año actual
        $anio_actual = date("Y");

        // Construimos las fechas del presente año
        $fecha_inicio = $anio_actual . '-01-01';
        $fecha_fin = $anio_actual . '-12-31';

        $data = [
            'titulo' => 'Reporte de Resumen Kardex',
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ];

        echo view('header');
        echo view('reportes/reporteResumenKardex', $data);
        echo view('footer');
    }

    public function generaPdfResumenKardex($fecha_inicio, $fecha_fin)
    {
        //obtenemos todos los datos de la tabla item con activo = 1  y total_movimiento > 0
        $Items = $this->item->where('activo', 1)->where('total_movimiento >', 0)->findAll();

        //reemplazamos '-' por '/' en las fechas
        $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
        $fecha_finMod = str_replace('-', '/', $fecha_fin);


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



        $pdf->SetTitle("Resumen del Kardex Fisico Valorado");
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(15, 5, "MASTER PIZZA", 0, 1, 'L');
        $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(195, 5, utf8_decode("RESUMEN DEL KARDEX FÍSICO VALORADO"), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(196, 5, "MOVIMIENTO DE " . $fecha_inicioMod . " AL " . $fecha_finMod, 0, 1, 'C');
        $pdf->Cell(196, 5, "(Expresado en Bolivianos)", 0, 1, 'C');
        // $pdf->image(base_url() . 'assets/img/logo_master_pizza.jpg', 188, 10, 18, 18, 'JPG');
        //dibuja una linea debajo
        // $pdf->Line(10, 30, 205, 30);


        //dibuja una linea debajo
        // $pdf->Line(10, 41, 206, 41);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(16, 16, utf8_decode("Código"), 1, 0, 'C');
        $pdf->Cell(42, 16, utf8_decode("Descripción"), 1, 0, 'C');
        $pdf->Cell(18, 16, "Unidad", 1, 0, 'C');
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

        foreach ($Items as $item) {

            $cellWidth = 42; // Ancho de celda envuelta
            $cellHeight = 5; // Altura de una línea normal de celda
            // Comprobar si el texto se desborda
            if ($pdf->GetStringWidth($item['descripcion']) < $cellWidth) {
                // Si no, no hacer nada
                $line = 1;
            } else {
                // Si es así, calcular la altura necesaria para la celda envuelta
                // dividiendo el texto para que quepa en el ancho de la celda
                // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                $textLength = strlen($item['descripcion']);    // Longitud total del texto
                $errMargin = 10;        // Margen de error del ancho de la celda, por si acaso
                $startChar = 0;        // Posición de inicio de caracteres para cada línea
                $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                $textArray = array();    // para mantener las cadenas de cada línea
                $tmpString = "";        // para mantener la cadena de una línea (temporal)

                while ($startChar < $textLength) { // bucle hasta el final del texto
                    // bucle hasta que se alcance el máximo de caracteres
                    while (
                        $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($item['descripcion'], $startChar, $maxChar);
                    }
                    // mueve startChar a la próxima línea
                    $startChar = $startChar + $maxChar;
                    // luego agréguelo al array para saber cuántas líneas son necesarias
                    array_push($textArray, $tmpString);
                    // restablecer maxChar y tmpString
                    $maxChar = 0;
                    $tmpString = '';
                }
                // obtener el número de líneas
                $line = count($textArray);
            }
            $pdf->SetFont('Arial', '', 7);

            // escribir las celdas
            $pdf->Cell(16, ($line * $cellHeight), $item['id_item'], 1, 0, 'C'); // adaptar la altura al número de líneas

            // ---------
            //salto de linea
            // $pdf->Cell(16, 8, $item['id_item'], 1, 0, 'C');
            $xPos = $pdf->GetX();
            $yPos = $pdf->GetY();
            $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($item['descripcion']), 1);
            $pdf->SetXY($xPos + $cellWidth, $yPos);
            //buscamos el nombre_unidad con el id_unidadmedida del item
            $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();
            $pdf->Cell(18, ($line * $cellHeight), utf8_decode($unidad['nombre_unidad']), 1, 0, 'C');
            //hallamos la suma de todas las cantidades de las entradas con el id_item y activo = 1
            $sumaCantidadEntradas = 0;
            $sumaValorEntradas = 0;
            //obtenemos todas las entradas con activo = 1, con el id_itemy que la fecha_alta este entre fecha_inicio y fecha_fin
            $entradasItem = $this->entrada->where('id_item', $item['id_item'])->where('activo', 1)->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();

            foreach ($entradasItem as $entradaItem) {
                //sumamos todas las cantidades de las entradas
                $sumaCantidadEntradas += $entradaItem['cantidad'];
                //sumamos todos los importes de las entradas
                $sumaValorEntradas += $entradaItem['importe'];
            }

            //hallamos la suma de todas las cantidades de las salidas con el id_item y activo = 1
            $sumaCantidadSalidas = 0;
            $sumaValorSalidas = 0;
            //obtenemos todas las salidas con activo = 1, con el id_item y que fecha_alta este entre fecha_inicio y fecha_fin
            $salidasItem = $this->salida->where('id_item', $item['id_item'])->where('activo', 1)->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();

            foreach ($salidasItem as $salidaItem) {
                //sumamos todas las cantidades de las salidas
                $sumaCantidadSalidas += $salidaItem['cantidad'];
                //sumamos todos los importes de las salidas
                $sumaValorSalidas += $salidaItem['importe'];
            }

            //hallamos la resta de la suma de las cantidades de las entradas menos la suma de las cantidades de las salidas
            $saldoCantidad = $sumaCantidadEntradas - $sumaCantidadSalidas;

            //hallamos la resta de la suma de los importes de las entradas menos la suma de los importes de las salidas
            $saldoValor = $sumaValorEntradas - $sumaValorSalidas;

            $pdf->Cell(20, ($line * $cellHeight), number_format($sumaCantidadEntradas, 2), 1, 0, 'R');
            $pdf->Cell(20, ($line * $cellHeight), number_format($sumaValorEntradas, 3), 1, 0, 'R');
            $pdf->Cell(20, ($line * $cellHeight), number_format($sumaCantidadSalidas, 2), 1, 0, 'R');
            $pdf->Cell(20, ($line * $cellHeight), number_format($sumaValorSalidas, 3), 1, 0, 'R');
            $pdf->Cell(20, ($line * $cellHeight), number_format($saldoCantidad, 2), 1, 0, 'R');
            $pdf->Cell(20, ($line * $cellHeight), number_format($saldoValor, 3), 1, 1, 'R');
        }

        $pdf->SetFont('Arial', 'B', 9);


        $sumaTotalValorEntradas = 0;
        //hallamos  todas las entradas con activo = 1 y que fecha_alta este entre fecha_inicio y fecha_fin
        $entradasGeneral = $this->entrada->where('activo', 1)->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();

        foreach ($entradasGeneral as $entrada) {
            //sumamos todos los importes de las entradas
            $sumaTotalValorEntradas += $entrada['importe'];
        }

        $sumaTotalValorSalidas = 0;
        //hallamos  todas las salidas con activo = 1 y que fecha_alta este entre fecha_inicio y fecha_fin
        $salidasGeneral = $this->salida->where('activo', 1)->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
        foreach ($salidasGeneral as $salida) {
            //sumamos todos los importes de las salidas
            $sumaTotalValorSalidas += $salida['importe'];
        }



        //hallamos la resta de la suma de los importes de las entradas menos la suma de los importes de las salidas
        $saldoTotalValor = $sumaTotalValorEntradas - $sumaTotalValorSalidas;

        $pdf->Cell(76, 9, "TOTAL", 1, 0, 'C');
        $pdf->Cell(40, 9, number_format($sumaTotalValorEntradas, 3), 1, 0, 'R');
        $pdf->Cell(40, 9, number_format($sumaTotalValorSalidas, 3), 1, 0, 'R');
        $pdf->Cell(40, 9, number_format($saldoTotalValor, 3), 1, 1, 'R');





        $this->response->setHeader('Content-Type', 'application/pdf');
        //ahora devolvemos el pdf para que lo reciba el ajax
        $pdf->Output("reporResumenKardex.pdf", "I");
    }











    //--------------------------------------------------------------------
    //--------------------reporte general--------------------
    public function vistaReporteGeneral()
    {
        // Obtenemos el año actual
        $anio_actual = date("Y");

        // Construimos las fechas del presente año
        $fecha_inicio = $anio_actual . '-01-01';
        $fecha_fin = $anio_actual . '-12-31';

        $data = [
            'titulo' => 'Reportes Generales',
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ];

        echo view('header');
        echo view('reportes/reporteGeneral', $data);
        echo view('footer');
    }

    public function generaPdfGeneral($fecha_inicio, $fecha_fin, $tipo)
    {

        if ($tipo == 'producto') {


            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);


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



            $pdf->SetTitle("Reporte General de Categoria");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 1, 'L');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("RESUMEN DEL KARDEX FÍSICO VALORADO"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(196, 5, "MOVIMIENTO DE " . $fecha_inicioMod . " AL " . $fecha_finMod, 0, 1, 'C');
            $pdf->Cell(196, 5, "(Expresado en Bolivianos)", 0, 1, 'C');
            // $pdf->image(base_url() . 'assets/img/logo_master_pizza.jpg', 188, 10, 18, 18, 'JPG');


            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(16, 16, utf8_decode("Código"), 1, 0, 'C');
            $pdf->Cell(60, 16, utf8_decode("Descripción"), 1, 0, 'C');

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

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(196, 8, utf8_decode("ALMACÉN CENTRAL"), 0, 1, 'L');
            //obtenemos todos los datos de la tabla producto con activo = 1
            $productos = $this->producto->where('activo', 1)->findAll();

            //suma de totales
            $sumaTotalValorEntradas = 0;
            $sumaTotalValorSalidas = 0;

            foreach ($productos as $producto) {

                $pdf->Cell(16, 8, $producto['id_producto'], 1, 0, 'C');
                $pdf->Cell(60, 8, utf8_decode($producto['nombre_producto']), 1, 0, 'L');

                // Cargar la base de datos para hacer consultas
                $db = \Config\Database::connect();
                $id_prod = $producto['id_producto'];

                //fecha inicio y fecha fin los convertimos a cadenas
                $fecha_inicio = strval($fecha_inicio);
                $fecha_fin = strval($fecha_fin);


                // Crear la consulta 
                $query = $db->query("SELECT e.id_entrada, e.cantidad, e.importe, e.id_item, e.activo, e.fecha_alta
                FROM entrada AS e
                JOIN item AS i ON e.id_item = i.id_item
                WHERE i.id_producto = '$id_prod' AND e.activo = 1
                AND e.fecha_alta BETWEEN '$fecha_inicio' AND '$fecha_fin';");

                // Obtener los resultados
                $results = $query->getResultArray();

                // print_r($results);

                $totalCantidadEntradas = 0;
                $totalImporteEntradas = 0;



                foreach ($results as $result) {
                    $totalCantidadEntradas += $result['cantidad'];
                    $totalImporteEntradas += $result['importe'];
                }
                //sumamos todos los importes de las entradas
                $sumaTotalValorEntradas += $totalImporteEntradas;

                $pdf->Cell(20, 8, number_format($totalCantidadEntradas, 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($totalImporteEntradas, 3), 1, 0, 'R');

                //creamos la consulta para obtener las salidas
                $query2 = $db->query("SELECT s.id_salida, s.cantidad, s.importe, s.id_item, s.activo, s.fecha_alta
                FROM salida AS s
                JOIN item AS i ON s.id_item = i.id_item
                WHERE i.id_producto = '$id_prod' AND s.activo = 1
                AND s.fecha_alta BETWEEN '$fecha_inicio' AND '$fecha_fin';");

                // Obtener los resultados
                $results2 = $query2->getResultArray();

                $totalCantidadSalidas = 0;
                $totalImporteSalidas = 0;

                foreach ($results2 as $result2) {
                    $totalCantidadSalidas += $result2['cantidad'];
                    $totalImporteSalidas += $result2['importe'];
                }
                //sumamos todos los importes de las salidas
                $sumaTotalValorSalidas += $totalImporteSalidas;

                $pdf->Cell(20, 8, number_format($totalCantidadSalidas, 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($totalImporteSalidas, 3), 1, 0, 'R');


                //hallamos las cantidades de saldos
                $saldoCantidad = $totalCantidadEntradas - $totalCantidadSalidas;
                $saldoValor = $totalImporteEntradas - $totalImporteSalidas;

                $pdf->Cell(20, 8, number_format($saldoCantidad, 2), 1, 0, 'R');
                $pdf->Cell(20, 8, number_format($saldoValor, 3), 1, 1, 'R');
            }
            //totales
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(76, 9, "TOTAL", 1, 0, 'C');
            $pdf->Cell(40, 9, number_format($sumaTotalValorEntradas, 3), 1, 0, 'R');
            $pdf->Cell(40, 9, number_format($sumaTotalValorSalidas, 3), 1, 0, 'R');
            $pdf->Cell(40, 9, number_format($sumaTotalValorEntradas - $sumaTotalValorSalidas, 3), 1, 1, 'R');

            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteGeneralCategoria.pdf", "I");
        } else { //==================si es por categoria==================

            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);


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



            $pdf->SetTitle("Reporte de Movimiento por Items");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 1, 'L');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("REPORTE DE MOVIMIENTO POR ITEMS"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(196, 5, "MOVIMIENTO DE " . $fecha_inicioMod . " AL " . $fecha_finMod, 0, 1, 'C');
            $pdf->Cell(196, 5, "(Expresado en Bolivianos)", 0, 1, 'C');
            // $pdf->image(base_url() . 'assets/img/logo_master_pizza.jpg', 188, 10, 18, 18, 'JPG');
            //dibuja una linea debajo
            // $pdf->Line(10, 30, 205, 30);



            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(16, 16, utf8_decode("Código"), 1, 0, 'C');
            $pdf->Cell(42, 16, utf8_decode("Descripción"), 1, 0, 'C');
            $pdf->Cell(18, 16, "Unidad", 1, 0, 'C');
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

            $pdf->Cell(196, 8, utf8_decode("ALMACÉN CENTRAL"), 0, 1, 'L');
            //obtenemos todos los datos de la tabla producto con activo = 1
            $productos = $this->producto->where('activo', 1)->findAll();

            //suma de totales
            $sumaTotalValorEntradas = 0;
            $sumaTotalValorSalidas = 0;
            $sumaTotalValorSaldo = 0;

            foreach ($productos as $producto) {
                $pdf->SetFont('Arial', '', 8);


                $pdf->Cell(196, 10, $producto['id_producto'] . " - " . $producto['nombre_producto'], 0, 1, 'L');

                // Cargar la base de datos para hacer consultas
                $db = \Config\Database::connect();
                $id_prod = $producto['id_producto'];

                //fecha inicio y fecha fin los convertimos a cadenas
                $fecha_inicio = strval($fecha_inicio);
                $fecha_fin = strval($fecha_fin);


                // Crear la consulta 
                $query = $db->query("SELECT MAX(e.id_entrada) as id_entrada, e.id_item, e.activo, MAX(e.fecha_alta) as fecha_alta
                 FROM entrada AS e
                 JOIN item AS i ON e.id_item = i.id_item
                 WHERE i.id_producto = '$id_prod' AND e.activo = 1
                 AND e.fecha_alta BETWEEN '$fecha_inicio' AND '$fecha_fin'
                 GROUP BY e.id_item;");

                // Obtener los resultados
                $results = $query->getResultArray();

                $sumaValorEntradas = 0;
                $sumaValorSalidas = 0;
                $sumaValorSaldo = 0;



                foreach ($results as $result) {

                    //obtenemos el item con el id_item
                    $item = $this->item->where('id_item', $result['id_item'])->first();
                    //obtenemos la unidad de medida con el id_unidadmedida del item
                    $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();

                    $cellWidth = 42; // Ancho de celda envuelta
                    $cellHeight = 5; // Altura de una línea normal de celda

                    // Comprobar si el texto se desborda
                    if ($pdf->GetStringWidth($item['descripcion']) < $cellWidth) {
                        // Si no, no hacer nada
                        $line = 2;
                    } else {
                        // Si es así, calcular la altura necesaria para la celda envuelta
                        // dividiendo el texto para que quepa en el ancho de la celda
                        // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                        $textLength = strlen($item['descripcion']);    // Longitud total del texto
                        $errMargin = 10;        // Margen de error del ancho de la celda, por si acaso
                        $startChar = 0;        // Posición de inicio de caracteres para cada línea
                        $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                        $textArray = array();    // para mantener las cadenas de cada línea
                        $tmpString = "";        // para mantener la cadena de una línea (temporal)

                        while ($startChar < $textLength) { // bucle hasta el final del texto
                            // bucle hasta que se alcance el máximo de caracteres
                            while (
                                $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                                ($startChar + $maxChar) < $textLength
                            ) {
                                $maxChar++;
                                $tmpString = substr($item['descripcion'], $startChar, $maxChar);
                            }
                            // mueve startChar a la próxima línea
                            $startChar = $startChar + $maxChar;
                            // luego agréguelo al array para saber cuántas líneas son necesarias
                            array_push($textArray, $tmpString);
                            // restablecer maxChar y tmpString
                            $maxChar = 0;
                            $tmpString = '';
                        }
                        // obtener el número de líneas
                        $line = count($textArray);
                    }

                    ///***-*-*-**-**-**- */




                    $pdf->Cell(16, ($line * $cellHeight), $item['id_item'], 1, 0, 'C');
                    $xPos = $pdf->GetX();
                    $yPos = $pdf->GetY();
                    $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($item['descripcion']), 1, 'L');
                    $pdf->SetXY($xPos + $cellWidth, $yPos);


                    $pdf->Cell(18, ($line * $cellHeight), utf8_decode($unidad['nombre_unidad']), 1, 0, 'C');


                    //buscamos todas las entradas con el id_item y activo = 1
                    $entradas = $this->entrada->where('id_item', $item['id_item'])->where('activo', 1)->findAll();

                    $totalCantidadEntradas = 0;
                    $totalImporteEntradas = 0;


                    foreach ($entradas as $entrada) {
                        $totalCantidadEntradas += $entrada['cantidad'];
                        $totalImporteEntradas += $entrada['importe'];
                    }

                    $sumaValorEntradas += $totalImporteEntradas;

                    //colocamos en celdas los valores totales
                    $pdf->Cell(20, ($line * $cellHeight), number_format($totalCantidadEntradas, 2), 1, 0, 'R');
                    $pdf->Cell(20, ($line * $cellHeight), number_format($totalImporteEntradas, 3), 1, 0, 'R');


                    //buscamos las salidas con el id_item y activo = 1
                    $salidas = $this->salida->where('id_item', $result['id_item'])->where('activo', 1)->findAll();

                    $totalCantidadSalidas = 0;
                    $totalImporteSalidas = 0;

                    foreach ($salidas as $salida) {
                        $totalCantidadSalidas += $salida['cantidad'];
                        $totalImporteSalidas += $salida['importe'];
                    }

                    $sumaValorSalidas += $totalImporteSalidas;

                    //coloca en celdas los valores totales
                    $pdf->Cell(20, ($line * $cellHeight), number_format($totalCantidadSalidas, 2), 1, 0, 'R');
                    $pdf->Cell(20, ($line * $cellHeight), number_format($totalImporteSalidas, 3), 1, 0, 'R');

                    //hallamos saldo cantidad y saldo valor
                    $saldoCantidad = $totalCantidadEntradas - $totalCantidadSalidas;
                    $saldoValor = $totalImporteEntradas - $totalImporteSalidas;

                    $sumaValorSaldo += $saldoValor;

                    //colocamos en celdas los valores totales
                    $pdf->Cell(20, ($line * $cellHeight), number_format($saldoCantidad, 2), 1, 0, 'R');
                    $pdf->Cell(20, ($line * $cellHeight), number_format($saldoValor, 3), 1, 1, 'R');
                }

                $sumaTotalValorEntradas += $sumaValorEntradas;
                $sumaTotalValorSalidas += $sumaValorSalidas;
                $sumaTotalValorSaldo += $sumaValorSaldo;

                //colocamos en celdas los valores totales
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(76, 9, "TOTAL", 1, 0, 'C');
                $pdf->Cell(40, 9, number_format($sumaValorEntradas, 3), 1, 0, 'R');
                $pdf->Cell(40, 9, number_format($sumaValorSalidas, 3), 1, 0, 'R');
                $pdf->Cell(40, 9, number_format($sumaValorSaldo, 3), 1, 1, 'R');
            }

            //colocamos en celdas los valores totales
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(76, 9, "TOTAL GENERAL", 1, 0, 'C');
            $pdf->Cell(40, 9, number_format($sumaTotalValorEntradas, 3), 1, 0, 'R');
            $pdf->Cell(40, 9, number_format($sumaTotalValorSalidas, 3), 1, 0, 'R');
            $pdf->Cell(40, 9, number_format($sumaTotalValorSaldo, 3), 1, 1, 'R');


            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteGeneralCategoria.pdf", "I");
        }
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
            $pdf->Cell(30, 10, 'Importe (Bs.)', 1, 1, 'C');

            $cont = 1;
            //contador de los precios unitarios
            $total_precios = 0;
            //contador de los costos unitarios
            $total_importes = 0;
            //contador de los precios unitarios iva
            $total__iva = 0;
            foreach ($entradas as $entrada) {

                //buscamos la descripcion de la entrada en items
                $item = $this->item->where('id_item', $entrada['id_item'])->first();

                $cellWidth = 67; // Ancho de celda envuelta
                $cellHeight = 5; // Altura de una línea normal de celda

                // Comprobar si el texto se desborda
                if ($pdf->GetStringWidth($item['descripcion']) < $cellWidth) {
                    // Si no, no hacer nada
                    $line = 1;
                } else {
                    // Si es así, calcular la altura necesaria para la celda envuelta
                    // dividiendo el texto para que quepa en el ancho de la celda
                    // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                    $textLength = strlen($item['descripcion']);    // Longitud total del texto
                    $errMargin = 2;        // Margen de error del ancho de la celda, por si acaso
                    $startChar = 0;        // Posición de inicio de caracteres para cada línea
                    $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                    $textArray = array();    // para mantener las cadenas de cada línea
                    $tmpString = "";        // para mantener la cadena de una línea (temporal)

                    while ($startChar < $textLength) { // bucle hasta el final del texto
                        // bucle hasta que se alcance el máximo de caracteres
                        while (
                            $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                            ($startChar + $maxChar) < $textLength
                        ) {
                            $maxChar++;
                            $tmpString = substr($item['descripcion'], $startChar, $maxChar);
                        }
                        // mueve startChar a la próxima línea
                        $startChar = $startChar + $maxChar;
                        // luego agréguelo al array para saber cuántas líneas son necesarias
                        array_push($textArray, $tmpString);
                        // restablecer maxChar y tmpString
                        $maxChar = 0;
                        $tmpString = '';
                    }
                    // obtener el número de líneas
                    $line = count($textArray);
                }




                //***-*-*-*- */
                $pdf->SetFont('Arial', '', 7);

                $total_precios += $entrada['total_precio'];
                $total_importes += $entrada['importe'];
                $total__iva += number_format($entrada['total_precio'] - $entrada['importe'], 2);

                $pdf->Cell(14, ($line * $cellHeight), $cont, 1, 0, 'C');
                $cont++;
                $pdf->Cell(25, ($line * $cellHeight), str_replace('-', '/', $entrada['fecha']), 1, 0, 'C');

                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                //creamos la columna $item['descripcion'] pero con formato utf8  para que no salgan caracteres raros
                $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($item['descripcion']), 1, 'L');
                $pdf->SetXY($xPos + $cellWidth, $yPos);

                $pdf->Cell(30, ($line * $cellHeight), number_format($entrada['total_precio'], 2), 1, 0, 'C');
                $pdf->Cell(30, ($line * $cellHeight), number_format($entrada['total_precio'] - $entrada['importe'], 2), 1, 0, 'C');
                $pdf->Cell(30, ($line * $cellHeight), number_format($entrada['importe'], 2), 1, 1, 'C');
            }


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
    //--------------------------------------------------------------------
    //--------------------reporte de administracion--------------------

    public function vistaReporteVarios()
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
        echo view('reportes/reporteVarios', $data);
        echo view('footer');
    }

    public function generaPdfReporteVarios($fecha_inicio, $fecha_fin, $tipo)
    {
        if ($tipo == 'tipo_entrada') {

            $pdf = new \FPDF('P', 'mm', 'letter');
            $pdf->AddPage();



            $margin = 10; // Márgenes de 10 mm
            $pdf->SetMargins($margin, $margin, $margin);

            //cambiamos las fechas de - a /
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);
            //obtenemos la fecha actual
            $fecha_actual = date('Y-m-d');
            //cambiamos la fecha actual de - a /
            $fecha_actualMod = str_replace('-', '/', $fecha_actual);


            $pdf->SetTitle("Reporte por tipo en Entrada");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 0, 'L');
            $pdf->Cell(181, 5,  $fecha_actualMod, 0, 1, 'R');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("DETALLE DE ENTRADAS AL ALMACÉN (Por tipo de entrada)"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(195, 5, "Del " . $fecha_inicioMod . " al " . $fecha_finMod, 0, 1, 'C');

            $pdf->SetFont('Arial', 'B', 9);

            $cellWidth = 20; // Ancho de celda envuelta
            $cellHeight = 5; // Altura de una línea normal de celda

            // Comprobar si el texto se desborda
            if ($pdf->GetStringWidth("Factura o Recibo") < $cellWidth) {
                // Si no, no hacer nada
                $line = 1;
            } else {
                // Si es así, calcular la altura necesaria para la celda envuelta
                // dividiendo el texto para que quepa en el ancho de la celda
                // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                $textLength = strlen("Factura o Recibo");    // Longitud total del texto
                $errMargin = 8;        // Margen de error del ancho de la celda, por si acaso
                $startChar = 0;        // Posición de inicio de caracteres para cada línea
                $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                $textArray = array();    // para mantener las cadenas de cada línea
                $tmpString = "";        // para mantener la cadena de una línea (temporal)

                while ($startChar < $textLength) { // bucle hasta el final del texto
                    // bucle hasta que se alcance el máximo de caracteres
                    while (
                        $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr("Factura o Recibo", $startChar, $maxChar);
                    }
                    // mueve startChar a la próxima línea
                    $startChar = $startChar + $maxChar;
                    // luego agréguelo al array para saber cuántas líneas son necesarias
                    array_push($textArray, $tmpString);
                    // restablecer maxChar y tmpString
                    $maxChar = 0;
                    $tmpString = '';
                }
                // obtener el número de líneas
                $line = count($textArray);
            }


            $pdf->Cell(20, ($line * $cellHeight), "No Ingreso", 1, 0, 'C');
            $pdf->Cell(22, ($line * $cellHeight), "Fecha Ingreso", 1, 0, 'C');
            $xPos = $pdf->GetX();
            $yPos = $pdf->GetY();
            $pdf->MultiCell($cellWidth, $cellHeight, "Factura o Recibo", 1);
            $pdf->SetXY($xPos + $cellWidth, $yPos);
            $pdf->Cell(74, ($line * $cellHeight), "Proveedor", 1, 0, 'C');
            $pdf->Cell(20, ($line * $cellHeight), "Importe (Bs.)", 1, 0, 'C');
            $pdf->Cell(20, ($line * $cellHeight), "I.V.A (Bs.)", 1, 0, 'C');
            $pdf->Cell(20, ($line * $cellHeight), "Costo (Bs.)", 1, 1, 'C');

            $pdf->SetFont('Arial', '', 8);


            //obtenemos todos los tipos de entrada con activo = 1
            $tentradas = $this->tentrada->where('activo', 1)->findAll();

            foreach ($tentradas as $tentrada) {
                //buscamos todas las entradas con activo = 1 y id_tentrada = $tentrada['id_tentrada'] y que este dentro del rangos de fechas
                $entradas = $this->entrada->where('activo', 1)->where('id_tipoentrada', $tentrada['id_tipoentrada'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                // $entradas = $this->entrada->where('activo', 1)->where('id_tipoentrada', $tentrada['id_tipoentrada'])->findAll();

                //preguntamos si hay entradas
                if ($entradas) {

                    $totalImporte = 0;
                    $totalIva = 0;
                    $totalCosto = 0;
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(196, 8, $tentrada['nombre_entrada'], 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);

                    foreach ($entradas as $entrada) {
                        $pdf->Cell(20, 8, $entrada['id_entrada'], 1, 0, 'R');
                        $pdf->Cell(22, 8, str_replace('-', '/', $entrada['fecha']), 1, 0, 'C');
                        $pdf->Cell(20, 8, $entrada['nota_recepcion'], 1, 0, 'R');
                        //buscamos el proveedor con el id_proveedor de la entrada
                        $proveedor = $this->proveedor->where('id_proveedor', $entrada['id_proveedor'])->first();
                        $pdf->Cell(74, 8, utf8_decode($proveedor['nombre_proveedor']), 1, 0, 'L');
                        $pdf->Cell(20, 8, number_format($entrada['total_precio'], 2), 1, 0, 'R');
                        $pdf->Cell(20, 8, number_format($entrada['total_precio'] - $entrada['importe'], 2), 1, 0, 'R');
                        $pdf->Cell(20, 8, number_format($entrada['importe'], 2), 1, 1, 'R');
                        $totalImporte += $entrada['total_precio'];
                        $totalIva += $entrada['total_precio'] - $entrada['importe'];
                        $totalCosto += $entrada['importe'];
                    }
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(136, 8, 'TOTAL', 1, 0, 'L');
                    $pdf->Cell(20, 8, number_format($totalImporte, 2), 1, 0, 'R');
                    $pdf->Cell(20, 8, number_format($totalIva, 2), 1, 0, 'R');
                    $pdf->Cell(20, 8, number_format($totalCosto, 2), 1, 1, 'R');
                    $pdf->Ln();
                } else {
                    //cuando no entradas damos continue al bucle
                    continue;
                }
            }


            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteTipoDeEntrada.pdf", "I");
        }
        ///=================================================================================================

        if ($tipo == 'tipo_salida') {

            $pdf = new \FPDF('P', 'mm', 'letter');
            $pdf->AddPage();



            $margin = 10; // Márgenes de 10 mm
            $pdf->SetMargins($margin, $margin, $margin);

            //cambiamos las fechas de - a /
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);
            //obtenemos la fecha actual
            $fecha_actual = date('Y-m-d');
            //cambiamos la fecha actual de - a /
            $fecha_actualMod = str_replace('-', '/', $fecha_actual);


            $pdf->SetTitle("Reporte por tipo de Salida");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 0, 'L');
            $pdf->Cell(181, 5,  $fecha_actualMod, 0, 1, 'R');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("DETALLE DE SALIDAS AL ALMACÉN (Por tipo de salida)"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(195, 5, "Del " . $fecha_inicioMod . " al " . $fecha_finMod, 0, 1, 'C');

            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(30, 8, "No Salida", 1, 0, 'C');
            $pdf->Cell(30, 8, "Fecha Salida", 1, 0, 'C');

            $pdf->Cell(106, 8, "Destino", 1, 0, 'C');
            $pdf->Cell(30, 8, "Importe (Bs.)", 1, 1, 'C');


            $pdf->SetFont('Arial', '', 8);

            //obtenemos todas los tipos de salidas con activo = 1
            $tsalidas = $this->tsalida->where('activo', 1)->findAll();

            foreach ($tsalidas as $tsalida) {
                //buscamos todas las salidas con activo = 1 y id_tsalida = $tsalida['id_tsalida'] y que este dentro del rangos de fechas
                $salidas = $this->salida->where('activo', 1)->where('id_tiposalida', $tsalida['id_tiposalida'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                // $salidas = $this->salida->where('activo', 1)->where('id_tiposalida', $tsalida['id_tiposalida'])->findAll();

                //preguntamos si hay salidas
                if ($salidas) {

                    $totalImporte = 0;
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(196, 8, utf8_decode($tsalida['nombre_salida']), 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);

                    foreach ($salidas as $salida) {
                        $pdf->Cell(30, 8, $salida['id_salida'], 1, 0, 'R');
                        $pdf->Cell(30, 8, str_replace('-', '/', $salida['fecha']), 1, 0, 'C');
                        $pdf->Cell(106, 8, utf8_decode($salida['destino']), 1, 0, 'L');
                        $pdf->Cell(30, 8, number_format($salida['importe'], 2), 1, 1, 'R');
                        $totalImporte += $salida['importe'];
                    }
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(166, 8, 'TOTAL', 1, 0, 'L');
                    $pdf->Cell(30, 8, number_format($totalImporte, 2), 1, 1, 'R');
                    $pdf->Ln();
                } else {
                    //cuando no salidas damos continue al bucle
                    continue;
                }
            }




            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteTipoDeSalida.pdf", "I");
        }
        if ($tipo == 'proveedor') {

            $pdf = new \FPDF('P', 'mm', 'letter');
            $pdf->AddPage();



            $margin = 10; // Márgenes de 10 mm
            $pdf->SetMargins($margin, $margin, $margin);

            //cambiamos las fechas de - a /
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);
            //obtenemos la fecha actual
            $fecha_actual = date('Y-m-d');
            //cambiamos la fecha actual de - a /
            $fecha_actualMod = str_replace('-', '/', $fecha_actual);


            $pdf->SetTitle("Reporte por proveedor");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 0, 'L');
            $pdf->Cell(181, 5,  $fecha_actualMod, 0, 1, 'R');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("REPORTE POR PROVEEDOR"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(195, 5, "Del " . $fecha_inicioMod . " al " . $fecha_finMod, 0, 1, 'C');
            $pdf->SetFont('Arial', '', 8);

            $cellWidth = 20; // Ancho de celda envuelta
            $cellHeight = 5; // Altura de una línea normal de celda

            // Comprobar si el texto se desborda
            if ($pdf->GetStringWidth("Factura o Recibo") < $cellWidth) {
                // Si no, no hacer nada
                $line = 1;
            } else {
                // Si es así, calcular la altura necesaria para la celda envuelta
                // dividiendo el texto para que quepa en el ancho de la celda
                // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                $textLength = strlen("Factura o Recibo");    // Longitud total del texto
                $errMargin = 8;        // Margen de error del ancho de la celda, por si acaso
                $startChar = 0;        // Posición de inicio de caracteres para cada línea
                $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                $textArray = array();    // para mantener las cadenas de cada línea
                $tmpString = "";        // para mantener la cadena de una línea (temporal)

                while ($startChar < $textLength) { // bucle hasta el final del texto
                    // bucle hasta que se alcance el máximo de caracteres
                    while (
                        $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr("Factura o Recibo", $startChar, $maxChar);
                    }
                    // mueve startChar a la próxima línea
                    $startChar = $startChar + $maxChar;
                    // luego agréguelo al array para saber cuántas líneas son necesarias
                    array_push($textArray, $tmpString);
                    // restablecer maxChar y tmpString
                    $maxChar = 0;
                    $tmpString = '';
                }
                // obtener el número de líneas
                $line = count($textArray);
            }


            $pdf->Cell(19, ($line * $cellHeight), "No Ingreso", 1, 0, 'C');
            $pdf->Cell(23, ($line * $cellHeight), "Fecha Ingreso", 1, 0, 'C');
            $xPos = $pdf->GetX();
            $yPos = $pdf->GetY();
            $pdf->MultiCell($cellWidth, $cellHeight, "Factura o Recibo", 1, 'C');
            $pdf->SetXY($xPos + $cellWidth, $yPos);
            $pdf->Cell(74, ($line * $cellHeight), "Item", 1, 0, 'C');
            $pdf->Cell(22, ($line * $cellHeight), "Importe (Bs.)", 1, 0, 'C');
            $pdf->Cell(18, ($line * $cellHeight), "I.V.A. (Bs.)", 1, 0, 'C');
            $pdf->Cell(20, ($line * $cellHeight), "Costo (Bs.)", 1, 1, 'C');


            $pdf->SetFont('Arial', '', 9);

            //obtenemos todos los proveedores con activo = 1
            $proveedores = $this->proveedor->where('activo', 1)->findAll();

            foreach ($proveedores as $proveedor) {
                //buscamos todas las entradas con activo = 1 y id_proveedor = $proveedor['id_proveedor'] y que este dentro del rangos de fechas
                $entradas = $this->entrada->where('activo', 1)->where('id_proveedor', $proveedor['id_proveedor'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                // $entradas = $this->entrada->where('activo', 1)->where('id_proveedor', $proveedor['id_proveedor'])->findAll();

                //preguntamos si hay entradas
                if ($entradas) {

                    $totalImporte = 0;
                    $totalIva = 0;
                    $totalCosto = 0;
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(196, 8, utf8_decode($proveedor['nombre_proveedor']), 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);

                    foreach ($entradas as $entrada) {
                        //buscamos el item con el id_item de la entrada
                        $item = $this->item->where('id_item', $entrada['id_item'])->first();


                        $cellWidth = 74; // Ancho de celda envuelta
                        $cellHeight = 5; // Altura de una línea normal de celda

                        // Comprobar si el texto se desborda
                        if ($pdf->GetStringWidth($item['descripcion']) < $cellWidth) {
                            // Si no, no hacer nada
                            $line = 1;
                        } else {
                            // Si es así, calcular la altura necesaria para la celda envuelta
                            // dividiendo el texto para que quepa en el ancho de la celda
                            // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                            $textLength = strlen($item['descripcion']);    // Longitud total del texto
                            $errMargin = 10;        // Margen de error del ancho de la celda, por si acaso
                            $startChar = 0;        // Posición de inicio de caracteres para cada línea
                            $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                            $textArray = array();    // para mantener las cadenas de cada línea
                            $tmpString = "";        // para mantener la cadena de una línea (temporal)

                            while ($startChar < $textLength) { // bucle hasta el final del texto
                                // bucle hasta que se alcance el máximo de caracteres
                                while (
                                    $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                                    ($startChar + $maxChar) < $textLength
                                ) {
                                    $maxChar++;
                                    $tmpString = substr($item['descripcion'], $startChar, $maxChar);
                                }
                                // mueve startChar a la próxima línea
                                $startChar = $startChar + $maxChar;
                                // luego agréguelo al array para saber cuántas líneas son necesarias
                                array_push($textArray, $tmpString);
                                // restablecer maxChar y tmpString
                                $maxChar = 0;
                                $tmpString = '';
                            }
                            // obtener el número de líneas
                            $line = count($textArray);
                        }


                        //*******-*-*-**-* */
                        $pdf->Cell(19, ($line * $cellHeight), $entrada['id_entrada'], 1, 0, 'R');
                        $pdf->Cell(23, ($line * $cellHeight), str_replace('-', '/', $entrada['fecha']), 1, 0, 'C');
                        $pdf->Cell(20, ($line * $cellHeight), $entrada['nota_recepcion'], 1, 0, 'R');
                        $xPos = $pdf->GetX();
                        $yPos = $pdf->GetY();
                        $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($item['descripcion']), 1, 'L');

                        // devuelve la posición para la siguiente celda al lado de la multicelda
                        // y ajusta la x con el ancho de la multicelda
                        $pdf->SetXY($xPos + $cellWidth, $yPos);
                        // $pdf->Cell(74, 8, utf8_decode($item['descripcion']), 1, 0, 'L');
                        $pdf->Cell(22, ($line * $cellHeight), number_format($entrada['total_precio'], 2), 1, 0, 'R');
                        $pdf->Cell(18, ($line * $cellHeight), number_format($entrada['total_precio'] - $entrada['importe'], 2), 1, 0, 'R');
                        $pdf->Cell(20, ($line * $cellHeight), number_format($entrada['importe'], 2), 1, 1, 'R');
                        $totalImporte += $entrada['total_precio'];
                        $totalIva += $entrada['total_precio'] - $entrada['importe'];
                        $totalCosto += $entrada['importe'];
                    }
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(136, 8, 'TOTAL', 1, 0, 'L');
                    $pdf->Cell(22, 8, number_format($totalImporte, 2), 1, 0, 'R');
                    $pdf->Cell(18, 8, number_format($totalIva, 2), 1, 0, 'R');
                    $pdf->Cell(20, 8, number_format($totalCosto, 2), 1, 1, 'R');
                    $pdf->Ln();
                } else {
                    //cuando no entradas damos continue al bucle
                    continue;
                }
            }

            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteProveedores.pdf", "I");
        }
        if ($tipo == 'usuario') {

            $pdf = new \FPDF('P', 'mm', 'letter');
            $pdf->AddPage();



            $margin = 10; // Márgenes de 10 mm
            $pdf->SetMargins($margin, $margin, $margin);

            //cambiamos las fechas de - a /
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);
            //obtenemos la fecha actual
            $fecha_actual = date('Y-m-d');
            //cambiamos la fecha actual de - a /
            $fecha_actualMod = str_replace('-', '/', $fecha_actual);


            $pdf->SetTitle("Reporte por usuario");
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(15, 5, "MASTER PIZZA", 0, 0, 'L');
            $pdf->Cell(181, 5,  $fecha_actualMod, 0, 1, 'R');
            $pdf->Cell(15, 5, "LA PAZ - BOLIVIA", 0, 1, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(195, 5, utf8_decode("REPORTE DE RECEPCION Y ENTREGA POR EMPLEADO"), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(195, 5, "Del " . $fecha_inicioMod . " al " . $fecha_finMod, 0, 1, 'C');

            $pdf->SetFont('Arial', '', 9);

            $pdf->Cell(20, 8, "No. E/S", 1, 0, 'C');
            $pdf->Cell(20, 8, "Fecha", 1, 0, 'C');

            $pdf->Cell(75, 8, "Item", 1, 0, 'C');;
            $pdf->Cell(20, 8, "Unidad", 1, 0, 'C');
            $pdf->Cell(20, 8, "Cantidad", 1, 0, 'C');
            $pdf->Cell(21, 8, "Costo U. (Bs.)", 1, 0, 'C');
            $pdf->Cell(20, 8, "Importe (Bs.)", 1, 1, 'C');


            $pdf->SetFont('Arial', '', 8);

            //obtenemos los datos de todos los usuarios con activo 1
            $usuarios = $this->usuario->where('activo', 1)->findAll();


            foreach ($usuarios as $usuario) {
                //buscamos todas las entradas con activo = 1 y id_usuario = $usuario['id_usuario'] y que este dentro del rangos de fechas
                $entradas = $this->entrada->where('activo', 1)->where('id_usuario2', $usuario['id_usuario'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                // $entradas = $this->entrada->where('activo', 1)->where('id_usuario', $usuario['id_usuario'])->findAll();

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(196, 8, utf8_decode($usuario['nombre_usuario']), 0, 1, 'L');
                //preguntamos si hay entradas
                if ($entradas) {

                    $totalImporte = 0;

                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(196, 8, "RECEPCIONES", 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);


                    foreach ($entradas as $entrada) {
                        //buscamos el item con el id_item de la entrada
                        $item = $this->item->where('id_item', $entrada['id_item'])->first();

                        $cellWidth = 75; // Ancho de celda envuelta
                        $cellHeight = 6; // Altura de una línea normal de celda

                        // Comprobar si el texto se desborda
                        if ($pdf->GetStringWidth($item['descripcion']) < $cellWidth) {
                            // Si no, no hacer nada
                            $line = 1;
                        } else {
                            // Si es así, calcular la altura necesaria para la celda envuelta
                            // dividiendo el texto para que quepa en el ancho de la celda
                            // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                            $textLength = strlen($item['descripcion']);    // Longitud total del texto
                            $errMargin = 10;        // Margen de error del ancho de la celda, por si acaso
                            $startChar = 0;        // Posición de inicio de caracteres para cada línea
                            $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                            $textArray = array();    // para mantener las cadenas de cada línea
                            $tmpString = "";        // para mantener la cadena de una línea (temporal)

                            while ($startChar < $textLength) { // bucle hasta el final del texto
                                // bucle hasta que se alcance el máximo de caracteres
                                while (
                                    $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                                    ($startChar + $maxChar) < $textLength
                                ) {
                                    $maxChar++;
                                    $tmpString = substr($item['descripcion'], $startChar, $maxChar);
                                }
                                // mueve startChar a la próxima línea
                                $startChar = $startChar + $maxChar;
                                // luego agréguelo al array para saber cuántas líneas son necesarias
                                array_push($textArray, $tmpString);
                                // restablecer maxChar y tmpString
                                $maxChar = 0;
                                $tmpString = '';
                            }
                            // obtener el número de líneas
                            $line = count($textArray);
                        }

                        $pdf->Cell(20, ($line * $cellHeight), $entrada['id_entrada'], 1, 0, 'R');
                        $pdf->Cell(20, ($line * $cellHeight), str_replace('-', '/', $entrada['fecha']), 1, 0, 'C');
                        $xPos = $pdf->GetX();
                        $yPos = $pdf->GetY();
                        $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($item['descripcion']), 1, 'L');
                        $pdf->SetXY($xPos + $cellWidth, $yPos);
                        //buscamos el nombre unidad con el id_unidad de la entrada
                        $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();
                        $pdf->Cell(20, ($line * $cellHeight), utf8_decode($unidad['nombre_unidad']), 1, 0, 'L');
                        $pdf->Cell(20, ($line * $cellHeight), number_format($entrada['cantidad'], 2), 1, 0, 'R');
                        $pdf->Cell(21, ($line * $cellHeight), number_format($entrada['costo_unitario'], 2), 1, 0, 'R');
                        $pdf->Cell(20, ($line * $cellHeight), number_format($entrada['importe'], 2), 1, 1, 'R');
                        $totalImporte += $entrada['importe'];
                    }
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(176, 8, 'TOTAL RECEPCIONES: ' . $usuario['nombre_usuario'], 1, 0, 'L');
                    $pdf->Cell(20, 8, number_format($totalImporte, 2), 1, 1, 'R');
                }
                //buscamos todas las salidas con activo 1 con id_usuario = $usuario['id_usuario'] y que este dentro del rangos de fechas
                $salidas = $this->salida->where('activo', 1)->where('id_usuario2', $usuario['id_usuario'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                //preguntamos si hay salidas
                if ($salidas) {

                    $totalImporte = 0;
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(196, 8, "ENTREGAS", 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 8);

                    foreach ($salidas as $salida) {
                        //buscamos el item con el id_item de la salida
                        $item = $this->item->where('id_item', $salida['id_item'])->first();

                        $cellWidth = 75; // Ancho de celda envuelta
                        $cellHeight = 6; // Altura de una línea normal de celda

                        // Comprobar si el texto se desborda
                        if ($pdf->GetStringWidth($item['descripcion']) < $cellWidth) {
                            // Si no, no hacer nada
                            $line = 1;
                        } else {
                            // Si es así, calcular la altura necesaria para la celda envuelta
                            // dividiendo el texto para que quepa en el ancho de la celda
                            // luego contar cuántas líneas son necesarias para que el texto quepa en la celda

                            $textLength = strlen($item['descripcion']);    // Longitud total del texto
                            $errMargin = 10;        // Margen de error del ancho de la celda, por si acaso
                            $startChar = 0;        // Posición de inicio de caracteres para cada línea
                            $maxChar = 0;            // máximo de caracteres en una línea, para aumentar más tarde
                            $textArray = array();    // para mantener las cadenas de cada línea
                            $tmpString = "";        // para mantener la cadena de una línea (temporal)

                            while ($startChar < $textLength) { // bucle hasta el final del texto
                                // bucle hasta que se alcance el máximo de caracteres
                                while (
                                    $pdf->GetStringWidth($tmpString) < ($cellWidth - $errMargin) &&
                                    ($startChar + $maxChar) < $textLength
                                ) {
                                    $maxChar++;
                                    $tmpString = substr($item['descripcion'], $startChar, $maxChar);
                                }
                                // mueve startChar a la próxima línea
                                $startChar = $startChar + $maxChar;
                                // luego agréguelo al array para saber cuántas líneas son necesarias
                                array_push($textArray, $tmpString);
                                // restablecer maxChar y tmpString
                                $maxChar = 0;
                                $tmpString = '';
                            }
                            // obtener el número de líneas
                            $line = count($textArray);
                        }


                        $pdf->Cell(20, ($line * $cellHeight), $salida['id_salida'], 1, 0, 'R');
                        $pdf->Cell(20, ($line * $cellHeight), str_replace('-', '/', $salida['fecha']), 1, 0, 'C');
                        $xPos = $pdf->GetX();
                        $yPos = $pdf->GetY();  
                        $pdf->MultiCell($cellWidth, $cellHeight, utf8_decode($item['descripcion']), 1, 'L');
                        $pdf->SetXY($xPos + $cellWidth, $yPos);
                        //buscamos el nombre unidad con el id_unidad de la salida
                        $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();
                        $pdf->Cell(20, ($line * $cellHeight), utf8_decode($unidad['nombre_unidad']), 1, 0, 'L');
                        $pdf->Cell(20, ($line * $cellHeight), number_format($salida['cantidad'], 2), 1, 0, 'R');
                        $pdf->Cell(21, ($line * $cellHeight), number_format($salida['costo_unitario'], 2), 1, 0, 'R');
                        $pdf->Cell(20, ($line * $cellHeight), number_format($salida['importe'], 2), 1, 1, 'R');
                        $totalImporte += $salida['importe'];
                    }
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(176, 8, 'TOTAL ENTREGAS: ' . $usuario['nombre_usuario'], 1, 0, 'L');
                    $pdf->Cell(20, 8, number_format($totalImporte, 2), 1, 1, 'R');
                    $pdf->Ln();
                }
            }


            $this->response->setHeader('Content-Type', 'application/pdf');
            //ahora devolvemos el pdf para que lo reciba el ajax
            $pdf->Output("reporteEmpleados.pdf", "I");
        }
    }
}
