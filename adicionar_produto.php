<?php
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

session_start(); // Inicia a sessão

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Adicionar produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["productId"])) {
    $nome = $_POST["productName"];
    $descricao = $_POST["productDescription"];
    $tamanho_p = $_POST["productQuantityP"];
    $tamanho_m = $_POST["productQuantityM"];
    $tamanho_l = $_POST["productQuantityL"];
    $tamanho_xl = $_POST["productQuantityXL"];
    $quantidade_total = $tamanho_p + $tamanho_m + $tamanho_l + $tamanho_xl;
    $valor = $_POST["productPrice"];

    // Verificar se a pasta 'uploads' existe e criar se necessário
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Processar o arquivo de imagem
    if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES["productImage"]["tmp_name"];
        $imageName = basename($_FILES["productImage"]["name"]);
        $target_file = $target_dir . $imageName;
        $imageType = $_FILES["productImage"]["type"];

        if (move_uploaded_file($imageTmpName, $target_file)) {
            $sql = "INSERT INTO estoque (nome, descricao, tamanho_p, tamanho_m, tamanho_l, tamanho_xl, quantidade_total, valor)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiiiiid", $nome, $descricao, $tamanho_p, $tamanho_m, $tamanho_l, $tamanho_xl, $quantidade_total, $valor);

            if ($stmt->execute()) {
                $id_estoque = $stmt->insert_id;
                $sql_file = "INSERT INTO estoque_files (id_estoque, nome_arquivo, tipo_arquivo, caminho_arquivo)
                             VALUES (?, ?, ?, ?)";
                $stmt_file = $conn->prepare($sql_file);
                $stmt_file->bind_param("isss", $id_estoque, $imageName, $imageType, $target_file);
                $stmt_file->execute();
                $stmt_file->close();
            }
            $stmt->close();
        }
    }
}

// Atualizar produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["productId"])) {
    $id_produto = $_POST["productId"];
    $nome = $_POST["productName"];
    $descricao = $_POST["productDescription"];
    $tamanho_p = $_POST["productQuantityP"];
    $tamanho_m = $_POST["productQuantityM"];
    $tamanho_l = $_POST["productQuantityL"];
    $tamanho_xl = $_POST["productQuantityXL"];
    $valor = $_POST["productPrice"];
    $quantidade_total = $tamanho_p + $tamanho_m + $tamanho_l + $tamanho_xl;

    // Query para atualizar os dados do produto
    $sql = "UPDATE estoque SET nome = ?, descricao = ?, tamanho_p = ?, tamanho_m = ?, tamanho_l = ?, tamanho_xl = ?, quantidade_total = ?, valor = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiiiidi", $nome, $descricao, $tamanho_p, $tamanho_m, $tamanho_l, $tamanho_xl, $quantidade_total, $valor, $id_produto);

    if ($stmt->execute()) {
        // Redirecionar para a página 'adicionar_produto.php' após sucesso
        header("Location: adicionar_produto.php"); // Redirecionamento para a página
        exit; // Interrompe a execução do script após o redirecionamento
    } else {
        // Caso haja erro na execução
        echo "Erro ao atualizar o produto.";
    }

    $stmt->close();
}

// Excluir produto
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];
    $sql = "SELECT caminho_arquivo FROM estoque_files WHERE id_estoque = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($caminho_arquivo);
    $stmt->fetch();

    if ($caminho_arquivo && file_exists($caminho_arquivo)) {
        unlink($caminho_arquivo);
    }

    $sql = "DELETE FROM estoque_files WHERE id_estoque = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();

    $sql_product = "DELETE FROM estoque WHERE id = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param("i", $id_produto);
    $stmt_product->execute();

    $stmt_product->close();
    $stmt->close();
}

// Carregar todos os produtos
$sql = "SELECT * FROM estoque";
$result = $conn->query($sql);
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

        /* Ajustar a largura das colunas */
        .form-control {
            max-width: 350px;
            margin-bottom: 10px;
        }

        .card {
            margin-bottom: 20px;
        }
    </style>
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

    <div class="container mt-6">
        <br>
        <h2 class="mb-4">Estoque de Produtos</h2>

        <!-- Botão Novo Produto -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Novo Produto</button>

        <div class="container">
            <div class="row">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-3"> <!-- Mudança para tornar responsivo -->
                        <form action="adicionar_produto.php" method="POST" enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Imagem do Produto -->
                                        <div class="col-12 mb-3"> <!-- Mudança para garantir imagem ocupando toda a largura -->
                                            <?php
                                            $sql_image = "SELECT caminho_arquivo FROM estoque_files WHERE id_estoque = ?";
                                            $stmt_image = $conn->prepare($sql_image);
                                            $stmt_image->bind_param("i", $row['id']);
                                            $stmt_image->execute();
                                            $stmt_image->store_result();
                                            $stmt_image->bind_result($caminho_arquivo);
                                            $stmt_image->fetch();
                                            if ($caminho_arquivo) {
                                                echo '<img src="' . $caminho_arquivo . '" class="img-fluid" style="width: 200px; height: 250px;">'; // Usar img-fluid para imagem responsiva
                                            }
                                            ?>
                                        </div>

                                        <!-- Campos do Produto -->
                                        <div class="col-12">
                                            <input type="hidden" name="productId" value="<?= $row['id']; ?>">
                                            <div class="mb-3">
                                                <label for="productName" class="form-label">Nome</label>
                                                <input type="text" class="form-control" name="productName" value="<?= $row['nome']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="productDescription" class="form-label">Cor / Sexo</label>
                                                <textarea class="form-control" name="productDescription" required><?= $row['descricao']; ?></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="productQuantityP" class="form-label">Tamanho P</label>
                                                    <input type="number" class="form-control" name="productQuantityP" value="<?= $row['tamanho_p']; ?>" required>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="productQuantityM" class="form-label">Tamanho M</label>
                                                    <input type="number" class="form-control" name="productQuantityM" value="<?= $row['tamanho_m']; ?>" required>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="productQuantityL" class="form-label">Tamanho L</label>
                                                    <input type="number" class="form-control" name="productQuantityL" value="<?= $row['tamanho_l']; ?>" required>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="productQuantityXL" class="form-label">Tamanho XL</label>
                                                    <input type="number" class="form-control" name="productQuantityXL" value="<?= $row['tamanho_xl']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="productPrice" class="form-label">Valor</label>
                                                <input type="number" class="form-control" name="productPrice" value="<?= $row['valor']; ?>" step="0.01" required>
                                            </div>

                                            <div class="mb-3 text-left">
                                                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                                <button type="button" class="btn btn-danger" onclick="deleteProduct(<?= $row['id']; ?>)">Excluir</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>


    </div>

    <!-- Modal para Novo Produto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="addProductImage" class="form-label">Imagem</label>
                            <input type="file" class="form-control" id="addProductImage" name="productImage" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductName" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="addProductName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductDescription" class="form-label">Cor / Sexo</label>
                            <textarea class="form-control" id="addProductDescription" name="productDescription" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="addProductQuantityP" class="form-label">Tamanho P</label>
                            <input type="number" class="form-control" id="addProductQuantityP" name="productQuantityP" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductQuantityM" class="form-label">Tamanho M</label>
                            <input type="number" class="form-control" id="addProductQuantityM" name="productQuantityM" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductQuantityL" class="form-label">Tamanho L</label>
                            <input type="number" class="form-control" id="addProductQuantityL" name="productQuantityL" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductQuantityXL" class="form-label">Tamanho XL</label>
                            <input type="number" class="form-control" id="addProductQuantityXL" name="productQuantityXL" required>
                        </div>
                        <div class="mb-3">
                            <label for="addProductPrice" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="addProductPrice" name="productPrice" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar Produto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para excluir produto
        function deleteProduct(id) {
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                window.location.href = 'adicionar_produto.php?id=' + id;
            }
        }
    </script>
</body>

</html>