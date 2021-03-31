<?php

namespace app\models;
use app\core\Model;
use app\helpers\model\QueryHelper;
use app\helpers\model\MensalidadeHelper;

class Mensalidade extends Model{
    public function __construct() {
        parent::__construct();
        $this->table = "mensalidades";
    }

    public function initialInsert($dadosContrato){
        //Aluguel + iptu + condominio
        $repasse = new Repasse();
        $dataRep = array();
        $dataMen['contrato_id'] = $dadosContrato['contrato_id'];
        $dataMen['data_vencimento'] = MensalidadeHelper::getVencimento($dadosContrato['data_inicio']);
        $dataMen['status'] = "PENDENTE";
        $dataMen['valor'] = MensalidadeHelper::getMensalidade($dadosContrato['data_inicio'], $dadosContrato['valor_aluguel'],
                            $dadosContrato['valor_condominio'],
                            $dadosContrato['valor_iptu'],
                            /*Primeira mensalidade ? */ true);
        $pdoParams = QueryHelper::insert($this->table, $dataMen);

        $mensalidadeValFixo = MensalidadeHelper::getMensalidade($dadosContrato['data_inicio'], $dadosContrato['valor_aluguel'],
        $dadosContrato['valor_condominio'],
        $dadosContrato['valor_iptu'],
        /*Primeira mensalidade ? */ false);

        $stmt = $this->db->prepare($pdoParams['sql']);
        $this->db->beginTransaction();
        for($mensalidadeCount = 1; $mensalidadeCount <= 12; $mensalidadeCount++){
            $stmt->execute($pdoParams['values']);
            $dataRep[$mensalidadeCount]['mensalidade_id'] = $this->db->lastInsertId();
            $pdoParams['values'][1/*vencimento*/] = MensalidadeHelper::getVencimento($pdoParams['values'][1]);
            $pdoParams['values'][3/*valor*/] = $mensalidadeValFixo;
        }

        if($this->db->commit())
            return $dataRep;
        else
            return false;
        //$repasse->initialInsert($dataRep, $data);
    }

     public function find($idContrato){
        $sql = "SELECT  mensalidades.id as id,
                        mensalidades.valor as valor,
                        DATE_FORMAT(mensalidades.data_vencimento, '%d/%m/%Y') as data_vencimento,
                        mensalidades.status as status FROM mensalidades
                       WHERE mensalidades.contrato_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $idContrato);
        $stmt->execute();
        $dados['dados'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return  $dados;
    }

    public function verificaPagamento($id){
        $sql = "SELECT  mensalidades.status as status
                        FROM mensalidades
                       WHERE mensalidades.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $dados = $stmt->fetch(\PDO::FETCH_ASSOC);
        if( $dados['status'] == "PAGA" )
            return true;
        else
            return false;
    }

}