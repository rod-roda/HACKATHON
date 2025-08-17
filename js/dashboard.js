// Use unique variable names to avoid conflicts
const modalBtnAbrir = document.getElementById("btn-filtrar");
const modalOverlay = document.getElementById("modalOverlay");
const modalBtnFechar = document.getElementById("closeModal");
const modalBtnCancelar = document.getElementById("cancelModal");

// Add null checks for safety
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


btnSalvar.addEventListener("click", () => {
    registrarAtividade();
});



async function registrarAtividade() {
    try {
        const form = document.getElementById("formAtividade");

        // Validação dos campos
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
            usuario_id: 1, // exemplo, ideal pegar do token JWT
            nome_atividade: form.nome_atividade.value,
            quantidade: form.quantidade.value,
            data_atividade: form.data_atividade.value
        };

        const resposta = await fetch("/HACKATHON/dashboard", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                //"Authorization": "Bearer SEU_TOKEN_AQUI"
            },
            body: JSON.stringify(dados)
        });

        const json = await resposta.json();
        
        if (json.status) {
            modalOverlay.classList.remove("active");
            form.reset();
            
            // Mostra o toast de sucesso
            showNotification(json.mensagem || "Atividade registrada com sucesso!", "success");
            
            // Espera um momento para mostrar o toast antes do reload
            setTimeout(() => {
                // Recarrega a página completamente
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


async function carregarCarbonoStats() {
    try {
        const resposta = await fetch("/HACKATHON/dashboard/relatorio/dashboards");
        const json = await resposta.json();

        if (json.status) {
            // Carbono Total
            const dadosTotal = json.dados.total;
            document.getElementById("carbonoTotal").innerText = dadosTotal.total + " kg";
            document.getElementById("carbonoVariacao").innerText = `${dadosTotal.comparacao > 0 ? '+' : ''}${dadosTotal.comparacao}%`;
            document.getElementById("carbonoVariacao").className = dadosTotal.comparacao > 0 ? 'text-danger' : 'text-success';

            // Carbono Mensal
            const dadosMes = json.dados.mes;
            document.getElementById("carbonoMes").innerText = dadosMes.total + " kg";
            document.getElementById("diasAtivosCarbonoMes").innerText = dadosMes.dias_ativos;
            document.getElementById("tendenciaMes").innerText = `${dadosMes.tendencia_mes > 0 ? '+' : ''}${dadosMes.tendencia_mes}%`;
            document.getElementById("tendenciaMes").className = dadosMes.tendencia_mes > 0 ? 'text-danger' : 'text-success';

            // Quiz
            const dadosQuiz = json.dados.quiz_acertos_mes;
            document.getElementById("qtdQuiz").innerText = dadosQuiz.pontuacao + " pts";
            document.getElementById("diasQuiz").innerText = dadosQuiz.dias_jogados;
            document.getElementById("evolucaoQuiz").innerText = `${dadosQuiz.evolucao > 0 ? '+' : ''}${dadosQuiz.evolucao}%`;
            document.getElementById("evolucaoQuiz").className = dadosQuiz.evolucao >= 0 ? 'text-success' : 'text-danger';

            // Doações
            const dadosDoacao = json.dados.total_doado_mes;
            document.getElementById("donation").innerText = "R$ " + dadosDoacao.total;
            document.getElementById("diasDoacao").innerText = dadosDoacao.dias_doacao;
            document.getElementById("evolucaoDoacao").innerText = `${dadosDoacao.evolucao > 0 ? '+' : ''}${dadosDoacao.evolucao}%`;
            document.getElementById("evolucaoDoacao").className = dadosDoacao.evolucao >= 0 ? 'text-success' : 'text-danger';
        }
    } catch (err) {
        console.error("Erro ao carregar stats de carbono:", err);
    }
}


// chama ao carregar a página
document.addEventListener("DOMContentLoaded", carregarCarbonoStats);