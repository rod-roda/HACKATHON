<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doações | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
    <style>
        body {
            background: #101820;
        }
        .info-section {
            width: 100vw;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .ong-banner {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
            width: 100vw;
            padding: 0 5vw;
            box-sizing: border-box;
        }
        .ong-banner.reverse {
            flex-direction: row-reverse;
        }
        .ong-banner.greenpeace {
            background: linear-gradient(90deg, #0f2e13 60%, #39ff14 100%);
        }
        .ong-banner.wwf {
            background: linear-gradient(90deg, #0a1a2f 60%, #e0e0e0 100%);
        }
        .ong-banner.sosma {
            background: linear-gradient(90deg, #174a3c 60%, #00e1ff 100%);
        }
        .ong-banner.terra {
            background: linear-gradient(90deg, #2e2412 60%, #8bc34a 100%);
        }
        .ong-img-rect {
            width: 800px;
            height: 420px;
            object-fit: cover;
            border-radius: 1.2rem;
            border: 4px solid #fff;
            background: #fff;
            box-shadow: 0 0 24px #fff;
            margin: 0 3vw;
            transition: width 0.2s, height 0.2s;
        }
        .ong-info-block {
            max-width: 700px;
            padding: 2rem 1vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .ong-title-block {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1.2rem;
            text-shadow: 0 0 12px #fff;
            color: #fff;
        }
        .ong-banner.greenpeace .ong-title-block {
            color: #39ff14;
            text-shadow: 0 0 12px #39ff14;
        }
        .ong-banner.wwf .ong-title-block {
            color: #000;
            text-shadow: 0 0 12px #e0e0e0;
        }
        .ong-banner.wwf .ong-desc-block {
            color: #f5f5f5;
            text-shadow: 0 0 12px #e0e0e0;
        }
        .ong-banner.wwf .ong-inspire {
            color: #000;
            text-shadow: 0 0 8px #e0e0e0;
        }
        .ong-banner.sosma .ong-title-block {
            color: #00e1ff;
            text-shadow: 0 0 12px #00e1ff;
        }
        .ong-banner.terra .ong-title-block {
            color: #8bc34a;
            text-shadow: 0 0 12px #8bc34a;
        }
        .ong-desc-block {
            font-size: 2rem;
            color: #f5f5f5;
            line-height: 1.5;
            margin-bottom: 1.2rem;
        }
        .ong-banner.greenpeace .ong-desc-block {
            color: #caffb7;
        }
        .ong-banner.wwf .ong-desc-block,
        .ong-banner.wwf .ong-inspire {
            color: #f5f5f5 !important;
        }
        .ong-banner.sosma .ong-desc-block {
            color: #e3f7ff;
        }
        .ong-banner.terra .ong-desc-block {
            color: #eaffd3;
        }
        .ong-inspire {
            font-size: 1.5rem;
            font-style: italic;
            font-weight: 600;
            color: #fff;
            margin-top: 0.5rem;
            text-shadow: 0 0 8px #000;
        }
        .ong-banner.greenpeace .ong-inspire {
            color: #39ff14;
        }
        .ong-banner.wwf .ong-inspire {
            color: #f5f5f5;
        }
        .ong-banner.sosma .ong-inspire {
            color: #00e1ff;
        }
        .ong-banner.terra .ong-inspire {
            color: #8bc34a;
        }
        /* Formulário mantém verde neon e fica horizontal como um infoot */
        .donation-footer {
            width: 100vw;
            background: #181f2a;
            box-shadow: 0 0 24px #39ff14, 0 0 4px #39ff14 inset;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0 2.5rem 0;
        }
        .donation-wrapper {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: stretch;
            gap: 2rem;
            padding: 0 2rem;
        }
        .donation-title {
            color: #39ff14;
            text-shadow: 0 0 8px #39ff14;
            font-size: 2.2rem;
            font-weight: bold;
            margin-right: 2rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            flex: 1 1 220px;
        }
        .donation-form {
            display: flex;
            flex: 4 1 700px;
            align-items: stretch;
            gap: 2rem;
            width: 100%;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-bottom: 0;
            flex: 1 1 180px;
        }
        .form-group.label-valores {
            justify-content: flex-start;
            flex: 1.2 1 220px;
        }
        .form-group label {
            color: #39ff14;
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
        .donation-values {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            flex-wrap: nowrap;
            margin-bottom: 0;
        }
        .donation-value {
            display: flex;
            align-items: center;
            flex-direction: row;
            gap: 0.5rem;
            background: #101820;
            border: 2px solid #39ff14;
            color: #39ff14;
            border-radius: 0.5rem;
            padding: 0.3rem 1rem;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
            font-size: 1rem;
            height: 48px;
        }
        .donation-value input[type="radio"] {
            accent-color: #39ff14;
            margin-right: 0.5em;
            margin-bottom: 0;
        }
        .form-control, .custom-amount {
            width: 100%;
            min-width: 120px;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #39ff14;
            background: #101820;
            color: #fff;
            margin-bottom: 0.2rem;
            height: 48px;
            font-size: 1rem;
        }
        .btn-success {
            background: linear-gradient(90deg, #39ff14 60%, #1aff66 100%);
            color: #101820;
            font-weight: bold;
            border: none;
            border-radius: 0.5rem;
            padding: 0.6rem 1.5rem;
            box-shadow: 0 0 8px #39ff14;
            transition: background 0.2s;
            font-size: 1.1rem;
            margin-left: 1rem;
            align-self: center;
            height: 48px;
            display: flex;
            align-items: center;
        }
        .custom-amount-label {
            color: #39ff14;
            margin-top: 0.5rem;
            display: block;
            font-weight: 500;
            font-size: 1rem;
        }
        @media (max-width: 1200px) {
            .donation-wrapper {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1rem 0.5rem;
                align-items: stretch;
            }
            .donation-title {
                margin-bottom: 1rem;
                margin-right: 0;
                font-size: 1.5rem;
                justify-content: flex-start;
            }
            .donation-form {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }
            .form-group, .form-group.label-valores {
                min-width: unset;
                width: 100%;
                flex: unset;
            }
            .btn-success {
                margin-left: 0;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <!-- Área informativa das ONGs -->
    <section class="info-section">
        <div class="ong-banner greenpeace">
            <img src="../image/Greenpeace.webp" alt="Greenpeace" class="ong-img-rect">
            <div class="ong-info-block">
                <div class="ong-title-block">Greenpeace</div>
                <div class="ong-desc-block">
                    ONG internacional dedicada à proteção do meio ambiente, atuando em campanhas contra desmatamento, poluição e mudanças climáticas.
                </div>
                <div class="ong-inspire">
                    "Sua doação é uma semente para um planeta mais verde. Junte-se a nós e faça a diferença!"
                </div>
            </div>
        </div>
        <div class="ong-banner wwf reverse">
            <img src="../image/wwfBrasil.png" alt="WWF Brasil" class="ong-img-rect">
            <div class="ong-info-block">
                <div class="ong-title-block">WWF Brasil</div>
                <div class="ong-desc-block">
                    Trabalha pela conservação da natureza e biodiversidade, promovendo projetos de proteção de espécies e ecossistemas brasileiros.
                </div>
                <div class="ong-inspire">
                    "Preserve hoje para existir amanhã. Doe e ajude a proteger a vida em todas as formas!"
                </div>
            </div>
        </div>
        <div class="ong-banner sosma">
            <img src="../image/SosMataAtlantica.jpg" alt="SOS Mata Atlântica" class="ong-img-rect">
            <div class="ong-info-block">
                <div class="ong-title-block">SOS Mata Atlântica</div>
                <div class="ong-desc-block">
                    Focada na preservação da Mata Atlântica, realiza ações de reflorestamento, educação ambiental e defesa de políticas públicas.
                </div>
                <div class="ong-inspire">
                    "Cada árvore plantada é esperança renovada. Doe e ajude a restaurar a Mata Atlântica!"
                </div>
            </div>
        </div>
        <div class="ong-banner terra reverse">
            <img src="../image/intitutoTerra.webp" alt="Instituto Terra" class="ong-img-rect">
            <div class="ong-info-block">
                <div class="ong-title-block">Instituto Terra</div>
                <div class="ong-desc-block">
                    Instituição voltada à recuperação ambiental do Vale do Rio Doce, promovendo reflorestamento e educação ecológica.
                </div>
                <div class="ong-inspire">
                    "Transforme vidas e paisagens. Sua doação ajuda a reconstruir o futuro do nosso planeta!"
                </div>
            </div>
        </div>
    </section>

    <!-- Formulário de doação como infoot -->
    <footer class="donation-footer">
        <div class="donation-wrapper">
            <div class="donation-title">
                <i class="ri-hand-heart-fill"></i> Faça uma Doação
            </div>
            <form id="donationForm" class="donation-form">
                <div class="form-group label-valores">
                    <label>Valor:</label>
                    <div class="donation-values">
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
                    </select>
                </div>
                <div class="form-group">
                    <label for="customAmount" class="custom-amount-label">Ou digite outro valor:</label>
                    <input type="number" min="1" step="1" id="customAmount" name="customAmount" class="custom-amount" placeholder="R$ Valor personalizado">
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="ri-gift-fill"></i> Doar
                </button>
            </form>
        </div>
    </footer>
    <script>
        // Se o usuário digitar valor personalizado, desmarca os radios
        document.getElementById('customAmount').addEventListener('input', function() {
            if (this.value.length > 0) {
                document.querySelectorAll('input[name="amount"]').forEach(radio => radio.checked = false);
            }
        });
        // Se o usuário clicar em radio, limpa campo personalizado
        document.querySelectorAll('input[name="amount"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('customAmount').value = '';
            });
        });
    </script>
</body>
</html>