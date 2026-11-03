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
$nome = trim($_POST['nome'] ?? '');
$especialidade = trim($_POST['especialidade'] ?? '');
$ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

if (!$id || $nome === '' || $especialidade === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID, nome e especialidade são obrigatórios.'
    ]);

    exit;
}

if ($ativo !== 0 && $ativo !== 1) {
    $ativo = 1;
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

    $sql = "UPDATE profissionais
            SET nome = :nome,
                especialidade = :especialidade,
                ativo = :ativo
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':especialidade' => $especialidade,
        ':ativo' => $ativo,
        ':id' => $id
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Profissional atualizado com sucesso.'
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao atualizar profissional.'
    ]);
}