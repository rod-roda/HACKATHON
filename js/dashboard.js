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
