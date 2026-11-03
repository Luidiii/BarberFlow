interface Cliente {
    id: number;
    nome: string;
    telefone: string;
    email: string | null;
    created_at: string;
}

interface RespostaListaClientes {
    sucesso: boolean;
    dados: Cliente[];
    mensagem?: string;
}

interface RespostaCliente {
    sucesso: boolean;
    mensagem: string;
    id?: number;
}

declare const bootstrap: {
    Modal: {
        getInstance(element: HTMLElement): {
            hide(): void;
        } | null;
    };
};

let clientesAtuais: Cliente[] = [];

async function carregarClientes(): Promise<void> {

    const tabela = document.getElementById('tabelaClientes');

    if (!(tabela instanceof HTMLElement)) {
        return;
    }

    try {

        const resposta = await fetch(
            '../api/clientes/listar.php'
        );

        if (!resposta.ok) {
            throw new Error('Erro na comunicação com a API.');
        }

        const resultado: RespostaListaClientes =
            await resposta.json();

        if (
            !resultado.sucesso ||
            resultado.dados.length === 0
        ) {

            clientesAtuais = [];

            tabela.innerHTML = `
                <tr>
                    <td
                        colspan="5"
                        class="text-center text-muted">
                        Nenhum cliente registrado.
                    </td>
                </tr>
            `;

            return;
        }

        clientesAtuais = resultado.dados;

        tabela.innerHTML = resultado.dados.map(
            (cliente: Cliente): string => `
                <tr>

                    <td>${cliente.id}</td>

                    <td>${cliente.nome}</td>

                    <td>${cliente.telefone}</td>

                    <td>
                        ${cliente.email ?? 'Não informado'}
                    </td>

                    <td>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary btn-editar-cliente"
                            data-id="${cliente.id}">
                            Editar
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger btn-excluir-cliente"
                            data-id="${cliente.id}">
                            Excluir
                        </button>

                    </td>

                </tr>
            `
        ).join('');

        configurarBotoes();

    } catch (erro: unknown) {

        tabela.innerHTML = `
            <tr>
                <td
                    colspan="5"
                    class="text-center text-danger">
                    Não foi possível carregar os clientes.
                </td>
            </tr>
        `;
    }
}

function configurarBotoes(): void {

    const botoesEditar =
        document.querySelectorAll(
            '.btn-editar-cliente'
        );

    botoesEditar.forEach(
        (botao: Element): void => {

            if (!(botao instanceof HTMLButtonElement)) {
                return;
            }

            botao.addEventListener(
                'click',
                (): void => {

                    const id =
                        Number(botao.dataset.id);

                    if (
                        !Number.isInteger(id) ||
                        id <= 0
                    ) {
                        return;
                    }

                    abrirModalEdicao(id);
                }
            );
        }
    );

    const botoesExcluir =
        document.querySelectorAll(
            '.btn-excluir-cliente'
        );

    botoesExcluir.forEach(
        (botao: Element): void => {

            if (!(botao instanceof HTMLButtonElement)) {
                return;
            }

            botao.addEventListener(
                'click',
                (): void => {

                    const id =
                        Number(botao.dataset.id);

                    if (
                        !Number.isInteger(id) ||
                        id <= 0
                    ) {
                        return;
                    }

                    excluirCliente(id);
                }
            );
        }
    );
}

function abrirModalEdicao(id: number): void {

    const cliente =
        clientesAtuais.find(
            (item: Cliente): boolean =>
                item.id === id
        );

    if (!cliente) {

        alert('Cliente não encontrado.');

        return;
    }

    const nome =
        prompt(
            'Nome do cliente:',
            cliente.nome
        );

    if (nome === null) {
        return;
    }

    const telefone =
        prompt(
            'Telefone do cliente:',
            cliente.telefone
        );

    if (telefone === null) {
        return;
    }

    const email =
        prompt(
            'E-mail do cliente:',
            cliente.email ?? ''
        );

    if (email === null) {
        return;
    }

    if (
        nome.trim() === '' ||
        telefone.trim() === ''
    ) {

        alert(
            'Nome e telefone são obrigatórios.'
        );

        return;
    }

    editarCliente(
        id,
        nome.trim(),
        telefone.trim(),
        email.trim()
    );
}

async function cadastrarCliente(
    form: HTMLFormElement
): Promise<void> {

    const dados =
        new FormData(form);

    try {

        const resposta =
            await fetch(
                '../api/clientes/cadastrar.php',
                {
                    method: 'POST',
                    body: dados
                }
            );

        const resultado: RespostaCliente =
            await resposta.json();

        if (
            !resposta.ok ||
            !resultado.sucesso
        ) {

            alert(
                resultado.mensagem ||
                'Não foi possível cadastrar o cliente.'
            );

            return;
        }

        alert(resultado.mensagem);

        form.reset();

        const modalElement =
            document.getElementById(
                'modalCliente'
            );

        if (
            modalElement instanceof HTMLElement
        ) {

            const modal =
                bootstrap.Modal.getInstance(
                    modalElement
                );

            if (modal !== null) {
                modal.hide();
            }
        }

        await carregarClientes();

    } catch (erro: unknown) {

        alert(
            'Não foi possível cadastrar o cliente.'
        );
    }
}

async function editarCliente(
    id: number,
    nome: string,
    telefone: string,
    email: string
): Promise<void> {

    const dados =
        new FormData();

    dados.append(
        'id',
        String(id)
    );

    dados.append(
        'nome',
        nome
    );

    dados.append(
        'telefone',
        telefone
    );

    dados.append(
        'email',
        email
    );

    try {

        const resposta =
            await fetch(
                '../api/clientes/editar.php',
                {
                    method: 'POST',
                    body: dados
                }
            );

        const resultado: RespostaCliente =
            await resposta.json();

        if (
            !resposta.ok ||
            !resultado.sucesso
        ) {

            alert(
                resultado.mensagem ||
                'Não foi possível atualizar o cliente.'
            );

            return;
        }

        alert(resultado.mensagem);

        await carregarClientes();

    } catch (erro: unknown) {

        alert(
            'Não foi possível atualizar o cliente.'
        );
    }
}

async function excluirCliente(
    id: number
): Promise<void> {

    const cliente =
        clientesAtuais.find(
            (item: Cliente): boolean =>
                item.id === id
        );

    if (!cliente) {
        return;
    }

    const confirmar =
        confirm(
            `Tem certeza que deseja excluir o cliente "${cliente.nome}"?`
        );

    if (!confirmar) {
        return;
    }

    const dados =
        new FormData();

    dados.append(
        'id',
        String(id)
    );

    try {

        const resposta =
            await fetch(
                '../api/clientes/excluir.php',
                {
                    method: 'POST',
                    body: dados
                }
            );

        const resultado: RespostaCliente =
            await resposta.json();

        if (
            !resposta.ok ||
            !resultado.sucesso
        ) {

            alert(
                resultado.mensagem ||
                'Não foi possível excluir o cliente.'
            );

            return;
        }

        alert(resultado.mensagem);

        await carregarClientes();

    } catch (erro: unknown) {

        alert(
            'Não foi possível excluir o cliente.'
        );
    }
}

document.addEventListener(
    'DOMContentLoaded',
    (): void => {

        const formCadastro =
            document.getElementById(
                'formCliente'
            );

        if (
            formCadastro instanceof HTMLFormElement
        ) {

            formCadastro.addEventListener(
                'submit',
                (evento: SubmitEvent): void => {

                    evento.preventDefault();

                    cadastrarCliente(
                        formCadastro
                    );
                }
            );
        }

        carregarClientes();
    }
);

export {};