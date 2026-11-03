<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

$busca = trim($_GET['busca'] ?? '');
$status = trim($_GET['status'] ?? '');
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$limite = max(1, min(20, (int) ($_GET['limite'] ?? 5)));

try {

    $stmt = $pdo->prepare("
        CALL sp_buscar_agendamentos(
            :busca,
            :status,
            :pagina,
            :limite
        )
    ");

    $stmt->execute([
        ':busca' => $busca,
        ':status' => $status,
        ':pagina' => $pagina,
        ':limite' => $limite
    ]);

    $agendamentos = $stmt->fetchAll();

    while ($stmt->nextRowset()) {
    }

    echo json_encode([
        'sucesso' => true,
        'dados' => $agendamentos,
        'pagina' => $pagina,
        'limite' => $limite
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'dados' => [],
        'mensagem' => 'Erro ao buscar agendamentos.'
    ], JSON_UNESCAPED_UNICODE);
}