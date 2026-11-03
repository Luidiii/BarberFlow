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

if ($id <= 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Cliente inválido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM agendamentos
        WHERE cliente_id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $temAgendamento = (int) $stmt->fetchColumn();

    if ($temAgendamento > 0) {
        http_response_code(409);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Não é possível excluir este cliente porque existem agendamentos vinculados a ele.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM vendas
        WHERE cliente_id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $temVenda = (int) $stmt->fetchColumn();

    if ($temVenda > 0) {
        http_response_code(409);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Não é possível excluir este cliente porque existem vendas vinculadas a ele.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $stmt = $pdo->prepare("
        DELETE FROM clientes
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Cliente não encontrado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Cliente excluído com sucesso.'
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao excluir cliente.'
    ], JSON_UNESCAPED_UNICODE);
}