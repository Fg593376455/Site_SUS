<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $cpf = $_POST['cpf'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $sql = "INSERT INTO users (name, cpf, phone, password, is_admin) VALUES ('$name', '$cpf', '$phone', '$password', '$is_admin')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro criado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

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
            background-color: #87CEFA; /* Azul celeste brilhante */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .register-container h2 {
            margin-bottom: 20px;
        }
        .register-container label {
            
            margin-bottom: 5px;
        }
        .register-container input[type="text"],
        .register-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-container input[type="checkbox"] {
            margin-top: -30px;
            margin-left: 10px;
            padding: 5px;
        }
        .register-container input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .register-container p {
            margin-top: 10px;
        }
        .register-container a {
            color: #007BFF;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registro</h2>
        <form action="register.php" method="post">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required><br>
            <label for="phone">Telefone:</label>
            <input type="text" id="phone" name="phone" required><br>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" minlength="8" required><br><br>
            <label for="is_admin">Administrador:</label>
            <input type="checkbox" id="is_admin" name="is_admin"><br><br><br>
            <input type="submit" value="Registrar">
        </form>
        <p>Já tem uma conta? <a href="index.php">Faça login</a></p>
    </div>
</body>
</html>