<?php
session_start();

// Se já estiver logado, redireciona
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard_user.php");
    exit();
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
            background-color: #87CEFA; /* Azul SUS, igual às outras telas */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255,255,255,0.9);
            width: 350px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
            text-align: center;
        }

        h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: #d32f2f;
            margin-bottom: 15px;
            font-weight: bold;
        }

        p {
            margin-top: 15px;
        }

        a {
            color: #007BFF;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <!-- Exibição de erros -->
        <?php if (isset($_GET['error'])): ?>
            <p class="error">
                <?php
                    switch ($_GET['error']) {
                        case 'empty_fields': echo "Preencha todos os campos."; break;
                        case 'wrong_password': echo "Senha incorreta."; break;
                        case 'cpf_not_found': echo "CPF não encontrado."; break;
                    }
                ?>
            </p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Entrar">
        </form>

        <p>Não tem uma conta? <a href="register.php">Registre-se aqui</a></p>
    </div>

</body>
</html>
