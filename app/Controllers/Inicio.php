<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SalidaModel;
use App\Models\TsalidaModel;
use App\Models\EntradaModel;
use App\Models\TentradaModel;
use App\Models\ProveedorModel;
use App\Models\ProductoModel;
use App\Models\UsuarioModel;
use App\Models\ItemModel;
use App\Models\UnidadesModel;

class Inicio extends BaseController
{
    protected $salida;
    protected $tsalida;
    protected $tentrada;
    protected $entrada;
    protected $proveedor;
    protected $producto;
    protected $usuario;
    protected $item;
    protected $unidadmedida;

    // protected $reglas;


    public function __construct()
    {
        $this->salida = new SalidaModel();
        $this->tsalida = new TsalidaModel();
        $this->tentrada = new TentradaModel();
        $this->entrada = new EntradaModel();
        $this->proveedor = new ProveedorModel();
        $this->producto = new ProductoModel();
        $this->usuario = new UsuarioModel();
        $this->item = new ItemModel();
        $this->unidadmedida = new UnidadesModel();
    }


    public function index()
    {
        // $totalProductos = $this->producto->totalProductos();
        //tarjeta 1
        $totalEntradas = $this->entrada->totalEntradas();
        //tarjeta 2
        $totalSalidas = $this->salida->totalSalidas();
        //tarjeta 3
        $totalItems = $this->item->totalItems();

        //grafico 1 ---------------------------------------------------------------

        //obtenemos los días de la semana en un array, el nombre del ultimo día tiene que ser hoy, es decir la ultima posicion del array tiene que tener el nombre del día de hoy 
        $diasSemana = [];
        $diasSemana[] = date('l', strtotime('-6 days'));
        $diasSemana[] = date('l', strtotime('-5 days'));
        $diasSemana[] = date('l', strtotime('-4 days'));
        $diasSemana[] = date('l', strtotime('-3 days'));
        $diasSemana[] = date('l', strtotime('-2 days'));
        $diasSemana[] = date('l', strtotime('-1 days'));
        $diasSemana[] = date('l');

        //traducimos al español los días de la semana y guardamos en un nuevo array
        $diasSemanaEsp = [];
        foreach($diasSemana as $diaSemana){
            if($diaSemana == 'Monday'){
                $diaSemana = 'Lunes';
            }elseif($diaSemana == 'Tuesday'){
                $diaSemana = 'Martes';
            }elseif($diaSemana == 'Wednesday'){
                $diaSemana = 'Miércoles';
            }elseif($diaSemana == 'Thursday'){
                $diaSemana = 'Jueves';
            }elseif($diaSemana == 'Friday'){
                $diaSemana = 'Viernes';
            }elseif($diaSemana == 'Saturday'){
                $diaSemana = 'Sábado';
            }elseif($diaSemana == 'Sunday'){
                $diaSemana = 'Domingo';
            }
            $diasSemanaEsp[] = $diaSemana;
        }
        

        //convertimos el array en un string separado por comas
        $diasSemanaEsp = implode("','", $diasSemanaEsp);
        //colocamos corchetes para que sea un vector
        $diasSemanaEsp = "['" . $diasSemanaEsp . "']";

        //ahora obtenemos los datos de productos con activo = 1 y sacamos todos los nombre_producto
        $productos = $this->producto->where('activo', 1)->findAll();
        //llenamos en un vector solo con los nombres de los productos
        $nombre_producto = array_column($productos, 'nombre_producto');
        //a cada elemento del array le asignamos un vector vacio donde insertaremos cantidades de entradas + salidas concernientes a los items que pertenezcan a ese producto
        foreach($nombre_producto as $nombre){
            $data[$nombre] = [];
        }

        //ahora iteramos los productos y obtenemos los items con id_producto = $id_producto y que activo = 1
        foreach($productos as $producto){
            $id_producto = $producto['id_producto'];
            //buscamos los items con id_producto = $id_producto y que activo = 1 
            $items = $this->item->where('id_producto', $id_producto)->where('activo', 1)->findAll();
            //buscamos las entradas y salidas de cada item  que sean de hace 7 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 7 días
            $sum=0;
            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 7 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-6 days')))->findAll();
                //buscamos las salidas de hace 7 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-6 days')))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                
                $sum=$sum+$total;
            }
            $data[$producto['nombre_producto']][] = $sum;

            //buscamos las entradas y salidas de cada item  que sean de hace 6 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 6 días
            $sum=0;
            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 6 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-5 days')))->findAll();
                //buscamos las salidas de hace 6 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-5 days')))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                $sum=$sum+$total;
                
            }
            $data[$producto['nombre_producto']][] = $total;

            //buscamos las entradas y salidas de cada item  que sean de hace 5 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 5 días
            $sum=0;

            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 5 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-4 days')))->findAll();
                //buscamos las salidas de hace 5 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-4 days')))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                $sum=$sum+$total;
                
                
            }
            $data[$producto['nombre_producto']][] = $total;

            //buscamos las entradas y salidas de cada item  que sean de hace 4 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 4 días
            $sum=0;

            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 4 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-3 days')))->findAll();
                //buscamos las salidas de hace 4 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-3 days')))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                $sum=$sum+$total;

                
                
            }
            $data[$producto['nombre_producto']][] = $total;

            //buscamos las entradas y salidas de cada item  que sean de hace 3 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 3 días
            $sum=0;

            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 3 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-2 days')))->findAll();
                //buscamos las salidas de hace 3 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-2 days')))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                $sum=$sum+$total;

                
                
            }
            $data[$producto['nombre_producto']][] = $total;

            //buscamos las entradas y salidas de cada item  que sean de hace 2 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 2 días
            $sum=0;

            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 2 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-1 days')))->findAll();
                //buscamos las salidas de hace 2 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d', strtotime('-1 days')))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                $sum=$sum+$total;
                
                
            }
            $data[$producto['nombre_producto']][] = $total;

            //buscamos las entradas y salidas de cada item  que sean de hace 1 días, es decir que la columna fecha sea mayor o igual a la fecha de hace 1 días
            $sum=0;

            foreach($items as $item){
                $id_item = $item['id_item'];
                //buscamos las entradas de hace 1 días
                $entradas = $this->entrada->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d'))->findAll();
                //buscamos las salidas de hace 1 días
                $salidas = $this->salida->where('activo',1)->where('id_item', $id_item)->where('fecha >=', date('Y-m-d'))->findAll();
                //sumamos las entradas y salidas
                $total = count($entradas) + count($salidas);
                //en caso de que total sea cero guardamos con null
                $sum=$sum+$total;

                
                
            }
            $data[$producto['nombre_producto']][] = $total;

            

        }
        //de  $data[$producto['nombre_producto']][] invertimos el orden de los elementos de cada vector
        foreach($data as $key => $value){
            $data[$key] = array_reverse($value);
        }
        //de $data[$producto['nombre_producto']][] cambiamos los valores que son 0 a null
        foreach($data as $key => $value){
            foreach($value as $key2 => $value2){
                if($value2 == 0){
                    $data[$key][$key2] = 'null';
                }
            }
        }
        //creamos una cadena pero si $value es cero ponemos null
        $series1 = "";
        foreach($data as $key => $value){
            $series1 = $series1 . "{name: '" . $key . "', data: [" . $value[0] . "," . $value[1] . "," . $value[2] . "," . $value[3] . "," . $value[4] . "," . $value[5] . "," . $value[6] . "]},";
        }
        
        
        // print_r($series1);

        // print_r($data);


        //grafico 2 ---------------------------------------------------------------
        //obtenemos los datos de los producto con activo = 1 y  sacamos todos nombre_producto
        $productos = $this->producto->where('activo', 1)->findAll();
        //llenamos en un vector solo con los nombres de los productos
        $nombre_producto = array_column($productos, 'nombre_producto');
        //convierte el vector en un string separado por comas
        $nombre_producto = implode("','", $nombre_producto);
        //colocamos corchetes para que sea un vector
        $nombre_producto = "['" . $nombre_producto . "']";


        $arrayCantidadEntradas = [];
        $arrayCantidadSalidas = [];
        foreach($productos as $producto){
            $id_producto = $producto['id_producto'];
            //obtenemos los items con  id_producto = $id_producto y que activo = 1
            $items = $this->item->where('id_producto', $id_producto)->where('activo', 1)->findAll();

            $cantidadEntradas = 0;
            $cantidadSalidas = 0;
            foreach($items as $item){
                $id_item = $item['id_item'];
                //obtenemos las el numero de entradas con id_item = $id_item
                $numEntradas = $this->entrada->where('id_item', $id_item)->countAllResults();
                //obtenemos las el numero de salidas con id_item = $id_item
                $numSalidas = $this->salida->where('id_item', $id_item)->countAllResults();
                //sumamos las entradas
                $cantidadEntradas = $cantidadEntradas + $numEntradas;
                //sumamos las salidas
                $cantidadSalidas = $cantidadSalidas + $numSalidas;
                    

            }
            //llenamos el vector con las cantidades de entradas
            $arrayCantidadEntradas[] = $cantidadEntradas;
        
            //llenamos el vector con las cantidades de salidas
            $arrayCantidadSalidas[] = $cantidadSalidas;

            
        }

        //convertimos el vector en un string separado por comas
        $cantidadEntradas = implode(",", $arrayCantidadEntradas);
        $cantidadSalidas = implode(",", $arrayCantidadSalidas);
        //colocamos corchetes para que sea un vector
        $cantidadEntradas = "[" . $cantidadEntradas . "]";
        $cantidadSalidas = "[" . $cantidadSalidas . "]";

        
        //grafico 3 ---------------------------------------------------------------
        
        //obtenemos datos de item con activo = 1 y con total movimiento mayor a 0
        $items = $this->item->where('activo', 1)->where('total_movimiento >', 0)->findAll();
       

        $dataItems = [];

        foreach($items as $item){
            $id_item = $item['id_item'];
            //obtenemos los datos de entrada con id_item = $id_item
            $entradas = $this->entrada->where('id_item', $id_item)->countAllResults();
            //obtenemos los datos de salida con id_item = $id_item
            $salidas = $this->salida->where('id_item', $id_item)->countAllResults();

            //suma de entradas y salidas
            $total = $entradas + $salidas;
            //obtenemos la descripcion del item
            $descripcion = $item['descripcion'];

            //guardamos los datos en un vector
            $dataItems[] = [
                'descripcion' => $descripcion,
                'total' => $total
            ];
        }

        //sumamos los elementos del vector
        $totalSumaItems = 0;
        foreach($dataItems as $dataItem){
            $totalSumaItems = $totalSumaItems + $dataItem['total'];
        }

        //de dataItems convertimos en porcentajes en funcion de totalSumaItems
        $dataItemsPorcentajes = [];
        foreach($dataItems as $dataItem){
            $porcentaje = ($dataItem['total'] * 100) / $totalItems;
            //redoondeamos el porcentaje a 2 decimales
            $porcentaje = round($porcentaje, 2);
            //convertimos el porcentaje en string
            $porcentaje = (string)$porcentaje;
            

            $dataItemsPorcentajes[] = [
                'descripcion' => $dataItem['descripcion'],
                'porcentaje' => $porcentaje
            ];
        }

        //creamos una cadena con los $dataItemsPorcentajes

        $series = "";
        foreach($dataItemsPorcentajes as $dataItemPorcentaje){
            $series = $series . "{name: '" . $dataItemPorcentaje['descripcion'] . "', y: " . $dataItemPorcentaje['porcentaje'] . "},";
        }

        // var_dump($dataItemsPorcentajes);  
        // print_r($dataItemsPorcentajes);   
        // --------------------------------------------
        $data = [
            //data
            'totalEntradas' => $totalEntradas,
            'totalSalidas' => $totalSalidas,
            'totalItems' => $totalItems,
            //data1
            'diasSemanaEsp' => $diasSemanaEsp,
            'series1' => $series1,
            //data2
            'nombre_prod' => $nombre_producto,
            'cantidadEntradas' => $cantidadEntradas,
            'cantidadSalidas' => $cantidadSalidas,
            //data3
            'dataItemsPorcentajes' => $series,


        ];

       
        echo view('header');
        echo view('graficos', $data);
        echo view('footer');

    }
}
