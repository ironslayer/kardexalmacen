<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductoModel;

class Producto extends BaseController

{
    protected $producto;

    public function __construct()
    {
        $this->producto = new ProductoModel();
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
        $this->producto->save(['nombre_producto'=>$this->request->getPost('nombre')] );
        return redirect()->to(base_url().'producto');
    }

    public function editar($id_producto)
    {
        $informacion = $this->producto->where('id_producto', $id_producto)->first();
        $data = ['titulo' => 'Editar Producto','datos'=>$informacion];


        echo view('header');
        echo view('producto/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {
        $this->producto->update($this->request->getPost('id'),['nombre_producto'=>$this->request->getPost('nombre')] );
        return redirect()->to(base_url().'producto');
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
