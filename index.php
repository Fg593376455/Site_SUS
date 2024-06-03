<?php
session_start();
include('db.php');
// Usar prepared statements para evitar injeção de SQL
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$is_admin = ($row !== null && array_key_exists('is_admin', $row)) ? $row['is_admin'] : null;


if ($is_admin !== null) {
    if ($is_admin == 1) {
        header('Location: dashboard_admin.php');
        exit();
    } else {
        header('Location: dashboard_user.php');
        exit();
    }


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Garante que o script PHP não continue executando após redirecionar
}


}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-form h2 {
            margin-bottom: 20px;
        }
        .login-form label {
            display: block;
            margin-bottom: 5px;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .login-form p {
            margin-top: 10px;
        }
        .login-form a {
            color: #007BFF;
        }
        .login-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" required>
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Login">
            </form>
            <p>Não tem uma conta? <a href="register.php">Registre-se aqui</a></p>
        </div>
    </div>
</body>
</html>
