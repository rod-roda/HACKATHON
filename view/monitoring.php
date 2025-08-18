<?php
// Lógica PHP para buscar os dados da API
// Esta lógica é executada no servidor antes de a página ser enviada ao navegador
$apiKey = "ac194985c6b749509d8235429251308";
$localizacao_padrao = "São José dos Campos";
$lang = 'pt';
$apiUrl = "https://api.weatherapi.com/v1/forecast.json?key={$apiKey}&q={$localizacao_padrao}&days=3&lang={$lang}";

// Inicializa cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento Climático | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            /* background: linear-gradient(135deg, var(--primary) 0%, #1a1a2e 50%, var(--primary) 100%); */
            background: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background: radial-gradient(circle at 20% 80%, rgba(41, 253, 83, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(41, 253, 83, 0.1) 0%, transparent 50%); */
            pointer-events: none;
            z-index: -1;
        }

        .dashboard-container {
            padding-top: 120px;
            max-width: 1400px;
            margin: 0 auto;
            padding-left: var(--space-xl);
            padding-right: var(--space-xl);
            padding-bottom: var(--space-4xl);
        }

        .page-header {
            text-align: center;
            margin-bottom: var(--space-4xl);
            position: relative;
        }

        .page-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: var(--font-black);
            background: linear-gradient(135deg, var(--main-color), #00ff88, var(--main-color));
            background-size: 200% 200%;
            animation: gradientShift 3s ease-in-out infinite;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: var(--space-md);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-lg);
        }

        .page-title i {
            font-size: clamp(2.5rem, 5vw, 4rem);
            color: var(--main-color);
            filter: drop-shadow(0 0 20px rgba(41, 253, 83, 0.5));
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .page-subtitle {
            font-size: var(--font-xl);
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 30vh;
            text-align: center;
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(41, 253, 83, 0.2);
            border-top: 4px solid var(--main-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: var(--space-xl);
            box-shadow: 0 0 30px rgba(41, 253, 83, 0.3);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: var(--font-xl);
            color: var(--main-color);
            font-weight: var(--font-semibold);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: var(--radius-xl);
            padding: var(--space-2xl);
            text-align: center;
            color: #ff6b6b;
            font-size: var(--font-lg);
            margin-top: var(--space-3xl);
        }

        .location-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-lg);
            margin-bottom: var(--space-3xl);
            padding: var(--space-xl);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-2xl);
            border: 1px solid rgba(41, 253, 83, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .location-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(41, 253, 83, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        #location-icon {
            font-size: 3rem;
            color: var(--main-color);
            text-shadow: 0 0 20px rgba(41, 253, 83, 0.5);
            filter: drop-shadow(0 0 10px rgba(41, 253, 83, 0.3));
        }

        .location-title {
            font-size: var(--font-3xl);
            font-weight: var(--font-bold);
            margin: 0;
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .search-container {
            display: flex;
            gap: var(--space-md);
            margin-bottom: var(--space-3xl);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .search-container .form-control {
            flex-grow: 1;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(41, 253, 83, 0.3);
            border-radius: var(--radius-xl);
            padding: var(--space-lg) var(--space-xl);
            color: var(--text-primary);
            font-size: var(--font-lg);
            transition: all var(--transition-normal);
        }

        .search-container .form-control:focus {
            outline: none;
            border-color: var(--main-color);
            box-shadow: 0 0 0 4px rgba(41, 253, 83, 0.2), 0 0 20px rgba(41, 253, 83, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        .search-container .form-control::placeholder {
            color: var(--text-secondary);
        }

        .search-container .btn {
            padding: var(--space-md) var(--space-xl);
            border-radius: var(--radius-xl);
            font-weight: var(--font-semibold);
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .search-container .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .search-container .btn:hover::before {
            left: 100%;
        }
        
        .weather-hero {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(41, 253, 83, 0.2);
            border-radius: var(--radius-3xl);
            padding: var(--space-2xl);
            margin-bottom: var(--space-3xl);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .current-data-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: var(--space-lg);
            margin-bottom: var(--space-3xl);
        }

        .data-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(41, 253, 83, 0.2);
            border-radius: var(--radius-2xl);
            padding: var(--space-xl);
            text-align: center;
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .data-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--main-color), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .data-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: var(--main-color);
            box-shadow: 0 20px 40px rgba(41, 253, 83, 0.2);
        }

        .data-card:hover::before {
            transform: translateX(100%);
        }
        
        .data-card .value {
            font-size: var(--font-3xl);
            font-weight: var(--font-black);
            color: var(--main-color);
            text-shadow: 0 0 10px rgba(41, 253, 83, 0.5);
            margin-bottom: var(--space-sm);
            display: block;
        }
        
        .data-card .label {
            font-size: var(--font-md);
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: var(--font-semibold);
        }
        
        .condition-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .condition-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(45deg, var(--main-color), transparent, var(--main-color));
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: xor;
            -webkit-mask-composite: xor;
        }

        .condition-card img {
            width: 80px;
            height: 80px;
            margin-bottom: var(--space-md);
            filter: drop-shadow(0 0 20px rgba(41, 253, 83, 0.5));
        }

        .condition-card .label {
            font-size: var(--font-xl);
            color: var(--text-primary);
            text-transform: none;
            letter-spacing: normal;
        }

        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: var(--space-2xl);
            margin-top: var(--space-3xl);
        }

        .chart-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(41, 253, 83, 0.2);
            border-radius: var(--radius-2xl);
            padding: var(--space-lg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            transition: all var(--transition-normal);
        }

        .chart-container:hover {
            transform: translateY(-5px);
            border-color: var(--main-color);
            box-shadow: 0 20px 40px rgba(41, 253, 83, 0.2);
        }

        .chart-title {
            font-size: var(--font-xl);
            font-weight: var(--font-bold);
            color: var(--primary);
            margin-bottom: var(--space-lg);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-md);
            flex-shrink: 0;
            padding-bottom: var(--space-md);
            border-bottom: 1px solid rgba(41, 253, 83, 0.2);
        }

        .chart-wrapper {
            flex: 1;
            position: relative;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-wrapper canvas {
            max-width: 100% !important;
            max-height: 100% !important;
            width: auto !important;
            height: auto !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding-left: var(--space-md);
                padding-right: var(--space-md);
            }

            .page-title {
                font-size: var(--font-3xl);
                flex-direction: column;
                gap: var(--space-md);
            }

            .current-data-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(3, 1fr);
                gap: var(--space-md);
            }

            .condition-card {
                grid-column: span 1;
            }

            .charts-section {
                grid-template-columns: 1fr;
                gap: var(--space-xl);
            }

            .chart-container {
                min-height: 350px;
                padding: var(--space-md);
            }

            .chart-wrapper {
                min-height: 280px;
            }

            .search-container {
                flex-direction: column;
                gap: var(--space-md);
            }

            .location-header {
                flex-direction: column;
                text-align: center;
                gap: var(--space-md);
            }
        }

        @media (max-width: 480px) {
            .current-data-grid {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(6, 1fr);
                gap: var(--space-md);
            }

            .data-card .value {
                font-size: var(--font-2xl);
            }

            .chart-container {
                min-height: 300px;
                padding: var(--space-md);
            }

            .chart-wrapper {
                min-height: 250px;
            }
        }
    </style>
</head>

<body>
    <script>
        if(!(localStorage.getItem('token')))  window.location.href = "logar.php"; //TODO -- DEIXAR ISSO MAIS SEGURO DPS COM O SERVERSIDE DO PHP
    </script>

    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <div class="dashboard-container">
        
        <div class="page-header">
            <h1 class="page-title">
                <i class="ri-cloud-line"></i>
                Monitoramento Climático
            </h1>
            <p class="page-subtitle">
                Monitoramento em tempo real das condições meteorológicas para sustentabilidade ambiental
            </p>
        </div>

        <div id="loading-message" class="loading-container" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text">Carregando dados climáticos...</div>
        </div>

        <div id="dashboard-content" style="display: none;">
            <div class="location-header">
                <i class="ri-building-fill" id="location-icon"></i>
                <h2 id="location-title" class="location-title">Monitoramento Climático</h2>
            </div>

            <div class="search-container">
                <input type="text" id="location-input" class="form-control" placeholder="Digite o nome de uma cidade..." />
                <button id="search-button" class="btn btn-primary">
                    <i class="ri-search-line"></i> Buscar
                </button>
            </div>

            <div class="weather-hero">
                <div class="current-data-grid">
                    <div id="condition-card" class="data-card">
                        <img id="condition-icon" src="" alt="Ícone do tempo">
                        <div id="condition-text" class="label">--</div>
                    </div>
                    <div class="data-card">
                        <span id="temp-value" class="value">--°C</span>
                        <div class="label">🌡️ Temperatura</div>
                    </div>
                    <div class="data-card">
                        <span id="feelslike-value" class="value">--°C</span>
                        <div class="label">🌡️ Sensação Térmica</div>
                    </div>
                    <div class="data-card">
                        <span id="wind-value" class="value">-- km/h</span>
                        <div class="label">💨 Velocidade do Vento</div>
                    </div>
                    <div class="data-card">
                        <span id="humidity-value" class="value">--%</span>
                        <div class="label">💧 Umidade Relativa</div>
                    </div>
                    <div class="data-card">
                        <span id="pressure-value" class="value">-- hPa</span>
                        <div class="label">📊 Pressão Atmosférica</div>
                    </div>
                </div>
            </div>

            <div class="charts-section">
                <div class="chart-container">
                    <h3 class="chart-title">🌡️ Variação de Temperatura</h3>
                    <div class="chart-wrapper">
                        <canvas id="tempChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-container">
                    <h3 class="chart-title">💨 Velocidade do Vento</h3>
                    <div class="chart-wrapper">
                        <canvas id="windChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingMessage = document.getElementById('loading-message');
        const dashboardContent = document.getElementById('dashboard-content');
        const locationTitle = document.getElementById('location-title');
        const tempValueDiv = document.getElementById('temp-value');
        const feelslikeValueDiv = document.getElementById('feelslike-value');
        const windValueDiv = document.getElementById('wind-value');
        const humidityValueDiv = document.getElementById('humidity-value');
        const pressureValueDiv = document.getElementById('pressure-value');
        const conditionIcon = document.getElementById('condition-icon');
        const conditionText = document.getElementById('condition-text');
        const locationInput = document.getElementById('location-input');
        const searchButton = document.getElementById('search-button');

        const charts = {};

        // Configurações de estilo para os gráficos
        const chartGlobalOptions = {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            },
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#ffffff',
                        font: { 
                            size: 13, 
                            weight: '600' 
                        },
                        usePointStyle: true,
                        padding: 15,
                        boxWidth: 12,
                        boxHeight: 12
                    }
                },
                tooltip: {
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    borderColor: 'var(--main-color)',
                    borderWidth: 2,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    padding: 12,
                    displayColors: true,
                    usePointStyle: true
                }
            },
            scales: {
                x: {
                    grid: { 
                        color: 'rgba(41, 253, 83, 0.1)',
                        borderColor: 'rgba(41, 253, 83, 0.3)'
                    },
                    ticks: { 
                        color: '#ffffff',
                        font: { size: 11, weight: '500' },
                        maxTicksLimit: 12,
                        padding: 5
                    }
                },
                y: {
                    grid: { 
                        color: 'rgba(41, 253, 83, 0.1)',
                        borderColor: 'rgba(41, 253, 83, 0.3)'
                    },
                    ticks: { 
                        color: '#ffffff',
                        font: { size: 11, weight: '500' },
                        padding: 5
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6,
                    borderWidth: 2
                },
                line: {
                    borderWidth: 3,
                    tension: 0.4
                },
                bar: {
                    borderRadius: 4,
                    borderSkipped: false
                }
            }
        };

        // Função para buscar os dados da API
        async function fetchData(location) {
            loadingMessage.style.display = 'flex';
            dashboardContent.style.display = 'none';

            try {
                const response = await fetch(`/HACKATHON/controle/monitoramento/controle_monitoramento.php?localizacao=${location}`);
                const data = await response.json();

                if (response.ok) {
                    updateDashboard(data);
                } else {
                    showError(data.erro);
                }
            } catch (error) {
                console.error('Erro ao buscar dados:', error);
                showError('Não foi possível conectar ao servidor.');
            } finally {
                loadingMessage.style.display = 'none';
            }
        }

        // Função para atualizar a interface com os dados
        function updateDashboard(data) {
            locationTitle.innerText = `${data.location.name}, ${data.location.region}`;

            tempValueDiv.innerText = `${data.current.temp_c}°C`;
            feelslikeValueDiv.innerText = `${data.current.feelslike_c}°C`;
            windValueDiv.innerText = `${data.current.wind_kph} km/h`;
            humidityValueDiv.innerText = `${data.current.humidity}%`;
            pressureValueDiv.innerText = `${data.current.pressure_mb} hPa`;

            conditionIcon.src = `https:${data.current.condition.icon}`;
            conditionIcon.alt = data.current.condition.text;
            conditionText.innerText = data.current.condition.text;

            const forecastHours = data.forecast.forecastday[0].hour.slice(0, 24);
            const labels = forecastHours.map(hour => `${new Date(hour.time).getHours()}h`);
            const temps = forecastHours.map(hour => hour.temp_c);
            const feelslikeTemps = forecastHours.map(hour => hour.feelslike_c);
            const windSpeeds = forecastHours.map(hour => hour.wind_kph);
            const gustSpeeds = forecastHours.map(hour => hour.gust_kph);

            renderCharts(labels, temps, feelslikeTemps, windSpeeds, gustSpeeds);
            dashboardContent.style.display = 'block';
        }

        // Função para exibir erro
        function showError(message) {
            locationTitle.innerText = 'Erro';
            const errorHtml = `<p class="error-message" style="color: var(--error);">${message}</p>`;
            dashboardContent.innerHTML = errorHtml;
            dashboardContent.style.display = 'block';
        }

        // Função para renderizar os gráficos
        function renderCharts(labels, temps, feelslikeTemps, windSpeeds, gustSpeeds) {
            if (charts.tempChart) charts.tempChart.destroy();
            if (charts.windChart) charts.windChart.destroy();
            
            // Gráfico de Temperatura (Temperatura Real vs. Sensação)
            const tempChartElement = document.getElementById('tempChart');
            charts.tempChart = new Chart(tempChartElement, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Temperatura Real (°C)',
                        data: temps,
                        borderColor: 'var(--main-color)',
                        backgroundColor: 'rgba(41, 253, 83, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'black',
                        pointBorderColor: 'var(--primary)',
                        pointHoverBackgroundColor: '#00ff88'
                    }, {
                        label: 'Sensação Térmica (°C)',
                        data: feelslikeTemps,
                        borderColor: '#00ddff',
                        backgroundColor: 'rgba(0, 221, 255, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#00ddff',
                        pointBorderColor: 'var(--primary)',
                        pointHoverBackgroundColor: '#33e6ff'
                    }]
                },
                options: {
                    ...chartGlobalOptions,
                    scales: {
                        ...chartGlobalOptions.scales,
                        y: {
                            ...chartGlobalOptions.scales.y,
                            title: {
                                display: true,
                                text: 'Temperatura (°C)',
                                color: '#ffffff',
                                font: { size: 12, weight: '600' },
                                padding: { top: 0, bottom: 10 }
                            }
                        }
                    }
                }
            });

            // Gráfico de Vento (Velocidade vs. Rajadas)
            const windChartElement = document.getElementById('windChart');
            charts.windChart = new Chart(windChartElement, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Velocidade do Vento (km/h)',
                        data: windSpeeds,
                        backgroundColor: 'rgba(41, 253, 83, 0.7)',
                        borderColor: 'var(--main-color)',
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(41, 253, 83, 0.9)'
                    }, {
                        label: 'Rajadas (km/h)',
                        data: gustSpeeds,
                        backgroundColor: 'rgba(0, 221, 255, 0.7)',
                        borderColor: '#00ddff',
                        borderWidth: 2,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(0, 221, 255, 0.9)'
                    }]
                },
                options: {
                    ...chartGlobalOptions,
                    scales: {
                        ...chartGlobalOptions.scales,
                        y: {
                            ...chartGlobalOptions.scales.y,
                            title: {
                                display: true,
                                text: 'Velocidade (km/h)',
                                color: '#ffffff',
                                font: { size: 12, weight: '600' },
                                padding: { top: 0, bottom: 10 }
                            }
                        }
                    }
                }
            });
        }

        // Eventos de interação do usuário
        searchButton.addEventListener('click', () => {
            const location = locationInput.value;
            if (location) fetchData(location);
        });

        locationInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') searchButton.click();
        });

        fetchData('São José dos Campos');
    });
    </script>
</body>
</html>