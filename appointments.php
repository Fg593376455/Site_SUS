<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Garante que o script PHP não continue executando após redirecionar
}

include('db.php');

$user_id = $_SESSION['user_id'];

// Usar prepared statements para evitar injeção de SQL
$stmt = $conn->prepare("SELECT date, time FROM appointments WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Horário das Consultas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .appointment-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .appointment-container h2 {
            margin-bottom: 20px;
        }
        .appointment-container p {
            margin: 10px 0;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="appointment-container">
        <h2>Horário de Consultas</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>Data: " . htmlspecialchars($row["date"]) . " - Hora: " . htmlspecialchars($row["time"]) . "</p>";
            }
        } else {
            echo "<p>Nenhuma consulta marcada.</p>";
        }

        // Fechar a declaração e a conexão
        $stmt->close();
        $conn->close();
        ?>
    
        <a href="dashboard_user.php" class="back-button">Voltar</a>
    </div>
</body>
</html>

