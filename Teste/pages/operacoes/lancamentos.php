<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $operacao_id = $_GET['id'] ?? 0;

// Buscar operação
    $sql = "SELECT o.*, c.nome AS cliente_nome
        FROM operacoes o
        JOIN clientes c ON c.id = o.cliente_id
        WHERE o.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $operacao_id);
    $stmt->execute();
    $op = $stmt->get_result()->fetch_assoc();

    if (!$op) {
        die("Operação não encontrada.");
}

// Buscar lançamentos
    $lancamentos = $conn->query("SELECT * FROM lancamentos WHERE operacao_id = $operacao_id ORDER BY data_lancamento");
?>

<h2>Lançamentos da Operação: <?= $op['descricao'] ?></h2>
<p><strong>Cliente:</strong> <?= $op['cliente_nome'] ?></p>

<a href="novo_lancamento.php?operacao_id=<?= $operacao_id ?>" class="btn btn-primary">➕ Novo Lançamento</a>
<hr>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>Data</th>
        <th>Descrição</th>
        <th>Tipo</th>
        <th>Valor</th>
    </tr>
    <?php while ($l = $lancamentos->fetch_assoc()): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($l['data_lancamento'])) ?></td>
            <td><?= $l['descricao'] ?></td>
            <td><?= $l['tipo'] ?></td>
            <td>R$ <?= number_format($l['valor'], 2, ',', '.') ?></td>
        </tr>
    <?php endwhile; ?>
</table>
