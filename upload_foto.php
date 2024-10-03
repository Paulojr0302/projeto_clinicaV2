<?php
session_start();
include 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar se o arquivo foi enviado
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
    $fileName = $_FILES['foto_perfil']['name'];
    $fileSize = $_FILES['foto_perfil']['size'];
    $fileType = $_FILES['foto_perfil']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Extensões permitidas
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    if (in_array($fileExtension, $allowedfileExtensions)) {
        // Diretório de upload
        $uploadFileDir = './uploads/';
        // Criar o diretório se não existir
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }
        // Nome do arquivo único
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        // Mover o arquivo para o diretório de upload
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Atualizar o caminho da foto no banco de dados
            $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $dest_path, $usuario_id);
            $stmt->execute();

            $_SESSION['mensagem'] = "Foto de perfil atualizada com sucesso!";
            header("Location: perfil.php");
            exit();
        } else {
            $_SESSION['mensagem'] = "Erro ao mover o arquivo para o diretório de upload.";
            header("Location: perfil.php");
            exit();
        }
    } else {
        $_SESSION['mensagem'] = "Extensão de arquivo não permitida.";
        header("Location: perfil.php");
        exit();
    }
} else {
    $_SESSION['mensagem'] = "Nenhum arquivo enviado ou erro no upload.";
    header("Location: perfil.php");
    exit();
}
?>
