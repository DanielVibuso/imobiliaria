<?php
namespace app\controllers;

use app\core\Controller;

class ClienteController extends Controller{

     public function __construct(){
          $this->modelName = "Cliente";
          $this->modelClass = NAMESPACE_MODEL . "Cliente";
          $this->campos = array('email', 'telefone');
     }

     public function create($data){
        $cliente = new $this->modelClass;
        $resultado = $cliente->existe($data);
        if($resultado){
          echo json_encode(["status" => "Email ou telefone já cadastrado"]);
          return;
        }
        parent::create($data);
     }

}
