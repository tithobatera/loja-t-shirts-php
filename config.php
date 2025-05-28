<?php
$servername = "localhost";
$username = "";  // Alterar se necessário
$password = "";  // Alterar se necessário
$dbname = "";

// Criar a conexão MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique se a conexão foi estabelecida corretamente
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Se você quiser continuar utilizando a conexão PDO, substitua $host por $servername
// Caso contrário, você pode remover a parte do PDO

try {
    // Criar uma conexão PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Definir o modo de erro para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    exit; // Se falhar, exibirá a mensagem de erro
}
?>
