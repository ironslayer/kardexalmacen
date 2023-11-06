<?php 

namespace App\Models;

use CodeIgniter\Model;

class ReporteExcelModel extends Model
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

    
}

?>