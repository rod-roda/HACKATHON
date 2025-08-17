<header>
    <a href="/HACKATHON/view/home.php" class="logo">
        <i class="ri-leaf-fill"><span>EcoSystem</span></i>
    </a>

    <ul class="navbar">
        <li><a href="/HACKATHON/view/weather.php">Mapa</a></li>
        <li><a href="">DashBoard</a></li>
        <li><a href="">Monitoramento</a></li>
        <li><a href="/HACKATHON/view/quiz.php">EcoQuiz</a></li>
        <li><a href="/HACKATHON/view/game.php">EcoGame</a></li>
        <li><a href="/HACKATHON/view/donation.php">Doações</a></li>
    </ul>

    <div class="main">
        <a href="/HACKATHON/view/logar.php" class="user"><i class="ri-user-fill"></i>Log-in</a>
        <button class="neon" id="btn-cadastrar">Sign-Up</button>
        <div class="bx bx-menu" id="menu-icon"></div>
    </div>

    <script>
        document.querySelector('#btn-cadastrar').addEventListener('click', function() {
            window.location.href = "cadastrar.php";
        });
    </script>
</header>