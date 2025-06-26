<?php
    include 'includes/auth_check.php';
    include 'connect.php';

    $usuario_id = $_SESSION['usuario_id'];

// Buscar nome do usuário
    $user_query = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $user_query->bind_param("i", $usuario_id);
    $user_query->execute();
    $usuario = $user_query->get_result()->fetch_assoc();

// Últimos clientes
    $clientes = $conn->query("SELECT nome FROM clientes WHERE usuario_id = $usuario_id ORDER BY id DESC LIMIT 5");

// Últimas operações
    $operacoes = $conn->query("
        SELECT o.descricao, c.nome AS cliente_nome
        FROM operacoes o
        JOIN clientes c ON c.id = o.cliente_id
        WHERE c.usuario_id = $usuario_id
        ORDER BY o.id DESC LIMIT 5
    ");
?>

<h2>Olá, <?= $usuario['nome'] ?> 👋</h2>

<hr>
<h3>📋 Últimos Clientes</h3>
<a href="pages/clientes/listar.php">🔎 Ver todos os clientes</a>

<hr>
<h3>💼 Últimas Operações</h3>
<a href="pages/operacoes/listar.php">🔎 Ver todas as operações</a>

<hr>
<h3>🚀 Ações Rápidas</h3>
<a href="pages/clientes/cadastrar.php">➕ Cadastrar Cliente</a><br>
<a href="pages/operacoes/criar.php">➕ Criar Nova Operação</a><br>
<a href="pages/calculos/calcular.php">📈 Calcular Atualização</a><br>
<a href="logout.php">🔒 Sair</a>
