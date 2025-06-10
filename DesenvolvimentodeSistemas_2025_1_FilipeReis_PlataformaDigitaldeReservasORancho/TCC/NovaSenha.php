<?php
session_start();
require_once 'Cadastro.php';
require_once 'conexao.php';

if (!isset($_SESSION['verificado']) || $_SESSION['verificado'] !== true) {
    header('Location: verificar.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = $_POST['nova_senha']; // nome do input no HTML
    $confirmarSenha = $_POST['confirmarSenha']; // nome do input no HTML

    if ($novaSenha !== $confirmarSenha) {
        $_SESSION['mensagem'] = "As senhas não coincidem.";
        header('Location: perfil.php');
        exit();
    }

    if (isset($_SESSION['usuario'])) {
        $usuario = unserialize($_SESSION['usuario']);
        $email = $usuario->getEmail();
        $senhaCriptografada = password_hash($novaSenha, PASSWORD_DEFAULT);

        $sql = "UPDATE clientes SET senha = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $senhaCriptografada, $email);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Senha atualizada com sucesso!";
            unset($_SESSION['verificado']);
        } else {
            $_SESSION['mensagem'] = "Erro ao atualizar a senha.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['mensagem'] = "Nenhum usuário registrado.";
    }

    header('Location: perfil.php');
    exit();
}
?>