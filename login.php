<?php
session_start();
include 'config.php';

// Verificar se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Preparar a consulta
    $sql = "SELECT id, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o usuário existe
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verificar a senha
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            header("Location: perfil.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta.');window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado.');window.location.href='login.html';</script>";
    }
}
?>
