<?php 

namespace App\Models;

use CodeIgniter\Model;

class TsalidaModel extends Model
{
    protected $table      = 'tipo_salida';
    protected $primaryKey = 'id_tiposalida';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_salida', 'activo'];

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
        $builder = $db->table('tipo_salida');
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