document.addEventListener('DOMContentLoaded', () => {
    // Seletores de elementos principais
    const formProduto = document.getElementById('formProduto');
    const formTitle = document.getElementById('form-title');
    const btnSubmit = document.getElementById('btn-submit');
    const btnCancelarEdicao = document.getElementById('btn-cancelar-edicao');
    const listaProdutos = document.getElementById('lista-produtos');
    const notificationBar = document.getElementById('notification-bar');
    const imagePreview = document.getElementById('image-preview');
    const imagemProdutoInput = document.getElementById('imagemProduto');

    // Seletores do Modal de Cores
    const modalCor = document.getElementById('modal-cor');
    const formCor = document.getElementById('form-cor');
    const btnAddCor = document.getElementById('btn-add-cor');
    const btnCancelarCor = document.getElementById('btn-cancelar-cor');

    let idEditando = null;

    // --- FUNÇÕES AUXILIARES ---

    const showNotification = (message, type = 'success') => {
        notificationBar.textContent = message;
        notificationBar.className = `notification-bar ${type}`;
        notificationBar.style.display = 'block';
        setTimeout(() => {
            notificationBar.style.display = 'none';
        }, 4000);
    };

    const apiCall = async (endpoint, options) => {
        try {
            const response = await fetch(endpoint, options);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('API Call Error:', error);
            showNotification('Erro de comunicação com o servidor.', 'danger');
            return null;
        }
    };
    
    // --- LÓGICA DE PRODUTOS ---

    const carregarProdutos = async () => {
        const produtos = await apiCall('listarProdutos.php');
        listaProdutos.innerHTML = '';
        if (produtos && produtos.length > 0) {
            produtos.forEach(produto => {
                const tr = document.createElement('tr');
                tr.dataset.id = produto.id;
                tr.innerHTML = `
                    <td>
                        <img src="${produto.id_img ? `exibir_imagem.php?id=${produto.id_img}` : '../assets/img/placeholder.png'}" alt="${produto.produto}" class="product-image">
                    </td>
                    <td>${produto.produto}</td>
                    <td>${produto.categoria || '-'}</td>
                    <td>R$ ${parseFloat(produto.preco).toFixed(2)}</td>
                    <td>${produto.cor || '-'}/${produto.tamanho || '-'}</td>
                    <td>${produto.quantidade}</td>
                    <td class="actions">
                        <button class="btn btn-secondary btn-edit"><i class="fas fa-pencil-alt"></i></button>
                        <button class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></button>
                    </td>
                `;
                listaProdutos.appendChild(tr);
            });
        } else {
            listaProdutos.innerHTML = '<tr><td colspan="7" class="empty-state">Nenhum produto cadastrado.</td></tr>';
        }
    };

    const limparFormulario = () => {
        formProduto.reset();
        idEditando = null;
        imagePreview.innerHTML = '';
        formTitle.textContent = 'Adicionar Novo Produto';
        btnSubmit.innerHTML = '<i class="fas fa-plus"></i> Adicionar Produto';
        btnCancelarEdicao.style.display = 'none';
        // A imagem se torna obrigatória novamente para novos produtos
        imagemProdutoInput.required = true; 
    };

    const preencherFormularioParaEdicao = (produto) => {
        document.getElementById('nome').value = produto.produto;
        document.getElementById('preco').value = produto.preco;
        document.getElementById('id_categoria').value = produto.id_categoria;
        document.getElementById('id_tamanho').value = produto.id_tamanho;
        document.getElementById('id_cor').value = produto.id_cor;
        document.getElementById('qtd').value = produto.quantidade;
        
        idEditando = produto.id;
        formTitle.textContent = `Editando: ${produto.produto}`;
        btnSubmit.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
        btnCancelarEdicao.style.display = 'inline-block';

        // A imagem não é obrigatória na edição
        imagemProdutoInput.required = false;

        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    formProduto.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formProduto);
        const endpoint = idEditando ? 'editarProduto.php' : 'inserirProdutos.php';

        if (idEditando) {
            formData.append('id', idEditando);
        }

        const data = await apiCall(endpoint, { method: 'POST', body: formData });
        
        // CÓDIGO NOVO E CORRIGIDO
if (data && data.success) {
    showNotification(`Produto ${idEditando ? 'editado' : 'adicionado'} com sucesso!`);
    limparFormulario();
    carregarProdutos();
} else if (data) {
    // Se 'data' existe, mas success é false, mostramos a mensagem de erro vinda do servidor
    showNotification(data.message || 'Ocorreu um erro no servidor.', 'danger');
}
// Se 'data' for null, não fazemos nada aqui, pois a função apiCall já mostrou uma notificação genérica.
    });

    listaProdutos.addEventListener('click', async (e) => {
        const target = e.target;
        const id = target.closest('tr')?.dataset.id;

        if (!id) return;

        if (target.closest('.btn-edit')) {
            const produto = await apiCall(`buscarProduto.php?id=${id}`);
            if(produto) preencherFormularioParaEdicao(produto);
        }

        if (target.closest('.btn-delete')) {
            if (confirm('Tem certeza que deseja remover este produto? Esta ação é irreversível.')) {
                const data = await apiCall('removerProduto.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                });

                if (data && data.success) {
                    showNotification('Produto removido com sucesso!');
                    carregarProdutos();
                } else {
                    showNotification(data.message || 'Erro ao remover produto.', 'danger');
                }
            }
        }
    });

    btnCancelarEdicao.addEventListener('click', limparFormulario);

    imagemProdutoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = '';
        }
    });

    // --- LÓGICA DO MODAL DE CORES ---
    
    btnAddCor.addEventListener('click', () => modalCor.style.display = 'flex');
    btnCancelarCor.addEventListener('click', () => modalCor.style.display = 'none');
    modalCor.addEventListener('click', (e) => {
        if (e.target === modalCor) modalCor.style.display = 'none';
    });

    formCor.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formCor);
        const data = await apiCall('salvar_cor.php', { method: 'POST', body: formData });

        if(data && data.success) {
            const selectCor = document.getElementById('id_cor');
            // Adiciona a nova cor ao dropdown
            const newOption = new Option(data.cor.nome, data.cor.id);
            selectCor.add(newOption);
            // Seleciona a cor recém-adicionada
            selectCor.value = data.cor.id;
            
            modalCor.style.display = 'none';
            formCor.reset();
            showNotification('Nova cor adicionada com sucesso!');
        } else {
            showNotification(data.message || 'Erro ao salvar a cor.', 'danger');
        }
    });

    // Carga inicial
    carregarProdutos();
});