interface Servico {
    id: number;
    nome: string;
    descricao: string | null;
    preco: string;
    duracao_minutos: number;
    ativo: number;
}

interface RespostaListaServicos {
    sucesso: boolean;
    dados: Servico[];
    mensagem?: string;
}

interface RespostaServico {
    sucesso: boolean;
    mensagem: string;
    id?: number;
}

declare const bootstrap: {
    Modal: {
        new (element: HTMLElement): {
            show(): void;
        };
        getInstance(element: HTMLElement): {
            hide(): void;
        } | null;
    };
};

let servicosAtuais: Servico[] = [];

async function carregarServicos(): Promise<void> {

    const tabela = document.getElementById('tabelaServicos');

    if (!(tabela instanceof HTMLElement)) {
        return;
    }

    try {

        const resposta = await fetch('../api/servicos/listar.php');

        if (!resposta.ok) {
            throw new Error('Erro na comunicação com a API.');
        }

        const resultado: RespostaListaServicos = await resposta.json();

        if (!resultado.sucesso || resultado.dados.length === 0) {

            servicosAtuais = [];

            tabela.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Nenhum serviço registrado.
                    </td>
                </tr>
            `;

            return;
        }

        servicosAtuais = resultado.dados;

        tabela.innerHTML = resultado.dados.map((servico: Servico): string => `
            <tr>
                <td>${servico.id}</td>

                <td>${servico.nome}</td>

                <td>${servico.descricao ?? 'Não informado'}</td>

                <td>
                    R$ ${Number(servico.preco).toFixed(2).replace('.', ',')}
                </td>

                <td>
                    ${servico.duracao_minutos} min
                </td>

                <td>
                    ${
                        servico.ativo === 1
                            ? '<span class="badge text-bg-success">Ativo</span>'
                            : '<span class="badge text-bg-secondary">Inativo</span>'
                    }
                </td>

                <td>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary btn-editar"
                        data-id="${servico.id}">
                        Editar
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger btn-excluir"
                        data-id="${servico.id}">
                        Excluir
                    </button>
                </td>
            </tr>
        `).join('');

        configurarBotoes();

    } catch (erro: unknown) {

        tabela.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-danger">
                    Não foi possível carregar os serviços.
                </td>
            </tr>
        `;
    }
}

function configurarBotoes(): void {

    const botoesEditar = document.querySelectorAll('.btn-editar');

    botoesEditar.forEach((botao: Element): void => {

        if (!(botao instanceof HTMLButtonElement)) {
            return;
        }

        botao.addEventListener('click', (): void => {

            const id = Number(botao.dataset.id);

            if (!Number.isInteger(id) || id <= 0) {
                return;
            }

            abrirModalEdicao(id);
        });
    });

    const botoesExcluir = document.querySelectorAll('.btn-excluir');

    botoesExcluir.forEach((botao: Element): void => {

        if (!(botao instanceof HTMLButtonElement)) {
            return;
        }

        botao.addEventListener('click', (): void => {

            const id = Number(botao.dataset.id);

            if (!Number.isInteger(id) || id <= 0) {
                return;
            }

            excluirServico(id);
        });
    });
}

function abrirModalEdicao(id: number): void {

    const servico = servicosAtuais.find(
        (item: Servico): boolean => item.id === id
    );

    if (!servico) {

        alert('Serviço não encontrado.');

        return;
    }

    const campoId = document.getElementById('editar_id');
    const campoNome = document.getElementById('editar_nome');
    const campoDescricao = document.getElementById('editar_descricao');
    const campoPreco = document.getElementById('editar_preco');
    const campoDuracao = document.getElementById('editar_duracao');
    const modalElement = document.getElementById('modalEditarServico');

    if (
        !(campoId instanceof HTMLInputElement) ||
        !(campoNome instanceof HTMLInputElement) ||
        !(campoDescricao instanceof HTMLTextAreaElement) ||
        !(campoPreco instanceof HTMLInputElement) ||
        !(campoDuracao instanceof HTMLInputElement) ||
        !(modalElement instanceof HTMLElement)
    ) {
        return;
    }

    campoId.value = String(servico.id);
    campoNome.value = servico.nome;
    campoDescricao.value = servico.descricao ?? '';
    campoPreco.value = servico.preco;
    campoDuracao.value = String(servico.duracao_minutos);

    const modal = new bootstrap.Modal(modalElement);

    modal.show();
}

async function cadastrarServico(form: HTMLFormElement): Promise<void> {

    const dados = new FormData(form);

    try {

        const resposta = await fetch('../api/servicos/cadastrar.php', {
            method: 'POST',
            body: dados
        });

        const resultado: RespostaServico = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {

            alert(
                resultado.mensagem ||
                'Não foi possível cadastrar o serviço.'
            );

            return;
        }

        alert(resultado.mensagem);

        form.reset();

        const modalElement = document.getElementById('modalServico');

        if (modalElement instanceof HTMLElement) {

            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal !== null) {
                modal.hide();
            }
        }

        await carregarServicos();

    } catch (erro: unknown) {

        alert('Não foi possível cadastrar o serviço.');
    }
}

async function editarServico(form: HTMLFormElement): Promise<void> {

    const dados = new FormData(form);

    try {

        const resposta = await fetch('../api/servicos/editar.php', {
            method: 'POST',
            body: dados
        });

        const resultado: RespostaServico = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {

            alert(
                resultado.mensagem ||
                'Não foi possível atualizar o serviço.'
            );

            return;
        }

        alert(resultado.mensagem);

        const modalElement = document.getElementById(
            'modalEditarServico'
        );

        if (modalElement instanceof HTMLElement) {

            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal !== null) {
                modal.hide();
            }
        }

        await carregarServicos();

    } catch (erro: unknown) {

        alert('Não foi possível atualizar o serviço.');
    }
}

async function excluirServico(id: number): Promise<void> {

    const confirmar = confirm(
        'Tem certeza que deseja excluir este serviço?'
    );

    if (!confirmar) {
        return;
    }

    const dados = new FormData();

    dados.append('id', String(id));

    try {

        const resposta = await fetch('../api/servicos/excluir.php', {
            method: 'POST',
            body: dados
        });

        const resultado: RespostaServico = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {

            alert(
                resultado.mensagem ||
                'Não foi possível excluir o serviço.'
            );

            return;
        }

        alert(resultado.mensagem);

        await carregarServicos();

    } catch (erro: unknown) {

        alert('Não foi possível excluir o serviço.');
    }
}

document.addEventListener('DOMContentLoaded', (): void => {

    const formCadastro = document.getElementById('formServico');

    if (formCadastro instanceof HTMLFormElement) {

        formCadastro.addEventListener(
            'submit',
            (evento: SubmitEvent): void => {

                evento.preventDefault();

                cadastrarServico(formCadastro);
            }
        );
    }

    const formEdicao = document.getElementById(
        'formEditarServico'
    );

    if (formEdicao instanceof HTMLFormElement) {

        formEdicao.addEventListener(
            'submit',
            (evento: SubmitEvent): void => {

                evento.preventDefault();

                editarServico(formEdicao);
            }
        );
    }

    carregarServicos();
});

export {};