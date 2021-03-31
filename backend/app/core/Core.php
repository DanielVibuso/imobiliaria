<?php
namespace app\core;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

use app\helpers\core\CoreHelper;

class Core{
    private $controller;
    private $metodo;
    private $parametros = array();

    public function __construct() {
        $this->verificaUri();
    }

    public function run(){
        $controllerCorrente = $this->getController();

       $controller = new $controllerCorrente;
       call_user_func_array(array($controller,
                            $this->getMetodo()),
                            count($this->getParametros()) > 1 ?
                            array($this->getParametros()) :
                            $this->getParametros());

    }
    public function verificaUri(){
        $url = explode("index.php", $_SERVER["PHP_SELF"]);
        $url = end($url);
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if($url!=""){
            $url = explode('/', $url);
            array_shift($url);

            //Pega o Controller
            $this->controller = ucfirst($url[0]) ."Controller";
            array_shift($url);

                //Verifica método
                switch($requestMethod){
                    case "GET":
                        $this->metodo = "find";
                        break;
                    case "POST":
                        $this->metodo = "create";
                        $this->parametros = CoreHelper::getInput($this->parametros);
                        break;
                    case "PUT":
                        $this->metodo = "edit";
                        $this->parametros = CoreHelper::getInput($this->parametros);
                        break;
                    case "DELETE":
                        $this->metodo = "delete";
                        break;
                    default:
                        http_response_code(501);
                        die();
                }

            //Pegar os parâmetros
            if(isset($url[0])){
                array_push($this->parametros, $url[0]);
            }

        }else{
            $this->controller = ucfirst(CONTROLLER_PADRAO) ."Controller";
        }

    }
    public function getController() {
        if(class_exists(NAMESPACE_CONTROLLER .$this->controller)){
            return NAMESPACE_CONTROLLER .$this->controller;
        }
        return NAMESPACE_CONTROLLER .ucfirst(CONTROLLER_PADRAO) ."Controller";
    }

    public function getMetodo() {
        if(method_exists(NAMESPACE_CONTROLLER .$this->controller, $this->metodo)){
            return $this->metodo;
        }

        return METODO_PADRAO;
    }

    public function getParametros() {
        return $this->parametros;
    }
}
