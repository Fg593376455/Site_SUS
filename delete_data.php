<?php
session_start();
include('db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delete_type = $_POST['delete_type'];
    if ($delete_type == "user" && isset($_POST['user_name'])) {
        $user_name_to_delete = $_POST['user_name'];
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE name = ?");
        $delete_stmt->bind_param("s", $user_name_to_delete);
    } elseif ($delete_type == "appointment" && isset($_POST['appointment_id'])) {
        $appointment_id_to_delete = $_POST['appointment_id'];
        $delete_stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $delete_stmt->bind_param("i", $appointment_id_to_delete);
    }
    
    if ($delete_stmt->execute()) {
        if ($delete_stmt->affected_rows > 0) {
            $message = "Dados excluídos com sucesso!";
        } else {
            $message = "Nenhum registro encontrado para exclusão.";
        }
    } else {
        $message = "Erro ao excluir dados: " . $conn->error;
    }

    $delete_stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Dados</title>
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
        .delete-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .delete-container h2 {
            margin-bottom: 20px;
        }
        .delete-container label {
            display: block;
            margin-bottom: 5px;
        }
        .delete-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .delete-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .delete-container p {
            margin-top: 10px;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
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
    <script>
        function toggleForm(type) {
            if (type === 'user') {
                document.getElementById('user_form').style.display = 'block';
                document.getElementById('appointment_form').style.display = 'none';
            } else if (type === 'appointment') {
                document.getElementById('user_form').style.display = 'none';
                document.getElementById('appointment_form').style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <div class="delete-container">
        <h2>Excluir Dados</h2>
        <form action="delete_data.php" method="post">
            <input type="radio" id="user" name="delete_type" value="user" checked onclick="toggleForm('user')">
            <label for="user">Excluir Usuário</label><br>
            <input type="radio" id="appointment" name="delete_type" value="appointment" onclick="toggleForm('appointment')">
            <label for="appointment">Excluir Consulta</label><br><br>

            <div id="user_form">
                <label for="user_name">Nome do Usuário:</label><br>
                <input type="text" id="user_name" name="user_name"><br>
            </div>

            <div id="appointment_form" style="display:none;">
                <label for="appointment_id">ID da Consulta:</label><br>
                <input type="text" id="appointment_id" name="appointment_id"><br>
            </div><br>

            <input type="submit" value="Excluir Dados">
        </form>

        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <button class="back-button" onclick="window.location.href='dashboard_admin.php'">Voltar</button>
    </div>
</body>
</html>

