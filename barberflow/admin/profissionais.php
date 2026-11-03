<?php

$titulo = 'Profissionais';

require_once '../config/database.php';
require_once '../templates/header.php';
require_once '../templates/sidebar.php';

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold mb-1">Profissionais</h1>
        <p class="text-muted mb-0">
            Gerencie os barbeiros da barbearia.
        </p>
    </div>

    <button
        type="button"
        class="btn btn-dark"
        data-bs-toggle="modal"
        data-bs-target="#modalProfissional"
        id="btnNovoProfissional"
    >
        + Novo Profissional
    </button>

</div>

<div id="mensagem"></div>

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>

                </thead>

                <tbody id="tabelaProfissionais">

                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Carregando profissionais...
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div
    class="modal fade"
    id="modalProfissional"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="tituloModal">
                    Novo Profissional
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>

            </div>

            <form id="formProfissional">

                <div class="modal-body">

                    <input
                        type="hidden"
                        id="profissionalId"
                    >

                    <div class="mb-3">

                        <label
                            for="nome"
                            class="form-label"
                        >
                            Nome
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nome"
                            maxlength="100"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label
                            for="especialidade"
                            class="form-label"
                        >
                            Especialidade
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="especialidade"
                            maxlength="100"
                            required
                        >

                    </div>

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="ativo"
                            checked
                        >

                        <label
                            class="form-check-label"
                            for="ativo"
                        >
                            Profissional ativo
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        Salvar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php

require_once '../templates/footer.php';

?>

<script src="../assets/js/profissionais.js"></script>

?>