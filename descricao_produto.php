<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descrição das T-Shirts</title>
    <script src="../assets/js/color-modes.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        h1,
        h2 {
            color: #333;
        }

        .container-2 {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .section {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
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
    <header class="d-flex justify-content-center align-items-center py-2 mb-1" style="background-color: white; width: 100%;">
        <nav class="py-3">
            <ul class="nav nav-pills text-center">
                <li class="nav-item"><a href="index.php" class="nav-link active" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="t-shirts.php" class="nav-link">Produtos</a></li>
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



    <br><br>

    <h2 class="nav-item text-center mb-4">Descrição dos produtos</h2>
    <br>


    <div class="container-2">

        <div class="section">
            <h2 class="nav-item">Qualidade das T-Shirts</h2>
            <p>Nossas T-shirts são feitas com <strong>100% algodão</strong>, garantindo conforto, respirabilidade e durabilidade. O algodão é um material natural que proporciona maciez ao toque e excelente absorção de umidade, sendo ideal para o uso diário.</p>
        </div>

        <div class="section">
            <h2>Cuidados com a Lavagem e Secagem</h2>
            <p>Para manter a qualidade e a durabilidade da sua T-shirt de algodão, siga estas instruções de lavagem e secagem:</p>

            <div class="row">
                <div class="col-md-6">
                    <h3>Lavagem:</h3>
                    <ul>
                        <li>Lave a peça com água fria ou morna (máximo 30°C).</li>
                        <li>Use sabão neutro para evitar o desgaste do tecido.</li>
                        <li>Evite alvejantes e produtos à base de cloro, pois podem desbotar a cor.</li>
                        <li>Prefira lavar a peça do avesso para preservar as estampas e as fibras.</li>
                        <li>Evite misturar com roupas de outras cores para evitar manchas.</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h3>Secagem:</h3>
                    <ul>
                        <li>Seque à sombra e em local bem ventilado para evitar encolhimento.</li>
                        <li>Evite o uso de secadora, pois o calor pode encolher o tecido.</li>
                        <li>Não torça excessivamente a peça para não deformar as fibras.</li>
                        <li>Se precisar passar a camiseta, utilize ferro a temperatura baixa e passe pelo avesso.</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="section">

            <!-- Tabela Masculino -->
            <div class="section">
                <h2>Tamanhos e Medidas</h2>
                <p>Confira abaixo as medidas exatas para cada tamanho de T-shirt:</p>

                <div class="row">
                    <!-- Tabela Masculino -->
                    <div class="col-md-6">
                        <h5>Tamanhos Masculinos</h5>
                        <table class="table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Tamanho</th>
                                    <th>Compr.(cm)</th>
                                    <th>Largura (cm)</th>
                                    <th>Ombro (cm)</th>
                                    <th>Manga (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>P</td>
                                    <td>68</td>
                                    <td>48</td>
                                    <td>42</td>
                                    <td>19</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>70</td>
                                    <td>50</td>
                                    <td>44</td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td>G</td>
                                    <td>72</td>
                                    <td>52</td>
                                    <td>46</td>
                                    <td>21</td>
                                </tr>
                                <tr>
                                    <td>GG (XL)</td>
                                    <td>74</td>
                                    <td>54</td>
                                    <td>48</td>
                                    <td>22</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tabela Feminino -->
                    <div class="col-md-6">
                        <h5>Tamanhos Femininos</h5>
                        <table class="table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Tamanho</th>
                                    <th>Comp.(cm)</th>
                                    <th>Largura (cm)</th>
                                    <th>Ombro (cm)</th>
                                    <th>Manga (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>P</td>
                                    <td>60</td>
                                    <td>42</td>
                                    <td>36</td>
                                    <td>16</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>62</td>
                                    <td>44</td>
                                    <td>38</td>
                                    <td>17</td>
                                </tr>
                                <tr>
                                    <td>G</td>
                                    <td>64</td>
                                    <td>46</td>
                                    <td>40</td>
                                    <td>18</td>
                                </tr>
                                <tr>
                                    <td>GG (XL)</td>
                                    <td>66</td>
                                    <td>48</td>
                                    <td>42</td>
                                    <td>19</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>



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


    </body>

</html>