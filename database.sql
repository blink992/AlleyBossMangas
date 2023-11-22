-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 22-Out-2022 às 21:10
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `database`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `mangas`
--

DROP TABLE IF EXISTS `mangas`;
CREATE TABLE IF NOT EXISTS `mangas` (
  `Thumb` char(255) NOT NULL,
  `Titulo` varchar(5000) NOT NULL,
  `Autor` char(255) NOT NULL,
  `Sinopse` varchar(25000) NOT NULL,
  `Avaliacao` text NOT NULL,
  `Genero` varchar(5000) NOT NULL,
  `Nota` float NOT NULL,
  `Capitulos` char(255) NOT NULL,
  `Capitulo-Inicial` tinyint(10) NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `mangas`
--

INSERT INTO `mangas` (`Thumb`, `Titulo`, `Autor`, `Sinopse`, `Avaliacao`, `Genero`, `Nota`, `Capitulos`, `Capitulo-Inicial`, `ID`) VALUES
('Mangas/Overgeared (Remake)/Thumb/overgeared(remake).png', 'Overgeared (Remake)', 'Dong Wook Lee, Team Argo (Artista)', 'Shin Youngwoo tinha uma vida miserável como servente de pedreiro. Ele até teve que trabalhar no jogo de realidade virtual, “Satisfy”! Mas, a sorte em breve apareceria em sua vida sem esperanças. Seu personagem, ‘Grid’, descobre uma missão na Caverna do Fim do Norte e nesse local ele encontra o ‘Raro Livro de Pagma’ que o torna um jogador lendário…', 'Indisponível (Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Aventura/Jogos/Shounen/VRMMORPG', 9.53, 'Mangas\\Overgeared (Remake)\\Capitulos', 0, 1),
('Mangas\\Omniscient Reader\'s Viewpoint\\Thumb\\omniscient_readers_viewpoint.png', 'Omniscient Reader\'s Viewpoint', 'Singshong, Sleepy-C (Artista)', '“Esse é um desenrolar que eu já conheço”. No momento em que pensei isso, o mundo foi destruído e um novo universo surgiu. A nova vida de um leitor comum começa no mundo de uma novel, a novel que só ele terminou.', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Aventura/Drama/Fantasia/Sobrenatural/Jogos/Shounen', 8, 'Mangas\\Omniscient Reader\'s Viewpoint\\Capitulos', 0, 2),
('Mangas\\Boku no Hero Academia\\Thumb\\boku_no_hero_academia.png\r\n', 'Boku no Hero Academia', 'Kohei Horikoshi', 'Em um mundo onde 80% da população mundial possuem super poderes, o tímido estudante Midoriya Izuku teve a infelicidade de nascer sem poderes. Grande fã do sorridente All Might, o herói conhecido como o símbolo da paz, Izuku, sofre com a frustração de saber que jamais terá uma individualidade especial para que possa se tornar, assim como seu grande ídolo, em um defensor dos fracos e oprimidos.\r\n\r\nMesmo sofrendo bullying por seus amigos de escola, como o arrogante Katsuki, o garoto nunca abandonou o herói existente dentro de si. Gentil e generoso, ele está sempre pronto a ajudar quem precisa.\r\n\r\nPorém, um inesperado encontro irá mudar o destino de Izuku. Destino esse que o levará a ingressar no tão sonhado colégio U.A., instituição para onde os grandes heróis vão estudar e treinar. A partir daí, as cortinas de uma fantástica aventura repleta de personagens cativantes e temerosos vilões se abrem para o jovem Midoriya.\r\n', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Comédia/Escolar/Super Poderes/Shounen', 5, 'Mangas\\Boku no Hero Academia\\Capitulos', 1, 3),
('Mangas/Berserk/Thumb/berserk.png', 'Berserk', 'Kentaro Miura', 'Gatts é um sobrevivente que vaga pelo mundo à procura de respostas. Antigo membro do ext \"Bando dos Falcões\", um grupo mercenário de cavaleiros e guerreiros liderado por Griffith e Caska, Gatts se adentra na história que ganha corpo e emerge sob um ponto de vista totalmente imprevisível, a medida que os acontecimentos vão se completando. É uma obra dedicada à eterna luta do Catolicismo contra Paganismo...', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Adulto/Ação/Aventura/Demônios/Drama/Fantasia/Horror/Seinen/Sobrenatural', 10, 'Mangas\\Berserk\\Capitulos', -16, 5),
('Mangas\\Jujutsu Kaisen\\Thumb\\jujutsu_kaisen.png', 'Jujutsu Kaisen', 'Gege Akutami', 'Yuji é um gênio do atletismo, mas não tem interesse algum em ficar correndo em círculos. Ele é feliz como membro no Clube de Estudo de Fenômenos Psíquicos. Apesar de estar no clube apenas por diversão, tudo fica sério quando um espírito de verdade aparece na escola! A vida está prestes a se tornar muito interessante na Escola Sugisawa…\r\n\r\n', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Demônios/Escolar/Fantasia/Sobrenatural/Shounen', 10, 'Mangas\\Jujutsu Kaisen\\Capitulos', 0, 4),
('Mangas\\Solo Leveling\\Thumb\\solo_leveling.png', 'Solo Leveling', 'Chugong', 'Dez anos atrás, depois do \"Portal\" que conecta o mundo real com um mundo de montros se abriu, algumas pessoas comuns receberam o poder de caçar os monstros do portal. Eles são conhecidos como caçadores. Porém, nem todos os caçadores são fortes. Meu nome é Sung Jin-Woo, um caçador de rank E. Eu sou alguém que tem que arriscar a própria vida nas dungeons mais fracas, \"O mais fraco do mundo\". Sem ter nenhuma habilidade à disposição, eu mal consigo dinheiro nas dungeons de baixo nível... Ao menos até eu encontrar uma dungeon escondida com a maior dificuldade dentro do Rank D! No fim, enquanto aceitava minha morte, eu ganhei um novo poder!', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Aventura/Shounen/Jogos/Fantasia', 10, 'Mangas\\Solo Leveling\\Capitulos', 0, 6),
('Mangas/Tokyo Ghoul RE/Thumb/tokyo_ghoul_re.png', 'Tokyo Ghoul:re', 'Sui Ishida', 'Dois anos se passaram desde que a Comissão Contra Ghoul\'s (CCG) invadiu o Anteiku. Embora a atmosfera em Tóquio mudou drasticamente devido ao aumento da influência do CCG, ghouls continuam a representar um problema, mas eles começaram a tomar cuidado, especialmente a organização terrorista Árvore Aogiri, que reconhecem a crescente ameaça do CCG à sua existência.A criação de uma equipe especial, conhecido como o Esquadrão Quinx, pode fornecer ao CCG o impulso que precisam para exterminar os residentes indesejados de Tóquio. Como seres humanos que tenham sido submetidos a cirurgia, a fim de fazer uso das habilidades especiais dos ghouls, eles participam de operações para erradicar essas criaturas perigosas. O líder deste grupo, Haise Sasaki, é um, metade humano metade ghoul que foi treinado pelo famoso pesquisador de classe especial, Kishou Arima. No entanto, há mais neste jovem do que os olhos podem ver, como memórias desconhecidos arranham sua mente, lentamente, lembrando-o da pessoa que ele costumava ser!', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Drama/Horror/Psicológico/Seinen/Sobrenatural', 10, 'Mangas\\Tokyo Ghoul RE\\Capitulos', 0, 7),
('Mangas/Tokyo Ghoul/Thumb/tokyo_ghoul.png', 'Tokyo Ghoul', 'Sui Ishida', 'Pense numa história em que você é o protagonista. Provavelmente seria uma tragédia, não? Pois essa é a história de Kaneki... Estranhos assassinatos estão acontecendo em Tokyo. Devido a evidência líquida na cena, a polícia concluiu que os ataques são resultados de um \"comedor\" de um tipo ghoul. Kaneki é um jovem de 18 anos cursando a faculdade, apaixonado por romances japoneses, ele e seu amigo Hide, criam a teoria de que os ghouls estão imitando os humanos, por isso nunca foram vistos. Mau eles sabem que essa teoria pode ser verdade.', 'Indisponível \r\n(Você pode escrever uma avaliação pessoal e nos enviar - iremos escolher a avaliação mais bem feita para utilizar nesta seção)', 'Ação/Drama/Horror/Psicológico/Seinen/Sobrenatural', 10, 'Mangas\\Tokyo Ghoul\\Capitulos', 1, 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mangas_favoritos`
--

DROP TABLE IF EXISTS `mangas_favoritos`;
CREATE TABLE IF NOT EXISTS `mangas_favoritos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `mangas_favoritos`
--

INSERT INTO `mangas_favoritos` (`ID`) VALUES
(1),
(2),
(3),
(5),
(6),
(7);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
