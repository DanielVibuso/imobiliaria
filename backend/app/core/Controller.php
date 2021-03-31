<?php
namespace app\core;

class Controller{
    protected $modelClass;
    protected $campos;
    protected $modelName;

    public function find($id_queryString){
      /*verifica se foi enviado um único id ou uma queryString de paginação*/
        if(!is_null($id_queryString))
            $resultado = explode("&", $id_queryString);
            if(count($resultado) == 1){
                $id = $resultado[0];
            }
            else{
                $id = null;
                $offset = explode("=", $resultado[0])[1];
                $limit = explode("=", $resultado[1])[1];
            }
        $model = new $this->modelClass;
        $dados = is_null($id) ? $model->all($offset, $limit) : $model->find($id);
        if(!is_array($dados))
        {
             http_response_code(204);
        }
        else
        {
             http_response_code(200);
             echo json_encode($dados);
        }
     }

     public function create($data){

        $model = new $this->modelClass;
        if($model->insert($data))
        {
             http_response_code(201);
             echo json_encode(["status" => "$this->modelName cadastrado com sucesso"]);
             return;
        }
        http_response_code(500);
        echo json_encode(["status" => "Falha ao cadastrar $this->modelName"]);
      }

      public function edit($data){
        $where['id'] = array_pop($data);

        $cliente = new $this->modelClass;
        if($cliente->update($data, $where))
        {
             http_response_code(200);
             echo json_encode(["status" => "$this->modelName alterado com sucesso"]);
             return;
        }
      }

      public function delete($id){
        $model = new $this->modelClass;
        if($model->delete($id))
        {
             http_response_code(200);
             echo json_encode(["status" => "$this->modelName deletado com sucesso"]);
             return;
        }

        http_response_code(500);
        echo json_encode(["status" => "Falha ao deletar $this->modelName"]);
     }

   
}
