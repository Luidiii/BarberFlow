<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

try {

    $stmt = $pdo->query("
        SELECT
            a.id,
            a.cliente_id,
            c.nome AS cliente,
            a.profissional_id,
            p.nome AS profissional,
            a.servico_id,
            s.nome AS servico,
            a.data_hora,
            a.status,
            a.valor
        FROM agendamentos a
        INNER JOIN clientes c
            ON a.cliente_id = c.id
        INNER JOIN profissionais p
            ON a.profissional_id = p.id
        INNER JOIN servicos s
            ON a.servico_id = s.id
        ORDER BY a.data_hora DESC
    ");

    $agendamentos = $stmt->fetchAll();

    echo json_encode([
        'sucesso' => true,
        'dados' => $agendamentos
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'dados' => [],
        'mensagem' => 'Erro ao buscar agendamentos.'
    ], JSON_UNESCAPED_UNICODE);
}''