document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // sempre para o envio automático
    console.log('Form foi submetido'); // debug

    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value.trim();

    if (senha.length < 6) {
        showNotification("A senha deve ter no mínimo 6 caracteres", 'warning');
        return;
    }

    const user = { email, senha };
    console.log(user);

    fetchPost('/HACKATHON/logar', user)
        .then(res => {
            if(res.status){
                showNotification("Login realizado com sucesso!");
                localStorage.setItem('token', JSON.stringify(res.token));
                window.location.href = "home.php";
            } else {
                showNotification(`Erro ao logar! ${res.msg}`);
            }
        });
});