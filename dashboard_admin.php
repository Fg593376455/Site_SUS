<?php
session_start();
include('db.php');

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Busca dados do usuário
$stmt = $conn->prepare("SELECT name, is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Usuário não existe mais
    header("Location: logout.php");
    exit();
}

$user = $result->fetch_assoc();
$name = $user['name'];
$is_admin = $user['is_admin'];

// Bloqueia acesso se não for admin
if ($is_admin != 1) {
    header("Location: dashboard_user.php");
    exit();
}

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
            background-color: #87CEFA;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .panel-container {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            width: 420px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
            text-align: center;
        }

        h2 {
            margin-bottom: 15px;
        }

        .welcome {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #007BFF;
        }

        .btn {
            display: block;
            padding: 12px;
            margin: 12px 0;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 15px;
            transition: 0.2s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .logout {
            background: #dc3545;
        }

        .logout:hover {
            background: #b51f2f;
        }
    </style>
</head>
<body>

    <div class="panel-container">

        <h2>Painel do Administrador</h2>
        <p class="welcome">Olá, <strong><?= htmlspecialchars($name) ?></strong>!</p>

        <a href="admin_data_view.php" class="btn">Consultar Dados de Usuários</a>
        <a href="admin_data_consulta.php" class="btn">Consultar Consultas</a>
        <a href="edit_data.php" class="btn">Alterar Dados ou Consultas</a>
        <a href="delete_data.php" class="btn">Excluir Usuários ou Consultas</a>
        <a href="alert.php" class="btn">Alertas de Medicamentos</a>
        <a href="register.php" class="btn">Registrar Novo Usuário</a>

        <a href="logout.php" class="btn logout">Sair</a>

    </div>

</body>
</html>
