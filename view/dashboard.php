<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
    <!-- Chart.js - única lib de gráficos (~60KB vs ~1MB do amCharts v4+v5) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --bg-color: #222327;
            --text-color: #fff;
            --main-color: #29fd53;
            --header-bg: rgba(34, 35, 39, 0.95);
            --secondary: #222327;
            --secondary-light: #2a2b30;
            --secondary-dark: #1a1a1f;
            --primary: #29fd53;
            --primary-light: #5dff80;
            --primary-dark: #1acb40;
            --primary-darker: #0e9a2d;
            --radius-lg: 0.5rem;
            --radius-xl: 0.75rem;
            --font-base: 1rem;
            --font-lg: 1.125rem;
            --font-xl: 1.25rem;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-color) 0%, #1a1a1f 100%);
            color: var(--text-color);
            padding-top: 100px;
            font-size: var(--font-base);
            line-height: 1.5;
        }
        
        .container-new {
            background: var(--secondary);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            margin: 20px auto;
        }

        /* Dashboard Stats Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #201f1f;
            border-radius: 12px;
            color: #fff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 22px;
        }
        
        .icon-primary {
            background: rgba(41, 253, 83, 0.1);
            color: var(--primary);
        }

        .icon-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .icon-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .icon-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .stat-title {
            font-size: 0.9rem;
            color: #ccc;
            margin: 0;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #fff;
        }
        
        .stat-footer {
            margin-top: 10px;
            font-size: 0.85rem;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        /* Gráficos Grid */
        .caixas {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .caixas {
                grid-template-columns: 1fr;
            }
        }

        .graficoOcorrencias {
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 100%;
            background: #201f1f;
            transition: all 0.3s ease;
            padding: 20px;
            min-height: 420px;
            display: flex;
            flex-direction: column;
        }

        .graficoOcorrencias:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .grafico-titulo {
            color: #29fd53;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            flex-shrink: 0;
        }

        .grafico-titulo i {
            margin-right: 8px;
        }

        .grafico-frame {
            width: 100%;
            flex: 1;
            position: relative;
            min-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .grafico-frame canvas {
            max-width: 100% !important;
            max-height: 100% !important;
        }

        /* Skeleton Loading */
        .skeleton-pulse {
            background: linear-gradient(90deg, #2a2b30 25%, #333438 50%, #2a2b30 75%);
            background-size: 200% 100%;
            animation: skeletonPulse 1.5s ease-in-out infinite;
            border-radius: 8px;
        }

        .skeleton-chart {
            width: 100%;
            height: 350px;
        }

        .skeleton-value {
            width: 80px;
            height: 2rem;
            display: inline-block;
        }

        @keyframes skeletonPulse {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Fade in para gráficos carregados */
        .chart-loaded {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Filtros */
        .filtros-container {
            background: #222327;
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .filtros-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .filtros-header h2 {
            color: #f6f8faff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .filtro-group {
            margin-bottom: 0;
        }
        
        .filtro-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #f6f8faff;
            font-size: 0.95rem;
        }

        .filtro-btn {
            display: flex;
            align-items: flex-end;
        }

        .btn-filtrar {
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: #222;
        }
        
        .btn-filtrar:hover {
            background-color: #0e9a2d;
        }

        /* Modal */
        .btn-modal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: var(--primary);
            color: #222;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 15px 0;
        }
        
        .btn-modal:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(41, 253, 83, 0.3);
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background: var(--secondary-light);
            border-radius: var(--radius-xl);
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 20px;
            background: var(--primary-darker);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            color: white;
            font-size: var(--font-xl);
            font-weight: 600;
        }
        
        .close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .close-btn:hover {
            transform: rotate(90deg);
            color: var(--primary-light);
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-light);
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: var(--secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            color: var(--text-color);
            font-size: var(--font-base);
            transition: all 0.3s ease;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(41, 253, 83, 0.2);
        }
        
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2329fd53' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }
        
        .modal-footer {
            padding: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-cancel {
            background: transparent;
            color: #aaa;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
        }
        
        .btn-save {
            background: var(--primary);
            color: var(--secondary-dark);
            font-weight: 600;
        }
        
        .btn-save:hover {
            background: var(--primary-dark);
            box-shadow: 0 0 15px rgba(41, 253, 83, 0.3);
            transform: translateY(-2px);
        }

        /* Notificações */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            max-width: 300px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            background-color: #333;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .notification-success { background-color: #28a745 !important; }
        .notification-error { background-color: #dc3545 !important; }
        .notification-warning { background-color: #ffc107 !important; color: #333; }

        .notification-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .notification-message { flex-grow: 1; }

        .notification-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .notification-close:hover { opacity: 1; }
        .notification.hide { animation: slideOut 0.3s ease-out forwards; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
</head>
<body>
    <script>
        if(!(localStorage.getItem('token')))  window.location.href = "logar.php";
    </script>

    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <script>
        const links = document.querySelectorAll(".navbar li a");
        links.forEach(link => {
            if (link.textContent.trim() === "DashBoard") {
                link.classList.add("active");
            }
        });
    </script>

    <!-- Filtros -->
    <div class="filtros-container">
        <div class="filtros-header">
            <h2><i class="bi bi-funnel"></i> Dashboard Eco System</h2>
        </div>
        <div class="filtros-grid">
            <div class="filtro-group filtro-btn">
                <label>&nbsp;</label>
                <button id="btn-filtrar" class="btn-filtrar">
                    <i class="bi bi-plus-circle"></i> Registrar Atividade Ecológica
                </button>
            </div>
        </div>
    </div>

    <div class="container-new">
        <!-- Dashboard Stats (com skeleton) -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-warning"><i class="bi bi-cloud"></i></div>
                    <div>
                        <p class="stat-title">Carbono Emitido (total)</p>
                        <h3 id="carbonoTotal" class="stat-value"><span class="skeleton-pulse skeleton-value"></span></h3>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-success"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <p class="stat-title">Carbono Emitido (este mês)</p>
                        <h3 id="carbonoMes" class="stat-value"><span class="skeleton-pulse skeleton-value"></span></h3>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-success"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <p class="stat-title">Acertos no quiz:</p>
                        <h3 id="qtdQuiz" class="stat-value"><span class="skeleton-pulse skeleton-value"></span></h3>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-success"><i class="bi bi-tree"></i></div>
                    <div>
                        <p class="stat-title">Total doado:</p>
                        <h3 id="donation" class="stat-value"><span class="skeleton-pulse skeleton-value"></span></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos inline (sem iframes!) -->
        <div class="caixas">
            <div class="graficoOcorrencias" id="chart-comparacao-wrapper">
                <h3 class="grafico-titulo"><i class="bi bi-globe"></i> Sua média em relação aos outros países</h3>
                <div class="grafico-frame">
                    <div class="skeleton-pulse skeleton-chart" id="skeleton-comparacao"></div>
                    <canvas id="chartComparacao" style="display:none;"></canvas>
                </div>
            </div>

            <div class="graficoOcorrencias" id="chart-emissao-wrapper">
                <h3 class="grafico-titulo"><i class="bi bi-cloud"></i> Emissões de CO₂ neste ano</h3>
                <div class="grafico-frame">
                    <div class="skeleton-pulse skeleton-chart" id="skeleton-emissao"></div>
                    <canvas id="chartEmissao" style="display:none;"></canvas>
                </div>
            </div>

            <div class="graficoOcorrencias" id="chart-atividade-wrapper" data-lazy="true">
                <h3 class="grafico-titulo"><i class="bi bi-activity"></i> Suas atividades</h3>
                <div class="grafico-frame">
                    <div class="skeleton-pulse skeleton-chart" id="skeleton-atividade"></div>
                    <canvas id="chartAtividade" style="display:none;"></canvas>
                </div>
            </div>

            <div class="graficoOcorrencias" id="chart-doadores-wrapper" data-lazy="true">
                <h3 class="grafico-titulo"><i class="bi bi-heart"></i> Top doadores</h3>
                <div class="grafico-frame">
                    <div class="skeleton-pulse skeleton-chart" id="skeleton-doadores"></div>
                    <canvas id="chartDoadores" style="display:none;"></canvas>
                </div>
            </div>

            <div class="graficoOcorrencias" id="chart-quiz-wrapper" data-lazy="true">
                <h3 class="grafico-titulo"><i class="bi bi-star"></i> Top quiz</h3>
                <div class="grafico-frame">
                    <div class="skeleton-pulse skeleton-chart" id="skeleton-quiz"></div>
                    <canvas id="chartQuiz" style="display:none;"></canvas>
                </div>
            </div>

            <div class="graficoOcorrencias" id="chart-games-wrapper" data-lazy="true">
                <h3 class="grafico-titulo"><i class="bi bi-controller"></i> Top games</h3>
                <div class="grafico-frame">
                    <div class="skeleton-pulse skeleton-chart" id="skeleton-games"></div>
                    <canvas id="chartGames" style="display:none;"></canvas>
                </div>
            </div>
        </div>

        <!-- Modal Registrar Atividade -->
        <div class="modal-overlay" id="modalOverlay">
            <div class="modal">
                <div class="modal-header">
                    <h2><i class="bi bi-tree"></i> Cadastrar Atividade</h2>
                    <button class="close-btn" id="closeModal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <form id="formAtividade">
                        <div class="form-group">
                            <label for="nome_atividade"><i class="bi bi-tag"></i> Tipo de Atividade</label>
                            <select id="nome_atividade" name="nome_atividade" required>
                                <option value="">Selecione uma atividade</option>
                                <option value="carro">Uso de Carro (km)</option>
                                <option value="energia">Consumo de Energia (kWh)</option>
                                <option value="aviao">Viagem de Avião (km)</option>
                                <option value="carne">Consumo de Carne (kg)</option>
                                <option value="gas">Consumo de Gás (m³)</option>
                                <option value="onibus">Uso de Ônibus (km)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantidade"><i class="bi bi-123"></i> Medida </label>
                            <input type="number" id="quantidade" name="quantidade" required>
                        </div>
                        <div class="form-group">
                            <label for="data_atividade"><i class="bi bi-calendar"></i> Data da Atividade</label>
                            <input type="date" id="data_atividade" name="data_atividade" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="modal-btn btn-cancel" id="cancelModal">Cancelar</button>
                    <button class="modal-btn btn-save" id="btnSalvar"><i class="bi bi-check-circle"></i> Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/functions.js"></script>
    <script src="../js/dashboard.js"></script>
</body>
</html>
