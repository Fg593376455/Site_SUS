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

// Inicializa variáveis
$search_name = "";
$search_result = null;

// Verifica se foi feita uma pesquisa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_name = $_POST['name'];

    // Consulta de dados pelo nome
    $search_stmt = $conn->prepare("SELECT id, name, cpf, phone, is_admin FROM users WHERE name LIKE ?");
    $like_search_name = "%" . $search_name . "%";
    $search_stmt->bind_param("s", $like_search_name);
    $search_stmt->execute();
    $search_result = $search_stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Dados - Administrador</title>
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
        .panel-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .panel-container h2 {
            margin-bottom: 20px;
        }
        .panel-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .panel-container table, th, td {
            border: 1px solid black;
        }
        .panel-container th, td {
            padding: 10px;
            text-align: left;
        }
        .panel-container a {
            color: #007BFF;
            text-decoration: none;
        }
        .panel-container a:hover {
            text-decoration: underline;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <h2>Consulta de Dados - Administrador</h2>
        <form class="search-form" method="post" action="">
            <input type="text" name="name" placeholder="Digite o nome para buscar" value="<?php echo htmlspecialchars($search_name); ?>" required>
            <input type="submit" value="Buscar">
        </form>
        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Tipo de Usuário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data_row = $search_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data_row['id']); ?></td>
                            <td><?php echo htmlspecialchars($data_row['name']); ?></td>
                            <td><?php echo htmlspecialchars($data_row['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($data_row['phone']); ?></td>
                            <td><?php echo $data_row['is_admin'] ? 'Administrador' : 'Usuário'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p>Nenhum usuário encontrado com o nome "<?php echo htmlspecialchars($search_name); ?>".</p>
        <?php endif; ?>
        <p><a href="dashboard_admin.php">Voltar ao Painel do Administrador</a></p>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>

<?php
if (isset($search_stmt)) {
    $search_stmt->close();
}
$conn->close();
?>
