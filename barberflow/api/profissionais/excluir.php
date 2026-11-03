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

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID do profissional é obrigatório.'
    ]);

    exit;
}

try {

    $verificar = $pdo->prepare(
        "SELECT id FROM profissionais WHERE id = :id"
    );

    $verificar->execute([
        ':id' => $id
    ]);

    if (!$verificar->fetch()) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Profissional não encontrado.'
        ]);

        exit;
    }

    $agendamentos = $pdo->prepare(
        "SELECT COUNT(*) FROM agendamentos WHERE profissional_id = :id"
    );

    $agendamentos->execute([
        ':id' => $id
    ]);

    if ((int) $agendamentos->fetchColumn() > 0) {
        http_response_code(409);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Não é possível excluir este profissional porque existem agendamentos vinculados a ele.'
        ]);

        exit;
    }

    $sql = "DELETE FROM profissionais WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Profissional excluído com sucesso.'
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao excluir profissional.'
    ]);
}