<?php
// Conexión a MySQL
$host = "localhost";
$user = "root";
$password = "linux2025";
$db = "linux"; // Cambia si tu base tiene otro nombre

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Error en la conexión: " . mysqli_connect_error());
}

echo "Conexión establecida a MySQL<br>";

// Consulta para verificar conexión
$result = mysqli_query($conn, "SELECT DATABASE();");

// Simular carga de CPU
echo "Ejecutando operaciones intensivas de CPU...<br>";

$start = microtime(true);
$valor = 0;

for ($i = 0; $i < 50000000; $i++) {
    $valor += log($i + 1) * cos($i % 50);
}

$end = microtime(true);
$time = $end - $start;

echo "Operación finalizada. Resultado parcial: " . number_format($valor, 2) . "<br>";
echo "Tiempo de ejecución: " . number_format($time, 2) . " segundos<br>";

mysqli_close($conn);
?>
