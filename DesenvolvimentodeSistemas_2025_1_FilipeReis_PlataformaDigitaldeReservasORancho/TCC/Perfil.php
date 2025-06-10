<?php
session_start();
require_once 'conexao.php'; // Arquivo com a conexão PDO

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Buscar dados do usuário
$stmt = $conn->prepare("CALL sp_obter_usuario_por_email(?)");
$stmt->bindParam(1, $email, PDO::PARAM_STR);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Atualizar telefone e endereço
if (isset($_POST['atualizar'])) {
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $stmt = $conn->prepare("CALL sp_atualizar_usuario(?, ?, ?)");
    $stmt->execute([$email, $telefone, $endereco]);
    $stmt->closeCursor();

    header("Location: perfil.php");
    exit();
}

// Buscar reserva atual
$stmt = $conn->prepare("CALL sp_obter_reserva_por_usuario(?)");
$stmt->bindParam(1, $email, PDO::PARAM_STR);
$stmt->execute();
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Cancelar reserva
if (isset($_POST['cancelar_reserva'])) {
    $stmt = $conn->prepare("CALL sp_cancelar_reserva(?)");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->closeCursor();

    header("Location: perfil.php");
    exit();
}

// Excluir conta
if (isset($_POST['excluir_conta'])) {
    $stmt = $conn->prepare("CALL sp_excluir_usuario(?)");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->closeCursor();

    session_destroy();
    header("Location: login.php");
    exit();
}
?>