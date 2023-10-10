<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table      = 'producto';
    protected $primaryKey = 'id_producto';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_producto', 'activo'];

    // Dates
    protected $useTimestamps = true;
    // protected $dateFormat    = 'timestamp';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edit';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

?>