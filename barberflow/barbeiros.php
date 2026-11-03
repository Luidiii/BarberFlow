<?php
require_once 'config/database.php';

$titulo = 'Barbeiros';
$stmt = $pdo->query("
    SELECT id, nome, especialidade
    FROM profissionais
    WHERE ativo = TRUE
    ORDER BY nome
");
$barbeiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?> - BarberFlow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/site.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">💈 BarberFlow</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="servicos.php">Serviços</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="barbeiros.php">Barbeiros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="agendamento.php">Agendamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contato.php">Contato</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h1 class="fw-bold">Nossos Barbeiros</h1>
            <p class="text-muted">
                Conheça nossa equipe de profissionais
            </p>
        </div>

        <?php if (empty($barbeiros)): ?>

            <div class="alert alert-info text-center">
                Nenhum barbeiro disponível no momento.
            </div>

        <?php else: ?>

            <div class="row g-4">

                <?php foreach ($barbeiros as $barbeiro): ?>

                    <div class="col-md-6 col-lg-4">
                        <div class="card team-card h-100 shadow-sm border-0 text-center">

                            <div class="card-body p-4">

                                <div class="barber-avatar mb-3">
                                    💈
                                </div>

                                <h4 class="fw-bold">
                                    <?= htmlspecialchars($barbeiro['nome']) ?>
                                </h4>

                                <p class="text-muted mb-4">
                                    <?= htmlspecialchars($barbeiro['especialidade']) ?>
                                </p>

                                <a href="agendamento.php?profissional=<?= $barbeiro['id'] ?>"
                                   class="btn btn-dark">
                                    Agendar com este barbeiro
                                </a>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>

<section class="cta-section py-5">
    <div class="container text-center">
        <h2 class="fw-bold">Pronto para renovar o visual?</h2>
        <p class="text-muted">
            Escolha seu barbeiro e faça seu agendamento.
        </p>

        <a href="agendamento.php" class="btn btn-dark btn-lg">
            Agendar agora
        </a>
    </div>
</section>

<footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">BarberFlow © 2026 - Sistema para Barbearia</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>