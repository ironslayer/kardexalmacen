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
        helper(['form']);

        $this->reglas = [
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ]
        ];
    }

    public function index($activo=1)
    {
        $info = $this->tsalida->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipos de salida', 'datos' => $info];

        echo view('header');
        echo view('tsalida/tsalida',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $data = ['titulo' => 'Agregar Tipo de salida'];

        echo view('header');
        echo view('tsalida/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->tsalida->save([
                'nombre_salida' => $this->request->getPost('nombre')
            ]);
            return redirect()->to(base_url() . 'tsalida');
        } else {
            $data = ['titulo' => 'Agregar Tipo de Salida', 'validation' => $this->validator];

            echo view('header');
            echo view('tsalida/nuevo', $data);
            echo view('footer');
        }
    }

    public function editar($id_tsalida, $valid=null)
    {
        $informacion = $this->tsalida->where('id_tiposalida', $id_tsalida)->first();
        if($valid != null){
            $data = ['titulo' => 'Editar Tipo de Salida', 'datos' => $informacion, 'validation' =>$valid];
        }else{
            $data = ['titulo' => 'Editar Tipo de Salida', 'datos' => $informacion];
        }

        echo view('header');
        echo view('tsalida/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->tsalida->update($this->request->getPost('id'), [
                'nombre_salida' => $this->request->getPost('nombre')
           
            ]);
            return redirect()->to(base_url() . 'tsalida');
        }else{
            return $this->editar($this->request->getPost('id'), $this->validator);
        }
    }

    public function eliminar($id)
    {
        $this->tsalida->update($id,['activo'=>0]);
        return redirect()->to(base_url().'tsalida');
    }

    public function eliminados($activo=0)
    {
        $info = $this->tsalida->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipos de Salidas Eliminadas', 'datos' => $info];

        echo view('header');
        echo view('tsalida/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->tsalida->update($id,['activo'=>1]);
        return redirect()->to(base_url().'tsalida');
    }
}
