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

    protected $allowedFields = ['c_iva', 'nota_recepcion', 'fecha', 'fuente', 'concepto', 'cantidad', 'total_precio', 'precio_unitario', 'costo_unitario', 'importe', 'id_tipoentrada', 'id_proveedor', 'id_usuario1', 'id_usuario2', 'activo'];

    // Dates
    // protected $useTimestamps = true;
    // protected $dateFormat    = 'timestamp';
    // protected $createdField  = 'fecha_alta';
    // protected $updatedField  = 'fecha_edit';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];
}

?>