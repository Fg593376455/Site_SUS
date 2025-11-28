<?php
session_start();

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include('db.php');

$user_id = $_SESSION['user_id'];

// Consulta consultas do usuário
$stmt = $conn->prepare("SELECT date, time FROM appointments WHERE user_id = ? ORDER BY date, time");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fecha depois da consulta
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Horários das Consultas</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #87CEFA;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .appointment-container {
            background: rgba(255,255,255,0.9);
            width: 420px;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
        }

        h2 {
            margin-bottom: 20px;
            color: #007BFF;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }

        .no-appointments {
            font-size: 16px;
            color: #333;
            margin-top: 10px;
        }

        .back-button {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.2s;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="appointment-container">
        <h2>Consultas Marcadas</h2>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Data</th>
                        <th>Hora</th>
                    </tr>

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["date"]); ?></td>
                            <td><?= htmlspecialchars($row["time"]); ?></td>
                        </tr>
                    <?php endwhile; ?>

                </table>
            <?php else: ?>
                <p class="no-appointments">Nenhuma consulta marcada.</p>
            <?php endif; ?>
        </div>

        <a href="dashboard_user.php" class="back-button">Voltar</a>
    </div>

</body>
</html>
