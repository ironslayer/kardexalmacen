<?php 

namespace App\Models;

use CodeIgniter\Model;

class EntradaModel extends Model
{
    protected $table      = 'entrada';
    protected $primaryKey = 'id_entrada';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['c_iva', 'nota_recepcion', 'fecha', 'fuente', 'concepto', 'cantidad', 'total_precio', 'precio_unitario', 'costo_unitario', 'importe', 'id_tipoentrada', 'id_proveedor', 'id_usuario1', 'id_usuario2', 'activo', 'id_item', 'nro_movimiento', 'e_s'];

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
        $builder = $db->table('entrada');
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