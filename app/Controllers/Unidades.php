<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnidadesModel;

class Unidades extends BaseController

{
    protected $unidades;

    public function __construct()
    {
        $this->unidades = new UnidadesModel();
    }

    public function index($activo=1)
    {
        $info = $this->unidades->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Unidad de Medida', 'datos' => $info];

        echo view('header');
        echo view('unidades/unidades',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $data = ['titulo' => 'Agregar Unidad de Medida'];

        echo view('header');
        echo view('unidades/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {
        if($this->request->is('post') && $this->validate(['nombre'=>'required'])){
            $this->unidades->save(['nombre_unidad'=>$this->request->getPost('nombre')] );
            return redirect()->to(base_url().'unidades');
        }else{
            $data = ['titulo' => 'Agregar Unidad de Medida', 'validation'=>$this->validator];

            echo view('header');
            echo view('unidades/nuevo',$data);
            echo view('footer');
        }
        
    }

    public function editar($id_unidadmedida)
    {
        $informacion = $this->unidades->where('id_unidadmedida', $id_unidadmedida)->first();
        $data = ['titulo' => 'Editar Unidad','datos'=>$informacion];


        echo view('header');
        echo view('unidades/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {
        $this->unidades->update($this->request->getPost('id'),['nombre_unidad'=>$this->request->getPost('nombre')] );
        return redirect()->to(base_url().'unidades');
    }

    public function eliminar($id)
    {
        $this->unidades->update($id,['activo'=>0]);
        return redirect()->to(base_url().'unidades');
    }

    public function eliminados($activo=0)
    {
        $info = $this->unidades->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Unidades Elmininadas', 'datos' => $info];

        echo view('header');
        echo view('unidades/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->unidades->update($id,['activo'=>1]);
        return redirect()->to(base_url().'unidades');
    }
}
