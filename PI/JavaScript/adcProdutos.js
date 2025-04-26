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

document.querySelector('form').addEventListener('submit', () => {
    const tamanhoSelect = document.getElementById('tamanho');
    const outroTamanho = document.getElementById('outroTamanho');
    if (tamanhoSelect.value === 'outro' && outroTamanho.value.trim() !== '') {
        tamanhoSelect.value = outroTamanho.value;
    }

    const corSelect = document.getElementById('cor');
    const outraCor = document.getElementById('outraCor');
    if (corSelect.value === 'outro' && outraCor.value.trim() !== '') {
        corSelect.value = outraCor.value;
    }
});

function mostrarErro(campo, mensagem) {
    const erro = document.createElement('span');
    erro.style.color = 'red';
    erro.textContent = mensagem;
    campo.parentNode.appendChild(erro);
}

