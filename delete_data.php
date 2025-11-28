<?php
session_start();
include('db.php');

$message = "";

// ------------------------------------------------------
// EXCLUIR USUÁRIO POR NOME (EXCLUI MEDICAMENTOS + CONSULTAS ANTES)
// ------------------------------------------------------
if (isset($_POST['delete_user_name'])) {
    $name = trim($_POST['delete_user_name']);

    if ($name !== "") {

        // Buscar IDs dos usuários com nome parecido
        $u = $conn->prepare("SELECT id FROM users WHERE name LIKE ?");
        $like = "%".$name."%";
        $u->bind_param("s", $like);
        $u->execute();
        $res = $u->get_result();

        while ($user = $res->fetch_assoc()) {
            $uid = $user['id'];

            // Excluir consultas
            $delC = $conn->prepare("DELETE FROM consultas WHERE user_id = ?");
            $delC->bind_param("i", $uid);
            $delC->execute();

            // Excluir medicamentos
            $delM = $conn->prepare("DELETE FROM medications WHERE user_id = ?");
            $delM->bind_param("i", $uid);
            $delM->execute();
        }

        // Agora excluir o usuário
        $delUser = $conn->prepare("DELETE FROM users WHERE name LIKE ?");
        $delUser->bind_param("s", $like);

        if ($delUser->execute()) {
            $message = "Usuário(s), medicamentos e consultas excluídos com sucesso!";
        } else {
            $message = "Erro ao excluir: " . $conn->error;
        }
    }
}



// ------------------------------------------------------
// EXCLUIR MEDICAMENTO POR NOME
// ------------------------------------------------------
if (isset($_POST['delete_med_name'])) {
    $med = trim($_POST['delete_med_name']);

    if ($med !== "") {
        $stmt = $conn->prepare("DELETE FROM medications WHERE name LIKE ?");
        $like = "%".$med."%";
        $stmt->bind_param("s", $like);

        if ($stmt->execute()) {
            $message = "Medicamento(s) excluído(s) com sucesso!";
        } else {
            $message = "Erro ao excluir medicamento: " . $conn->error;
        }
    }
}



// ------------------------------------------------------
// EXCLUIR CONSULTA POR NOME
// ------------------------------------------------------
if (isset($_POST['delete_consulta_name'])) {
    $con = trim($_POST['delete_consulta_name']);

    if ($con !== "") {
        $stmt = $conn->prepare("DELETE FROM consultas WHERE descricao LIKE ?");
        $like = "%".$con."%";
        $stmt->bind_param("s", $like);

        if ($stmt->execute()) {
            $message = "Consulta(s) excluída(s) com sucesso!";
        } else {
            $message = "Erro ao excluir consulta: " . $conn->error;
        }
    }
}

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
            background-color: #87CEFA;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 480px;
            margin: 50px auto;
            background: #ffffffdd;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px #0003;
        }

        h2 {
            text-align: center;
            color: #007BFF;
        }

        label {
            font-weight: bold;
            margin-top: 12px;
            display: block;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 13px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        .back {
            width: 100%;
            padding: 13px;
            background: #6c757d;
            color: white;
            border-radius: 8px;
            border: none;
            margin-top: 10px;
            cursor: pointer;
        }

        .back:hover {
            background: #5a6268;
        }

        .msg {
            background: #e3f2fd;
            padding: 12px;
            border-left: 5px solid #007BFF;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Excluir Dados</h2>

    <?php if ($message !== "") { echo "<div class='msg'>$message</div>"; } ?>

    <!-- EXCLUIR USUÁRIO -->
    <form method="post">
        <label>Excluir Usuário pelo Nome:</label>
        <input type="text" name="delete_user_name" placeholder="Ex: João Silva">
        <input type="submit" value="Excluir Usuário">
    </form>

    <!-- EXCLUIR MEDICAMENTO -->
    <form method="post">
        <label>Excluir Medicamento pelo Nome:</label>
        <input type="text" name="delete_med_name" placeholder="Ex: Dipirona">
        <input type="submit" value="Excluir Medicamento">
    </form>

    <!-- EXCLUIR CONSULTA -->
    <form method="post">
        <label>Excluir Consulta pelo Nome/Descrição:</label>
        <input type="text" name="delete_consulta_name" placeholder="Ex: Retorno cardiologista">
        <input type="submit" value="Excluir Consulta">
    </form>

    <button class="back" onclick="window.history.back();">Voltar</button>

</div>

</body>
</html>
