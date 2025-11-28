<?php
session_start();

// Verifica login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include('db.php');

$user_id = $_SESSION['user_id'];

// Busca medicamentos do usuário
$stmt = $conn->prepare("SELECT name, next_refill_date FROM medications WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alertas de Medicamentos</title>
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

        .medications-container {
            background: rgba(255,255,255,0.95);
            width: 520px;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 0 12px rgba(0,0,0,0.25);
        }

        h2 {
            margin-bottom: 20px;
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #007BFF;
            color: white;
            padding: 12px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        .danger {
            color: #d32f2f;
            font-weight: bold;
        }

        .warning {
            color: #ff9800;
            font-weight: bold;
        }

        .back-button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="medications-container">
        <h2>Alertas de Medicamentos</h2>

        <?php if ($result->num_rows > 0): ?>

            <table>
                <thead>
                    <tr>
                        <th>Medicamento</th>
                        <th>Próxima Reposição</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while ($row = $result->fetch_assoc()):
                        $name = htmlspecialchars($row["name"]);
                        $refill = htmlspecialchars($row["next_refill_date"]);

                        // Cálculo do alerta
                        $today = new DateTime();
                        $refill_date = new DateTime($refill);
                        $diff = $today->diff($refill_date)->days;

                        // Define cor do alerta
                        $class = "";
                        if ($refill_date < $today) {
                            $class = "danger"; // já passou da data
                        } elseif ($diff <= 3) {
                            $class = "danger";
                        } elseif ($diff <= 7) {
                            $class = "warning";
                        }
                ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td class="<?= $class ?>"><?= $refill ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>

            <p>Nenhum medicamento registrado.</p>

        <?php endif; ?>

        <button class="back-button" onclick="window.history.back()">Voltar</button>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
