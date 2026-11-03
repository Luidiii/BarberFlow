async function carregarDashboard() {
    try {
        const resposta = await fetch('../api/dashboard/resumo.php');
        if (!resposta.ok) {
            throw new Error('Erro na comunicação com a API.');
        }
        const resultado = await resposta.json();
        if (!resultado.sucesso) {
            throw new Error(resultado.mensagem ||
                'Não foi possível carregar o dashboard.');
        }
        atualizarCards(resultado.dados);
        atualizarGraficoServicos(resultado.dados.ranking_servicos);
        atualizarEstoque(resultado.dados.produtos);
        atualizarRankingServicos(resultado.dados.ranking_servicos);
        atualizarRankingProdutos(resultado.dados.itens_venda);
        atualizarUltimosAgendamentos(resultado.dados.ultimos_agendamentos);
    }
    catch (erro) {
        mostrarErro();
    }
}
function atualizarCards(dados) {
    const faturamentoServicos = Number(dados.faturamento_servicos);
    const faturamentoVendas = dados.itens_venda.reduce((total, item) => {
        const quantidade = Number(item.quantidade);
        const valorUnitario = Number(item.valor_unitario);
        return total +
            (quantidade * valorUnitario);
    }, 0);
    const faturamentoTotal = [
        faturamentoServicos,
        faturamentoVendas
    ].reduce((total, valor) => total + valor, 0);
    const clientes = document.getElementById('totalClientes');
    const agendamentos = document.getElementById('totalAgendamentos');
    const faturamento = document.getElementById('faturamentoTotal');
    if (clientes instanceof HTMLElement) {
        clientes.textContent =
            String(dados.total_clientes);
    }
    if (agendamentos instanceof HTMLElement) {
        agendamentos.textContent =
            String(dados.total_agendamentos);
    }
    if (faturamento instanceof HTMLElement) {
        faturamento.textContent =
            formatarMoeda(faturamentoTotal);
    }
}
function atualizarGraficoServicos(ranking) {
    const container = document.getElementById('graficoServicos');
    if (!(container instanceof HTMLElement))
        return;
    if (ranking.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-4">
                Nenhum dado registrado.
            </div>
        `;
        return;
    }
    const faturamentos = ranking.map((item) => Number(item.faturamento));
    const maiorFaturamento = Math.max(...faturamentos, 1);
    container.innerHTML = ranking
        .map((item) => {
        const faturamento = Number(item.faturamento);
        const percentual = (faturamento / maiorFaturamento) * 100;
        return `
                    <div class="mb-4">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="fw-semibold">
                                ${item.servico}
                            </span>

                            <span class="text-muted">
                                ${formatarMoeda(faturamento)}
                            </span>

                        </div>

                        <div
                            class="progress"
                            style="height: 25px;"
                        >

                            <div
                                class="progress-bar bg-dark"
                                role="progressbar"
                                style="width: ${percentual}%"
                                aria-valuenow="${faturamento}"
                                aria-valuemin="0"
                                aria-valuemax="${maiorFaturamento}"
                            >
                                ${formatarMoeda(faturamento)}
                            </div>

                        </div>

                    </div>
                `;
    })
        .join('');
}
function atualizarEstoque(produtos) {
    const container = document.getElementById('listaEstoque');
    if (!(container instanceof HTMLElement))
        return;
    const produtosCriticos = produtos.filter((produto) => {
        const estoque = Number(produto.estoque);
        const minimo = Number(produto.estoque_minimo);
        return estoque <= minimo;
    });
    if (produtosCriticos.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                Nenhum produto com estoque baixo.
            </div>
        `;
        return;
    }
    container.innerHTML =
        produtosCriticos
            .map((produto) => {
            const estoque = Number(produto.estoque);
            const minimo = Number(produto.estoque_minimo);
            return `
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                            <div>

                                <strong>
                                    ${produto.nome}
                                </strong>

                                <small class="d-block text-muted">
                                    Mínimo: ${minimo}
                                </small>

                            </div>

                            <span class="badge bg-danger">
                                ${estoque} unidades
                            </span>

                        </div>
                    `;
        })
            .join('');
}
function atualizarRankingServicos(ranking) {
    const container = document.getElementById('rankingServicos');
    if (!(container instanceof HTMLElement))
        return;
    if (ranking.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                Nenhum dado registrado.
            </div>
        `;
        return;
    }
    const rankingFormatado = ranking
        .map((item, index) => `

                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                        <div>

                            <span class="fw-bold me-2">
                                ${index + 1}º
                            </span>

                            ${item.servico}

                        </div>

                        <span class="badge bg-dark">
                            ${Number(item.quantidade)}
                            atendimentos
                        </span>

                    </div>

                `)
        .join('');
    container.innerHTML =
        rankingFormatado;
}
function atualizarRankingProdutos(itens) {
    const container = document.getElementById('rankingProdutos');
    if (!(container instanceof HTMLElement))
        return;
    if (itens.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                Nenhum dado registrado.
            </div>
        `;
        return;
    }
    const frequencia = itens.reduce((acumulado, item) => {
        const nome = item.produto;
        const quantidade = Number(item.quantidade);
        acumulado[nome] =
            (acumulado[nome] || 0) +
                quantidade;
        return acumulado;
    }, {});
    const ranking = Object.entries(frequencia)
        .map(([produto, quantidade]) => ({
        produto,
        quantidade
    }))
        .sort((primeiro, segundo) => segundo.quantidade -
        primeiro.quantidade);
    container.innerHTML =
        ranking
            .map((item, index) => `

                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                        <div>

                            <span class="fw-bold me-2">
                                ${index + 1}º
                            </span>

                            ${item.produto}

                        </div>

                        <span class="badge bg-dark">
                            ${item.quantidade} vendidos
                        </span>

                    </div>

                `)
            .join('');
}
function atualizarUltimosAgendamentos(agendamentos) {
    const container = document.getElementById('ultimosAgendamentos');
    if (!(container instanceof HTMLElement))
        return;
    if (agendamentos.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                Nenhum agendamento registrado.
            </div>
        `;
        return;
    }
    container.innerHTML =
        agendamentos
            .map((agendamento) => {
            const data = new Date(agendamento.data_hora
                .replace(' ', 'T'));
            const dataFormatada = data.toLocaleDateString('pt-BR');
            const horaFormatada = data.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            return `
                        <div class="border-bottom py-3">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    ${agendamento.cliente}
                                </strong>

                                <span class="badge ${classeStatus(agendamento.status)}">
                                    ${agendamento.status}
                                </span>

                            </div>

                            <div class="text-muted small">
                                ${agendamento.servico}
                                •
                                ${agendamento.profissional}
                            </div>

                            <div class="text-muted small">
                                ${dataFormatada}
                                às
                                ${horaFormatada}
                            </div>

                        </div>
                    `;
        })
            .join('');
}
function classeStatus(status) {
    if (status === 'Concluido') {
        return 'bg-success';
    }
    if (status === 'Cancelado') {
        return 'bg-danger';
    }
    return 'bg-warning text-dark';
}
function formatarMoeda(valor) {
    if (!Number.isFinite(valor)) {
        return 'R$ 0,00';
    }
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}
function mostrarErro() {
    const container = document.getElementById('mensagemDashboard');
    if (container instanceof HTMLElement) {
        container.textContent =
            'Não foi possível carregar os dados do dashboard.';
        container.classList.remove('d-none');
    }
}
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
export {};
