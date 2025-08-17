<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <main class="main-content">
        <!-- Seção de boas-vindas (Hero) -->
        <section class="hero-section container">
            <div>
                <h1 class="hero-title">
                    Soluções Inovadoras<br>
                    Para um Futuro Sustentável
                </h1>
                <p class="hero-subtitle">
                    O Eco System é uma plataforma desenvolvida para o Hackathon "Dados pelo Clima", com foco em conscientização e ação contra as mudanças climáticas (ODS 13).
                </p>
                <a href="#modules" class="cta-button">
                    Explore os Módulos
                </a>
            </div>
        </section>

        <!-- Seção de Módulos -->
        <section id="modules" class="modules-section container">
            <h2 class="text-3xl font-bold mb-md text-primary">
                Nossos Módulos
            </h2>
            <p class="text-secondary mb-2xl max-w-xl mx-auto">
                Desenvolvemos diferentes ferramentas para você participar ativamente na luta por um planeta mais verde.
            </p>

            <div class="modules-grid">
                <!-- Card do Módulo de Monitoramento -->
                <a href="/HACKATHON/view/monitoring.php" class="module-card">
                    <i class="ri-cloud-line module-icon"></i>
                    <h3 class="text-primary">Monitoramento</h3>
                    <p class="text-secondary">Acompanhe alertas de clima extremo em tempo real em qualquer lugar do mundo.</p>
                </a>

                <!-- Card do Módulo EcoQuiz -->
                <a href="/HACKATHON/view/quiz.php" class="module-card">
                    <i class="ri-question-answer-line module-icon"></i>
                    <h3 class="text-primary">EcoQuiz</h3>
                    <p class="text-secondary">Teste seus conhecimentos sobre sustentabilidade e concorra no ranking global.</p>
                </a>

                <!-- Card do Módulo EcoGame -->
                <a href="/HACKATHON/view/game.php" class="module-card">
                    <i class="ri-game-line module-icon"></i>
                    <h3 class="text-primary">EcoGame</h3>
                    <p class="text-secondary">Um simulador divertido onde suas decisões impactam o futuro do planeta.</p>
                </a>

                <!-- Card do Módulo de Doações -->
                <a href="/HACKATHON/view/donation.php" class="module-card">
                    <i class="ri-wallet-line module-icon"></i>
                    <h3 class="text-primary">Doações</h3>
                    <p class="text-secondary">Apoie projetos de financiamento coletivo voltados para a sustentabilidade e o clima.</p>
                </a>
            </div>
        </section>

    </main>

</body>
</html>
