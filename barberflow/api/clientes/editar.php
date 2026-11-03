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

$id = (int) ($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($id <= 0 || $nome === '' || $telefone === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID, nome e telefone são obrigatórios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE clientes
        SET nome = :nome,
            telefone = :telefone,
            email = :email
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':telefone' => $telefone,
        ':email' => $email !== '' ? $email : null
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Cliente atualizado com sucesso.'
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao atualizar cliente.'
    ], JSON_UNESCAPED_UNICODE);
}