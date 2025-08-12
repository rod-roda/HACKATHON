<!-- Quiz Container -->
<main class="container mt-xl">
    <div class="quiz-wrapper">
        <!-- Quiz Header -->
        <div class="quiz-header text-center mb-2xl">
            <h1 class="text-4xl font-bold text-primary mb-md">
                <i class="ri-leaf-fill"></i> EcoQuiz
            </h1>
            <p class="text-lg text-secondary">Teste seus conhecimentos sobre sustentabilidade</p>
            <div class="quiz-progress mt-lg">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 10%"></div>
                </div>
                <span class="progress-text text-sm text-muted mt-sm">Pergunta 1 de 10</span>
            </div>
        </div>

        <!-- Question Card -->
        <div class="question-card card shadow-neon mb-xl">
            <div class="card-header">
                <span class="badge badge-success"></span>
            </div>
            
            <h2 class="question-title text-2xl font-semibold mb-lg">
                
            </h2>

            <!-- Answer Options -->
            <form id="quizForm" class="answer-options">
                <div class="option-group">
                    <label class="answer-option" for="option1">
                        <input type="radio" id="option1" name="answer" value="1">
                        <div class="option-content">
                            <div class="option-letter">A</div>
                            <div class="option-text">
                                <span class="option-title"></span>
                            </div>
                        </div>
                        <div class="option-indicator">
                            <i class="ri-check-line"></i>
                        </div>
                    </label>

                    <label class="answer-option" for="option2">
                        <input type="radio" id="option2" name="answer" value="2">
                        <div class="option-content">
                            <div class="option-letter">B</div>
                            <div class="option-text">
                                <span class="option-title"></span>
                            </div>
                        </div>
                        <div class="option-indicator">
                            <i class="ri-check-line"></i>
                        </div>
                    </label>

                    <label class="answer-option" for="option3">
                        <input type="radio" id="option3" name="answer" value="3">
                        <div class="option-content">
                            <div class="option-letter">C</div>
                            <div class="option-text">
                                <span class="option-title"></span>
                            </div>
                        </div>
                        <div class="option-indicator">
                            <i class="ri-check-line"></i>
                        </div>
                    </label>

                    <label class="answer-option" for="option4">
                        <input type="radio" id="option4" name="answer" value="4">
                        <div class="option-content">
                            <div class="option-letter">D</div>
                            <div class="option-text">
                                <span class="option-title"></span>
                            </div>
                        </div>
                        <div class="option-indicator">
                            <i class="ri-check-line"></i>
                        </div>
                    </label>

                </div>
            </form>
        </div>

        <!-- Quiz Actions -->
        <div class="quiz-actions text-center">
            <button type="button" class="btn btn-secondary mr-md" onclick="previousQuestion()">
                <i class="ri-arrow-left-line"></i> Anterior
            </button>
            <button type="submit" form="quizForm" class="btn btn-primary mr-md">
                Próxima <i class="ri-arrow-right-line"></i>
            </button>
        </div>

        <!-- Quiz Statistics -->
        <div class="quiz-stats mt-xl">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value text-primary">85%</div>
                    <div class="stat-label">Precisão</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value text-accent">12</div>
                    <div class="stat-label">Questões Respondidas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value text-success">3</div>
                    <div class="stat-label">Nível Atual</div>
                </div>
            </div>
        </div>
    </div>
</main>