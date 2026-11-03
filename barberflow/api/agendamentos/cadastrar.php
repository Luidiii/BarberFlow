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

$clienteId = (int) ($_POST['cliente_id'] ?? 0);
$profissionalId = (int) ($_POST['profissional_id'] ?? 0);
$servicoId = (int) ($_POST['servico_id'] ?? 0);
$dataHora = trim($_POST['data_hora'] ?? '');
$status = trim($_POST['status'] ?? 'Agendado');
$valor = (float) ($_POST['valor'] ?? 0);

if (
    $clienteId <= 0 ||
    $profissionalId <= 0 ||
    $servicoId <= 0 ||
    $dataHora === ''
) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Cliente, profissional, serviço e data são obrigatórios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

if (!in_array($status, ['Agendado', 'Concluido', 'Cancelado'], true)) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Status de agendamento inválido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

if ($valor < 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'O valor não pode ser negativo.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT id
        FROM clientes
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $clienteId
    ]);

    if (!$stmt->fetch()) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Cliente não encontrado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM profissionais
        WHERE id = :id
        AND ativo = TRUE
    ");

    $stmt->execute([
        ':id' => $profissionalId
    ]);

    if (!$stmt->fetch()) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Profissional não encontrado ou inativo.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $stmt = $pdo->prepare("
        SELECT preco
        FROM servicos
        WHERE id = :id
        AND ativo = TRUE
    ");

    $stmt->execute([
        ':id' => $servicoId
    ]);

    $servico = $stmt->fetch();

    if (!$servico) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Serviço não encontrado ou inativo.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $valor = (float) $servico['preco'];

    $stmt = $pdo->prepare("
        INSERT INTO agendamentos (
            cliente_id,
            profissional_id,
            servico_id,
            data_hora,
            status,
            valor
        )
        VALUES (
            :cliente_id,
            :profissional_id,
            :servico_id,
            :data_hora,
            :status,
            :valor
        )
    ");

    $stmt->execute([
        ':cliente_id' => $clienteId,
        ':profissional_id' => $profissionalId,
        ':servico_id' => $servicoId,
        ':data_hora' => $dataHora,
        ':status' => $status,
        ':valor' => $valor
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Agendamento cadastrado com sucesso.',
        'id' => (int) $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar agendamento.'
    ], JSON_UNESCAPED_UNICODE);
}