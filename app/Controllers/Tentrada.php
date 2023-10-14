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
        $info = $this->tentrada->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipo de Entradas', 'datos' => $info];

        echo view('header');
        echo view('tentrada/tentrada',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $data = ['titulo' => 'Agregar Tipo de Entrada'];

        echo view('header');
        echo view('tentrada/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->tentrada->save([
                'nombre_entrada' => $this->request->getPost('nombre')
            ]);
            return redirect()->to(base_url() . 'tentrada');
        } else {
            $data = ['titulo' => 'Agregar Tipo de Entrada', 'validation' => $this->validator];

            echo view('header');
            echo view('tentrada/nuevo', $data);
            echo view('footer');
        }
    }

    public function editar($id_tentrada, $valid=null)
    {
        $informacion = $this->tentrada->where('id_tipoentrada', $id_tentrada)->first();
        if($valid != null){
            $data = ['titulo' => 'Editar Tipo de Entrada', 'datos' => $informacion, 'validation' =>$valid];
        }else{
            $data = ['titulo' => 'Editar Tipo de Entrada', 'datos' => $informacion];
        }

        echo view('header');
        echo view('tentrada/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->tentrada->update($this->request->getPost('id'), [
                'nombre_entrada' => $this->request->getPost('nombre')
           
            ]);
            return redirect()->to(base_url() . 'tentrada');
        }else{
            return $this->editar($this->request->getPost('id'), $this->validator);
        }
    }

    public function eliminar($id)
    {
        $this->tentrada->update($id,['activo'=>0]);
        return redirect()->to(base_url().'tentrada');
    }

    public function eliminados($activo=0)
    {
        $info = $this->tentrada->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Tipos de Entradas Eliminadas', 'datos' => $info];

        echo view('header');
        echo view('tentrada/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->tentrada->update($id,['activo'=>1]);
        return redirect()->to(base_url().'tentrada');
    }
}
