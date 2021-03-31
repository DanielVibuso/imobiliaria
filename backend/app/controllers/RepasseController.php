<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Repasse;

class RepasseController extends Controller{
    public function __construct(){
          $this->modelName = "Repasse";
          $this->modelClass = NAMESPACE_MODEL . "Repasse";
          //$this->campos = array('email', 'telefone');
     }

    public function edit($dados){
        $data['status'] = $dados['novo_status'];
        $data['mensalidade_id'] = $dados['mensalidade_id'];
        $where['id'] = $dados[0];
        $repasse = new Repasse;
        if($repasse->update($data, $where))
        {
             //http_response_code(200);
             echo json_encode(["status" => "Repasse alterado com sucesso"]);
             return;
        }
        echo json_encode(["status" => "Falha ao alterar repasse"]);
      }
}