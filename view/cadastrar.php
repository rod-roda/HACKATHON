<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
</head>
<body class="body-logar">
    <div class="sign-in-container">
        <h2>Sign-In</h2>
        <form>
            <div class="input-group">
                <input type="name" id="name" name="name" placeholder="Digite seu nome completo" required>
                <i class="ri-user-fill"></i>
            </div>
        
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
                <i class="ri-at-line"></i>
            </div>

            <div class="input-group">
                <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" maxlength="14" required>
                <i class="ri-id-card-fill"></i>
            </div>

            <div class="input-group">
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                <i class="ri-eye-line" id="toggleSenha"></i>
            </div>
            <button type="submit">Cadastrar</button>

            <span>Já tem uma conta? <a href="logar.php">Entre agora!</a></span>
        </form>
    </div>

    <script>
        
        const toggleSenha = document.getElementById('toggleSenha');
        const inputSenha = document.getElementById('senha');

        toggleSenha.addEventListener('click', () => {
            const tipo = inputSenha.getAttribute('type') === 'password' ? 'text' : 'password';
            inputSenha.setAttribute('type', tipo);
            toggleSenha.classList.toggle('ri-eye-line');
            toggleSenha.classList.toggle('ri-eye-off-line');
        });

        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value;
            // Remove tudo que não for número
            value = value.replace(/\D/g, '');
            // Aplica a máscara
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });

    </script>

    <script src="../js/functions.js"></script>
    <script src="../js/cadastrar.js"></script>

</body>
</html>