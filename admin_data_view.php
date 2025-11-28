<?php
session_start();
include('db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Verifica se é admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$is_admin = $row['is_admin'];
$stmt->close();

if (!$is_admin) {
    echo "Acesso negado. Apenas administradores podem acessar esta página.";
    exit();
}

// Variáveis
$search_name = "";
$search_result = null;

// Pesquisa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_name = trim($_POST['name']);

    $search_stmt = $conn->prepare(
        "SELECT id, name, cpf, phone, is_admin 
         FROM users 
         WHERE name LIKE ? 
         ORDER BY name ASC"
    );

    $like = "%" . $search_name . "%";
    $search_stmt->bind_param("s", $like);
    $search_stmt->execute();
    $search_result = $search_stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Usuários - Admin</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #87CEFA;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .panel-container {
            background: rgba(255,255,255,0.9);
            width: 650px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #007BFF;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 65%;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-form input[type="submit"] {
            padding: 10px 18px;
            background-color: #007BFF;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            margin-left: 8px;
        }

        .search-form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background-color: #007BFF;
            color: white;
            padding: 12px;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        .no-results {
            margin-top: 15px;
            font-size: 16px;
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .logout {
            margin-top: 8px;
        }
    </style>
</head>
<body>

    <div class="panel-container">
        <h2>Consulta de Usuários</h2>

        <form class="search-form" method="post">
            <input type="text" name="name" placeholder="Nome do usuário" 
                   value="<?= htmlspecialchars($search_name); ?>" required>
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
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $search_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['cpf']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= $row['is_admin'] ? 'Administrador' : 'Usuário'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p class="no-results">Nenhum usuário encontrado com o nome "<?= htmlspecialchars($search_name); ?>".</p>
        <?php endif; ?>

        <a href="dashboard_admin.php">Voltar ao Painel do Administrador</a>
        <br>
        <a class="logout" href="logout.php">Sair</a>
    </div>

</body>
</html>

<?php
if (isset($search_stmt)) $search_stmt->close();
$conn->close();
?>
