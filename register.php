<?php
include('db.php');

// Exibir erros (debug temporário)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitização
    $name = trim($_POST['name']);
    $cpf = trim($_POST['cpf']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Validações
    if ($name === "" || $cpf === "" || $phone === "" || $password === "") {
        echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
        exit();
    }

    if (strlen($password) < 8) {
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres!'); window.history.back();</script>";
        exit();
    }

    // Verificar CPF já cadastrado
    $check = $conn->prepare("SELECT id FROM users WHERE cpf = ?");
    if (!$check) {
        die("Erro no prepare do SELECT: " . $conn->error);
    }

    $check->bind_param("s", $cpf);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Este CPF já está cadastrado!'); window.history.back();</script>";
        exit();
    }

    // Hash da senha
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Inserção segura
    $stmt = $conn->prepare("INSERT INTO users (name, cpf, phone, password, is_admin) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Erro no prepare do INSERT: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $name, $cpf, $phone, $hashed_password, $is_admin);

    if ($stmt->execute()) {
        echo "<script>alert('Registro concluído com sucesso!'); window.location='index.php';</script>";
        exit();
    } else {
        die("Erro ao inserir: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
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
        .register-container {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.25);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #007BFF;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registro</h2>

        <!-- AQUI está a correção importantíssima -->
        <form action="" method="post">

            <label for="name">Nome:</label>
            <input type="text" name="name" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" required>

            <label for="phone">Telefone:</label>
            <input type="text" name="phone" required>

            <label for="password">Senha:</label>
            <input type="password" name="password" minlength="8" required>

            <label for="is_admin">Administrador:</label>
            <input type="checkbox" name="is_admin">

            <input type="submit" value="Registrar">
        </form>

        <button class="back-button" onclick="window.history.back();">Voltar</button>
    </div>
</body>
</html>
