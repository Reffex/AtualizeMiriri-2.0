<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $operacao_id = $_GET['operacao_id'] ?? 0;

// Verifica se operação existe
    $stmt = $conn->prepare("
        SELECT o.id, o.descricao, c.nome AS cliente_nome
        FROM operacoes o
        JOIN clientes c ON c.id = o.cliente_id
        WHERE o.id = ?
    ");
    $stmt->bind_param("i", $operacao_id);
    $stmt->execute();
    $op = $stmt->get_result()->fetch_assoc();

    if (!$op) {
        die("Operação não encontrada.");
    }

// Inserção do lançamento
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $descricao = $_POST['descricao'];
        $data = $_POST['data'];
        $tipo = $_POST['tipo'];
        $valor = $_POST['valor'];

        $stmt = $conn->prepare("INSERT INTO lancamentos (operacao_id, data_lancamento, descricao, tipo, valor) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssd", $operacao_id, $data, $descricao, $tipo, $valor);
        $stmt->execute();

        header("Location: lancamentos.php?id=$operacao_id");
        exit;
    }
?>

<h2>Novo Lançamento - <?= $op['descricao'] ?></h2>
<p><strong>Cliente:</strong> <?= $op['cliente_nome'] ?></p>

<form method="POST">
    <label>Data do Lançamento:</label>
    <input type="date" name="data" required class="form-control">

    <label>Descrição:</label>
    <input type="text" name="descricao" required class="form-control">

    <label>Tipo:</label>
    <select name="tipo" class="form-control">
        <option>Débito</option>
        <option>Crédito</option>
    </select>

    <label>Valor:</label>
    <input type="number" step="0.01" name="valor" required class="form-control">

    <div class="mt-3">
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="lancamentos.php?id=<?= $operacao_id ?>" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
