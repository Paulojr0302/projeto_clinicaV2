<?php
// config.php
$servername = "localhost";
$username = "root";
$password = ""; // Altere se você tiver uma senha
$dbname = "clinica_psicologica";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Definir o conjunto de caracteres
$conn->set_charset("utf8");
?>
