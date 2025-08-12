<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
    <style>
.container-new{ 
            background-color:  #e9e3e3;
                padding: 70px;
    border-radius: 8px;


}

        .container-new { 
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-top: 20px;
        }
        
        /* Estilização dos filtros */
        .filtros-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }
        
        .filtros-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .filtros-header h2 {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .filtro-group {
            margin-bottom: 0;
        }
        
        .filtro-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #34495e;
            font-size: 0.95rem;
        }
        
        .filtro-group select,
        .filtro-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            background-color: #f8fafc;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .filtro-group select:focus,
        .filtro-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            background-color: #fff;
        }
        
        .filtro-group select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
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
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .filtro-btn {
    display: flex;
    align-items: flex-end;
}
       /* .stat-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        */


    </style>
</head>
<body>
    <?php include __DIR__ . '/../public/components/header.php'; ?>
    <script src="../js/dashboard.js"></script>

    <!-- Filtros acima dos gráficos -->
 <div class="filtros-container">
    <div class="filtros-header">
        <h2><i class="bi bi-funnel"></i> Filtros de Pesquisa</h2>
    </div>

    <div class="filtros-grid">
        <div class="filtro-group">
            <label for="filtro-mes"><i class="bi bi-calendar"></i> Filtrar por mês</label>
            <select id="filtro-mes">
                <option>Selecione um mês</option>
                <option>Janeiro</option>
                <option>Fevereiro</option>
                <option>Março</option>
                <option>Abril</option>
                <option>Maio</option>
                <option>Junho</option>
                <option>Julho</option>
                <option>Agosto</option>
                <option>Setembro</option>
                <option>Outubro</option>
                <option>Novembro</option>
                <option>Dezembro</option>
            </select>
        </div>

        <div class="filtro-group">
            <label for="filtro-turma"><i class="bi bi-people"></i> Filtrar por turma</label>
            <select id="filtro-turma">
                <option>Selecione uma turma</option>
                <option>1º Ano</option>
                <option>2º Ano</option>
                <option>3º Ano</option>
                <option>4º Ano</option>
                <option>5º Ano</option>
            </select>
        </div>

        <div class="filtro-group">
            <label for="filtro-professor"><i class="bi bi-person"></i> Filtrar por professor</label>
            <select id="filtro-professor">
                <option>Selecione um professor</option>
                <option>Ana Silva</option>
                <option>Carlos Mendes</option>
                <option>Fernanda Oliveira</option>
                <option>Ricardo Santos</option>
            </select>
        </div>

        <div class="filtro-group">
            <label for="busca-aluno"><i class="bi bi-search"></i> Buscar por aluno</label>
            <input type="text" id="busca-aluno" placeholder="Digite o nome do aluno...">
        </div>

        <div class="filtro-group">
            <label for="filtro-status"><i class="bi bi-list-check"></i> Status da ocorrência</label>
            <select id="filtro-status">
                <option>Todos os status</option>
                <option>Pendente</option>
                <option>Resolvido</option>
                <option>Em análise</option>
            </select>
        </div>

        <div class="filtro-group">
            <label for="filtro-periodo"><i class="bi bi-clock"></i> Período</label>
            <select id="filtro-periodo">
                <option>Todo o período</option>
                <option>Últimos 7 dias</option>
                <option>Últimos 30 dias</option>
                <option>Últimos 90 dias</option>
            </select>
        </div>

        <!-- Botão no grid -->
        <div class="filtro-group filtro-btn">
            <label>&nbsp;</label>
            <button class="btn-filtrar">
                <i class="bi bi-search"></i> Aplicar Filtros
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
                                <i class="bi bi-clipboard"></i>
                            </div>
                            <div>
                                <p class="stat-title">Ocorrências Totais</p>
                                <h3 class="stat-value">142</h3>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <i class="bi bi-arrow-up"></i> 12% em relação ao mês anterior
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon icon-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div>
                                <p class="stat-title">Ocorrências Pendentes</p>
                                <h3 class="stat-value">18</h3>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <i class="bi bi-arrow-down text-success"></i> 3 resolvidas hoje
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon icon-danger">
                                <i class="bi bi-person-x"></i>
                            </div>
                            <div>
                                <p class="stat-title">Alunos em Alerta</p>
                                <h3 class="stat-value">7</h3>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <i class="bi bi-info-circle"></i> 2 novos casos esta semana
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon icon-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div>
                                <p class="stat-title">Resolvidas (30 dias)</p>
                                <h3 class="stat-value">89%</h3>
                            </div>
                        </div>
                        <div class="stat-footer">
                            <i class="bi bi-arrow-up"></i> 5% acima da meta
                        </div>
                    </div>
                </div>
                
 <div class="caixas">
    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">📊 Tipos Ocorrências mais frequentes</h3>
        <div class="grafico-frame">
            <iframe id="iframe-tipo-ocorrencia"
                    src="graficos/graficoVertical.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">📈 Ocorrências por Mês</h3>
        <div class="grafico-frame">
            <iframe id="iframe-ocorrencias-mes"
                    src="graficos/graficoPizza.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">🚨 Alunos em Alerta</h3>
        <div class="grafico-frame">
            <iframe id="iframe-alunos-alerta"
                    src="graficos/graficoVertical.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">🚨 Turmas mais problemáticas</h3>
        <div class="grafico-frame">
            <iframe id="iframe-turmas-problematicas"
                    src="graficos/graficoPizza.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo">👨‍🏫 Professor que mais gerou ocorrências</h3>
        <div class="grafico-frame">
            <iframe id="iframe-professores-ocorrencias"
                    src="graficos/graficoVertical.html"></iframe>
        </div>
    </div>
</div>

        </div>
</body>
</html>
