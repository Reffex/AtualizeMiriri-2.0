<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    if (!isset($_GET['id'])) {
        echo "ID da operação não especificado.";
        exit;
    }

    $id = $_GET['id'];

// Busca a operação
    $stmt = $conn->prepare("SELECT o.*, c.nome AS cliente_nome FROM operacoes o JOIN clientes c ON o.cliente_id = c.id WHERE o.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $op = $stmt->get_result()->fetch_assoc();

    if (!$op) {
        echo "Operação não encontrada.";
        exit;
    }

// Parâmetros
    $indice_nome = $op['indice'];
    $data_inicio = new DateTime($op['data_inicio']);
    $data_fim = new DateTime($op['data_fim']);
    $valor_base = 1000;

    $intervalo = new DateInterval('P1M');
    $periodo = new DatePeriod($data_inicio, $intervalo, $data_fim);

    $valor_corrigido = $valor_base;
    $detalhes = [];

    foreach ($periodo as $mes) {
        $data_ref = $mes->format('Y-m-01');

        $stmt = $conn->prepare("SELECT valor FROM indices WHERE nome = ? AND data_ref = ?");
        $stmt->bind_param("ss", $indice_nome, $data_ref);
        $stmt->execute();
        $res = $stmt->get_result();
        $indice = $res->fetch_assoc();

        $indice_valor = $indice['valor'] ?? 0;
        $corrigido_mes = $valor_corrigido * (1 + $indice_valor);

        $detalhes[] = [
            'data' => $data_ref,
            'indice' => number_format($indice_valor * 100, 2, ',', '') . '%',
            'valor_anterior' => number_format($valor_corrigido, 2, ',', '.'),
            'valor_corrigido' => number_format($corrigido_mes, 2, ',', '.')
        ];

        $valor_corrigido = $corrigido_mes;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Extrato da Operação</title>
    <link rel="stylesheet" href="../../assets/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h2>📄 Extrato - <?= htmlspecialchars($op['descricao']) ?></h2>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($op['cliente_nome']) ?></p>
    <p><strong>Índice aplicado:</strong> <?= strtoupper($indice_nome) ?></p>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Mês</th>
                <th>Índice (%)</th>
                <th>Valor Anterior</th>
                <th>Valor Corrigido</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalhes as $linha): ?>
                <tr>
                    <td><?= date('m/Y', strtotime($linha['data'])) ?></td>
                    <td><?= $linha['indice'] ?></td>
                    <td>R$ <?= $linha['valor_anterior'] ?></td>
                    <td>R$ <?= $linha['valor_corrigido'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="mt-3"><strong>Valor final atualizado:</strong> R$ <?= number_format($valor_corrigido, 2, ',', '.') ?></p>

    <a href="../operacoes/detalhes.php?id=<?= $id ?>" class="btn btn-secondary">🔙 Voltar</a>
</body>
</html>
