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

// Consulta para buscar produtos disponíveis no estoque
$sql = "
SELECT
e.id, e.nome, e.descricao, e.tamanho_p, e.tamanho_m, e.tamanho_l, e.tamanho_xl, e.valor,
ef.nome_arquivo
FROM
estoque e
LEFT JOIN
estoque_files ef ON e.id = ef.id_estoque
WHERE
(e.tamanho_p > 0 OR e.tamanho_m > 0 OR e.tamanho_l > 0 OR e.tamanho_xl > 0)
";
$result = $conn->query($sql);

// Função para retornar o caminho da imagem
function getImagePath($imageName)
{
    if (!empty($imageName)) {
        return 'uploads/' . $imageName;
    }
    return 'uploads/default_image.jpg';
}

?>








<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Carrinho de Compras</title>
    <script src="../assets/js/color-modes.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        .card-img-top {
            width: 100%;
            height: 225px;
            object-fit: cover;
        }

        .btn-group .btn.active {
            background-color: #007bff;
            color: white;
        }

        /* Estilos adicionais */
        .card-body {
            padding: 15px;
        }

        .cart-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>

    <style>
        #scrollToTopBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
            border-radius: 50%;
            font-size: 24px;
            width: 50px;
            height: 50px;
            background-color: #007bff;
            color: white;
            border: none;
            display: none;
            cursor: pointer;
        }

        #scrollToTopBtn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<!-- Botão voltar ao topo -->
<button id="scrollToTopBtn" class="btn btn-primary" onclick="scrollToTop()" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 1050;">
    <i class="fas fa-arrow-up"></i>
</button>


<div class="container-fluid">
    <header class="d-flex justify-content-between align-items-center py-2 mb-1" style="background-color: white; width: 100%;">

        <div class="container">
            <header class="d-flex justify-content-center py-3">
                <nav class="py-3 justify-content-center position-fixed w-100" style="top: 0; left: 0; background-color: white; z-index: 1030;">
                    <ul class="nav nav-pills justify-content-center">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active" aria-current="page">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="t-shirts.php" class="nav-link">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                                Sobre nós
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="true" aria-controls="collapseExample2">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </nav>

            </header>

        </div>
    </header>
</div>

<!-- Sobre nós (primeiro colapso) -->
<div class="collapse" id="collapseExample">
    <div class="card card-body " class="text-center">

        <h4 class="text-center"> Sobre nós</h4>
        <p class="text-center"> NT-Shirts é uma loja criada em 2020, especializada em T-shirts exclusivas e de alta qualidade. Nossa missão é oferecer peças confortáveis e modernas, com design único, que expressem estilo e personalidade. Trabalhamos com materiais de excelente qualidade, sempre buscando inovações para manter nossos produtos atualizados.
            Cada camiseta é feita com dedicação, garantindo conforto, durabilidade e autenticidade. Agradecemos a confiança dos nossos clientes e continuamos comprometidos em oferecer o melhor em estilo e conforto. Se você busca algo único, a NT-Shirts é o lugar ideal para você!
        </p>
    </div>
</div>

<!-- Contato (segundo colapso) -->
<div class="collapse" id="collapseExample2">
    <div class="card card-body">
        <h4 class="text-center">Contacto</h4>
        <div class="row">
            <!-- Coluna 1: Email e Telefone -->
            <div class="col-md-6">
                <p class="text-center"><strong>Email:</strong> contato@nt-shirts.com</p>
                <p class="text-center"><strong>Telefone:</strong> +123 456 7890</p>
            </div>
            <!-- Coluna 2: WhatsApp -->
            <div class="col-md-6">
                <p class="text-center"><strong>WhatsApp:</strong> +123 456 7890</p>
                <p class="text-center">
                    <strong><a href="https://www.google.pt/maps/place/Master+D+Lisboa+-+Cursos+e+Forma%C3%A7%C3%A3o/@38.7335602,-9.1437149,17z/data=!3m1!4b1!4m6!3m5!1s0xd19339f5c78b77b:0xca44b80af51c2996!8m2!3d38.733556!4d-9.14114!16s%2Fg%2F11c60jkk83?entry=ttu&g_ep=EgoyMDI1MDMwNC4wIKXMDSoJLDEwMjExNDUzSAFQAw%3D%3D" target="_blank" style="text-decoration: none; color: inherit;">
                            Morada: Lisboa
                        </a></strong>
                </p>


            </div>
        </div>
    </div>
</div>



<div class="container mt-5">
    <div>
        <div class="container text-center mt-5">
            <h2 class="nav-item text-center mb-4">T-Shirt´s</h2>
            <p class="nav-item"">
        " Na NT-Shirt's, oferecemos muito mais do que camisetas – entregamos uma experiência de conforto e estilo em cada peça. Nossas T-Shirts são confeccionadas com tecidos premium, garantindo um toque macio e respirável que acompanha você ao longo do dia, com total liberdade de movimento. Perfeitas para qualquer ocasião, nossas camisetas se adaptam tanto ao visual casual quanto a encontros mais descontraídos, sempre com aquele toque moderno e elegante. Sinta-se autêntico e confiante ao vestir uma peça que une qualidade excepcional e versatilidade incomparável. Escolha NT-Shirt's, e transforme seu guarda-roupa com estilo e conforto em cada detalhe."
                </p>
                <a href="descricao_produto.php" class="btn btn-primary mt-3">Descrição dos produtos</a>
        </div>


        <div class="container d-flex justify-content-center align-items-center p-5">
            <div class="dropdown">

                <!-- Scrollable modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Ver carrinho
                </button>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="true" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Meu carrinho</h1>
                                <!-- Botão de fechar -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped shadow-sm rounded" style="border-width: 3px;">
                                        <thead class="table-primary">
                                            <tr>
                                                <th></th>
                                                <th>Tamanho</th>
                                                <th>Qtd</th>
                                                <th>Preço</th>
                                                <th>Subtotal</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartTableBody">
                                            <!-- Itens do Carrinho serão inseridos aqui via JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="text-right">
                                    <strong>Total: R$ <span id="totalValue">0.00</span></strong>
                                </div>
                                <button class="btn btn-warning mt-3" id="checkoutBtn" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                    Realizar Pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu" aria-labelledby="cartDropdown" style="max-height: 600px; overflow-y: auto; margin-top: 20px;">
                    <li>

                    </li>
                </ul>
            </div>
        </div>


        <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- Adicione a classe modal-lg aqui -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutModalLabel">Resumo do Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="resumo_pedido">
                            <!-- Resumo será preenchido aqui via JavaScript -->
                        </div>
                        <form method="POST" action="finalizar_pedido.php">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome completo:</label>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="data" class="form-label">Data de nascimento:</label>
                                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Morada / Complemento:</label>
                                <input type="text" name="endereco" id="endereco" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="codigo_postal" class="form-label">Código Postal:</label>
                                <input type="number" name="codigo_postal" id="cep" class="form-control" required>
                            </div>
                            <input type="hidden" name="carrinho_data" id="carrinhoData">
                            <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <DIV>
        </div>
        <!-- Exibição dos produtos -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="col mb-4">
                    <div class="card">
                        <img src="<?php echo getImagePath($row['nome_arquivo']); ?>" class="card-img-top" alt="Produto">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $row['nome']; ?> &nbsp; <span class="ms-5">Preço: R$<?php echo number_format($row['valor'], 2, ',', '.'); ?></span>
                            </h5>
                            <p class="card-text"><?php echo $row['descricao']; ?></p>

                            <form class="add-to-cart-form">
                                <input type="hidden" name="id_estoque" value="<?php echo $row['id']; ?>">
                                <div class="d-flex justify-content-between mb-2">
                                    <select name="tamanho" class="form-control" required>
                                        <option value="" disabled selected>Tamanho</option>
                                        <option value="P">P</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                    </select>
                                    <input type="number" name="quantidade" class="form-control" placeholder="Quantidade" min="1" required>
                                </div>
                                <button type="button" class="btn btn-primary add-to-cart-btn w-100"
                                    data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                    data-nome="<?php echo htmlspecialchars($row['nome']); ?>"
                                    data-preco="<?php echo htmlspecialchars($row['valor']); ?>"
                                    data-imagem="<?php echo htmlspecialchars(getImagePath($row['nome_arquivo'])); ?>">
                                    Adicionar ao Carrinho
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</DIV>





<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="facebook" viewBox="0 0 16 16">
        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
    </symbol>
    <symbol id="instagram" viewBox="0 0 16 16">
        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
    </symbol>
    <symbol id="twitter" viewBox="0 0 16 16">
        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
    </symbol>
</svg>

<div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div class="col-md-4 d-flex align-items-center">
            <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                <svg class="bi" width="30" height="24">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>
            <span class="mb-3 mb-md-0 text-body-secondary">© 2025 NT-Shirt´s</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3"><a class="text-body-secondary" href="https://x.com/"><svg class="bi" width="24" height="24">
                        <use xlink:href="#twitter"></use>
                    </svg></a></li>
            <li class="ms-3"><a class="text-body-secondary" href="https://www.instagram.com/"><svg class="bi" width="24" height="24">
                        <use xlink:href="#instagram"></use>
                    </svg></a></li>
            <li class="ms-3"><a class="text-body-secondary" href="https://www.facebook.com/"><svg class="bi" width="24" height="24">
                        <use xlink:href="#facebook"></use>
                    </svg></a></li>
        </ul>
    </footer>
</div>


<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>



<script>
    // Função para mostrar ou esconder o botão de "voltar ao topo"
    window.onscroll = function() {
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");
        // Verifica se o usuário rolou para baixo 200px da parte superior da página
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            scrollToTopBtn.style.display = "block"; // Mostra o botão
        } else {
            scrollToTopBtn.style.display = "none"; // Esconde o botão
        }
    };

    // Função para voltar ao topo
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth" // Rolagem suave
        });
    }
</script>

<script>
    // Limpar o carrinho no carregamento da página
    window.onload = function() {
        localStorage.removeItem('carrinho');
    };
</script>

<script>
    // Fechar os colapsos quando clicar fora de qualquer um dos colapsos
    document.addEventListener('click', function(event) {
        const collapseElements = document.querySelectorAll('.collapse'); // Seleciona todos os elementos com a classe 'collapse'
        const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]'); // Seleciona todos os botões de colapso

        collapseElements.forEach(function(collapseElement) {
            const collapseButton = document.querySelector(`[href="#${collapseElement.id}"]`); // Encontra o botão associado ao colapso

            // Verifica se o clique foi fora do colapso e do botão
            if (!collapseElement.contains(event.target) && !collapseButton.contains(event.target)) {
                const collapseInstance = bootstrap.Collapse.getInstance(collapseElement) || new bootstrap.Collapse(collapseElement, {
                    toggle: false
                });
                collapseInstance.hide(); // Fecha o colapso
            }
        });
    });
</script>

<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let nome = this.getAttribute('data-nome');
            let preco = parseFloat(this.getAttribute('data-preco'));
            let tamanho = this.closest('form').querySelector('select[name="tamanho"]').value;
            let quantidade = parseInt(this.closest('form').querySelector('input[name="quantidade"]').value);
            let imagem = this.getAttribute('data-imagem');

            // Verifica se o tamanho e a quantidade são válidos antes de continuar
            if (!tamanho || isNaN(quantidade) || quantidade <= 0) {
                alert("Por favor, selecione um tamanho e uma quantidade válida.");
                return; // Impede a execução do código caso os dados não sejam válidos
            }

            // Realiza a verificação de estoque antes de adicionar ao carrinho
            fetch('verificar_estoque.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_produto=${encodeURIComponent(id)}&tamanho=${encodeURIComponent(tamanho)}`
                })
                .then(response => response.json()) // Recebe a resposta JSON
                .then(data => {
                    if (data.error) {
                        alert("Erro ao verificar estoque: " + data.error);
                        return; // Impede a execução se houver erro no servidor
                    }

                    let estoqueDisponivel = data.estoque || 0;
                    console.log('Estoque Disponível: ', estoqueDisponivel); // Depuração

                    // Verifica se a quantidade solicitada é maior que o estoque disponível
                    if (quantidade > estoqueDisponivel) {
                        alert(`Quantidade solicitada maior que o estoque disponível!\nEstoque para o tamanho ${tamanho}: ${estoqueDisponivel}`);
                        return; // Impede que o item seja adicionado ao carrinho
                    }

                    // Se a quantidade for válida (menor ou igual ao estoque disponível), adicione ao carrinho
                    alert("Produto adicionado ao carrinho!");
                    addToCart(id, nome, preco, tamanho, quantidade, imagem);
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert("Erro ao verificar estoque. Tente novamente.");
                });
        });
    });

    // Função para adicionar o item ao carrinho
    function addToCart(id, nome, preco, tamanho, quantidade, imagem) {
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

        let item = {
            id,
            nome_produto: nome,
            preco: preco,
            tamanho: tamanho,
            quantidade: quantidade,
            imagem_produto: imagem
        };

        carrinho.push(item);
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        updateCart(); // Função para atualizar a interface do carrinho
    }

    // Função para atualizar o carrinho na interface
    function updateCart() {
        const cartTableBody = document.getElementById('cartTableBody');
        const totalValueElement = document.getElementById('totalValue');
        let total = 0;

        cartTableBody.innerHTML = ''; // Limpa o carrinho na tela

        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

        carrinho.forEach(item => {
            let subtotal = item.quantidade * item.preco;
            total += subtotal;

            let row = `
    <tr>
        <td><img src="${item.imagem_produto}" class="cart-img" alt="Imagem do Produto"></td>
        <td>${item.tamanho}</td>
        <td>${item.quantidade}</td>
        <td>R$ ${item.preco.toFixed(2).replace('.', ',')}</td>
        <td>R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
        <td><button class="btn btn-danger btn-sm remove-btn" data-id="${item.id}">Remover</button></td>
    </tr>
    `;
            cartTableBody.insertAdjacentHTML('beforeend', row);
        });

        totalValueElement.textContent = total.toFixed(2).replace('.', ',');
    }
</script>





<script>
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    let resumoPedidoDiv = document.getElementById('resumo_pedido');

    // Função para atualizar o carrinho no modal de "Carrinho"
    function updateCart() {
        const cartTableBody = document.getElementById('cartTableBody');
        const totalValueElement = document.getElementById('totalValue');
        let total = 0;

        cartTableBody.innerHTML = ''; // Limpar carrinho na tela

        carrinho.forEach(item => {
            let subtotal = item.quantidade * item.preco;
            total += subtotal;

            // Criar a linha da tabela
            let row = `
            <tr>
                <td><img src="${item.imagem_produto}" class="cart-img" alt="Imagem do Produto"></td>
                <td>${item.tamanho}</td>
                <td>${item.quantidade}</td>
                <td>R$ ${item.preco.toFixed(2).replace('.', ',')}</td>
                <td>R$ ${subtotal.toFixed(2).replace('.', ',')}</td>
                <td><button class="btn btn-danger btn-sm remove-btn" data-id="${item.id}">Remover</button></td>
            </tr>
            `;
            cartTableBody.insertAdjacentHTML('beforeend', row);
        });

        // Atualizar o total
        totalValueElement.textContent = total.toFixed(2).replace('.', ',');
    }

    // Função para adicionar item ao carrinho
    function addToCart(id, nome, preco, tamanho, quantidade, imagem) {
        let item = {
            id,
            nome_produto: nome,
            preco: preco,
            tamanho: tamanho,
            quantidade: quantidade,
            imagem_produto: imagem
        };

        carrinho.push(item);
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        updateCart();
    }

    // Função para remover item do carrinho
    function removeFromCart(id) {
        carrinho = carrinho.filter(item => item.id !== id);
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        updateCart();
    }



    // Adicionar evento para os botões de remoção do carrinho
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-btn')) {
            let itemId = event.target.getAttribute('data-id');
            removeFromCart(itemId);
        }
    });

    // Função para preencher o resumo do pedido no modal de "Resumo do Pedido"
    document.getElementById('checkoutBtn').addEventListener('click', function() {
        let resumo = '';
        let total = 0;

        resumo += '<div class="row">';

        carrinho.forEach(item => {
            let subtotal = item.quantidade * item.preco;
            total += subtotal;

            resumo += `
            <div class="col-md-3 mb-3">
                <div class="cart-item">
                    <img src="${item.imagem_produto}" alt="${item.nome_produto}" class="cart-img" style="width: 40px; height: 50px;">
                    <div class="cart-item-info">
                        <strong>${item.nome_produto}</strong><br>
                        Tamanho: ${item.tamanho}<br>
                        Quantidade: ${item.quantidade}<br>
                        Preço: R$ ${item.preco.toFixed(2).replace('.', ',')}<br>
                        Subtotal: R$ ${subtotal.toFixed(2).replace('.', ',')}
                    </div>
                </div>
            </div>
            `;
        });

        resumo += '</div>'; // Fechando a div da row

        resumo += `
        <div class="text-right">
            <strong>Total: R$ ${total.toFixed(2).replace('.', ',')}</strong>
        </div><br>
        <hr>
        `;

        resumoPedidoDiv.innerHTML = resumo;
        document.getElementById('carrinhoData').value = JSON.stringify(carrinho);
    });

    // Inicializa o carrinho ao carregar a página
    updateCart();

    // Exibir o modal de "Carrinho"
    document.getElementById('viewCartBtn').addEventListener('click', function() {
        document.getElementById('cartModal').style.display = 'block'; // Exibe o modal de carrinho
    });

    // Exibir o modal de "Resumo do Pedido"
    document.getElementById('checkoutBtn').addEventListener('click', function() {
        document.getElementById('summaryModal').style.display = 'block'; // Exibe o modal de resumo do pedido
    });

    // Fechar os modais
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none'; // Fecha o modal quando clicar fora dele
            }
        });
    });
</script>


<script>
    document.getElementById('checkoutModal').addEventListener('submit', function(event) {
        // Captura os dados do formulário
        const nome = document.getElementById('nome').value.trim();
        const email = document.getElementById('email').value.trim();
        const endereco = document.getElementById('endereco').value.trim();
        const cep = document.getElementById('cep').value.trim();
        const dataNascimento = document.getElementById('data_nascimento').value.trim();

        // Valida se os campos obrigatórios foram preenchidos
        if (!nome || !email || !endereco || !cep || !dataNascimento) {
            alert("Todos os campos são obrigatórios!");
            event.preventDefault(); // Impede o envio do formulário
            return false;
        }

        // Valida se o nome tem pelo menos 3 caracteres
        if (nome.length < 3) {
            alert("O nome deve ter pelo menos 3 caracteres.");
            event.preventDefault();
            return false;
        }

        // Valida se a morada tem pelo menos 5 caracteres
        if (endereco.length < 5) {
            alert("O endereço deve ter pelo menos 5 caracteres.");
            event.preventDefault();
            return false;
        }

        // Valida se a data de nascimento corresponde a um usuário maior de 18 anos
        const dataNascimentoObj = new Date(dataNascimento);
        const hoje = new Date();
        let idade = hoje.getFullYear() - dataNascimentoObj.getFullYear();

        // Ajusta a idade caso o aniversário ainda não tenha ocorrido este ano
        const mesAtual = hoje.getMonth();
        const diaAtual = hoje.getDate();
        const mesNascimento = dataNascimentoObj.getMonth();
        const diaNascimento = dataNascimentoObj.getDate();

        if (mesAtual < mesNascimento || (mesAtual === mesNascimento && diaAtual < diaNascimento)) {
            idade--;
        }

        if (idade < 18) {
            alert("A idade deve ser igual ou superior a 18 anos para realizar a compra.");
            event.preventDefault(); // Impede o envio do formulário
            return false;
        }

        return true; // O formulário é válido
    });
</script>


</body>

</html>