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
        let token = localStorage.getItem('token');
        const resposta = await fetch("/HACKATHON/dashboard/relatorio/dashboards", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            'Authorization': `Bearer ${token}`,
        },
        });


        const json = await resposta.json();

        if (json.status) {
            // Carbono Total
            const dadosTotal = json.dados.total;
            document.getElementById("carbonoTotal").innerText = dadosTotal.total + " kg";
            
            // Carbono Mensal
            const dadosMes = json.dados.mes;
            document.getElementById("carbonoMes").innerText = dadosMes.total + " kg";
          

            // Quiz
            const dadosQuiz = json.dados.quiz_acertos_mes;
            document.getElementById("qtdQuiz").innerText = dadosQuiz.pontuacao + " pts";
        
            // Doações
            const dadosDoacao = json.dados.total_doado_mes;
            document.getElementById("donation").innerText = "R$ " + dadosDoacao.total;
        
        }
    } catch (err) {
        console.error("Erro ao carregar stats de carbono:", err);
    }
}


// chama ao carregar a página
document.addEventListener("DOMContentLoaded", carregarCarbonoStats);