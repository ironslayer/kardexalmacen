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
                $hoja->setCellValue('A' . $fila, $fila-4);
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
            header('Content-Disposition: attachment;filename="'.basename($nombreExcel).'"');
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
                $hoja->setCellValue('A' . $fila, $fila-4);
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
            header('Content-Disposition: attachment;filename="'.basename($nombreExcel).'"');
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
