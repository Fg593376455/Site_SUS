<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    // Sanitização
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    // Validação básica
    if (empty($date) || empty($time)) {
        header("Location: mark_appointment.php?error=empty_fields");
        exit();
    }

    // Impedir datas passadas
    $currentDate = date("Y-m-d");
    if ($date < $currentDate) {
        header("Location: mark_appointment.php?error=past_date");
        exit();
    }

    // Verificar duplicidade
    $check = $conn->prepare("SELECT id FROM appointments WHERE user_id = ? AND date = ? AND time = ?");
    $check->bind_param("iss", $user_id, $date, $time);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        header("Location: mark_appointment.php?error=duplicate");
        exit();
    }

    // Inserir consulta
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, date, time) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $date, $time);

    if ($stmt->execute()) {
        header("Location: mark_appointment.php?success=1");
        exit();
    } else {
        header("Location: mark_appointment.php?error=db_error");
        exit();
    }
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
            background-color: #f0f0f0; 
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #007bff;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; 
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            display: block;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc; 
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff; 
            color: #fff; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-button {
            display: block;
            max-width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        .msg {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

    <h2>Marcar Consulta</h2>

    <!-- Exibir mensagens -->
    <?php if (isset($_GET['error'])): ?>
        <p class="msg error">
            <?php
                switch ($_GET['error']) {
                    case 'empty_fields': echo "Preencha todos os campos."; break;
                    case 'past_date': echo "Não é permitido marcar uma data no passado."; break;
                    case 'duplicate': echo "Você já possui consulta nesse mesmo horário."; break;
                    case 'db_error': echo "Erro ao salvar no banco de dados."; break;
                }
            ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <p class="msg success">Consulta marcada com sucesso!</p>
    <?php endif; ?>

    <!-- Formulário -->
    <form action="mark_appointment.php" method="post">
        <label for="date">Data:</label>
        <input type="date" id="date" name="date" required>

        <label for="time">Hora:</label>
        <input type="time" id="time" name="time" required>

        <input type="submit" value="Marcar Consulta">
    </form>

    <a href="dashboard_user.php" class="back-button">Voltar</a>

</body>
</html>
