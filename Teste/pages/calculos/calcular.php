<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $usuario_id = $_SESSION['usuario_id'];

// Buscar todas as operações do usuário
    $sql = "SELECT o.id, o.descricao, c.nome AS cliente_nome
        FROM operacoes o
        INNER JOIN clientes c ON c.id = o.cliente_id
        WHERE c.usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $ops = $stmt->get_result();
?>

<h2>Calcular Atualização Monetária</h2>

<form method="GET" action="extrato.php">
    <label>Escolha uma operação:</label>
    <select name="id" required>
        <?php while ($op = $ops->fetch_assoc()): ?>
            <option value="<?= $op['id'] ?>">
                <?= $op['cliente_nome'] ?> - <?= $op['descricao'] ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Calcular</button>
</form>
