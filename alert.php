<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Garante que o script PHP não continue executando após redirecionar
}

include('db.php');

$user_id = $_SESSION['user_id'];

// Usar prepared statements para evitar injeção de SQL
$stmt = $conn->prepare("SELECT name, next_refill_date FROM medications WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alertas de Medicamentos</title>
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
        .medications-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .medications-container h2 {
            margin-bottom: 20px;
        }
        .medications-container p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="medications-container">
        <h2>Alertas de Medicamentos</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>Nome do Medicamento: " . htmlspecialchars($row["name"]) . " - Próxima Reposição: " . htmlspecialchars($row["next_refill_date"]) . "</p>";
            }
        } else {
            echo "<p>Nenhum medicamento registrado.</p>";
        }

        // Fechar a declaração e a conexão
        $stmt->close();
        $conn->close();
        ?>
        <button class="back-button" onclick="window.history.back();">Voltar</button>
    </div>
</body>
</html>
