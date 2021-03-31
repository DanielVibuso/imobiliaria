<?php
namespace app\controllers;

class IndexController{

   public function index(){
      http_response_code(404);
    echo json_encode(["status" => "rota não encontrada"]);
   }
}
