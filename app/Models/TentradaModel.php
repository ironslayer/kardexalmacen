<?php 

namespace App\Models;

use CodeIgniter\Model;

class TentradaModel extends Model
{
    protected $table      = 'tipo_entrada';
    protected $primaryKey = 'id_tipoentrada';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_entrada', 'activo'];

    // Dates
    protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edit';
    protected $deletedField  = 'deleted_at';

    public function __construct()
    {
        parent::__construct();
        // $this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('tipo_entrada');
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