// ========== QUIZ FUNCTIONALITY ========== 

let currentQuestion = 1;
let totalQuestions = 10;
let selectedAnswer = null;
let userAnswers = [];
let score = 0;
let quizData;

// Initialize quiz
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('quiz')) {
        fetchGet("http://localhost:80/HACKATON/perguntas/random")
        .then(data => {
            quizData = transformarQuiz(data);
            console.log("Quiz carregado:", quizData);
            initializeQuiz();
            setupEventListeners();
        }); 
    }
});

function initializeQuiz() {
    const nextButton = document.querySelector('.btn-primary');
    nextButton.disabled = true;
    nextButton.style.opacity = '0.6';
    
    updateProgressBar();
    loadQuestion(currentQuestion);
}

function fetchGet(uri) {
    return fetch(uri, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .catch(error => {
        console.error('Erro no fetchGet:', error);
        return null;
    });
}

function transformarQuiz(apiResponse) {
    const letterToIndex = { "A": 1, "B": 2, "C": 3, "D": 4, "E": 5 };

    return apiResponse.dados.map(item => ({
        id: item.id_pergunta,
        category: "Sustentabilidade",
        question: item.pergunta,
        options: item.alternativas.map((alt, idx) => ({
            letter: String.fromCharCode(65 + idx),
            title: alt,
            description: ""
        })),
        correctAnswer: letterToIndex[item.alternativa_correta],
        explanation: item.explicacao
    }));
}

function setupEventListeners() {
    // Answer option selection
    const answerOptions = document.querySelectorAll('.answer-option');
    answerOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectAnswer(this);
        });
    });

    // Form submission
    const quizForm = document.getElementById('quizForm');
    if (quizForm) {
        quizForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitAnswer();
        });
    }
}

function selectAnswer(selectedOption) {
    // Remove previous selection
    document.querySelectorAll('.answer-option').forEach(option => {
        option.classList.remove('selected');
        const radio = option.querySelector('input[type="radio"]');
        radio.checked = false;
    });

    // Select current option
    selectedOption.classList.add('selected');
    const radio = selectedOption.querySelector('input[type="radio"]');
    radio.checked = true;
    selectedAnswer = parseInt(radio.value);

    // Enable next button
    const nextButton = document.querySelector('.btn-primary');
    nextButton.disabled = false;
    nextButton.style.opacity = '1';
}

function submitAnswer() {
    if (selectedAnswer === null) {
        showNotification('Por favor, selecione uma resposta!', 'warning');
        return;
    }

    // Store user answer
    userAnswers[currentQuestion - 1] = selectedAnswer;

    // Show correct answer
    showAnswerFeedback();

    // Move to next question after delay
    setTimeout(() => {
        if (currentQuestion < totalQuestions) {
            nextQuestion();
        } else {
            showQuizResults();
        }
    }, 2000);
}

function showAnswerFeedback() {
    const options = document.querySelectorAll('.answer-option');
    const correctAnswer = quizData[currentQuestion - 1].correctAnswer;

    options.forEach((option, index) => {
        const radio = option.querySelector('input[type="radio"]');
        const answerValue = parseInt(radio.value);

        if (answerValue === correctAnswer) {
            option.classList.add('correct');
        } else if (answerValue === selectedAnswer && answerValue !== correctAnswer) {
            option.classList.add('incorrect');
        }

        // Disable further selection
        option.style.pointerEvents = 'none';
    });

    // Show explanation
    const explanation = quizData[currentQuestion - 1].explanation;
    if (explanation) {
        showNotification(explanation, selectedAnswer === correctAnswer ? 'success' : 'info');
    }

    // Update score
    if (selectedAnswer === correctAnswer) {
        score++;
    }
}

function nextQuestion() {
    currentQuestion++;
    selectedAnswer = null;

    // Reset options
    document.querySelectorAll('.answer-option').forEach(option => {
        option.classList.remove('selected', 'correct', 'incorrect');
        option.style.pointerEvents = 'auto';
        const radio = option.querySelector('input[type="radio"]');
        radio.checked = false;
    });

    // Disable next button
    const nextButton = document.querySelector('.btn-primary');
    nextButton.disabled = true;
    nextButton.style.opacity = '0.6';

    updateProgressBar();
    loadQuestion(currentQuestion);
}

function previousQuestion() {
    if (currentQuestion > 1) {
        currentQuestion--;
        selectedAnswer = userAnswers[currentQuestion - 1] || null;
        updateProgressBar();
        loadQuestion(currentQuestion);
    }
}

function updateProgressBar() {
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    
    const percentage = (currentQuestion / totalQuestions) * 100;
    
    if (progressFill) {
        progressFill.style.width = percentage + '%';
    }
    
    if (progressText) {
        progressText.textContent = `Pergunta ${currentQuestion} de ${totalQuestions}`;
    }
}

function loadQuestion(questionNumber) {
    // This function would load question data from server
    // For now, we're using static data
    const question = quizData[questionNumber - 1];
    console.log(question);
    console.log(`Questao nmro ${questionNumber}`);
    
    if (question) {
        // Update question title
        const questionTitle = document.querySelector('.question-title');
        if (questionTitle) {
            questionTitle.textContent = question.question;
        }

        // Update badge
        const badge = document.querySelector('.badge');
        if (badge) {
            badge.textContent = question.category;
        }

        const options = document.querySelectorAll('.option-title');
        if (options) {
            options.forEach((option, index) => {
                option.textContent = question.options[index].title;
            });
        }
    }
}

function showQuizResults() {
    const percentage = Math.round((score / totalQuestions) * 100);
    
    const resultHTML = `
        <div class="quiz-result card shadow-neon text-center">
            <h2 class="text-3xl font-bold text-primary mb-lg">Quiz Concluído!</h2>
            <div class="result-score mb-xl">
                <div class="score-circle">
                    <span class="score-percentage text-4xl font-bold">${percentage}%</span>
                    <span class="score-text">de acertos</span>
                </div>
            </div>
            <div class="result-details mb-xl">
                <p class="text-lg">Você acertou <strong class="text-primary">${score}</strong> de <strong>${totalQuestions}</strong> perguntas</p>
                <p class="text-muted mt-sm">${getScoreMessage(percentage)}</p>
            </div>
            <div class="result-actions">
                <button class="btn btn-primary mr-md" onclick="restartQuiz()">
                    <i class="ri-refresh-line"></i> Tentar Novamente
                </button>
                <button class="btn btn-secondary" onclick="goToHome()">
                    <i class="ri-home-line"></i> Início
                </button>
            </div>
        </div>
    `;

    const quizWrapper = document.querySelector('.quiz-wrapper');
    quizWrapper.innerHTML = resultHTML;
}

function getScoreMessage(percentage) {
    if (percentage >= 90) return "Excelente! Você é um expert em sustentabilidade! 🌱";
    if (percentage >= 70) return "Muito bom! Você tem um bom conhecimento sobre sustentabilidade! 🌿";
    if (percentage >= 50) return "Bom trabalho! Continue estudando sobre sustentabilidade! 🌳";
    return "Continue aprendendo! A sustentabilidade é fundamental para o futuro! 🌍";
}

function restartQuiz() {
    currentQuestion = 1;
    selectedAnswer = null;
    userAnswers = [];
    score = 0;
    location.reload();
}

function goToHome() {
    window.location.href = '../view/home.php';
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => closeNotification(notification), 3000);
}

function closeNotification(el) {
    const notification = el.classList.contains('notification')
        ? el
        : el.closest('.notification');

    if (notification) {
        notification.classList.add('hide');
        notification.addEventListener('animationend', () => notification.remove(), { once: true });
    }
}

// ========== UTILITY FUNCTIONS ==========

// Add smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Add loading animation for buttons
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 1000);
            }
        });
    });
});