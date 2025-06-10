<?php
$host = 'localhost';
$dbname = 'Hotelaria';
$usuario = 'root';
$senha = 'root'; // coloque a senha do seu banco, se houver

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conex�o: " . $e->getMessage());
}
?>