<?php

$titulo = 'Clientes';

require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Clientes</h1>
            <p class="text-muted">Gerencie os clientes da barbearia.</p>
        </div>

        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCliente">
            + Novo cliente
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
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody id="tabelaClientes">
                        <tr>
                            <td colspan="5" class="text-center">
                                Carregando clientes...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="modalCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Novo cliente</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formCliente">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" id="telefone" name="telefone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Cadastrar cliente
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

<script
    type="module"
    src="../assets/js/clientes.js">
</script>

<?php require_once '../templates/footer.php'; ?>