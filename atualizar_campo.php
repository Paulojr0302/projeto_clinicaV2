<?php
session_start();
include 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo 'erro';
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['campo']) && isset($_POST['valor'])) {
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    // Lista de campos permitidos
    $campos_permitidos = ['email', 'telefone', 'endereco'];

    if (in_array($campo, $campos_permitidos)) {
        // Sanitizar o valor
        $valor = htmlspecialchars(trim($valor));

        // Atualizar o campo no banco de dados
        $sql_update = "UPDATE usuarios SET $campo = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $valor, $usuario_id);

        if ($stmt_update->execute()) {
            echo 'sucesso';
        } else {
            echo 'erro';
        }
    } else {
        echo 'erro';
    }
} else {
    echo 'erro';
}
?>
