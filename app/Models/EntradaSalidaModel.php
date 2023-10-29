<?php 

namespace App\Models;

use CodeIgniter\Model;

class EntradaSalidaModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->database();
        $db = \Config\Database::connect();
    }

    public function insert_data($data){
        if($this->db->table($this->table)->insert($data)){
            return $this->db->insertID();
        }else{
            return false;
        }
    }

    // public function obtenerDatos($id_item) {
    //     $this->db->select('nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe');
    //     $this->db->from('entrada');
    //     $this->db->where('id_item', $id_item);
    //     $this->db->union_all();
    //     $this->db->select('nro_movimiento, fecha, id_item, e_s, cantidad, costo_unitario, importe');
    //     $this->db->from('salida');
    //     $this->db->where('id_item', $id_item);
    //     $this->db->order_by('nro_movimiento', 'ASC');
    //     $query = $this->db->get();
    //     return $query->result(); // Retorna los resultados como un arreglo de objetos
    // }
    
}

?>