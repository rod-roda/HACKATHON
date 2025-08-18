<header>
    <a href="/HACKATHON/view/home.php" class="logo">
        <i class="ri-leaf-fill"><span>EcoSystem</span></i>
    </a>

    <ul class="navbar">
        <li><a href="/HACKATHON/view/weather.php">Mapa</a></li>
        <li><a href="/HACKATHON/view/dashboard.php">DashBoard</a></li>
        <li><a href="/HACKATHON/view/monitoring.php">Monitoramento</a></li>
        <li><a href="/HACKATHON/view/quiz.php">EcoQuiz</a></li>
        <li><a href="/HACKATHON/view/game.php">EcoGame</a></li>
        <li><a href="/HACKATHON/view/donation.php">Doações</a></li>
    </ul>

    <div class="main">
        <!-- Elementos para usuário não logado -->
        <div id="auth-buttons" class="auth-buttons">
            <a href="/HACKATHON/view/logar.php" class="user">
                <i class="ri-user-fill"></i>Log-in
            </a>
            <button class="neon" id="btn-cadastrar">Sign-Up</button>
        </div>
        
        <!-- Elemento para usuário logado -->
        <div id="user-profile" class="user-profile" style="display: none;">
            <div class="profile-container">
                <i class="ri-user-3-fill profile-icon"></i>
                <div class="profile-dropdown">
                    <div class="profile-info">
                        <span id="user-name">Usuário</span>
                        <small id="user-email">usuario@email.com</small>
                    </div>
                    <div class="profile-actions">
                        <button class="profile-action logout-btn" id="logout-btn">
                            <i class="ri-logout-circle-line"></i> Sair
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bx bx-menu" id="menu-icon"></div>
    </div>

    <script src="../js/functions.js"></script>
    <script>
        document.querySelector('#btn-cadastrar').addEventListener('click', function() {
            window.location.href = "cadastrar.php";
        });

        // Verificar token no localStorage e mostrar profile ou botões de auth
        document.addEventListener('DOMContentLoaded', function() {
            checkUserAuth();
        });

        function checkUserAuth() {
            const token = localStorage.getItem('token');
            const userData = localStorage.getItem('userData');
            
            const authButtons = document.getElementById('auth-buttons');
            const userProfile = document.getElementById('user-profile');
            
            if (token) {
                // Usuário está logado
                try { 
                    fetchPost('/HACKATHON/usuario/token/payload', {token: token})
                        .then(res => {
                            if(res.status){
                                document.getElementById('user-name').textContent = res.payload.nomeUsuario;
                                document.getElementById('user-email').textContent = res.payload.emailUsuario;
                            }
                        });
                    
                    authButtons.style.display = 'none';
                    userProfile.style.display = 'block';
                } catch (e) {
                    // Erro ao parsear dados do usuário
                    showAuthButtons();
                }
            } else {
                // Usuário não está logado
                showAuthButtons();
            }
        }

        function showAuthButtons() {
            document.getElementById('user-profile').style.display = 'none';
            document.getElementById('auth-buttons').style.display = 'flex';
        }

        // Logout
        document.getElementById('logout-btn').addEventListener('click', function() {
            localStorage.removeItem('token');
            showAuthButtons();
            window.location.href = '/HACKATHON/view/home.php';
        });

        // Toggle dropdown do profile
        document.querySelector('.profile-container').addEventListener('click', function() {
            this.classList.toggle('active');
        });

        // Fechar dropdown quando clicar fora
        document.addEventListener('click', function(e) {
            const profileContainer = document.querySelector('.profile-container');
            if (!profileContainer.contains(e.target)) {
                profileContainer.classList.remove('active');
            }
        });
    </script>
</header>