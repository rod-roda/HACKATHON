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
    <title>EcoSystem - Dashboard Climático</title>

    <?php include __DIR__ . '/../public/components/links.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-color: var(--cor-fundo);
            color: var(--cor-texto-principal);
        }

        .dashboard-container {
            padding-top: 100px;
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--space-xl);
        }

        .loading-container, .error-message {
            text-align: center;
            color: var(--primary);
            font-size: var(--font-lg);
            margin-top: var(--space-3xl);
        }

        .location-header {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-xl);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: var(--space-lg);
        }

        .location-header img {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-sm);
        }

        .location-header h1 {
            font-size: var(--font-3xl);
            font-weight: var(--font-bold);
            margin: 0;
            color: var(--primary);
        }

        .search-container {
            display: flex;
            gap: var(--space-md);
            margin-bottom: var(--space-xl);
        }

        .search-container .form-control {
            flex-grow: 1;
        }
        
        .current-data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-md);
            margin-bottom: var(--space-xl);
        }

        .data-card {
            background-color: var(--card-bg-transparent);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--space-md);
            text-align: center;
            box-shadow: var(--shadow-md);
        }
        
        .data-card .value {
            font-size: var(--font-2xl);
            font-weight: var(--font-bold);
            color: var(--text-primary);
        }
        
        .data-card .label {
            font-size: var(--font-sm);
            color: var(--text-muted);
            text-transform: uppercase;
        }
        
        .condition-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .condition-card img {
            width: 64px;
            height: 64px;
            margin-bottom: var(--space-sm);
        }
        .condition-card .label {
            font-size: var(--font-md);
        }

        .charts-section {
            display: grid;
            grid-template-columns: 1fr; /* Cada gráfico em uma linha */
            gap: var(--space-xl); /* Espaçamento maior entre os gráficos */
        }

        .chart-container {
            background-color: var(--card-bg-transparent);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--space-lg);
            box-shadow: var(--shadow-md);
            height: 300px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <div class="dashboard-container">
        
        <div class="location-header">
            <img id="flag-img" src="" alt="Bandeira do país">
            <h1 id="location-title">Monitoramento Climático</h1>
        </div>

        <div class="search-container">
            <input type="text" id="location-input" class="form-control" placeholder="Digite uma cidade e pressione Enter..." />
            <button id="search-button" class="btn btn-primary">Buscar</button>
        </div>

        <div id="loading-message" class="loading-container" style="display: none;">
            <i class="ri-loader-4-line ri-spin"></i> Carregando dados...
        </div>

        <div id="dashboard-content" style="display: none;">
            <div class="current-data-grid">
                <div id="condition-card" class="data-card condition-card">
                    <img id="condition-icon" src="" alt="Ícone do tempo">
                    <div id="condition-text" class="label">--</div>
                </div>
                <div class="data-card">
                    <div id="temp-value" class="value"></div>
                    <div class="label">Temperatura</div>
                </div>
                <div class="data-card">
                    <div id="feelslike-value" class="value"></div>
                    <div class="label">Sensação</div>
                </div>
                <div class="data-card">
                    <div id="wind-value" class="value"></div>
                    <div class="label">Vento</div>
                </div>
                <div class="data-card">
                    <div id="humidity-value" class="value"></div>
                    <div class="label">Umidade</div>
                </div>
                <div class="data-card">
                    <div id="pressure-value" class="value"></div>
                    <div class="label">Pressão</div>
                </div>
            </div>

            <div class="charts-section">
                <div class="chart-container">
                    <canvas id="tempChart"></canvas>
                </div>
                
                <div class="chart-container">
                    <canvas id="windChart"></canvas>
                </div>

                <div class="chart-container">
                    <canvas id="precipChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingMessage = document.getElementById('loading-message');
        const dashboardContent = document.getElementById('dashboard-content');
        const locationTitle = document.getElementById('location-title');
        const flagImg = document.getElementById('flag-img');
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
            plugins: {
                legend: { display: false },
                tooltip: {
                    titleColor: 'var(--text-primary)',
                    bodyColor: 'var(--text-primary)',
                    backgroundColor: 'var(--card-bg)',
                    borderColor: 'var(--border-color)',
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.2)' },
                    ticks: { color: 'var(--text-muted)' }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.2)' },
                    ticks: { color: 'var(--text-muted)' }
                }
            }
        };

        // Função para buscar os dados da API
        async function fetchData(location) {
            loadingMessage.style.display = 'block';
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
            flagImg.src = `https://flagcdn.com/w40/${data.location.country.toLowerCase()}.png`;

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
            const precipitations = forecastHours.map(hour => hour.precip_mm);

            renderCharts(labels, temps, feelslikeTemps, windSpeeds, gustSpeeds, precipitations);
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
        function renderCharts(labels, temps, feelslikeTemps, windSpeeds, gustSpeeds, precipitations) {
            if (charts.tempChart) charts.tempChart.destroy();
            if (charts.windChart) charts.windChart.destroy();
            if (charts.precipChart) charts.precipChart.destroy();
            
            // Gráfico de Temperatura (Temperatura Real vs. Sensação)
            const tempChartElement = document.getElementById('tempChart');
            charts.tempChart = new Chart(tempChartElement, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Temp. Real (°C)',
                        data: temps,
                        borderColor: 'var(--primary)',
                        backgroundColor: 'rgba(0, 255, 255, 0.2)',
                        fill: true, tension: 0.4
                    }, {
                        label: 'Sensação (°C)',
                        data: feelslikeTemps,
                        borderColor: '#FF69B4',
                        backgroundColor: 'rgba(255, 105, 180, 0.2)',
                        fill: true, tension: 0.4
                    }]
                },
                options: chartGlobalOptions
            });

            // Gráfico de Vento (Velocidade vs. Rajadas)
            const windChartElement = document.getElementById('windChart');
            charts.windChart = new Chart(windChartElement, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Velocidade (km/h)',
                        data: windSpeeds,
                        backgroundColor: 'var(--secondary)',
                        borderColor: 'var(--secondary)',
                        borderWidth: 1
                    }, {
                        label: 'Rajadas (km/h)',
                        data: gustSpeeds,
                        backgroundColor: 'rgba(255, 255, 255, 0.4)',
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        borderWidth: 1
                    }]
                },
                options: chartGlobalOptions
            });

            // Gráfico de Precipitação
            const precipChartElement = document.getElementById('precipChart');
            charts.precipChart = new Chart(precipChartElement, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Precipitação (mm)',
                        data: precipitations,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: chartGlobalOptions
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