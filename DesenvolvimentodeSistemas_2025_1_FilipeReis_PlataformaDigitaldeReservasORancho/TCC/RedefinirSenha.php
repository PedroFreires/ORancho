<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitiza entrada
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);

    // Verifica se campos foram preenchidos
    if (!$nome || !$email || !$cpf) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Verifica se dados do usuário estão na sessão
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];

        // Usa comparação simples, mas exata
        if (
            hash_equals($usuario['nome'], $nome) &&
            hash_equals($usuario['email'], $email) &&
            hash_equals($usuario['cpf'], $cpf)
        ) {
            $_SESSION['verificado'] = true;
            header('Location: redefinir_senha.php');
            exit();
        }
    }

    // Caso algum dado esteja errado ou sessão não exista
    echo "Dados inválidos. Por favor, tente novamente.";
}
?>