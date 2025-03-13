<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar o pedido para editar
    $stmt = $pdo->prepare("SELECT * FROM encomendas WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo "<div class='alert alert-danger text-center'>Pedido não encontrado!</div>";
        exit;
    }

    // Função para buscar os produtos de um pedido na tabela carrinho_data
    function getOrderProducts($pdo, $orderId)
    {
        $stmt = $pdo->prepare("SELECT carrinho_data FROM encomendas WHERE id = :pedido_id");
        $stmt->bindParam(':pedido_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_decode($result['carrinho_data'], true);
    }

    // Verificar se o formulário foi enviado para atualizar o pedido
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $endereco = $_POST['endereco'];
        $codigo_postal = $_POST['codigo_postal'];
        $data_nascimento = $_POST['data_nascimento'];

        // Atualizar os dados no banco de dados
        $stmt = $pdo->prepare("UPDATE encomendas SET nome = :nome, email = :email, endereco = :endereco, codigo_postal = :codigo_postal, data_nascimento = :data_nascimento WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':codigo_postal', $codigo_postal);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirecionar para a página de encomendas
        header("Location: encomendas.php");
        exit;
    }
} else {
    echo "<div class='alert alert-warning text-center'>ID do pedido não fornecido!</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

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


    <div class="container mt-5">

        <!-- FORMULÁRIO DE EDIÇÃO DO PEDIDO -->
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Editar Pedido</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($pedido['nome']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($pedido['email']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço:</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($pedido['endereco']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="codigo_postal" class="form-label">Código Postal:</label>
                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?php echo htmlspecialchars($pedido['codigo_postal']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($pedido['data_nascimento']); ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="encomendas.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success">Atualizar Pedido</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- PRODUTOS DA ENCOMENDA -->
        <div class="card shadow-lg mt-4">
            <div class="card-header bg-info text-white">
                <h4 class="text-center">Produtos da Encomenda</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <?php
                    $produtos = getOrderProducts($pdo, $pedido['id']); // Buscar produtos do pedido
                    $totalPedido = 0; // Inicializa a variável para armazenar o valor total

                    foreach ($produtos as $produto):
                        $produtoTotal = $produto['quantidade'] * $produto['preco']; // Calcula o total do produto
                        $totalPedido += $produtoTotal; // Adiciona o total do produto ao total da encomenda
                    ?>
                        <div class="col-12 col-md-6 mb-3">
                            <!-- Coluna 1: Imagem do Produto -->
                            <img src="<?php echo htmlspecialchars($produto['imagem_produto']); ?>" alt="Imagem do Produto" class="img-fluid img-thumbnail" style="max-width: 250px;">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <!-- Coluna 2: Detalhes do Produto -->
                            <ul class="list-unstyled">
                                <br><br>
                                <li><strong>Produto:</strong> <?php echo htmlspecialchars($produto['nome_produto']); ?></li>
                                <li><strong>Quantidade:</strong> <?php echo htmlspecialchars($produto['quantidade']); ?></li>
                                <li><strong>Valor Unitário:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></li>
                                <li><strong>Valor Total:</strong> R$ <?php echo number_format($produtoTotal, 2, ',', '.'); ?></li>
                                <li><strong>Tamanho:</strong> <?php echo htmlspecialchars($produto['tamanho']); ?></li>
                            </ul>

                            <button class="btn btn-danger btn-sm float-end excluir-produto"
                                data-pedido-id="<?php echo $pedido['id']; ?>"
                                data-produto-id="<?php echo $produto['id']; ?>"
                                onclick="confirmarExclusao(<?php echo $pedido['id']; ?>, <?php echo $produto['id']; ?>)">
                                Excluir Produto
                            </button>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                    <div class="text-end">
                        <h5><strong>Valor Total da Encomenda:</strong> R$ <?php echo number_format($totalPedido, 2, ',', '.'); ?></h5>
                    </div>
                </div>
                <!-- Exibir o valor total da encomenda -->

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Função para confirmar a exclusão do produto
        function confirmarExclusao(pedidoId, produtoId) {
            if (confirm("Tem certeza de que deseja excluir este produto da encomenda?")) {
                // Enviar requisição para o servidor via AJAX para excluir o produto
                const url = `excluir_produto.php?pedido_id=${pedidoId}&produto_id=${produtoId}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Produto excluído com sucesso!");
                            // Atualizar a tela para refletir a exclusão
                            location.reload(); // Recarrega a página para mostrar os produtos atualizados
                        } else {
                            alert("Erro ao excluir o produto.");
                        }
                    })
                    .catch(error => {
                        alert("Erro ao processar a solicitação.");
                        console.error(error);
                    });
            }
        }
    </script>

</body>

</html>