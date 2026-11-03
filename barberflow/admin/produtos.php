<?php

$titulo = 'Produtos';

require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Produtos</h1>
            <p class="text-muted">
                Gerencie os produtos e o estoque da barbearia.
            </p>
        </div>

        <button
            class="btn btn-dark"
            data-bs-toggle="modal"
            data-bs-target="#modalProduto">
            + Novo produto
        </button>
    </div>

    <div id="mensagem"></div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Mínimo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody id="tabelaProdutos">

                        <tr>
                            <td colspan="8" class="text-center">
                                Carregando produtos...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="modalProduto" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Novo produto
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formProduto">

                    <div class="mb-3">

                        <label
                            for="nome"
                            class="form-label">
                            Nome do produto
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            class="form-control"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="categoria"
                            class="form-label">
                            Categoria
                        </label>

                        <input
                            type="text"
                            id="categoria"
                            name="categoria"
                            class="form-control">

                    </div>


                    <div class="mb-3">

                        <label
                            for="preco_venda"
                            class="form-label">
                            Preço de venda
                        </label>

                        <input
                            type="number"
                            id="preco_venda"
                            name="preco_venda"
                            class="form-control"
                            step="0.01"
                            min="0"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="estoque"
                            class="form-label">
                            Estoque
                        </label>

                        <input
                            type="number"
                            id="estoque"
                            name="estoque"
                            class="form-control"
                            min="0"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="estoque_minimo"
                            class="form-label">
                            Estoque mínimo
                        </label>

                        <input
                            type="number"
                            id="estoque_minimo"
                            name="estoque_minimo"
                            class="form-control"
                            min="0"
                            value="5"
                            required>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100">
                        Cadastrar produto
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="modalEditarProduto" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Editar produto
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formEditarProduto">

                    <input
                        type="hidden"
                        id="editar_id"
                        name="id">


                    <div class="mb-3">

                        <label
                            for="editar_nome"
                            class="form-label">
                            Nome do produto
                        </label>

                        <input
                            type="text"
                            id="editar_nome"
                            name="nome"
                            class="form-control"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_categoria"
                            class="form-label">
                            Categoria
                        </label>

                        <input
                            type="text"
                            id="editar_categoria"
                            name="categoria"
                            class="form-control">

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_preco_venda"
                            class="form-label">
                            Preço de venda
                        </label>

                        <input
                            type="number"
                            id="editar_preco_venda"
                            name="preco_venda"
                            class="form-control"
                            step="0.01"
                            min="0"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_estoque"
                            class="form-label">
                            Estoque
                        </label>

                        <input
                            type="number"
                            id="editar_estoque"
                            name="estoque"
                            class="form-control"
                            min="0"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_estoque_minimo"
                            class="form-label">
                            Estoque mínimo
                        </label>

                        <input
                            type="number"
                            id="editar_estoque_minimo"
                            name="estoque_minimo"
                            class="form-control"
                            min="0"
                            required>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100">
                        Salvar alterações
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script
    type="module"
    src="../assets/js/produtos.js">
</script>


<?php require_once '../templates/footer.php'; ?>