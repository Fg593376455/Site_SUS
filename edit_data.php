<?php
session_start();
include('db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o usuário é um administrador
$user_id = $_SESSION['user_id'];
$sql = "SELECT is_admin FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row['is_admin']) {
    die("Acesso negado!");
}

// Processa o formulário quando for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $edit_type = $_POST['edit_type'];
    
    if ($edit_type == 'user') {
        $edit_user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $cpf = $_POST['cpf'];
        $phone = $_POST['phone'];

        $sql = "UPDATE users SET name=?, cpf=?, phone=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $cpf, $phone, $edit_user_id);
    } elseif ($edit_type == 'appointment') {
        $appointment_id = $_POST['appointment_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        $sql = "UPDATE appointments SET date=?, time=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $date, $time, $appointment_id);
    }

    if ($stmt->execute()) {
        echo "Dados atualizados com sucesso!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Dados</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #87CEFA;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .edit-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .edit-container h2 {
            margin-bottom: 20px;
        }
        .edit-container label {
            display: block;
            margin-bottom: 5px;
        }
        .edit-container input[type="text"],
        .edit-container input[type="date"],
        .edit-container input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .edit-container input[type="submit"],
        .edit-container input[type="button"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-container input[type="submit"]:hover,
        .edit-container input[type="button"]:hover {
            background-color: #0056b3;
        }
        .edit-container p {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Alterar Dados</h2>
        <form action="edit_data.php" method="post">
            <input type="radio" id="user" name="edit_type" value="user" checked onclick="toggleForm('user')">
            <label for="user">Alterar Usuário</label><br>
            <input type="radio" id="appointment" name="edit_type" value="appointment" onclick="toggleForm('appointment')">
            <label for="appointment">Alterar Consulta</label><br><br>

            <div id="user_form">
                <label for="user_id">ID do Usuário:</label><br>
                <input type="text" id="user_id" name="user_id" required><br>
                <label for="name">Nome:</label><br>
                <input type="text" id="name" name="name"><br>
                <label for="cpf">CPF:</label><br>
                <input type="text" id="cpf" name="cpf"><br>
                <label for="phone">Telefone:</label><br>
                <input type="text" id="phone" name="phone"><br>
            </div>

            <div id="appointment_form" style="display:none;">
                <label for="appointment_id">ID da Consulta:</label><br>
                <input type="text" id="appointment_id" name="appointment_id"><br>
                <label for="date">Data:</label><br>
                <input type="date" id="date" name="date"><br>
                <label for="time">Hora:</label><br>
                <input type="time" id="time" name="time"><br>
            </div><br>

            <input type="submit" value="Alterar Dados">
        </form>
        <br>
        <input type="button" value="Voltar" onclick="window.location.href='dashboard_admin.php';">
    </div>

    <script>
        function toggleForm(formType) {
            document.getElementById('user_form').style.display = (formType === 'user') ? 'block' : 'none';
            document.getElementById('appointment_form').style.display = (formType === 'appointment') ? 'block' : 'none';
        }
    </script>
</body>
</html>


