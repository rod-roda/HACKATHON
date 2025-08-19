# 🌍 EcoSystem – Plataforma de Sustentabilidade Climática

> **Projeto desenvolvido para o Hackathon "Dados pelo Clima" (ODS 13)**
> Conscientização, educação, engajamento e ação climática em um único ecossistema digital.

---
## 📑 Sumário
1. [Visão Geral](#-visão-geral)
2. [Principais Módulos](#-principais-módulos)
3. [Arquitetura & Tecnologias](#-arquitetura--tecnologias)
4. [Instalação e Configuração](#-instalação-e-configuração)
5. [Estrutura de Pastas](#-estrutura-de-pastas)
6. [Fluxo de Autenticação (JWT)](#-fluxo-de-autenticação-jwt)
7. [Rotas Principais da API](#-rotas-principais-da-api)
8. [Banco de Dados](#-banco-de-dados)
9. [Segurança & Boas Práticas](#-segurança--boas-práticas)
10. [Roadmap / Próximos Passos](#-roadmap--próximos-passos)
11. [Licença](#-licença)
12. [Créditos & Contato](#-créditos--contato)

---
## 🧭 Visão Geral
O **EcoSystem** é uma plataforma integrada que entrega **monitoramento climático**, **quiz educativo (EcoQuiz)**, **gamificação ambiental (EcoGame)** e **doações para ONGs ambientais** – tudo sustentado por um backend em **PHP** e um frontend moderno com **JavaScript + CSS + HTML**.

O objetivo é apoiar a **ODS 13 (Ação Contra a Mudança Global do Clima)** por meio de ferramentas de engajamento que unem dados, educação e impacto real.

Este projeto é de fito **puramente educativo**, representando um **protótipo funcional** para a realização da proposta do **Hackathon**.

---
## 🚀 Principais Módulos
| Módulo | Descrição | Destaques |
|--------|-----------|-----------|
| Mapa | Mapa em camadas que aponta diversos fatores climáticos de todo o globo | MapTiler |
| Dashboard | Estatísticas de quiz, jogo, doações e comparação do carbono emitido | Consolidação de engajamento do usuário |
| Monitoramento Climático | Dashboard com busca por cidade e gráficos interativos | WeatherAPI, Chart.js, Glassmorphism |
| EcoQuiz | Quiz de sustentabilidade com explicações | 10 questões dinâmicas, score persistido, ranking futuro |
| EcoGame | Jogo estilo snake temático oceânico | Coleta de lixo virtual, gamificação ambiental |
| Doações | Sistema de apoio a ONGs ambientais via Pix | Integração EfiPay (Gerencianet), QR Code dinâmico |

---
## 🏗 Arquitetura & Tecnologias
### Backend
- PHP 8+ (estilo procedural + classes de modelo)
- Bramus Router (`modelo/Router.php`)
- MySQL / MariaDB (scripts em `docs/`)
- JWT personalizado (`modelo/MeuTokenJWT.php`)

### Frontend
- HTML5 semântico
- CSS3 + Design System (variáveis, glassmorphism, animações neon)
- JavaScript modular (`js/`): quiz, monitoração, doações, dashboard
- Chart.js para gráficos de Monitoramento (temperatura, vento)
- QRCode.js para geração de QR Pix
- Remix Icons / Bootstrap Icons

### Integrações Externas - API's Utilizadas
| Serviço | Uso | Link | 
|---------|-----|------|
| WeatherAPI | Dados climáticos por localização | https://openweathermap.org/api
| EfiPay Pix API | Geração de cobranças e QR Code Pix | https://sejaefi.com.br/lp/api-pix
| MapTiler (weather view) | Visualização de camadas meteorológicas dinâmicas | https://www.maptiler.com
| API Gemini Developer | Cálculo da pegada ecológica do usuário com I.A. | https://ai.google.dev/gemini-api/docs

---
## 🔧 Instalação e Configuração
### 1. Clonar o repositório
```bash
git clone https://github.com/rod-roda/HACKATHON.git
cd HACKATHON
```

### 2. Configurar ambiente
Requisitos:
- PHP 8+
- MySQL/MariaDB
- Extensões: `curl`, `openssl`, `pdo_mysql`
- Servidor (Apache recomendado – XAMPP ou similar)

### 3. Banco de Dados
Importe o script principal:
```bash
mysql -u root -p < docs/hackathon.sql
```
Configure credenciais no arquivo `modelo/Banco.php` (se existir placeholders).

### 4. WeatherAPI
Cadastre-se em: https://www.weatherapi.com/ e substitua a chave em `view/monitoring.php`:
```php
$apiKey = "SUA_CHAVE_AQUI";
```

### 5. Pix (EfiPay / Gerencianet)
- Coloque o certificado `.pem` em `certificados/`
- Ajuste `client_id`, `client_secret` e caminhos em `controle/controller_donations.php`

### 6. Permissões / Certificados
Garanta permissões de leitura nos certificados (Windows: ver propriedades > segurança).

### 7. Acessar
Abra no navegador:
```
http://localhost/HACKATHON/view/home.php
```

---
## 🗂 Estrutura de Pastas
Árvore completa (principais arquivos relevantes):
```
HACKATHON/
├── .htaccess
├── index.php                          # Definição de rotas principais (Router)
├── README.md
├── certificados/                      # Certificados para integração Pix
│   ├── certificado.pem
│   ├── certificado_completo.pem
│   ├── chave.key
│   └── chave_descriptografada.key
├── controle/                          # Controllers (camada de aplicação)
│   ├── controller_dashboards.php      # Estatísticas consolidadas e gráficos
│   ├── controller_donations.php       # Fluxo de doações via Pix (EfiPay)
│   ├── controller_iaServices.php      # Perguntas IA / pegada ecológica
│   ├── controller_logs.php            # (Infraestrutura de logs - em evolução)
│   ├── controller_monitoramento.php   # Proxy WeatherAPI / monitoramento
│   ├── controller_perguntas_quiz.php  # CRUD/Read de perguntas do quiz
│   ├── controller_user_game.php       # Pontuação EcoGame
│   ├── controller_user_quiz.php       # Pontuação EcoQuiz
│   └── controller_usuarios.php        # Cadastro / login / token
├── docs/                              # Scripts SQL e utilitários
│   ├── atividadeEcologicaTable.sql    # Tabela de atividades ecológicas
│   ├── bancoHackathon.sql             # Dump completo (estrutura + dados)
│   ├── hackathon.sql                  # Script alternativo de criação
│   ├── regioes.txt                    # Possível apoio a dados geográficos
│   └── codes/                         # Scripts auxiliares / protótipos
│       ├── configs.php
│       ├── crypto.php
│       └── functions.php
├── image/                             # Imagens e logos (ONGs, branding)
│   ├── Greenpeace_logo.png
│   ├── Greenpeace.webp
│   ├── institutoterra_image.webp
│   ├── logo_instituto_terra.png
│   ├── projetotamar_img.webp
│   ├── sos_mataatlantica_logo.png
│   ├── sosmataatlantica_image.jpg
│   ├── wwf_image.jpg
│   └── wwf_ong.png
├── js/                                # Scripts de frontend
│   ├── cadastrar.js                   # Lógica de cadastro
│   ├── dashboard.js                   # Carrega estatísticas e gráficos
│   ├── donation.js                    # Fluxo de doações / QR Pix
│   ├── functions.js                   # Funções utilitárias (fetch wrappers, etc.)
│   ├── logar.js                       # Lógica de autenticação
│   ├── monitoramento.js               # Consome rota /monitoramento/
│   └── quiz.js                        # Lógica completa do EcoQuiz
├── jwt/                               # Biblioteca JWT incorporada
│   ├── BeforeValidException.php
│   ├── CachedKeySet.php
│   ├── ExpiredException.php
│   ├── JWK.php
│   ├── JWT.php
│   ├── Key.php
│   ├── SignatureInvalidException.php
│   ├── php-jwt-main.zip               # Arquivo compactado original
│   └── php-jwt-main/                  # Código fonte vendor interno
│       └── php-jwt-main/
│           ├── CHANGELOG.md
│           ├── composer.json
│           ├── LICENSE
│           ├── README.md
│           └── src/
│               ├── BeforeValidException.php
│               ├── CachedKeySet.php
│               ├── ExpiredException.php
│               ├── JWK.php
│               ├── JWT.php
│               ├── Key.php
│               └── SignatureInvalidException.php
├── modelo/                            # Camada de Modelos / Entidades
│   ├── Banco.php                      # Conexão PDO / abstração DB
│   ├── Dashboard.php                  # Modelo de estatísticas
│   ├── Donation.php                   # Modelo de doações
│   ├── IaService.php                  # Modelo para serviço IA
│   ├── Log.php                        # Modelo de logs
│   ├── MeuTokenJWT.php                # Implementação de tokens JWT
│   ├── PerguntaQuiz.php               # Estrutura de pergunta do quiz
│   ├── Router.php                     # Micro roteador customizado
│   ├── UserGame.php                   # Entidade pontuação de jogo
│   ├── UserQuiz.php                   # Entidade pontuação do quiz
│   └── Usuario.php                    # Entidade usuário
├── public/
│   └── components/                    # Componentização de layout
│       ├── donation_container.php     # UI de valores e QR Pix
│       ├── header.php                 # Navbar / perfil / auth UI
│       ├── links.php                  # Includes (CSS, libs)
│       ├── ongs_content.php           # Conteúdo institucional ONGs
│       └── quiz_container.php         # Estrutura visual do quiz
├── style/
│   └── style.css                      # Design System / temas / utilidades
├── view/                              # Páginas principais (MVC - V)
│   ├── cadastrar.php                  # Tela de cadastro
│   ├── dashboard.php                  # Dashboard analítico / emissões
│   ├── donation.php                   # Página de doações
│   ├── game.php                       # Página EcoGame
│   ├── graficos/                      # Gráficos parciais (iframes e seções)
│   │   ├── graficoComparacao.html
│   │   ├── graficoDoadores.html
│   │   ├── graficoMesEmissao.html
│   │   ├── graficoTipoAtividade.html
│   │   ├── graficoTopGames.html
│   │   ├── graficoTopQuiz.html
│   │   └── graficolinha.html
│   ├── home.php                       # Landing principal / módulos
│   ├── logar.php                      # Tela de login
│   ├── monitoring.php                 # Dashboard climático detalhado
│   ├── quiz.php                       # Página do EcoQuiz
│   └── weather.php                    # Visualização mapa meteorológico (MapTiler)
```

---
## 🔐 Fluxo de Autenticação (JWT)
1. Usuário cadastra: `POST /cadastrar`
2. Faz login: `POST /logar`
3. Backend gera JWT (`MeuTokenJWT`)
4. Token armazenado em `localStorage`
5. Requisições autenticadas enviam token (ex.: quiz, jogo, scoreboard, doações)
6. Validação no backend antes de persistir estatísticas / transações

---
## 🌐 Rotas Principais da API
Lista completa das rotas ativas definidas em `index.php` (excluindo as comentadas):

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/` | Health check / router OK |
| GET | `/perguntas/{id}` | Retorna pergunta específica do quiz |
| GET | `/perguntas/random` | Retorna 10 perguntas aleatórias do quiz |
| POST | `/cadastrar` | Cadastro de usuário (gera JWT) |
| POST | `/logar` | Login de usuário (gera JWT) |
| POST | `/pix/gerarCodigo` | Gera payload/QR Code Pix para doação |
| POST | `/pix/registrar` | Registra efetivação de doação Pix |
| POST | `/iaServices/pergunta` | IA calcula pegada ecológica |
| POST | `/usuario/token/payload` | Retorna payload do token JWT (dados do usuário) |
| POST | `/user_game/insert` | Insere / acumula pontuação do EcoGame |
| POST | `/user_quiz/insert` | Insere / acumula pontuação do EcoQuiz |
| GET | `/user_quiz/read` | Lê estatísticas acumuladas do quiz do usuário autenticado |
| GET | `/user_game/read` | Lê estatísticas acumuladas do game do usuário autenticado |
| GET | `/dashboard/relatorio/dashboards` | Retorna indicadores consolidados (dashboard) |
| GET | `/dashboard/relatorio/graficos` | Retorna dados agregados para gráficos |
| POST | `/dashboard` | Registra atividade (emissão / ação sustentável) |
| GET | `/monitoramento/{localizacao}` | Retorna dados climáticos (WeatherAPI) |

> Todas as rotas protegidas exigem envio de JWT válido (authorization header / token localStorage conforme implementação). Ajuste middlewares futuros conforme necessidade.

---
## 🗄 Banco de Dados (Resumo Lógico)
Principais entidades (baseado em `bancoHackathon.sql`):
- `usuarios` – Autenticação / perfil
- `perguntas_quiz` – Questões e alternativas
- `user_quiz` – Estatísticas agregadas do quiz
- `user_game` – Estatísticas agregadas do jogo
- `doacoes` – Registros de transações Pix
- `atividades_ecologicas` - Registro da pegada ecológica do usuário
- `cache` – Armazenamento temporário (possível otimização de API)
- `logs` – Auditoria e monitoramento

> Utilize índices em colunas usadas para busca frequente (ex.: `usuario_id`).

---
## 🛡 Segurança & Boas Práticas
| Camada | Ação |
|--------|------|
| Autenticação | JWT baseado em claims personalizados |
| API Pix | Uso de certificado + client credentials |
| SQL | Senhas armazenadas com criptografia HASH |
| Armazenamento | Sem dados sensíveis do usuário transitando livremente |
| Frontend | Token em `localStorage` |

### Melhorias Futuras de Segurança
- Rotação de chaves JWT
- Rate limiting em rotas sensíveis
- CSP / Headers de segurança adicional
- Transição de toda a autenticação para o Server-Side

---
## 📊 Destaques do Frontend
- Design **Glassmorphism + Neon** consistente
- Gráficos interativos responsivos (Chart.js)
- Componentização via `public/components` (header, quiz, doações)
- UX educativa (explicações no quiz, feedback visual, progresso)
- Gamificação (EcoGame + ranking planejado)

---
## 🛣 Roadmap / Próximos Passos
- [ ] Exportação de relatórios (PDF/CSV)
- [ ] Cache implementado
- [ ] Dark/Light Theme toggle
- [ ] Internacionalização (pt-BR/en)
- [ ] Modo offline parcial (PWA)
- [ ] Maior responsividade mobile

---

## 📄 Licença
Projeto desenvolvido para fins educacionais e demonstração em Hackathon.

---
## 👥 Créditos & Contato

### Autores

1. **Rodrigo Roda** - GitHub: https://github.com/rod-roda
2. **Thiago César** – GitHub: https://github.com/ThhiagoCarvalho
3. **Heitor Rodrigues** – GitHub: https://github.com/HeitorCRZ
4. **Natan Telles** – GitHub: https://github.com/natan-telles
5. **Thiago Mancilla** – GitHub: https://github.com/Thiago-Mancilla
6. **Isabelli** – GitHub: https://github.com/iisaag
7. **Ana Clara** – GitHub: https://github.com/claramorei
8. **Isabela Rangel** – GitHub: https://github.com/isarangel000
9. **Abner Telles** – GitHub: (adicionar)

**Hackathon:** Dados pelo Clima  
**ODS Foco:** 13 – Ação Contra a Mudança Global do Clima  
**Repositório:** https://github.com/rod-roda/HACKATHON

---
<div align="center">
<br/>
<b>🌱 "Tecnologia a serviço do clima"</b><br/>
Feito com 💚 para um futuro sustentável.
</div>
