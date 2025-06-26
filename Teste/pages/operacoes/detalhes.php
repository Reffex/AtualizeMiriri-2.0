<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $id = $_GET['id'] ?? 0;

// Buscar operação e cliente
    $sql = "
        SELECT o.*, c.nome AS cliente_nome, c.cpf_cnpj
        FROM operacoes o
        JOIN clientes c ON c.id = o.cliente_id
        WHERE o.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $op = $stmt->get_result()->fetch_assoc();

    if (!$op) {
        die("Operação não encontrada.");
    }

// Buscar lançamentos vinculados
    $lancamentos = $conn->query("SELECT * FROM lancamentos WHERE operacao_id = $id ORDER BY data_lancamento");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Operação</title>
    <link rel="stylesheet" href="../../assets/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2>📋 Detalhes da Operação</h2>

<div class="card mt-3 mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($op['descricao']) ?></h5>
        <p class="card-text">
            <strong>Cliente:</strong> <?= htmlspecialchars($op['cliente_nome']) ?> (<?= $op['cpf_cnpj'] ?>)<br>
            <strong>Índice:</strong> <?= $op['indice'] ?> | <strong>Periodicidade:</strong> <?= $op['periodicidade'] ?><br>
            <strong>Data Inicial:</strong> <?= date('d/m/Y', strtotime($op['data_inicio'])) ?><br>
            <strong>Data de Alteração:</strong> <?= date('d/m/Y', strtotime($op['data_fim'])) ?><br><br>

            <strong>Correção até alteração:</strong> <?= number_format($op['correcao1'] * 100, 2, ',', '.') ?>% |
            <strong>Juros até alteração:</strong> <?= number_format($op['juros1'] * 100, 2, ',', '.') ?>% |
            <strong>Atualizar até:</strong> <?= date('d/m/Y', strtotime($op['data_inicio'])) ?><br>
            <strong>Correção após alteração:</strong> <?= number_format($op['correcao2'] * 100, 2, ',', '.') ?>% |
            <strong>Juros após alteração:</strong> <?= number_format($op['juros2'] * 100, 2, ',', '.') ?>% |
            <strong>Alterar taxas em:</strong> <?= date('d/m/Y', strtotime($op['data_fim'])) ?><br><br>


            <strong>Multa:</strong> <?= number_format($op['multa'], 2, ',', '.') ?>% |
            <strong>Honorários:</strong> R$ <?= number_format($op['honorarios'], 2, ',', '.') ?><br><br>

            <strong>Observação:</strong> <?= nl2br(htmlspecialchars($op['observacao'])) ?>
        </p>
    </div>
</div>

<!-- Lançamentos vinculados -->
<h4>📄 Lançamentos</h4>

<a href="novo_lancamento.php?operacao_id=<?= $op['id'] ?>" class="btn btn-primary btn-sm mb-3">➕ Novo lançamento</a>

<?php if ($lancamentos->num_rows > 0): ?>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($l = $lancamentos->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($l['data_lancamento'])) ?></td>
                    <td><?= htmlspecialchars($l['descricao']) ?></td>
                    <td><?= $l['tipo'] ?></td>
                    <td>R$ <?= number_format($l['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-muted">Nenhum lançamento registrado ainda.</p>
<?php endif; ?>

<a href="listar.php" class="btn btn-secondary mt-4">🔙 Voltar</a>

</body>
</html>
