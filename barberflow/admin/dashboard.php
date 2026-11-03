<?php

$titulo = 'Dashboard';

require_once '../templates/header.php';
require_once '../templates/sidebar.php';

?>

<div class="container-fluid">

    <div id="mensagemDashboard" class="alert alert-danger d-none mb-4"></div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="fw-bold">
                Dashboard
            </h1>

            <p class="text-muted mb-0">
                Visão geral da sua barbearia
            </p>

        </div>

    </div>

    <div class="row g-4">

        <div class="col-md-6 col-xl-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Clientes
                    </h6>

                    <h2 id="totalClientes" class="fw-bold">
                        ...
                    </h2>

                    <span class="text-muted">
                        clientes cadastrados
                    </span>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Agendamentos
                    </h6>

                    <h2 id="totalAgendamentos" class="fw-bold">
                        ...
                    </h2>

                    <span class="text-muted">
                        agendamentos registrados
                    </span>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h6 class="text-muted">
                        Faturamento total
                    </h6>

                    <h2 id="faturamentoTotal" class="fw-bold">
                        R$ 0,00
                    </h2>

                    <span class="text-muted">
                        serviços + vendas
                    </span>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mt-1">

        <div class="col-12">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        📊 Desempenho dos serviços
                    </h5>

                    <div id="graficoServicos">

                        <div class="text-center text-muted py-4">
                            Carregando...
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mt-1">

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        📦 Estoque baixo
                    </h5>

                    <div id="listaEstoque">

                        <div class="text-center text-muted py-3">
                            Carregando...
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        ✂️ Ranking de serviços
                    </h5>

                    <div id="rankingServicos">

                        <div class="text-center text-muted py-3">
                            Carregando...
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row g-4 mt-1">

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        🏆 Produtos mais vendidos
                    </h5>

                    <div id="rankingProdutos">

                        <div class="text-center text-muted py-3">
                            Carregando...
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        📅 Últimos agendamentos
                    </h5>

                    <div id="ultimosAgendamentos">

                        <div class="text-center text-muted py-3">
                            Carregando...
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script
    type="module"
    src="../assets/js/dashboard.js">
</script>

<?php require_once '../templates/footer.php'; ?>