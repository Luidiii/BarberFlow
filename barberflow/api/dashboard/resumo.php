<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/database.php';

try {

    $totalClientes = (int) $pdo
        ->query("SELECT COUNT(*) FROM clientes")
        ->fetchColumn();

    $totalAgendamentos = (int) $pdo
        ->query("SELECT COUNT(*) FROM agendamentos")
        ->fetchColumn();

    $faturamentoServicos = (float) $pdo
        ->query("
            SELECT COALESCE(SUM(valor), 0)
            FROM agendamentos
            WHERE status = 'Concluido'
        ")
        ->fetchColumn();

    $faturamentoVendas = (float) $pdo
        ->query("
            SELECT COALESCE(SUM(valor_total), 0)
            FROM vendas
        ")
        ->fetchColumn();

    $produtos = $pdo
        ->query("
            SELECT
                id,
                nome,
                preco_venda,
                estoque,
                estoque_minimo
            FROM produtos
            WHERE ativo = TRUE
            ORDER BY nome ASC
        ")
        ->fetchAll();
//CTE AQ
    $rankingServicos = $pdo
        ->query("
            WITH ranking AS (
                SELECT
                    s.id,
                    s.nome AS servico,
                    COUNT(a.id) AS quantidade,
                    COALESCE(SUM(a.valor), 0) AS faturamento
                FROM servicos s
                LEFT JOIN agendamentos a
                    ON s.id = a.servico_id
                    AND a.status = 'Concluido'
                GROUP BY s.id, s.nome
            )
            SELECT
                id,
                servico,
                quantidade,
                faturamento
            FROM ranking
            ORDER BY quantidade DESC, servico ASC
        ")
        ->fetchAll();

    $itensVenda = $pdo
        ->query("
            SELECT
                iv.produto_id,
                p.nome AS produto,
                iv.quantidade,
                iv.valor_unitario,
                iv.subtotal
            FROM itens_venda iv
            INNER JOIN produtos p
                ON iv.produto_id = p.id
            ORDER BY iv.id DESC
        ")
        ->fetchAll();

    $stmt = $pdo->prepare("
        CALL sp_buscar_agendamentos(
            :busca,
            :status,
            :pagina,
            :limite
        )
    ");

    $stmt->execute([
        ':busca' => '',
        ':status' => '',
        ':pagina' => 1,
        ':limite' => 5
    ]);

    $ultimosAgendamentos = $stmt->fetchAll();

    while ($stmt->nextRowset()) {
    }

    echo json_encode([
        'sucesso' => true,
        'dados' => [
            'total_clientes' => $totalClientes,
            'total_agendamentos' => $totalAgendamentos,
            'faturamento_servicos' => $faturamentoServicos,
            'faturamento_vendas' => $faturamentoVendas,
            'produtos' => $produtos,
            'ranking_servicos' => $rankingServicos,
            'itens_venda' => $itensVenda,
            'ultimos_agendamentos' => $ultimosAgendamentos
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao carregar os dados do dashboard.',
        'dados' => []
    ], JSON_UNESCAPED_UNICODE);
}