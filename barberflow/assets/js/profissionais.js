"use strict";
const tabelaProfissionais = document.getElementById('tabelaProfissionais');
const formProfissional = document.getElementById('formProfissional');
const profissionalId = document.getElementById('profissionalId');
const nome = document.getElementById('nome');
const especialidade = document.getElementById('especialidade');
const ativo = document.getElementById('ativo');
const tituloModal = document.getElementById('tituloModal');
const mensagem = document.getElementById('mensagem');
const modalProfissional = document.getElementById('modalProfissional');
const limparFormulario = () => {
    if (profissionalId) {
        profissionalId.value = '';
    }
    if (nome) {
        nome.value = '';
    }
    if (especialidade) {
        especialidade.value = '';
    }
    if (ativo) {
        ativo.checked = true;
    }
    if (tituloModal) {
        tituloModal.textContent = 'Novo Profissional';
    }
};
const mostrarMensagem = (texto, tipo) => {
    if (!mensagem) {
        return;
    }
    mensagem.innerHTML = `
        <div class="alert alert-${tipo}">
            ${texto}
        </div>
    `;
    setTimeout(() => {
        if (mensagem) {
            mensagem.innerHTML = '';
        }
    }, 4000);
};
const carregarProfissionais = async () => {
    if (!tabelaProfissionais) {
        return;
    }
    try {
        const resposta = await fetch('../api/profissionais/listar.php');
        const resultado = await resposta.json();
        if (!resultado.sucesso || !resultado.dados) {
            tabelaProfissionais.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">
                        ${resultado.mensagem ?? 'Erro ao carregar profissionais.'}
                    </td>
                </tr>
            `;
            return;
        }
        if (resultado.dados.length === 0) {
            tabelaProfissionais.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Nenhum profissional cadastrado.
                    </td>
                </tr>
            `;
            return;
        }
        tabelaProfissionais.innerHTML = resultado.dados
            .map((profissional) => {
            const status = profissional.ativo === 1
                ? '<span class="badge bg-success">Ativo</span>'
                : '<span class="badge bg-secondary">Inativo</span>';
            return `
                    <tr>
                        <td>${profissional.id}</td>

                        <td>
                            ${profissional.nome}
                        </td>

                        <td>
                            ${profissional.especialidade}
                        </td>

                        <td>
                            ${status}
                        </td>

                        <td class="text-end">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary me-1 btn-editar"
                                data-id="${profissional.id}"
                            >
                                Editar
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger btn-excluir"
                                data-id="${profissional.id}"
                            >
                                Excluir
                            </button>

                        </td>
                    </tr>
                `;
        })
            .join('');
        adicionarEventosTabela(resultado.dados);
    }
    catch {
        tabelaProfissionais.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-danger py-4">
                    Erro de conexão com o servidor.
                </td>
            </tr>
        `;
    }
};
const adicionarEventosTabela = (profissionais) => {
    const botoesEditar = document.querySelectorAll('.btn-editar');
    botoesEditar.forEach((botao) => {
        botao.addEventListener('click', () => {
            const elemento = botao;
            const id = Number(elemento.dataset.id);
            const profissional = profissionais.find((item) => item.id === id);
            if (!profissional) {
                return;
            }
            editarProfissional(profissional);
        });
    });
    const botoesExcluir = document.querySelectorAll('.btn-excluir');
    botoesExcluir.forEach((botao) => {
        botao.addEventListener('click', async () => {
            const elemento = botao;
            const id = Number(elemento.dataset.id);
            await excluirProfissional(id);
        });
    });
};
const editarProfissional = (profissional) => {
    if (profissionalId) {
        profissionalId.value =
            String(profissional.id);
    }
    if (nome) {
        nome.value =
            profissional.nome;
    }
    if (especialidade) {
        especialidade.value =
            profissional.especialidade;
    }
    if (ativo) {
        ativo.checked =
            profissional.ativo === 1;
    }
    if (tituloModal) {
        tituloModal.textContent =
            'Editar Profissional';
    }
    if (modalProfissional) {
        modalProfissional.classList.add('show');
        modalProfissional.style.display = 'block';
        modalProfissional.setAttribute('aria-modal', 'true');
        modalProfissional.removeAttribute('aria-hidden');
    }
    document.body.classList.add('modal-open');
    if (!document.querySelector('.modal-backdrop')) {
        const fundo = document.createElement('div');
        fundo.className =
            'modal-backdrop fade show';
        document.body.appendChild(fundo);
    }
};
const fecharModal = () => {
    if (!modalProfissional) {
        return;
    }
    modalProfissional.classList.remove('show');
    modalProfissional.style.display =
        'none';
    modalProfissional.setAttribute('aria-hidden', 'true');
    modalProfissional.removeAttribute('aria-modal');
    document.body.classList.remove('modal-open');
    document
        .querySelectorAll('.modal-backdrop')
        .forEach((elemento) => {
        elemento.remove();
    });
};
const salvarProfissional = async () => {
    if (!nome || !especialidade || !ativo) {
        return;
    }
    if (nome.value.trim() === '' ||
        especialidade.value.trim() === '') {
        mostrarMensagem('Nome e especialidade são obrigatórios.', 'danger');
        return;
    }
    const dados = new FormData();
    dados.append('nome', nome.value.trim());
    dados.append('especialidade', especialidade.value.trim());
    dados.append('ativo', ativo.checked ? '1' : '0');
    const id = profissionalId?.value.trim();
    let url = '../api/profissionais/cadastrar.php';
    if (id) {
        url =
            '../api/profissionais/editar.php';
        dados.append('id', id);
    }
    try {
        const resposta = await fetch(url, {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if (!resultado.sucesso) {
            mostrarMensagem(resultado.mensagem, 'danger');
            return;
        }
        fecharModal();
        limparFormulario();
        mostrarMensagem(resultado.mensagem, 'success');
        await carregarProfissionais();
    }
    catch {
        mostrarMensagem('Erro de conexão com o servidor.', 'danger');
    }
};
const excluirProfissional = async (id) => {
    const confirmar = confirm('Tem certeza que deseja excluir este profissional?');
    if (!confirmar) {
        return;
    }
    const dados = new FormData();
    dados.append('id', String(id));
    try {
        const resposta = await fetch('../api/profissionais/excluir.php', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if (!resultado.sucesso) {
            mostrarMensagem(resultado.mensagem, 'danger');
            return;
        }
        mostrarMensagem(resultado.mensagem, 'success');
        await carregarProfissionais();
    }
    catch {
        mostrarMensagem('Erro de conexão com o servidor.', 'danger');
    }
};
formProfissional?.addEventListener('submit', async (event) => {
    event.preventDefault();
    await salvarProfissional();
});
document
    .getElementById('btnNovoProfissional')
    ?.addEventListener('click', () => {
    limparFormulario();
});
document
    .querySelectorAll('[data-bs-dismiss="modal"]')
    .forEach((botao) => {
    botao.addEventListener('click', () => {
        fecharModal();
    });
});
carregarProfissionais();
