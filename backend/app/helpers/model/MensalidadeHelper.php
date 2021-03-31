<?php

namespace app\helpers\model;

class MensalidadeHelper{

    public static function getVencimento($dataBase){

        $dataVencimento = date("Y-m-01", strtotime("+1 month", strtotime($dataBase)));
        return $dataVencimento;
    }

    public static function getMensalidade($inicioContrato, $valAluguel, $valCondominio, $valIptu, $primeiraMensalidade = false){
        if(!$primeiraMensalidade)
            return $valAluguel + $valCondominio + $valIptu;

        $valorDia = self::getDayValue(self::getLastDay($inicioContrato), $valAluguel);
        $diasContantes = self::getDaysBetween($inicioContrato, self::getLastDay($inicioContrato));
        return ($valorDia * $diasContantes) + $valCondominio + $valIptu ;
    }

    public static function getLastDay($inicioContrato){
        $lastDateOfMonth = date("t", strtotime($inicioContrato));
        return $lastDateOfMonth;
    }

    public static function getDayValue($dia, $valAluguel){
        return $valAluguel / $dia;
    }

    public static function getDaysBetween($inicioContrato, $fimDoMes){
        $diaInicialContrato = date("d", strtotime($inicioContrato));
        return ($fimDoMes - $diaInicialContrato) + 1; /* adiciona +1 se não ele não conta o dia atual */

    }
}