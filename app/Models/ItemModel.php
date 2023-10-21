<?php 

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table      = 'item';
    protected $primaryKey = 'id_item';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['descripcion', 'id_producto', 'id_unidadmedida', 'cantidad', 'costo_unitario', 'importe', 'activo'];

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
        $builder = $db->table('item');
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