<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

try {

    $stmt = $pdo->query("
        SELECT
            id,
            nome,
            categoria,
            preco_venda,
            estoque,
            estoque_minimo,
            ativo
        FROM produtos
        ORDER BY nome ASC
    ");

    $produtos = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'dados' => $produtos
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'dados' => [],
        'mensagem' => 'Erro ao buscar produtos.'
    ], JSON_UNESCAPED_UNICODE);
}