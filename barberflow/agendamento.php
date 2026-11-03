<?php
require_once 'config/database.php';

$servicoSelecionado = isset($_GET['servico']) ? (int) $_GET['servico'] : 0;
$profissionalSelecionado = isset($_GET['profissional']) ? (int) $_GET['profissional'] : 0;

$stmt = $pdo->query("
    SELECT id, nome, descricao, preco, duracao_minutos
    FROM servicos
    WHERE ativo = TRUE
    ORDER BY nome
");
$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("
    SELECT id, nome, especialidade
    FROM profissionais
    WHERE ativo = TRUE
    ORDER BY nome
");
$profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agendamento - BarberFlow</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/site.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            💈 BarberFlow
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Início
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="servicos.php">
                        Serviços
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="barbeiros.php">
                        Barbeiros
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="agendamento.php">
                        Agendamento
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contato.php">
                        Contato
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h1 class="fw-bold">
                Faça seu Agendamento
            </h1>

            <p class="text-muted">
                Escolha o serviço, o barbeiro e o melhor horário para você.
            </p>

        </div>

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-body p-4 p-md-5">

                        <form id="formAgendamento">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label
                                        for="nome"
                                        class="form-label"
                                    >
                                        Nome completo
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nome"
                                        required
                                    >

                                </div>

                                <div class="col-md-6">

                                    <label
                                        for="telefone"
                                        class="form-label"
                                    >
                                        Telefone
                                    </label>

                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="telefone"
                                        placeholder="(44) 99999-9999"
                                        required
                                    >

                                </div>

                                <div class="col-12">

                                    <label
                                        for="email"
                                        class="form-label"
                                    >
                                        E-mail
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                    >

                                </div>

                                <div class="col-md-6">

                                    <label
                                        for="servico_id"
                                        class="form-label"
                                    >
                                        Serviço
                                    </label>

                                    <select
                                        class="form-select"
                                        id="servico_id"
                                        required
                                    >

                                        <option value="">
                                            Selecione um serviço
                                        </option>

                                        <?php foreach ($servicos as $servico): ?>

                                            <option
                                                value="<?= $servico['id'] ?>"
                                                <?= $servicoSelecionado === (int) $servico['id'] ? 'selected' : '' ?>
                                            >

                                                <?= htmlspecialchars($servico['nome']) ?>

                                                -
                                                R$ <?= number_format($servico['preco'], 2, ',', '.') ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label
                                        for="profissional_id"
                                        class="form-label"
                                    >
                                        Barbeiro
                                    </label>

                                    <select
                                        class="form-select"
                                        id="profissional_id"
                                        required
                                    >

                                        <option value="">
                                            Selecione um barbeiro
                                        </option>

                                        <?php foreach ($profissionais as $profissional): ?>

                                            <option
                                                value="<?= $profissional['id'] ?>"
                                                <?= $profissionalSelecionado === (int) $profissional['id'] ? 'selected' : '' ?>
                                            >

                                                <?= htmlspecialchars($profissional['nome']) ?>

                                                -
                                                <?= htmlspecialchars($profissional['especialidade']) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label
                                        for="data"
                                        class="form-label"
                                    >
                                        Data
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control"
                                        id="data"
                                        required
                                    >

                                </div>

                                <div class="col-md-6">

                                    <label
                                        for="hora"
                                        class="form-label"
                                    >
                                        Horário
                                    </label>

                                    <input
                                        type="time"
                                        class="form-control"
                                        id="hora"
                                        required
                                    >

                                </div>

                                <div class="col-12">

                                    <div id="mensagem"></div>

                                </div>

                                <div class="col-12">

                                    <button
                                        type="submit"
                                        class="btn btn-dark btn-lg w-100"
                                        id="btnAgendar"
                                    >
                                        Confirmar Agendamento
                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<footer class="bg-dark text-white text-center py-3">

    <p class="mb-0">
        BarberFlow © 2026 - Sistema para Barbearia
    </p>

</footer>

<script>
const form = document.getElementById('formAgendamento');
const mensagem = document.getElementById('mensagem');
const botao = document.getElementById('btnAgendar');

form.addEventListener('submit', async function(event) {

    event.preventDefault();

    const nome = document.getElementById('nome').value.trim();
    const telefone = document.getElementById('telefone').value.trim();
    const email = document.getElementById('email').value.trim();

    const servicoId = Number(
        document.getElementById('servico_id').value
    );

    const profissionalId = Number(
        document.getElementById('profissional_id').value
    );

    const data = document.getElementById('data').value;
    const hora = document.getElementById('hora').value;

    if (
        !nome ||
        !telefone ||
        !servicoId ||
        !profissionalId ||
        !data ||
        !hora
    ) {
        mensagem.innerHTML = `
            <div class="alert alert-warning">
                Preencha todos os campos obrigatórios.
            </div>
        `;

        return;
    }

    botao.disabled = true;
    botao.textContent = 'Agendando...';

    mensagem.innerHTML = '';

    try {

        const dadosCliente = new FormData();

        dadosCliente.append('nome', nome);
        dadosCliente.append('telefone', telefone);
        dadosCliente.append('email', email);

        const clienteResposta = await fetch(
            'api/clientes/cadastrar.php',
            {
                method: 'POST',
                body: dadosCliente
            }
        );

        const clienteResultado =
            await clienteResposta.json();

        if (!clienteResposta.ok) {

            throw new Error(
                clienteResultado.mensagem ||
                'Não foi possível cadastrar o cliente.'
            );

        }

        const clienteId =
            clienteResultado.id ??
            clienteResultado.cliente_id;

        if (!clienteId) {

            throw new Error(
                'A API não retornou o ID do cliente.'
            );

        }

        const dadosAgendamento = new FormData();

        dadosAgendamento.append(
            'cliente_id',
            clienteId
        );

        dadosAgendamento.append(
            'profissional_id',
            profissionalId
        );

        dadosAgendamento.append(
            'servico_id',
            servicoId
        );

        dadosAgendamento.append(
            'data_hora',
            `${data} ${hora}`
        );

        const agendamentoResposta = await fetch(
            'api/agendamentos/cadastrar.php',
            {
                method: 'POST',
                body: dadosAgendamento
            }
        );

        const agendamentoResultado =
            await agendamentoResposta.json();

        if (!agendamentoResposta.ok) {

            throw new Error(
                agendamentoResultado.mensagem ||
                'Não foi possível realizar o agendamento.'
            );

        }

        mensagem.innerHTML = `
            <div class="alert alert-success">
                <strong>Agendamento realizado com sucesso!</strong><br>
                Seu horário foi registrado na BarberFlow.
            </div>
        `;

        form.reset();

    } catch (erro) {

        mensagem.innerHTML = `
            <div class="alert alert-danger">
                ${erro.message}
            </div>
        `;

    } finally {

        botao.disabled = false;
        botao.textContent = 'Confirmar Agendamento';

    }

});
</script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>