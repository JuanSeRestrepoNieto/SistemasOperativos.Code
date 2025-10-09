<?php
$servername = "localhost";
$database = "linux";
$username = "root";
$password = "linux2025";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}
 
echo "Connected successfully";
 
$sql = "INSERT INTO sabado (nombre, apellido, email, telefono) VALUES ('pedro', 'perez', 'pedro@paramo.com', '3003763097')";
if (mysqli_query($conn, $sql)) {
      echo "New record created successfully";
} else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
?>
