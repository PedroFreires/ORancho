<?php
// reserva.php

// Dados de conexão
$host = 'localhost';
$db   = 'Hotelaria';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

// Configuração DSN e opções PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // para mostrar erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Criar conexão PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Caso dê erro na conexão, mostrar mensagem e sair
    exit('Erro na conexão com o banco: ' . $e->getMessage());
}

// Função para converter data do formato dd/mm/yyyy para yyyy-mm-dd
function converterData($data) {
    $partes = explode('/', $data);
    if (count($partes) === 3) {
        return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    return null;
}

// Pegar dados do formulário
$data_checkin = isset($_POST['checkin']) ? converterData($_POST['checkin']) : null;
$data_checkout = isset($_POST['checkout']) ? converterData($_POST['checkout']) : null;
$tipo_quarto = isset($_POST['room']) ? $_POST['room'] : null;

// Aqui você precisaria obter o Id_Hospede e Id_Quarto corretos.
// Por exemplo, vamos supor que você já tem $id_hospede (do usuário logado, por ex)
// e você busca o quarto pelo tipo:

$id_hospede = 1; // só um exemplo, você precisa definir isso conforme seu sistema

// Buscar Id do quarto pelo tipo (exemplo simples)
$stmt = $pdo->prepare("SELECT Id FROM Quartos WHERE Tipo = :tipo LIMIT 1");
$stmt->execute(['tipo' => ucfirst($tipo_quarto)]); // cuidado com case: no banco tá com a primeira letra maiúscula
$quarto = $stmt->fetch();

if (!$quarto) {
    exit('Quarto do tipo selecionado não encontrado.');
}

$id_quarto = $quarto['Id'];

// Valor total (exemplo: pegar preço do quarto e calcular quantidade de dias)
$stmt = $pdo->prepare("SELECT Preco FROM Quartos WHERE Id = :id");
$stmt->execute(['id' => $id_quarto]);
$preco_quarto = $stmt->fetchColumn();

if (!$preco_quarto) {
    exit('Preço do quarto não encontrado.');
}

// Calcular quantidade de dias entre checkin e checkout
$d1 = new DateTime($data_checkin);
$d2 = new DateTime($data_checkout);
$interval = $d1->diff($d2);
$dias = $interval->days;

if ($dias <= 0) {
    exit('Data de check-out deve ser maior que a de check-in.');
}

$valor_total = $preco_quarto * $dias;

// Inserir reserva
$sql = "INSERT INTO Reservas (Id_Hospede, Id_Quarto, Data_Checkin, Data_Checkout, Status_Reserva, Valor_Total)
        VALUES (:id_hospede, :id_quarto, :data_checkin, :data_checkout, 'Reservada', :valor_total)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id_hospede' => $id_hospede,
    'id_quarto' => $id_quarto,
    'data_checkin' => $data_checkin,
    'data_checkout' => $data_checkout,
    'valor_total' => $valor_total
]);

echo "Reserva feita com sucesso!";
?>