<?php
    include '../../includes/auth_check.php';
    include '../../connect.php';

    $usuario_id = $_SESSION['usuario_id'];
    $result = $conn->query("SELECT * FROM clientes WHERE usuario_id = $usuario_id");
?>

<h2>Clientes Cadastrados</h2>
<a href="cadastrar.php">+ Novo Cliente</a>
<table border="1">
    <tr>
        <th>Nome</th><th>CPF/CNPJ</th><th>Email</th><th>Telefone</th><th>Ações</th>
    </tr>
    <?php while($c = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $c['nome'] ?></td>
            <td><?= $c['cpf_cnpj'] ?></td>
            <td><?= $c['email'] ?></td>
            <td><?= $c['telefone'] ?></td>
            <td>
                <a href="editar.php?id=<?= $c['id'] ?>">Editar</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
