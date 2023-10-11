<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\ProductoModel;
use App\Models\UnidadesModel;

class Item extends BaseController

{
    protected $item;
    protected $unidades;
    protected $producto;


    public function __construct()
    {
        $this->item = new ItemModel();
        $this->unidades = new UnidadesModel();
        $this->producto = new ProductoModel();

    }

    public function index($activo=1)
    {
        $info1 = $this->item->where('activo', $activo)->findAll();
        $info2 = $this->unidades->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Items', 'datos' => $info1, 'datos2'=>$info2];

        echo view('header');
        echo view('item/item',$data);
        echo view('footer');
    }

    public function nuevo()
    {
        $info1 = $this->unidades->where('activo', 1)->findAll();
        $info2 = $this->producto->where('activo', 1)->findAll();
        $data = ['titulo' => 'Agregar Items', 'unidades'=>$info1, 'productos'=>$info2];

        echo view('header');
        echo view('item/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {
        if($this->request->is('post')){
            $this->item->save([
                'descripcion'=>$this->request->getPost('descripcion'),'id_producto'=>$this->request->getPost('id_producto'),'id_unidadmedida'=>$this->request->getPost('id_unidadmedida')] );
            return redirect()->to(base_url().'item');
        }else{
            $data = ['titulo' => 'Agregar Items', 'validation'=>$this->validator];

            echo view('header');
            echo view('item/nuevo',$data);
            echo view('footer');
        }
        
    }

    public function editar($id_item)
    {
        $info1 = $this->unidades->where('activo', 1)->findAll();
        $info2 = $this->producto->where('activo', 1)->findAll();
        $items = $this->item->where('id_item', $id_item)->first();
        $data = ['titulo' => 'Editar item', 'unidades'=>$info1, 'productos'=>$info2, 'item'=>$items];


        echo view('header');
        echo view('item/editar',$data);
        echo view('footer');
    }

    public function actualizar()
    {

        $this->item->update($this->request->getPost('id_item'),[
            'descripcion'=>$this->request->getPost('descripcion'),'id_producto'=>$this->request->getPost('id_producto'),'id_unidadmedida'=>$this->request->getPost('id_unidadmedida')] );

        return redirect()->to(base_url().'item');
    }

    public function eliminar($id)
    {
        $this->item->update($id,['activo'=>0]);
        return redirect()->to(base_url().'item');
    }

    public function eliminados($activo=0)
    {

        $info1 = $this->item->where('activo', $activo)->findAll();
        $info2 = $this->unidades->findAll();

        $data = ['titulo' => 'Items eliminados', 'datos' => $info1, 'datos2'=>$info2];

        echo view('header');
        echo view('item/eliminados',$data);
        echo view('footer');
    }

    public function reingresar($id)
    {
        $this->item->update($id,['activo'=>1]);
        return redirect()->to(base_url().'item');
    }
}
