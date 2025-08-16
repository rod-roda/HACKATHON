<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
    <style>
     * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --bg-color: #222327;
            --text-color: #fff;
            --main-color: #29fd53;
            --header-bg: rgba(34, 35, 39, 0.95);
            --secondary: #222327;
            --secondary-light: #2a2b30;
            --secondary-dark: #1a1a1f;
            --primary: #29fd53;
            --primary-light: #5dff80;
            --primary-dark: #1acb40;
            --primary-darker: #0e9a2d;
            --radius-lg: 0.5rem;
            --radius-xl: 0.75rem;
            --font-base: 1rem;
            --font-lg: 1.125rem;
            --font-xl: 1.25rem;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-color) 0%, #1a1a1f 100%);
            color: var(--text-color);
            padding-top: 100px;
            font-size: var(--font-base);
            line-height: 1.5;
        }
        
        .container-new {
            background: var(--secondary);
            padding: 40px;
            border-radius: 12px;
            margin: 20px auto;
        }
        
        /* Botão para abrir o modal */
        .btn-modal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: var(--primary);
            color: #222;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 15px 0;
        }
        
        .btn-modal:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(41, 253, 83, 0.3);
        }
        
        .btn-modal i {
            margin-right: 8px;
        }
        
        /* Estilo do Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background: var(--secondary-light);
            border-radius: var(--radius-xl);
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 20px;
            background: var(--primary-darker);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            color: white;
            font-size: var(--font-xl);
            font-weight: 600;
        }
        
        .close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .close-btn:hover {
            transform: rotate(90deg);
            color: var(--primary-light);
        }
        
        .modal-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-light);
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 15px;
            background: var(--secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            color: var(--text-color);
            font-size: var(--font-base);
            transition: all 0.3s ease;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(41, 253, 83, 0.2);
        }
        
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2329fd53' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }
        
        .modal-footer {
            padding: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-cancel {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary);
        }
        
        .btn-save {
            background: var(--primary);
            color: var(--secondary-dark);
            font-weight: 600;
        }
        
        .btn-save:hover {
            background: var(--primary-dark);
            box-shadow: 0 0 15px rgba(41, 253, 83, 0.3);
            transform: translateY(-2px);
        }
        
        /* Dashboard stats para contexto */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--secondary-light);
            border-radius: var(--radius-xl);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
                .stat-card {
            background:#201f1f;
            border-radius: 12px;
            color: #fff;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 22px;
        }
        
        .icon-primary {
            background: rgba(41, 253, 83, 0.1);
            color: var(--primary);
        }
        
        .stat-title {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin: 0;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }
        
        .stat-footer {
            margin-top: 10px;
            font-size: 0.85rem;
            color: var(--primary);
            display: flex;
            align-items: center;
        }


             .btn-filtrar {
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-filtrar:hover {
            background-color:#0e9a2d;
    
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>

    <!-- Filtros acima dos gráficos -->
 <div class="filtros-container">
    <div class="filtros-header">
        <h2><i class="bi bi-funnel"></i> Dashboard Eco System</h2>

    </div>

    <div class="filtros-grid">
   

        <!-- Botão no grid -->

        <div class="filtro-group filtro-btn">
    <label>&nbsp;</label>
<button id="btn-filtrar" class="btn-filtrar">
        <i class="bi bi-plus-circle"></i> Registrar Atividade Ecológica
    </button>
</div>



    </div>
</div>


        <div class="container-new">

    <!-- Gráficos -->
               <!-- Dashboard Stats -->
   <div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon icon-primary">
                <i class="bi bi-bicycle"></i>
            </div>
            <div>
                <p class="stat-title">Viagens Sustentáveis (mês)</p>
                <h3 class="stat-value">1.245</h3>
            </div>
        </div>
        <div class="stat-footer">
            <i class="bi bi-arrow-up"></i> 15% mais que o mês anterior
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon icon-warning">
                <i class="bi bi-cloud-fog"></i>
            </div>
            <div>
                <p class="stat-title">Emissões de CO₂ (ton)</p>
                <h3 class="stat-value">325</h3>
            </div>
        </div>
        <div class="stat-footer">
            <i class="bi bi-arrow-down text-success"></i> Redução de 8% este mês
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon icon-danger">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <div>
                <p class="stat-title">Energia Renovável (%)</p>
                <h3 class="stat-value">62%</h3>
            </div>
        </div>
        <div class="stat-footer">
            <i class="bi bi-arrow-up"></i> Meta de 70% até final do ano
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon icon-success">
                <i class="bi bi-tree"></i>
            </div>
            <div>
                <p class="stat-title">Árvores Plantadas</p>
                <h3 class="stat-value">4.530</h3>
            </div>
        </div>
        <div class="stat-footer">
            <i class="bi bi-arrow-up"></i> +320 este mês
        </div>
    </div>
</div>

                
<div class="caixas">
    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">🚴 Tipos de Transporte Utilizados</h3>
        <div class="grafico-frame">
            <iframe src="graficos/transporteVertical.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">🌎 Emissões de CO₂ por Mês</h3>
        <div class="grafico-frame">
            <iframe src="graficos/emissoesPizza.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">⚡ Fontes de Energia Utilizadas</h3>
        <div class="grafico-frame">
            <iframe src="graficos/energiaVertical.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">🌱 Áreas Reflorestadas</h3>
        <div class="grafico-frame">
            <iframe src="graficos/reflorestamentoPizza.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">🏢 Empresas que Adotaram Medidas Climáticas</h3>
        <div class="grafico-frame">
            <iframe src="graficos/empresasVertical.html"></iframe>
        </div>
    </div>
</div>

   <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-header">
            <h2><i class="bi bi-tree"></i> Cadastrar Atividade</h2>
            <button class="close-btn" id="closeModal"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <form id="formAtividade">
                <div class="form-group">
                    <label for="nome_atividade"><i class="bi bi-tag"></i> Nome da Atividade</label>
                    <input type="text" id="nome_atividade" name="nome_atividade" required>
                </div>
                <div class="form-group">
                    <label for="quantidade"><i class="bi bi-123"></i> Quantidade</label>
                    <input type="number" id="quantidade" name="quantidade" required>
                </div>
        
                <div class="form-group">
                    <label for="data_atividade"><i class="bi bi-calendar"></i> Data da Atividade</label>
                    <input type="date" id="data_atividade" name="data_atividade" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-btn btn-cancel" id="cancelModal">Cancelar</button>
            <button class="modal-btn btn-save" id="btnSalvar"><i class="bi bi-check-circle"></i> Salvar</button>
        </div>
    </div>
</div>


        
        </div>


    <script src="../js/dashboard.js"></script>


</body>
</html>
