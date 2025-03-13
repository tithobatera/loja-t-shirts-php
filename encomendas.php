<?php
require 'config.php'; // Conexão com o banco de dados
session_start(); // Inicia a sessão para acessar as variáveis de sessão

// Função para buscar os pedidos do banco de dados
function getOrders($pdo)
{
    $stmt = $pdo->query("SELECT * FROM encomendas"); // Supondo que a tabela se chame "encomendas"
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para buscar os produtos de um pedido na tabela carrinho_data
function getOrderProducts($pdo, $orderId)
{
    // Busca os dados do carrinho relacionados ao pedido
    $stmt = $pdo->prepare("SELECT carrinho_data FROM encomendas WHERE id = :pedido_id");
    $stmt->bindParam(':pedido_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Decodifica o JSON armazenado no campo carrinho_data
    return json_decode($result['carrinho_data'], true);
}

// Verificar se o pedido foi aprovado ou excluído
if (isset($_GET['action'])) {
    $id = $_GET['id'];

    if ($_GET['action'] === 'aprovar') {
        // Buscar os dados da encomenda
        $stmt = $pdo->prepare("SELECT * FROM encomendas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        // Inserir os dados na tabela encomendas_finalizadas
        $stmt = $pdo->prepare("INSERT INTO encomendas_finalizadas (id, nome, email, data_pedido, status, carrinho_data) 
                               VALUES (:id, :nome, :email, :data_pedido, :status, :carrinho_data)");
        $stmt->bindParam(':id', $pedido['id']);
        $stmt->bindParam(':nome', $pedido['nome']);
        $stmt->bindParam(':email', $pedido['email']);
        $stmt->bindParam(':data_pedido', $pedido['data_pedido']);
        $stmt->bindParam(':status', $pedido['status']);
        $stmt->bindParam(':carrinho_data', $pedido['carrinho_data']);
        $stmt->execute();

        // Excluir o pedido da tabela encomendas
        $stmt = $pdo->prepare("DELETE FROM encomendas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Definir uma variável de sessão para mostrar o alerta
        $_SESSION['pedido_aprovado'] = true;

        // Redirecionar para a página de encomendas
        header("Location: encomendas.php"); // Redireciona para a mesma página
        exit;
    } elseif ($_GET['action'] === 'excluir') {
        $stmt = $pdo->prepare("DELETE FROM encomendas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: encomendas.php"); // Redireciona após a ação
        exit;
    }
}

$encomendas = getOrders($pdo); // Buscar todos os pedidos
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encomendas</title>
    <!-- Incluindo o CDN do Bootstrap -->
    <script src="../assets/js/color-modes.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3JzLr34Ck7jS5l7JrF1RtAdy1uVmC2eU8b3FkF65w5mqNx+EXyHHzp+5F" crossorigin="anonymous">

    <style>
        /* Reduzir o tamanho da tabela */
        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
            font-size: 0.85rem;
        }

        /* Opcional: Ajustar a largura das colunas */
        .table th,
        .table td {
            word-wrap: break-word;
            max-width: 150px;
        }
    </style>
</head>

<body class="bg-light">
    <!-- CABEÇALHO -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Administração</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="encomendas.php">Encomendas</a></li>
                    <li class="nav-item"><a class="nav-link" href="adicionar_produto.php">Estoque</a></li>
                    <li class="nav-item"><a class="nav-link" href="encomendas_finalizadas.php">Pedidos finalizados</a></li>
                </ul>
                <form action="logout.php" method="POST">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </nav>


    <!-- Exibir o alerta de "Pedido Aprovado" -->
    <?php if (isset($_SESSION['pedido_aprovado'])): ?>
        <script>
            alert('Pedido Aprovado com Sucesso!');
        </script>
        <?php unset($_SESSION['pedido_aprovado']); ?>
    <?php endif; ?>

    <div class="container-fluid mt-4">
        <h1 class="mb-4 text-center">Gerenciar Encomendas</h1>

        <div class="container-fluid mt-4">
            <form action="encomendas.php" method="POST">
                <?php foreach ($encomendas as $encomenda): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>ID:</strong> <?php echo htmlspecialchars($encomenda['id']); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Nome:</strong> <?php echo htmlspecialchars($encomenda['nome']); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($encomenda['email']); ?>
                                </div>
                                <div class="col-md-2">
                                    <strong>Data:</strong> <?php echo htmlspecialchars($encomenda['data_pedido']); ?>
                                </div>
                                <div class="col-md-2">
                                    <a href="editar_pedido.php?id=<?php echo $encomenda['id']; ?>" class="btn btn-warning btn-sm mb-2">Ver pedido</a>
                                    <a href="?action=aprovar&id=<?php echo $encomenda['id']; ?>" class="btn btn-success btn-sm mb-2">Aprovar</a>
                                    <a href="?action=excluir&id=<?php echo $encomenda['id']; ?>" class="btn btn-danger btn-sm mb-2">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb4I4JkXz4B9b1B9Hqbm6k4a0pP2l8J6iXf7dh9bCwF6glGEk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0x1+8zNUINwczG2w6D9mePq0e6c5eTzZZ/JZmz98vRBBvpcO" crossorigin="anonymous"></script>

    <!-- Incluindo o JavaScript do Bootstrap e o Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb4I4JkXz4B9b1B9Hqbm6k4a0pP2l8J6iXf7dh9bCwF6glGEk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0x1+8zNUINwczG2w6D9mePq0e6c5eTzZZ/JZmz98vRBBvpcO" crossorigin="anonymous"></script>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        // Função para confirmar a exclusão do pedido
        function confirmDelete(id) {
            if (confirm('Tem certeza de que deseja excluir este pedido?')) {
                window.location.href = '?action=excluir&id=' + id;
                return true;
            }
            return false;
        }
    </script>
    <script>
        // Função para confirmar a exclusão de um pedido
        function confirmDelete(id) {
            if (confirm('Tem certeza de que deseja excluir este pedido?')) {
                window.location.href = '?action=excluir&id=' + id; // Se o usuário confirmar, redireciona para a exclusão
                return true;
            }
            return false; // Se o usuário cancelar, nada acontece
        }
    </script>

</body>

</html>