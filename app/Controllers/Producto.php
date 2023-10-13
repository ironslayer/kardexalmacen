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
        $info = $this->producto->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Productos-Categorias', 'datos' => $info];

        echo view('header');
        echo view('producto/producto',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $data = ['titulo' => 'Agregar Producto'];

        echo view('header');
        echo view('producto/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->producto->save(['nombre_producto' => $this->request->getPost('nombre')]);
            return redirect()->to(base_url() . 'producto');
        } else {
            $data = ['titulo' => 'Agregar Producto', 'validation' => $this->validator];

            echo view('header');
            echo view('producto/nuevo', $data);
            echo view('footer');
        }
    }

    public function editar($id_producto, $valid=null)
    {
        $informacion = $this->producto->where('id_producto', $id_producto)->first();
        if($valid != null){
            $data = ['titulo' => 'Editar Producto', 'datos' => $informacion, 'validation' =>$valid];
        }else{
            $data = ['titulo' => 'Editar Producto', 'datos' => $informacion];
        }

        echo view('header');
        echo view('producto/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $this->producto->update($this->request->getPost('id'), ['nombre_producto' => $this->request->getPost('nombre')]);
            return redirect()->to(base_url() . 'producto');
        }else{
            return $this->editar($this->request->getPost('id'), $this->validator);
        }
    }

    public function eliminar($id)
    {
        $this->producto->update($id,['activo'=>0]);
        return redirect()->to(base_url().'producto');
    }

    public function eliminados($activo=0)
    {
        $info = $this->producto->where('activo', $activo)->findAll();
        $data = ['titulo' => 'Productos Elmininados', 'datos' => $info];

        echo view('header');
        echo view('producto/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->producto->update($id,['activo'=>1]);
        return redirect()->to(base_url().'producto');
    }
}
