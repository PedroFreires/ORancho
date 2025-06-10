<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['usuario_id'])) {
    // Usuário está logado, redireciona para o perfil
    header("Location: Perfil.php");
    exit();
} else {
    // Usuário não está logado, redireciona para a tela de login
    header("Location: Login.html");
    exit();
}
?>