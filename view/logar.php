<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logar | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
</head>
<body class="body-logar">
    <div class="login-container">
        <h2>Login</h2>
        <form>
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
                <i class="ri-user-fill"></i>
            </div>

            <div class="input-group">
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                <i class="ri-eye-line" id="toggleSenha"></i>
            </div>
            <button type="submit">Entrar</button>

            <span>Ainda não tem uma conta? <a href="cadastrar.php">Cadastre-se</a></span>
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
    </script>

    <script src="../js/functions.js"></script>
    <script src="../js/logar.js"></script>

</body>
</html>