<?php

$titulo = 'Agendamentos';

require_once '../config/database.php';

$clientes = $pdo->query("
    SELECT id, nome
    FROM clientes
    ORDER BY nome ASC
")->fetchAll();

$profissionais = $pdo->query("
    SELECT id, nome
    FROM profissionais
    WHERE ativo = TRUE
    ORDER BY nome ASC
")->fetchAll();

$servicos = $pdo->query("
    SELECT id, nome, preco
    FROM servicos
    WHERE ativo = TRUE
    ORDER BY nome ASC
")->fetchAll();

require_once '../templates/header.php';
require_once '../templates/sidebar.php';

?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">Agendamentos</h1>

            <p class="text-muted mb-0">
                Gerencie os horários e atendimentos da barbearia.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-dark"
            data-bs-toggle="modal"
            data-bs-target="#modalNovoAgendamento">
            + Novo agendamento
        </button>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                <div class="col-md-5">

                    <label
                        for="buscaAgendamento"
                        class="form-label">
                        Buscar cliente
                    </label>

                    <input
                        type="text"
                        id="buscaAgendamento"
                        class="form-control"
                        placeholder="Digite o nome do cliente">

                </div>

                <div class="col-md-4">

                    <label
                        for="filtroStatus"
                        class="form-label">
                        Status
                    </label>

                    <select
                        id="filtroStatus"
                        class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="Agendado">
                            Agendado
                        </option>

                        <option value="Concluido">
                            Concluído
                        </option>

                        <option value="Cancelado">
                            Cancelado
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <button
                        type="button"
                        id="btnBuscar"
                        class="btn btn-dark w-100">
                        🔎 Buscar
                    </button>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Profissional</th>
                            <th>Serviço</th>
                            <th>Data e hora</th>
                            <th>Status</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>

                    </thead>

                    <tbody id="tabelaAgendamentos">

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-4">
                                Carregando agendamentos...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">

                <button
                    type="button"
                    id="btnPaginaAnterior"
                    class="btn btn-outline-dark">
                    ← Anterior
                </button>

                <span
                    id="infoPagina"
                    class="text-muted">
                    Página 1
                </span>

                <button
                    type="button"
                    id="btnProximaPagina"
                    class="btn btn-outline-dark">
                    Próxima →
                </button>

            </div>

        </div>

    </div>

</div>


<div
    class="modal fade"
    id="modalNovoAgendamento"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Novo agendamento
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formNovoAgendamento">

                    <div class="mb-3">

                        <label
                            for="novo_cliente_id"
                            class="form-label">
                            Cliente
                        </label>

                        <select
                            id="novo_cliente_id"
                            name="cliente_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o cliente
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
                            for="novo_profissional_id"
                            class="form-label">
                            Profissional
                        </label>

                        <select
                            id="novo_profissional_id"
                            name="profissional_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o profissional
                            </option>

                            <?php foreach ($profissionais as $profissional): ?>

                                <option
                                    value="<?= $profissional['id'] ?>">
                                    <?= htmlspecialchars($profissional['nome']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label
                            for="novo_servico_id"
                            class="form-label">
                            Serviço
                        </label>

                        <select
                            id="novo_servico_id"
                            name="servico_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o serviço
                            </option>

                            <?php foreach ($servicos as $servico): ?>

                                <option
                                    value="<?= $servico['id'] ?>">
                                    <?= htmlspecialchars($servico['nome']) ?>
                                    - R$
                                    <?= number_format(
                                        $servico['preco'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label
                            for="novo_data_hora"
                            class="form-label">
                            Data e hora
                        </label>

                        <input
                            type="datetime-local"
                            id="novo_data_hora"
                            name="data_hora"
                            class="form-control"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="novo_status"
                            class="form-label">
                            Status
                        </label>

                        <select
                            id="novo_status"
                            name="status"
                            class="form-select">

                            <option value="Agendado">
                                Agendado
                            </option>

                            <option value="Concluido">
                                Concluído
                            </option>

                            <option value="Cancelado">
                                Cancelado
                            </option>

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100">
                        Cadastrar agendamento
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<div
    class="modal fade"
    id="modalEditarAgendamento"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Editar agendamento
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="formEditarAgendamento">

                    <input
                        type="hidden"
                        id="editar_id"
                        name="id">


                    <div class="mb-3">

                        <label
                            for="editar_cliente_id"
                            class="form-label">
                            Cliente
                        </label>

                        <select
                            id="editar_cliente_id"
                            name="cliente_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o cliente
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
                            for="editar_profissional_id"
                            class="form-label">
                            Profissional
                        </label>

                        <select
                            id="editar_profissional_id"
                            name="profissional_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o profissional
                            </option>

                            <?php foreach ($profissionais as $profissional): ?>

                                <option
                                    value="<?= $profissional['id'] ?>">
                                    <?= htmlspecialchars($profissional['nome']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_servico_id"
                            class="form-label">
                            Serviço
                        </label>

                        <select
                            id="editar_servico_id"
                            name="servico_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o serviço
                            </option>

                            <?php foreach ($servicos as $servico): ?>

                                <option
                                    value="<?= $servico['id'] ?>">
                                    <?= htmlspecialchars($servico['nome']) ?>
                                    - R$
                                    <?= number_format(
                                        $servico['preco'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_data_hora"
                            class="form-label">
                            Data e hora
                        </label>

                        <input
                            type="datetime-local"
                            id="editar_data_hora"
                            name="data_hora"
                            class="form-control"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            for="editar_status"
                            class="form-label">
                            Status
                        </label>

                        <select
                            id="editar_status"
                            name="status"
                            class="form-select">

                            <option value="Agendado">
                                Agendado
                            </option>

                            <option value="Concluido">
                                Concluído
                            </option>

                            <option value="Cancelado">
                                Cancelado
                            </option>

                        </select>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100">
                        Salvar alterações
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<script
    type="module"
    src="../assets/js/agendamentos.js">
</script>


<?php require_once '../templates/footer.php'; ?>