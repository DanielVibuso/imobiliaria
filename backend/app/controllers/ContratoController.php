<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Contrato;
//use app\models\Mensalidade;

class ContratoController extends Controller{

     public function __construct(){
          $this->modelName = "Contrato";
          $this->modelClass = NAMESPACE_MODEL . "Contrato";
          $this->campos = array('imovel_id', 'proprietario_id', 'cliente_id',
                               'data_inicio', 'data_fim', 'taxa_admin',
                               'valor_aluguel', 'valor_condominio', 'valor_iptu');
     }
   

   public function create($data){

     $contrato = new Contrato;
     if(!$contrato->imovelDisponivel($data)){
        http_response_code(200);
        echo json_encode(["status" => "Imóvel indisponível no período escolhido"]);
        return;
     }

     parent::create($data);
    
   }

   

}
