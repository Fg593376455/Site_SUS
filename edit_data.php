<?php
session_start();
include('db.php');

// REDIRECIONA SE NÃO ESTIVER LOGADO
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// VERIFICA SE É ADMIN
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['is_admin'] != 1) {
    header("Location: dashboard_user.php");
    exit();
}

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $edit_type = $_POST['edit_type'];

    // EDITAR USUÁRIO
    if ($edit_type == 'user') {

        $edit_user_id = trim($_POST['user_id']);
        $name = trim($_POST['name']);
        $cpf = trim($_POST['cpf']);
        $phone = trim($_POST['phone']);

        // Verifica ID válido
        if (!is_numeric($edit_user_id)) {
            header("Location: edit_data.php?error=invalid_user_id");
            exit();
        }

        // Verifica se existe usuário
        $check = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $check->bind_param("i", $edit_user_id);
        $check->execute();
        if ($check->get_result()->num_rows == 0) {
            header("Location: edit_data.php?error=user_not_found");
            exit();
        }

        // Evitar sobrescrever dados com vazio
        if (empty($name) && empty($cpf) && empty($phone)) {
            header("Location: edit_data.php?error=empty_fields");
            exit();
        }

        // Monta query dinâmica
        $fields = [];
        $params = [];
        $types = "";

        if (!empty($name)) { 
            $fields[] = "name=?"; 
            $params[] = $name; 
            $types .= "s"; 
        }
        if (!empty($cpf)) { 
            $fields[] = "cpf=?"; 
            $params[] = $cpf; 
            $types .= "s"; 
        }
        if (!empty($phone)) { 
            $fields[] = "phone=?"; 
            $params[] = $phone; 
            $types .= "s"; 
        }

        $params[] = $edit_user_id;
        $types .= "i";

        $sql = "UPDATE users SET ".implode(", ", $fields)." WHERE id=?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            header("Location: edit_data.php?success=user_updated");
            exit();
        } else {
            header("Location: edit_data.php?error=db_error");
            exit();
        }
    }

    // EDITAR CONSULTA
    elseif ($edit_type == 'appointment') {

        $appt_id = trim($_POST['appointment_id']);
        $date = trim($_POST['date']);
        $time = trim($_POST['time']);

        if (!is_numeric($appt_id)) {
            header("Location: edit_data.php?error=invalid_appt_id");
            exit();
        }

        // Verifica se existe consulta
        $check = $conn->prepare("SELECT id FROM appointments WHERE id = ?");
        $check->bind_param("i", $appt_id);
        $check->execute();

        if ($check->get_result()->num_rows == 0) {
            header("Location: edit_data.php?error=appt_not_found");
            exit();
        }

        if (empty($date) && empty($time)) {
            header("Location: edit_data.php?error=empty_fields");
            exit();
        }

        // Atualização dinâmica
        $fields = [];
        $params = [];
        $types = "";

        if (!empty($date)) { 
            $fields[] = "date=?"; 
            $params[] = $date; 
            $types .= "s"; 
        }
        if (!empty($time)) { 
            $fields[] = "time=?"; 
            $params[] = $time; 
            $types .= "s"; 
        }

        $params[] = $appt_id;
        $types .= "i";

        $sql = "UPDATE appointments SET ".implode(", ", $fields)." WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            header("Location: edit_data.php?success=appt_updated");
            exit();
        } else {
            header("Location: edit_data.php?error=db_error");
            exit();
        }
    }
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
            background-color: #87CEFA;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .edit-container {
            width: 400px;
            background: rgba(255,255,255,0.9);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
        }

        h2 { text-align: center; }

        label { font-weight: bold; margin-top: 10px; display: block; }

        input[type=text], input[type=date], input[type=time] {
            width: 100%; padding: 10px;
            border-radius: 5px; border: 1px solid #ccc;
            margin-top: 5px; margin-bottom: 15px;
        }

        input[type=submit], input[type=button] {
            width: 100%; padding: 12px;
            border: none; border-radius: 5px;
            background: #007BFF; color: #fff;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type=submit]:hover, input[type=button]:hover {
            background: #0056b3;
        }

        .msg { text-align:center; font-weight:bold; margin-bottom:15px; }
        .error { color:red; }
        .success { color:green; }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2>Alterar Dados</h2>

        <!-- MENSAGENS -->
        <?php if (isset($_GET['error'])): ?>
            <p class="msg error">
                <?php
                    switch($_GET['error']){
                        case 'invalid_user_id': echo "ID de usuário inválido."; break;
                        case 'user_not_found': echo "Usuário não encontrado."; break;
                        case 'invalid_appt_id': echo "ID de consulta inválido."; break;
                        case 'appt_not_found': echo "Consulta não encontrada."; break;
                        case 'empty_fields': echo "Preencha pelo menos um campo."; break;
                        case 'db_error': echo "Erro no banco de dados."; break;
                    }
                ?>
            </p>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <p class="msg success">
                <?= ($_GET['success'] == 'user_updated') 
                    ? "Usuário atualizado com sucesso!" 
                    : "Consulta atualizada com sucesso!" ?>
            </p>
        <?php endif; ?>

        <!-- FORMULÁRIO -->
        <form action="edit_data.php" method="POST">
            
            <input type="radio" name="edit_type" value="user" checked onclick="toggleForm('user')"> Alterar Usuário  
            <br><br>
            <input type="radio" name="edit_type" value="appointment" onclick="toggleForm('appointment')"> Alterar Consulta  

            <!-- FORM USUÁRIO -->
            <div id="user_form">
                <label>ID do Usuário:</label>
                <input type="text" name="user_id">

                <label>Nome:</label>
                <input type="text" name="name">

                <label>CPF:</label>
                <input type="text" name="cpf">

                <label>Telefone:</label>
                <input type="text" name="phone">
            </div>

            <!-- FORM CONSULTA -->
            <div id="appointment_form" style="display:none;">
                <label>ID da Consulta:</label>
                <input type="text" name="appointment_id">

                <label>Data:</label>
                <input type="date" name="date">

                <label>Hora:</label>
                <input type="time" name="time">
            </div>

            <input type="submit" value="Salvar Alterações">
        </form>

        <input type="button" value="Voltar" onclick="window.location.href='dashboard_admin.php';">
    </div>

    <script>
        function toggleForm(type){
            document.getElementById("user_form").style.display = (type === "user") ? "block" : "none";
            document.getElementById("appointment_form").style.display = (type === "appointment") ? "block" : "none";
        }
    </script>

</body>
</html>
