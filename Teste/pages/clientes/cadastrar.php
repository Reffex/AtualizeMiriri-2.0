<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario_id = $_SESSION['usuario_id'];
        $nome = $_POST['nome'];
        $cpf_cnpj = $_POST['cpf_cnpj'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];

        $stmt = $conn->prepare("INSERT INTO clientes (usuario_id, nome, cpf_cnpj, email, telefone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $usuario_id, $nome, $cpf_cnpj, $email, $telefone);
        $stmt->execute();

        header("Location: listar.php");
        exit;
    }
?>

<h2>Cadastrar Cliente</h2>
<form method="POST">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="text" name="cpf_cnpj" placeholder="CPF ou CNPJ">
    <input type="email" name="email" placeholder="E-mail">
    <input type="text" name="telefone" placeholder="Telefone">
    <button type="submit">Salvar</button>
</form>
