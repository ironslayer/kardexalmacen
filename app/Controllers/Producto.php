<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductoModel;

class Producto extends BaseController

{
    protected $producto;
    protected $reglas;

    public function __construct()
    {
        $this->producto = new ProductoModel();
    }

    public function index($activo=1)
    {
        $info = $this->producto->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Productos-Categorias', 'datos' => $info];

        echo view('header');
        echo view('producto/producto',$data);
        echo view('footer');
    }

    public function insertar()
    {

        helper(['form','url']);
        $producto = new ProductoModel();
        $data = [
            'nombre_producto' => $this->request->getVar('nombre'),
        ];
  
        $save = $producto->insert_data($data);

        if($save != false){
            $data = $producto->where('id_producto', $save)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }

    public function editar($id=null)
    {
        $producto = new ProductoModel();
        $data = $producto->where('id_producto', $id)->first();

        if($data){
            echo json_encode(array("status" => true, 'data' => $data));
        }else{  
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $producto = new ProductoModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nombre_producto' => $this->request->getVar('txt_nombre'),
        ];

        $update = $producto->update($id, $data);
        if($update != false){
            $data = $producto->where('id_producto', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }

    }

    public function eliminar($id=null)
    {
        $producto = new ProductoModel();
        // $delete = $producto->where('id_productomedida', $id)->delete($id);
        // $this->producto->update($id,['activo'=>0]);
        $delete = $producto->where('id_producto', $id)->first();
        $delete = $producto->update($id, ['activo' => 0]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function eliminados($activo = 0)
    {
        $info = $this->producto->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Productos o Categorias Eliminadas', 'datos' => $info];

        echo view('header');
        echo view('producto/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id=null)
    {
        $producto = new ProductoModel();
        $delete = $producto->where('id_producto', $id)->first();
        $delete = $producto->update($id, ['activo' => 1]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }
}
