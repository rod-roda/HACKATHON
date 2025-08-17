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
                        <button type="submit" class="btn btn-success">
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
</section>