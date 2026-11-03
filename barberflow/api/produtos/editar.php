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
$categoria = trim($_POST['categoria'] ?? '');
$precoVenda = (float) ($_POST['preco_venda'] ?? 0);
$estoque = (int) ($_POST['estoque'] ?? 0);
$estoqueMinimo = (int) ($_POST['estoque_minimo'] ?? 0);

if ($id <= 0 || $nome === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID e nome do produto são obrigatórios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

if ($precoVenda < 0 || $estoque < 0 || $estoqueMinimo < 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preço e estoque não podem ser negativos.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT id
        FROM produtos
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    if (!$stmt->fetch()) {
        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Produto não encontrado.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE produtos
        SET
            nome = :nome,
            categoria = :categoria,
            preco_venda = :preco_venda,
            estoque = :estoque,
            estoque_minimo = :estoque_minimo
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':categoria' => $categoria !== '' ? $categoria : null,
        ':preco_venda' => $precoVenda,
        ':estoque' => $estoque,
        ':estoque_minimo' => $estoqueMinimo
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Produto atualizado com sucesso.'
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao atualizar produto.'
    ], JSON_UNESCAPED_UNICODE);
}