<?php
require 'config.php';

// Verifica se os parâmetros necessários estão presentes
if (isset($_GET['pedido_id']) && isset($_GET['produto_id'])) {
    $pedidoId = $_GET['pedido_id'];
    $produtoId = $_GET['produto_id'];

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
    $updatedProducts = array_filter($produtos, function ($produto) use ($produtoId) {
        return $produto['id'] !== $produtoId;  // Exclui o produto com o ID fornecido
    });

    // Recriar o campo carrinho_data com os produtos atualizados
    $updatedCarrinhoData = json_encode(array_values($updatedProducts));

    // Atualizar o banco de dados com os produtos excluídos
    $stmt = $pdo->prepare("UPDATE encomendas SET carrinho_data = :carrinho_data WHERE id = :pedido_id");
    $stmt->bindParam(':carrinho_data', $updatedCarrinhoData);
    $stmt->bindParam(':pedido_id', $pedidoId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Retorna sucesso
        echo json_encode(['success' => true]);
    } else {
        // Retorna erro
        echo json_encode(['success' => false]);
    }
} else {
    // Parâmetros ausentes
    echo json_encode(['success' => false]);
}
?>
