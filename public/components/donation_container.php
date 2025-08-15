<!-- Seção de Doação -->
<section class="donation-section">
    <div class="donation-banner">
        <div class="donation-content">
            <div class="donation-title-block">
                <i class="ri-hand-heart-fill"></i> Faça uma Doação
            </div>
            <div class="donation-desc-block">
                Sua contribuição faz a diferença na preservação do nosso planeta. Escolha uma das organizações e doe para apoiar projetos de sustentabilidade e conservação ambiental.
            </div>
            <form id="donationForm" class="donation-form">
                <div class="form-row">
                    <div class="form-group label-valores">
                        <label>Valor:</label>
                        <div class="donation-values">
                            <label class="donation-value">
                                <input type="radio" name="amount" value="5">
                                R$ 5
                            </label>
                            <label class="donation-value">
                                <input type="radio" name="amount" value="10">
                                R$ 10
                            </label>
                            <label class="donation-value">
                                <input type="radio" name="amount" value="20">
                                R$ 20
                            </label>
                            <label class="donation-value">
                                <input type="radio" name="amount" value="50">
                                R$ 50
                            </label>
                            <label class="donation-value">
                                <input type="radio" name="amount" value="100">
                                R$ 100
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="orgSelect">Organização:</label>
                        <select id="orgSelect" name="organization" class="form-control" required>
                            <option value="" disabled selected>Selecione...</option>
                            <option value="greenpeace">Greenpeace</option>
                            <option value="wwf">WWF Brasil</option>
                            <option value="sos_mata_atlantica">SOS Mata Atlântica</option>
                            <option value="instituto_terra">Instituto Terra</option>
                            <option value="projeto_tamar">Projeto Tamar</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group custom-amount-group">
                        <label for="customAmount" class="custom-amount-label">Ou digite outro valor:</label>
                        <input type="number" min="1" step="1" id="customAmount" name="customAmount" class="custom-amount" placeholder="R$ Valor personalizado">
                    </div>
                    <div class="form-group donate-button-group">
                            <button type="button" class="btn btn-success" id="openPixModal">
                                <i class="ri-gift-fill"></i> Doar Agora
                            </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="donation-visual">
            <div class="donation-icon-grid">
                <div class="donation-icon">
                    <i class="ri-plant-line"></i>
                </div>
                <div class="donation-icon">
                    <i class="ri-earth-line"></i>
                </div>
                <div class="donation-icon">
                    <i class="ri-leaf-line"></i>
                </div>
                <div class="donation-icon">
                    <i class="ri-recycle-line"></i>
                </div>
            </div>
            <div class="donation-inspire">
                Transforme o futuro do nosso planeta com sua generosidade. Cada doação é uma semente de esperança!
            </div>
        </div>
    </div>
        <!-- Modal QR Code Pix -->
        <div id="pixModal" class="pix-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:9999;">
            <div style="background:#fff; padding:32px 24px; border-radius:12px; box-shadow:0 2px 16px rgba(0,0,0,0.2); position:relative; min-width:320px; max-width:90vw; text-align:center;">
                <span id="closePixModal" style="position:absolute; top:12px; right:16px; font-size:24px; cursor:pointer;">&times;</span>
                <h2 style="margin-bottom:16px;">Escaneie o QR Code Pix</h2>
                <img id="pixQrCodeImg" src="" alt="QR Code Pix" style="width:220px; height:220px; margin-bottom:16px;" />
                <p style="margin-bottom:8px;">Use o app do seu banco para escanear e doar.</p>
                <div id="pixKeyBlock" style="margin-bottom:8px; font-size:16px; color:#333;"></div>
            </div>
        </div>
</section>