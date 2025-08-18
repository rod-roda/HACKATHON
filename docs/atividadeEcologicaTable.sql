-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/08/2025 às 04:03
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

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
-- Estrutura para tabela `atividades_ecologicas`
--

CREATE TABLE `atividades_ecologicas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome_atividade` varchar(150) NOT NULL,
  `quantidade` decimal(10,2) DEFAULT 0.00,
  `carbono_emitido` decimal(10,4) DEFAULT 0.0000,
  `data_atividade` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atividades_ecologicas`
--

INSERT INTO `atividades_ecologicas` (`id`, `usuario_id`, `nome_atividade`, `quantidade`, `carbono_emitido`, `data_atividade`) VALUES
(1, 1, 'onibus', 1.00, 15.0000, '2025-08-09 03:00:00'),
(2, 1, 'onibus', 2.00, 30.0000, '2025-08-09 03:00:00'),
(3, 1, 'carro', 2.00, 60.0000, '2025-08-09 03:00:00'),
(5, 1, 'carro', 2.00, 0.0000, '2025-08-15 03:00:00'),
(6, 1, 'carro', 1.00, 44.0000, '0000-00-00 00:00:00'),
(7, 1, 'carro', 2.00, 0.0000, '2025-08-14 03:00:00'),
(8, 1, 'carro', 2.00, 0.0000, '2025-08-14 03:00:00'),
(9, 1, 'gas', 2.00, 4.0000, '2025-08-14 03:00:00'),
(10, 1, 'gas', 2.00, 4.0000, '2025-08-14 03:00:00'),
(11, 1, 'gas', 2.00, 4.0000, '2025-08-14 03:00:00'),
(12, 1, 'gas', 2.00, 4.0000, '2025-08-14 03:00:00'),
(13, 1, 'gas', 1.00, 2.0000, '2025-08-14 03:00:00'),
(14, 1, 'aviao', 2.00, 0.0000, '0000-00-00 00:00:00'),
(15, 1, 'aviao', 2.00, 0.8000, '0000-00-00 00:00:00'),
(16, 1, 'carro', 3.00, 0.0000, '2025-08-14 03:00:00'),
(17, 1, 'carro', 3.00, 0.0000, '2025-08-14 03:00:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atividades_ecologicas`
--
ALTER TABLE `atividades_ecologicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atividades_ecologicas`
--
ALTER TABLE `atividades_ecologicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atividades_ecologicas`
--
ALTER TABLE `atividades_ecologicas`
  ADD CONSTRAINT `atividades_ecologicas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;