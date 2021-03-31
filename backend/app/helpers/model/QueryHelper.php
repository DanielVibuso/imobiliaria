<?php

namespace app\helpers\model;

class QueryHelper{
    public static function insert($table, $data){
        //Parâmetros de posição escolhidos ao invés de parametros nomeados
        $sql = "INSERT INTO $table "; // VALUES ( $params )";
        $params = "";
        $columns = "";
        $values = array();

        foreach($data as $key => $val){
            $columns .= "$key";
            $params .= "?";
            array_push($values, $val);
            end($data);
            $lastKey = key($data);
            if($lastKey != $key){
                $columns .= ", ";
                $params .= ", ";
            }
        }

        $sql .= "($columns) VALUES ($params)";

        return array("sql" => $sql,
                      "values" => $values);

    }
    public static function update($table, $data, $where){
        $sql = "UPDATE $table SET ";
        $values = array();

        foreach($data as $key => $val){
            $sql .= "$key = ?";
            array_push($values, $val);
            end($data);
            $lastKey = key($data);
            if($lastKey != $key){
                $sql .= " , ";
            }
        }

        if (!is_null($where)){
            $sql .= " WHERE ";

            foreach($where as $key => $val){
                $sql .= "$key = ?";
                array_push($values, $val);
                end($where);
                $lastKey = key($where);
                if($lastKey != $key){
                    $sql .= " AND ";
                }
            }
        }

        return array("sql" => $sql,
                      "values" => $values);
    }

    /*public static function search($table, $data, $where){
        $selectVal = $data === "*" ? "*" : explode(',', $data);

        $sql = "SELECT $selectVal FROM $table";

        $values = array();

        if (!is_null($where)){
            $sql .= " WHERE ";

            foreach($where as $key => $val){
                $sql .= "$key = ?";
                array_push($values, $val);
                end($where);
                $lastKey = key($where);
                if($lastKey != $key){
                    $sql .= " OR ";
                }
            }
        }

        return array("sql" => $sql,
                      "values" => $values);
    }*/

    public static function queryStringURL($totalRegistros, $offset, $limit){
        $totalPages = $totalRegistros / $limit;
        //$paginas = array();
        $proxima = $offset + $limit;
        $anterior = $offset - $limit;

        if($proxima < $totalRegistros)
            $proxima = "offset=$proxima&limit=$limit";
        else
            $proxima = "";

        //if($anterior  <= 0)
         //   $anterior = "";
        //else
            $anterior = "offset=$anterior&limit=$limit";

        return array("anterior" => $anterior, "proxima" => $proxima);
    }
}