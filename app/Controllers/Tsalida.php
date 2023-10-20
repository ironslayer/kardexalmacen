<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TsalidaModel;

class Tsalida extends BaseController

{
    protected $tsalida;
    protected $reglas;

    public function __construct()
    {
        $this->tsalida = new TsalidaModel();
        
    }

    public function index($activo=1)
    {
        $info = $this->tsalida->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipos de salida', 'datos' => $info];

        echo view('header');
        echo view('tsalida/tsalida',$data);
        echo view('footer');
    }

    public function insertar()
    {

        helper(['form','url']);
        $tsalida = new TsalidaModel();
        $data = [
            'nombre_salida' => $this->request->getVar('nombre'),
        ];
  
        $save = $tsalida->insert_data($data);

        if($save != false){
            $data = $tsalida->where('id_tiposalida', $save)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }

    public function editar($id=null)
    {
        $tsalida = new TsalidaModel();
        $data = $tsalida->where('id_tiposalida', $id)->first();

        if($data){
            echo json_encode(array("status" => true, 'data' => $data));
        }else{  
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $tsalida = new TsalidaModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nombre_salida' => $this->request->getVar('txt_nombre'),
        ];

        $update = $tsalida->update($id, $data);
        if($update != false){
            $data = $tsalida->where('id_tiposalida', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }

    }

    public function eliminar($id=null)
    {
        $tsalida = new TsalidaModel();
        // $delete = $tsalida->where('id_salidamedida', $id)->delete($id);
        // $this->producto->update($id,['activo'=>0]);
        $delete = $tsalida->where('id_tiposalida', $id)->first();
        $delete = $tsalida->update($id, ['activo' => 0]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function eliminados($activo = 0)
    {
        $info = $this->tsalida->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipos de Salida Eliminados', 'datos' => $info];

        echo view('header');
        echo view('tsalida/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id=null)
    {
        $tsalida = new TsalidaModel();
        $delete = $tsalida->where('id_tiposalida', $id)->first();
        $delete = $tsalida->update($id, ['activo' => 1]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }
}
