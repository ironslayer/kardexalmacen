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




class EntradaSalida extends BaseController

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
        //cargamos todos los iems que tengan el total movimientos > 0 y que esten activos
        $info = $this->item->where('total_movimiento >', 0)->where('activo', 1)->findAll();

        $data = [
            'titulo' => 'Entradas y Salidas por Item',
            'items' => $info,
        ];

        echo view('header');
        echo view('entrada_salida/entrada_salida', $data);
        echo view('footer');
    }

    public function buscaEntradasSalidasItem($id_item)
    {
        // Cargar la base de datos para hacer consultas
        $db = \Config\Database::connect();

        // Crear la consulta
        $query = $db->query('SELECT nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe
        FROM entrada
        WHERE id_item = ' . $id_item . '
        UNION ALL
        SELECT nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe
        FROM salida
        WHERE id_item = ' . $id_item . '
        ORDER BY nro_movimiento ASC;');

        // Obtener los resultados
        $results = $query->getResultArray();

        // Validar que la consulta haya traído resultados
        if (count($results) != 0) {
            // Con los resultados y estilos de fondo, armamos una tabla
            $tabla = '<table class="table table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th width="2%">Nro. Movimiento</th>
                    <th>Fecha</th>
                    <th>Item</th>
                    <th>Entrada/Salida</th>
                    <th>Cantidad</th>
                    <th>Costo U.</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>';
            foreach ($results as $row) {
                $estiloFondo = '';
                if ($row['e_s'] === 'E') {
                    $estiloFondo = 'background-color: #CCFFCA;'; // Verde claro para entradas
                } elseif ($row['e_s'] === 'S') {
                    $estiloFondo = 'background-color: #F9DFD8;'; // Rojo claro para salidas
                }
                $tabla .= '<tr style="' . $estiloFondo . '">';
                $tabla .= '<td>' . $row['nro_movimiento'] . '</td>';
                $tabla .= '<td>' . $row['fecha'] . '</td>';
                $tabla .= '<td>' . $row['id_item'] . '</td>';
                $tabla .= '<td>' . $row['e_s'] . '</td>';
                $tabla .= '<td>' . $row['cantidad'] . '</td>';
                $tabla .= '<td>' . $row['costo_unitario'] . '</td>';
                $tabla .= '<td>' . $row['importe'] . '</td>';
                $tabla .= '</tr>';
            }

            $tabla .= '</tbody></table>';
            // Devolvemos la tabla
            $data['tabla'] = $tabla;
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }


    public function editar($id = null)
    {
        // buscamos datos de la entrada
        $salida = new SalidaModel();
        $data = $salida->where('id_salida', $id)->first();



        // //codigo para crear opciones de tipo de salida
        $tiposSalida = $this->tsalida->where('activo', 1)->findAll();
        $opcionesTipoSalida = '<label for="txt_id_tiposalida">Tipo de Salida</label>';
        $opcionesTipoSalida .= '<select class="form-select" id="txt_id_tiposalida" name="txt_id_tiposalida" required >';
        $opcionesTipoSalida .= '<option value="">Seleccionar Tipo de Salida</option>';
        foreach ($tiposSalida as $row) {
            if ($row['id_tiposalida'] == $data['id_tiposalida']) {
                $opcionesTipoSalida .= '<option value="' . $row['id_tiposalida'] . '" selected>' . $row['nombre_salida'] . '</option>';
            } else {
                $opcionesTipoSalida .= '<option value="' . $row['id_tiposalida'] . '">' . $row['nombre_salida'] . '</option>';
            }
        }
        $opcionesTipoSalida .= '</select>';
        $data['opcionesTipoSalida'] = $opcionesTipoSalida;

        // //codigo para crear opciones de item
        $items = $this->item->where('activo', 1)->findAll();
        $opcionesItem = '<label for="txt_id_item">Item</label>';
        $opcionesItem .= '<select class="form-select" id="txt_id_item" name="txt_id_item" disabled>';
        $opcionesItem .= '<option value="">Seleccionar Item</option>';
        foreach ($items as $row) {
            if ($row['id_item'] == $data['id_item']) {
                $opcionesItem .= '<option value="' . $row['id_item'] . '" selected>' . $row['descripcion'] . '</option>';
            } else {
                $opcionesItem .= '<option value="' . $row['id_item'] . '">' . $row['descripcion'] . '</option>';
            }
        }
        $opcionesItem .= '</select>';
        $data['opcionesItem'] = $opcionesItem;

        // //codigo para crear opciones de usuario1
        $usuarios = $this->usuario->where('activo', 1)->findAll();
        $opcionesUsuario1 = '<label for="txt_id_usuario">Autorizado por</label>';
        $opcionesUsuario1 .= '<select class="form-select" id="txt_id_usuario" name="txt_id_usuario" required >';
        $opcionesUsuario1 .= '<option value="">Seleccione</option>';
        foreach ($usuarios as $row) {
            if ($row['id_usuario'] == $data['id_usuario1']) {
                $opcionesUsuario1 .= '<option value="' . $row['id_usuario'] . '" selected>' . $row['nombre_usuario'] . '</option>';
            } else {
                $opcionesUsuario1 .= '<option value="' . $row['id_usuario'] . '">' . $row['nombre_usuario'] . '</option>';
            }
        }
        $opcionesUsuario1 .= '</select>';
        $data['opcionesUsuario1'] = $opcionesUsuario1;

        // //codigo para crear opciones de usuario2
        $usuarios = $this->usuario->where('activo', 1)->findAll();
        $opcionesUsuario2 = '<label for="txt_id_usuario_dos">Entregado a</label>';
        $opcionesUsuario2 .= '<select class="form-select" id="txt_id_usuario_dos" name="txt_id_usuario_dos" required >';
        $opcionesUsuario2 .= '<option value="">Seleccione</option>';
        foreach ($usuarios as $row) {
            if ($row['id_usuario'] == $data['id_usuario2']) {
                $opcionesUsuario2 .= '<option value="' . $row['id_usuario'] . '" selected>' . $row['nombre_usuario'] . '</option>';
            } else {
                $opcionesUsuario2 .= '<option value="' . $row['id_usuario'] . '">' . $row['nombre_usuario'] . '</option>';
            }
        }
        $opcionesUsuario2 .= '</select>';
        $data['opcionesUsuario2'] = $opcionesUsuario2;

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }

    public function actualizar()
    {
        helper(['form', 'url']);
        $salida = new SalidaModel();

        $id = $this->request->getVar('txt_id');

        $data = [
            'nota_entrega' => $this->request->getVar('txt_nota_entrega'),
            'fecha' => $this->request->getVar('txt_fecha'),
            'destino' => $this->request->getVar('txt_destino'),
            'concepto' => $this->request->getVar('txt_concepto'),
            'id_tiposalida' => $this->request->getVar('txt_id_tiposalida'),
            'id_usuario1' => $this->request->getVar('txt_id_usuario'),
            'id_usuario2' => $this->request->getVar('txt_id_usuario_dos'),
        ];

        $update = $salida->update($id, $data);
        if ($update != false) {
            $data = $salida->where('id_salida', $id)->first();

            $data['id_item'] = $this->item->where('id_item', $data['id_item'])->first();
            //buscamos el nombre_unidadmedida del item
            $data['id_unidadmedida'] = $this->unidadmedida->where('id_unidadmedida', $data['id_item']['id_unidadmedida'])->first();

            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false, 'data' => $data));
        }
    }
}
