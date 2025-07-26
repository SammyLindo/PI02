document.addEventListener("DOMContentLoaded", () => {
  const modalOverlay = document.getElementById("modal-edicao")
  const modalContent = modalOverlay.querySelector(".modal-content")
  const modalCloseBtn = document.getElementById("modal-close-btn")
  const modalTitle = document.getElementById("modal-title")
  const formEdicao = document.getElementById("form-edicao")
  const tabelaCorpo = document.getElementById("dados-tabela")

  // Função para abrir o modal
  const abrirModal = () => {
    modalOverlay.classList.add("visible")
    // Adiciona animação de entrada
    setTimeout(() => {
      modalContent.style.transform = "scale(1) translateY(0)"
    }, 10)
  }

  // Função para fechar o modal
  const fecharModal = () => {
    // Animação de saída
    modalContent.style.transform = "scale(0.9) translateY(20px)"
    setTimeout(() => {
      modalOverlay.classList.remove("visible")
    }, 300)
  }

  // Event listener para fechar o modal
  modalCloseBtn.addEventListener("click", fecharModal)
  modalOverlay.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
      fecharModal()
    }
  })

  // Event listener na tabela para capturar cliques nos botões de editar
  tabelaCorpo.addEventListener("click", async (e) => {
    const editButton = e.target.closest(".btn-abrir-modal")
    if (!editButton) return

    const id = editButton.dataset.id
    const tabela = editButton.dataset.tabela
    const colunaPk = editButton.dataset.colunaPk

    // Adiciona efeito de loading no botão
    const originalText = editButton.innerHTML
    editButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...'
    editButton.disabled = true

    try {
      const response = await fetch(`api/buscar_registro.php?tabela=${tabela}&id=${id}&coluna_pk=${colunaPk}`)
      if (!response.ok) throw new Error("Falha ao buscar dados do registro.")

      const registro = await response.json()
      if (registro.erro) throw new Error(registro.erro)

      // Montar o formulário dinamicamente
      montarFormulario(registro, tabela, colunaPk, id)

      // Abrir o modal
      abrirModal()
    } catch (error) {
      // Mostra notificação de erro
      mostrarNotificacao("Erro: " + error.message, "danger")
    } finally {
      // Restaura o botão
      editButton.innerHTML = originalText
      editButton.disabled = false
    }
  })

  // Função para criar o formulário dentro do modal
  const montarFormulario = (registro, tabela, colunaPk, id) => {
    modalTitle.innerHTML = `
            <i class="fas fa-edit icon"></i>
            Editando ${tabela} (ID: ${id})
        `
    formEdicao.innerHTML = "" // Limpa o formulário anterior

    // Adiciona campos ocultos para enviar dados de controle
    formEdicao.insertAdjacentHTML("beforeend", `<input type="hidden" name="tabela" value="${tabela}">`)
    formEdicao.insertAdjacentHTML("beforeend", `<input type="hidden" name="id" value="${id}">`)
    formEdicao.insertAdjacentHTML("beforeend", `<input type="hidden" name="coluna_pk" value="${colunaPk}">`)

    for (const [coluna, valor] of Object.entries(registro)) {
      const isPK = coluna === colunaPk

      const inputHtml = `
                <div class="form-group">
                    <label for="campo-${coluna}">${coluna}</label>
                    <input type="text" id="campo-${coluna}" name="${coluna}" 
                           value="${valor ?? ""}" 
                           ${isPK ? "disabled" : ""}>
                </div>
            `
      formEdicao.insertAdjacentHTML("beforeend", inputHtml)
    }

    // Adiciona o botão de salvar
    formEdicao.insertAdjacentHTML(
      "beforeend",
      `
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        `,
    )
  }

  // Event listener para o envio do formulário de edição
  formEdicao.addEventListener("submit", async (e) => {
    e.preventDefault()
    const formData = new FormData(formEdicao)
    const submitBtn = formEdicao.querySelector('button[type="submit"]')

    // Adiciona efeito de loading no botão
    const originalText = submitBtn.innerHTML
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...'
    submitBtn.disabled = true

    try {
      const response = await fetch("api/salvar_registro.php", {
        method: "POST",
        body: formData,
      })
      if (!response.ok) throw new Error("Erro na resposta do servidor.")

      const resultado = await response.json()
      if (!resultado.success) throw new Error(resultado.message)

      // Se deu certo, fecha o modal e recarrega a página com a mensagem de sucesso
      fecharModal()
      const tabela = formData.get("tabela")

      // Mostra notificação de sucesso antes de recarregar
      mostrarNotificacao("Registro atualizado com sucesso!", "success")

      // Recarrega após um pequeno delay para mostrar a notificação
      setTimeout(() => {
        window.location.href = `estoque.php?tabela_selecionada=${tabela}&status=editado`
      }, 1500)
    } catch (error) {
      mostrarNotificacao("Erro ao salvar: " + error.message, "danger")
    } finally {
      // Restaura o botão
      submitBtn.innerHTML = originalText
      submitBtn.disabled = false
    }
  })

  // Função para mostrar notificações
  const mostrarNotificacao = (mensagem, tipo) => {
    // Remove notificação existente se houver
    const notificacaoExistente = document.querySelector(".notification-toast")
    if (notificacaoExistente) {
      notificacaoExistente.remove()
    }

    // Cria nova notificação
    const notificacao = document.createElement("div")
    notificacao.className = `notification-toast alert alert-${tipo}`
    notificacao.innerHTML = `
            <i class="fas fa-${tipo === "success" ? "check-circle" : "exclamation-triangle"} icon"></i>
            ${mensagem}
        `

    // Adiciona estilos para posicionamento
    notificacao.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease-out;
        `

    document.body.appendChild(notificacao)

    // Remove após 4 segundos
    setTimeout(() => {
      notificacao.style.animation = "slideOutRight 0.3s ease-in"
      setTimeout(() => {
        if (notificacao.parentNode) {
          notificacao.remove()
        }
      }, 300)
    }, 4000)
  }

  // Adiciona animações CSS para as notificações
  const style = document.createElement("style")
  style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `
  document.head.appendChild(style)

  // Adiciona efeitos hover para os botões da sidebar
  const sidebarLinks = document.querySelectorAll(".sidebar-nav a:not(.back-link)")
  sidebarLinks.forEach((link) => {
    if (!link.classList.contains("active")) {
      link.addEventListener("mouseenter", () => {
        link.style.backgroundColor = "#495057"
        link.style.color = "#ffffff"
      })

      link.addEventListener("mouseleave", () => {
        link.style.backgroundColor = "transparent"
        link.style.color = "#6c757d"
      })
    }
  })

  // Adiciona efeitos hover para as linhas da tabela
  const tableRows = document.querySelectorAll(".data-table tbody tr")
  tableRows.forEach((row) => {
    row.addEventListener("mouseenter", () => {
      row.style.backgroundColor = "#f8f9fa"
    })

    row.addEventListener("mouseleave", () => {
      row.style.backgroundColor = "transparent"
    })
  })

  // Adiciona efeito de focus nos inputs do modal
  document.addEventListener(
    "focus",
    (e) => {
      if (e.target.matches(".form-group input:not([disabled])")) {
        e.target.style.borderColor = "#343a40"
        e.target.style.boxShadow = "0 0 0 3px rgba(52, 58, 64, 0.2)"
      }
    },
    true,
  )

  document.addEventListener(
    "blur",
    (e) => {
      if (e.target.matches(".form-group input:not([disabled])")) {
        e.target.style.borderColor = "#dee2e6"
        e.target.style.boxShadow = "none"
      }
    },
    true,
  )
})
