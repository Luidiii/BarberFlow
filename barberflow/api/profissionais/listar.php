<?php

require_once '../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "SELECT id, nome, especialidade, ativo
            FROM profissionais
            ORDER BY nome ASC";

    $stmt = $pdo->query($sql);

    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'sucesso' => true,
        'dados' => $profissionais
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao listar profissionais.'
    ]);
}