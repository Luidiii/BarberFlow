<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

try {
    $stmt = $pdo->query("
        SELECT
            id,
            nome,
            telefone,
            email,
            created_at
        FROM clientes
        ORDER BY nome ASC
    ");

    $clientes = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'dados' => $clientes
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'dados' => [],
        'mensagem' => 'Erro ao buscar clientes.'
    ], JSON_UNESCAPED_UNICODE);
}