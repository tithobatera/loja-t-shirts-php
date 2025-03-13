<?php
session_start();

// Dados de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastrodb";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado e se os dados existem no $_POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Depuração: verifique o conteúdo de $_POST
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    // Verificando se as chaves estão definidas no array $_POST
    if (isset($_POST['nome'], $_POST['email'], $_POST['endereco'], $_POST['codigo_postal'], $_POST['data_nascimento'], $_POST['carrinho_data'])) {

        // Recebe os dados do formulário
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $endereco = $_POST['endereco'];
        $codigo_postal = $_POST['codigo_postal'];
        $data_nascimento = $_POST['data_nascimento'];
        $carrinho_data = $_POST['carrinho_data'];  // Dados do carrinho (JSON)

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Iniciar transação para garantir que ambas as operações sejam realizadas ou nenhuma delas
            $pdo->beginTransaction();

            // Inserção na tabela encomendas
            $sql_encomenda = "INSERT INTO encomendas (nome, email, endereco, codigo_postal, data_nascimento, carrinho_data)
                              VALUES (:nome, :email, :endereco, :codigo_postal, :data_nascimento, :carrinho_data)";

            $stmt_encomenda = $pdo->prepare($sql_encomenda);
            $stmt_encomenda->bindParam(':nome', $nome);
            $stmt_encomenda->bindParam(':email', $email);
            $stmt_encomenda->bindParam(':endereco', $endereco);
            $stmt_encomenda->bindParam(':codigo_postal', $codigo_postal);
            $stmt_encomenda->bindParam(':data_nascimento', $data_nascimento);
            $stmt_encomenda->bindParam(':carrinho_data', $carrinho_data);
            $stmt_encomenda->execute();

            // Recupera o ID do pedido recém inserido
            $pedido_id = $pdo->lastInsertId();

            // Processar os itens do carrinho e atualizar o estoque
            $itens_carrinho = json_decode($carrinho_data, true);  // Converte o JSON do carrinho para um array PHP

            foreach ($itens_carrinho as $item) {
                $produto_id = $item['id'];  // ID do produto
                $quantidade = $item['quantidade'];  // Quantidade do item no carrinho
                $tamanho = $item['tamanho'];  // Tamanho do item

                // Lógica para atualizar o estoque do tamanho específico e a quantidade total
                switch ($tamanho) {
                    case 'M':
                        $sql_estoque = "UPDATE estoque 
                                        SET tamanho_M = tamanho_M - :quantidade,
                                            quantidade_total = quantidade_total - :quantidade
                                        WHERE id = :id_produto AND tamanho_M >= :quantidade";
                        break;
                    case 'L':
                        $sql_estoque = "UPDATE estoque 
                                        SET tamanho_L = tamanho_L - :quantidade,
                                            quantidade_total = quantidade_total - :quantidade
                                        WHERE id = :id_produto AND tamanho_L >= :quantidade";
                        break;
                    case 'XL':
                        $sql_estoque = "UPDATE estoque 
                                        SET tamanho_XL = tamanho_XL - :quantidade,
                                            quantidade_total = quantidade_total - :quantidade
                                        WHERE id = :id_produto AND tamanho_XL >= :quantidade";
                        break;
                    case 'P':
                        $sql_estoque = "UPDATE estoque 
                                        SET tamanho_P = tamanho_P - :quantidade,
                                            quantidade_total = quantidade_total - :quantidade
                                        WHERE id = :id_produto AND tamanho_P >= :quantidade";
                        break;
                    default:
                        echo "Erro: Tamanho desconhecido!";
                        exit;
                }

                // Preparando a consulta
                $stmt_estoque = $pdo->prepare($sql_estoque);
                $stmt_estoque->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
                $stmt_estoque->bindParam(':id_produto', $produto_id, PDO::PARAM_INT);
                $stmt_estoque->execute();

                // Verifica se a quantidade foi atualizada corretamente
                if ($stmt_estoque->rowCount() == 0) {
                    // Se a quantidade não foi atualizada (provavelmente estoque insuficiente), desfaz a transação
                    $pdo->rollBack();
                    echo "Erro: Estoque insuficiente para o produto (tamanho: $tamanho): " . $item['nome_produto'];
                    exit;
                }
            }


            // Commit da transação se tudo estiver certo
            $pdo->commit();
            // Redirecionar para a página de sucesso
            header("Location: success.php");
            exit; // Importante para garantir que o código pare por aqui após o redirecionamento

        } catch (PDOException $e) {
            // Caso haja erro, desfaz a transação
            $pdo->rollBack();
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Erro: Dados obrigatórios não recebidos!";
    }
} else {
    echo "Erro: Formulário não enviado corretamente!";
}
