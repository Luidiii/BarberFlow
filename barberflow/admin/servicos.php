<?php

$titulo = 'Serviços';

require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Serviços</h1>
            <p class="text-muted">Gerencie os serviços oferecidos pela barbearia.</p>
        </div>

        <button
            class="btn btn-dark"
            data-bs-toggle="modal"
            data-bs-target="#modalServico">
            + Novo serviço
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
                            <th>Serviço</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Duração</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody id="tabelaServicos">
                        <tr>
                            <td colspan="7" class="text-center">
                                Carregando serviços...
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="modalServico" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Novo serviço
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formServico">

                    <div class="mb-3">
                        <label for="nome" class="form-label">
                            Nome do serviço
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">
                            Descrição
                        </label>

                        <textarea
                            id="descricao"
                            name="descricao"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="preco" class="form-label">
                            Preço
                        </label>

                        <input
                            type="number"
                            id="preco"
                            name="preco"
                            class="form-control"
                            step="0.01"
                            min="0"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="duracao_minutos" class="form-label">
                            Duração em minutos
                        </label>

                        <input
                            type="number"
                            id="duracao_minutos"
                            name="duracao_minutos"
                            class="form-control"
                            min="1"
                            required>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-dark w-100">
                        Cadastrar serviço
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="modalEditarServico" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Editar serviço
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formEditarServico">

                    <input
                        type="hidden"
                        id="editar_id"
                        name="id">

                    <div class="mb-3">
                        <label for="editar_nome" class="form-label">
                            Nome do serviço
                        </label>

                        <input
                            type="text"
                            id="editar_nome"
                            name="nome"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editar_descricao" class="form-label">
                            Descrição
                        </label>

                        <textarea
                            id="editar_descricao"
                            name="descricao"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editar_preco" class="form-label">
                            Preço
                        </label>

                        <input
                            type="number"
                            id="editar_preco"
                            name="preco"
                            class="form-control"
                            step="0.01"
                            min="0"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editar_duracao" class="form-label">
                            Duração em minutos
                        </label>

                        <input
                            type="number"
                            id="editar_duracao"
                            name="duracao_minutos"
                            class="form-control"
                            min="1"
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
    src="../assets/js/servicos.js">
</script>

<?php require_once '../templates/footer.php'; ?>