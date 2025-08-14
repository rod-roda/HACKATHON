document.addEventListener("DOMContentLoaded", function () {
    const tokenField = document.getElementById("pixToken");

    fetch(`${window.location.origin}/HACKATHON/pix/token`)
        .then(response => response.json())
        .then(data => {
            if (data.status && data.access_token) {
                console.log("Access Token recebido:", data.access_token);
                if (tokenField) {
                    tokenField.value = data.access_token;
                }
            } else {
                console.error("Erro ao obter token PIX:", data.mensagem || data);
            }
        })
        .catch(err => {
            console.error("Erro na requisição do token PIX:", err);
        });
});
