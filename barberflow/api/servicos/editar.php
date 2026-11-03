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
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = (float) ($_POST['preco'] ?? 0);
$duracao = (int) ($_POST['duracao_minutos'] ?? 0);

if ($id <= 0 || $nome === '' || $preco < 0 || $duracao <= 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha corretamente os dados do serviço.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE servicos
        SET
            nome = :nome,
            descricao = :descricao,
            preco = :preco,
            duracao_minutos = :duracao
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':descricao' => $descricao !== '' ? $descricao : null,
        ':preco' => $preco,
        ':duracao' => $duracao
    ]);

    if ($stmt->rowCount() === 0) {
        $stmtVerificacao = $pdo->prepare("
            SELECT id
            FROM servicos
            WHERE id = :id
        ");

        $stmtVerificacao->execute([
            ':id' => $id
        ]);

        if (!$stmtVerificacao->fetch()) {
            http_response_code(404);

            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Serviço não encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }
    }

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Serviço atualizado com sucesso.'
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao atualizar serviço.'
    ], JSON_UNESCAPED_UNICODE);
}