<?php
// Conexão com o banco de dados MySQL
$host = "localhost";
$user = "root";
$password = "Titho@1810";
$dbname = "cadastrodb";

$conn = new mysqli($host, $user, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}


// Gerar o hash da senha
$senha = "admin123";
$senhaHash = password_hash($senha, PASSWORD_DEFAULT); // Gera o hash da senha

// Dados do administrador
$nome = "Administrador";
$email = "admin@admin.com";

// Inserir dados na tabela de administradores
$sql = "INSERT INTO administradores (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senhaHash); // 'sss' indica três strings
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Administrador inserido com sucesso!";
} else {
    echo "Erro ao inserir o administrador.";
}

$stmt->close();
$conn->close();
?>
