<?php
require_once '../../includes/auto_check.php';
require_once '../../includes/connect_app.php';

$clientes = $mysqli->query("SELECT * FROM clientes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <script>
        setTimeout(function() {
            const msg = document.getElementById('alerta-msg');
            if (msg) {
                msg.style.transition = 'opacity 1s';
                msg.style.opacity = 0;
                setTimeout(() => msg.remove(), 1000);
            }
        }, 3000);
    </script>
</head>

<body>
        <div class="form-box">
            <h1 class="clientes-titulo">Clientes</h1>

            <?php if (isset($_GET['sucesso'])): ?>
                <div id="alerta-msg">
                    <?php if ($_GET['sucesso'] == 1): ?>
                        Cliente cadastrado com sucesso!
                    <?php elseif ($_GET['sucesso'] == 3): ?>
                        Cliente excluído com sucesso!
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="botao-centralizado">
                <a href="cadastrar.php">
                    <button class="login botao-criar">Criar Novo Cliente</button>
                </a>
            </div>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Documento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($clientes->num_rows > 0): ?>
                        <?php while ($cliente = $clientes->fetch_assoc()): ?>
                            <tr class="linha-borda">
                                <td><?= htmlspecialchars($cliente['nome']) ?></td>
                                <td><?= htmlspecialchars($cliente['documento']) ?></td>
                                <td>
                                    <a href="editar.php?id=<?= $cliente['id'] ?>" title="Editar" class="link-sem-decoracao">
                                        <i class='bx bx-edit icone-acao editar'></i>
                                    </a>
                                    <a href="excluir.php?id=<?= $cliente['id'] ?>" onclick="return confirm('Deseja excluir?')" title="Excluir" class="link-sem-decoracao">
                                        <i class='bx bx-trash icone-acao excluir'></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-left" style="padding:20px;">Nenhum cliente cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="register-link">
                <p><a href="../../index.php">Voltar para o menu</a></p>
            </div>
        </div>
</body>

</html>
