<?php

namespace app\helpers\core;

class CoreHelper{
	 public static function getInput($parametros){

      $data = get_object_vars(json_decode(file_get_contents("php://input")));
      return array_merge($parametros, $data);
    }
}

?>