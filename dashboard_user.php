<?php
session_start();
include('db.php');

// Verifica se o usuário está logado
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Garante que o script PHP não continue executando após redirecionar
}

$user_id = $_SESSION['user_id'];



// Fecha a declaração e a conexão

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Usuário</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #87CEFA; /* Azul celeste brilhante */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .panel-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .panel-container h2 {
            margin-bottom: 20px;
        }
        .panel-container p {
            margin: 10px 0;
        }
        .panel-container a {
            color: #007BFF;
            text-decoration: none;
        }
        .panel-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <h2>Bem-vindo ao Painel do Usuário</h2>
        <p><a href="mark_appointment.php">Marcar Consulta</a></p>
        <p><a href="appointments.php">Conferir Horários das Consultas</a></p>
        <p><a href="alert.php">Verificar Alertas de Medicamentos</a></p>
       
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
