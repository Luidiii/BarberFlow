<?php

require_once 'config/database.php';

$servicos = $pdo
    ->query("
        SELECT id, nome, descricao, preco, duracao_minutos
        FROM servicos
        WHERE ativo = TRUE
        ORDER BY id DESC
        LIMIT 3
    ")
    ->fetchAll();

$profissionais = $pdo
    ->query("
        SELECT id, nome, especialidade
        FROM profissionais
        WHERE ativo = TRUE
        ORDER BY id DESC
        LIMIT 3
    ")
    ->fetchAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BarberFlow - Barbearia</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/site.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            💈 BarberFlow
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menuPrincipal"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">

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
                    <a class="nav-link" href="#galeria">
                        Galeria
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contato.php">
                        Contato
                    </a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a class="btn btn-light" href="agendamento.php">
                        Agendar
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>


<section class="hero text-white">

    <div class="container">

        <div class="row align-items-center min-vh-75">

            <div class="col-lg-7">

                <p class="text-uppercase fw-bold mb-2">
                    Estilo • Precisão • Qualidade
                </p>

                <h1 class="display-3 fw-bold">
                    Seu estilo começa
                    <span>na cadeira.</span>
                </h1>

                <p class="lead mt-4">
                    Cortes modernos, atendimento profissional
                    e aquele visual que combina com você.
                </p>

                <div class="mt-4">

                    <a
                        href="agendamento.php"
                        class="btn btn-light btn-lg me-2"
                    >
                        Agendar horário
                    </a>

                    <a
                        href="servicos.php"
                        class="btn btn-outline-light btn-lg"
                    >
                        Ver serviços
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <p class="text-muted mb-1">
                O que oferecemos
            </p>

            <h2 class="fw-bold">
                Nossos serviços
            </h2>

        </div>

        <div class="row g-4">

            <?php if (empty($servicos)): ?>

                <div class="col-12">

                    <div class="alert alert-secondary text-center">
                        Nenhum serviço disponível no momento.
                    </div>

                </div>

            <?php else: ?>

                <?php foreach ($servicos as $servico): ?>

                    <div class="col-md-4">

                        <div class="card service-card border-0 shadow-sm h-100">

                            <div class="card-body p-4">

                                <div class="service-icon mb-3">
                                    ✂️
                                </div>

                                <h4 class="fw-bold">
                                    <?= htmlspecialchars($servico['nome']) ?>
                                </h4>

                                <p class="text-muted">
                                    <?= htmlspecialchars($servico['descricao'] ?? '') ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-4">

                                    <strong class="fs-5">
                                        R$ <?= number_format($servico['preco'], 2, ',', '.') ?>
                                    </strong>

                                    <span class="text-muted">
                                        <?= (int) $servico['duracao_minutos'] ?> min
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

        <div class="text-center mt-4">

            <a
                href="servicos.php"
                class="btn btn-dark"
            >
                Ver todos os serviços
            </a>

        </div>

    </div>

</section>


<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <p class="text-muted mb-1">
                Nossa equipe
            </p>

            <h2 class="fw-bold">
                Conheça nossos barbeiros
            </h2>

        </div>

        <div class="row g-4">

            <?php if (empty($profissionais)): ?>

                <div class="col-12">

                    <div class="alert alert-secondary text-center">
                        Nenhum profissional disponível no momento.
                    </div>

                </div>

            <?php else: ?>

                <?php foreach ($profissionais as $profissional): ?>

                    <div class="col-md-4">

                        <div class="card team-card border-0 shadow-sm text-center h-100">

                            <div class="card-body p-4">

                                <div class="barber-avatar mb-3">
                                    💈
                                </div>

                                <h4 class="fw-bold">
                                    <?= htmlspecialchars($profissional['nome']) ?>
                                </h4>

                                <p class="text-muted mb-0">
                                    <?= htmlspecialchars($profissional['especialidade']) ?>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

        <div class="text-center mt-4">

            <a
                href="barbeiros.php"
                class="btn btn-dark"
            >
                Conhecer equipe
            </a>

        </div>

    </div>

</section>


<section id="galeria" class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <p class="text-muted mb-1">
                Nosso trabalho
            </p>

            <h2 class="fw-bold">
                Galeria
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">

                <div class="gallery-container">
                    <img
                        src="assets/img/galeria/corte1.jpg"
                        class="gallery-photo"
                        alt="Corte de cabelo realizado na barbearia"
                    >
                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="gallery-container">
                    <img
                        src="assets/img/galeria/corte2.jpg"
                        class="gallery-photo"
                        alt="Corte de cabelo realizado na barbearia"
                    >
                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="gallery-container">
                    <img
                        src="assets/img/galeria/corte3.jpg"
                        class="gallery-photo"
                        alt="Corte de cabelo realizado na barbearia"
                    >
                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="gallery-container">
                    <img
                        src="assets/img/galeria/corte4.jpg"
                        class="gallery-photo"
                        alt="Corte de cabelo realizado na barbearia"
                    >
                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="gallery-container">
                    <img
                        src="assets/img/galeria/corte5.jpg"
                        class="gallery-photo"
                        alt="Corte de cabelo realizado na barbearia"
                    >
                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="gallery-container">
                    <img
                        src="assets/img/galeria/corte6.jpg"
                        class="gallery-photo"
                        alt="Corte de cabelo realizado na barbearia"
                    >
                </div>

            </div>

        </div>

    </div>

</section>


<section class="cta-section py-5">

    <div class="container text-center">

        <h2 class="fw-bold">
            Pronto para mudar o visual?
        </h2>

        <p class="text-muted">
            Escolha seu serviço e agende seu horário.
        </p>

        <a
            href="agendamento.php"
            class="btn btn-dark btn-lg"
        >
            Agendar agora
        </a>

    </div>

</section>


<footer class="bg-dark text-white text-center py-4">

    <div class="container">

        <p class="mb-1 fw-bold">
            💈 BarberFlow
        </p>

        <p class="mb-0 text-secondary">
            © 2026 BarberFlow - Sistema para Barbearia
        </p>

    </div>

</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>