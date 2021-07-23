-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.17-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela sistema.bond
CREATE TABLE IF NOT EXISTS `bond` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team` int(11) NOT NULL,
  `image` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.bond: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `bond` DISABLE KEYS */;
INSERT INTO `bond` (`id`, `team`, `image`, `sku`) VALUES
	(1, 2, 'https://s3.amazonaws.com/iderislab/2018/2021/2/27/2018_55ba4768-feb2-4c05-b029-1c7ff8c9554f_100x100.jpg', 'QUADRO-MOSAICO-PS014'),
	(2, 2, 'https://s3.amazonaws.com/iderislab/2018/2021/5/17/2018_daa9d717-4f2e-40c7-89f1-c657bde9316b_95x100.jpg', 'QUADRO-MOSAICO-3P-PERS-1'),
	(3, 2, 'https://s3.amazonaws.com/iderislab/2018/2021/5/17/2018_09b0ba26-4116-47be-b4fe-e9183b1126b1_100x92.jpg', 'QUADRO-MOSAICO-PERS-2'),
	(7, 2, 'https://s3.amazonaws.com/iderislab/2018/2021/2/27/2018_876eb8b3-ad12-4c12-ba17-96b26e963535_100x100.jpg', 'QUADRO-MOSAICO-PS013'),
	(8, 2, 'https://s3.amazonaws.com/iderislab/2018/2021/2/27/2018_f9876372-f47a-4047-9709-f114437ee081_100x100.jpg', 'QUADRO-MOSAICO-PS011');
/*!40000 ALTER TABLE `bond` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.contributor
CREATE TABLE IF NOT EXISTS `contributor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `office` int(11) NOT NULL,
  `permission` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.contributor: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `contributor` DISABLE KEYS */;
INSERT INTO `contributor` (`id`, `user`, `name`, `image`, `password`, `office`, `permission`) VALUES
	(2, 'gabriel.flambo', 'Gabriel Flambo', 'public/admin/images/images/2021/05/gabriel-flambo.jpg', '$2y$10$oFGDpqt1lU/qZYVJBnewNOziVfL8i4SCMtXKATsJ917CmS9QPmqMG', 3, '1,2,3,4,5,6,7,8,9,10,11'),
	(3, 'jessica', 'Jessica Barros Bezerra', 'public/images/avatar.jpg', '$2y$10$K.fWyUxn7lbdYCjhla9noebLGLXXyhNROlNG1oeF8UFvHvKfJy23u', 1, '2,10,11');
/*!40000 ALTER TABLE `contributor` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.image
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urlImagem` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `imagemBase64` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.image: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.info
CREATE TABLE IF NOT EXISTS `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `descricaoLonga` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.info: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `info` DISABLE KEYS */;
/*!40000 ALTER TABLE `info` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valorVenda` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valorCusto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `peso` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `altura` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `largura` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comprimento` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pesoEmbalagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alturaEmbalagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `larguraEmbalagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comprimentoEmbalagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `categoriaIdIderis` int(11) NOT NULL,
  `subCategoriaIdIderis` int(11) NOT NULL,
  `marcaIdIderis` int(11) NOT NULL,
  `departamentoIdIderis` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.product: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `titulo`, `valorVenda`, `valorCusto`, `sku`, `peso`, `altura`, `largura`, `comprimento`, `pesoEmbalagem`, `alturaEmbalagem`, `larguraEmbalagem`, `comprimentoEmbalagem`, `categoriaIdIderis`, `subCategoriaIdIderis`, `marcaIdIderis`, `departamentoIdIderis`) VALUES
	(5, 'Faixa De Papel Adesiva Parede Decorativa Vingadores', '', '', 'MLB1530054503_56435572380', '110', '', '', '', '', '', '', '', 0, 0, 0, 0);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.record
CREATE TABLE IF NOT EXISTS `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request` int(11) NOT NULL,
  `note` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `currentSector` int(11) DEFAULT NULL,
  `transferredSector` int(11) DEFAULT NULL,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.record: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `record` DISABLE KEYS */;
INSERT INTO `record` (`id`, `request`, `note`, `currentSector`, `transferredSector`, `user`, `date`) VALUES
	(14, 38267, '', 1218, 1118, 2, '2021-05-28 19:28:23'),
	(15, 38267, '', 1118, 1009, 2, '2021-05-28 19:29:21');
/*!40000 ALTER TABLE `record` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.stock
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `amount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.stock: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` (`id`, `product`, `type`, `amount`, `price`, `note`, `date`) VALUES
	(1, 'QUADRO-MOSAICO-PS014', 1, '4', '1,23', '', '2021-06-18 16:33:35'),
	(3, 'QUADRO-MOSAICO-PS012', 1, '50', '49,99', 'Chegaram semana passada', '2021-06-18 16:38:45'),
	(4, 'QUADRO-MOSAICO-PS011', 2, '50', '49,99', 'Chegaram semana passada', '2021-06-18 16:38:45'),
	(5, 'QUADRO-MOSAICO-PS014', 2, '20', '2,23', '', '2021-06-17 17:00:35'),
	(6, 'QUADRO-MOSAICO-PS014', 2, '20', '19,99', '', '2021-06-18 17:58:56'),
	(7, 'QUADRO-MOSAICO-PS011', 2, '20', '19,99', '', '2021-06-18 17:58:56'),
	(8, 'QUADRO-MOSAICO-PS013', 2, '20', '19,99', '', '2021-06-18 17:58:56'),
	(9, 'QUADRO-MOSAICO-PS014', 2, '20', '19,99', '', '2021-06-18 17:58:56'),
	(10, 'QUADRO-MOSAICO-PS014', 2, '20', '19,99', '', '2021-06-18 17:58:56'),
	(11, 'QUADRO-MOSAICO-PS011', 2, '20', '19,99', '', '2021-06-18 17:58:56'),
	(15, 'QUADRO-MOSAICO-PS014', 1, '4', '23,12', '', '2021-06-21 10:47:56');
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.team
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sectorP` int(11) NOT NULL,
  `sectorS` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.team: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` (`id`, `name`, `sectorP`, `sectorS`) VALUES
	(1, 'Personalizados', 1, 3),
	(2, 'Quadros', 4, 3);
/*!40000 ALTER TABLE `team` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.user: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `user`, `password`) VALUES
	(1, 'admin', '$2y$10$0P3MDii2V7xYxk8R88GBFOqHID5TFwLlGxcURQ9vk5P11ed5nDOq2');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Copiando estrutura para tabela sistema.variations
CREATE TABLE IF NOT EXISTS `variations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `skuVariacao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantidadeVariacao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nomeAtributo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valorAtributo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela sistema.variations: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `variations` DISABLE KEYS */;
/*!40000 ALTER TABLE `variations` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
