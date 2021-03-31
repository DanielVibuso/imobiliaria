<?php

namespace app\models;
use app\core\Model;
use app\helpers\model\QueryHelper;
use app\helpers\model\RepasseHelper;
use app\models\Proprietario;
use app\models\Mensalidade;

class Repasse extends Model{
    public function __construct() {
        parent::__construct();
        $this->table = "repasses";
    }

    public function initialInsert($mensalidade, $dadosContrato){
        $data['mensalidade_id'] = '';
        $data['valor'] = RepasseHelper::getRepasse($dadosContrato['data_inicio'], $dadosContrato['valor_aluguel'],
                                    $dadosContrato['taxa_admin'], $dadosContrato['valor_iptu'], true);
        $repasseValFixo = RepasseHelper::getRepasse($dadosContrato['data_inicio'], $dadosContrato['valor_aluguel'],
                                    $dadosContrato['taxa_admin'], $dadosContrato['valor_iptu'], false);
        $prop = new Proprietario();
        $proprietario = $prop->find($dadosContrato['proprietario_id']);

        $data['data_vencimento'] = RepasseHelper::getVencimento($dadosContrato['data_inicio'], $proprietario['dados']['dia_repasse']);
        $data['status'] = 'PENDENTE';

        $pdoParams = QueryHelper::insert($this->table, $data);

        $stmt = $this->db->prepare($pdoParams['sql']);
        $this->db->beginTransaction();
        for($repasseCount = 1; $repasseCount <= 12; $repasseCount++){
            $data['mensalidade_id'] = $mensalidade[$repasseCount]['mensalidade_id'];
            $stmt->execute(array_values($data));
            $data['data_vencimento'] = RepasseHelper::getVencimento($data['data_vencimento'], $proprietario['dados']['dia_repasse']);
            $data['valor'] = $repasseValFixo;
        }

        if($this->db->commit())
            return true;
        else
            return false;
    }

    public function update($data, $where){
        $mensalidade = new Mensalidade();
        if($mensalidade->verificaPagamento($data['mensalidade_id'])){
            return parent::update($data, $where);
        }
        return false;
    }

    public function find($idContrato){
        $sql = "SELECT  repasses.mensalidade_id as mensalidade_id,
                        repasses.id as id,
                        repasses.valor as valor,
                        DATE_FORMAT(repasses.data_vencimento, '%d/%m/%Y') as data_vencimento,
                        repasses.status as status FROM repasses
                        inner join mensalidades on mensalidades.id = repasses.mensalidade_id
                        inner join contratos on contratos.id = mensalidades.contrato_id
                       WHERE mensalidades.contrato_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $idContrato);
        $stmt->execute();
        $dados['dados'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return  $dados;
    }
}