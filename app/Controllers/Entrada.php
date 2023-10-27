<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EntradaModel;
use App\Models\TentradaModel;
use App\Models\ProveedorModel;
use App\Models\UsuarioModel;
use App\Models\ItemModel;
use App\Models\UnidadesModel;




class Entrada extends BaseController

{
    protected $entrada;
    protected $tentrada;
    protected $proveedor;
    protected $usuario;
    protected $item;
    protected $unidadmedida;

    // protected $reglas;


    public function __construct()
    {
        $this->entrada = new EntradaModel();
        $this->tentrada = new TentradaModel();
        $this->proveedor = new ProveedorModel();
        $this->usuario = new UsuarioModel();
        $this->item = new ItemModel();
        $this->unidadmedida = new UnidadesModel();

    }

    public function nuevo()
    {
        $info1 = $this->tentrada->where('activo', 1)->findAll();
        $info2 = $this->proveedor->where('activo', 1)->findAll();
        $info3 = $this->usuario->where('activo', 1)->findAll();
        $info4 = $this->item->where('activo', 1)->findAll();
        $info5 = $this->entrada->where('activo', 1)->findAll();
        $info6 = $this->unidadmedida->findAll();

        //suma de todos los total_precio e importes de entrada
        $sumaTotales = 0;
        $sumaImportes = 0;
        foreach ($info5 as $key) {
            $sumaTotales = $sumaTotales + $key['total_precio'];
            $sumaImportes = $sumaImportes + $key['importe'];
        }

        $importeTotalIva = $sumaTotales - $sumaImportes;

        $sumaTotales = number_format($sumaTotales, 2, '.', '');
        $sumaImportes = number_format($sumaImportes, 3, '.', '');
        $importeTotalIva = number_format($importeTotalIva, 3, '.', '');


        $data = ['titulo' => 'Entradas', 'tipo_entradas'=>$info1, 'proveedores'=>$info2, 'usuarios'=>$info3, 'items'=>$info4, 'entradas'=>$info5, 'sumaTotales'=>$sumaTotales, 'sumaImportes'=>$sumaImportes, 'importeTotalIva'=>$importeTotalIva, 'unidadmedidas'=>$info6];

        echo view('header');
        echo view('entrada/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {
        helper(['form','url']);
        $entrada = new EntradaModel();
 
        $nota_recepcion = $this->request->getVar('nota_recepcion');
        $c_iva = $this->request->getVar('c_iva');
        $fecha = $this->request->getVar('fecha');
        $fuente = $this->request->getVar('fuente');
        $id_tipoentrada = $this->request->getVar('id_tipoentrada');
        $id_proveedor = $this->request->getVar('id_proveedor');
        $id_item = $this->request->getVar('id_item');
        $cantidad = $this->request->getVar('cantidad');
        $total_precio = $this->request->getVar('total_precio');
        $concepto = $this->request->getVar('concepto');
        $id_usuario1 = $this->request->getVar('id_usuario');
        $id_usuario2 = $this->request->getVar('id_usuario_dos');

        $precio_unitario = $total_precio / $cantidad;

        //validamos el c_iva

        if($c_iva == ''){
            $c_iva = 0;
        }else{
            $c_iva = 1;
        }
        
        // calculo para el costo unitario
        $costo_unitario = $precio_unitario;
        if($c_iva == 1){
            $costo_unitario = $costo_unitario - $precio_unitario * 0.13;
        }

        $importe = $costo_unitario * $cantidad;

        //se obtiene datos del item
        $datosItem = $this->item->where('id_item', $id_item)->first();
        $total_movimiento = $datosItem['total_movimiento'] + 1;
        

        $data = [
            'c_iva' => $c_iva,
            'nota_recepcion' => $nota_recepcion,
            'fecha' => $fecha,
            'fuente' => $fuente,
            'concepto' => $concepto,
            'cantidad' => $cantidad,
            'total_precio' => $total_precio,
            'precio_unitario' => $precio_unitario,
            'costo_unitario' => $costo_unitario,
            'importe' => $importe,
            'id_tipoentrada' => $id_tipoentrada,
            'id_proveedor' => $id_proveedor,
            'id_usuario1' => $id_usuario1,
            'id_usuario2' => $id_usuario2,
            'id_item' => $id_item,
            'nro_movimiento' => $total_movimiento,
        ];

        $datosItem = $this->item->where('id_item', $id_item)->first();

        $cantidadItem = $datosItem['cantidad'] + $cantidad;
        $importeItem = $datosItem['importe'] + $importe;
        $costo_unitarioItem = $importeItem / $cantidadItem;

        $data2 = [
            'cantidad' => $cantidadItem,
            'costo_unitario' => $costo_unitarioItem,
            'importe' => $importeItem,
            'total_movimiento' => $total_movimiento,      
        ];

        //se actualiza el item
        $this->item->update($id_item, $data2);

        //se inserta la entrada
        $save = $entrada->insert_data($data);

        //se obtiene la suma de todos los total_precio e importes de entrada
        $info5 = $this->entrada->where('activo', 1)->findAll();
        $sumaTotales = 0;
        $sumaImportes = 0;
        foreach ($info5 as $key) {
            $sumaTotales = $sumaTotales + $key['total_precio'];
            $sumaImportes = $sumaImportes + $key['importe'];
        }
        $importeTotalIva = $sumaTotales - $sumaImportes;

        if($save != false){
            $data = $entrada->where('id_entrada', $save)->first();
            // $data['id_tipoentrada'] = $this->tentrada->where('id_tipoentrada', $data['id_tipoentrada'])->first();
            $data['id_unidadmedida'] = $this->unidadmedida->where('id_unidadmedida', $data['id_item'])->first();
            $data['id_item'] = $this->item->where('id_item', $data['id_item'])->first();
            //se agrega al data la suma de todos los total_precio e importes de entrada y los importes con iva
            $data['sumaTotales'] = number_format($sumaTotales, 2, '.', '');
            $data['sumaImportes'] = number_format($sumaImportes, 3, '.', '');
            $data['importeTotalIva'] = number_format($importeTotalIva, 3, '.', '');
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
            
        
    }

    public function editar($id=null)
    {
        // buscamos datos de la entrada
        $entrada = new EntradaModel();
        $data = $entrada->where('id_entrada', $id)->first();

        //creamos el codigo para el c_iva si esta activado o no el checkbox
        $valor_iva=$data['c_iva'];
        $opcion_iva = '';
        if($valor_iva == 1){
            $opcion_iva = '<label for="txt_c_iva">C/IVA</label>';
            $opcion_iva .= '<input type="checkbox" class="" id="txt_c_iva" name="txt_c_iva" style="margin-top: 9px" disabled checked />';
        }else{
            $opcion_iva = '<label for="txt_c_iva">C/IVA</label>';
            $opcion_iva .= '<input type="checkbox" class="" id="txt_c_iva" name="txt_c_iva" style="margin-top: 9px" disabled />';
        }
        $data['opcion_iva'] = $opcion_iva;

        // //codigo para crear opciones de tipo de entrada
        $tipo_entradas = $this->tentrada->where('activo', 1)->findAll();
        $opcionesTipoEntrada = '<label for="txt_id_tipoentrada">Tipo de Entrada</label>';
        $opcionesTipoEntrada .= '<select class="form-select" id="txt_id_tipoentrada" name="txt_id_tipoentrada" required >';
        $opcionesTipoEntrada .= '<option value="">Seleccionar Tipo de Entrada</option>';
        foreach ($tipo_entradas as $row) {
            if ($row['id_tipoentrada'] == $data['id_tipoentrada']) {
                $opcionesTipoEntrada .= '<option value="' . $row['id_tipoentrada'] . '" selected>' . $row['nombre_entrada'] . '</option>';
            } else {
                $opcionesTipoEntrada .= '<option value="' . $row['id_tipoentrada'] . '">' . $row['nombre_entrada'] . '</option>';
            }
        }
        $opcionesTipoEntrada .= '</select>';
        $data['opcionesTipoEntrada'] = $opcionesTipoEntrada;

        // //codigo para crear opciones de proveedor
        $proveedores = $this->proveedor->where('activo', 1)->findAll();
        $opcionesProveedor = '<label for="txt_id_proveedor">Proveedor</label>';
        $opcionesProveedor .= '<select class="form-select" id="txt_id_proveedor" name="txt_id_proveedor" required >';
        $opcionesProveedor .= '<option value="">Seleccionar Proveedor</option>';
        foreach ($proveedores as $row) {
            if ($row['id_proveedor'] == $data['id_proveedor']) {
                $opcionesProveedor .= '<option value="' . $row['id_proveedor'] . '" selected>' . $row['nombre_proveedor'] . '</option>';
            } else {
                $opcionesProveedor .= '<option value="' . $row['id_proveedor'] . '">' . $row['nombre_proveedor'] . '</option>';
            }
        }
        $opcionesProveedor .= '</select>';
        $data['opcionesProveedor'] = $opcionesProveedor;

        // //codigo para crear opciones de item
        $items = $this->item->where('activo', 1)->findAll();
        $opcionesItem = '<label for="txt_id_item">Item</label>';
        $opcionesItem .= '<select class="form-select" id="txt_id_item" name="txt_id_item" disabled>';
        $opcionesItem .= '<option value="">Seleccionar Item</option>';
        foreach ($items as $row) {
            if ($row['id_item'] == $data['id_item']) {
                $opcionesItem .= '<option value="' . $row['id_item'] . '" selected>' . $row['descripcion'] . '</option>';
            } else {
                $opcionesItem .= '<option value="' . $row['id_item'] . '">' . $row['descripcion'] . '</option>';
            }
        }
        $opcionesItem .= '</select>';
        $data['opcionesItem'] = $opcionesItem;

        // //codigo para crear opciones de usuario1
        $usuarios = $this->usuario->where('activo', 1)->findAll();
        $opcionesUsuario1 = '<label for="txt_id_usuario">Autorizado por</label>';
        $opcionesUsuario1 .= '<select class="form-select" id="txt_id_usuario" name="txt_id_usuario" required >';
        $opcionesUsuario1 .= '<option value="">Seleccione</option>';
        foreach ($usuarios as $row) {
            if ($row['id_usuario'] == $data['id_usuario1']) {
                $opcionesUsuario1 .= '<option value="' . $row['id_usuario'] . '" selected>' . $row['nombre_usuario'] . '</option>';
            } else {
                $opcionesUsuario1 .= '<option value="' . $row['id_usuario'] . '">' . $row['nombre_usuario'] . '</option>';
            }
        }
        $opcionesUsuario1 .= '</select>';
        $data['opcionesUsuario1'] = $opcionesUsuario1;

        // //codigo para crear opciones de usuario2
        $usuarios = $this->usuario->where('activo', 1)->findAll();
        $opcionesUsuario2 = '<label for="txt_id_usuario_dos">Entregado a</label>';
        $opcionesUsuario2 .= '<select class="form-select" id="txt_id_usuario_dos" name="txt_id_usuario_dos" required >';
        $opcionesUsuario2 .= '<option value="">Seleccione</option>';
        foreach ($usuarios as $row) {
            if ($row['id_usuario'] == $data['id_usuario2']) {
                $opcionesUsuario2 .= '<option value="' . $row['id_usuario'] . '" selected>' . $row['nombre_usuario'] . '</option>';
            } else {
                $opcionesUsuario2 .= '<option value="' . $row['id_usuario'] . '">' . $row['nombre_usuario'] . '</option>';
            }
        }
        $opcionesUsuario2 .= '</select>';
        $data['opcionesUsuario2'] = $opcionesUsuario2;

        if($data){
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $entrada = new EntradaModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nota_recepcion' => $this->request->getVar('txt_nota_recepcion'),
            'fecha' => $this->request->getVar('txt_fecha'),
            'fuente' => $this->request->getVar('txt_fuente'),
            'concepto' => $this->request->getVar('txt_concepto'),
            'id_tipoentrada' => $this->request->getVar('txt_id_tipoentrada'),
            'id_proveedor' => $this->request->getVar('txt_id_proveedor'),
            'id_usuario1' => $this->request->getVar('txt_id_usuario'),
            'id_usuario2' => $this->request->getVar('txt_id_usuario_dos'),
        ];

        $update = $entrada->update($id, $data);
        if ($update != false) {
            $data = $entrada->where('id_entrada', $id)->first();
            
            $data['id_unidadmedida'] = $this->unidadmedida->where('id_unidadmedida', $data['id_item'])->first();
            
            $data['id_item'] = $this->item->where('id_item', $data['id_item'])->first();
            
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false, 'data' => $data));
        }
        
    }


}
