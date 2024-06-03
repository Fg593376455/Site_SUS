<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $password = $_POST['password'];

    // Usar prepared statements para evitar injeção de SQL
    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            if ($row['is_admin']) {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "CPF não encontrado!";
    }

    $stmt->close();
    $conn->close();
}
?>




