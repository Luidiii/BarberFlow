<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

try {

    $stmt = $pdo->query("
        SELECT
            v.id,
            v.cliente_id,
            COALESCE(c.nome, 'Cliente não informado') AS cliente,
            v.data_venda,
            v.valor_total
        FROM vendas v
        LEFT JOIN clientes c
            ON v.cliente_id = c.id
        ORDER BY v.data_venda DESC
    ");

    $vendas = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'dados' => $vendas
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'dados' => [],
        'mensagem' => 'Erro ao buscar vendas.'
    ], JSON_UNESCAPED_UNICODE);
}