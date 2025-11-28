<?php
session_start();
include('db.php');

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Confirma se é admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$is_admin = $row['is_admin'];
$stmt->close();

if (!$is_admin) {
    echo "Acesso negado. Apenas administradores podem acessar esta página.";
    exit();
}

// Obtém todas as consultas
$appointment_stmt = $conn->prepare("
    SELECT appointments.id, appointments.date, appointments.time, users.name
    FROM appointments
    INNER JOIN users ON appointments.user_id = users.id
    ORDER BY appointments.date, appointments.time
");
$appointment_stmt->execute();
$appointment_result = $appointment_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultas - Administrador</title>
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
            width: 700px;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
        }

        h2 {
            margin-bottom: 20px;
            color: #007BFF;
        }

        h3 {
            color: #333;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            background-color: #007BFF;
            color: white;
            padding: 12px;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

    <div class="panel-container">
        <h2>Consultas de Todos os Pacientes</h2>

        <h3>Lista Geral de Consultas</h3>

        <table>
            <thead>
                <tr>
                    <th>ID Consulta</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointment_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['date']); ?></td>
                        <td><?= htmlspecialchars($row['time']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>

        <a href="edit_data.php">Alterar Dados ou Consultas</a><br><br>
        <a href="delete_data.php">Excluir Dados ou Consultas</a><br><br>

        <button class="back-button" onclick="window.location.href='dashboard_admin.php'">Voltar</button>
    </div>

</body>
</html>

<?php
$appointment_stmt->close();
$conn->close();
?>
