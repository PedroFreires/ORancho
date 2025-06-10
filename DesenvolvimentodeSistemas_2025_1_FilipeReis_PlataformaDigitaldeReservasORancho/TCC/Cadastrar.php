<?php
session_start();
require_once 'Usuario.php';
require_once 'conexao.php'; // aqui puxamos a conexão PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario(
        $_POST['nome'],
        $_POST['email'],
        $_POST['telefone'],
        $_POST['CPD'], // cuidado: esse nome do campo precisa ser "CPD" também no HTML
        $_POST['endereco'],
        $_POST['dataNascimento'],
        $_POST['senha']
    );

    try {
        $stmt = $pdo->prepare("INSERT INTO Clientes (Nome, Email, Telefone, CPF, Endereço, Data_Nascimento, Senha)
                               VALUES (:nome, :email, :telefone, :cpf, :endereco, :dataNascimento, :senha)");

        $stmt->execute([
            ':nome' => $usuario->nome,
            ':email' => $usuario->email,
            ':telefone' => $usuario->telefone,
            ':cpf' => $usuario->cpf,
            ':endereco' => $usuario->endereco,
            ':dataNascimento' => $usuario->dataNascimento,
            ':senha' => password_hash($usuario->senha, PASSWORD_DEFAULT) // segurança!
        ]);

        $_SESSION['usuario'] = serialize($usuario);
        echo "Usuário registrado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao registrar usuário: " . $e->getMessage();
    }
}
?>