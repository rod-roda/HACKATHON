-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 18-Ago-2025 às 00:45
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hackathon`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache`
--

CREATE TABLE `cache` (
  `id` int(11) NOT NULL,
  `chave` varchar(255) NOT NULL,
  `valor` text NOT NULL,
  `expira_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `criado_em` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `doacoes`
--

CREATE TABLE `doacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `metodo_pagamento` varchar(50) DEFAULT NULL,
  `data_doacao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `doacoes`
--

INSERT INTO `doacoes` (`id`, `usuario_id`, `valor`, `metodo_pagamento`, `data_doacao`) VALUES
(1, 1, '50.00', 'PIX', '2025-08-17 23:10:00'),
(2, 6, '75.50', 'PIX', '2025-08-17 23:15:00'),
(3, 14, '120.00', 'PIX', '2025-08-17 23:20:00'),
(4, 15, '200.00', 'PIX', '2025-08-17 23:25:00'),
(5, 16, '35.75', 'PIX', '2025-08-17 23:30:00'),
(6, 17, '90.00', 'PIX', '2025-08-17 23:35:00'),
(7, 19, '150.00', 'PIX', '2025-08-17 23:40:00'),
(8, 20, '500.00', 'PIX', '2025-08-17 23:45:00'),
(9, 21, '80.25', 'PIX', '2025-08-17 23:50:00'),
(10, 22, '300.00', 'PIX', '2025-08-17 23:55:00'),
(11, 23, '45.00', 'PIX', '2025-08-18 00:00:00'),
(12, 24, '110.00', 'PIX', '2025-08-18 00:05:00'),
(13, 25, '250.00', 'PIX', '2025-08-18 00:10:00'),
(14, 26, '400.00', 'PIX', '2025-08-18 00:15:00'),
(15, 27, '60.00', 'PIX', '2025-08-18 00:20:00'),
(16, 28, '180.00', 'PIX', '2025-08-18 00:25:00'),
(17, 29, '275.00', 'PIX', '2025-08-18 00:30:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` varchar(255) NOT NULL,
  `detalhes` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `data_registro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perguntas_quiz`
--

CREATE TABLE `perguntas_quiz` (
  `id` int(11) NOT NULL,
  `pergunta` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL COMMENT 'Caminho ou URL da imagem relacionada à pergunta',
  `alternativa_a` varchar(255) NOT NULL,
  `alternativa_b` varchar(255) NOT NULL,
  `alternativa_c` varchar(255) NOT NULL,
  `alternativa_d` varchar(255) NOT NULL,
  `alternativa_e` varchar(255) DEFAULT NULL,
  `alternativa_correta` char(1) NOT NULL COMMENT 'Valores: A, B, C ou D',
  `explicacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `perguntas_quiz`
--

INSERT INTO `perguntas_quiz` (`id`, `pergunta`, `imagem`, `alternativa_a`, `alternativa_b`, `alternativa_c`, `alternativa_d`, `alternativa_e`, `alternativa_correta`, `explicacao`) VALUES
(1, 'O que significa o termo “sustentabilidade”?', NULL, 'Uso ilimitado de recursos naturais', 'Uso consciente dos recursos para não comprometer o futuro', 'Proibição total de uso de recursos naturais', 'Substituição de todos os recursos por sintéticos', 'Ignorar totalmente o uso de recursos', 'B', 'Sustentabilidade significa usar os recursos de forma equilibrada para não comprometer as gerações futuras.'),
(2, 'Qual gás é o principal responsável pelo efeito estufa?', NULL, 'Oxigênio', 'Metano', 'Dióxido de carbono', 'Ozônio', 'Nitrogênio', 'C', 'O dióxido de carbono é o principal gás do efeito estufa gerado pelas atividades humanas.'),
(3, 'Qual destas é uma fonte de energia renovável?', NULL, 'Carvão mineral', 'Petróleo', 'Energia solar', 'Gás natural', 'Energia nuclear', 'C', 'A energia solar é renovável porque vem de uma fonte inesgotável e não polui durante a geração.'),
(4, 'O que é reciclagem?', NULL, 'Queima de resíduos para gerar energia', 'Reaproveitamento de materiais para produzir novos produtos', 'Enterrar resíduos no solo', 'Usar produtos até se desgastarem totalmente', 'Descartar o lixo no mar', 'B', 'Reciclagem é o processo de transformar resíduos em novos produtos, reduzindo o consumo de recursos naturais.'),
(5, 'Qual das opções abaixo é um exemplo de economia circular?', NULL, 'Produzir, usar e descartar', 'Reciclar materiais para criar novos produtos', 'Extrair recursos sem reposição', 'Uso único de embalagens', 'Produzir mais resíduos para incentivar a reciclagem', 'B', 'Na economia circular, os materiais são reaproveitados e reinseridos no ciclo produtivo, reduzindo desperdícios.'),
(6, 'Qual prática ajuda na redução do consumo de água?', NULL, 'Lavar carro todos os dias', 'Usar torneiras com arejadores', 'Deixar torneira aberta ao escovar os dentes', 'Irrigação agrícola em horário de sol forte', 'Lavar calçada com mangueira diariamente', 'B', 'Torneiras com arejadores reduzem o fluxo de água sem prejudicar o uso, economizando recursos.'),
(7, 'O que significa “pegada de carbono”?', NULL, 'Quantidade de CO₂ emitida por uma pessoa, empresa ou produto', 'Marca de sapato ecológico', 'Quantidade de energia elétrica consumida', 'Área verde por habitante', 'Quantidade de árvores plantadas', 'A', 'A pegada de carbono mede a quantidade de CO₂ emitida diretamente ou indiretamente por atividades humanas.'),
(8, 'Qual destas atitudes é mais sustentável no transporte urbano?', NULL, 'Usar bicicleta', 'Usar carro particular diariamente', 'Usar moto para pequenas distâncias', 'Andar de táxi todos os dias', 'Helicóptero para deslocamentos rápidos', 'A', 'A bicicleta é um meio de transporte limpo e sustentável, sem emissão de gases poluentes.'),
(9, 'Qual bioma brasileiro é o mais ameaçado pelo desmatamento?', NULL, 'Pantanal', 'Amazônia', 'Mata Atlântica', 'Caatinga', 'Pampa', 'C', 'A Mata Atlântica é o bioma brasileiro mais devastado, restando menos de 15% da sua área original.'),
(10, 'O que é compostagem?', NULL, 'Processamento de resíduos orgânicos para gerar adubo natural', 'Descarte de lixo em aterros sanitários', 'Queima de resíduos para gerar energia', 'Produção de embalagens biodegradáveis', 'Desidratação de alimentos', 'A', 'Compostagem transforma resíduos orgânicos em adubo, fechando o ciclo de nutrientes na natureza.'),
(11, 'Qual é a principal causa do desmatamento na Amazônia?', NULL, 'Expansão urbana', 'Mineração', 'Agricultura e pecuária', 'Turismo', 'Pesca predatória', 'C', 'A agricultura e pecuária são as principais causas do desmatamento na Amazônia devido à expansão de áreas produtivas.'),
(12, 'O que é energia eólica?', NULL, 'Energia obtida a partir de combustíveis fósseis', 'Energia obtida do vento', 'Energia gerada por marés', 'Energia obtida de resíduos orgânicos', 'Energia de baterias recarregáveis', 'B', 'Energia eólica é gerada pelo movimento do vento, uma fonte limpa e renovável.'),
(13, 'Qual material demora mais tempo para se decompor na natureza?', NULL, 'Papel', 'Plástico', 'Vidro', 'Madeira', 'Alumínio', 'C', 'O vidro demora milhares de anos para se decompor, sendo um dos materiais mais duráveis na natureza.'),
(14, 'O que significa “economia verde”?', NULL, 'Sistema econômico que incentiva o uso de combustíveis fósseis', 'Modelo de produção baseado na preservação ambiental', 'Economia baseada no uso de metais preciosos', 'Sistema de comércio de carbono', 'Economia de baixo carbono', 'B', 'Economia verde prioriza o desenvolvimento sustentável, conciliando crescimento econômico e preservação ambiental.'),
(15, 'Qual destes produtos é biodegradável?', NULL, 'Garrafa PET', 'Sacola plástica comum', 'Casca de banana', 'Canudo de plástico', 'Embalagem de isopor', 'C', 'A casca de banana é biodegradável, pois se decompõe naturalmente e rapidamente.'),
(16, 'O que é aquecimento global?', NULL, 'Diminuição da temperatura média da Terra', 'Aumento da temperatura média da Terra', 'Aumento da camada de ozônio', 'Resfriamento dos polos', 'Aumento da temperatura dos oceanos apenas', 'B', 'O aquecimento global é o aumento da temperatura média da Terra causado por gases de efeito estufa.'),
(17, 'Qual destes hábitos contribui mais para a redução de lixo?', NULL, 'Comprar produtos descartáveis', 'Reutilizar embalagens', 'Usar copos plásticos', 'Descartar pilhas no lixo comum', 'Comprar produtos em excesso', 'B', 'Reutilizar embalagens evita a geração de novos resíduos e prolonga a vida útil dos materiais.'),
(18, 'O que é certificação FSC?', NULL, 'Selo para produtos de madeira provenientes de manejo florestal responsável', 'Certificação para alimentos orgânicos', 'Selo de eficiência energética', 'Certificação para reciclagem de plástico', 'Certificação de energia solar', 'A', 'O selo FSC garante que o produto de madeira foi obtido de manejo florestal sustentável.'),
(19, 'Qual destes é um exemplo de poluição atmosférica?', NULL, 'Lixo jogado na rua', 'Derramamento de petróleo', 'Emissão de fumaça por veículos', 'Ruídos urbanos', 'Uso de agrotóxicos', 'C', 'A emissão de fumaça por veículos libera poluentes que contribuem para a poluição atmosférica.'),
(20, 'O que significa “consumo consciente”?', NULL, 'Comprar o máximo possível para estimular a economia', 'Adquirir produtos de forma responsável, pensando no impacto ambiental e social', 'Evitar comprar qualquer tipo de produto', 'Usar apenas produtos importados', 'Comprar apenas produtos de luxo', 'B', 'Consumo consciente envolve avaliar impactos ambientais e sociais antes da compra, escolhendo opções responsáveis.'),
(21, 'Qual é o principal objetivo da Agenda 2030 da ONU?', NULL, 'Promover o crescimento econômico ilimitado', 'Erradicar a pobreza e proteger o planeta', 'Aumentar a produção industrial', 'Reduzir impostos sobre combustíveis fósseis', 'Aumentar exploração de petróleo', 'B', 'A Agenda 2030 da ONU busca erradicar a pobreza, proteger o planeta e promover prosperidade sustentável.'),
(22, 'O que é energia geotérmica?', NULL, 'Energia obtida do calor interno da Terra', 'Energia obtida de rios', 'Energia obtida de combustíveis fósseis', 'Energia gerada por ondas sonoras', 'Energia obtida de tempestades', 'A', 'Energia geotérmica aproveita o calor interno da Terra para gerar eletricidade ou aquecimento.'),
(23, 'Qual destes materiais é totalmente reciclável infinitas vezes?', NULL, 'Plástico', 'Papel', 'Vidro', 'Borracha', 'Aço', 'C', 'O vidro pode ser reciclado infinitas vezes sem perder qualidade ou propriedades.'),
(24, 'O que é biodiversidade?', NULL, 'Variedade de ecossistemas de um país', 'Quantidade de florestas existentes', 'Variedade de espécies vivas em um ecossistema', 'Número de rios em uma região', 'Quantidade de montanhas', 'C', 'Biodiversidade é a variedade de espécies vivas em um ecossistema, essencial para equilíbrio ambiental.'),
(25, 'Qual prática reduz a emissão de gases de efeito estufa no setor de transporte?', NULL, 'Carona solidária', 'Aumentar velocidade nas rodovias', 'Usar veículos a diesel', 'Fazer viagens aéreas curtas', 'Aumentar o número de rodovias', 'A', 'A carona solidária reduz o número de veículos nas ruas e as emissões de gases poluentes.'),
(26, 'O que significa “desenvolvimento sustentável”?', NULL, 'Crescimento econômico sem considerar o meio ambiente', 'Atender às necessidades atuais sem comprometer as futuras gerações', 'Uso irrestrito de recursos naturais', 'Expansão urbana acelerada', 'Crescimento econômico acelerado sem restrições', 'B', 'Desenvolvimento sustentável é atender às necessidades atuais sem comprometer as futuras gerações.'),
(27, 'Qual recurso natural é considerado não renovável?', NULL, 'Energia solar', 'Vento', 'Petróleo', 'Biomassa', 'Energia geotérmica', 'C', 'Petróleo é um recurso não renovável, pois leva milhões de anos para se formar.'),
(28, 'O que é o Protocolo de Kyoto?', NULL, 'Acordo internacional para reduzir emissões de gases de efeito estufa', 'Tratado de livre comércio', 'Lei ambiental brasileira', 'Programa de reciclagem municipal', 'Convenção sobre Diversidade Biológica', 'A', 'O Protocolo de Kyoto é um acordo internacional para reduzir emissões de gases de efeito estufa.'),
(29, 'Qual destas ações ajuda na preservação da água potável?', NULL, 'Evitar lavar calçadas com mangueira', 'Deixar torneira aberta ao ensaboar a louça', 'Usar mais produtos químicos de limpeza', 'Descartar óleo de cozinha na pia', 'Aumentar o uso de agrotóxicos', 'A', 'Evitar lavar calçadas com mangueira economiza grandes quantidades de água potável.'),
(30, 'O que é agricultura orgânica?', NULL, 'Cultivo de alimentos com uso intensivo de agrotóxicos', 'Produção sem uso de pesticidas e fertilizantes químicos sintéticos', 'Plantio apenas em estufas fechadas', 'Produção agrícola feita apenas por máquinas', 'Uso de fertilizantes químicos orgânicos', 'B', 'Agricultura orgânica não utiliza pesticidas e fertilizantes químicos sintéticos.'),
(31, 'Qual é a principal função da camada de ozônio?', NULL, 'Absorver radiação ultravioleta do sol', 'Aumentar a temperatura da Terra', 'Produzir oxigênio', 'Refletir luz visível', 'Absorver gás carbônico da atmosfera', 'A', 'A camada de ozônio absorve a radiação ultravioleta prejudicial à vida na Terra.'),
(32, 'O que caracteriza um produto “carbono neutro”?', NULL, 'Produto que não emite CO₂ durante a fabricação', 'Produto cuja emissão de CO₂ foi compensada por ações ambientais', 'Produto feito apenas com energia solar', 'Produto 100% reciclável', 'Produto reciclado duas vezes', 'B', 'Produto carbono neutro tem suas emissões compensadas por ações como reflorestamento.'),
(33, 'Qual bioma brasileiro é conhecido pela vegetação de cerrado?', NULL, 'Pantanal', 'Amazônia', 'Cerrado', 'Mata Atlântica', 'Caatinga', 'C', 'O Cerrado é caracterizado por vegetação de árvores baixas e retorcidas, adaptadas ao clima seco.'),
(34, 'O que é efeito estufa?', NULL, 'Aquecimento natural da Terra causado por gases na atmosfera', 'Resfriamento dos polos', 'Camada de gelo que cobre o planeta', 'Fenômeno que provoca chuvas ácidas', 'Processo de descongelamento polar', 'A', 'O efeito estufa é um aquecimento natural da Terra devido à presença de gases na atmosfera.'),
(35, 'Qual destas práticas é um exemplo de reúso de água?', NULL, 'Aproveitar água da chuva para regar plantas', 'Lavar carro com água potável', 'Trocar encanamentos antigos', 'Beber água filtrada', 'Usar água mineral para irrigar jardins', 'A', 'Reaproveitar água da chuva para regar plantas é um exemplo de reúso de água.'),
(36, 'O que é poluição sonora?', NULL, 'Som agradável de pássaros', 'Ruídos excessivos que prejudicam saúde e bem-estar', 'Explosões vulcânicas', 'Barulho produzido por ondas do mar', 'Sons de baixa frequência usados em pesquisas', 'B', 'Poluição sonora são ruídos excessivos que afetam saúde e bem-estar.'),
(37, 'Qual destas ações ajuda na conservação de energia elétrica?', NULL, 'Desligar aparelhos da tomada quando não estão em uso', 'Deixar lâmpadas acesas o dia todo', 'Usar aparelhos antigos com alto consumo', 'Manter ar-condicionado ligado constantemente', 'Trocar lâmpadas LED por incandescentes', 'A', 'Desligar aparelhos da tomada evita consumo de energia em modo standby.'),
(38, 'O que significa “descarte correto de lixo eletrônico”?', NULL, 'Jogar no lixo comum', 'Levar a pontos de coleta especializados', 'Enterrar no quintal', 'Queimar para reduzir volume', 'Doar para lixões clandestinos', 'B', 'Lixo eletrônico deve ser levado a pontos de coleta especializados para evitar contaminação.'),
(39, 'O que é reflorestamento?', NULL, 'Queimar áreas verdes para plantar soja', 'Plantio de árvores em áreas degradadas', 'Remoção de árvores antigas', 'Substituição de florestas por pastagens', 'Plantio de árvores em áreas já preservadas', 'B', 'Reflorestamento consiste em plantar árvores em áreas degradadas para restaurar ecossistemas.'),
(40, 'Qual destes gases não contribui significativamente para o efeito estufa?', NULL, 'Metano', 'Oxigênio', 'Dióxido de carbono', 'Óxido nitroso', 'Vapor d’água', 'B', 'O oxigênio não é um gás de efeito estufa significativo.'),
(41, 'O que é um ecossistema?', NULL, 'Um conjunto de seres vivos interagindo entre si e com o ambiente', 'Um tipo de floresta tropical', 'Apenas animais de uma região', 'Uma área protegida pelo governo', 'Apenas animais e plantas de uma região', 'A', 'Um ecossistema é o conjunto de seres vivos e elementos não vivos interagindo em um ambiente.'),
(42, 'Qual é a principal fonte de energia do planeta Terra?', NULL, 'Energia nuclear', 'Energia solar', 'Energia das marés', 'Energia eólica', 'Energia elétrica de usinas térmicas', 'B', 'A energia solar é a principal fonte de energia da Terra, alimentando quase todos os ecossistemas.'),
(43, 'O que é efeito de ilha de calor?', NULL, 'Fenômeno em que áreas urbanas ficam mais quentes que áreas rurais', 'Aquecimento dos oceanos tropicais', 'Derretimento das calotas polares', 'Aumento da temperatura apenas no verão', 'Fenômeno de aquecimento global total', 'A', 'A ilha de calor ocorre quando áreas urbanas ficam mais quentes devido ao excesso de asfalto e concreto.'),
(44, 'Qual destas alternativas representa um exemplo de energia limpa?', NULL, 'Petróleo', 'Carvão mineral', 'Energia solar', 'Gás natural', 'Energia a diesel', 'C', 'Energia solar é considerada limpa por não emitir poluentes durante a geração.'),
(45, 'O que significa o símbolo dos três “R” da sustentabilidade?', NULL, 'Reduzir, Reutilizar e Reciclar', 'Reflorestar, Reaproveitar e Reparar', 'Reciclar, Renovar e Restaurar', 'Reutilizar, Reduzir e Reparar', 'Reparar, Reaproveitar e Reciclar', 'A', 'Os três Rs significam Reduzir, Reutilizar e Reciclar para minimizar o impacto ambiental.'),
(46, 'Qual destes impactos é causado pelo desmatamento?', NULL, 'Aumento da biodiversidade', 'Aumento da erosão do solo', 'Redução da poluição atmosférica', 'Diminuição das mudanças climáticas', 'Melhora da qualidade do ar', 'B', 'O desmatamento causa erosão do solo, perda de biodiversidade e alteração climática.'),
(47, 'O que é energia de biomassa?', NULL, 'Energia obtida do movimento das ondas', 'Energia produzida a partir de matéria orgânica', 'Energia obtida do núcleo atômico', 'Energia vinda do vento', 'Energia gerada pelo movimento das marés', 'B', 'A biomassa é energia produzida a partir de matéria orgânica como resíduos agrícolas e florestais.'),
(48, 'Qual destas atitudes ajuda na redução do consumo de papel?', NULL, 'Imprimir documentos sempre que possível', 'Utilizar blocos de anotações digitais', 'Comprar cadernos com mais folhas', 'Jogar folhas usadas no lixo', 'Imprimir sempre em frente e verso', 'B', 'Usar anotações digitais reduz o consumo de papel e o impacto ambiental.'),
(49, 'O que são resíduos perigosos?', NULL, 'Materiais que podem causar danos à saúde ou ao meio ambiente', 'Resíduos orgânicos de cozinha', 'Restos de jardinagem', 'Materiais recicláveis', 'Resíduos sólidos de varrição', 'A', 'Resíduos perigosos contêm substâncias que podem prejudicar a saúde e o meio ambiente.'),
(50, 'Qual é a principal fonte de poluição dos oceanos?', NULL, 'Plástico descartado de forma incorreta', 'Areia das praias', 'Resíduos de alimentos', 'Água da chuva', 'Areia de dragagem portuária', 'A', 'O plástico descartado incorretamente é a principal fonte de poluição dos oceanos.'),
(51, 'Qual é a cor da lixeira destinada a papel e papelão no sistema de coleta seletiva do Brasil?', NULL, 'Azul', 'Verde', 'Amarelo', 'Vermelho', 'Laranja', 'A', 'No Brasil, a cor azul é utilizada para identificar a lixeira destinada a papel e papelão.'),
(52, 'Qual é a cor da lixeira usada para metal na coleta seletiva brasileira?', NULL, 'Azul', 'Amarelo', 'Verde', 'Preto', 'Branco', 'B', 'Na coleta seletiva brasileira, a cor amarela identifica a lixeira para metais.'),
(53, 'Qual é a cor da lixeira para vidro no Brasil?', NULL, 'Verde', 'Vermelho', 'Laranja', 'Cinza', 'Marrom', 'A', 'O verde é a cor usada para lixeiras destinadas ao vidro no sistema de coleta seletiva.'),
(54, 'O que significa logística reversa?', NULL, 'Processo de coleta e retorno de produtos ou embalagens ao fabricante para reaproveitamento ou descarte adequado', 'Entrega rápida de produtos ao consumidor', 'Sistema de transporte público sustentável', 'Método de separação manual do lixo', 'Transporte de produtos orgânicos', 'A', 'Logística reversa é o processo de recolher produtos ou embalagens para reaproveitamento ou descarte adequado.'),
(55, 'Qual destes materiais não pode ser reciclado?', NULL, 'Papelão limpo', 'Vidro', 'Papel higiênico usado', 'Latas de alumínio', 'Papel engordurado', 'C', 'Papel higiênico usado não pode ser reciclado por questões de contaminação.'),
(56, 'O que é compostagem?', NULL, 'Processo de decomposição de resíduos orgânicos para produção de adubo natural', 'Queima de resíduos sólidos', 'Trituramento de materiais recicláveis', 'Armazenamento de lixo em aterros', 'Processo de secagem de resíduos', 'A', 'A compostagem transforma resíduos orgânicos em adubo natural por meio de decomposição controlada.'),
(57, 'O que é um aterro sanitário?', NULL, 'Área controlada para disposição final de resíduos com medidas de proteção ambiental', 'Depósito a céu aberto para lixo', 'Fábrica de reciclagem', 'Usina de energia solar', 'Área de preservação ambiental', 'A', 'Um aterro sanitário é um local controlado para destinação de resíduos, com medidas para evitar poluição.'),
(58, 'Qual destes é considerado resíduo perigoso?', NULL, 'Pilhas e baterias usadas', 'Papel reciclável', 'Restos de alimentos', 'Garrafa PET', 'Madeira tratada', 'A', 'Pilhas e baterias usadas são resíduos perigosos por conter metais pesados e substâncias tóxicas.'),
(59, 'Qual é o destino mais sustentável para resíduos orgânicos?', NULL, 'Compostagem', 'Aterro controlado', 'Incinerador', 'Lixão', 'Queima controlada', 'A', 'A compostagem é o destino mais sustentável para resíduos orgânicos, pois devolve nutrientes ao solo.'),
(60, 'Qual é o principal objetivo da coleta seletiva?', NULL, 'Separar resíduos para facilitar a reciclagem e reduzir o impacto ambiental', 'Acumular lixo para exportação', 'Aumentar o volume de resíduos coletados', 'Diminuir o número de lixeiras públicas', 'Diminuir número de funcionários da limpeza', 'A', 'A coleta seletiva separa materiais para facilitar a reciclagem e reduzir o impacto ambiental.'),
(61, 'O que é energia solar fotovoltaica?', NULL, 'Energia obtida do vento', 'Energia gerada a partir da luz do sol convertida em eletricidade', 'Energia produzida por combustíveis fósseis', 'Energia gerada pelas marés', 'Energia obtida da queima de lixo', 'B', 'A energia solar fotovoltaica converte a luz do sol diretamente em eletricidade por meio de painéis.'),
(62, 'Qual país é o maior produtor de energia eólica do mundo?', NULL, 'Estados Unidos', 'China', 'Alemanha', 'Brasil', 'Índia', 'B', 'A China é atualmente o maior produtor de energia eólica do mundo.'),
(63, 'O que é energia hidrelétrica?', NULL, 'Energia obtida do calor da Terra', 'Energia produzida pelo movimento da água', 'Energia gerada por ondas sonoras', 'Energia produzida por painéis solares', 'Energia de resíduos sólidos', 'B', 'Energia hidrelétrica é gerada pelo movimento da água em rios e barragens.'),
(64, 'Qual destas fontes é considerada renovável?', NULL, 'Carvão mineral', 'Petróleo', 'Energia das marés', 'Gás natural', 'Energia de petróleo', 'C', 'A energia das marés é renovável porque utiliza um recurso natural que se renova continuamente.'),
(65, 'O que é energia de biomassa?', NULL, 'Energia produzida a partir de matéria orgânica', 'Energia obtida de ondas do mar', 'Energia gerada por placas solares', 'Energia extraída de minérios', 'Energia de reatores nucleares', 'A', 'A biomassa produz energia a partir de matéria orgânica como resíduos agrícolas e florestais.'),
(66, 'O que caracteriza a energia geotérmica?', NULL, 'Uso do calor interno da Terra para gerar energia', 'Uso de turbinas movidas a vento', 'Uso da força das marés', 'Uso de painéis solares', 'Energia obtida de raios', 'A', 'A energia geotérmica utiliza o calor interno da Terra para gerar energia elétrica ou térmica.'),
(67, 'Qual é uma desvantagem da energia eólica?', NULL, 'Emissão de gases poluentes', 'Necessidade de áreas com ventos constantes', 'Uso de grandes quantidades de carvão', 'Aumento do desmatamento', 'Baixo custo de instalação', 'B', 'A energia eólica depende de locais com ventos constantes, o que pode limitar sua aplicação.'),
(68, 'O que significa o termo “energia limpa”?', NULL, 'Energia produzida sem emissão significativa de poluentes', 'Energia que não precisa de manutenção', 'Energia gerada apenas por usinas nucleares', 'Energia produzida exclusivamente à noite', 'Energia produzida apenas de dia', 'A', 'Energia limpa é aquela que não gera poluentes significativos durante sua produção.'),
(69, 'Qual destas energias depende diretamente do ciclo da água?', NULL, 'Energia solar', 'Energia hidrelétrica', 'Energia eólica', 'Energia de biomassa', 'Energia nuclear', 'B', 'A energia hidrelétrica depende do ciclo da água para movimentar turbinas e gerar eletricidade.'),
(70, 'Qual é a principal vantagem das energias renováveis?', NULL, 'São ilimitadas e causam menor impacto ambiental', 'São mais baratas que todas as fósseis', 'Podem ser armazenadas indefinidamente', 'Não precisam de infraestrutura para gerar energia', 'Não requerem manutenção', 'A', 'As energias renováveis são ilimitadas e causam menos impacto ambiental do que as fósseis.'),
(71, 'O que são mudanças climáticas?', NULL, 'Variações significativas e de longo prazo no clima da Terra', 'Alterações rápidas na previsão do tempo', 'Mudança repentina de estação do ano', 'Aumento temporário de chuvas', 'Mudança repentina do vento', 'A', 'Mudanças climáticas são variações significativas e de longo prazo no clima global.'),
(72, 'Qual é o principal gás de efeito estufa liberado pelas atividades humanas?', NULL, 'Oxigênio', 'Dióxido de carbono', 'Metano', 'Ozônio', 'Vapor d’água', 'B', 'O dióxido de carbono é o principal gás de efeito estufa emitido pelas atividades humanas.'),
(73, 'Qual setor mais contribui para as emissões de gases de efeito estufa no mundo?', NULL, 'Agricultura e pecuária', 'Transporte', 'Indústria', 'Produção de energia', 'Setor de resíduos sólidos', 'D', 'A produção de energia é o setor que mais contribui para as emissões globais de gases de efeito estufa.'),
(74, 'O que é o Acordo de Paris?', NULL, 'Acordo internacional para combater as mudanças climáticas', 'Tratado de livre comércio entre países europeus', 'Programa da ONU para proteção dos oceanos', 'Conferência sobre biodiversidade', 'Acordo para promover o turismo sustentável', 'A', 'O Acordo de Paris é um compromisso internacional para combater as mudanças climáticas.'),
(75, 'Qual fenômeno climático está relacionado ao aquecimento anormal das águas do Oceano Pacífico?', NULL, 'La Niña', 'El Niño', 'Monções', 'Ciclones', 'Frente fria', 'B', 'O El Niño é caracterizado pelo aquecimento anormal das águas do Oceano Pacífico, alterando o clima global.'),
(76, 'O que pode ocorrer com o derretimento das calotas polares?', NULL, 'Diminuição do nível do mar', 'Aumento do nível do mar', 'Resfriamento global', 'Seca nos desertos', 'Nenhuma alteração no nível do mar', 'B', 'O derretimento das calotas polares provoca aumento do nível do mar, afetando áreas costeiras.'),
(77, 'Qual destas ações ajuda a combater as mudanças climáticas?', NULL, 'Plantar árvores', 'Aumentar o uso de combustíveis fósseis', 'Expandir áreas de desmatamento', 'Usar mais produtos descartáveis', 'Ampliar a exploração de carvão', 'A', 'Plantar árvores ajuda a absorver CO₂ e combater as mudanças climáticas.'),
(78, 'O que significa “mitigação” das mudanças climáticas?', NULL, 'Aumento das emissões de gases poluentes', 'Adoção de medidas para reduzir ou evitar impactos ambientais', 'Substituição de áreas verdes por áreas urbanas', 'Queima de combustíveis fósseis', 'Aceleração do aquecimento global', 'B', 'Mitigação é a adoção de medidas para reduzir ou evitar impactos ambientais e climáticos.'),
(79, 'Qual é a relação entre desmatamento e mudanças climáticas?', NULL, 'O desmatamento reduz as emissões de CO₂', 'O desmatamento aumenta a captura de carbono', 'O desmatamento contribui para o aumento de gases de efeito estufa', 'O desmatamento não afeta o clima', 'O desmatamento limpa o ar', 'C', 'O desmatamento libera CO₂ e reduz a capacidade de absorção de carbono, intensificando o aquecimento global.'),
(80, 'O que é “adaptação” às mudanças climáticas?', NULL, 'Mudanças nos hábitos e infraestrutura para lidar com os impactos climáticos', 'Construção de mais usinas a carvão', 'Ignorar os riscos climáticos', 'Aumento do desmatamento', 'Queima de combustíveis fósseis', 'A', 'Adaptação climática envolve ajustar hábitos e infraestrutura para lidar com impactos das mudanças climáticas.'),
(81, 'O que é biodiversidade?', NULL, 'Variedade de espécies vivas em um ecossistema', 'Número de árvores em uma floresta', 'Quantidade de rios em um país', 'Variedade de tipos de solo', 'Número de parques nacionais', 'A', 'Biodiversidade é a variedade de espécies vivas em um ecossistema.'),
(82, 'Qual é a principal causa da perda de biodiversidade no mundo?', NULL, 'Mudança de estações', 'Desmatamento e destruição de habitats', 'Aumento das chuvas', 'Formação de montanhas', 'Aumento das áreas urbanas verdes', 'B', 'A principal causa da perda de biodiversidade é o desmatamento e destruição de habitats.'),
(83, 'O que significa espécie endêmica?', NULL, 'Espécie encontrada apenas em uma região específica', 'Espécie em extinção', 'Espécie que migra anualmente', 'Espécie adaptada a qualquer habitat', 'Espécie domesticada', 'A', 'Espécie endêmica é aquela encontrada apenas em uma região específica.'),
(84, 'Qual bioma brasileiro possui a maior biodiversidade?', NULL, 'Amazônia', 'Cerrado', 'Pantanal', 'Mata Atlântica', 'Caatinga', 'A', 'A Amazônia possui a maior biodiversidade entre os biomas brasileiros.'),
(85, 'O que são corredores ecológicos?', NULL, 'Áreas que ligam fragmentos de habitats para permitir o deslocamento de espécies', 'Rios com alto índice de peixes', 'Caminhos utilizados por seres humanos em trilhas', 'Faixas de reflorestamento apenas em áreas urbanas', 'Trilhas ecológicas para turistas', 'A', 'Corredores ecológicos conectam habitats fragmentados, permitindo fluxo genético e deslocamento das espécies.'),
(86, 'Qual é o principal objetivo das unidades de conservação?', NULL, 'Proteger ecossistemas e espécies ameaçadas', 'Ampliar áreas para agricultura', 'Incentivar mineração sustentável', 'Promover o turismo urbano', 'Criação de áreas para mineração', 'A', 'Unidades de conservação protegem ecossistemas e espécies ameaçadas.'),
(87, 'O que é polinização?', NULL, 'Processo de transferência de pólen entre flores', 'Dispersão de sementes pelo vento', 'Plantio de árvores nativas', 'Processo de decomposição de matéria orgânica', 'Produção de frutos e sementes', 'A', 'Polinização é a transferência de pólen entre flores, essencial para a reprodução das plantas.'),
(88, 'Qual é o impacto da introdução de espécies exóticas em um ecossistema?', NULL, 'Pode desequilibrar a fauna e flora local', 'Aumenta a biodiversidade sem impactos negativos', 'Melhora automaticamente o solo', 'Não interfere no ecossistema', 'Enriquecimento natural da fauna', 'A', 'Espécies exóticas podem desequilibrar o ecossistema ao competir com espécies nativas.'),
(89, 'O que é um animal considerado “chave” em um ecossistema?', NULL, 'Espécie cuja ausência provoca grande desequilíbrio no ambiente', 'Animal que vive apenas em cavernas', 'Espécie que se alimenta apenas de frutas', 'Animal que migra durante o inverno', 'Animal usado em pesquisas científicas', 'A', 'Espécie-chave é aquela cuja ausência provoca grande desequilíbrio no ecossistema.'),
(90, 'Qual destas ações contribui para a preservação da biodiversidade?', NULL, 'Proteção de habitats naturais', 'Caça predatória', 'Desmatamento para pecuária', 'Poluição dos rios', 'Caça esportiva controlada', 'A', 'Proteger habitats naturais é essencial para preservar a biodiversidade.'),
(91, 'O que é consumo consciente?', NULL, 'Comprar apenas produtos importados', 'Adquirir bens e serviços considerando impacto ambiental e social', 'Comprar sempre em grandes quantidades', 'Evitar consumir qualquer produto', 'Evitar comprar produtos nacionais', 'B', 'Consumo consciente é adquirir bens considerando impactos ambientais e sociais.'),
(92, 'Qual é a primeira etapa dos 3Rs da sustentabilidade?', NULL, 'Reduzir', 'Reciclar', 'Reutilizar', 'Reparar', 'Reparar', 'A', 'Reduzir é a primeira etapa dos 3Rs, minimizando a geração de resíduos.'),
(93, 'Qual destas atitudes é um exemplo de consumo consciente?', NULL, 'Comprar produtos com embalagens recicláveis', 'Trocar de celular todo ano', 'Deixar luzes acesas sem necessidade', 'Usar descartáveis sempre que possível', 'Comprar itens descartáveis sempre', 'A', 'Optar por embalagens recicláveis é uma atitude de consumo consciente.'),
(94, 'O que é obsolescência programada?', NULL, 'Produção de itens para durar o máximo possível', 'Planejamento para que produtos se tornem inúteis mais rapidamente', 'Venda de produtos sem garantia', 'Produção artesanal de bens', 'Venda de produtos apenas no atacado', 'B', 'Obsolescência programada é a prática de fabricar produtos para durar menos e estimular novas compras.'),
(95, 'Como um consumidor pode reduzir seu impacto ambiental?', NULL, 'Escolhendo produtos duráveis e sustentáveis', 'Ignorando certificações ambientais', 'Comprando apenas pelo menor preço', 'Usando sempre descartáveis', 'Escolher produtos descartáveis pela praticidade', 'A', 'Escolher produtos duráveis e sustentáveis reduz o impacto ambiental.'),
(96, 'O que significa selo “Orgânico Brasil” em um produto?', NULL, 'Produzido com uso intensivo de agrotóxicos', 'Produzido sem agrotóxicos e com práticas sustentáveis', 'Produto reciclado', 'Produto importado', 'Produzido no exterior', 'B', 'O selo “Orgânico Brasil” indica produção sem agrotóxicos e com práticas sustentáveis.'),
(97, 'Qual é uma prática de consumo consciente na moda?', NULL, 'Preferir roupas de marcas que adotam produção sustentável', 'Comprar roupas para uso único', 'Seguir apenas tendências rápidas', 'Descartar roupas em lixo comum', 'Comprar roupas de uso único para eventos', 'A', 'Na moda consciente, prioriza-se marcas que adotam práticas sustentáveis.'),
(98, 'O que é comércio justo (Fair Trade)?', NULL, 'Sistema que prioriza apenas lucro', 'Modelo de comércio que valoriza produtores, práticas sustentáveis e preço justo', 'Venda exclusiva de produtos importados', 'Sistema de liquidações constantes', 'Venda de produtos exclusivamente online', 'B', 'O comércio justo valoriza produtores, práticas sustentáveis e preços adequados.'),
(99, 'Como o consumo local pode ajudar o meio ambiente?', NULL, 'Reduz emissão de gases de transporte de longa distância', 'Aumenta a dependência de combustíveis fósseis', 'Eleva o consumo de embalagens plásticas', 'Diminui empregos na comunidade', 'Aumenta o uso de transporte aéreo', 'A', 'O consumo local reduz emissões de transporte e fortalece a economia da comunidade.'),
(100, 'Qual dessas ações evita o desperdício de alimentos?', NULL, 'Planejar compras e aproveitar sobras', 'Comprar grandes quantidades sem necessidade', 'Descartar frutas com aparência imperfeita', 'Ignorar datas de validade', 'Comprar alimentos em excesso para estocar', 'A', 'Planejar compras e aproveitar sobras evita desperdício de alimentos.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_game`
--

CREATE TABLE `user_game` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `jogo_nome` varchar(100) NOT NULL,
  `resultado` int(11) NOT NULL,
  `data_jogada` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user_game`
--

INSERT INTO `user_game` (`id`, `usuario_id`, `jogo_nome`, `resultado`, `data_jogada`) VALUES
(1, 1, 'EcoMarine', 105, '2025-08-17 03:25:14'),
(2, 15, 'EcoMarine', 575, '2025-08-17 14:24:48'),
(3, 1, 'EcoMarine', 150, '2025-08-17 21:10:00'),
(4, 6, 'EcoMarine', 300, '2025-08-17 21:15:00'),
(5, 14, 'EcoMarine', 450, '2025-08-17 21:20:00'),
(6, 15, 'EcoMarine', 575, '2025-08-17 21:25:00'),
(7, 16, 'EcoMarine', 220, '2025-08-17 21:30:00'),
(8, 17, 'EcoMarine', 95, '2025-08-17 21:35:00'),
(9, 19, 'EcoMarine', 230, '2025-08-17 21:40:00'),
(10, 20, 'EcoMarine', 600, '2025-08-17 21:45:00'),
(11, 21, 'EcoMarine', 340, '2025-08-17 21:50:00'),
(12, 22, 'EcoMarine', 720, '2025-08-17 21:55:00'),
(13, 23, 'EcoMarine', 180, '2025-08-17 22:00:00'),
(14, 24, 'EcoMarine', 410, '2025-08-17 22:05:00'),
(15, 25, 'EcoMarine', 380, '2025-08-17 22:10:00'),
(16, 26, 'EcoMarine', 500, '2025-08-17 22:15:00'),
(17, 27, 'EcoMarine', 270, '2025-08-17 22:20:00'),
(18, 28, 'EcoMarine', 455, '2025-08-17 22:25:00'),
(19, 29, 'EcoMarine', 610, '2025-08-17 22:30:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_quiz`
--

CREATE TABLE `user_quiz` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `quiz_nome` varchar(100) NOT NULL,
  `pontuacao` int(11) NOT NULL,
  `data_jogada` timestamp NULL DEFAULT current_timestamp(),
  `qtdRespondidas` int(11) NOT NULL,
  `qtdCorretas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `user_quiz`
--

INSERT INTO `user_quiz` (`id`, `usuario_id`, `quiz_nome`, `pontuacao`, `data_jogada`, `qtdRespondidas`, `qtdCorretas`) VALUES
(1, 15, 'EcoQuiz', 600, '2025-08-17 04:27:33', 60, 50),
(2, 1, 'EcoQuiz', 45, '2025-08-17 15:46:48', 20, 9),
(3, 1, 'EcoQuiz', 45, '2025-08-17 19:10:00', 20, 9),
(4, 6, 'EcoQuiz', 120, '2025-08-17 19:15:00', 30, 18),
(5, 14, 'EcoQuiz', 300, '2025-08-17 19:20:00', 40, 25),
(6, 15, 'EcoQuiz', 720, '2025-08-17 19:25:00', 70, 60),
(7, 16, 'EcoQuiz', 250, '2025-08-17 19:30:00', 35, 20),
(8, 17, 'EcoQuiz', 400, '2025-08-17 19:35:00', 50, 30),
(9, 19, 'EcoQuiz', 310, '2025-08-17 19:40:00', 40, 22),
(10, 20, 'EcoQuiz', 500, '2025-08-17 19:45:00', 55, 40),
(11, 21, 'EcoQuiz', 275, '2025-08-17 19:50:00', 30, 18),
(12, 22, 'EcoQuiz', 610, '2025-08-17 19:55:00', 65, 52),
(13, 23, 'EcoQuiz', 150, '2025-08-17 20:00:00', 25, 15),
(14, 24, 'EcoQuiz', 420, '2025-08-17 20:05:00', 50, 32),
(15, 25, 'EcoQuiz', 380, '2025-08-17 20:10:00', 45, 28),
(16, 26, 'EcoQuiz', 520, '2025-08-17 20:15:00', 60, 45),
(17, 27, 'EcoQuiz', 210, '2025-08-17 20:20:00', 30, 17),
(18, 28, 'EcoQuiz', 455, '2025-08-17 20:25:00', 55, 38),
(19, 29, 'EcoQuiz', 600, '2025-08-17 20:30:00', 65, 50);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `cpf` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha_hash`, `data_criacao`, `cpf`) VALUES
(1, 'Rodrigo Roda', 'rodrroda@gmail.com', 'IrgRAEYBxTwTXev7jfUgiFhETjlBb0NteUVEN2UvM1VLT1paSGc9PQ==', '2025-08-14 00:18:10', '48304487845'),
(6, 'Rodrigo Roda', 'teste@gmail.com', '3B8azF/ihYx1PCVsVLm/RUVmYnVtK1JQeWhEYUZmdFcveFYrR3c9PQ==', '2025-08-16 01:07:22', '483.044.878'),
(14, 'Rodrigo Roda Oliveto Alves', 'rodrigo.oliveto2@p4ed.com', 'S2cHjzaHuPc0Jy2vjGg+b21JYnBPckMvNHE3bUxFd1RoVzJLdFE9PQ==', '2025-08-16 01:31:39', '483.044.878'),
(15, 'Rodrigo Roda Oliveto Alves', 'rodriguinhoroda@gmail.com', 'NikoZrOjFal43MuCqthoMDFHa2ptdXZEY1V6OHV6VncycWtXOUE9PQ==', '2025-08-16 01:32:51', '483.044.878'),
(16, 'Rodrigo Roda', 'slaoq@gmail.com', 'fIvtFw/Pjkz1xWZOv/60sTdFNWsrU3Y0RmFpYmZIK2tWbGdmd2c9PQ==', '2025-08-17 01:24:15', '48304487845'),
(17, 'Rodrigo Roda', 'slaoq2@gmail.com', 'oqj/J0Jw6YLnXzhm+q0v03pkYUtLVGY2bkZUekxCNUMwM1ViK3c9PQ==', '2025-08-17 02:44:53', '48304487845'),
(19, 'thiaguinnnnn', 'thiaguinhomain@gmail.com', 'HOhQoUek+EN4sgD9Kk93yHRMdzdlMFZ0SVJreXVwSzlKSDkwa3c9PQ==', '2025-08-17 14:54:50', '483.044.878'),
(20, 'Mariana Silva', 'mariana.silva@gmail.com', '/1RTxd5MSz8SwdIahN3/oWlQdDNPU2R4OVJLYnlBNXJvSm1PeWc9PQ==', '2025-08-17 22:38:08', '12345678901'),
(21, 'Carlos Souza', 'carlos.souza@gmail.com', '9BTLx/rglhv1zh24TOBkumEwakpBMExxTk9Lc1BBTGNLL0dFWFE9PQ==', '2025-08-17 22:38:08', '98765432100'),
(22, 'Ana Oliveira', 'ana.oliveira@gmail.com', 'u2RgyM5OBdKaRVQhGzfmpGlLTXNsYjZ0bXNMOGtPSUxNem9LWXc9PQ==', '2025-08-17 22:38:08', '45612378900'),
(23, 'João Pereira', 'joao.pereira@gmail.com', 'MscfVnlx2gHOqO+261yvGXJVSFhTaHFOcDhVaTBPNHVOZXlzeWc9PQ==', '2025-08-17 22:38:08', '78945612300'),
(24, 'Fernanda Costa', 'fernanda.costa@gmail.com', 'g6d604h5LzUnFr65alRwT25ybGw1UU1FRXhuYWpSc0pncFZvNnc9PQ==', '2025-08-17 22:38:08', '85274196300'),
(25, 'Rafael Gomes', 'rafael.gomes@gmail.com', 'ehXfWdERi+rgV7rA5it9LElTeVA1L0RvTTdadWZ2S1ZsWFROU3c9PQ==', '2025-08-17 22:38:08', '36925814700'),
(26, 'Beatriz Almeida', 'beatriz.almeida@gmail.com', 'Xmrdf2pf9tZgLnTqwYDrjExwUzB0QmNjeVdXUTF0UkFxRHNLbGc9PQ==', '2025-08-17 22:38:08', '74185296300'),
(27, 'Lucas Fernandes', 'lucas.fernandes@gmail.com', 'jGOHi0zJuNZ/kriCRvpsUTZyMnBqTFl3V3BJdHlLeGh6ZUdIaEE9PQ==', '2025-08-17 22:38:08', '15975348600'),
(28, 'Juliana Rocha', 'juliana.rocha@gmail.com', 'Eho53BSRm9jswsGI429VaVJvQXp2SXhjT2MzbmRady9RQ254cFE9PQ==', '2025-08-17 22:38:08', '25845678900'),
(29, 'Gabriel Lima', 'gabriel.lima@gmail.com', 'rSGrKC1dVxjGZpRXnmXS/U1uVTFZd3BYdkMzU3Z6VVVjWUtuR1E9PQ==', '2025-08-17 22:38:08', '32165498700');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chave` (`chave`);

--
-- Índices para tabela `doacoes`
--
ALTER TABLE `doacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `perguntas_quiz`
--
ALTER TABLE `perguntas_quiz`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `user_game`
--
ALTER TABLE `user_game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `user_quiz`
--
ALTER TABLE `user_quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cache`
--
ALTER TABLE `cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `doacoes`
--
ALTER TABLE `doacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perguntas_quiz`
--
ALTER TABLE `perguntas_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de tabela `user_game`
--
ALTER TABLE `user_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `user_quiz`
--
ALTER TABLE `user_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `doacoes`
--
ALTER TABLE `doacoes`
  ADD CONSTRAINT `doacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `user_game`
--
ALTER TABLE `user_game`
  ADD CONSTRAINT `user_game_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `user_quiz`
--
ALTER TABLE `user_quiz`
  ADD CONSTRAINT `user_quiz_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
