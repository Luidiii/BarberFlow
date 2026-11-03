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

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = (float) ($_POST['preco'] ?? 0);
$duracao = (int) ($_POST['duracao_minutos'] ?? 0);

if ($nome === '' || $preco < 0 || $duracao <= 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha corretamente os dados do serviço.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO servicos (
            nome,
            descricao,
            preco,
            duracao_minutos
        )
        VALUES (
            :nome,
            :descricao,
            :preco,
            :duracao
        )
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao !== '' ? $descricao : null,
        ':preco' => $preco,
        ':duracao' => $duracao
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Serviço cadastrado com sucesso.',
        'id' => (int) $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar serviço.'
    ], JSON_UNESCAPED_UNICODE);
}