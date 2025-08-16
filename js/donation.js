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
    const copyPixBtn = document.getElementById('copyPixBtn');
    const copyFeedback = document.getElementById('copyFeedback');
    
    let currentPixKey = '';

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

            let token = localStorage.getItem('token')
            
            fetchPost('/HACKATHON/pix/gerarCodigo', { valor: valor}, token)
            .then(res => {
                if(res.status){
                    currentPixKey = res.pixCopiaECola; // Armazena a chave PIX
                    const qrApiUrl = generateQRCode(currentPixKey);
    
                    pixModal.style.display = 'flex';
                    pixKeyBlock.textContent = currentPixKey;
                    pixQrCodeImg.src = qrApiUrl;
                    
                    // Reset feedback message
                    copyFeedback.style.display = 'none';
                }else{
                    showNotification(`Erro: ${res.msg}`, 'warning');
                }
            });

        });
    }

    if (copyPixBtn) {
        copyPixBtn.addEventListener('click', function() {
            if (currentPixKey) {
                copyToClipboard(currentPixKey)
                    .then(() => {
                        // Mostra feedback de sucesso
                        copyFeedback.style.display = 'block';
                        copyPixBtn.innerHTML = '<i class="ri-check-line"></i> Copiado!';
                        copyPixBtn.style.background = '#28a745';
                        
                        setTimeout(() => {
                            copyFeedback.style.display = 'none';
                            copyPixBtn.innerHTML = '<i class="ri-file-copy-line"></i> Copiar Código PIX';
                            copyPixBtn.style.background = '#0e9a2d';
                        }, 2000);
                    })
                    .catch(() => {
                        showNotification('Erro ao copiar código PIX. Tente copiar manualmente.', 'error');
                    });
            }
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

async function copyToClipboard(text) {
    try {
        // Tenta usar a API moderna do clipboard
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(text);
            return Promise.resolve();
        } else {
            // Fallback para navegadores mais antigos
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            const successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            
            if (successful) {
                return Promise.resolve();
            } else {
                return Promise.reject();
            }
        }
    } catch (err) {
        return Promise.reject(err);
    }
}