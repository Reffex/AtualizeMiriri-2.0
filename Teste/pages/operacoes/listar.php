<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $usuario_id = $_SESSION['usuario_id'];

    $sql = "
        SELECT o.id, o.descricao, o.indice, o.valor, o.data_inicio, o.data_fim, c.nome AS cliente_nome
        FROM operacoes o
        JOIN clientes c ON c.id = o.cliente_id
        WHERE c.usuario_id = $usuario_id
        ORDER BY o.id DESC
    ";
    $resultado = $conn->query($sql);
?>

<h2>Operações Cadastradas</h2>
<a href="criar.php">+ Nova Operação</a>
<table border="1">
    <tr>
        <th>Cliente</th><th>Descrição</th><th>Valor</th><th>Início</th><th>Fim</th><th>Índice</th><th>Ações</th>
    </tr>
    <?php while($op = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $op['cliente_nome'] ?></td>
            <td><?= $op['descricao'] ?></td>
            <td>R$ <?= number_format($op['valor'], 2, ',', '.') ?></td>
            <td><?= $op['data_inicio'] ?></td>
            <td><?= $op['data_fim'] ?></td>
            <td><?= $op['indice'] ?></td>
            <td>
                <a href="detalhes.php?id=<?= $op['id'] ?>">Detalhes</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
