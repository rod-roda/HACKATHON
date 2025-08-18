<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoGame | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
</head>
<body>
    <script>
        if(!(localStorage.getItem('token')))  window.location.href = "logar.php"; //TODO -- DEIXAR ISSO MAIS SEGURO DPS COM O SERVERSIDE DO PHP
    </script>

    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <script>
        const links = document.querySelectorAll(".navbar li a");
        links.forEach(link => {
            if (link.textContent.trim() === "EcoGame") {
                link.classList.add("active");
            }
        });
    </script>

    <div class="game-container">
        <div class="game-header">
            <h1 class="game-title">
                <i class="ri-ship-2-fill"></i> EcoMarine - Navio Limpa-Oceanos
            </h1>
            <p class="game-subtitle">
                Pilote o navio ecológico e limpe o oceano coletando lixo plástico em containers!
            </p>
        </div>

        <div class="game-controls">
            <div class="score-display">
                Containers: <span id="score">0</span>
            </div>
            <button id="startBtn" class="game-button">
                <i class="ri-play-fill"></i> Iniciar Missão
            </button>
            <button id="pauseBtn" class="game-button secondary" style="display: none;">
                <i class="ri-pause-fill"></i> Pausar
            </button>
            <button id="resetBtn" class="game-button secondary">
                <i class="ri-reset-left-line"></i> Reiniciar
            </button>
        </div>

        <p class="points-title">
            Melhor pontuação: <span id="points-text">0 PTS</span>
        </p>

        <div class="game-board">
            <canvas id="gameCanvas" width="400" height="400"></canvas>
            <div id="gameOverlay" class="game-over-overlay">
                <div class="game-over-content">
                    <h2 class="game-over-title">Missão Concluída!</h2>
                    <p class="final-score">Containers Cheios: <span id="finalScore">0</span></p>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg);">Parabéns! Seu navio coletou muito lixo marinho! 🚢</p>
                    <button id="restartBtn" class="game-button">
                        <i class="ri-restart-fill"></i> Nova Missão
                    </button>
                </div>
            </div>
        </div>

        <div class="game-instructions">
            <strong>Como Jogar:</strong> Use as setas do teclado (↑ ↓ ← →) para mover o navio de limpeza. 
            Colete lixo plástico para encher containers e limpar o oceano. Evite bater nos obstáculos ou nos próprios containers!
        </div>

        <div class="mobile-controls">
            <button class="mobile-btn" data-direction="up">
                <i class="ri-arrow-up-s-fill"></i>
            </button>
            <button class="mobile-btn" data-direction="left">
                <i class="ri-arrow-left-s-fill"></i>
            </button>
            <button class="mobile-btn" data-direction="down">
                <i class="ri-arrow-down-s-fill"></i>
            </button>
            <button class="mobile-btn" data-direction="right">
                <i class="ri-arrow-right-s-fill"></i>
            </button>
        </div>
    </div>

    <script src="../js/functions.js"></script>
    <script>
        
        function updateBestScore(){
            const textoId = document.getElementById('points-text');
            const token = localStorage.getItem('token');
            
            fetchGet(`${window.location.origin}/HACKATHON/user_game/read`, token)
            .then(data => {
                if(data.status){
                    let dados = data.cod != 404 ? data.dados.resultado : 0;
                    textoId.textContent = `${dados} PTS`;
                }else{
                    showNotification(`Erro ao resgatar sua pontuação: ${data.msg}`, 'warning');
                }
            });
        }
        updateBestScore();
    </script>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const scoreElement = document.getElementById('score');
        const finalScoreElement = document.getElementById('finalScore');
        const gameOverlay = document.getElementById('gameOverlay');
        const startBtn = document.getElementById('startBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const resetBtn = document.getElementById('resetBtn');
        const restartBtn = document.getElementById('restartBtn');

        let gameRunning = false;
        let gamePaused = false;
        let gameLoop;
        let score = 0;
        let trashCollected = 0;

        const gridSize = 20;
        const tileCount = canvas.width / gridSize;

        let ship = [
            { x: 10, y: 10 }
        ];
        let dx = 0;
        let dy = 0;
        let shipDirection = 'right';

        let trash = {
            x: Math.floor(Math.random() * tileCount),
            y: Math.floor(Math.random() * tileCount),
            type: Math.floor(Math.random() * 3)
        };

        const colors = {
            ocean: '#1e40af',
            oceanDeep: '#1e1b4b',
            ship: '#64748b',
            shipDeck: '#475569',
            shipCabin: '#334155',
            containers: [
                '#dc2626', // Red
                '#16a34a', // Green
                '#ea580c', // Orange
                '#7c3aed', // Purple
                '#0891b2', // Cyan
                '#ca8a04', // Yellow
                '#be185d', // Pink
                '#059669', // Emerald
                '#4338ca', // Indigo
                '#b91c1c'  // Dark red
            ],
            trash: {
                bottle: '#ef4444',    
                bag: '#f59e0b',       
                can: '#64748b'        
            },
            waves: '#3b82f6',
            bubbles: '#93c5fd'
        };

        function clearCanvas() {
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, '#0ea5e9');
            gradient.addColorStop(0.3, '#0284c7');
            gradient.addColorStop(0.7, '#0369a1');
            gradient.addColorStop(1, '#1e40af');
            
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
            ctx.lineWidth = 1;
            
            for (let y = 0; y < canvas.height; y += 40) {
                ctx.beginPath();
                for (let x = 0; x <= canvas.width; x += 20) {
                    const waveHeight = Math.sin((x + y) * 0.02) * 3;
                    if (x === 0) {
                        ctx.moveTo(x, y + waveHeight);
                    } else {
                        ctx.lineTo(x, y + waveHeight);
                    }
                }
                ctx.stroke();
            }
            
            ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
            for (let i = 0; i < 20; i++) {
                const x = Math.random() * canvas.width;
                const y = Math.random() * canvas.height;
                const radius = Math.random() * 2 + 0.5;
                ctx.beginPath();
                ctx.arc(x, y, radius, 0, 2 * Math.PI);
                ctx.fill();
            }
            
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
            ctx.lineWidth = 1;
            for (let i = 0; i <= tileCount; i++) {
                ctx.beginPath();
                ctx.moveTo(i * gridSize, 0);
                ctx.lineTo(i * gridSize, canvas.height);
                ctx.stroke();
                
                ctx.beginPath();
                ctx.moveTo(0, i * gridSize);
                ctx.lineTo(canvas.width, i * gridSize);
                ctx.stroke();
            }
        }

        function drawShip() {
            ship.forEach((segment, index) => {
                const centerX = segment.x * gridSize + gridSize / 2;
                const centerY = segment.y * gridSize + gridSize / 2;
                const size = gridSize - 2;
                
                if (index === 0) {
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    
                    let rotation = 0;
                    switch(shipDirection) {
                        case 'right': rotation = 0; break;
                        case 'left': rotation = Math.PI; break;
                        case 'up': rotation = -Math.PI/2; break;
                        case 'down': rotation = Math.PI/2; break;
                    }
                    ctx.rotate(rotation);
                    
                    ctx.fillStyle = colors.ship;
                    
                    ctx.fillRect(-size/2, -size/3, size, size/1.5);
                    
                    ctx.beginPath();
                    ctx.moveTo(size/2, -size/3);
                    ctx.lineTo(size/2 + 4, 0);
                    ctx.lineTo(size/2, size/3);
                    ctx.fill();
                    
                    ctx.fillStyle = colors.shipCabin;
                    ctx.fillRect(-size/3, -size/4, size/1.5, size/2);
                    
                    ctx.fillStyle = colors.shipDeck;
                    ctx.fillRect(-size/6, -size/2, size/3, size/4);
                    
                    ctx.fillStyle = '#dc2626';
                    ctx.beginPath();
                    ctx.arc(size/3, -size/6, 1.5, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    ctx.fillStyle = '#16a34a';
                    ctx.beginPath();
                    ctx.arc(size/3, size/6, 1.5, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    ctx.restore();
                    
                } else {

                    const colorIndex = (index - 1) % colors.containers.length;
                    const containerColor = colors.containers[colorIndex];
                    
                    ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
                    ctx.fillRect(centerX - size/2 + 2, centerY - size/2 + 2, size - 2, size - 2);
                    
                    ctx.fillStyle = containerColor;
                    ctx.fillRect(centerX - size/2 + 1, centerY - size/2 + 1, size - 2, size - 2);
                    
                    const gradient = ctx.createLinearGradient(
                        centerX - size/2 + 1, centerY - size/2 + 1,
                        centerX + size/2 - 1, centerY + size/2 - 1
                    );
                    gradient.addColorStop(0, containerColor);
                    gradient.addColorStop(0.3, containerColor);
                    gradient.addColorStop(1, darkenColor(containerColor, 0.3));
                    
                    ctx.fillStyle = gradient;
                    ctx.fillRect(centerX - size/2 + 1, centerY - size/2 + 1, size - 2, size - 2);
                    
                    ctx.strokeStyle = darkenColor(containerColor, 0.5);
                    ctx.lineWidth = 2;
                    ctx.strokeRect(centerX - size/2 + 1, centerY - size/2 + 1, size - 2, size - 2);
                    
                    ctx.strokeStyle = darkenColor(containerColor, 0.4);
                    ctx.lineWidth = 1;
                    for (let i = 1; i < 4; i++) {
                        const y = centerY - size/2 + 1 + (i * (size-2)/4);
                        ctx.beginPath();
                        ctx.moveTo(centerX - size/2 + 2, y);
                        ctx.lineTo(centerX + size/2 - 2, y);
                        ctx.stroke();
                    }
                    
                    for (let i = 1; i < 3; i++) {
                        const x = centerX - size/2 + 1 + (i * (size-2)/3);
                        ctx.beginPath();
                        ctx.moveTo(x, centerY - size/2 + 2);
                        ctx.lineTo(x, centerY + size/2 - 2);
                        ctx.stroke();
                    }
                    
                    ctx.fillStyle = '#1f2937';
                    ctx.fillRect(centerX + size/3 - 1, centerY - size/8, 4, size/4);
                    
                    ctx.fillStyle = '#374151';
                    ctx.fillRect(centerX + size/3, centerY - size/12, 2, size/6);
                    
                }
            });
        }

        function drawTrash() {
            const centerX = trash.x * gridSize + gridSize / 2;
            const centerY = trash.y * gridSize + gridSize / 2;
            
            ctx.font = `${gridSize - 4}px Arial`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            ctx.fillStyle = 'rgba(0, 0, 0, 0.3)';
            ctx.fillText(getTrashEmoji(trash.type), centerX + 1, centerY + 1);

            ctx.fillStyle = '#000000';
            ctx.fillText(getTrashEmoji(trash.type), centerX, centerY);
        }
        
        function getTrashEmoji(type) {
            switch(type) {
                case 0: return '🍼';
                case 1: return '🛍️';
                case 2: return '🥤';
                default: return '🗑️';
            }
        }

        function darkenColor(color, factor) {
            color = color.replace('#', '');
            
            const r = parseInt(color.substr(0, 2), 16);
            const g = parseInt(color.substr(2, 2), 16);
            const b = parseInt(color.substr(4, 2), 16);
            
            // Escurece a cor
            const newR = Math.round(r * (1 - factor));
            const newG = Math.round(g * (1 - factor));
            const newB = Math.round(b * (1 - factor));
            
            // Converte de volta para hex
            return `#${newR.toString(16).padStart(2, '0')}${newG.toString(16).padStart(2, '0')}${newB.toString(16).padStart(2, '0')}`;
        }

        function moveShip() {
            const head = { x: ship[0].x + dx, y: ship[0].y + dy };
            ship.unshift(head);

            if (head.x === trash.x && head.y === trash.y) {
                trashCollected++;
                score += trash.type === 1 ? 15 : 10;
                scoreElement.textContent = score;
                generateTrash();
                
            } else {
                ship.pop();
            }
        }

        function generateTrash() {
            do {
                trash = {
                    x: Math.floor(Math.random() * tileCount),
                    y: Math.floor(Math.random() * tileCount),
                    type: Math.floor(Math.random() * 3)
                };
            } while (ship.some(segment => segment.x === trash.x && segment.y === trash.y));
        }

        function checkCollision() {
            const head = ship[0];

            if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) {
                return true;
            }

            for (let i = 1; i < ship.length; i++) {
                if (head.x === ship[i].x && head.y === ship[i].y) {
                    return true;
                }
            }

            return false;
        }

        function gameOver() {
            gameRunning = false;
            clearInterval(gameLoop);
            finalScoreElement.textContent = score;
            gameOverlay.style.display = 'flex';
            startBtn.style.display = 'inline-flex';
            pauseBtn.style.display = 'none';

            const token = localStorage.getItem('token');
            const jsonBody = {
                jogo_nome: 'EcoMarine',
                resultado: score
            };

            
            
            fetchGet(`${window.location.origin}/HACKATHON/user_game/read`, token)
            .then(data => {
                if(data.status){
                    if(data.cod != 404 && data.dados.resultado < score){

                        fetchPost(`${window.location.origin}/HACKATHON/user_game/insert`, jsonBody, token)
                        .then(data => {
                            if(data.status){
                                updateBestScore();
                            }else{
                                showNotification("Erro ao atualizar sua pontuação", 'warning');
                            }
                        });

                    }
                }else{
                    showNotification(`Erro ao resgatar sua pontuação: ${data.msg}`, 'warning');
                }
            });
        }

        function update() {
            if (!gameRunning || gamePaused) return;

            moveShip();

            if (checkCollision()) {
                gameOver();
                return;
            }

            clearCanvas();
            drawTrash();
            drawShip();
        }

        function startGame() {
            if (gameRunning) return;

            gameRunning = true;
            gamePaused = false;
            gameOverlay.style.display = 'none';
            startBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-flex';

            gameLoop = setInterval(update, 150);
        }

        function pauseGame() {
            gamePaused = !gamePaused;
            pauseBtn.innerHTML = gamePaused ? 
                '<i class="ri-play-fill"></i> Continuar' : 
                '<i class="ri-pause-fill"></i> Pausar';
        }

        function resetGame() {
            gameRunning = false;
            gamePaused = false;
            clearInterval(gameLoop);
            
            ship = [{ x: 10, y: 10 }];
            dx = 0;
            dy = 0;
            shipDirection = 'right'; // Reset direction
            score = 0;
            trashCollected = 0;
            scoreElement.textContent = score;
            
            generateTrash();
            clearCanvas();
            drawTrash();
            drawShip();
            
            gameOverlay.style.display = 'none';
            startBtn.style.display = 'inline-flex';
            pauseBtn.style.display = 'none';
            pauseBtn.innerHTML = '<i class="ri-pause-fill"></i> Pausar';
        }

        function handleKeyPress(e) {
            if (!gameRunning || gamePaused) return;

            const key = e.key;
            
            if ((key === 'ArrowUp' || key === 'w' || key === 'W') && dy === 0) {
                dx = 0;
                dy = -1;
                shipDirection = 'up';
            } else if ((key === 'ArrowDown' || key === 's' || key === 'S') && dy === 0) {
                dx = 0;
                dy = 1;
                shipDirection = 'down';
            } else if ((key === 'ArrowLeft' || key === 'a' || key === 'A') && dx === 0) {
                dx = -1;
                dy = 0;
                shipDirection = 'left';
            } else if ((key === 'ArrowRight' || key === 'd' || key === 'D') && dx === 0) {
                dx = 1;
                dy = 0;
                shipDirection = 'right';
            }
        }

        function handleMobileControl(direction) {
            if (!gameRunning || gamePaused) return;

            switch(direction) {
                case 'up':
                    if (dy === 0) { 
                        dx = 0; 
                        dy = -1; 
                        shipDirection = 'up';
                    }
                    break;
                case 'down':
                    if (dy === 0) { 
                        dx = 0; 
                        dy = 1; 
                        shipDirection = 'down';
                    }
                    break;
                case 'left':
                    if (dx === 0) { 
                        dx = -1; 
                        dy = 0; 
                        shipDirection = 'left';
                    }
                    break;
                case 'right':
                    if (dx === 0) { 
                        dx = 1; 
                        dy = 0; 
                        shipDirection = 'right';
                    }
                    break;
            }
        }

        document.addEventListener('keydown', handleKeyPress);
        startBtn.addEventListener('click', startGame);
        pauseBtn.addEventListener('click', pauseGame);
        resetBtn.addEventListener('click', resetGame);
        restartBtn.addEventListener('click', resetGame);

        document.querySelectorAll('.mobile-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                handleMobileControl(btn.dataset.direction);
            });
        });

        resetGame();
    </script>
</body>
</html>