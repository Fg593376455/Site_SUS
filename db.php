<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sus";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
/*
 //Verifica a conexão
if ($conn->connect_error) {
    
    echo"conexão falhou";
}
echo "Conexão bem-sucedida";
?>*/