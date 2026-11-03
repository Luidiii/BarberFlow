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
$produtoId = (int) ($_POST['produto_id'] ?? 0);
$quantidade = (int) ($_POST['quantidade'] ?? 0);

if ($produtoId <= 0 || $quantidade <= 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Produto e quantidade são obrigatórios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT
            id,
            preco_venda,
            estoque
        FROM produtos
        WHERE id = :id
        AND ativo = TRUE
        FOR UPDATE
    ");

    $stmt->execute([
        ':id' => $produtoId
    ]);

    $produto = $stmt->fetch();

    if (!$produto) {

        $pdo->rollBack();

        http_response_code(404);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Produto não encontrado ou inativo.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ((int) $produto['estoque'] < $quantidade) {

        $pdo->rollBack();

        http_response_code(409);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Estoque insuficiente para realizar a venda.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    if ($clienteId > 0) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM clientes
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $clienteId
        ]);

        if (!$stmt->fetch()) {

            $pdo->rollBack();

            http_response_code(404);

            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Cliente não encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }
    }

    $valorUnitario = (float) $produto['preco_venda'];

    $stmt = $pdo->prepare("
        SELECT fn_calcular_subtotal(
            :quantidade,
            :valor_unitario
        ) AS subtotal
    ");

    $stmt->execute([
        ':quantidade' => $quantidade,
        ':valor_unitario' => $valorUnitario
    ]);

    $subtotal = (float) $stmt->fetchColumn();

    $stmt = $pdo->prepare("
        INSERT INTO vendas (
            cliente_id,
            valor_total
        )
        VALUES (
            :cliente_id,
            :valor_total
        )
    ");

    $stmt->execute([
        ':cliente_id' => $clienteId > 0 ? $clienteId : null,
        ':valor_total' => $subtotal
    ]);

    $vendaId = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare("
        INSERT INTO itens_venda (
            venda_id,
            produto_id,
            quantidade,
            valor_unitario,
            subtotal
        )
        VALUES (
            :venda_id,
            :produto_id,
            :quantidade,
            :valor_unitario,
            :subtotal
        )
    ");

    $stmt->execute([
        ':venda_id' => $vendaId,
        ':produto_id' => $produtoId,
        ':quantidade' => $quantidade,
        ':valor_unitario' => $valorUnitario,
        ':subtotal' => $subtotal
    ]);

    $stmt = $pdo->prepare("
        UPDATE produtos
        SET estoque = estoque - :quantidade
        WHERE id = :id
    ");

    $stmt->execute([
        ':quantidade' => $quantidade,
        ':id' => $produtoId
    ]);

    $pdo->commit();

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Venda registrada com sucesso.',
        'id' => $vendaId,
        'valor_total' => $subtotal
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao registrar venda.'
    ], JSON_UNESCAPED_UNICODE);
}