<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitização básica
    $cpf = trim($_POST['cpf']);
    $password = trim($_POST['password']);

    // Validação simples
    if (empty($cpf) || empty($password)) {
        header("Location: index.php?error=empty_fields");
        exit();
    }

    // Buscar usuário por CPF
    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se CPF existe
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verifica senha
        if (password_verify($password, $row['password'])) {

            // Login bem-sucedido
            $_SESSION['user_id'] = $row['id'];

            if ($row['is_admin'] == 1) {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit();

        } else {
            // Senha incorreta
            header("Location: index.php?error=wrong_password");
            exit();
        }

    } else {
        // CPF não existe
        header("Location: index.php?error=cpf_not_found");
        exit();
    }

    // Boa prática
    $stmt->close();
    $conn->close();
}
?>
