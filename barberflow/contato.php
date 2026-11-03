<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contato - BarberFlow</title>

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
                    <a class="nav-link" href="agendamento.php">
                        Agendamento
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="contato.php">
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
                Entre em Contato
            </h1>

            <p class="text-muted">
                Ficou com alguma dúvida? Fale com a nossa equipe.
            </p>

        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100 text-center">

                    <div class="card-body p-4">

                        <div class="fs-1 mb-3">
                            📍
                        </div>

                        <h4 class="fw-bold">
                            Endereço
                        </h4>

                        <p class="text-muted mb-0">
                            Rua Principal, 123<br>
                            Centro
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100 text-center">

                    <div class="card-body p-4">

                        <div class="fs-1 mb-3">
                            📞
                        </div>

                        <h4 class="fw-bold">
                            Telefone
                        </h4>

                        <p class="text-muted mb-0">
                            (44) 98433-3478
                        </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100 text-center">

                    <div class="card-body p-4">

                        <div class="fs-1 mb-3">
                            📱
                        </div>

                        <h4 class="fw-bold">
                            WhatsApp
                        </h4>

                        <p class="text-muted mb-3">
                            Fale diretamente conosco.
                        </p>

                        <a
                            href="https://wa.me/5544984333478"
                            class="btn btn-dark"
                            target="_blank"
                        >
                            Conversar no WhatsApp
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="row justify-content-center mt-5">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <h3 class="fw-bold mb-4">
                            Envie uma mensagem
                        </h3>

                        <form id="formContato">

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
                                    required
                                >

                            </div>

                            <div class="mb-3">

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
                                    required
                                >

                            </div>

                            <div class="mb-3">

                                <label
                                    for="mensagem"
                                    class="form-label"
                                >
                                    Mensagem
                                </label>

                                <textarea
                                    class="form-control"
                                    id="mensagem"
                                    rows="5"
                                    required
                                ></textarea>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-dark w-100"
                            >
                                Enviar mensagem
                            </button>

                        </form>

                        <div id="mensagemSucesso"></div>

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

const formContato = document.getElementById('formContato');
const mensagemSucesso = document.getElementById('mensagemSucesso');

formContato.addEventListener('submit', function(event) {

    event.preventDefault();

    mensagemSucesso.innerHTML = `
        <div class="alert alert-success mt-4">
            Mensagem enviada com sucesso! Em breve nossa equipe entrará em contato.
        </div>
    `;

    formContato.reset();

});

</script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>