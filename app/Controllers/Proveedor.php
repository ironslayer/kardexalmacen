<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProveedorModel;

class Proveedor extends BaseController

{
    protected $proveedor;
    protected $reglas;

    public function __construct()
    {
        $this->proveedor = new ProveedorModel();
    }

    public function index($activo=1)
    {
        $info = $this->proveedor->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Proveedores', 'datos' => $info];

        echo view('header');
        echo view('proveedor/proveedor',$data);
        echo view('footer');
    }

    public function insertar()
    {

        helper(['form','url']);
        $proveedor = new ProveedorModel();
        $data = [
            'nombre_proveedor' => $this->request->getVar('nombre'),
            'contacto' => $this->request->getVar('contacto'),
            'direccion' => $this->request->getVar('direccion'),
            'ciudad' => $this->request->getVar('ciudad'),
            'telefono' => $this->request->getVar('telefono'),
        ];
  
        $save = $proveedor->insert_data($data);

        if($save != false){
            $data = $proveedor->where('id_proveedor', $save)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }

    public function editar($id_proveedor=null)
    {
        $proveedor = new ProveedorModel();
        $data = $proveedor->where('id_proveedor', $id_proveedor)->first();

        if($data){
            echo json_encode(array("status" => true, 'data' => $data));
        }else{  
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $proveedor = new ProveedorModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nombre_proveedor' => $this->request->getVar('txt_nombre'),
            'contacto' => $this->request->getVar('txt_contacto'),
            'direccion' => $this->request->getVar('txt_direccion'),
            'ciudad' => $this->request->getVar('txt_ciudad'),
            'telefono' => $this->request->getVar('txt_telefono'),
        ];

        $update = $proveedor->update($id, $data);
        if($update != false){
            $data = $proveedor->where('id_proveedor', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }

    }

    public function eliminar($id=null)
    {
        $proveedor = new ProveedorModel();
        // $delete = $proveedor->where('id_proveedormedida', $id)->delete($id);
        // $this->producto->update($id,['activo'=>0]);
        $delete = $proveedor->where('id_proveedor', $id)->first();
        $delete = $proveedor->update($id, ['activo' => 0]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function eliminados($activo = 0)
    {
        $info = $this->proveedor->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Proveedores Eliminados', 'datos' => $info];

        echo view('header');
        echo view('proveedor/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id=null)
    {
        $proveedor = new ProveedorModel();
        $delete = $proveedor->where('id_proveedor', $id)->first();
        $delete = $proveedor->update($id, ['activo' => 1]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }
}
