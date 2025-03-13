<?php
require 'config.php'; // Conexão com o banco de dados

// Função para buscar os pedidos finalizados do banco de dados
function getFinalizedOrders($pdo)
{
    $stmt = $pdo->query("SELECT id, nome, email, data_pedido, status, carrinho_data, created_at FROM encomendas_finalizadas"); // Atualizado para selecionar todos os campos necessários
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$encomendasFinalizadas = getFinalizedOrders($pdo); // Buscar todos os pedidos finalizados
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encomendas Finalizadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
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


    <div class="container mt-4">
        <h1 class="mb-4 text-center">Pedidos Finalizados</h1>

        <div class="row">
            <?php foreach ($encomendasFinalizadas as $encomenda): ?>
                <div class="col-md-4 mb-4">
                    <!-- Card para cada encomenda -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title">Pedido #<?php echo htmlspecialchars($encomenda['id']); ?></h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nome:</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($encomenda['nome']); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($encomenda['email']); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Data do Pedido:</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($encomenda['data_pedido']); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status:</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($encomenda['status']); ?></p>
                                </div>

                                <!-- Botão para abrir o Modal -->
                                <div class="mb-3">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#carrinhoModal<?php echo $encomenda['id']; ?>">
                                        Ver Carrinho de Compras
                                    </button>
                                </div>

                                <!-- Modal -->
                                <!-- Modal -->
                                <div class="modal fade" id="carrinhoModal<?php echo $encomenda['id']; ?>" tabindex="-1" aria-labelledby="carrinhoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="carrinhoModalLabel">Carrinho de Compras - Pedido #<?php echo htmlspecialchars($encomenda['id']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                // Decodificar o carrinho de compras
                                                $carrinhoData = json_decode($encomenda['carrinho_data'], true);
                                                if (!empty($carrinhoData)) {
                                                    // Iniciar um layout organizado em forma de lista ou tabela
                                                    echo '<div class="row">';
                                                    foreach ($carrinhoData as $produto) {
                                                        echo '<div class="col-12 col-md-6 mb-4">';
                                                        echo '<div class="card shadow-sm">';
                                                        echo '<div class="card-body">';
                                                        echo "<h5 class='card-title'>" . htmlspecialchars($produto['nome_produto']) . "</h5>";
                                                        echo "<p><strong>Preço:</strong> R$ " . number_format($produto['preco'], 2, ',', '.') . "</p>";
                                                        echo "<p><strong>Tamanho:</strong> " . htmlspecialchars($produto['tamanho']) . "</p>";
                                                        echo "<p><strong>Quantidade:</strong> " . htmlspecialchars($produto['quantidade']) . "</p>";
                                                        echo "<p><strong>Imagem:</strong><br> 
                              <img src='" . htmlspecialchars($produto['imagem_produto']) . "' alt='Imagem do produto' class='img-fluid produto-imagem' widht='200px' height='250px'>";
                                                        echo "</div></div></div>";
                                                        
                                                    }
                                                    echo '</div>';
                                                } else {
                                                    echo '<p class="text-muted">Nenhum item no carrinho.</p>';
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="mb-3">
                                    <label class="form-label">Data da aprovação:</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($encomenda['created_at']); ?></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- JS do Bootstrap (para funcionalidades interativas) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>

</html>