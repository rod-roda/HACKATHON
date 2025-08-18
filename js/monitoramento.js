document.addEventListener('DOMContentLoaded', function() {
    const loadingMessage = document.getElementById('loading-message');
    const dashboardContent = document.getElementById('dashboard-content');
    const locationTitle = document.getElementById('location-title');
    const flagImg = document.getElementById('flag-img');
    const tempValueDiv = document.getElementById('temp-value');
    const windValueDiv = document.getElementById('wind-value');
    const pressureValueDiv = document.getElementById('pressure-value');
    const humidityValueDiv = document.getElementById('humidity-value');
    const locationInput = document.getElementById('location-input');
    const searchButton = document.getElementById('search-button');

    const charts = {}; // Objeto para armazenar as instâncias dos gráficos

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
                grid: { color: 'rgba(255,255,255,0.1)' },
                ticks: { color: 'var(--text-muted)' }
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.1)' },
                ticks: { color: 'var(--text-muted)' }
            }
        }
    };

    // Função para buscar os dados da API
    async function fetchData(location) {
        // Exibe a mensagem de carregamento e esconde o conteúdo
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
        // Atualiza cabeçalho de localização
        locationTitle.innerText = `Monitoramento Climático - ${data.location.name}, ${data.location.region}`;
        // Corrige o caminho da bandeira para carregar corretamente
        flagImg.src = `https://flagcdn.com/w40/${data.location.country.toLowerCase()}.png`;

        // Atualiza os cards de dados atuais
        tempValueDiv.innerText = `${data.current.temp_c}°C`;
        windValueDiv.innerText = `${data.current.wind_kph} km/h`;
        pressureValueDiv.innerText = `${data.current.pressure_mb} hPa`;
        humidityValueDiv.innerText = `${data.current.humidity}%`;

        // Prepara os dados para os gráficos
        const forecastHours = data.forecast.forecastday[0].hour.slice(0, 12);
        const labels = forecastHours.map(hour => `${new Date(hour.time).getHours()}h`);
        const temps = forecastHours.map(hour => hour.temp_c);
        const humidity = forecastHours.map(hour => hour.humidity);
        const windSpeed = forecastHours.map(hour => hour.wind_kph);

        // Inicializa/Atualiza os gráficos
        renderCharts(labels, temps, humidity, windSpeed);
        dashboardContent.style.display = 'block';
    }

    // Função para exibir erro
    function showError(message) {
        locationTitle.innerText = 'Erro';
        const errorHtml = `<p class="loading-container" style="color: var(--error);">${message}</p>`;
        dashboardContent.innerHTML = errorHtml;
        dashboardContent.style.display = 'block';
    }

    // Função para renderizar os gráficos
    function renderCharts(labels, temps, humidity, windSpeed) {
        const chartConfigs = [
            { id: 'tempChart', label: 'Temperatura (°C)', data: temps, type: 'line', borderColor: 'var(--primary)', backgroundColor: 'rgba(0, 255, 255, 0.2)' },
            { id: 'windChart', label: 'Vento (km/h)', data: windSpeed, type: 'bar', backgroundColor: 'var(--secondary)', borderColor: 'var(--secondary)' },
            { id: 'humidityChart', label: 'Umidade (%)', data: humidity, type: 'line', borderColor: 'var(--accent)', backgroundColor: 'rgba(255, 165, 0, 0.2)' }
        ];

        chartConfigs.forEach(config => {
            if (charts[config.id]) {
                charts[config.id].data.labels = labels;
                charts[config.id].data.datasets[0].data = config.data;
                charts[config.id].update();
            } else {
                charts[config.id] = new Chart(document.getElementById(config.id), {
                    type: config.type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: config.label,
                            data: config.data,
                            borderColor: config.borderColor,
                            backgroundColor: config.backgroundColor,
                            fill: config.type === 'line' ? true : false,
                            tension: 0.4
                        }]
                    },
                    options: chartGlobalOptions // Usando as opções globais
                });
            }
        });
    }

    // Eventos de interação do usuário
    searchButton.addEventListener('click', () => {
        const location = locationInput.value;
        if (location) {
            fetchData(location);
        }
    });

    locationInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            searchButton.click();
        }
    });

    // Inicia o carregamento dos dados para uma localização padrão
    fetchData('São José dos Campos');
});