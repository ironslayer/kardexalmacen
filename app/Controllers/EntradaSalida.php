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
                <tr class="table-secondary">    
                    <th width="2%">Nro. Movimiento</th>
                    <th>Fecha</th>
                    <th>Item</th>
                    <th>E/S</th>
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

            //creamos botones para editar y eliminar

            $data['botones'] = '<a data-id="' . $id_item . '" class="btn btn-warning btnEdit"><i class="fas fa-wrench"></i> Editar último movimiento</a>
            <a data-id="' . $id_item . '" class="btn btn-danger btnDelete"><i class="fas fa-trash-alt"></i> Eliminar último movimiento</a>
            </div>';

            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }


    public function editar($id = null)
    {
        //obtenemos el ultimo movimiento del item
        $datosItem = $this->item->where('id_item', $id)->first();
        $nroUltimoMovimiento = $datosItem['total_movimiento'];

        //buscamos el ultimo movimiento del item
        $ultimoMovimiento = $this->entrada->where('id_item', $id)->where('nro_movimiento', $nroUltimoMovimiento)->first();
        //pregunta si el ultimo movimiento es una entrada o una salida
        if ($ultimoMovimiento) {
            $data = $this->entrada->where('id_item', $id)->where('nro_movimiento', $nroUltimoMovimiento)->first();

            //obtenemos la penultima cantidad del item
            $ultimaCantidad = $datosItem['cantidad'];
            $penultimaCantidad = $ultimaCantidad - $data['cantidad'];
            $data['penultimaCantidad'] = $penultimaCantidad;
           

            //creamos el codigo para el c_iva si esta activado o no el checkbox
            $valor_iva = $data['c_iva'];
            $opcion_iva = '';
            if ($valor_iva == 1) {
                $opcion_iva = '<label for="txt_c_iva_uno">C/IVA</label>';
                $opcion_iva .= '<input type="checkbox" class="" id="txt_c_iva_uno" name="txt_c_iva_uno" style="margin-top: 9px" checked />';
            } else {
                $opcion_iva = '<label for="txt_c_iva_uno">C/IVA</label>';
                $opcion_iva .= '<input type="checkbox" class="" id="txt_c_iva_uno" name="txt_c_iva_uno" style="margin-top: 9px" />';
            }
            $data['opcion_iva'] = $opcion_iva;

            // //codigo para crear opciones de tipo de entrada
            $tipo_entradas = $this->tentrada->where('activo', 1)->findAll();
            $opcionesTipoEntrada = '<label for="txt_id_tipoentrada_uno">Tipo de Entrada</label>';
            $opcionesTipoEntrada .= '<select class="form-select" id="txt_id_tipoentrada_uno" name="txt_id_tipoentrada_uno" required >';
            $opcionesTipoEntrada .= '<option value="">Seleccionar Tipo de Entrada</option>';
            foreach ($tipo_entradas as $row) {
                if ($row['id_tipoentrada'] == $data['id_tipoentrada']) {
                    $opcionesTipoEntrada .= '<option value="' . $row['id_tipoentrada'] . '" selected>' . $row['nombre_entrada'] . '</option>';
                } else {
                    $opcionesTipoEntrada .= '<option value="' . $row['id_tipoentrada'] . '">' . $row['nombre_entrada'] . '</option>';
                }
            }
            $opcionesTipoEntrada .= '</select>';
            $data['opcionesTipoEntrada'] = $opcionesTipoEntrada;

            // //codigo para crear opciones de proveedor
            $proveedores = $this->proveedor->where('activo', 1)->findAll();
            $opcionesProveedor = '<label for="txt_id_proveedor_uno">Proveedor</label>';
            $opcionesProveedor .= '<select class="form-select" id="txt_id_proveedor_uno" name="txt_id_proveedor_uno" required >';
            $opcionesProveedor .= '<option value="">Seleccionar Proveedor</option>';
            foreach ($proveedores as $row) {
                if ($row['id_proveedor'] == $data['id_proveedor']) {
                    $opcionesProveedor .= '<option value="' . $row['id_proveedor'] . '" selected>' . $row['nombre_proveedor'] . '</option>';
                } else {
                    $opcionesProveedor .= '<option value="' . $row['id_proveedor'] . '">' . $row['nombre_proveedor'] . '</option>';
                }
            }
            $opcionesProveedor .= '</select>';
            $data['opcionesProveedor'] = $opcionesProveedor;

            // //codigo para crear opciones de item
            $items = $this->item->where('activo', 1)->findAll();
            $opcionesItem = '<label for="txt_id_item_uno">Item</label>';
            $opcionesItem .= '<select class="form-select" id="txt_id_item_uno" name="txt_id_item_uno" disabled >';
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
            $opcionesUsuario1 = '<label for="txt_id_usuario_uno">Autorizado por</label>';
            $opcionesUsuario1 .= '<select class="form-select" id="txt_id_usuario_uno" name="txt_id_usuario_uno" required >';
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
            $opcionesUsuario2 = '<label for="txt_id_usuario_dos_uno">Entregado a</label>';
            $opcionesUsuario2 .= '<select class="form-select" id="txt_id_usuario_dos_uno" name="txt_id_usuario_dos_uno" required >';
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

            echo json_encode(array("status" => true, 'data' => $data));
        } else {//----------------------------------------------------------------------
            $data = $this->salida->where('id_item', $id)->where('nro_movimiento', $nroUltimoMovimiento)->first();

            //obtenemos la penultima cantidad del item
            $ultimaCantidad = $datosItem['cantidad'];
            $penultimaCantidad = $ultimaCantidad + $data['cantidad'];
            $data['penultimaCantidad'] = $penultimaCantidad;
            
            // //codigo para crear opciones de tipo de salida
            $tiposSalida = $this->tsalida->where('activo', 1)->findAll();
            $opcionesTipoSalida = '<label for="txt_id_tiposalida_dos">Tipo de Salida</label>';
            $opcionesTipoSalida .= '<select class="form-select" id="txt_id_tiposalida_dos" name="txt_id_tiposalida_dos" required >';
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
            $opcionesItem = '<label for="txt_id_item_dos">Item</label>';
            $opcionesItem .= '<select class="form-select" id="txt_id_item_dos" name="txt_id_item_dos" disabled >';
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
            $opcionesUsuario1 = '<label for="txt_id_usuario_dos">Autorizado por</label>';
            $opcionesUsuario1 .= '<select class="form-select" id="txt_id_usuario_dos" name="txt_id_usuario_dos" required >';
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
            $opcionesUsuario2 = '<label for="txt_id_usuario_dos_dos">Entregado a</label>';
            $opcionesUsuario2 .= '<select class="form-select" id="txt_id_usuario_dos_dos" name="txt_id_usuario_dos_dos" required >';
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

            echo json_encode(array("status" => false, 'data' => $data));
        }
       
    }

    public function actualizar_entrada()
    {
        helper(['form', 'url']);
        $entrada = new EntradaModel();
        //obtenemos el id de la entrada
        $id = $this->request->getVar('txt_id_uno');
        //obtenemos los datos del formulario
        $nota_recepcion = $this->request->getVar('txt_nota_recepcion_uno');
        $c_iva = $this->request->getVar('txt_c_iva_uno');
        $fecha = $this->request->getVar('txt_fecha_uno');
        $fuente = $this->request->getVar('txt_fuente_uno');
        $id_tipoentrada = $this->request->getVar('txt_id_tipoentrada_uno');
        $id_proveedor = $this->request->getVar('txt_id_proveedor_uno');
        // $id_item = $this->request->getVar('txt_id_item_uno');
        $cantidad = $this->request->getVar('txt_cantidad_uno');
        $total_precio = $this->request->getVar('txt_total_precio_uno');
        $concepto = $this->request->getVar('txt_concepto_uno');
        $id_usuario1 = $this->request->getVar('txt_id_usuario_uno');
        $id_usuario2 = $this->request->getVar('txt_id_usuario_dos_uno');

        //obtenemos el id_item de la entrada
        $id_item = $this->entrada->where('id_entrada', $id)->first();
        $id_item = $id_item['id_item'];
        //obtenemos el ultimo movimiento del item
        $datosItem = $this->item->where('id_item', $id_item)->first();
        // $nroUltimoMovimiento = $datosItem['total_movimiento'];
        //obtenemos datos de la ultima entrada por partes
        $dataUltimaEntrada = $this->entrada->where('id_item', $id_item)->findAll();
        $dataUltimaEntrada = end($dataUltimaEntrada);
        //obtenemos la penultima cantidad del item
        $ultimaCantidad = $datosItem['cantidad'];
        $penultimaCantidad = $ultimaCantidad - $dataUltimaEntrada['cantidad'];
        //obtenemos el penultimo importe del item
        $ultimoImporte = $datosItem['importe'];
        $penultimoImporte = $ultimoImporte - $dataUltimaEntrada['importe'];
        //obtenemos el penultimo costo unitario del item
        if($penultimaCantidad == 0){
            $penultimoCostoUnitario = 0;
        }else{
            $penultimoCostoUnitario = $penultimoImporte / $penultimaCantidad;
        }
        //creamos objeto item para actualizar en la tabla item
        $dataItem = [
            'cantidad' => $penultimaCantidad,
            'importe' => $penultimoImporte,
            'costo_unitario' => $penultimoCostoUnitario,
        ];
        //actualizamos el item
        $this->item->update($id_item, $dataItem);
        //calculamos precio unitario para actualizar en la tabla entrada
        $precioUnitario = $total_precio / $cantidad;
        //validamos el c_iva
        if ($c_iva == '') {
            $c_iva = 0;
        } else {
            $c_iva = 1;
        }
        //calculamos el costo unitario
        $costo_unitario = $precioUnitario;
        if($c_iva == 1){
            $costo_unitario = $costo_unitario - $precioUnitario * 0.13;
        }
        //calculamos el importe
        $importe = $costo_unitario * $cantidad;
        //creamos la data para actualizar la entrada
        $dataEntrada = [
            'nota_recepcion' => $nota_recepcion,
            'c_iva' => $c_iva,
            'fecha' => $fecha,
            'fuente' => $fuente,
            'id_tipoentrada' => $id_tipoentrada,
            'id_proveedor' => $id_proveedor,
            'id_item' => $id_item,
            'cantidad' => $cantidad,
            'total_precio' => $total_precio,
            'precio_unitario' => $precioUnitario,
            'costo_unitario' => $costo_unitario,
            'importe' => $importe,
            'concepto' => $concepto,
            'id_usuario1' => $id_usuario1,
            'id_usuario2' => $id_usuario2,
        ];
        //actualizamos la entrada
        $this->entrada->update($id, $dataEntrada);
        //calculamos los nuevos datos del item
        $nuevaCantidad = $penultimaCantidad + $cantidad;
        $nuevoImporte = $penultimoImporte + $importe;
        $nuevoCostoUnitario = $nuevoImporte / $nuevaCantidad;
        //creamos objeto item para actualizar en la tabla item
        $dataItem = [
            'cantidad' => $nuevaCantidad,
            'importe' => $nuevoImporte,
            'costo_unitario' => $nuevoCostoUnitario,
        ];
        //actualizamos el item y validamos si se actualizo
        if ($this->item->update($id_item, $dataItem)) {
            //obtenemos los datos de la entrada actualizada
            $data = $this->entrada->where('id_entrada', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
        
        
    }

    public function actualizar_salida()
    {
        helper(['form', 'url']);
        $salida = new SalidaModel();
        //obtenemos el id de la salida
        $id = $this->request->getVar('txt_id_dos');

        //obtenemos los datos del formulario
        // $id_item = $this->request->getVar('txt_id_item_dos');
        $cantidad = $this->request->getVar('txt_cantidad_dos');
        $nota_entrega = $this->request->getVar('txt_nota_entrega_dos');
        $fecha = $this->request->getVar('txt_fecha_dos');
        $id_tiposalida = $this->request->getVar('txt_id_tiposalida_dos');
        $destino = $this->request->getVar('txt_destino_dos');
        $concepto = $this->request->getVar('txt_concepto_dos');
        $id_usuario1 = $this->request->getVar('txt_id_usuario_dos');
        $id_usuario2 = $this->request->getVar('txt_id_usuario_dos_dos');

        //obtenemos el id_item de la salida
        $id_item = $this->salida->where('id_salida', $id)->first();
        $id_item = $id_item['id_item'];
        //obtenemos el ultimo movimiento del item
        $datosItem = $this->item->where('id_item', $id_item)->first();
        //obtenemos datos de la ultima salida por partes
        $dataUltimaSalida = $this->salida->where('id_item', $id_item)->findAll();
        $dataUltimaSalida = end($dataUltimaSalida);
        //obtenemos la penultima cantidad del item
        $ultimaCantidad = $datosItem['cantidad'];
        $penultimaCantidad = $ultimaCantidad + $dataUltimaSalida['cantidad'];
        //obtenemos el penultimo importe del item
        $ultimoImporte = $datosItem['importe'];
        $penultimoImporte = $ultimoImporte + $dataUltimaSalida['importe'];
        //obtenemos el penultimo costo unitario del item
        $penultimoCostoUnitario = $penultimoImporte / $penultimaCantidad;
        //creamos objeto item para actualizar en la tabla item
        $dataItem = [
            'cantidad' => $penultimaCantidad,
            'importe' => $penultimoImporte,
            'costo_unitario' => $penultimoCostoUnitario,
        ];
        //actualizamos el item
        $this->item->update($id_item, $dataItem);
        //calculamos el costo unitario
        $costo_unitario = $penultimoCostoUnitario;
        //calculamos el importe
        $importe = $costo_unitario * $cantidad;
        //creamos la data para actualizar la salida
        $dataSalida = [
            'id_item' => $id_item,
            'cantidad' => $cantidad,
            'nota_entrega' => $nota_entrega,
            'fecha' => $fecha,
            'id_tiposalida' => $id_tiposalida,
            'destino' => $destino,
            'concepto' => $concepto,
            'costo_unitario' => $costo_unitario,
            'importe' => $importe,
            'id_usuario1' => $id_usuario1,
            'id_usuario2' => $id_usuario2,
        ];
        //actualizamos la salida
        $this->salida->update($id, $dataSalida);
        //calculamos los nuevos datos del item
        $nuevaCantidad = $penultimaCantidad - $cantidad;
        $nuevoImporte = $penultimoImporte - $importe;
        $nuevoCostoUnitario = $nuevoImporte / $nuevaCantidad;
        //creamos objeto item para actualizar en la tabla item
        $dataItem = [
            'cantidad' => $nuevaCantidad,
            'importe' => $nuevoImporte,
            'costo_unitario' => $nuevoCostoUnitario,
        ];
        //actualizamos el item y validamos si se actualizo
        if ($this->item->update($id_item, $dataItem)) {
            //obtenemos los datos de la salida actualizada
            $data = $this->salida->where('id_salida', $id)->first();
            echo json_encode(array("status" => true, 'data' => $data));
            // return $this->response->setJSON(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }

    
}
