<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Eco System</title>
    <?php include __DIR__ . '/../public/components/links.php'; ?>
    <style>
        .graficoOcorrencias {
            border-radius: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            height: 600px;

        }

        .grafico-titulo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #003366;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .grafico-frame {
                height: 400px;
        }

        .grafico-frame iframe {
            
            width: 100%;
            border: none;
            display: block;
        }
        
        .graficoOcorrencias h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #00488B;
            border-left: 5px solid #00488B;
            padding-left: 10px;
        }
        :root {
            --primary: #00488B;
            --secondary: #0d6efd;
            --success: #198754;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
        }

        .body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #333;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .header {
            width: 250px;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .logo-menu {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .logo-menu img {
            max-width: 120px;
        }

        .nav ul {
            list-style: none;
            padding: 0;
        }

        .nav ul li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .nav ul li a:hover,
        .nav ul li a.active {
            background: #f0f7ff;
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        .nav ul li a i {
            margin-right: 12px;
            font-size: 18px;
        }

        .container {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .boas-vindas {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            font-size: 1.2rem;
            color: var(--primary);
            font-weight: 600;
            border-left: 4px solid var(--primary);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
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
        }

        .stat-header {
            color: white;
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
            background: rgba(0, 72, 139, 0.1);
            color: var(--primary);
        }

        .icon-warning {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .icon-danger {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .icon-success {
            background: rgba(25, 135, 84, 0.1);
            color: var(--success);
        }

        .stat-title {
            font-size: 0.9rem;
            color: #ffffff;
            margin: 0;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--white);
        }

        .stat-footer {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #28a745;
            display: flex;
            align-items: center;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        @media (max-width: 992px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .box-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .box-header h3 {
            margin: 0;
            font-size: 1.3rem;
            color: var(--primary);
            font-weight: 600;
        }

        .box-header i {
            margin-right: 12px;
            font-size: 1.4rem;
        }

        .ocorrencia-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
            align-items: center;
        }

        .ocorrencia-item:last-child {
            border-bottom: none;
        }

        .ocorrencia-info {
            flex: 1;
        }

        .ocorrencia-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .ocorrencia-meta {
            display: flex;
            font-size: 0.85rem;
            color: #777;
        }

        .ocorrencia-meta span {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .ocorrencia-meta i {
            margin-right: 5px;
        }

        .ocorrencia-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pendente {
            background: #fff3cd;
            color: #856404;
        }

        .status-analise {
            background: #cce5ff;
            color: #004085;
        }

        .aluno-item {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
            align-items: center;
        }

        .aluno-item:last-child {
            border-bottom: none;
        }

        .aluno-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: 600;
            color: var(--primary);
        }

        .aluno-info {
            flex: 1;
        }

        .aluno-name {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .aluno-meta {
            font-size: 0.85rem;
            color: #777;
        }

        .aluno-ocorrencias {
            background: var(--danger);
            color: white;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .ver-todos {
            display: inline-block;
            margin-top: 15px;
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .ver-todos:hover {
            text-decoration: underline;
        }

        .botaochat {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 72, 139, 0.3);
            cursor: pointer;
            z-index: 99;
            transition: all 0.3s;
        }

        .botaochat:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 72, 139, 0.4);
        }

        /* Menu mobile */
        .menu-mobile {
            display: none;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            
            header {
                width: 100%;
            }
            
            .menu-desktop {
                display: none;
            }
            
            .menu-mobile {
                display: block;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        *{
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

        .grafico-titulo {
            color: #29fd53;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        .grafico-titulo i {
            margin-right: 8px;
        }

        .graficoOcorrencias {
            background: #201f1f;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .graficoOcorrencias:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .grafico-frame {
            width: 100%;
            height: 500px;
            overflow: hidden;
            border-radius: 8px;
        }

        .grafico-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
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

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            max-width: 300px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            background-color: #333;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .notification-success { background-color: #28a745 !important; }
        .notification-error { background-color: #dc3545 !important; }
        .notification-warning { background-color: #ffc107 !important; color: #333; }

        .notification-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .notification-message { flex-grow: 1; }

        .notification-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .notification-close:hover { opacity: 1; }
        .notification.hide { animation: slideOut 0.3s ease-out forwards; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        .caixas {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 gráficos por linha */
    gap: 20px; /* espaço entre eles */
    margin: 20px;

}

.graficoOcorrencias {
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
}

.grafico-titulo {
    font-size: 1.1rem;
    margin-bottom: 10px;
    font-weight: bold;
}

.grafico-frame {
    height: 600px;
    display: block;
    align-items: center;
}

.grafico-frame iframe {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 8px;
}

/* Responsivo: quando a tela for menor, coloca 1 por linha */
@media (max-width: 768px) {
    .caixas {
        grid-template-columns: 1fr;
    }
}
.filtros {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.filtros select,
.filtros input {
    padding: 10px 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    min-width: 150px;
    background: #f9f9f9;
    font-size: 14px;
    transition: 0.3s ease;
}

.filtros select:focus,
.filtros input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.5);
    outline: none;
}

/* Botão filtrar (caso adicione no HTML) */
.filtros button {
    padding: 10px 20px;
    border: none;
    background: #007bff;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s ease;
}





.container-new{ 
            background-color:  #e9e3e3;
                padding: 70px;
    border-radius: 8px;


}

        .container-new { 
            
    background:  #222327;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-top: 20px;
        }
        
        /* Estilização dos filtros */
        .filtros-container {
            background:  #222327;
            color:whit;
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
            color: #f6f8faff;
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
            color: #f6f8faff;
            font-size: 0.95rem;
        }
        
        .filtro-group select,
        .filtro-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            background-color:      #222327;
            color : #fff;
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
                
        .filtros button:hover {
            background: #0056b3;
        }

        /* Ícones de filtro */
        .campoFiltro {
            position: relative;
            flex: 1;
            min-width: 180px;
        }

        .campoFiltro i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .filtros {
                flex-direction: column;
            }
        }

    </style>
</head>
<body>
    <script>
        if(!(localStorage.getItem('token')))  window.location.href = "logar.php"; //TODO -- DEIXAR ISSO MAIS SEGURO DPS COM O SERVERSIDE DO PHP
    </script>

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
        <div class="stat-icon icon-warning">
            <i class="bi bi-cloud"></i>
        </div>
        <div>
            <p class="stat-title">Carbono Emitido (total)</p>
            <h3 id="carbonoTotal" class="stat-value">0 kg</h3>
        </div>
    </div>
    <div class="stat-footer">
        <i class="bi bi-arrow-up"></i> <span id="carbonoVariacao">0%</span> em relação ao mês anterior
    </div>
</div>

<div class="stat-card">
    <div class="stat-header">
        <div class="stat-icon icon-success">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <p class="stat-title">Carbono Emitido (este mês)</p>
            <h3 id="carbonoMes" class="stat-value">0 kg</h3>
        </div>
    </div>
    <div class="stat-footer">
        <i class="bi bi-calendar-check"></i> Registros em <span id="diasAtivosCarbonoMes">0</span> dias | 
        <span id="tendenciaMes" class="text-warning">+0%</span> tendência
    </div>
</div>

<div class="stat-card">
    <div class="stat-header">
        <div class="stat-icon icon-success">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <p class="stat-title">Acertos no quiz:</p>
            <h3 id="qtdQuiz" class="stat-value">13</h3>
        </div>
    </div>
    <div class="stat-footer">
        <i class="bi bi-star"></i> Em <span id="diasQuiz">0</span> dias | 
        <span id="evolucaoQuiz" class="text-success">+0%</span> evolução
    </div>
</div>


    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon icon-success">
                <i class="bi bi-tree"></i>
            </div>
            <div>
                <p class="stat-title">Total doado:</p>
                <h3 id="donation"  class="stat-value">R$203</h3>
            </div>
        </div>
        <div class="stat-footer">
            <i class="bi bi-heart"></i> Doações em <span id="diasDoacao">0</span> dias | 
            <span id="evolucaoDoacao" class="text-success">+0%</span> evolução
        </div>
    </div>
</div>

                
<div class="caixas">
   <div class="graficoOcorrencias">
    <h3 class="grafico-titulo"><i class="bi bi-globe"></i> Sua média em relação aos outros países</h3>
    <div class="grafico-frame">
        <iframe src="graficos/graficoComparacao.html"></iframe>
    </div>
</div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo"><i class="bi bi-cloud"></i> Emissões de CO₂ neste ano</h3>
        <div class="grafico-frame">
            <iframe src="graficos/graficoMesEmissao.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo"><i class="bi bi-activity"></i> Suas atividades</h3>
        <div class="grafico-frame">
            <iframe src="graficos/graficoTipoAtividade.html"></iframe>
        </div>
    </div>

    <div class="graficoOcorrencias">
        <h3 class="grafico-titulo"><i class="bi bi-heart"></i> Top doadores</h3>
        <div class="grafico-frame">
            <iframe src="graficos/graficoDoadores.html"></iframe>
        </div>
    </div>
 <div class="graficoOcorrencias">
        <h3 class="grafico-titulo"><i class="bi bi-star"></i> Top quiz</h3>
        <div class="grafico-frame">
            <iframe src="graficos/graficoTopQuiz.html"></iframe>
        </div>
    </div>

     <div class="graficoOcorrencias">
        <h3 class="grafico-titulo"><i class="bi bi-controller"></i> Top games</h3>
        <div class="grafico-frame">
            <iframe src="graficos/graficoTopGames.html"></iframe>
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
                    <label for="nome_atividade"><i class="bi bi-tag"></i> Tipo de Atividade</label>
                    <select id="nome_atividade" name="nome_atividade" required>
                        <option value="">Selecione uma atividade</option>
                        <option value="carro">Uso de Carro (km)</option>
                        <option value="energia">Consumo de Energia (kWh)</option>
                        <option value="aviao">Viagem de Avião (km)</option>
                        <option value="carne">Consumo de Carne (kg)</option>
                        <option value="gas">Consumo de Gás (m³)</option>
                        <option value="onibus">Uso de Ônibus (km)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantidade"><i class="bi bi-123"></i> Distância </label>
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


    <script src="../js/functions.js"></script>
    <script src="../js/dashboard.js"></script>

    <style>
        /* Notificações */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            max-width: 300px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }

        .notification-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification-message {
            margin-right: 10px;
            color: #fff;
        }

        .notification-close {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
        }

        .notification-success {
            background-color: #28a745;
        }

        .notification-error {
            background-color: #dc3545;
        }

        .notification-warning {
            background-color: #ffc107;
            color: #333;
        }

        .notification.hide {
            animation: slideOut 0.3s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>

</body>
</html>
