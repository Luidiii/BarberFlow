let paginaAtual = 1;
const limitePorPagina = 5;
async function carregarAgendamentos() {
    const tabela = document.getElementById('tabelaAgendamentos');
    if (!(tabela instanceof HTMLElement)) {
        return;
    }
    const buscaElement = document.getElementById('buscaAgendamento');
    const statusElement = document.getElementById('filtroStatus');
    const busca = buscaElement instanceof HTMLInputElement
        ? buscaElement.value.trim()
        : '';
    const status = statusElement instanceof HTMLSelectElement
        ? statusElement.value
        : '';
    const parametros = new URLSearchParams({
        busca: busca,
        status: status,
        pagina: String(paginaAtual),
        limite: String(limitePorPagina)
    });
    try {
        const resposta = await fetch(`../api/agendamentos/buscar.php?${parametros.toString()}`);
        if (!resposta.ok) {
            throw new Error('Erro na comunicação com a API.');
        }
        const resultado = await resposta.json();
        if (!resultado.sucesso) {
            throw new Error(resultado.mensagem ||
                'Não foi possível carregar os agendamentos.');
        }
        if (resultado.dados.length === 0) {
            tabela.innerHTML = `
                <tr>
                    <td
                        colspan="8"
                        class="text-center text-muted py-4">
                        Nenhum agendamento encontrado.
                    </td>
                </tr>
            `;
            atualizarPaginacao(0);
            return;
        }
        tabela.innerHTML = resultado.dados
            .map((agendamento) => {
            const data = new Date(agendamento.data_hora.replace(' ', 'T'));
            const dataFormatada = data.toLocaleDateString('pt-BR');
            const horaFormatada = data.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const valor = Number(agendamento.valor);
            return `
                        <tr>

                            <td>
                                ${agendamento.id}
                            </td>

                            <td>
                                <strong>
                                    ${agendamento.cliente}
                                </strong>
                            </td>

                            <td>
                                ${agendamento.profissional}
                            </td>

                            <td>
                                ${agendamento.servico}
                            </td>

                            <td>
                                ${dataFormatada}
                                <br>
                                <small class="text-muted">
                                    ${horaFormatada}
                                </small>
                            </td>

                            <td>
                                <span
                                    class="badge ${classeStatus(agendamento.status)}">
                                    ${formatarStatus(agendamento.status)}
                                </span>
                            </td>

                            <td>
                                R$
                                ${Number.isFinite(valor)
                ? valor
                    .toFixed(2)
                    .replace('.', ',')
                : '0,00'}
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-editar="${agendamento.id}">
                                        Editar
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-excluir="${agendamento.id}">
                                        Excluir
                                    </button>

                                </div>
                            </td>

                        </tr>
                    `;
        })
            .join('');
        configurarBotoesTabela(resultado.dados);
        atualizarPaginacao(resultado.dados.length);
    }
    catch (erro) {
        tabela.innerHTML = `
            <tr>
                <td
                    colspan="8"
                    class="text-center text-danger py-4">
                    Não foi possível carregar os agendamentos.
                </td>
            </tr>
        `;
        atualizarPaginacao(0);
    }
}
function configurarBotoesTabela(agendamentos) {
    const botoesEditar = document.querySelectorAll('[data-editar]');
    botoesEditar.forEach((botao) => {
        botao.addEventListener('click', () => {
            const id = Number(botao.dataset.editar);
            const agendamento = agendamentos.find((item) => item.id === id);
            if (agendamento) {
                preencherFormularioEdicao(agendamento);
            }
        });
    });
    const botoesExcluir = document.querySelectorAll('[data-excluir]');
    botoesExcluir.forEach((botao) => {
        botao.addEventListener('click', () => {
            const id = Number(botao.dataset.excluir);
            excluirAgendamento(id);
        });
    });
}
function preencherFormularioEdicao(agendamento) {
    const id = document.getElementById('editar_id');
    const cliente = document.getElementById('editar_cliente_id');
    const profissional = document.getElementById('editar_profissional_id');
    const servico = document.getElementById('editar_servico_id');
    const dataHora = document.getElementById('editar_data_hora');
    const status = document.getElementById('editar_status');
    if (!(id instanceof HTMLInputElement) ||
        !(cliente instanceof HTMLSelectElement) ||
        !(profissional instanceof HTMLSelectElement) ||
        !(servico instanceof HTMLSelectElement) ||
        !(dataHora instanceof HTMLInputElement) ||
        !(status instanceof HTMLSelectElement)) {
        return;
    }
    id.value =
        String(agendamento.id);
    cliente.value =
        String(agendamento.cliente_id);
    profissional.value =
        String(agendamento.profissional_id);
    servico.value =
        String(agendamento.servico_id);
    dataHora.value =
        agendamento.data_hora
            .replace(' ', 'T')
            .slice(0, 16);
    status.value =
        agendamento.status;
    const modalElement = document.getElementById('modalEditarAgendamento');
    if (modalElement instanceof HTMLElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}
async function cadastrarAgendamento(form) {
    const dados = new FormData(form);
    try {
        const resposta = await fetch('../api/agendamentos/cadastrar.php', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if (!resposta.ok ||
            !resultado.sucesso) {
            alert(resultado.mensagem ||
                'Não foi possível cadastrar o agendamento.');
            return;
        }
        alert(resultado.mensagem);
        form.reset();
        fecharModal('modalNovoAgendamento');
        paginaAtual = 1;
        await carregarAgendamentos();
    }
    catch (erro) {
        alert('Não foi possível cadastrar o agendamento.');
    }
}
async function editarAgendamento(form) {
    const dados = new FormData(form);
    try {
        const resposta = await fetch('../api/agendamentos/editar.php', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if (!resposta.ok ||
            !resultado.sucesso) {
            alert(resultado.mensagem ||
                'Não foi possível editar o agendamento.');
            return;
        }
        alert(resultado.mensagem);
        fecharModal('modalEditarAgendamento');
        await carregarAgendamentos();
    }
    catch (erro) {
        alert('Não foi possível editar o agendamento.');
    }
}
async function excluirAgendamento(id) {
    const confirmar = confirm('Deseja realmente excluir este agendamento?');
    if (!confirmar) {
        return;
    }
    const dados = new FormData();
    dados.append('id', String(id));
    try {
        const resposta = await fetch('../api/agendamentos/excluir.php', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if (!resposta.ok ||
            !resultado.sucesso) {
            alert(resultado.mensagem ||
                'Não foi possível excluir o agendamento.');
            return;
        }
        alert(resultado.mensagem);
        await carregarAgendamentos();
    }
    catch (erro) {
        alert('Não foi possível excluir o agendamento.');
    }
}
function configurarBusca() {
    const botao = document.getElementById('btnBuscar');
    const busca = document.getElementById('buscaAgendamento');
    const status = document.getElementById('filtroStatus');
    if (botao instanceof HTMLButtonElement) {
        botao.addEventListener('click', () => {
            paginaAtual = 1;
            carregarAgendamentos();
        });
    }
    if (busca instanceof HTMLInputElement) {
        busca.addEventListener('keydown', (evento) => {
            if (evento.key === 'Enter') {
                paginaAtual = 1;
                carregarAgendamentos();
            }
        });
    }
    if (status instanceof HTMLSelectElement) {
        status.addEventListener('change', () => {
            paginaAtual = 1;
            carregarAgendamentos();
        });
    }
}
function configurarPaginacao() {
    const anterior = document.getElementById('btnPaginaAnterior');
    const proxima = document.getElementById('btnProximaPagina');
    if (anterior instanceof HTMLButtonElement) {
        anterior.addEventListener('click', () => {
            if (paginaAtual <= 1) {
                return;
            }
            paginaAtual--;
            carregarAgendamentos();
        });
    }
    if (proxima instanceof HTMLButtonElement) {
        proxima.addEventListener('click', () => {
            paginaAtual++;
            carregarAgendamentos();
        });
    }
}
function atualizarPaginacao(quantidadeResultados) {
    const info = document.getElementById('infoPagina');
    const anterior = document.getElementById('btnPaginaAnterior');
    const proxima = document.getElementById('btnProximaPagina');
    if (info instanceof HTMLElement) {
        info.textContent =
            `Página ${paginaAtual}`;
    }
    if (anterior instanceof HTMLButtonElement) {
        anterior.disabled =
            paginaAtual <= 1;
    }
    if (proxima instanceof HTMLButtonElement) {
        proxima.disabled =
            quantidadeResultados <
                limitePorPagina;
    }
}
function configurarFormularios() {
    const formularioNovo = document.getElementById('formNovoAgendamento');
    if (formularioNovo instanceof HTMLFormElement) {
        formularioNovo.addEventListener('submit', (evento) => {
            evento.preventDefault();
            cadastrarAgendamento(formularioNovo);
        });
    }
    const formularioEditar = document.getElementById('formEditarAgendamento');
    if (formularioEditar instanceof HTMLFormElement) {
        formularioEditar.addEventListener('submit', (evento) => {
            evento.preventDefault();
            editarAgendamento(formularioEditar);
        });
    }
}
function fecharModal(id) {
    const elemento = document.getElementById(id);
    if (!(elemento instanceof HTMLElement)) {
        return;
    }
    const modal = bootstrap.Modal.getInstance(elemento);
    if (modal !== null) {
        modal.hide();
    }
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
function formatarStatus(status) {
    if (status === 'Concluido') {
        return 'Concluído';
    }
    return status;
}
document.addEventListener('DOMContentLoaded', () => {
    configurarBusca();
    configurarPaginacao();
    configurarFormularios();
    carregarAgendamentos();
});
export {};
