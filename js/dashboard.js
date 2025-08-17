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

        const dados = {
            usuario_id: 1, // exemplo, ideal pegar do token JWT
            nome_atividade: form.nome_atividade.value,
            quantidade: form.quantidade.value,
            data_atividade: form.data_atividade.value
        };

        const resposta = await fetch("/HACKATON/dashboard", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                //"Authorization": "Bearer SEU_TOKEN_AQUI"
            },
            body: JSON.stringify(dados)
        });

        const json = await resposta.json();
        alert(json.mensagem);

        if (json.status) {
            modalOverlay.classList.remove("active");
            form.reset();
        }

    } catch (err) {
        console.error("Erro ao cadastrar:", err);
    }
}


async function carregarCarbonoStats() {
    try {
        const resposta = await fetch("/HACKATON/dashboard/relatorio/dashboards");
        const json = await resposta.json();

        if (json.status) {
            document.getElementById("carbonoTotal").innerText = json.dados.total + " kg";
            document.getElementById("carbonoMes").innerText = json.dados.mes + " kg";
            console.log(json.dados)
             document.getElementById("qtdQuiz").innerText = json.dados.quiz_acertos_mes + " pts";
        document.getElementById("donation").innerText = "R$" + json.dados.total_doado_mes;
        }
    } catch (err) {
        console.error("Erro ao carregar stats de carbono:", err);
    }
}


// chama ao carregar a página
document.addEventListener("DOMContentLoaded", carregarCarbonoStats);