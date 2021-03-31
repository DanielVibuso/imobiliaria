<?php

namespace app\helpers\model;

class RepasseHelper{

    public static function getVencimento($dataBase, $diaRepasse){

        $dataVencimento = date("Y-m-{$diaRepasse}", strtotime("+1 month", strtotime($dataBase)));
        return $dataVencimento;
    }

    /*@valorDia é o valor do aluguel dividido pela quantidade de dias no mes
    */
    public static function getRepasse($inicioContrato, $valAluguel, $taxaAdmin, $valIptu, $primeiroRepasse = false){
        if(!$primeiroRepasse)
            return ($valAluguel + $valIptu) - $taxaAdmin;

        $valorDia = self::getDayValue(self::getLastDay($inicioContrato), $valAluguel);
        $diasContantes = self::getDaysBetween($inicioContrato, self::getLastDay($inicioContrato));
        return (($valorDia * $diasContantes)  + $valIptu) - $taxaAdmin;
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