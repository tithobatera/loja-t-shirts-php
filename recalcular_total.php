<?php
require 'config.php';

// Verifica se o pedido ID foi passado
if (isset($_GET['pedido_id'])) {
    $pedidoId = $_GET['pedido_id'];

    // Função para buscar os produtos de um pedido
    function getOrderProducts($pdo, $pedidoId)
    {
        $stmt = $pdo->prepare("SELECT carrinho_data FROM encomendas WHERE id = :pedido_id");
        $stmt->bindParam(':pedido_id', $pedidoId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_decode($result['carrinho_data'], true);
    }

    // Buscar os produtos do pedido
    $produtos = getOrderProducts($pdo, $pedidoId);
    $totalPedido = 0;

    // Calcula o total da encomenda
    foreach ($produtos as $produto) {
        $totalPedido += $produto['quantidade'] * $produto['preco'];
    }

    // Retorna o valor total da encomenda em formato JSON
    echo json_encode(['success' => true, 'total' => $totalPedido]);
} else {
    echo json_encode(['success' => false]);
}
?>
