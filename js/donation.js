function generateQRCode(text, size = 256) {
    // Usar API externa para gerar QR Code
    const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${encodeURIComponent(text)}`;
    return qrApiUrl;
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.location.pathname.includes('donation')) {
        initializeDonation();
    }
});

function initializeDonation() {
    const pixModal = document.getElementById('pixModal');
    const openPixModalBtn = document.getElementById('openPixModal');
    const closePixModalBtn = document.getElementById('closePixModal');
    const pixQrCodeImg = document.getElementById('pixQrCodeImg');
    const pixKeyBlock = document.getElementById('pixKeyBlock');

    if (openPixModalBtn) {
        openPixModalBtn.addEventListener('click', function() {

            const valor = getDonationValue();
            const ong = document.getElementById('orgSelect');

            if(!ong.value){
                showNotification('Por favor, escolha a ONG que receberá sua doação!', 'warning');
                return;
            }

            if(valor <= 0){
                showNotification('Por favor, insira o valor de sua doação!', 'warning');
                return;
            }

            fetchPost('/HACKATHON/pix/gerarCodigo', { valor: valor})
            .then(res => {
                const pixKey = res.pixCopiaECola;
                const qrApiUrl = generateQRCode(pixKey);

                pixModal.style.display = 'flex';
                pixKeyBlock.textContent = 'Chave Pix: ' + pixKey;
                pixQrCodeImg.src = qrApiUrl;
            });

        });
    }

    if (closePixModalBtn) {
        closePixModalBtn.addEventListener('click', function() {
            pixModal.style.display = 'none';
        });
    }

    if (pixModal) {
        pixModal.addEventListener('click', function(e) {
            if (e.target === pixModal) pixModal.style.display = 'none';
        });
    }
}

function getDonationValue() {
    const customAmountInput = document.getElementById('customAmount');
    const selectedRadio = document.querySelector('input[name="amount"]:checked');

    let value = 0;

    if (customAmountInput.value && Number(customAmountInput.value) > 0) {
        value = Number(customAmountInput.value);
    } else if (selectedRadio) {
        value = Number(selectedRadio.value);
    }

    return value;
}