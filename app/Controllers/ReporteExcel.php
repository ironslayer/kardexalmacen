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
// use App\Models\ReporteExcelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




class ReporteExcel extends BaseController

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

    public function generaExcelEntradasSalidas($fecha_inicio, $fecha_fin, $tipo)
    {

        //preguntamos si tipo es igual a 'entrada'
        if ($tipo == 'entrada') {
            $nombreExcel = 'Reporte_de_Entradas.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte de Entradas");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------

            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta f2
            $hoja->mergeCells('A2:F2');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'RESUMEN DE ENTRADAS AL ALMACÉN');
            //cambiamos el tamaño de la letra
            $hoja->getStyle('A2')->getFont()->setSize(12);
            //unimos las celdas desde a3 hasta f3
            $hoja->mergeCells('A3:F3');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'Del ' . $fecha_inicioMod . ' al ' . $fecha_finMod);
            //cambiamos el tamaño de la letra
            $hoja->getStyle('A3')->getFont()->setSize(12);
            //CENTRAMOS EL TEXTO EN LA CELDA
            $hoja->getStyle('A2')->getAlignment()->setHorizontal('center');
            $hoja->getStyle('A3')->getAlignment()->setHorizontal('center');
            //colocamos en negrita el texto
            $hoja->getStyle('A2')->getFont()->setBold(true);
            $hoja->getStyle('A3')->getFont()->setBold(true);
            //cambiamos el color de la letra a azul oscuro
            $hoja->getStyle('A2')->getFont()->getColor()->setARGB('FF0000FF');
            $hoja->getStyle('A3')->getFont()->getColor()->setARGB('FF0000FF');
            //definimos el ancho la columna a
            $hoja->getColumnDimension('A')->setWidth(15);
            //definimos el ancho la columna b
            $hoja->getColumnDimension('B')->setWidth(15);
            //definimos el ancho la columna c
            $hoja->getColumnDimension('C')->setWidth(60);
            //definimos el ancho la columna d
            $hoja->getColumnDimension('D')->setWidth(20);
            //definimos el ancho la columna e
            $hoja->getColumnDimension('E')->setWidth(20);
            //definimos el ancho la columna f
            $hoja->getColumnDimension('F')->setWidth(20);
            //colocamos un borde medio grueso  a todas las celdas desde a4 hasta la f4
            $hoja->getStyle('A4:F4')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //escrbimos en la celda a4
            $hoja->setCellValue('A4', 'Número');
            //escrbimos en la celda b4
            $hoja->setCellValue('B4', 'Fecha');
            //escrbimos en la celda c4
            $hoja->setCellValue('C4', 'Referencia');
            //escrbimos en la celda d4
            $hoja->setCellValue('D4', 'Importe Factura (Bs)');
            //escrbimos en la celda e4
            $hoja->setCellValue('E4', 'Importe IVA (Bs)');
            //escrbimos en la celda f4
            $hoja->setCellValue('F4', 'Importe (Bs)');
            //centramos desde a4 hasta f4
            $hoja->getStyle('A4:F4')->getAlignment()->setHorizontal('center');
            //colocamos en negrita desde a4 hasta f4
            $hoja->getStyle('A4:F4')->getFont()->setBold(true);
            //colocamos fondo de color celeste desde a4 hasta f4
            $hoja->getStyle('A4:F4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFCC');
            //obtenemos todos los datos de la tabla entradas con activo = 1 y entre las fechas $fecha_inicio y $fecha_fin
            $entradas = $this->entrada->where('activo', 1)->where('fecha >=', $fecha_inicio)->where('fecha <=', $fecha_fin)->findAll();

            //empezamos desde la fila 5 a llenar la tabla
            $fila = 5;
            //recorremos el array de entradas
            foreach ($entradas as $entrada) {
                $item = $this->item->where('id_item', $entrada['id_item'])->first();

                //escribimos en la celda a5
                $hoja->setCellValue('A' . $fila, $fila - 4);
                //escribimos en la celda b5
                $hoja->setCellValue('B' . $fila, str_replace('-', '/', $entrada['fecha']));
                //escribimos en la celda c5
                $hoja->setCellValue('C' . $fila, $item['descripcion']);
                //escribimos en la celda d5
                $hoja->setCellValue('D' . $fila, $entrada['total_precio']);
                //escribimos en la celda e5
                $hoja->setCellValue('E' . $fila, $entrada['total_precio'] - $entrada['importe']);
                //escribimos en la celda f5
                $hoja->setCellValue('F' . $fila, $entrada['importe']);
                $fila++;
            }
            //redondeamos las cantidades desde d5 hasta d$filas
            $hoja->getStyle('D5:D' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            //redondeamos las cantidades desde e5 hasta e$filas
            $hoja->getStyle('E5:E' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            //redondeamos las cantidades desde f5 hasta f$filas
            $hoja->getStyle('F5:F' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

            //colocamos un borde delgado a todas las celdas de la tabla desde a5 hasta f$filas
            $hoja->getStyle('A5:F' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
            //colocamos la suma de las cantidades desde d$filas hasta d$filas+1
            $hoja->setCellValue('D' . ($fila), '=SUM(D5:D' . $fila . ')');
            //colocamos la suma de las cantidades desde e$filas hasta e$filas+1
            $hoja->setCellValue('E' . ($fila), '=SUM(E5:E' . $fila . ')');
            //colocamos la suma de las cantidades desde f$filas hasta f$filas+1
            $hoja->setCellValue('F' . ($fila), '=SUM(F5:F' . $fila . ')');
            //unicamos las celdas desde a$filas hasta c$filas
            $hoja->mergeCells('A' . ($fila) . ':C' . ($fila));
            //escribimos en la celda a$filas
            $hoja->setCellValue('A' . ($fila), 'TOTAL');
            //colocamos en negrita desde a$filas hasta f$filas
            $hoja->getStyle('A' . ($fila) . ':F' . ($fila))->getFont()->setBold(true);






            // $hoja->setCellValue('A1', 'Holaaaaa mundo PRUEBA XXXXX!');
            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;
        } else {

            $nombreExcel = 'Reporte_de_Salidas.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte de Salidas");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------

            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta d2
            $hoja->mergeCells('A2:D2');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'RESUMEN DE SALIDAS AL ALMACÉN');
            //cambiamos el tamaño de la letra
            $hoja->getStyle('A2')->getFont()->setSize(12);
            //unimos las celdas desde a3 hasta d3
            $hoja->mergeCells('A3:D3');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'Del ' . $fecha_inicioMod . ' al ' . $fecha_finMod);
            //cambiamos el tamaño de la letra
            $hoja->getStyle('A3')->getFont()->setSize(12);
            //CENTRAMOS EL TEXTO EN LA CELDA
            $hoja->getStyle('A2')->getAlignment()->setHorizontal('center');
            $hoja->getStyle('A3')->getAlignment()->setHorizontal('center');
            //colocamos en negrita el texto
            $hoja->getStyle('A2')->getFont()->setBold(true);
            $hoja->getStyle('A3')->getFont()->setBold(true);
            //cambiamos el color de la letra a azul oscuro
            $hoja->getStyle('A2')->getFont()->getColor()->setARGB('FF0000FF');
            $hoja->getStyle('A3')->getFont()->getColor()->setARGB('FF0000FF');
            //definimos el ancho la columna a
            $hoja->getColumnDimension('A')->setWidth(15);
            //definimos el ancho la columna b
            $hoja->getColumnDimension('B')->setWidth(15);
            //definimos el ancho la columna c
            $hoja->getColumnDimension('C')->setWidth(60);
            //definimos el ancho la columna d
            $hoja->getColumnDimension('D')->setWidth(20);
            //colocamos un borde medio grueso  a todas las celdas desde a4 hasta la d4
            $hoja->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //escrbimos en la celda a4
            $hoja->setCellValue('A4', 'Número');
            //escrbimos en la celda b4
            $hoja->setCellValue('B4', 'Fecha');
            //escrbimos en la celda c4
            $hoja->setCellValue('C4', 'Referencia');
            //escrbimos en la celda d4
            $hoja->setCellValue('D4', 'Importe (Bs)');
            //centramos desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getAlignment()->setHorizontal('center');
            //colocamos en negrita desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getFont()->setBold(true);
            //colocamos fondo de color celeste desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFCC');
            //obtenemos todos los datos de la tabla salidas con activo = 1 y entre las fechas $fecha_inicio y $fecha_fin
            $salidas = $this->salida->where('activo', 1)->where('fecha >=', $fecha_inicio)->where('fecha <=', $fecha_fin)->findAll();

            //empezamos desde la fila 5 a llenar la tabla
            $fila = 5;
            //recorremos el array de salidas
            foreach ($salidas as $salida) {
                $item = $this->item->where('id_item', $salida['id_item'])->first();
                //escribimos en la celda a5
                $hoja->setCellValue('A' . $fila, $fila - 4);
                //escribimos en la celda b5
                $hoja->setCellValue('B' . $fila, str_replace('-', '/', $salida['fecha']));
                //escribimos en la celda c5
                $hoja->setCellValue('C' . $fila, $item['descripcion']);
                //escribimos en la celda d5
                $hoja->setCellValue('D' . $fila, $salida['importe']);
                $fila++;
            }
            //redondeamos las cantidades desde d5 hasta d$filas
            $hoja->getStyle('D5:D' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            //colocamos un borde delgado a todas las celdas de la tabla desde a5 hasta d$filas
            $hoja->getStyle('A5:D' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
            //colocamos la suma de las cantidades desde d$filas hasta d$filas+1
            $hoja->setCellValue('D' . ($fila), '=SUM(D5:D' . $fila . ')');
            //unicamos las celdas desde a$filas hasta c$filas
            $hoja->mergeCells('A' . ($fila) . ':C' . ($fila));
            //escribimos en la celda a$filas
            $hoja->setCellValue('A' . ($fila), 'TOTAL');
            //colocamos en negrita desde a$filas hasta d$filas
            $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getFont()->setBold(true);





            // $hoja->setCellValue('A1', 'Holaaaaa mundo PRUEBA XXXXX!');
            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;
        }
    }


    public function generaExcelKardexFisicoValorado($fecha_inicio, $fecha_fin, $id_item)
    {
        $nombreExcel = 'Reporte_de_Kardex_Fisico_Valorado.xlsx';
        $phpExcel = new Spreadsheet();
        $phpExcel->getProperties()
            ->setCreator("ARGT")
            ->setTitle("Reporte de Kardex Fisico Valorado");
        $hoja = $phpExcel->getActiveSheet();

        //colocamos una imagen en la celda a1
        $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $objDrawing->setName('nombre_master_pizza');
        $objDrawing->setDescription('nombre_master_pizza');
        $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
        $objDrawing->setCoordinates('A1');
        $objDrawing->setHeight(40);
        $objDrawing->setWorksheet($hoja);

        //----codigo para llenar la hoja------------------






        //obtenemos los datos del item
        $item = $this->item->where('id_item', $id_item)->first();
        //obtenemos la unidad de medida del item
        $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();

        //unimos las celdas desde a2 hasta m2
        $hoja->mergeCells('A2:M2');
        //escribimos en la celda a2 y centramos el texto
        $hoja->setCellValue('A2', 'KARDEX FISICO VALORADO');
        $hoja->getStyle('A2')->getAlignment()->setHorizontal('center');
        //cambiamos el tamaño de la letra
        $hoja->getStyle('A2')->getFont()->setSize(12);
        //cambiamos el color de la letra a azul oscuro
        $hoja->getStyle('A2')->getFont()->getColor()->setARGB('FF0000FF');
        //colocamos en negrita el texto
        $hoja->getStyle('A2')->getFont()->setBold(true);
        //unimos las celdas desde a3 hasta m3
        $hoja->mergeCells('A3:M3');
        //escribimos en la celda a3 y centramos el texto
        $hoja->setCellValue('A3', '(Expresado en Bolivianos)');
        $hoja->getStyle('A3')->getAlignment()->setHorizontal('center');
        //cambiamos el tamaño de la letra
        $hoja->getStyle('A3')->getFont()->setSize(12);
        //cambiamos el color de la letra a azul oscuro
        $hoja->getStyle('A3')->getFont()->getColor()->setARGB('FF0000FF');
        //colocamos en negrita el texto
        $hoja->getStyle('A3')->getFont()->setBold(true);
        //escribimos en la celda a4
        $hoja->setCellValue('A4', 'CÓDIGO/ID');
        //escribimos en la celda c4
        $hoja->setCellValue('C4', 'ARTÍCULO');
        //escribimos en la celda k4
        $hoja->setCellValue('K4', 'UNIDAD');
        //cambiamos el tamaño de la letra desde a4 hasta m4
        $hoja->getStyle('A4:M4')->getFont()->setSize(12);
        //colocamos en negrita desde a4 hasta m4
        $hoja->getStyle('A4:M4')->getFont()->setBold(true);
        //cambiamos el color de la letra a azul oscuro desde a4 hasta m4
        $hoja->getStyle('A4:M4')->getFont()->getColor()->setARGB('FF0000FF');
        //escribimos en la celda a5
        $hoja->setCellValue('A5', $item['id_item']);
        //escribimos en la celda c5
        $hoja->setCellValue('C5', $item['descripcion']);
        //escribimos en la celda k5
        $hoja->setCellValue('K5', $unidad['nombre_unidad']);
        //definiendo el ancho de la columna a
        $hoja->getColumnDimension('A')->setWidth(12);
        //definiendo el ancho de la columna b
        $hoja->getColumnDimension('B')->setWidth(15);
        //definiendo el ancho de la columna c
        $hoja->getColumnDimension('C')->setWidth(50);
        //definiendo el ancho de la columna d
        $hoja->getColumnDimension('D')->setWidth(10);
        //definiendo el ancho de la columna e
        $hoja->getColumnDimension('E')->setWidth(15);
        //definiendo el ancho de la columna f
        $hoja->getColumnDimension('F')->setWidth(15);
        //definiendo el ancho de la columna g
        $hoja->getColumnDimension('G')->setWidth(15);
        //definiendo el ancho de la columna h
        $hoja->getColumnDimension('H')->setWidth(15);
        //definiendo el ancho de la columna i
        $hoja->getColumnDimension('I')->setWidth(15);
        //definiendo el ancho de la columna j
        $hoja->getColumnDimension('J')->setWidth(15);
        //definiendo el ancho de la columna k
        $hoja->getColumnDimension('K')->setWidth(15);
        //definiendo el ancho de la columna l
        $hoja->getColumnDimension('L')->setWidth(17);
        //definiendo el ancho de la columna m
        $hoja->getColumnDimension('M')->setWidth(15);
        //unimos las celdas a6 y a7
        $hoja->mergeCells('A6:A7');
        //unimos las celdas b6 y b7
        $hoja->mergeCells('B6:B7');
        //unimos las celdas c6 y c7
        $hoja->mergeCells('C6:C7');
        //unimos las celdas d6 y d7
        $hoja->mergeCells('D6:D7');
        //unimos las celdas e6 hasta g6
        $hoja->mergeCells('E6:G6');
        //unimos las celdas h6 hasta j6
        $hoja->mergeCells('H6:J6');
        //unimos las celdas k6 y m6
        $hoja->mergeCells('K6:M6');
        //colocamos un borde medio grueso  a todas las celdas desde a6 hasta m7
        $hoja->getStyle('A6:M7')->getBorders()->getAllBorders()->setBorderStyle('medium');
        //escribimos en la celda a6
        $hoja->setCellValue('A6', 'FECHA');
        //escribimos en la celda b6
        $hoja->setCellValue('B6', 'Entrada/Salida');
        //escribimos en la celda c6
        $hoja->setCellValue('C6', 'REFERENCIA');
        //escribimos en la celda d6
        $hoja->setCellValue('D6', 'Nro. E/S');
        //escribimos en la celda e6
        $hoja->setCellValue('E6', 'ENTRADAS');
        //escribimos en la celda h6
        $hoja->setCellValue('H6', 'SALIDAS');
        //escribimos en la celda k6
        $hoja->setCellValue('K6', 'SALDOS');
        //escribimos en la celda e7
        $hoja->setCellValue('E7', 'Cantidad');
        //escribimos en la celda f7
        $hoja->setCellValue('F7', 'Costo U.');
        //escribimos en la celda g7
        $hoja->setCellValue('G7', 'Valor');
        //escribimos en la celda h7
        $hoja->setCellValue('H7', 'Cantidad');
        //escribimos en la celda i7
        $hoja->setCellValue('I7', 'Costo U.');
        //escribimos en la celda j7
        $hoja->setCellValue('J7', 'Valor');
        //escribimos en la celda k7
        $hoja->setCellValue('k7', 'Cantidad');
        //escribimos en la celda l7
        $hoja->setCellValue('L7', 'Costo Ponderado');
        //escribimos en la celda m7
        $hoja->setCellValue('M7', 'Total');
        //centramos desde a6 hasta m7
        $hoja->getStyle('A6:M7')->getAlignment()->setHorizontal('center');
        //colocamos en negrita desde a6 hasta m7
        $hoja->getStyle('A6:M7')->getFont()->setBold(true);
        //colocamos fondo de color celeste claro desde a6 hasta m7
        $hoja->getStyle('A6:M7')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

        $fecha_inicio = "'" . $fecha_inicio . "'";
        $fecha_fin = "'" . $fecha_fin . "'";

        $db = \Config\Database::connect();
        $query = $db->query("SELECT nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe, fecha_alta
        FROM entrada
        WHERE id_item = '.$id_item.' AND activo = 1 AND fecha_alta BETWEEN $fecha_inicio AND $fecha_fin
        UNION ALL
        SELECT nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe, fecha_alta
        FROM salida
        WHERE id_item = '.$id_item.' AND activo = 1 AND fecha_alta BETWEEN $fecha_inicio AND $fecha_fin
        ORDER BY nro_movimiento ASC;");
        //cerramos la conexion
        $db->close();
        // Obtener los resultados
        $results = $query->getResultArray();

        $saldoCantidad = 0;
        $saldoValor = 0;
        $costoPromedio = 0;

        //insertamos los datos en la tabla
        $fila = 8;
        // $saldo = 0;
        foreach ($results as $result) {
            if ($result['e_s'] == 'E') {
                //buscamos la entrada con activo = 1 con $result['nro_movimiento'] y $result['id_item']
                $entrada = $this->entrada->where('nro_movimiento', $result['nro_movimiento'])->where('id_item', $result['id_item'])->where('activo', 1)->first();
                //escribimos en la celda a$fila
                $hoja->setCellValue('A' . $fila, str_replace('-', '/', $entrada['fecha']));
                //escribimos en la celda b$fila
                $hoja->setCellValue('B' . $fila, 'Entrada');
                //escribimos en la celda c$fila
                $hoja->setCellValue('C' . $fila, $entrada['concepto']);
                //escribimos en la celda d$fila
                $hoja->setCellValue('D' . $fila, $entrada['id_entrada']);
                //escribimos en la celda e$fila
                $hoja->setCellValue('E' . $fila, $entrada['cantidad']);
                //escribimos en la celda f$fila
                $hoja->setCellValue('F' . $fila, $entrada['costo_unitario']);
                //escribimos en la celda g$fila
                $hoja->setCellValue('G' . $fila, $entrada['importe']);
                //escribimos en la celda h$fila
                $hoja->setCellValue('H' . $fila, 0);
                //escribimos en la celda i$fila
                $hoja->setCellValue('I' . $fila, 0);
                //escribimos en la celda j$fila
                $hoja->setCellValue('J' . $fila, 0);

                //colocamos fondo de color verde super claro desde e$fila hasta g$fila
                $hoja->getStyle('E' . $fila . ':G' . $fila)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFCC');

                $saldoCantidad += $entrada['cantidad'];
                $saldoValor += $entrada['importe'];
                $costoPromedio = $saldoValor / $saldoCantidad;
                //escribimos en la celda k$fila
                $hoja->setCellValue('K' . $fila, $saldoCantidad);
                //escribimos en la celda l$fila
                $hoja->setCellValue('L' . $fila, $costoPromedio);
                //escribimos en la celda m$fila
                $hoja->setCellValue('M' . $fila, $saldoValor);

                $fila++;
            } else {
                //buscamos la salida con activo = 1 con $result['nro_movimiento'] y $result['id_item']
                $salida = $this->salida->where('nro_movimiento', $result['nro_movimiento'])->where('id_item', $result['id_item'])->where('activo', 1)->first();
                // print_r($salida);
                //escribimos en la celda a$fila
                $hoja->setCellValue('A' . $fila, str_replace('-', '/', $salida['fecha']));
                //escribimos en la celda b$fila
                $hoja->setCellValue('B' . $fila, 'Salida');
                //escribimos en la celda c$fila
                $hoja->setCellValue('C' . $fila, $salida['concepto']);
                //escribimos en la celda d$fila
                $hoja->setCellValue('D' . $fila, $salida['id_salida']);
                //escribimos en la celda e$fila
                $hoja->setCellValue('E' . $fila, 0);
                //escribimos en la celda f$fila
                $hoja->setCellValue('F' . $fila, 0);
                //escribimos en la celda g$fila
                $hoja->setCellValue('G' . $fila, 0);
                //escribimos en la celda h$fila
                $hoja->setCellValue('H' . $fila, $salida['cantidad']);
                //escribimos en la celda i$fila
                $hoja->setCellValue('I' . $fila, $salida['costo_unitario']);
                //escribimos en la celda j$f
                $hoja->setCellValue('J' . $fila, $salida['importe']);

                //color de fondo de color rojo super claro desde h$fila hasta j$fila
                $hoja->getStyle('H' . $fila . ':J' . $fila)->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFCCCC');

                $saldoCantidad -= $salida['cantidad'];
                $saldoValor -= $salida['importe'];
                $costoPromedio = $saldoValor / $saldoCantidad;
                //escribimos en la celda k$fila
                $hoja->setCellValue('K' . $fila, $saldoCantidad);
                //escribimos en la celda l$fila
                $hoja->setCellValue('L' . $fila, $costoPromedio);
                //escribimos en la celda m$fila
                $hoja->setCellValue('M' . $fila, $saldoValor);
                $fila++;
            }
        }
        //redondeamos las cantidades de la tabla desde e8 hasta m$filas
        $hoja->getStyle('E8:M' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

        //colocaos un borde delgado a todas las celdas desde a8 hasta m$filas
        $hoja->getStyle('A8:M' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');

        //unimos las celdas para el TOTAL desde a$filas hasta d$filas
        $hoja->mergeCells('A' . ($fila) . ':D' . ($fila));
        //centramos el texto desde a$filas hasta d$filas
        $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getAlignment()->setHorizontal('center');
        //escribimos en la celda a$filas
        $hoja->setCellValue('A' . ($fila), 'TOTAL');

        //sumamos las cantidades desde e8 hasta e$filas
        $hoja->setCellValue('E' . ($fila), '=SUM(E8:E' . $fila . ')');
        //sumamos las cantidades desde g8 hasta g$filas
        $hoja->setCellValue('G' . ($fila), '=SUM(G8:G' . $fila . ')');

        //sumamos las cantidades desde h8 hasta h$filas
        $hoja->setCellValue('H' . ($fila), '=SUM(H8:H' . $fila . ')');
        //sumamos las cantidades desde j8 hasta j$filas
        $hoja->setCellValue('J' . ($fila), '=SUM(J8:J' . $fila . ')');

        //ponemos en negrita desde a$filas hasta m$filas
        $hoja->getStyle('A' . ($fila) . ':M' . ($fila))->getFont()->setBold(true);


        //------------------------------------------------
        $writer = new Xlsx($phpExcel);
        $writer->save($nombreExcel);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($nombreExcel));
        flush(); // Flush system output buffer
        readfile($nombreExcel);
        exit;
    }

    public function generaExcelResumenKardex($fecha_inicio, $fecha_fin)
    {
        $nombreExcel = 'Reporte_resumen_kardex.xlsx';
        $phpExcel = new Spreadsheet();
        $phpExcel->getProperties()
            ->setCreator("ARGT")
            ->setTitle("Reporte resumen kardex");
        $hoja = $phpExcel->getActiveSheet();

        //colocamos una imagen en la celda a1
        $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $objDrawing->setName('nombre_master_pizza');
        $objDrawing->setDescription('nombre_master_pizza');
        $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
        $objDrawing->setCoordinates('A1');
        $objDrawing->setHeight(40);
        $objDrawing->setWorksheet($hoja);

        //----codigo para llenar la hoja------------------

        //obtenemos todos los datos de la tabla item con activo = 1  y total_movimiento > 0
        $Items = $this->item->where('activo', 1)->where('total_movimiento >', 0)->findAll();

        //reemplazamos '-' por '/' en las fechas
        $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
        $fecha_finMod = str_replace('-', '/', $fecha_fin);

        //unimos las celdas desde a2 hasta i2
        $hoja->mergeCells('A2:I2');
        //unicamos las celdas desde a3 hasta i3
        $hoja->mergeCells('A3:I3');
        //unicamos las celdas desde a4 hasta i4
        $hoja->mergeCells('A4:I4');
        //escribimos en la celda a2
        $hoja->setCellValue('A2', 'RESUMEN DEL KARDEX FISICO VALORADO');
        //escribimos en la celda a3
        $hoja->setCellValue('A3', 'MOVIMIENTO DE ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
        //escribimos en la celda a4
        $hoja->setCellValue('A4', '(Expresado en Bolivianos)');
        //cambiamos el tamaño de la letra desde a2 hasta i4
        $hoja->getStyle('A2:I4')->getFont()->setSize(12);
        //cambiamos el color de la letra a azul oscuro desde a2 hasta i4
        $hoja->getStyle('A2:I4')->getFont()->getColor()->setARGB('FF0000FF');
        //colocamos en negrita desde a2 hasta i4
        $hoja->getStyle('A2:I4')->getFont()->setBold(true);
        //centramos el texto desde a2 hasta i4
        $hoja->getStyle('A2:I4')->getAlignment()->setHorizontal('center');

        //definiendo el ancho de la columna a
        $hoja->getColumnDimension('A')->setWidth(12);
        //definiendo el ancho de la columna b
        $hoja->getColumnDimension('B')->setWidth(40);
        //definiendo el ancho de la columna c
        $hoja->getColumnDimension('C')->setWidth(15);
        //definiendo el ancho de la columna d
        $hoja->getColumnDimension('D')->setWidth(15);
        //definiendo el ancho de la columna e
        $hoja->getColumnDimension('E')->setWidth(15);
        //definiendo el ancho de la columna f
        $hoja->getColumnDimension('F')->setWidth(15);
        //definiendo el ancho de la columna g
        $hoja->getColumnDimension('G')->setWidth(15);
        //definiendo el ancho de la columna h
        $hoja->getColumnDimension('H')->setWidth(15);
        //definiendo el ancho de la columna i
        $hoja->getColumnDimension('I')->setWidth(15);

        //unimos las celdas desde a5 hasta a6
        $hoja->mergeCells('A5:A6');
        //unimos las celdas desde b5 hasta b6
        $hoja->mergeCells('B5:B6');
        //unimos las celdas desde c5 hasta d5
        $hoja->mergeCells('C5:C6');
        //unimos las celdas desde d5 hasta e5
        $hoja->mergeCells('D5:E5');
        //unimos las celdas desde g5 hasta h5
        $hoja->mergeCells('F5:G5');
        //unimos las celdas desde i5 hasta i6
        $hoja->mergeCells('H5:I5');

        //colocamos un borde medio grueso  a todas las celdas desde a5 hasta i6
        $hoja->getStyle('A5:I6')->getBorders()->getAllBorders()->setBorderStyle('medium');
        //poneos en negrita desde a5 hasta i6
        $hoja->getStyle('A5:I6')->getFont()->setBold(true);
        //colocamos fondo de color celeste claro desde a5 hasta i6
        $hoja->getStyle('A5:I6')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

        //escribimos en la celda a5
        $hoja->setCellValue('A5', 'Código/ID');
        //escribimos en la celda b5
        $hoja->setCellValue('B5', 'Descripción');
        //escribimos en la celda c5
        $hoja->setCellValue('C5', 'Unidad');
        //escribimos en la celda d5
        $hoja->setCellValue('D5', 'ENTRADA');
        //escribimos en la celda e6
        $hoja->setCellValue('D6', 'Cantidad');
        //escribimos en la celda f6
        $hoja->setCellValue('E6', 'Valor');
        //escribimos en la celda f5
        $hoja->setCellValue('F5', 'SALIDA');
        //escribimos en la celda f6
        $hoja->setCellValue('F6', 'Cantidad');
        //escribimos en la celda g6
        $hoja->setCellValue('G6', 'Valor');
        //escribimos en la celda h5
        $hoja->setCellValue('H5', 'SALDOS');
        //escribimos en la celda h6
        $hoja->setCellValue('H6', 'Cantidad');
        //escribimos en la celda i6
        $hoja->setCellValue('I6', 'Valor');

        //centramos el texto desde a5 hasta i6
        $hoja->getStyle('A5:I6')->getAlignment()->setHorizontal('center');

        //llenamos la  tabla
        $fila = 7;
        foreach ($Items as $item) {
            //obtenemos la unidad de medida del item
            $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();
            //escribimos en la celda a$fila
            $hoja->setCellValue('A' . $fila, $item['id_item']);
            //escribimos en la celda b$fila
            $hoja->setCellValue('B' . $fila, $item['descripcion']);
            //escribimos en la celda c$fila
            $hoja->setCellValue('C' . $fila, $unidad['nombre_unidad']);
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

            //escribimos en la celda d$fila
            $hoja->setCellValue('D' . $fila, $sumaCantidadEntradas);
            //escribimos en la celda e$fila
            $hoja->setCellValue('E' . $fila, $sumaValorEntradas);
            //escribimos en la celda f$fila
            $hoja->setCellValue('F' . $fila, $sumaCantidadSalidas);
            //escribimos en la celda g$fila
            $hoja->setCellValue('G' . $fila, $sumaValorSalidas);
            //escribimos en la celda h$fila
            $hoja->setCellValue('H' . $fila, $saldoCantidad);
            //escribimos en la celda i$fila
            $hoja->setCellValue('I' . $fila, $saldoValor);

            $fila++;
        }

        //redondeamos las cantidades de la tabla desde e8 hasta m$filas
        $hoja->getStyle('E7:I' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
        //colocamos un borde delgado a todas las celdas desde a8 hasta m$filas
        $hoja->getStyle('A7:I' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');

        //unicamos las celdas para el TOTAL desde a$filas hasta c$filas
        $hoja->mergeCells('A' . ($fila) . ':C' . ($fila));
        //escribimos en la celda a$filas
        $hoja->setCellValue('A' . ($fila), 'TOTAL');
        //colocamos en negrita desde a$filas hasta i$filas
        $hoja->getStyle('A' . ($fila) . ':I' . ($fila))->getFont()->setBold(true);
        //centramos el texto desde a$filas hasta c$filas
        $hoja->getStyle('A' . ($fila) . ':C' . ($fila))->getAlignment()->setHorizontal('center');

        //sumamos las cantidades de valores desde e7 hasta e$filas
        $hoja->setCellValue('E' . ($fila), '=SUM(E7:E' . $fila . ')');
        //sumamos las cantidades de valores desde g7 hasta g$filas
        $hoja->setCellValue('G' . ($fila), '=SUM(G7:G' . $fila . ')');
        //sumamos las cantidades de valores desde i7 hasta h$filas
        $hoja->setCellValue('I' . ($fila), '=SUM(I7:I' . $fila . ')');






        //------------------------------------------------
        $writer = new Xlsx($phpExcel);
        $writer->save($nombreExcel);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($nombreExcel));
        flush(); // Flush system output buffer
        readfile($nombreExcel);
        exit;
    }


    public function generaExcelGeneral($fecha_inicio, $fecha_fin, $tipo)
    {

        if ($tipo == 'producto') {
            $nombreExcel = 'Reporte_mov_por_categoria.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte mov por categoria");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------  

            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta h2
            $hoja->mergeCells('A2:H2');
            //unimos las celdas desde a3 hasta h3
            $hoja->mergeCells('A3:H3');
            //unimos las celdas desde a4 hasta h4
            $hoja->mergeCells('A4:H4');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'REPORTE DE MOVIMIENTOS POR CATEGORÍA');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'MOVIMIENTO DE ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
            //escribimos en la celda a4
            $hoja->setCellValue('A4', '(Expresado en Bolivianos)');
            //cambiamos el tamaño de la letra desde a2 hasta h4
            $hoja->getStyle('A2:H4')->getFont()->setSize(12);
            //cambiamos el color de la letra a azul oscuro desde a2 hasta h4
            $hoja->getStyle('A2:H4')->getFont()->getColor()->setARGB('FF0000FF');
            //colocamos en negrita desde a2 hasta h4
            $hoja->getStyle('A2:H4')->getFont()->setBold(true);
            //centramos el texto desde a2 hasta h4
            $hoja->getStyle('A2:H4')->getAlignment()->setHorizontal('center');

            //definiendo el ancho de la columna a
            $hoja->getColumnDimension('A')->setWidth(12);
            //definiendo el ancho de la columna b
            $hoja->getColumnDimension('B')->setWidth(35);
            //definiendo el ancho de la columna c
            $hoja->getColumnDimension('C')->setWidth(15);
            //definiendo el ancho de la columna d
            $hoja->getColumnDimension('D')->setWidth(15);
            //definiendo el ancho de la columna e
            $hoja->getColumnDimension('E')->setWidth(15);
            //definiendo el ancho de la columna f
            $hoja->getColumnDimension('F')->setWidth(15);
            //definiendo el ancho de la columna g
            $hoja->getColumnDimension('G')->setWidth(15);
            //definiendo el ancho de la columna h
            $hoja->getColumnDimension('H')->setWidth(15);

            //unimos las celdas desde a5 hasta a6
            $hoja->mergeCells('A5:A6');
            //unimos las celdas desde b5 hasta b6
            $hoja->mergeCells('B5:B6');
            //unimos las celdas desde c5 hasta d5
            $hoja->mergeCells('C5:D5');
            //unimos las celdas desde e5 hasta f5
            $hoja->mergeCells('E5:F5');
            //unimos las celdas desde g5 hasta h5
            $hoja->mergeCells('G5:H5');

            //colocamos un borde medio grueso  a todas las celdas desde a5 hasta h6
            $hoja->getStyle('A5:H6')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //poneos en negrita desde a5 hasta h6
            $hoja->getStyle('A5:H6')->getFont()->setBold(true);
            //colocamos fondo de color celeste claro desde a5 hasta h6
            $hoja->getStyle('A5:H6')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

            //escribimos en la celda a5
            $hoja->setCellValue('A5', 'Código/ID');
            //escribimos en la celda b5
            $hoja->setCellValue('B5', 'Descripción');
            //escribimos en la celda c5
            $hoja->setCellValue('C5', 'ENTRADA');
            //escribimos en la celda e5
            $hoja->setCellValue('E5', 'SALIDA');
            //escribimos en la celda g5
            $hoja->setCellValue('G5', 'SALDOS');
            //escribimos en la celda c6
            $hoja->setCellValue('C6', 'Cantidad');
            //escribimos en la celda d6
            $hoja->setCellValue('D6', 'Valor');
            //escribimos en la celda e6
            $hoja->setCellValue('E6', 'Cantidad');
            //escribimos en la celda f6
            $hoja->setCellValue('F6', 'Valor');
            //escribimos en la celda g6
            $hoja->setCellValue('G6', 'Cantidad');
            //escribimos en la celda h6
            $hoja->setCellValue('H6', 'Valor');

            //centramos el texto desde a5 hasta h6
            $hoja->getStyle('A5:H6')->getAlignment()->setHorizontal('center');

            $productos = $this->producto->where('activo', 1)->findAll();

            //llenamos la  tabla
            $fila = 7;

            foreach ($productos as $producto) {

                //llenamos la celda a$fila
                $hoja->setCellValue('A' . $fila, $producto['id_producto']);
                //llenamos la celda b$fila
                $hoja->setCellValue('B' . $fila, $producto['nombre_producto']);

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
                // $sumaTotalValorEntradas += $totalImporteEntradas;

                //llenanmos la celda c$fila
                $hoja->setCellValue('C' . $fila, $totalCantidadEntradas);
                //llenanmos la celda d$fila
                $hoja->setCellValue('D' . $fila, $totalImporteEntradas);

                

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
                // $sumaTotalValorSalidas += $totalImporteSalidas;

                //llenanmos la celda e$fila
                $hoja->setCellValue('E' . $fila, $totalCantidadSalidas);
                //llenanmos la celda f$fila
                $hoja->setCellValue('F' . $fila, $totalImporteSalidas);

               


                //hallamos las cantidades de saldos
                $saldoCantidad = $totalCantidadEntradas - $totalCantidadSalidas;
                $saldoValor = $totalImporteEntradas - $totalImporteSalidas;

                //llenamos la celda g$fila
                $hoja->setCellValue('G' . $fila, $saldoCantidad);
                //llenamos la celda h$fila
                $hoja->setCellValue('H' . $fila, $saldoValor);

                $fila++;

            }

            //redondeamos las cantidades de la tabla desde c8 hasta h$filas
            $hoja->getStyle('C7:H' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
            //colocamos un borde delgado a todas las celdas desde a8 hasta h$filas
            $hoja->getStyle('A7:H' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');

            //unicamos las celdas para el TOTAL desde a$filas hasta b$filas
            $hoja->mergeCells('A' . ($fila) . ':B' . ($fila));
            //escribimos en la celda a$filas
            $hoja->setCellValue('A' . ($fila), 'TOTAL');
            //colocamos en negrita desde a$filas hasta h$filas
            $hoja->getStyle('A' . ($fila) . ':H' . ($fila))->getFont()->setBold(true);
            //centramos el texto desde a$filas hasta b$filas
            $hoja->getStyle('A' . ($fila) . ':B' . ($fila))->getAlignment()->setHorizontal('center');

            //sumamos las cantidades de valores desde d7 hasta d$filas
            $hoja->setCellValue('D' . ($fila), '=SUM(D7:D' . $fila . ')');
            //sumamos las cantidades de valores desde f7 hasta f$filas
            $hoja->setCellValue('F' . ($fila), '=SUM(F7:F' . $fila . ')');
            //sumamos las cantidades de valores desde h7 hasta h$filas
            $hoja->setCellValue('H' . ($fila), '=SUM(H7:H' . $fila . ')');



            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;
        }

        if($tipo == 'item'){

            $nombreExcel = 'Reporte_mov_por_item.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte mov por item");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------  

            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta i2
            $hoja->mergeCells('A2:I2');
            //unicamos las celdas desde a3 hasta i3
            $hoja->mergeCells('A3:I3');
            //unicamos las celdas desde a4 hasta i4
            $hoja->mergeCells('A4:I4');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'REPORTE DE MOVIMIENTOS POR ITEM');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'MOVIMIENTO DE ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
            //escribimos en la celda a4
            $hoja->setCellValue('A4', '(Expresado en Bolivianos)');
            //cambiamos el tamaño de la letra desde a2 hasta i4
            $hoja->getStyle('A2:I4')->getFont()->setSize(12);
            //cambiamos el color de la letra a azul oscuro desde a2 hasta i4
            $hoja->getStyle('A2:I4')->getFont()->getColor()->setARGB('FF0000FF');
            //colocamos en negrita desde a2 hasta i4
            $hoja->getStyle('A2:I4')->getFont()->setBold(true);
            //centramos el texto desde a2 hasta i4
            $hoja->getStyle('A2:I4')->getAlignment()->setHorizontal('center');

            //definiendo el ancho de la columna a
            $hoja->getColumnDimension('A')->setWidth(12);
            //definiendo el ancho de la columna b
            $hoja->getColumnDimension('B')->setWidth(50);
            //definiendo el ancho de la columna c
            $hoja->getColumnDimension('C')->setWidth(15);
            //definiendo el ancho de la columna d
            $hoja->getColumnDimension('D')->setWidth(15);
            //definiendo el ancho de la columna e
            $hoja->getColumnDimension('E')->setWidth(15);
            //definiendo el ancho de la columna f
            $hoja->getColumnDimension('F')->setWidth(15);
            //definiendo el ancho de la columna g
            $hoja->getColumnDimension('G')->setWidth(15);
            //definiendo el ancho de la columna h
            $hoja->getColumnDimension('H')->setWidth(15);
            //definiendo el ancho de la columna i
            $hoja->getColumnDimension('I')->setWidth(15);

            //unimos las celdas desde a5 hasta a6
            $hoja->mergeCells('A5:A6');
            //unimos las celdas desde b5 hasta b6
            $hoja->mergeCells('B5:B6');
            //unimos las celdas desde c5 hasta c6
            $hoja->mergeCells('C5:C6');
            //unimos las celdas desde d5 hasta e5
            $hoja->mergeCells('D5:E5');
            //unimos las celdas desde f5 hasta g5
            $hoja->mergeCells('F5:G5');
            //unimos las celdas desde h5 hasta i5
            $hoja->mergeCells('H5:I5');

            //colocamos un borde medio grueso  a todas las celdas desde a5 hasta i6
            $hoja->getStyle('A5:I6')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //poneos en negrita desde a5 hasta i6
            $hoja->getStyle('A5:I6')->getFont()->setBold(true);
            //colocamos fondo de color celeste claro desde a5 hasta i6
            $hoja->getStyle('A5:I6')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

            //escribimos en la celda a5
            $hoja->setCellValue('A5', 'Código/ID');
            //escribimos en la celda b5
            $hoja->setCellValue('B5', 'Descripción');
            //escribimos en la celda c5
            $hoja->setCellValue('C5', 'Unidad');
            //escribimos en la celda d5
            $hoja->setCellValue('D5', 'ENTRADA');
            //escribimos en la celda f5
            $hoja->setCellValue('F5', 'SALIDA');
            //escribimos en la celda h5
            $hoja->setCellValue('H5', 'SALDOS');
            //escribimos en la celda d6
            $hoja->setCellValue('D6', 'Cantidad');
            //escribimos en la celda e6
            $hoja->setCellValue('E6', 'Valor');
            //escribimos en la celda f6
            $hoja->setCellValue('F6', 'Cantidad');
            //escribimos en la celda g6
            $hoja->setCellValue('G6', 'Valor');
            //escribimos en la celda h6
            $hoja->setCellValue('H6', 'Cantidad');
            //escribimos en la celda i6
            $hoja->setCellValue('I6', 'Valor');

            //centramos el texto desde a5 hasta i6
            $hoja->getStyle('A5:I6')->getAlignment()->setHorizontal('center');



            $productos = $this->producto->where('activo', 1)->findAll();

            //suma de totales
            $sumaTotalValorEntradas = 0;
            $sumaTotalValorSalidas = 0;
            $sumaTotalValorSaldo = 0;

            //llenamos la  tabla
            $fila = 7;

            foreach ($productos as $producto) {

                //unimos las celdas desde a$fila hasta i$fila
                $hoja->mergeCells('A' . $fila . ':I' . $fila);
                //escribimos en la celda a$fila
                $hoja->setCellValue('A' . $fila, $producto['id_producto'] . " - " . $producto['nombre_producto']);
                //colocamos color de texto verde oscuro desde a$fila hasta i$fila
                $hoja->getStyle('A' . $fila . ':I' . $fila)->getFont()->getColor()->setARGB('9A1207');
                //colocamos fondo de color amarillo claro desde a$fila hasta i$fila
                $hoja->getStyle('A' . $fila . ':I' . $fila)->getFill()->setFillType('solid')->getStartColor()->setARGB('FEFFDD');

                $fila++;

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
 
                    //llenanmos la celda a$fila
                    $hoja->setCellValue('A' . $fila, $item['id_item']);
                    //llenanmos la celda b$fila
                    $hoja->setCellValue('B' . $fila, $item['descripcion']);
                    //llenanmos la celda c$fila
                    $hoja->setCellValue('C' . $fila, $unidad['nombre_unidad']);
                   


                    //buscamos todas las entradas con el id_item y activo = 1
                    $entradas = $this->entrada->where('id_item', $item['id_item'])->where('activo', 1)->findAll();

                    $totalCantidadEntradas = 0;
                    $totalImporteEntradas = 0;


                    foreach ($entradas as $entrada) {
                        $totalCantidadEntradas += $entrada['cantidad'];
                        $totalImporteEntradas += $entrada['importe'];
                    }

                    $sumaValorEntradas += $totalImporteEntradas;

                    //llenanmos la celda d$fila
                    $hoja->setCellValue('D' . $fila, $totalCantidadEntradas);
                    //llenanmos la celda e$fila
                    $hoja->setCellValue('E' . $fila, $totalImporteEntradas);


            


                    //buscamos las salidas con el id_item y activo = 1
                    $salidas = $this->salida->where('id_item', $result['id_item'])->where('activo', 1)->findAll();

                    $totalCantidadSalidas = 0;
                    $totalImporteSalidas = 0;

                    foreach ($salidas as $salida) {
                        $totalCantidadSalidas += $salida['cantidad'];
                        $totalImporteSalidas += $salida['importe'];
                    }

                    $sumaValorSalidas += $totalImporteSalidas;

                    //llenamos la celda f$fila
                    $hoja->setCellValue('F' . $fila, $totalCantidadSalidas);
                    //llenamos la celda g$fila
                    $hoja->setCellValue('G' . $fila, $totalImporteSalidas);

                    

                    //hallamos saldo cantidad y saldo valor
                    $saldoCantidad = $totalCantidadEntradas - $totalCantidadSalidas;
                    $saldoValor = $totalImporteEntradas - $totalImporteSalidas;

                    $sumaValorSaldo += $saldoValor;

                    //llenamos la celda h$fila
                    $hoja->setCellValue('H' . $fila, $saldoCantidad);
                    //llenamos la celda i$fila
                    $hoja->setCellValue('I' . $fila, $saldoValor);

                    //colocaos un borde delgado a todas las celdas desde a$fila hasta i$fila
                    $hoja->getStyle('A' . $fila . ':I' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
                    //redondeamos las cantidades de la tabla desde e$filas hasta i$filas
                    $hoja->getStyle('E' . $fila . ':I' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

                    $fila++;

                    
                }

                $sumaTotalValorEntradas += $sumaValorEntradas;
                $sumaTotalValorSalidas += $sumaValorSalidas;
                $sumaTotalValorSaldo += $sumaValorSaldo;

                //colocamos un borde delgado a todas las celdas desde a$fila hasta i$fila
                $hoja->getStyle('A' . ($fila) . ':I' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('thin');
                //unimos para total desde a$fila hasta c$fila
                $hoja->mergeCells('A' . ($fila) . ':C' . ($fila));
                //escribimos en la celda a$fila
                $hoja->setCellValue('A' . ($fila), 'TOTAL');
                //colocamos en negrita desde a$fila hasta i$fila
                $hoja->getStyle('A' . ($fila) . ':I' . ($fila))->getFont()->setBold(true);
                //centramos el texto desde a$fila hasta c$fila
                $hoja->getStyle('A' . ($fila) . ':C' . ($fila))->getAlignment()->setHorizontal('center');

                //llenamos la celda e$fila
                $hoja->setCellValue('E' . ($fila), $sumaValorEntradas);
                //llenamos la celda g$fila
                $hoja->setCellValue('G' . ($fila), $sumaValorSalidas);
                //llenamos la celda i$fila
                $hoja->setCellValue('I' . ($fila), $sumaValorSaldo);

                //redondeamos las cantidades de la tabla desde e$filas hasta i$filas
                $hoja->getStyle('E' . $fila . ':I' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

                $fila++;

                
            }
            $fila++;
            //unicamos las celdas para el TOTAL GENERAL desde a$fila hasta c$fila
            $hoja->mergeCells('A' . ($fila) . ':C' . ($fila));
            //escribimos en la celda a$fila
            $hoja->setCellValue('A' . ($fila), 'TOTAL GENERAL');
            //colocamos en negrita desde a$fila hasta i$fila
            $hoja->getStyle('A' . ($fila) . ':I' . ($fila))->getFont()->setBold(true);
            //centramos el texto desde a$fila hasta c$fila
            $hoja->getStyle('A' . ($fila) . ':C' . ($fila))->getAlignment()->setHorizontal('center');
            //colocamos borde grueso a todas las celdas desde a$fila hasta i$fila
            $hoja->getStyle('A' . ($fila) . ':I' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('medium');
            //llenanmos la celda e$fila
            $hoja->setCellValue('E' . ($fila), $sumaTotalValorEntradas);
            //llenanmos la celda g$fila
            $hoja->setCellValue('G' . ($fila), $sumaTotalValorSalidas);
            //llenanmos la celda i$fila
            $hoja->setCellValue('I' . ($fila), $sumaTotalValorSaldo);

         

            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;

        }
    }

    public function generaExcelReporteVarios($fecha_inicio, $fecha_fin, $tipo)
    {
        if ($tipo == 'tipo_entrada') {

            $nombreExcel = 'Reporte_por_tipo_entrada.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte por tipo entrada");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------  

            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta g2
            $hoja->mergeCells('A2:G2');
            //unimos las celdas desde a3 hasta g3
            $hoja->mergeCells('A3:G3');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'DETALLE DE ENTRADAS AL ALMACÉN (Por tipo de entrada)');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'DEL ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
            //cambiamos el tamaño de la letra desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->setSize(12);
            //cambiamos el color de la letra a azul oscuro desde a2 hasta g3    
            $hoja->getStyle('A2:G3')->getFont()->getColor()->setARGB('FF0000FF');
            //colocamos en negrita desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->setBold(true);
            //centramos el texto desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getAlignment()->setHorizontal('center');
            
            //definiendo el ancho de la columna a
            $hoja->getColumnDimension('A')->setWidth(12);
            //definiendo el ancho de la columna b
            $hoja->getColumnDimension('B')->setWidth(15);
            //definiendo el ancho de la columna c
            $hoja->getColumnDimension('C')->setWidth(15);
            //definiendo el ancho de la columna d
            $hoja->getColumnDimension('D')->setWidth(40);
            //definiendo el ancho de la columna e
            $hoja->getColumnDimension('E')->setWidth(15);
            //definiendo el ancho de la columna f
            $hoja->getColumnDimension('F')->setWidth(15);
            //definiendo el ancho de la columna g
            $hoja->getColumnDimension('G')->setWidth(15);

            //escribimos en la celda a4
            $hoja->setCellValue('A4', 'No ingreso');
            //escribimos en la celda b4
            $hoja->setCellValue('B4', 'Fecha ingreso');
            //escribimos en la celda c4
            $hoja->setCellValue('C4', 'Factura o recibo');
            //escribimos en la celda d4
            $hoja->setCellValue('D4', 'Proveedor');
            //escribimos en la celda e4
            $hoja->setCellValue('E4', 'Importe (Bs)');
            //escribimos en la celda f4
            $hoja->setCellValue('F4', 'I.V.A. (Bs)');
            //escribimos en la celda g4
            $hoja->setCellValue('G4', 'Costo (Bs)');

            //centramos el texto desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getAlignment()->setHorizontal('center');

            //colocamos un borde medio grueso  a todas las celdas desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //poneos en negrita desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getFont()->setBold(true);
            //colocamos fondo de color celeste claro desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

            //obtenemos todos los tipos de entrada con activo = 1
            $tentradas = $this->tentrada->where('activo', 1)->findAll();
            //llenanmos la  tabla
            $fila = 5;

            foreach ($tentradas as $tentrada) {
                //buscamos todas las entradas con activo = 1 y id_tentrada = $tentrada['id_tentrada'] y que este dentro del rangos de fechas
                $entradas = $this->entrada->where('activo', 1)->where('id_tipoentrada', $tentrada['id_tipoentrada'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                // $entradas = $this->entrada->where('activo', 1)->where('id_tipoentrada', $tentrada['id_tipoentrada'])->findAll();

                //preguntamos si hay entradas
                if ($entradas) {

                    $totalImporte = 0;
                    $totalIva = 0;
                    $totalCosto = 0;
                    //unicamos las celdas para el tipo de entrada desde a$fila hasta g$fila
                    $hoja->mergeCells('A' . ($fila) . ':G' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), $tentrada['nombre_entrada']);
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //ponemos en color de fondo amarillo claro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFill()->setFillType('solid')->getStartColor()->setARGB('FEFFDD');
                    //cambiamos el color de la letra a rojo oscuro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->getColor()->setARGB('9A1207');

                    $fila++;

        
                    foreach ($entradas as $entrada) {
                        //llenamos la celda a$fila
                        $hoja->setCellValue('A' . $fila, $entrada['id_entrada']);
                        //llenamos la celda b$fila
                        $hoja->setCellValue('B' . $fila, str_replace('-', '/', $entrada['fecha']));
                        //llenamos la celda c$fila
                        $hoja->setCellValue('C' . $fila, $entrada['nota_recepcion']);
                      
                        //buscamos el proveedor con el id_proveedor de la entrada
                        $proveedor = $this->proveedor->where('id_proveedor', $entrada['id_proveedor'])->first();
                        //llenamos la celda d$fila
                        $hoja->setCellValue('D' . $fila, $proveedor['nombre_proveedor']);
                        //llenamos la celda e$fila
                        $hoja->setCellValue('E' . $fila, $entrada['total_precio']);
                        //llenamos la celda f$fila
                        $hoja->setCellValue('F' . $fila, $entrada['total_precio'] - $entrada['importe']);
                        //llenamos la celda g$fila
                        $hoja->setCellValue('G' . $fila, $entrada['importe']);

                        //colocamos borde delgado a todas las celdas desde a$fila hasta g$fila
                        $hoja->getStyle('A' . $fila . ':G' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
                        //redondeamos las cantidades de la tabla desde e$filas hasta g$filas
                        $hoja->getStyle('E' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
                        
                        $totalImporte += $entrada['total_precio'];
                        $totalIva += $entrada['total_precio'] - $entrada['importe'];
                        $totalCosto += $entrada['importe'];

                        $fila++;
                    }
                    //unicamos las celdas para el TOTAL desde a$fila hasta d$fila
                    $hoja->mergeCells('A' . ($fila) . ':D' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'TOTAL');
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //centramos el texto desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getAlignment()->setHorizontal('center');
                    //llenamos la celda e$fila
                    $hoja->setCellValue('E' . ($fila), $totalImporte);
                    //llenamos la celda f$fila
                    $hoja->setCellValue('F' . ($fila), $totalIva);
                    //llenamos la celda g$fila
                    $hoja->setCellValue('G' . ($fila), $totalCosto);
                    //colocaos un borde medio grueso a todas las celdas desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('medium');
                    //redondeamos las cantidades de la tabla desde e$filas hasta g$filas
                    $hoja->getStyle('E' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $fila=$fila+2;
                    
                } else {
                    //cuando no entradas damos continue al bucle
                    continue;
                }
            }



            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;
        }
        if ($tipo == 'tipo_salida') {

            $nombreExcel = 'Reporte_por_tipo_salida.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte por tipo salida");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(20);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------ 

            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta d2
            $hoja->mergeCells('A2:D2');
            //unimos las celdas desde a3 hasta d3
            $hoja->mergeCells('A3:D3');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'DETALLE DE SALIDAS DEL ALMACÉN (Por tipo de salida)');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'DEL ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
            //cambiamos el tamaño de la letra desde a2 hasta d3
            $hoja->getStyle('A2:D3')->getFont()->setSize(12);
            //cambiamos el color de la letra a azul oscuro desde a2 hasta d3
            $hoja->getStyle('A2:D3')->getFont()->getColor()->setARGB('FF0000FF');
            //colocamos en negrita desde a2 hasta d3
            $hoja->getStyle('A2:D3')->getFont()->setBold(true);
            //centramos el texto desde a2 hasta d3
            $hoja->getStyle('A2:D3')->getAlignment()->setHorizontal('center');

            //definiendo el ancho de la columna a
            $hoja->getColumnDimension('A')->setWidth(15);
            //definiendo el ancho de la columna b
            $hoja->getColumnDimension('B')->setWidth(15);
            //definiendo el ancho de la columna c
            $hoja->getColumnDimension('C')->setWidth(40);
            //definiendo el ancho de la columna d
            $hoja->getColumnDimension('D')->setWidth(20);

            //escribimos en la celda a4
            $hoja->setCellValue('A4', 'No salida');
            //escribimos en la celda b4
            $hoja->setCellValue('B4', 'Fecha salida');
            //escribimos en la celda c4
            $hoja->setCellValue('C4', 'Destino');
            //escribimos en la celda d4
            $hoja->setCellValue('D4', 'Importe (Bs)');
            //centramos el texto desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getAlignment()->setHorizontal('center');
            //colocamos un borde medio grueso  a todas las celdas desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //poneos en negrita desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getFont()->setBold(true);
            //colocamos fondo de color celeste claro desde a4 hasta d4
            $hoja->getStyle('A4:D4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

            //obtenemos todas los tipos de salidas con activo = 1
            $tsalidas = $this->tsalida->where('activo', 1)->findAll();

            //llenanmos la  tabla
            $fila = 5;

            foreach ($tsalidas as $tsalida) {
                //buscamos todas las salidas con activo = 1 y id_tsalida = $tsalida['id_tsalida'] y que este dentro del rangos de fechas
                $salidas = $this->salida->where('activo', 1)->where('id_tiposalida', $tsalida['id_tiposalida'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                
                //preguntamos si hay salidas
                if ($salidas) {

                    $totalImporte = 0;
                    //unicamos las celdas para el tipo de salida desde a$fila hasta d$fila
                    $hoja->mergeCells('A' . ($fila) . ':D' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), $tsalida['nombre_salida']);
                    //colocamos en negrita desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getFont()->setBold(true);
                    //ponemos en color de fondo amarillo claro desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getFill()->setFillType('solid')->getStartColor()->setARGB('FEFFDD');
                    //cambiamos el color de letra a rojo oscuro desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getFont()->getColor()->setARGB('9A1207');
                    
                    $fila++;
                    foreach ($salidas as $salida) {
                        //llenamos la celda a$fila
                        $hoja->setCellValue('A' . $fila, $salida['id_salida']);
                        //llenamos la celda b$fila
                        $hoja->setCellValue('B' . $fila, str_replace('-', '/', $salida['fecha']));
                        //llenamos la celda c$fila
                        $hoja->setCellValue('C' . $fila, $salida['destino']);
                        //llenamos la celda d$fila
                        $hoja->setCellValue('D' . $fila, $salida['importe']);
                        // colocaos un borde delgado a todas las celdas desde a$fila hasta d$fila
                        $hoja->getStyle('A' . $fila . ':D' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
                        //redondeamos las cantidades de la tabla desde d$filas hasta d$filas
                        $hoja->getStyle('D' . $fila . ':D' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

                        $totalImporte += $salida['importe'];
                        $fila++;
                    }
                    //unicamos las celdas para el TOTAL desde a$fila hasta c$fila
                    $hoja->mergeCells('A' . ($fila) . ':C' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'TOTAL');
                    //colocamos en negrita desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getFont()->setBold(true);
                    //centramos el texto desde a$fila hasta c$fila
                    $hoja->getStyle('A' . ($fila) . ':C' . ($fila))->getAlignment()->setHorizontal('center');
                    //llenamos la celda d$fila
                    $hoja->setCellValue('D' . ($fila), $totalImporte);
                    //colocaos un borde medio grueso a todas las celdas desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('medium');
                    //redondeamos las cantidades de la tabla desde d$filas hasta d$filas
                    $hoja->getStyle('D' . $fila . ':D' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $fila=$fila+2;
                    
                } else {
                    //cuando no salidas damos continue al bucle
                    continue;
                }
            }


            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;
        }
        if ($tipo == 'proveedor') {

            $nombreExcel = 'Reporte_por_proveedor.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte por proveedor");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------ 
            
            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta g2
            $hoja->mergeCells('A2:G2');
            //unimos las celdas desde a3 hasta g3
            $hoja->mergeCells('A3:G3');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'REPORTE POR PROVEEDOR');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'DEL ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
            //cambiamos el tamaño de la letra desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->setSize(12);
            //cambiamos el color de la letra a azul oscuro desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->getColor()->setARGB('FF0000FF');
            //colocamos en negrita desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->setBold(true);
            //centramos el texto desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getAlignment()->setHorizontal('center');

            //definiendo el ancho de la columna a
            $hoja->getColumnDimension('A')->setWidth(12);
            //definiendo el ancho de la columna b
            $hoja->getColumnDimension('B')->setWidth(15);
            //definiendo el ancho de la columna c
            $hoja->getColumnDimension('C')->setWidth(15);
            //definiendo el ancho de la columna d
            $hoja->getColumnDimension('D')->setWidth(50);
            //definiendo el ancho de la columna e
            $hoja->getColumnDimension('E')->setWidth(15);
            //definiendo el ancho de la columna f
            $hoja->getColumnDimension('F')->setWidth(15);
            //definiendo el ancho de la columna g
            $hoja->getColumnDimension('G')->setWidth(15);

            //escribimos en la celda a4
            $hoja->setCellValue('A4', 'No ingreso');
            //escribimos en la celda b4
            $hoja->setCellValue('B4', 'Fecha ingreso');
            //escribimos en la celda c4
            $hoja->setCellValue('C4', 'Factura o recibo');
            //escribimos en la celda d4
            $hoja->setCellValue('D4', 'Item');
            //escribimos en la celda e4
            $hoja->setCellValue('E4', 'Importe (Bs)');
            //escribimos en la celda f4
            $hoja->setCellValue('F4', 'I.V.A. (Bs)');
            //escribimos en la celda g4
            $hoja->setCellValue('G4', 'Costo (Bs)');
            //centramos el texto desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getAlignment()->setHorizontal('center');
            //colocamos un borde medio grueso  a todas las celdas desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //poneos en negrita desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getFont()->setBold(true);
            //colocamos fondo de color celeste claro desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

            //obtenemos todos los proveedores con activo = 1
            $proveedores = $this->proveedor->where('activo', 1)->findAll();

            //llenamos la  tabla
            $fila = 5;

            foreach ($proveedores as $proveedor) {
                //buscamos todas las entradas con activo = 1 y id_proveedor = $proveedor['id_proveedor'] y que este dentro del rangos de fechas
                $entradas = $this->entrada->where('activo', 1)->where('id_proveedor', $proveedor['id_proveedor'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
          

                //preguntamos si hay entradas
                if ($entradas) {

                    $totalImporte = 0;
                    $totalIva = 0;
                    $totalCosto = 0;
                    //unicamos las celdas para el proveedor desde a$fila hasta g$fila
                    $hoja->mergeCells('A' . ($fila) . ':G' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), $proveedor['nombre_proveedor']);
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //ponemos en color de fondo amarillo claro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFill()->setFillType('solid')->getStartColor()->setARGB('FEFFDD');
                    //cambiamos el color de letra a rojo oscuro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->getColor()->setARGB('9A1207');
                    
                    $fila++;

                    foreach ($entradas as $entrada) {
                        //buscamos el item con el id_item de la entrada
                        $item = $this->item->where('id_item', $entrada['id_item'])->first();

                        //llenamos la celda a$fila
                        $hoja->setCellValue('A' . $fila, $entrada['id_entrada']);
                        //llenamos la celda b$fila
                        $hoja->setCellValue('B' . $fila, str_replace('-', '/', $entrada['fecha']));
                        //llenamos la celda c$fila
                        $hoja->setCellValue('C' . $fila, $entrada['nota_recepcion']);
                        //llenamos la celda d$fila
                        $hoja->setCellValue('D' . $fila, $item['descripcion']);
                        //llenamos la celda e$fila
                        $hoja->setCellValue('E' . $fila, $entrada['total_precio']);
                        //llenamos la celda f$fila
                        $hoja->setCellValue('F' . $fila, $entrada['total_precio'] - $entrada['importe']);
                        //llenamos la celda g$fila
                        $hoja->setCellValue('G' . $fila, $entrada['importe']);

                        //colocamos borde delgado a todas las celdas desde a$fila hasta g$fila
                        $hoja->getStyle('A' . $fila . ':G' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
                        //redondeamos las cantidades de la tabla desde e$filas hasta g$filas
                        $hoja->getStyle('E' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

 
                        $totalImporte += $entrada['total_precio'];
                        $totalIva += $entrada['total_precio'] - $entrada['importe'];
                        $totalCosto += $entrada['importe'];

                        $fila++;
                    }
                    //unicamos las celdas para el TOTAL desde a$fila hasta d$fila
                    $hoja->mergeCells('A' . ($fila) . ':D' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'TOTAL');
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //centramos el texto desde a$fila hasta d$fila
                    $hoja->getStyle('A' . ($fila) . ':D' . ($fila))->getAlignment()->setHorizontal('center');
                    //llenamos la celda e$fila
                    $hoja->setCellValue('E' . ($fila), $totalImporte);
                    //llenamos la celda f$fila
                    $hoja->setCellValue('F' . ($fila), $totalIva);
                    //llenamos la celda g$fila
                    $hoja->setCellValue('G' . ($fila), $totalCosto);
                    //colocaos un borde medio grueso a todas las celdas desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('medium');
                    //redondeamos las cantidades de la tabla desde e$filas hasta g$filas
                    $hoja->getStyle('E' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $fila=$fila+2;

                } else {
                    //cuando no entradas damos continue al bucle
                    continue;
                }
            }


            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;

        }
        if ($tipo == 'usuario') {

            $nombreExcel = 'Reporte_por_personal.xlsx';
            $phpExcel = new Spreadsheet();
            $phpExcel->getProperties()
                ->setCreator("ARGT")
                ->setTitle("Reporte por personal");
            $hoja = $phpExcel->getActiveSheet();

            //colocamos una imagen en la celda a1
            $objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $objDrawing->setName('nombre_master_pizza');
            $objDrawing->setDescription('nombre_master_pizza');
            $objDrawing->setPath('./assets/img/nombre_master_pizza.png');
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(40);
            $objDrawing->setWorksheet($hoja);

            //----codigo para llenar la hoja------------------ 
            
            //reemplazamos '-' por '/' en las fechas
            $fecha_inicioMod = str_replace('-', '/', $fecha_inicio);
            $fecha_finMod = str_replace('-', '/', $fecha_fin);

            //unimos las celdas desde a2 hasta g2
            $hoja->mergeCells('A2:G2');
            //unimos las celdas desde a3 hasta g3
            $hoja->mergeCells('A3:G3');
            //escribimos en la celda a2
            $hoja->setCellValue('A2', 'REPORTE DE RECEPCIÓN Y ENTREGA POR EMPLEADO');
            //escribimos en la celda a3
            $hoja->setCellValue('A3', 'DEL ' . $fecha_inicioMod . ' AL ' . $fecha_finMod);
            //cambiamos el tamaño de la letra desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->setSize(12);
            //cambiamos el color de la letra a azul oscuro desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->getColor()->setARGB('FF0000FF');
            //colocamos en negrita desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getFont()->setBold(true);
            //centramos el texto desde a2 hasta g3
            $hoja->getStyle('A2:G3')->getAlignment()->setHorizontal('center');
            
            //definiendo el ancho de la columna a
            $hoja->getColumnDimension('A')->setWidth(10);
            //definiendo el ancho de la columna b
            $hoja->getColumnDimension('B')->setWidth(15);
            //definiendo el ancho de la columna c
            $hoja->getColumnDimension('C')->setWidth(50);
            //definiendo el ancho de la columna d
            $hoja->getColumnDimension('D')->setWidth(15);
            //definiendo el ancho de la columna e
            $hoja->getColumnDimension('E')->setWidth(15);
            //definiendo el ancho de la columna f
            $hoja->getColumnDimension('F')->setWidth(15);
            //definiendo el ancho de la columna g
            $hoja->getColumnDimension('G')->setWidth(15);

            //escribimos en la celda a4
            $hoja->setCellValue('A4', 'No E/S');
            //escribimos en la celda b4
            $hoja->setCellValue('B4', 'Fecha');
            //escribimos en la celda c4
            $hoja->setCellValue('C4', 'Item');
            //escribimos en la celda d4
            $hoja->setCellValue('D4', 'Unidad');
            //escribimos en la celda e4
            $hoja->setCellValue('E4', 'Cantidad');
            //escribimos en la celda f4
            $hoja->setCellValue('F4', 'Costo U. (Bs)');
            //escribimos en la celda g4
            $hoja->setCellValue('G4', 'Importe (Bs)');
            //centramos el texto desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getAlignment()->setHorizontal('center');
            //colocamos un borde medio grueso  a todas las celdas desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getBorders()->getAllBorders()->setBorderStyle('medium');
            //poneos en negrita desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getFont()->setBold(true);
            //colocamos fondo de color celeste claro desde a4 hasta g4
            $hoja->getStyle('A4:G4')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFCCFFFF');

            //obtenemos los datos de todos los usuarios con activo 1
            $usuarios = $this->usuario->where('activo', 1)->findAll();

            //llenanmos la  tabla
            $fila = 5;

            foreach ($usuarios as $usuario) {
                //buscamos todas las entradas con activo = 1 y id_usuario = $usuario['id_usuario'] y que este dentro del rangos de fechas
                $entradas = $this->entrada->where('activo', 1)->where('id_usuario2', $usuario['id_usuario'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
       
                //unicamos las celdas para el usuario desde a$fila hasta g$fila
                $hoja->mergeCells('A' . ($fila) . ':G' . ($fila));
                //escribimos en la celda a$fila
                $hoja->setCellValue('A' . ($fila), $usuario['nombre_usuario']);
                //colocamos en negrita desde a$fila hasta g$fila
                $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                //ponemos en color de fondo verde super claro desde a$fila hasta g$fila
                $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFill()->setFillType('solid')->getStartColor()->setARGB('FFD9F4D9');
                //cambiamos el color de letra a verde oscuro desde a$fila hasta g$fila
                $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->getColor()->setARGB('006100');

                $fila=$fila+2;
                //preguntamos si hay entradas
                if ($entradas) {

                    $totalImporte = 0;
                    //unicamos las celdas para el usuario desde a$fila hasta g$fila
                    $hoja->mergeCells('A' . ($fila) . ':G' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'RECEPCIONES');
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //ponemos en color de fondo amarillo claro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFill()->setFillType('solid')->getStartColor()->setARGB('FEFFDD');
                    //cambiamos el color de letra a rojo oscuro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->getColor()->setARGB('9A1207');

                    $fila++;
                    


                    foreach ($entradas as $entrada) {
                        //buscamos el item con el id_item de la entrada
                        $item = $this->item->where('id_item', $entrada['id_item'])->first();

                        //llenamos la celda a$fila
                        $hoja->setCellValue('A' . $fila, $entrada['id_entrada']);
                        //llenamos la celda b$fila
                        $hoja->setCellValue('B' . $fila, str_replace('-', '/', $entrada['fecha']));
                        //llenamos la celda c$fila
                        $hoja->setCellValue('C' . $fila, $item['descripcion']);
                        
                        
                        //buscamos el nombre unidad con el id_unidad de la entrada
                        $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();


                        //llenamos la celda d$fila
                        $hoja->setCellValue('D' . $fila, $unidad['nombre_unidad']);
                        //llenamos la celda e$fila
                        $hoja->setCellValue('E' . $fila, $entrada['cantidad']);
                        //llenamos la celda f$fila
                        $hoja->setCellValue('F' . $fila, $entrada['costo_unitario']);
                        //llenamos la celda g$fila
                        $hoja->setCellValue('G' . $fila, $entrada['importe']);

                        //colocamos borde delgado a todas las celdas desde a$fila hasta g$fila
                        $hoja->getStyle('A' . $fila . ':G' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
                        //redondeamos las cantidades de la tabla desde e$filas hasta g$filas
                        $hoja->getStyle('E' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

                        $totalImporte += $entrada['importe'];
                        $fila++;
                    }
                    //unicamos las celdas para el TOTAL desde a$fila hasta f$fila
                    $hoja->mergeCells('A' . ($fila) . ':F' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'TOTAL');
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //centramos el texto desde a$fila hasta f$fila
                    $hoja->getStyle('A' . ($fila) . ':F' . ($fila))->getAlignment()->setHorizontal('center');
                    //llenamos la celda g$fila
                    $hoja->setCellValue('G' . ($fila), $totalImporte);
                    //colocaos un borde medio grueso a todas las celdas desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('medium');
                    //redondeamos las cantidades de la tabla desde g$filas hasta g$filas
                    $hoja->getStyle('G' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $fila=$fila+2;
           
                }
                //buscamos todas las salidas con activo 1 con id_usuario = $usuario['id_usuario'] y que este dentro del rangos de fechas
                $salidas = $this->salida->where('activo', 1)->where('id_usuario2', $usuario['id_usuario'])->where('fecha_alta >=', $fecha_inicio)->where('fecha_alta <=', $fecha_fin)->findAll();
                //preguntamos si hay salidas
                if ($salidas) {

                    $totalImporte = 0;
                    //unicamos las celdas para el usuario desde a$fila hasta g$fila
                    $hoja->mergeCells('A' . ($fila) . ':G' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'ENTREGAS');
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //ponemos en color de fondo amarillo claro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFill()->setFillType('solid')->getStartColor()->setARGB('FEFFDD');
                    //cambiamos el color de letra a rojo oscuro desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->getColor()->setARGB('9A1207');

                    $fila++;
                    

                    foreach ($salidas as $salida) {
                        //buscamos el item con el id_item de la salida
                        $item = $this->item->where('id_item', $salida['id_item'])->first();
                       
                        //llenamos la celda a$fila
                        $hoja->setCellValue('A' . $fila, $salida['id_salida']);
                        //llenamos la celda b$fila
                        $hoja->setCellValue('B' . $fila, str_replace('-', '/', $salida['fecha']));
                        //llenamos la celda c$fila
                        $hoja->setCellValue('C' . $fila, $item['descripcion']);

                      
                        //buscamos el nombre unidad con el id_unidad de la salida
                        $unidad = $this->unidadmedida->where('id_unidadmedida', $item['id_unidadmedida'])->first();
                        //llenamos la celda d$fila
                        $hoja->setCellValue('D' . $fila, $unidad['nombre_unidad']);
                        //llenamos la celda e$fila
                        $hoja->setCellValue('E' . $fila, $salida['cantidad']);
                        //llenamos la celda f$fila
                        $hoja->setCellValue('F' . $fila, $salida['costo_unitario']);
                        //llenamos la celda g$fila
                        $hoja->setCellValue('G' . $fila, $salida['importe']);

                        //colocamos borde delgado a todas las celdas desde a$fila hasta g$fila
                        $hoja->getStyle('A' . $fila . ':G' . $fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
                        //redondeamos las cantidades de la tabla desde e$filas hasta g$filas
                        $hoja->getStyle('E' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

                        $totalImporte += $salida['importe'];

                        $fila++;
                    }
                    //unicamos las celdas para el TOTAL desde a$fila hasta f$fila
                    $hoja->mergeCells('A' . ($fila) . ':F' . ($fila));
                    //escribimos en la celda a$fila
                    $hoja->setCellValue('A' . ($fila), 'TOTAL');
                    //colocamos en negrita desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getFont()->setBold(true);
                    //centramos el texto desde a$fila hasta f$fila
                    $hoja->getStyle('A' . ($fila) . ':F' . ($fila))->getAlignment()->setHorizontal('center');
                    //llenamos la celda g$fila
                    $hoja->setCellValue('G' . ($fila), $totalImporte);
                    //colocaos un borde medio grueso a todas las celdas desde a$fila hasta g$fila
                    $hoja->getStyle('A' . ($fila) . ':G' . ($fila))->getBorders()->getAllBorders()->setBorderStyle('medium');
                    //redondeamos las cantidades de la tabla desde g$filas hasta g$filas
                    $hoja->getStyle('G' . $fila . ':G' . $fila)->getNumberFormat()->setFormatCode('#,##0.00');

                    $fila=$fila+2;
  
                }
            }

            //------------------------------------------------
            $writer = new Xlsx($phpExcel);
            $writer->save($nombreExcel);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . basename($nombreExcel) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($nombreExcel));
            flush(); // Flush system output buffer
            readfile($nombreExcel);
            exit;

        }

    }
}
