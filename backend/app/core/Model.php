<?php

namespace app\core;
use app\helpers\model\QueryHelper;

abstract class Model{
    protected $db;
    protected $table = "";

    public function __construct() {
        $this->db = new \PDO("mysql:dbname=".BANCO.";host=".SERVIDOR,USUARIO,SENHA);
    }

    public function find($id){
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $dados['dados'] = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $dados;
    }

    public function all($offset, $limit ){
        $sqlTotal = $this->db->query("SELECT count(id) as total from $this->table");
        $total = $sqlTotal->fetch(\PDO::FETCH_ASSOC);
        $totalPages = $total['total'] / $limit;
        $paginas = QueryHelper::queryStringURL($total['total'], $offset, $limit);

        $sql = "SELECT * FROM $this->table LIMIT $limit OFFSET $offset";
        $query = $this->db->query($sql);
        $dados['dados'] = $query->fetchAll(\PDO::FETCH_ASSOC);
        $dados = array_merge($dados, $paginas);
        return $dados;
    }

    public function insert($data){

        $pdoParams = QueryHelper::insert($this->table, $data);
        $stmt = $this->db->prepare($pdoParams['sql']);
        if( $stmt->execute($pdoParams['values']) )
            return $this->db->lastInsertId();
        else
            return false;
    }

    public function update($data, $where){
        $pdoParams = QueryHelper::update($this->table, $data, $where);
        $stmt = $this->db->prepare($pdoParams['sql']);
        $stmt->execute($pdoParams['values']);
        if ($stmt->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function delete($id){
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        if($stmt->rowCount() > 0 )
            return true;
        else
            return false;
    }

}

