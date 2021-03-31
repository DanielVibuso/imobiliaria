<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Proprietario;

class ProprietarioController extends Controller{

     public function __construct(){
          $this->modelName = "Proprietario";
          $this->modelClass = NAMESPACE_MODEL . "Proprietario";
         // $this->campos = array('email', 'telefone', 'dia_repasse');
     }

     public function create($data){
        $proprietario = new Proprietario;
        $resultado = $proprietario->existe($data);
        if($resultado){
          echo json_encode(["status" => "Email ou telefone já cadastrado"]);
          return;
        }
        parent::create($data);
     }
  
}