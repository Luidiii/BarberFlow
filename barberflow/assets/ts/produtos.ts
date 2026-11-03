interface Produto {
    id: number;
    nome: string;
    categoria: string | null;
    preco_venda: string;
    estoque: number;
    estoque_minimo: number;
    ativo: number;
}

interface RespostaListaProdutos {
    sucesso: boolean;
    dados: Produto[];
    mensagem?: string;
}

interface RespostaProduto {
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

let produtosAtuais: Produto[] = [];

async function carregarProdutos(): Promise<void> {

    const tabela = document.getElementById('tabelaProdutos');

    if (!(tabela instanceof HTMLElement)) {
        return;
    }

    try {

        const resposta = await fetch('../api/produtos/listar.php');

        if (!resposta.ok) {
            throw new Error('Erro na comunicação com a API.');
        }

        const resultado: RespostaListaProdutos = await resposta.json();

        if (!resultado.sucesso || resultado.dados.length === 0) {

            produtosAtuais = [];

            tabela.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Nenhum produto registrado.
                    </td>
                </tr>
            `;

            return;
        }

        produtosAtuais = resultado.dados;

        tabela.innerHTML = resultado.dados.map(
            (produto: Produto): string => {

                const estoqueBaixo =
                    produto.estoque <= produto.estoque_minimo;

                const estoqueTexto = estoqueBaixo
                    ? `<span class="badge text-bg-danger">${produto.estoque}</span>`
                    : `<span class="badge text-bg-success">${produto.estoque}</span>`;

                return `
                    <tr>
                        <td>${produto.id}</td>

                        <td>${produto.nome}</td>

                        <td>${produto.categoria ?? 'Não informado'}</td>

                        <td>
                            R$ ${Number(produto.preco_venda)
                                .toFixed(2)
                                .replace('.', ',')}
                        </td>

                        <td>
                            ${estoqueTexto}
                        </td>

                        <td>${produto.estoque_minimo}</td>

                        <td>
                            ${
                                produto.ativo === 1
                                    ? '<span class="badge text-bg-success">Ativo</span>'
                                    : '<span class="badge text-bg-secondary">Inativo</span>'
                            }
                        </td>

                        <td>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary btn-editar-produto"
                                data-id="${produto.id}">
                                Editar
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger btn-excluir-produto"
                                data-id="${produto.id}">
                                Excluir
                            </button>
                        </td>
                    </tr>
                `;
            }
        ).join('');

        configurarBotoes();

    } catch (erro: unknown) {

        tabela.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-danger">
                    Não foi possível carregar os produtos.
                </td>
            </tr>
        `;
    }
}

function configurarBotoes(): void {

    const botoesEditar =
        document.querySelectorAll('.btn-editar-produto');

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

    const botoesExcluir =
        document.querySelectorAll('.btn-excluir-produto');

    botoesExcluir.forEach((botao: Element): void => {

        if (!(botao instanceof HTMLButtonElement)) {
            return;
        }

        botao.addEventListener('click', (): void => {

            const id = Number(botao.dataset.id);

            if (!Number.isInteger(id) || id <= 0) {
                return;
            }

            excluirProduto(id);
        });
    });
}

function abrirModalEdicao(id: number): void {

    const produto = produtosAtuais.find(
        (item: Produto): boolean => item.id === id
    );

    if (!produto) {

        alert('Produto não encontrado.');

        return;
    }

    const campoId = document.getElementById('editar_id');
    const campoNome = document.getElementById('editar_nome');
    const campoCategoria =
        document.getElementById('editar_categoria');
    const campoPreco =
        document.getElementById('editar_preco_venda');
    const campoEstoque =
        document.getElementById('editar_estoque');
    const campoEstoqueMinimo =
        document.getElementById('editar_estoque_minimo');
    const modalElement =
        document.getElementById('modalEditarProduto');

    if (
        !(campoId instanceof HTMLInputElement) ||
        !(campoNome instanceof HTMLInputElement) ||
        !(campoCategoria instanceof HTMLInputElement) ||
        !(campoPreco instanceof HTMLInputElement) ||
        !(campoEstoque instanceof HTMLInputElement) ||
        !(campoEstoqueMinimo instanceof HTMLInputElement) ||
        !(modalElement instanceof HTMLElement)
    ) {
        return;
    }

    campoId.value = String(produto.id);
    campoNome.value = produto.nome;
    campoCategoria.value = produto.categoria ?? '';
    campoPreco.value = produto.preco_venda;
    campoEstoque.value = String(produto.estoque);
    campoEstoqueMinimo.value =
        String(produto.estoque_minimo);

    const modal = new bootstrap.Modal(modalElement);

    modal.show();
}

async function cadastrarProduto(
    form: HTMLFormElement
): Promise<void> {

    const dados = new FormData(form);

    try {

        const resposta = await fetch(
            '../api/produtos/cadastrar.php',
            {
                method: 'POST',
                body: dados
            }
        );

        const resultado: RespostaProduto =
            await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {

            alert(
                resultado.mensagem ||
                'Não foi possível cadastrar o produto.'
            );

            return;
        }

        alert(resultado.mensagem);

        form.reset();

        const estoqueMinimo =
            document.getElementById('estoque_minimo');

        if (estoqueMinimo instanceof HTMLInputElement) {
            estoqueMinimo.value = '5';
        }

        const modalElement =
            document.getElementById('modalProduto');

        if (modalElement instanceof HTMLElement) {

            const modal =
                bootstrap.Modal.getInstance(modalElement);

            if (modal !== null) {
                modal.hide();
            }
        }

        await carregarProdutos();

    } catch (erro: unknown) {

        alert('Não foi possível cadastrar o produto.');
    }
}

async function editarProduto(
    form: HTMLFormElement
): Promise<void> {

    const dados = new FormData(form);

    try {

        const resposta = await fetch(
            '../api/produtos/editar.php',
            {
                method: 'POST',
                body: dados
            }
        );

        const resultado: RespostaProduto =
            await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {

            alert(
                resultado.mensagem ||
                'Não foi possível atualizar o produto.'
            );

            return;
        }

        alert(resultado.mensagem);

        const modalElement =
            document.getElementById('modalEditarProduto');

        if (modalElement instanceof HTMLElement) {

            const modal =
                bootstrap.Modal.getInstance(modalElement);

            if (modal !== null) {
                modal.hide();
            }
        }

        await carregarProdutos();

    } catch (erro: unknown) {

        alert('Não foi possível atualizar o produto.');
    }
}

async function excluirProduto(id: number): Promise<void> {

    const produto = produtosAtuais.find(
        (item: Produto): boolean => item.id === id
    );

    if (!produto) {
        return;
    }

    const confirmar = confirm(
        `Tem certeza que deseja excluir o produto "${produto.nome}"?`
    );

    if (!confirmar) {
        return;
    }

    const dados = new FormData();

    dados.append('id', String(id));

    try {

        const resposta = await fetch(
            '../api/produtos/excluir.php',
            {
                method: 'POST',
                body: dados
            }
        );

        const resultado: RespostaProduto =
            await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {

            alert(
                resultado.mensagem ||
                'Não foi possível excluir o produto.'
            );

            return;
        }

        alert(resultado.mensagem);

        await carregarProdutos();

    } catch (erro: unknown) {

        alert('Não foi possível excluir o produto.');
    }
}

document.addEventListener(
    'DOMContentLoaded',
    (): void => {

        const formCadastro =
            document.getElementById('formProduto');

        if (formCadastro instanceof HTMLFormElement) {

            formCadastro.addEventListener(
                'submit',
                (evento: SubmitEvent): void => {

                    evento.preventDefault();

                    cadastrarProduto(formCadastro);
                }
            );
        }

        const formEdicao =
            document.getElementById('formEditarProduto');

        if (formEdicao instanceof HTMLFormElement) {

            formEdicao.addEventListener(
                'submit',
                (evento: SubmitEvent): void => {

                    evento.preventDefault();

                    editarProduto(formEdicao);
                }
            );
        }

        carregarProdutos();
    }
);

export {};