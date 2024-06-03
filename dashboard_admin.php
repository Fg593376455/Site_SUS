<?php
session_start();
include('db.php');

// Verifica se o usuário está logado
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Garante que o script PHP não continue executando após redirecionar
}

$user_id = $_SESSION['user_id'];

// Usar prepared statements para evitar injeção de SQL
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$is_admin = $row['is_admin'];

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
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
        <h2>Bem-vindo ao Painel do Administrador</h2>
       
        <?php if ($is_admin): ?>
            <p><a href="admin_data_view.php">Consultar Dados de Usuários</a></p>
            <p><a href="admin_data_consulta.php">Consultar Dados de Consultas</a></p>
            <p><a href="edit_data.php">Alterar Dados ou Consultas</a></p>
            <p><a href="delete_data.php">Excluir Dados ou Consultas</a></p>
            <p><a href="alert.php">Verificar Alertas de Medicamentos</a></p>
        <?php endif; ?>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>