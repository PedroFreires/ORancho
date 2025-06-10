<?php
session_start();
require_once 'conexao.php'; // arquivo com a conexão $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        echo "Por favor, preencha email e senha.";
        exit();
    }

    // Prepara a consulta para evitar SQL injection
    $sql = "SELECT * FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            // Login OK, cria sessão
            $_SESSION['usuario'] = serialize($usuario);
            $_SESSION['logado'] = true;

            header('Location: Perfil.php');
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Se acessou direto sem POST, redireciona para login ou Home
    header('Location: Login.html');
    exit();
}
?>