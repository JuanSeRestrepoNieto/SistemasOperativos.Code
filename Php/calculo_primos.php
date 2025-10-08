<?php

header('Content-Type: text/plain');

function es_primo($numero) {
    if ($numero <= 1) {
        return false;
    }
    for ($i = 2; $i <= sqrt($numero); $i++) {
        if ($numero % $i == 0) {
            return false;
        }
    }
    return true;
}

$limite = 100000;
$primos = [];

echo "Calculando números primos hasta $limite...\n";

$inicio_tiempo = microtime(true);

for ($i = 1; $i <= $limite; $i++) {
    if (es_primo($i)) {
        $primos[] = $i;
    }
}

$fin_tiempo = microtime(true);
$tiempo_total = round($fin_tiempo - $inicio_tiempo, 4);

echo "Cálculo finalizado.\n";
echo "Se encontraron " . count($primos) . " números primos.\n";
echo "Tiempo total de ejecución: " . $tiempo_total . " segundos.\n";

?>