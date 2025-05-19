function mostrarCampoCategoriaPersonalizada() {
    const categoriaSelect = document.getElementById("categoriaSelect");
    const novaCategoriaInput = document.getElementById("novaCategoriaInput");

    if (categoriaSelect.value === "Outros") {
        novaCategoriaInput.style.display = "block";
    } else {
        novaCategoriaInput.style.display = "none";
        novaCategoriaInput.value = "";
    }
}

function mostrarCampoOutro(campo) {
    const select = document.getElementById(campo);
    const inputOutro = document.getElementById(campo === 'tamanho' ? 'outroTamanho' : 'outraCor');

    if (select.value === "outro") {
        inputOutro.style.display = "block";
    } else {
        inputOutro.style.display = "none";
        inputOutro.value = "";
    }
}

function adicionarItem() {
    const nome = document.getElementById("itemNome").value;
    const tamanhoSelecionado = document.getElementById("tamanho").value;
    const corSelecionada = document.getElementById("cor").value;
    const outroTamanho = document.getElementById("outroTamanho").value.trim();
    const outraCor = document.getElementById("outraCor").value.trim();

    const tamanho = tamanhoSelecionado === "outro" ? outroTamanho : tamanhoSelecionado;
    const cor = corSelecionada === "outro" ? outraCor : corSelecionada;

    const imagemInput = document.getElementById("imagemProduto");
    const categoriaSelect = document.getElementById("categoriaSelect");
    const novaCategoriaInput = document.getElementById("novaCategoriaInput");
    const quantidade = parseInt(document.getElementById("quantidadeProduto").value) || 1;

    let categoria = categoriaSelect.value;

    if (nome.trim() === "") {
        alert("Por favor, insira um nome para o item.");
        return;
    }

    if (categoria === "Outros") {
        const novaCategoria = novaCategoriaInput.value.trim();
        if (novaCategoria === "") {
            alert("Por favor, insira o nome da nova categoria.");
            return;
        }

        const optionExists = Array.from(categoriaSelect.options).some(option => option.value === novaCategoria);
        if (!optionExists) {
            const novaOption = document.createElement("option");
            novaOption.value = novaCategoria;
            novaOption.textContent = novaCategoria;
            categoriaSelect.appendChild(novaOption);
        }
        categoria = novaCategoria;
    }

    let lista = document.getElementById("listaItens");
    let rows = lista.getElementsByTagName("tr");
    for (let row of rows) {
        let cols = row.getElementsByTagName("td");
        if (cols.length > 0 && cols[0].innerText === nome && cols[1].innerText === categoria && cols[2].innerText === tamanho && cols[3].innerText === cor) {
            let qtd = parseInt(cols[4].innerText);
            cols[4].innerText = qtd + quantidade;
            return;
        }
    }

    const row = document.createElement("tr");
    const img = document.createElement("img");
    if (imagemInput.files.length > 0) {
        const file = imagemInput.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }

    row.innerHTML = `
        <td>${nome}</td>
        <td>${categoria}</td>
        <td>${tamanho}</td>
        <td>${cor}</td>
        <td>${quantidade}</td>
        <td></td>
        <td>
            <button class='remove-btn' onclick='removerItem(this)'>Remover</button>
            <button class='edit-btn' onclick='editarItem(this)'>Editar</button>
        </td>
    `;
    row.children[5].appendChild(img);
    lista.appendChild(row);

    document.getElementById("itemNome").value = "";
    document.getElementById("imagemProduto").value = "";
    document.getElementById("novaCategoriaInput").value = "";
    document.getElementById("novaCategoriaInput").style.display = "none";
    document.getElementById("quantidadeProduto").value = 1;
    document.getElementById("outroTamanho").value = "";
    document.getElementById("outraCor").value = "";
    document.getElementById("outroTamanho").style.display = "none";
    document.getElementById("outraCor").style.display = "none";
}

function removerItem(botao) {
    let row = botao.parentElement.parentElement;
    let qtdCell = row.getElementsByTagName("td")[4];
    let qtd = parseInt(qtdCell.innerText);
    if (qtd > 1) {
        qtdCell.innerText = qtd - 1;
    } else {
        row.remove();
    }
}

function editarItem(botao) {
    const row = botao.parentElement.parentElement;
    const cols = row.getElementsByTagName("td");

    const nome = prompt("Editar nome:", cols[0].innerText);
    const categoria = prompt("Editar categoria:", cols[1].innerText);
    const tamanho = prompt("Editar tamanho:", cols[2].innerText);
    const cor = prompt("Editar cor:", cols[3].innerText);
    const quantidade = prompt("Editar quantidade:", cols[4].innerText);

    if (nome && categoria && quantidade) {
        cols[0].innerText = nome;
        cols[1].innerText = categoria;
        cols[2].innerText = tamanho;
        cols[3].innerText = cor;
        cols[4].innerText = quantidade;
    }
}