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

// Fecha a declaração
$stmt->close();

// Verifica se o usuário é administrador
if (!$is_admin) {
    echo "Acesso negado. Apenas administradores podem acessar esta página.";
    exit();
}

// Consulta de dados que apenas administradores podem ver
$user_data_stmt = $conn->prepare("SELECT id, name FROM users");
$user_data_stmt->execute();
$user_data_result = $user_data_stmt->get_result();

// Consulta de todas as consultas
$appointment_stmt = $conn->prepare("SELECT appointments.id, appointments.date, appointments.time, users.name 
                                    FROM appointments 
                                    INNER JOIN users ON appointments.user_id = users.id");
$appointment_stmt->execute();
$appointment_result = $appointment_stmt->get_result();
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
        .panel-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .panel-container table, th, td {
            border: 1px solid black;
        }
        .panel-container th, td {
            padding: 10px;
            text-align: left;
        }
        .panel-container a {
            color: #007BFF;
            text-decoration: none;
        }
        .panel-container a:hover {
            text-decoration: underline;
        }
        .back-button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <h2>Bem-vindo ao Painel do Administrador</h2>
       
        <h3>Consultar Consultas de Todos os Pacientes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Consulta</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Nome do Paciente</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appointment_row = $appointment_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment_row['id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment_row['date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment_row['time']); ?></td>
                        <td><?php echo htmlspecialchars($appointment_row['name']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($is_admin): ?>
            <p><a href="edit_data.php">Alterar Dados ou Consultas</a></p>
            <p><a href="delete_data.php">Excluir Dados ou Consultas</a></p>
        <?php endif; ?>
        <button class="back-button" onclick="window.history.back();">Voltar</button>
    </div>
</body>
</html>
