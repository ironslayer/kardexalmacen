<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SalidaModel;
use App\Models\TsalidaModel;
use App\Models\ProveedorModel;
use App\Models\UsuarioModel;
use App\Models\ItemModel;
use App\Models\UnidadesModel;




class Salida extends BaseController

{
    protected $salida;
    protected $tsalida;
    protected $proveedor;
    protected $usuario;
    protected $item;
    protected $unidadmedida;

    // protected $reglas;


    public function __construct()
    {
        $this->salida = new SalidaModel();
        $this->tsalida = new TsalidaModel();
        $this->proveedor = new ProveedorModel();
        $this->usuario = new UsuarioModel();
        $this->item = new ItemModel();
        $this->unidadmedida = new UnidadesModel();
    }

    public function nuevo()
    {
        $info1 = $this->tsalida->where('activo', 1)->findAll();
        $info2 = $this->proveedor->where('activo', 1)->findAll();
        $info3 = $this->usuario->where('activo', 1)->findAll();
        $info4 = $this->item->where('activo', 1)->findAll();
        $info5 = $this->salida->where('activo', 1)->findAll();
        $info6 = $this->unidadmedida->findAll();

        //suma de todos los total_precio e importes de salida
        // $sumaTotales = 0;
        $sumaImportes = 0;
        foreach ($info5 as $key) {
            // $sumaTotales = $sumaTotales + $key['total_precio'];
            $sumaImportes = $sumaImportes + $key['importe'];
        }

        // $importeTotalIva = $sumaTotales - $sumaImportes;

        // $sumaTotales = number_format($sumaTotales, 2, '.', '');
        $sumaImportes = number_format($sumaImportes, 3, '.', '');
        // $importeTotalIva = number_format($importeTotalIva, 3, '.', '');


        $data = [
            'titulo' => 'Salidas',
            'tipo_salidas' => $info1,
            'proveedores' => $info2,
            'usuarios' => $info3,
            'items' => $info4,
            'salidas' => $info5,
            // 'sumaTotales' => $sumaTotales,
            'sumaImportes' => $sumaImportes,
            // 'importeTotalIva' => $importeTotalIva,
            'unidadmedidas' => $info6
        ];

        echo view('header');
        echo view('salida/nuevo', $data);
        echo view('footer');
    }

    public function insertar()
    {

        helper(['form', 'url']);
        $salida = new SalidaModel();

        $id_item = $this->request->getVar('id_item');
        $cantidad = $this->request->getVar('cantidad');
        $nota_entrega = $this->request->getVar('nota_entrega');
        $fecha = $this->request->getVar('fecha');
        $id_tiposalida = $this->request->getVar('id_tiposalida');
        $destino = $this->request->getVar('destino');
        $concepto = $this->request->getVar('concepto');
        $id_usuario1 = $this->request->getVar('id_usuario');
        $id_usuario2 = $this->request->getVar('id_usuario_dos');



        //se obtiene datos del item

        $datosItem = $this->item->where('id_item', $id_item)->first();

        $cantidadItem = $datosItem['cantidad'] - $cantidad;
        $costo_unitarioItem = $datosItem['costo_unitario']; //sacamos el costo unitario del item
        $importe = $costo_unitarioItem * $cantidad;
        $importeItem = $datosItem['importe'] - $importe;

        $total_movimiento = $datosItem['total_movimiento'] + 1;



        $data = [
            'id_item' => $id_item,
            'cantidad' => $cantidad,
            'nota_entrega' => $nota_entrega,
            'fecha' => $fecha,
            'id_tiposalida' => $id_tiposalida,
            'destino' => $destino,
            'concepto' => $concepto,
            'id_usuario1' => $id_usuario1,
            'id_usuario2' => $id_usuario2,
            'importe' => $importe,
            'nro_movimiento' => $total_movimiento,
            'costo_unitario' => $costo_unitarioItem,
        ];



        $data2 = [
            'cantidad' => $cantidadItem,
            'importe' => $importeItem,
            'total_movimiento' => $total_movimiento,
        ];

        //se actualiza el item
        $this->item->update($id_item, $data2);

        //se inserta la salida
        $save = $salida->insert_data($data);

        //se obtiene la suma de todos los importes de salidas
        $info5 = $this->salida->where('activo', 1)->findAll();
        $sumaImportes = 0;
        foreach ($info5 as $key) {
            $sumaImportes = $sumaImportes + $key['importe'];
        }
        

        if ($save != false) {
            $data = $salida->where('id_salida', $save)->first();
            // $data['id_tiposalida'] = $this->tsalida->where('id_tiposalida', $data['id_tiposalida'])->first();
            $data['id_unidadmedida'] = $this->unidadmedida->where('id_unidadmedida', $data['id_item'])->first();
            $data['id_item'] = $this->item->where('id_item', $data['id_item'])->first();
            //se agrega al data la suma de todos los importes de salida
            $data['sumaImportes'] = number_format($sumaImportes, 3, '.', '');
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }
}
