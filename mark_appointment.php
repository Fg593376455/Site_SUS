<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Usar prepared statements para evitar injeção de SQL
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, date, time) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $date, $time);

    if ($stmt->execute()) {
        echo "Consulta marcada com sucesso!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Marcar Consulta</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Cor de fundo semelhante ao do projeto SUS */
        }

        h2 {
            margin-top: 0;
            color: #007bff; /* Azul usado no projeto SUS */
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff; /* Fundo branco */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra leve */
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555; /* Cor de texto cinza */
        }

        input[type="date"],
        input[type="time"] {
            width: calc(100% - 22px); /* Descontar o tamanho do padding */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc; /* Borda cinza */
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff; /* Azul usado no projeto SUS */
            color: #fff; /* Texto branco */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Azul mais escuro ao passar o mouse */
        }

        .back-button {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #6c757d; /* Cor cinza semelhante ao SUS */
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }

        .back-button:hover {
            background-color: #5a6268; /* Cor cinza mais escura ao passar o mouse */
        }
    </style>
</head>
<body>
    <h2>Marcar Consulta</h2>
    <form action="mark_appointment.php" method="post">
        <label for="date">Data:</label><br>
        <input type="date" id="date" name="date" required><br>
        <label for="time">Hora:</label><br>
        <input type="time" id="time" name="time" required><br><br>
        <input type="submit" value="Marcar Consulta">
    </form>

    <a href="dashboard_user.php" class="back-button">Voltar</a>
</body>
</html>
