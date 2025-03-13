<?php
require 'config.php'; // Conexão com o banco de dados
header('Content-Type: application/json'); // Define o cabeçalho como JSON

// Habilita exibição de erros para depuração (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtém os parâmetros de GET ou POST
$id_produto = $_REQUEST['id_produto'] ?? null;
$tamanho = $_REQUEST['tamanho'] ?? null;

// Verifica se os parâmetros foram recebidos corretamente
if (!$id_produto || !$tamanho) {
    echo json_encode(['error' => 'Parâmetros inválidos']);
    exit;
}

// Mapeia o tamanho para a coluna correspondente no banco de dados
$tamanho_coluna = [
    'P' => 'tamanho_P',
    'M' => 'tamanho_M',
    'L' => 'tamanho_L',
    'XL' => 'tamanho_XL'
];

if (!array_key_exists($tamanho, $tamanho_coluna)) {
    echo json_encode(['error' => 'Tamanho inválido']);
    exit;
}

$coluna_tamanho = $tamanho_coluna[$tamanho];

// Prepara a consulta para buscar o estoque do tamanho e a quantidade total
$sql = "SELECT $coluna_tamanho AS estoque_disponivel, quantidade_total FROM estoque WHERE id = :id_produto";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);

if ($stmt->execute()) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'estoque' => (int) $result['estoque_disponivel'],
            'quantidade_total' => (int) $result['quantidade_total']
        ]);
    } else {
        echo json_encode(['error' => 'Produto não encontrado']);
    }
} else {
    echo json_encode(['error' => 'Erro na consulta ao banco de dados']);
}
?>
