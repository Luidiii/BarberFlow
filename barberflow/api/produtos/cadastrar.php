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
$categoria = trim($_POST['categoria'] ?? '');
$precoVenda = (float) ($_POST['preco_venda'] ?? 0);
$estoque = (int) ($_POST['estoque'] ?? 0);
$estoqueMinimo = (int) ($_POST['estoque_minimo'] ?? 0);

if ($nome === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'O nome do produto é obrigatório.'
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
        INSERT INTO produtos (
            nome,
            categoria,
            preco_venda,
            estoque,
            estoque_minimo
        )
        VALUES (
            :nome,
            :categoria,
            :preco_venda,
            :estoque,
            :estoque_minimo
        )
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':categoria' => $categoria !== '' ? $categoria : null,
        ':preco_venda' => $precoVenda,
        ':estoque' => $estoque,
        ':estoque_minimo' => $estoqueMinimo
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Produto cadastrado com sucesso.',
        'id' => (int) $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao cadastrar produto.'
    ], JSON_UNESCAPED_UNICODE);
}