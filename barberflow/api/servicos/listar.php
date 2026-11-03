<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

try {
    $stmt = $pdo->query("
        SELECT
            id,
            nome,
            descricao,
            preco,
            duracao_minutos,
            ativo
        FROM servicos
        ORDER BY nome ASC
    ");

    $servicos = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'dados' => $servicos
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'dados' => [],
        'mensagem' => 'Erro ao buscar serviços.'
    ], JSON_UNESCAPED_UNICODE);
}