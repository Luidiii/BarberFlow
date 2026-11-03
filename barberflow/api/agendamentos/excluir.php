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
        'mensagem' => 'Agendamento inválido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT id, status
        FROM agendamentos
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $agendamento = $stmt->fetch();

    if (!$agendamento) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Agendamento não encontrado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($agendamento['status'] === 'Concluido') {
        http_response_code(409);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Não é possível excluir um agendamento que já foi concluído.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $stmt = $pdo->prepare("
        DELETE FROM agendamentos
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Agendamento excluído com sucesso.'
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao excluir agendamento.'
    ], JSON_UNESCAPED_UNICODE);
}