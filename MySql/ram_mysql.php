
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$password = "linux2025";
$db = "linux"; // Cambia si tu base tiene otro nombre

$count = isset($_GET['count']) ? (int)$_GET['count'] : 200000;
$requestedMem = isset($_GET['mem']) ? $_GET['mem'] : null; // e.g. "512M" or "1G"

// Si te atreves, puedes aumentar el límite SOLO PARA ESTE SCRIPT (pruebas)
if ($requestedMem) {
    ini_set('memory_limit', $requestedMem);
}

// Helper: parse memory limit string to bytes
function memToBytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $num = (int)$val;
    switch($last) {
        case 'g': return $num * 1024 * 1024 * 1024;
        case 'm': return $num * 1024 * 1024;
        case 'k': return $num * 1024;
        default: return (int)$val;
    }
}

// Conexión MySQL (misma conexión que tienes)
$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    http_response_code(500);
    die("Error en la conexión MySQL: " . mysqli_connect_error());
}

echo "Conexión MySQL OK.<br>";
echo "memory_limit (php.ini/actual): " . ini_get('memory_limit') . "<br>";
$limitStr = ini_get('memory_limit');
$limitBytes = ($limitStr === '-1') ? PHP_INT_MAX : memToBytes($limitStr);
echo "Memoria inicial (bytes): " . memory_get_usage(true) . "<br>";
echo "Contador pedido: " . number_format($count) . "<br>";

$data = [];
$start = microtime(true);

// parámetros de control
$batch = 10000;
$created = 0;
$warnThreshold = 0.9; // detener si alcanzamos 90% del límite

while ($created < $count) {
    $toCreate = min($batch, $count - $created);
    for ($i = 0; $i < $toCreate; $i++) {
        $data[] = str_repeat("X", 100); // ~100 bytes por elemento
    }
    $created += $toCreate;

    $memUsage = memory_get_usage(true);
    echo "Generados: " . number_format($created) . " — memoria usada (bytes): $memUsage<br>";
    @ob_flush(); @flush();

    // Si nos acercamos peligrosamente al límite: abortar con mensaje
    if ($limitBytes !== PHP_INT_MAX && $memUsage > ($limitBytes * $warnThreshold)) {
        echo "<strong>Deteniendo generación:</strong> uso de memoria alcanzó " . number_format($memUsage) . " bytes (>= " . ($warnThreshold*100) . "% del límite de " . $limitStr . ").<br>";
        break;
    }

    // pequeña pausa para observar el cambio en el servidor
    usleep(5000);
}

$end = microtime(true);
echo "Total elementos en memoria: " . number_format(count($data)) . "<br>";
echo "Memoria final (bytes): " . memory_get_usage(true) . "<br>";
echo "Peak memory (bytes): " . memory_get_peak_usage(true) . "<br>";
echo "Tiempo (s): " . number_format($end - $start, 2) . "<br>";

// liberar memoria
unset($data);
echo "Array liberado. Memoria después de unset (bytes): " . memory_get_usage(true) . "<br>";

mysqli_close($conn);
?>
