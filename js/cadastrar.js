document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // sempre para o envio automático
    console.log('Form foi submetido'); // debug

    const nome = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const cpf = document.getElementById('cpf').value.trim();
    const senha = document.getElementById('senha').value.trim();

    if (nome.length < 3) {
        showNotification("Digite um nome válido!", 'warning');
        return;
    }

    const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
    if (!cpfRegex.test(cpf)) {
        showNotification("Digite um CPF válido", 'warning');
        return;
    }

    if (senha.length < 6) {
        showNotification("A senha deve ter no mínimo 6 caracteres", 'warning');
        return;
    }

    const user = { nome, email, senha, cpf };
    console.log(user);

    fetchPost('/HACKATHON/cadastrar', user)
        .then(res => {
            if(!res) {
                showNotification("Erro ao conectar com o servidor", "error");
                return;
            }
            if(res.status){
                showNotification("Cadastro realizado com sucesso!");
                localStorage.setItem('token', res.token);
                window.location.href = "home.php";
            } else {
                showNotification(`Erro ao cadastrar! ${res.msg}`, "error");
            }
        });
});
