<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sus";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
