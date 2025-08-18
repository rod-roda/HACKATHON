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

    const user = { nome, email, senha, cpf };
    console.log(user);

    fetchPost('/HACKATHON/cadastrar', user)
        .then(res => {
            if(res.status){
                showNotification("Cadastro realizado com sucesso!");
                localStorage.setItem('token', res.token);
                window.location.href = "home.php";
            } else {
                showNotification(`Erro ao cadastrar! ${res.msg}`);
            }
        });
});
