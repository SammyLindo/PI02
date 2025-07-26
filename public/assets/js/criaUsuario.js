document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('cadastroForm');
    const senhaInput = document.getElementById('senha');
    const confirmarSenhaInput = document.getElementById('confirmar_senha');
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');

    // Validação de senhas no envio do formulário
    if (form) {
        form.addEventListener('submit', (e) => {
            if (senhaInput.value !== confirmarSenhaInput.value) {
                // Impede o envio do formulário
                e.preventDefault(); 
                
                // Exibe um alerta simples
                alert('As senhas não coincidem. Por favor, verifique.');
                
                // Limpa os campos de senha e foca no primeiro
                senhaInput.value = '';
                confirmarSenhaInput.value = '';
                senhaInput.focus();
            }
        });
    }

    // Lógica para o botão de visualizar senha
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetInputId = button.dataset.target;
            const targetInput = document.getElementById(targetInputId);

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                targetInput.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        });
    });
});