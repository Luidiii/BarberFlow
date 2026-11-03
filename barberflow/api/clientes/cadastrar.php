<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($nome === '' || $telefone === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Nome e telefone são obrigatórios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO clientes (nome, telefone, email)
        VALUES (:nome, :telefone, :email)
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':telefone' => $telefone,
        ':email' => $email !== '' ? $email : null
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Cliente cadastrado com sucesso.',
        'id' => (int) $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar cliente.'
    ], JSON_UNESCAPED_UNICODE);
}