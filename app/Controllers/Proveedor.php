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
        helper(['form']);

        $this->reglas = [
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'contacto' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'direccion' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'ciudad' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ],
            'telefono' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es obligatorio.'
                ]
            ]
        ];
    }

    public function index($activo=1)
    {
        $info = $this->proveedor->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Proveedores', 'datos' => $info];

        echo view('header');
        echo view('proveedor/proveedor',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $data = ['titulo' => 'Agregar Proveedor'];

        echo view('header');
        echo view('proveedor/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->proveedor->save([
                'nombre_proveedor' => $this->request->getPost('nombre'),
                'contacto' => $this->request->getPost('contacto'),
                'direccion' => $this->request->getPost('direccion'),
                'ciudad' => $this->request->getPost('ciudad'),
                'telefono' => $this->request->getPost('telefono')
            ]);
            return redirect()->to(base_url() . 'proveedor');
        } else {
            $data = ['titulo' => 'Agregar Proveedor', 'validation' => $this->validator];

            echo view('header');
            echo view('proveedor/nuevo', $data);
            echo view('footer');
        }
    }

    public function editar($id_proveedor, $valid=null)
    {
        $informacion = $this->proveedor->where('id_proveedor', $id_proveedor)->first();
        if($valid != null){
            $data = ['titulo' => 'Editar Proveedor', 'datos' => $informacion, 'validation' =>$valid];
        }else{
            $data = ['titulo' => 'Editar Proveedor', 'datos' => $informacion];
        }

        echo view('header');
        echo view('proveedor/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->proveedor->update($this->request->getPost('id'), [
                'nombre_proveedor' => $this->request->getPost('nombre'),
                'contacto' => $this->request->getPost('contacto'),
                'direccion' => $this->request->getPost('direccion'),
                'ciudad' => $this->request->getPost('ciudad'),
                'telefono' => $this->request->getPost('telefono')
           
            ]);
            return redirect()->to(base_url() . 'proveedor');
        }else{
            return $this->editar($this->request->getPost('id'), $this->validator);
        }
    }

    public function eliminar($id)
    {
        $this->proveedor->update($id,['activo'=>0]);
        return redirect()->to(base_url().'proveedor');
    }

    public function eliminados($activo=0)
    {
        $info = $this->proveedor->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Proveedores Eliminados', 'datos' => $info];

        echo view('header');
        echo view('proveedor/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->proveedor->update($id,['activo'=>1]);
        return redirect()->to(base_url().'proveedor');
    }
}
