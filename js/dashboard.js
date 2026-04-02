/**
 * Dashboard.js - Versão otimizada
 * 
 * OTIMIZAÇÕES APLICADAS:
 * 1. UMA ÚNICA requisição ao backend (combo endpoint) em vez de 7+
 * 2. Cache em sessionStorage (5 min) para evitar re-fetches
 * 3. Lazy loading de gráficos com Intersection Observer
 * 4. Skeleton loading para feedback visual imediato
 * 5. Instâncias de Chart.js destruídas antes de recriar (evita memory leaks)
 */

// ============================================================
// MODAL CONTROLS
// ============================================================
const modalBtnAbrir = document.getElementById("btn-filtrar");
const modalOverlay = document.getElementById("modalOverlay");
const modalBtnFechar = document.getElementById("closeModal");
const modalBtnCancelar = document.getElementById("cancelModal");
const btnSalvar = document.getElementById("btnSalvar");

if (modalBtnAbrir) {
    modalBtnAbrir.addEventListener("click", () => {
        modalOverlay.classList.add("active");
    });
}

if (modalBtnFechar) {
    modalBtnFechar.addEventListener("click", () => {
        modalOverlay.classList.remove("active");
    });
}

if (modalBtnCancelar) {
    modalBtnCancelar.addEventListener("click", () => {
        modalOverlay.classList.remove("active");
    });
}

if (modalOverlay) {
    modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.remove("active");
        }
    });
}

if (btnSalvar) {
    btnSalvar.addEventListener("click", () => {
        registrarAtividade();
    });
}

// ============================================================
// REGISTRAR ATIVIDADE
// ============================================================
async function registrarAtividade() {
    try {
        const form = document.getElementById("formAtividade");

        if (!form.nome_atividade.value) {
            showNotification("Por favor, selecione uma atividade", "warning");
            return;
        }
        if (!form.quantidade.value || form.quantidade.value <= 0) {
            showNotification("Por favor, informe uma quantidade válida", "warning");
            return;
        }
        if (!form.data_atividade.value) {
            showNotification("Por favor, selecione uma data", "warning");
            return;
        }

        const dados = {
            nome_atividade: form.nome_atividade.value,
            quantidade: form.quantidade.value,
            data_atividade: form.data_atividade.value
        };
        
        let token = localStorage.getItem('token');

        const resposta = await fetch("/HACKATHON/dashboard", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(dados)
        });

        const json = await resposta.json();

        if (json.status) {
            modalOverlay.classList.remove("active");
            form.reset();
            showNotification(json.mensagem || "Atividade registrada com sucesso!", "success");

            // Limpa o cache para forçar recarregamento dos dados atualizados
            sessionStorage.removeItem('dashboardComboData');
            sessionStorage.removeItem('dashboardComboTimestamp');

            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(json.mensagem || "Erro ao registrar atividade!", "error");
        }

    } catch (err) {
        console.error("Erro ao cadastrar:", err);
        showNotification("Erro ao registrar atividade. Tente novamente.", "error");
    }
}

// ============================================================
// CACHE MANAGER (sessionStorage, 5 min TTL)
// ============================================================
const CACHE_KEY = 'dashboardComboData';
const CACHE_TS_KEY = 'dashboardComboTimestamp';
const CACHE_TTL = 5 * 60 * 1000; // 5 minutos em ms

function getCachedData() {
    try {
        const ts = sessionStorage.getItem(CACHE_TS_KEY);
        if (!ts) return null;
        
        const age = Date.now() - parseInt(ts, 10);
        if (age > CACHE_TTL) {
            sessionStorage.removeItem(CACHE_KEY);
            sessionStorage.removeItem(CACHE_TS_KEY);
            return null;
        }
        
        const data = sessionStorage.getItem(CACHE_KEY);
        return data ? JSON.parse(data) : null;
    } catch (e) {
        return null;
    }
}

function setCachedData(data) {
    try {
        sessionStorage.setItem(CACHE_KEY, JSON.stringify(data));
        sessionStorage.setItem(CACHE_TS_KEY, Date.now().toString());
    } catch (e) {
        // sessionStorage cheio, ignora silenciosamente
    }
}

// ============================================================
// FETCH DATA (ÚNICA REQUISIÇÃO)
// ============================================================
async function fetchDashboardData() {
    // Tenta usar cache primeiro
    const cached = getCachedData();
    if (cached) {
        return cached;
    }

    // Sem cache válido: faz requisição ao endpoint combo
    const token = localStorage.getItem('token');
    const resp = await fetch("/HACKATHON/dashboard/relatorio/combo", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            'Authorization': `Bearer ${token}`,
        },
    });

    const json = await resp.json();

    if (json.status && json.dados) {
        setCachedData(json.dados);
        return json.dados;
    }

    throw new Error(json.message || "Erro ao carregar dados do dashboard");
}

// ============================================================
// POPULATE STAT CARDS
// ============================================================
function populateStats(dados) {
    const carbonoTotal = document.getElementById("carbonoTotal");
    const carbonoMes = document.getElementById("carbonoMes");
    const qtdQuiz = document.getElementById("qtdQuiz");
    const donation = document.getElementById("donation");

    if (carbonoTotal && dados.total) {
        carbonoTotal.textContent = dados.total.total + " kg";
    }
    if (carbonoMes && dados.mes) {
        carbonoMes.textContent = dados.mes.total + " kg";
    }
    if (qtdQuiz && dados.quiz_acertos_mes) {
        qtdQuiz.textContent = dados.quiz_acertos_mes.pontuacao + " pts";
    }
    if (donation && dados.total_doado_mes) {
        donation.textContent = "R$ " + dados.total_doado_mes.total;
    }
}

// ============================================================
// CHART.JS - Configuração global compartilhada
// ============================================================
const chartGlobalDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top',
            labels: {
                color: '#ffffff',
                font: { size: 12, weight: '500', family: 'Poppins' },
                usePointStyle: true,
                padding: 12,
                boxWidth: 10,
                boxHeight: 10
            }
        },
        tooltip: {
            titleColor: '#ffffff',
            bodyColor: '#ffffff',
            backgroundColor: 'rgba(0, 0, 0, 0.9)',
            borderColor: '#29fd53',
            borderWidth: 1,
            cornerRadius: 8,
            titleFont: { size: 13, weight: 'bold', family: 'Poppins' },
            bodyFont: { size: 12, family: 'Poppins' },
            padding: 10,
            displayColors: true,
            usePointStyle: true
        }
    },
    scales: {
        x: {
            grid: { color: 'rgba(41, 253, 83, 0.08)' },
            ticks: {
                color: '#aaa',
                font: { size: 11, family: 'Poppins' },
                maxTicksLimit: 12
            }
        },
        y: {
            grid: { color: 'rgba(41, 253, 83, 0.08)' },
            ticks: {
                color: '#aaa',
                font: { size: 11, family: 'Poppins' }
            }
        }
    }
};

// Paleta de cores premium para os gráficos
const chartColors = [
    '#29fd53', '#00ddff', '#ff6b6b', '#ffc107', '#a855f7',
    '#f97316', '#22d3ee', '#ec4899', '#84cc16', '#6366f1'
];

// Armazena instâncias para destruir antes de recriar
const chartInstances = {};

function destroyChart(id) {
    if (chartInstances[id]) {
        chartInstances[id].destroy();
        delete chartInstances[id];
    }
}

// ============================================================
// RENDER CHARTS
// ============================================================
function showCanvas(canvasId, skeletonId) {
    const skeleton = document.getElementById(skeletonId);
    const canvas = document.getElementById(canvasId);
    if (skeleton) skeleton.style.display = 'none';
    if (canvas) {
        canvas.style.display = 'block';
        canvas.classList.add('chart-loaded');
    }
}

function renderComparacaoChart(dados) {
    if (!dados || !dados.length) return;
    destroyChart('chartComparacao');

    const labels = dados.map(d => d.category);
    const values = dados.map(d => d.valor);
    const colors = dados.map((_, i) => chartColors[i % chartColors.length]);

    const ctx = document.getElementById('chartComparacao');
    if (!ctx) return;

    chartInstances['chartComparacao'] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Emissão diária (kg CO₂)',
                data: values,
                backgroundColor: colors.map(c => c + '99'),
                borderColor: colors,
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            ...chartGlobalDefaults,
            indexAxis: 'y',
            plugins: {
                ...chartGlobalDefaults.plugins,
                legend: { display: false }
            },
            scales: {
                x: {
                    ...chartGlobalDefaults.scales.x,
                    beginAtZero: true
                },
                y: {
                    ...chartGlobalDefaults.scales.y,
                    ticks: {
                        ...chartGlobalDefaults.scales.y.ticks,
                        color: '#fff',
                        font: { size: 13, weight: '600', family: 'Poppins' }
                    }
                }
            }
        }
    });

    showCanvas('chartComparacao', 'skeleton-comparacao');
}

function renderEmissaoChart(dados) {
    if (!dados || !dados.length) return;
    destroyChart('chartEmissao');

    const labels = dados.map(d => d.mes);
    const values = dados.map(d => d.carbono);

    const ctx = document.getElementById('chartEmissao');
    if (!ctx) return;

    chartInstances['chartEmissao'] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Carbono (kg)',
                data: values,
                borderColor: '#29fd53',
                backgroundColor: 'rgba(41, 253, 83, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#222327',
                pointBorderColor: '#29fd53',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: '#29fd53'
            }]
        },
        options: {
            ...chartGlobalDefaults,
            scales: {
                ...chartGlobalDefaults.scales,
                y: {
                    ...chartGlobalDefaults.scales.y,
                    beginAtZero: true
                }
            }
        }
    });

    showCanvas('chartEmissao', 'skeleton-emissao');
}

function renderAtividadeChart(dados) {
    if (!dados || !dados.length) return;
    destroyChart('chartAtividade');

    const labels = dados.map(d => d.category);
    const values = dados.map(d => d.value);
    const colors = dados.map((_, i) => chartColors[i % chartColors.length]);

    const ctx = document.getElementById('chartAtividade');
    if (!ctx) return;

    chartInstances['chartAtividade'] = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.map(c => c + 'CC'),
                borderColor: '#201f1f',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                ...chartGlobalDefaults.plugins,
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#fff',
                        font: { size: 12, family: 'Poppins' },
                        usePointStyle: true,
                        padding: 15
                    }
                }
            }
        }
    });

    showCanvas('chartAtividade', 'skeleton-atividade');
}

function renderDoadoresChart(dados) {
    if (!dados || !dados.length) return;
    destroyChart('chartDoadores');

    const labels = dados.map(d => d.category);
    const values = dados.map(d => d.valor);

    const ctx = document.getElementById('chartDoadores');
    if (!ctx) return;

    chartInstances['chartDoadores'] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total doado (R$)',
                data: values,
                backgroundColor: 'rgba(41, 253, 83, 0.7)',
                borderColor: '#29fd53',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            ...chartGlobalDefaults,
            scales: {
                ...chartGlobalDefaults.scales,
                y: {
                    ...chartGlobalDefaults.scales.y,
                    beginAtZero: true
                }
            }
        }
    });

    showCanvas('chartDoadores', 'skeleton-doadores');
}

function renderQuizChart(dados) {
    if (!dados || !dados.length) return;
    destroyChart('chartQuiz');

    const labels = dados.map(d => d.category);
    const values = dados.map(d => d.valor);

    const ctx = document.getElementById('chartQuiz');
    if (!ctx) return;

    chartInstances['chartQuiz'] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pontuação',
                data: values,
                backgroundColor: 'rgba(0, 221, 255, 0.7)',
                borderColor: '#00ddff',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            ...chartGlobalDefaults,
            scales: {
                ...chartGlobalDefaults.scales,
                y: {
                    ...chartGlobalDefaults.scales.y,
                    beginAtZero: true
                }
            }
        }
    });

    showCanvas('chartQuiz', 'skeleton-quiz');
}

function renderGamesChart(dados) {
    if (!dados || !dados.length) return;
    destroyChart('chartGames');

    const labels = dados.map(d => d.category);
    const values = dados.map(d => d.valor);

    const ctx = document.getElementById('chartGames');
    if (!ctx) return;

    chartInstances['chartGames'] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Resultado',
                data: values,
                backgroundColor: 'rgba(168, 85, 247, 0.7)',
                borderColor: '#a855f7',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            ...chartGlobalDefaults,
            scales: {
                ...chartGlobalDefaults.scales,
                y: {
                    ...chartGlobalDefaults.scales.y,
                    beginAtZero: true
                }
            }
        }
    });

    showCanvas('chartGames', 'skeleton-games');
}

// ============================================================
// LAZY LOADING com Intersection Observer
// ============================================================
let dashboardData = null;
const renderedCharts = new Set();

const lazyChartMap = {
    'chart-atividade-wrapper': () => renderAtividadeChart(dashboardData?.resumo),
    'chart-doadores-wrapper': () => renderDoadoresChart(dashboardData?.doadores),
    'chart-quiz-wrapper': () => renderQuizChart(dashboardData?.quizzes),
    'chart-games-wrapper': () => renderGamesChart(dashboardData?.jogos),
};

function setupLazyLoading() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && dashboardData) {
                const id = entry.target.id;
                if (!renderedCharts.has(id) && lazyChartMap[id]) {
                    lazyChartMap[id]();
                    renderedCharts.add(id);
                    observer.unobserve(entry.target);
                }
            }
        });
    }, {
        rootMargin: '100px', // Carrega 100px antes de entrar na viewport
        threshold: 0.1
    });

    // Observa apenas os gráficos marcados como lazy
    document.querySelectorAll('[data-lazy="true"]').forEach(el => {
        observer.observe(el);
    });
}

// ============================================================
// INICIALIZAÇÃO
// ============================================================
document.addEventListener("DOMContentLoaded", async function() {
    try {
        dashboardData = await fetchDashboardData();

        // 1. Popula stat cards imediatamente
        populateStats(dashboardData);

        // 2. Renderiza os 2 primeiros gráficos (visíveis acima da dobra)
        renderComparacaoChart(dashboardData.comparacao);
        renderEmissaoChart(dashboardData.carbono_mes);

        // 3. Configura lazy loading para os demais
        setupLazyLoading();

    } catch (err) {
        console.error("Erro ao carregar dashboard:", err);
    }
});