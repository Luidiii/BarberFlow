<?php

require_once '../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido.'
    ]);

    exit;
}

$nome = trim($_POST['nome'] ?? '');
$especialidade = trim($_POST['especialidade'] ?? '');
$ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

if ($nome === '' || $especialidade === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nome e especialidade são obrigatórios.'
    ]);

    exit;
}

if ($ativo !== 0 && $ativo !== 1) {
    $ativo = 1;
}

try {

    $sql = "INSERT INTO profissionais (nome, especialidade, ativo)
            VALUES (:nome, :especialidade, :ativo)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':especialidade' => $especialidade,
        ':ativo' => $ativo
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Profissional cadastrado com sucesso.',
        'id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar profissional.'
    ]);
}