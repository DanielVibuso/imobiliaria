<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Mensalidade;

class MensalidadeController extends Controller{
    public function __construct(){
          $this->modelName = "Mensalidade";
          $this->modelClass = NAMESPACE_MODEL . "Mensalidade";
          //$this->campos = array('email', 'telefone');
     }

    public function edit($data){
        $dados['status'] = $data['novo_status'];
        $where['id'] = $data[0];
        $mensalidade = new Mensalidade;
        if($mensalidade->update($dados, $where))
        {
             http_response_code(200);
             echo json_encode(["status" => "Mensalidade alterado com sucesso"]);
             return;
        }

        echo json_encode(["status" => "Falha ao alterar mensalidade"]);
      }
}