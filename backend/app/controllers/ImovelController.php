<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Imovel;

class ImovelController extends Controller{
     public function __construct(){
          $this->modelName = "Imovel";
          $this->modelClass = NAMESPACE_MODEL . "Imovel";
          $this->campos = array('endereco', 'proprietario_id');
     }

     public function create($data){
        $imovel = new Imovel;
        $resultado = $imovel->existe($data);
        if($resultado){
          echo json_encode(["status" => "Imovel já cadastrado"]);
          return;
        }
        parent::create($data);
     }

    
}