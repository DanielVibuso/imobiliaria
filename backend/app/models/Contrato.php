<?php

namespace app\models;
use app\core\Model;
use app\models\Mensalidade;
use app\models\Repasse;

class Contrato extends Model{
    public function __construct() {
        parent::__construct();
        $this->table = "contratos";
    }

    public function find($id){
        $sql = "SELECT  imoveis.id,
                        imoveis.endereco as imovel,
                        proprietarios.id,
                        proprietarios.email as proprietario,
                        clientes.id,
                        clientes.email as cliente,
                        contratos.id,
                        contratos.data_inicio as inicio,
                        contratos.data_fim as fim,
                        contratos.taxa_admin,
                        contratos.valor_aluguel,
                        contratos.valor_condominio,
                        contratos.valor_iptu FROM contratos
                       INNER JOIN imoveis ON imoveis.id = contratos.imovel_id
                       INNER JOIN proprietarios ON proprietarios.id = contratos.proprietario_id
                       INNER JOIN clientes ON clientes.id = contratos.cliente_id
                       WHERE contratos.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function all($offset, $limit){
        $sql = "SELECT imoveis.id,
                       imoveis.endereco as imovel,
                       proprietarios.id,
                       proprietarios.email as proprietario,
                       clientes.id,
                       clientes.email as cliente,
                       contratos.id,
                       DATE_FORMAT(contratos.data_inicio, '%d/%m/%Y') as inicio,
                       DATE_FORMAT(contratos.data_fim, '%d/%m/%Y') as fim,
                       contratos.taxa_admin,
                       contratos.valor_aluguel,
                       contratos.valor_condominio,
                       contratos.valor_iptu FROM contratos
                       INNER JOIN imoveis ON imoveis.id = contratos.imovel_id
                       INNER JOIN proprietarios ON proprietarios.id = contratos.proprietario_id
                       INNER JOIN clientes ON clientes.id = contratos.cliente_id";
        $query = $this->db->query($sql);
        return array("dados" => $query->fetchAll(\PDO::FETCH_ASSOC));
    } 

    public function insert($data){
        $dia_repasse = $data["dia_repasse"];
        unset($data["dia_repasse"]);

        $mensalidade = new Mensalidade;
        $repasse = new Repasse;
        $contratoId = parent::insert($data);
        if($contratoId > 0){
            $data['contrato_id'] = $contratoId;
            $dadosMensalidades = $mensalidade->initialInsert($data);
            if(!$dadosMensalidades)
                return false;

            $dadosMensalidades['dia_repasse'] = $dia_repasse;
            if($repasse->initialInsert($dadosMensalidades, $data))
                return true;

            return false;
        }

        return false;

    }

    public function imovelDisponivel($data){
       $sql = "SELECT count(imovel_id) as contratado from contratos WHERE 
              ((data_inicio between :data_ini_inicio and :data_ini_fim) OR 
              (data_fim between :data_fim_inicio and :data_fim_fim)) and
              imovel_id = :imovel_id";

       $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":data_ini_inicio", $data['data_inicio']);
        $stmt->bindValue(":data_ini_fim", $data['data_fim']);
        $stmt->bindValue(":data_fim_inicio", $data['data_inicio']);
        $stmt->bindValue(":data_fim_fim", $data['data_fim']);
        $stmt->bindValue(":imovel_id", $data['imovel_id']);
        $stmt->execute();
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($resultado['contratado'] > 0)
          return false;
        else
          return true;
    }
}
