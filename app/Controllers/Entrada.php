<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EntradaModel;
use App\Models\TentradaModel;
use App\Models\ProveedorModel;
use App\Models\UsuarioModel;
use App\Models\ItemModel;




class Entrada extends BaseController

{
    protected $entrada;
    protected $tentrada;
    protected $proveedor;
    protected $usuario;
    protected $item_entrada;
    protected $item;

    protected $reglas;


    public function __construct()
    {
        $this->entrada = new EntradaModel();
        $this->tentrada = new TentradaModel();
        $this->proveedor = new ProveedorModel();
        $this->usuario = new UsuarioModel();
        $this->item = new ItemModel();

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

    public function nuevo()
    {
        $info1 = $this->tentrada->where('activo', 1)->findAll();
        $info2 = $this->proveedor->where('activo', 1)->findAll();
        $info3 = $this->usuario->where('activo', 1)->findAll();
        $info4 = $this->item->where('activo', 1)->findAll();


        $data = ['titulo' => 'Entradas', 'tipo_entradas'=>$info1, 'proveedores'=>$info2, 'usuarios'=>$info3, 'items'=>$info4];
        echo view('header');
        echo view('entrada/nuevo',$data);
        echo view('footer');
    }

    public function insertar()
    {

    }



}
