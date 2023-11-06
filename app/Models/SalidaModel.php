<?php 

namespace App\Models;

use CodeIgniter\Model;

class SalidaModel extends Model
{
    protected $table      = 'salida';
    protected $primaryKey = 'id_salida';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['destino', 'nota_entrega', 'fecha', 'concepto', 'cantidad', 'costo_unitario', 'importe', 'id_tiposalida', 'id_usuario1', 'id_usuario2', 'activo', 'id_item', 'nro_movimiento', 'e_s'];

    // Dates
    protected $useTimestamps = true;
    // protected $dateFormat    = 'timestamp';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edit';
    protected $deletedField  = 'deleted_at';

    public function __construct()
    {
        parent::__construct();
        // $this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('salida');
    }

    public function insert_data($data){
        if($this->db->table($this->table)->insert($data)){
            return $this->db->insertID();
        }else{
            return false;
        }
    }

    
}

?>