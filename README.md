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
11. [Contribuição](#-contribuição)
12. [Licença](#-licença)
13. [Créditos & Contato](#-créditos--contato)

---
## 🧭 Visão Geral
O **EcoSystem** é uma plataforma integrada que entrega **monitoramento climático**, **quiz educativo (EcoQuiz)**, **gamificação ambiental (EcoGame)** e **doações para ONGs ambientais** – tudo sustentado por um backend em **PHP** e um frontend moderno com **JavaScript + Chart.js + Glassmorphism UI**.

O objetivo é apoiar a **ODS 13 (Ação Contra a Mudança Global do Clima)** por meio de ferramentas de engajamento que unem dados, educação e impacto real.

---
## 🚀 Principais Módulos
| Módulo | Descrição | Destaques |
|--------|-----------|-----------|
| Monitoramento Climático | Dashboard com busca por cidade e gráficos interativos | WeatherAPI, Chart.js, Glassmorphism |
| EcoQuiz | Quiz de sustentabilidade com explicações | 10 questões dinâmicas, score persistido, ranking futuro |
| EcoGame | Jogo estilo snake temático oceânico | Coleta de lixo virtual, gamificação ambiental |
| Doações | Sistema de apoio a ONGs ambientais via Pix | Integração EfiPay (Gerencianet), QR Code dinâmico |
| Dashboard Pessoal | Estatísticas de quiz, jogo e doações | Consolidação de engajamento do usuário |

---
## 🏗 Arquitetura & Tecnologias
### Backend
- PHP 8+ (estilo procedural + classes de modelo)
- Router simples (`modelo/Router.php`)
- cURL para integrações externas
- MySQL / MariaDB (scripts em `docs/`)
- JWT personalizado (`modelo/MeuTokenJWT.php`)

### Frontend
- HTML5 semântico
- CSS3 + Design System (variáveis, glassmorphism, animações neon)
- JavaScript modular (`js/`): quiz, monitoração, doações, dashboard
- Chart.js para gráficos (temperatura, vento)
- QRCode.js para geração de QR Pix
- Remix Icons / Bootstrap Icons

### Integrações Externas
| Serviço | Uso |
|---------|-----|
| WeatherAPI | Dados climáticos por localização |
| EfiPay Pix API | Geração de cobranças e QR Code Pix |
| MapTiler (weather view) | Visualização de camadas meteorológicas dinâmicas |

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
mysql -u root -p < docs/bancoHackathon.sql
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
## 🗂 Estrutura de Pastas (Resumo)
```
HACKATHON/
├── index.php                # Entrada e definição de rotas
├── controle/                # Controllers (quiz, doações, usuários ...)
├── modelo/                  # Classes (Banco, JWT, Router, Quiz ...)
├── view/                    # Views (home, monitoring, quiz, game ...)
├── public/components/       # Componentes reutilizáveis (header, containers)
├── js/                      # Scripts de frontend (quiz, funções, dashboard)
├── style/                   # CSS principal (design system)
├── docs/                    # SQL e documentação auxiliar
├── certificados/            # Certificados Pix / SSL
└── image/                   # Imagens e logos
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
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/` | Teste de rota (router OK) |
| POST | `/cadastrar` | Cadastro de usuário |
| POST | `/logar` | Login de usuário |
| GET | `/perguntas/random` | Retorna 10 perguntas aleatórias do quiz |
| GET | `/perguntas/{id}` | Pergunta específica |
| POST | `/pix/gerarCodigo` | Gera QR Code Pix |
| POST | `/pix/registrar` | Registra pagamento Pix |
| (Futuro) | `/user_quiz/*` | Persistência de resultados do quiz |
| (Futuro) | `/user_game/*` | Estatísticas de jogo |

> Algumas rotas avançadas podem estar em implementação ou comentadas (ex.: logs / dashboard analítico).

---
## 🗄 Banco de Dados (Resumo Lógico)
Principais entidades (baseado em `bancoHackathon.sql`):
- `usuarios` – Autenticação / perfil
- `perguntas_quiz` – Questões e alternativas
- `user_quiz` – Estatísticas agregadas do quiz
- `doacoes` – Registros de transações Pix
- `cache` – Armazenamento temporário (possível otimização de API)
- (Possível) `logs` – Auditoria e monitoramento (em planejamento)

> Utilize índices em colunas usadas para busca frequente (ex.: `usuario_id`, `data_criacao`).

---
## 🛡 Segurança & Boas Práticas
| Camada | Ação |
|--------|------|
| Autenticação | JWT baseado em claims personalizados |
| Transporte | Recomenda-se HTTPS em produção |
| API Pix | Uso de certificado + client credentials |
| SQL | Use prepared statements (verificar em controllers) |
| Armazenamento | Sem dados sensíveis em repositório público (ideal ocultar chaves) |
| Frontend | Token em `localStorage` (poderia evoluir para cookies httpOnly) |

### Melhorias Futuras de Segurança
- Rotação de chaves JWT
- Rate limiting em rotas sensíveis
- CSP / Headers de segurança adicional
- Sanitização centralizada para entradas

---
## 📊 Destaques do Frontend
- Design **Glassmorphism + Neon** consistente
- Gráficos interativos responsivos (Chart.js)
- Componentização parcial via `public/components` (header, quiz, doações)
- UX educativa (explicações no quiz, feedback visual, progresso)
- Gamificação (EcoGame + ranking planejado)

---
## 🧪 Testes (Sugestões)
Atualmente não há suíte de testes automatizados. Sugestões:
| Tipo | Ferramenta | Cobrir |
|------|------------|--------|
| Unit (PHP) | PHPUnit | JWT, Router, Controllers |
| Integração | curl / Postman | Fluxo Pix, Quiz Random |
| E2E | Cypress / Playwright | Login → Quiz → Doação |

---
## 🛣 Roadmap / Próximos Passos
- [ ] Ranking global (quiz e game)
- [ ] Dashboard unificado com emissões calculadas
- [ ] Exportação de relatórios (PDF/CSV)
- [ ] Logs estruturados e auditoria
- [ ] Dark/Light Theme toggle
- [ ] Internacionalização (pt-BR/en)
- [ ] Serviço de cache para chamadas WeatherAPI
- [ ] Modo offline parcial (PWA)

---

## 📄 Licença
Projeto desenvolvido para fins educacionais e demonstração em Hackathon. Ajuste uma licença (ex.: MIT) se desejar expansão comunitária.

---
## 👥 Créditos & Contato
**Autor:** Rodrigo Roda  
**Hackathon:** Dados pelo Clima  
**ODS Foco:** 13 – Ação Contra a Mudança Global do Clima  
**GitHub:** https://github.com/rod-roda/HACKATHON

---
<div align="center">
<br/>
<b>🌱 "Tecnologia a serviço do clima"</b><br/>
Feito com 💚 para um futuro sustentável.
</div>
