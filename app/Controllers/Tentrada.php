<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TentradaModel;

class Tentrada extends BaseController

{
    protected $tentrada;
    protected $reglas;

    public function __construct()
    {
        $this->tentrada = new TentradaModel();
        
    }

    public function index($activo=1)
    {
        $info = $this->tentrada->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipo de Entradas', 'datos' => $info];

        echo view('header');
        echo view('tentrada/tentrada',$data);
        echo view('footer');
    }

    public function insertar()
    {

        helper(['form','url']);
        $tentrada = new TentradaModel();
        $data = [
            'nombre_entrada' => $this->request->getVar('nombre'),
        ];
  
        $save = $tentrada->insert_data($data);

        if($save != false){
            $data = $tentrada->where('id_tipoentrada', $save)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }

    public function editar($id=null)
    {
        $tentrada = new TentradaModel();
        $data = $tentrada->where('id_tipoentrada', $id)->first();

        if($data){
            echo json_encode(array("status" => true, 'data' => $data));
        }else{  
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $tentrada = new TentradaModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nombre_entrada' => $this->request->getVar('txt_nombre'),
        ];

        $update = $tentrada->update($id, $data);
        if($update != false){
            $data = $tentrada->where('id_tipoentrada', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }

    }

    public function eliminar($id=null)
    {
        $tentrada = new TentradaModel();
        // $delete = $tentrada->where('id_entradamedida', $id)->delete($id);
        // $this->producto->update($id,['activo'=>0]);
        $delete = $tentrada->where('id_tipoentrada', $id)->first();
        $delete = $tentrada->update($id, ['activo' => 0]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function eliminados($activo = 0)
    {
        $info = $this->tentrada->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipos de Entrada Eliminadoss', 'datos' => $info];

        echo view('header');
        echo view('tentrada/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id=null)
    {
        $tentrada = new TentradaModel();
        $delete = $tentrada->where('id_tipoentrada', $id)->first();
        $delete = $tentrada->update($id, ['activo' => 1]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }
}
