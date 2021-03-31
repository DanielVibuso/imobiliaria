<?php

namespace app\models;
use app\core\Model;

class Cliente extends Model{
    public function __construct() {
        parent::__construct();
        $this->table = "clientes";
    }

    public function existe($data){
        $sqlParams['email'] = $data['email'];
        $sqlParams['telefone'] = $data['telefone'];
        $sql = "SELECT * FROM $this->table WHERE email = :email or telefone = :telefone";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($sqlParams);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(count($result) > 0)
            return true;

        return false;
    }
}
