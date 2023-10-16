<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EntradaModel;
use App\Models\TentradaModel;
use App\Models\ProveedorModel;

class Entrada extends BaseController

{
    protected $entrada;
    protected $tentrada;
    protected $proveedor;
    protected $reglas;


    public function __construct()
    {
        $this->entrada = new EntradaModel();
        $this->tentrada = new TentradaModel();
        $this->proveedor = new ProveedorModel();
        helper(['form']);

        // $this->reglas = [
        //     'descripcion' => [
        //         'rules' => 'required',
        //         'errors' => [
        //             'required' => 'El campo {field} es obligatorio.'
        //         ]
        //     ]
        // ];

    }

    public function index($activo=1)
    {
        $info1 = $this->entrada->where('activo', $activo)->findAll();
        $info2 = $this->tentrada->findAll();

        $data = ['titulo' => 'Entrada', 'datos' => $info1, 'datos2'=>$info2];

        echo view('header');
        echo view('entrada/entrada',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        // $info1 = $this->tentrada->where('activo', 1)->findAll();
        // $info2 = $this->proveedor->where('activo', 1)->findAll();
        // $data = ['titulo' => 'Agregar Items', 'tentrada'=>$info1, 'proveedors'=>$info2];

        echo view('header');
        echo view('entrada/nuevo');
        echo view('footer');
    }

    public function insertar()
    {
        if($this->request->is('post') && $this->validate($this->reglas)){
            $this->entrada->save([
                'descripcion'=>$this->request->getPost('descripcion'),'id_proveedor'=>$this->request->getPost('id_proveedor'),'id_unidadmedida'=>$this->request->getPost('id_unidadmedida')] );
            return redirect()->to(base_url().'entrada');
        }else{
            $info1 = $this->tentrada->where('activo', 1)->findAll();
            $info2 = $this->proveedor->where('activo', 1)->findAll();
            $data = ['titulo' => 'Agregar Items', 'tentrada'=>$info1, 'proveedors'=>$info2, 'validation'=>$this->validator];

            echo view('header');
            echo view('entrada/nuevo',$data);
            echo view('footer');
        }
        
    }

    public function editar($id_entrada, $valid=null)
    {

        $info1 = $this->tentrada->findAll();
        $info2 = $this->proveedor->findAll();
        $entradas = $this->entrada->where('id_entrada', $id_entrada)->first();
        
        if($valid != null){
            $data = ['titulo' => 'Editar entrada', 'tentrada'=>$info1, 'proveedors'=>$info2, 'entrada'=>$entradas, 'validation' =>$valid];
        }else{
            $data = ['titulo' => 'Editar entrada', 'tentrada'=>$info1, 'proveedors'=>$info2, 'entrada'=>$entradas];
        }

        echo view('header');
        echo view('entrada/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        // $this->entrada->update($this->request->getPost('id_entrada'),[
        //     'descripcion'=>$this->request->getPost('descripcion'),'id_proveedor'=>$this->request->getPost('id_proveedor'),'id_unidadmedida'=>$this->request->getPost('id_unidadmedida')] );

        // return redirect()->to(base_url().'entrada');

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->entrada->update($this->request->getPost('id_entrada'),[
                'descripcion'=>$this->request->getPost('descripcion'),'id_proveedor'=>$this->request->getPost('id_proveedor'),'id_unidadmedida'=>$this->request->getPost('id_unidadmedida')] );
            return redirect()->to(base_url() . 'entrada');
        }else{
            return $this->editar($this->request->getPost('id_entrada'), $this->validator);
        }
    }

    public function eliminar($id)
    {
        $this->entrada->update($id,['activo'=>0]);
        return redirect()->to(base_url().'entrada');
    }

    public function eliminados($activo=0)
    {

        $info1 = $this->entrada->where('activo', $activo)->findAll();
        $info2 = $this->tentrada->findAll();

        $data = ['titulo' => 'Items Eliminados', 'datos' => $info1, 'datos2'=>$info2];

        echo view('header');
        echo view('entrada/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->entrada->update($id,['activo'=>1]);
        return redirect()->to(base_url().'entrada');
    }
}
