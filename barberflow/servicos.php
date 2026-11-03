<?php
require_once 'config/database.php';

$titulo = 'Serviços';

$stmt = $pdo->query("
    SELECT id, nome, descricao, preco, duracao_minutos
    FROM servicos
    WHERE ativo = 1
    ORDER BY id
");

$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($titulo) ?> - BarberFlow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            data-bs-target="#menu">

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
                    <a class="nav-link active" href="servicos.php">
                        Serviços
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="barbeiros.php">
                        Barbeiros
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#galeria">
                        Galeria
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contato.php">
                        Contato
                    </a>
                </li>

            </ul>

            <a href="agendamento.php" class="btn btn-light ms-lg-3">
                Agendar horário
            </a>

        </div>

    </div>

</nav>


<section class="py-5 bg-dark text-white">

    <div class="container text-center py-4">

        <span class="text-uppercase text-white-50 fw-bold">
            BarberFlow
        </span>

        <h1 class="display-4 fw-bold mt-2">
            Nossos serviços
        </h1>

        <p class="lead text-white-50">
            Escolha o serviço ideal para o seu estilo.
        </p>

    </div>

</section>


<section class="py-5">

    <div class="container">

        <?php if (empty($servicos)): ?>

            <div class="alert alert-info text-center">
                Nenhum serviço disponível no momento.
            </div>

        <?php else: ?>

            <div class="row g-4">

                <?php foreach ($servicos as $servico): ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="card service-card h-100 border-0 shadow-sm">

                            <div class="card-body p-4 d-flex flex-column">

                                <div class="service-icon mb-3">
                                    ✂️
                                </div>

                                <h3 class="fw-bold">
                                    <?= htmlspecialchars($servico['nome']) ?>
                                </h3>

                                <p class="text-muted flex-grow-1">
                                    <?= htmlspecialchars(
                                        $servico['descricao'] ?: 'Serviço profissional de barbearia.'
                                    ) ?>
                                </p>

                                <div class="border-top pt-3 mt-3">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>
                                            <small class="text-muted">
                                                A partir de
                                            </small>

                                            <div class="fs-4 fw-bold">
                                                R$ <?= number_format(
                                                    $servico['preco'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>
                                            </div>
                                        </div>

                                        <div class="text-end">

                                            <small class="text-muted">
                                                Duração
                                            </small>

                                            <div class="fw-bold">
                                                <?= (int)$servico['duracao_minutos'] ?> min
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <a
                                    href="agendamento.php?servico=<?= (int)$servico['id'] ?>"
                                    class="btn btn-dark w-100 mt-4">

                                    Agendar este serviço

                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>


<section class="py-5 bg-light">

    <div class="container text-center">

        <h2 class="fw-bold">
            Encontrou o serviço ideal?
        </h2>

        <p class="text-muted">
            Não deixe para depois. Escolha um horário e venha renovar o visual.
        </p>

        <a href="agendamento.php" class="btn btn-dark btn-lg">
            Agendar agora
        </a>

    </div>

</section>


<footer class="bg-dark text-white py-4">

    <div class="container text-center">

        <h5 class="fw-bold">
            💈 BarberFlow
        </h5>

        <p class="text-white-50 mb-2">
            Seu estilo, nossa experiência.
        </p>

        <small>
            BarberFlow © 2026
        </small>

        <div class="mt-3">

            <a
                href="admin/dashboard.php"
                class="text-white-50 text-decoration-none">

                Área administrativa

            </a>

        </div>

    </div>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>