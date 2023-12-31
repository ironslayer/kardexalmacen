<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\ProductoModel;
use App\Models\UnidadesModel;

class Item extends BaseController

{
    protected $item;
    protected $unidades;
    protected $producto;
 


    public function __construct()
    {
        $this->item = new ItemModel();
        $this->unidades = new UnidadesModel();
        $this->producto = new ProductoModel();

    }

    public function index($activo=1)
    {
        $info1 = $this->item->where('activo', $activo)->findAll();
        $info2 = $this->unidades->findAll();
        $info3 = $this->producto->findAll();

        $data = ['titulo' => 'Items', 'datos' => $info1, 'datos2'=>$info2, 'datos3'=>$info3];

        echo view('header');
        echo view('item/item',$data);
        echo view('footer');
    }

    public function insertar()
    {

        helper(['form','url']);
        $item = new ItemModel();
        $data = [
            'descripcion' => $this->request->getVar('descripcion'),
            'id_producto' => $this->request->getVar('id_producto'),
            'id_unidadmedida' => $this->request->getVar('id_unidadmedida'),
        ];
  
        $save = $item->insert_data($data);


        if($save != false){
            $data = $item->where('id_item', $save)->first();
            $data['id_unidadmedida'] = $this->unidades->where('id_unidadmedida', $data['id_unidadmedida'])->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }

    public function editar($id=null)
    {
        $item = new ItemModel();
        $data = $item->where('id_item', $id)->first();

        //codigo para crear opciones de unidades de medida
        $unidades = $this->unidades->where('activo', 1)->findAll();
        $productos = $this->producto->where('activo', 1)->findAll();

        $opcionesUnidades = '<label for="txt_id_unidadmedida">Unidad de Medida</label>';
        $opcionesUnidades .= '<select class="form-control" id="txt_id_unidadmedida" name="txt_id_unidadmedida" required>';
        $opcionesUnidades .= '<option value="">Seleccionar Unidad de Medida</option>';
        foreach ($unidades as $row) {
            if ($row['id_unidadmedida'] == $data['id_unidadmedida']) {
                $opcionesUnidades .= '<option value="' . $row['id_unidadmedida'] . '" selected>' . $row['nombre_unidad'] . '</option>';
            } else {
                $opcionesUnidades .= '<option value="' . $row['id_unidadmedida'] . '">' . $row['nombre_unidad'] . '</option>';
            }
        }
        $opcionesUnidades .= '</select>';
        $data['opcionesUnidades'] = $opcionesUnidades;

        //codigo para crear opciones de productos

        $opcionesProductos = '<label for="txt_id_producto">Producto</label>';
        $opcionesProductos .= '<select class="form-control" id="txt_id_producto" name="txt_id_producto" required>';
        $opcionesProductos .= '<option value="">Seleccionar Producto</option>';
        foreach ($productos as $row) {
            if ($row['id_producto'] == $data['id_producto']) {
                $opcionesProductos .= '<option value="' . $row['id_producto'] . '" selected>' . $row['nombre_producto'] . '</option>';
            } else {
                $opcionesProductos .= '<option value="' . $row['id_producto'] . '">' . $row['nombre_producto'] . '</option>';
            }
        }
        $opcionesProductos .= '</select>';
        $data['opcionesProductos'] = $opcionesProductos;

        if($data){
            
            echo json_encode(array("status" => true, 'data' => $data));
        }else{  
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $item = new ItemModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'descripcion' => $this->request->getVar('txt_descripcion'),
            'id_producto' => $this->request->getVar('txt_id_producto'),
            'id_unidadmedida' => $this->request->getVar('txt_id_unidadmedida'),
        ];

        $update = $item->update($id, $data);
        if($update != false){
            $data = $item->where('id_item', $id)->first();
            $data['id_unidadmedida'] = $this->unidades->where('id_unidadmedida', $data['id_unidadmedida'])->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }

    }

    public function eliminar($id=null)
    {
        $item = new ItemModel();
        // $delete = $producto->where('id_productomedida', $id)->delete($id);
        // $this->producto->update($id,['activo'=>0]);
        $delete = $item->where('id_item', $id)->first();
        $delete = $item->update($id, ['activo' => 0]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function eliminados($activo = 0)
    {
 
        $info = $this->item->where('activo', $activo)->findAll();
        $info2 = $this->unidades->findAll();
        $data = ['titulo' => 'Items Eliminados', 'datos' => $info, 'datos2' => $info2];

        echo view('header');
        echo view('item/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id=null)
    {
        $item = new ItemModel();
        $delete = $item->where('id_item', $id)->first();
        $delete = $item->update($id, ['activo' => 1]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function buscaCantidadItem($id){

        $this->item->select('*');
        $this->item->where('id_item',$id);
        $this->item->where('activo',1);
        $datos = $this->item->get()->getRow();

        $res['datos']=$datos;

        echo json_encode($res);

    }

    


    

}
