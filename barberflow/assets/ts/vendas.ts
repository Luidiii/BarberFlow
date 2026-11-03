interface Venda {
    id: number;
    cliente_id: number | null;
    cliente: string;
    data_venda: string;
    valor_total: string;
}

interface RespostaListaVendas {
    sucesso: boolean;
    dados: Venda[];
    mensagem?: string;
}

interface RespostaVenda {
    sucesso: boolean;
    mensagem: string;
    id?: number;
    valor_total?: number;
}

declare const bootstrap: {
    Modal: {
        getInstance(element: HTMLElement): {
            hide(): void;
        } | null;
    };
};

async function carregarVendas(): Promise<void> {
    const tabela = document.getElementById('tabelaVendas');

    if (!(tabela instanceof HTMLElement)) return;

    try {
        const resposta = await fetch('../api/vendas/listar.php');

        if (!resposta.ok) {
            throw new Error('Erro na comunicação com a API.');
        }

        const resultado: RespostaListaVendas = await resposta.json();

        if (!resultado.sucesso || resultado.dados.length === 0) {
            tabela.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Nenhuma venda registrada.
                    </td>
                </tr>
            `;
            return;
        }

        tabela.innerHTML = resultado.dados
            .map((venda: Venda): string => {
                const data = new Date(
                    venda.data_venda.replace(' ', 'T')
                );

                const dataFormatada = data.toLocaleDateString('pt-BR');

                const horaFormatada = data.toLocaleTimeString('pt-BR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const valor = Number(venda.valor_total);

                return `
                    <tr>
                        <td>${venda.id}</td>
                        <td>${venda.cliente}</td>
                        <td>${dataFormatada} ${horaFormatada}</td>
                        <td>R$ ${valor.toFixed(2).replace('.', ',')}</td>
                    </tr>
                `;
            })
            .join('');

    } catch (erro: unknown) {
        tabela.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-danger">
                    Não foi possível carregar as vendas.
                </td>
            </tr>
        `;
    }
}

function configurarProduto(): void {
    const produto = document.getElementById('produto_id');
    const quantidade = document.getElementById('quantidade');
    const infoEstoque = document.getElementById('infoEstoque');

    if (
        !(produto instanceof HTMLSelectElement) ||
        !(quantidade instanceof HTMLInputElement) ||
        !(infoEstoque instanceof HTMLElement)
    ) {
        return;
    }

    produto.addEventListener('change', (): void => {
        const opcao = produto.selectedOptions[0];

        if (!opcao || opcao.value === '') {
            infoEstoque.classList.add('d-none');

            quantidade.min = '1';
            quantidade.removeAttribute('max');
            quantidade.value = '1';
            quantidade.disabled = false;

            return;
        }

        const estoque = Number(opcao.dataset.estoque);

        if (!Number.isInteger(estoque) || estoque < 0) {
            infoEstoque.classList.add('d-none');

            quantidade.min = '1';
            quantidade.removeAttribute('max');
            quantidade.value = '1';
            quantidade.disabled = false;

            return;
        }

        quantidade.max = String(estoque);

        if (estoque === 0) {
            quantidade.value = '0';
            quantidade.min = '0';
            quantidade.disabled = true;

            infoEstoque.textContent = 'Produto sem estoque disponível.';
            infoEstoque.classList.remove('d-none');

            return;
        }

        quantidade.min = '1';
        quantidade.disabled = false;

        if (
            Number(quantidade.value) > estoque ||
            Number(quantidade.value) < 1
        ) {
            quantidade.value = '1';
        }

        infoEstoque.textContent = `Estoque disponível: ${estoque}`;
        infoEstoque.classList.remove('d-none');
    });
}

async function cadastrarVenda(form: HTMLFormElement): Promise<void> {
    const dados = new FormData(form);

    try {
        const resposta = await fetch(
            '../api/vendas/cadastrar.php',
            {
                method: 'POST',
                body: dados
            }
        );

        const resultado: RespostaVenda = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {
            alert(
                resultado.mensagem ||
                'Não foi possível registrar a venda.'
            );
            return;
        }

        alert(resultado.mensagem);

        form.reset();

        const quantidade = document.getElementById('quantidade');

        if (quantidade instanceof HTMLInputElement) {
            quantidade.min = '1';
            quantidade.removeAttribute('max');
            quantidade.value = '1';
            quantidade.disabled = false;
        }

        const infoEstoque = document.getElementById('infoEstoque');

        if (infoEstoque instanceof HTMLElement) {
            infoEstoque.classList.add('d-none');
            infoEstoque.textContent = '';
        }

        const modalElement = document.getElementById('modalVenda');

        if (modalElement instanceof HTMLElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal !== null) {
                modal.hide();
            }
        }

        await carregarVendas();

    } catch (erro: unknown) {
        alert('Não foi possível registrar a venda.');
    }
}

document.addEventListener('DOMContentLoaded', (): void => {
    const form = document.getElementById('formVenda');

    if (form instanceof HTMLFormElement) {
        form.addEventListener(
            'submit',
            (evento: SubmitEvent): void => {
                evento.preventDefault();
                cadastrarVenda(form);
            }
        );
    }

    configurarProduto();
    carregarVendas();
});

export {};