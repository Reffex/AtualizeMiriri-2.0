<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

// Buscar clientes do usuário
    $usuario_id = $_SESSION['usuario_id'];
    $clientes = $conn->query("SELECT id, nome, cpf_cnpj FROM clientes WHERE usuario_id = $usuario_id");

// Lógica de inserção
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cliente_id = $_POST['cliente_id'];
        $descricao = $_POST['descricao'];
        $indexador = $_POST['indexador'];
        $periodicidade = $_POST['periodicidade'];
        $data_atualizar = $_POST['data_atualizar'];
        $data_alterar = $_POST['data_alterar'];
        $correcao1 = $_POST['correcao1'];
        $juros1 = $_POST['juros1'];
        $correcao2 = $_POST['correcao2'];
        $juros2 = $_POST['juros2'];
        $multa = $_POST['multa'];
        $honorarios = $_POST['honorarios'];
        $observacao = $_POST['observacao'];
        $dia_debito1 = $_POST['dia_debito1'];
        $dia_debito2 = $_POST['dia_debito2'];

        $stmt = $conn->prepare("INSERT INTO operacoes (
            cliente_id, descricao, indice, periodicidade, data_inicio, data_fim,
            correcao1, juros1, correcao2, juros2, multa, honorarios, observacao, dia_debito1, dia_debito2
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isssssddddddsii",
            $cliente_id, $descricao, $indexador, $periodicidade,
            $data_atualizar, $data_alterar,
            $correcao1, $juros1, $correcao2, $juros2,
            $multa, $honorarios, $observacao, $dia_debito1, $dia_debito2
        );
        $stmt->execute();

        header("Location: listar.php");
        exit;
    }
?>

<h2>Criando nova operação</h2>
<form method="POST" class="form-group">

    <label>Cliente:</label>
    <select name="cliente_id" class="form-control" required>
        <option value="">Selecione</option>
        <?php while ($c = $clientes->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= $c['nome'] ?> (<?= $c['cpf_cnpj'] ?>)</option>
        <?php endwhile; ?>
    </select>

    <label>Identificador da operação:</label>
    <input type="text" name="descricao" class="form-control" placeholder="" required>

    <div class="row">
        <div class="col">
            <label>Indexador:</label>
            <select name="indexador" class="form-control">
                <option>IPCA</option>
                <option>INPC</option>
                <option>SELIC</option>
            </select>
        </div>
        <div class="col">
            <label>Periodicidade:</label>
            <select name="periodicidade" class="form-control">
                <option>Diária</option>
                <option>Mensal</option>
                <option>Trimestral</option>
                <option>Semestral</option>
                <option>Anual</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <label>Atualizar até:</label>
            <input type="date" name="data_atualizar" class="form-control" required>
        </div>
        <div class="col">
            <label>Alterar taxas em:</label>
            <input type="date" name="data_alterar" class="form-control" required>
        </div>
    </div>

    <h5 class="mt-4">Taxas até a data de alteração:</h5>
    <div class="row">
        <div class="col">
            <label class="form-label">Dia do Débito</label>
            <input type="number" name="dia_debito1" class="form-control" min="1" max="31" value="1" required>
        </div>
        <div class="col">
            <label>Correção monetária(%):</label>
            <input type="number" step="0.01" name="correcao1" class="form-control" value="100">
        </div>
        <div class="col">
            <label>Juros nominais (%):</label>
            <input type="number" step="0.01" name="juros1" class="form-control" value="12">
        </div>
    </div>

    <h5 class="mt-4">Taxas após a data de alteração:</h5>
    <div class="row">
        <div class="col">
            <label class="form-label">Dia do Débito</label>
            <input type="number" name="dia_debito2" class="form-control" min="1" max="31" value="1" required>
        </div>
        <div class="col">
            <label>Correção monetária(%):</label>
            <input type="number" step="0.01" name="correcao2" class="form-control" value="100">
        </div>
        <div class="col">
            <label>Juros nominais (%):</label>
            <input type="number" step="0.01" name="juros2" class="form-control" value="12">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <label>Valor da multa:</label>
            <input type="number" step="0.01" name="multa" class="form-control">
        </div>
        <div class="col">
            <label>Honorários:</label>
            <input type="number" step="0.01" name="honorarios" class="form-control">
        </div>
    </div>

    <label class="mt-3">Observação:</label>
    <textarea name="observacao" class="form-control" rows="3"></textarea>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">✅ Criar operação</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </div>
    
</form>
