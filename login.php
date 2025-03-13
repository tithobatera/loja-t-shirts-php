<?php
session_start();  // Começa a sessão para armazenar os dados do usuário

// Incluir o arquivo de conexão
include 'config.php';

// Verifica se o método da requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar a consulta para verificar se o e-mail existe no banco de dados
    $stmt = $conn->prepare("SELECT * FROM administradores WHERE email = ?");
    
    // Vincula o parâmetro
    $stmt->bind_param("s", $email); // "s" é para string
    
    // Executa a consulta
    $stmt->execute();
    
    // Obtém o resultado da consulta
    $result = $stmt->get_result();
    
    // Verifica se o administrador foi encontrado
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verifica se a senha é válida
        if (password_verify($senha, $admin['senha'])) {
            // Se a senha for válida, cria uma variável de sessão para o admin
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['email'] = $admin['email'];

            // Redireciona para a página admin.php
            header("Location: encomendas.php");
            exit;
        } else {
            // Se a senha não for válida
            echo "<script>alert('Email ou senha inválidos!'); window.location.href='index.php';</script>";
        }
    } else {
        // Se o email não for encontrado
        echo "<script>alert('Email ou senha inválidos!'); window.location.href='index.php';</script>";
    }

    // Fecha a consulta
    $stmt->close();
}
?>
