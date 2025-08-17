document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // sempre para o envio automático
    
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value.trim();

    if (!email) {
        showNotification("Digite seu e-mail", 'warning');
        return;
    }

    if (senha.length < 6) {
        showNotification("A senha deve ter no mínimo 6 caracteres", 'warning');
        return;
    }

    const user = { email, senha };

    fetchPost('/HACKATHON/logar', user)
        .then(res => {
            if (!res) {
                showNotification("Erro ao conectar com o servidor", "error");
                return;
            }
            if(res.status){
                showNotification("Login realizado com sucesso!");
                localStorage.setItem('token', res.token);
                window.location.href = "home.php";
            } else {
                showNotification(res.msg, "error");
            }
        });
});