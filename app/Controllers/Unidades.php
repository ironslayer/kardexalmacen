<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnidadesModel;

class Unidades extends BaseController

{
    protected $unidades;
    protected $reglas;

    public function __construct()
    {
        $this->unidades = new UnidadesModel();


    }

    public function index($activo = 1)
    {
        $info = $this->unidades->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Unidad de Medida', 'datos' => $info];

        echo view('header');
        echo view('unidades/unidades', $data);
        echo view('footer');
    }


    public function insertar()
    {

        helper(['form','url']);
        $unidades = new UnidadesModel();
        $data = [
            'nombre_unidad' => $this->request->getVar('nombre'),
        ];
  
        $save = $unidades->insert_data($data);

        if($save != false){
            $data = $unidades->where('id_unidadmedida', $save)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }

    public function editar($id_unidadmedida=null)
    {
        $unidades = new UnidadesModel();
        $data = $unidades->where('id_unidadmedida', $id_unidadmedida)->first();

        if($data){
            echo json_encode(array("status" => true, 'data' => $data));
        }else{  
            echo json_encode(array("status" => false));
        }

    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $unidades = new UnidadesModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nombre_unidad' => $this->request->getVar('txt_nombre'),
        ];

        $update = $unidades->update($id, $data);
        if($update != false){
            $data = $unidades->where('id_unidadmedida', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        }else{
            echo json_encode(array("status" => false, 'data' => $data));
        }

    }

    public function eliminar($id=null)
    {
        $unidades = new UnidadesModel();
        // $delete = $unidades->where('id_unidadmedida', $id)->delete($id);
        // $this->producto->update($id,['activo'=>0]);
        $delete = $unidades->where('id_unidadmedida', $id)->first();
        $delete = $unidades->update($id, ['activo' => 0]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }

    public function eliminados($activo = 0)
    {
        $info = $this->unidades->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Unidades Eliminadas', 'datos' => $info];

        echo view('header');
        echo view('unidades/eliminados', $data);
        echo view('footer');
    }

    public function reingresar($id=null)
    {
        $unidades = new UnidadesModel();
        $delete = $unidades->where('id_unidadmedida', $id)->first();
        $delete = $unidades->update($id, ['activo' => 1]);
        if($delete){
            echo json_encode(array("status" => true));
        }else{
            echo json_encode(array("status" => false));
        }
    }


}
