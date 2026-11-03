<?php

$titulo = 'Vendas';

require_once '../config/database.php';

$clientes = $pdo->query("
    SELECT id, nome
    FROM clientes
    ORDER BY nome ASC
")->fetchAll();

$produtos = $pdo->query("
    SELECT id, nome, preco_venda, estoque
    FROM produtos
    WHERE ativo = TRUE
    ORDER BY nome ASC
")->fetchAll();

require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">Vendas</h1>

            <p class="text-muted">
                Registre as vendas de produtos da barbearia.
            </p>
        </div>

        <button
            class="btn btn-dark"
            data-bs-toggle="modal"
            data-bs-target="#modalVenda">
            + Nova venda
        </button>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Valor total</th>
                        </tr>

                    </thead>

                    <tbody id="tabelaVendas">

                        <tr>
                            <td
                                colspan="4"
                                class="text-center">
                                Carregando vendas...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="modalVenda" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Nova venda
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formVenda">

                    <div class="mb-3">

                        <label
                            for="cliente_id"
                            class="form-label">
                            Cliente
                        </label>

                        <select
                            id="cliente_id"
                            name="cliente_id"
                            class="form-select">

                            <option value="">
                                Venda sem cliente
                            </option>

                            <?php foreach ($clientes as $cliente): ?>

                                <option
                                    value="<?= $cliente['id'] ?>">
                                    <?= htmlspecialchars($cliente['nome']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label
                            for="produto_id"
                            class="form-label">
                            Produto
                        </label>

                        <select
                            id="produto_id"
                            name="produto_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o produto
                            </option>

                            <?php foreach ($produtos as $produto): ?>

                                <option
                                    value="<?= $produto['id'] ?>"
                                    data-estoque="<?= $produto['estoque'] ?>">

                                    <?= htmlspecialchars($produto['nome']) ?>

                                    -
                                    R$ <?= number_format(
                                        $produto['preco_venda'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>

                                    -
                                    Estoque: <?= $produto['estoque'] ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div
                        id="infoEstoque"
                        class="alert alert-secondary d-none">
                    </div>


                    <div class="mb-3">

                        <label
                            for="quantidade"
                            class="form-label">
                            Quantidade
                        </label>

                        <input
                            type="number"
                            id="quantidade"
                            name="quantidade"
                            class="form-control"
                            min="1"
                            value="1"
                            required>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100">
                        Registrar venda
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script
    type="module"
    src="../assets/js/vendas.js">
</script>

<?php require_once '../templates/footer.php'; ?>