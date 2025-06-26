<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $cpf_cnpj = $_POST['cpf_cnpj'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];

        $stmt = $conn->prepare("UPDATE clientes SET nome=?, cpf_cnpj=?, email=?, telefone=? WHERE id=? AND usuario_id=?");
        $stmt->bind_param("ssssii", $nome, $cpf_cnpj, $email, $telefone, $id, $usuario_id);
        $stmt->execute();

        header("Location: listar.php");
        exit;
    } else {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE id=? AND usuario_id=?");
        $stmt->bind_param("ii", $id, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cliente = $result->fetch_assoc();
    }
?>

<h2>Editar Cliente</h2>
<form method="POST">
    <input type="text" name="nome" value="<?= $cliente['nome'] ?>" required>
    <input type="text" name="cpf_cnpj" value="<?= $cliente['cpf_cnpj'] ?>">
    <input type="email" name="email" value="<?= $cliente['email'] ?>">
    <input type="text" name="telefone" value="<?= $cliente['telefone'] ?>">
    <button type="submit">Salvar</button>
</form>
