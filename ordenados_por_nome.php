<?php

  if(!isset($_SESSION)){
    session_start();
}

include "cabecalho.php";
include "responsividade.php";


ini_set('default_charset','UTF-8');


$consulta = "SELECT * FROM mangas WHERE Titulo LIKE '%' ORDER BY Titulo";

if(isset($_POST['letra'])) {
    $filtro = $_POST['letra']."%";
    $consulta = "SELECT * FROM mangas WHERE Titulo LIKE '$filtro' ORDER BY Titulo";

}
else {
    $filtro = "";
}
$string_conc = "";
if(isset($_POST['aplicar_filtros']) and ($_POST['aplicar_filtros'] !== '')) {
    if(isset($_COOKIE['generos_selecionados']) and ($_COOKIE['generos_selecionados'] !== '')) {
        $genero_conc = utf8_decode($_COOKIE['generos_selecionados']);
        $array_generos = explode('%', $genero_conc);
        foreach($array_generos as $genero_str) {
            $string_conc .= " AND Genero Like '%$genero_str%'";
        }
        $consulta = "SELECT * FROM mangas WHERE Genero LIKE '%'$string_conc";
    }
}
else {
    $genero_conc = "";
}

$limpar_filtros = filter_input(INPUT_POST, 'limpar');
if($limpar_filtros) {
    setcookie('generos_selecionados', '',  time() + 3600);
}

$mangas = $mysqli->query($consulta) or die($mysqli->error);
$ranqueados = $mysqli->query($consulta) or die($mysqli->error);
$mais_lidos = $mysqli->query($consulta) or die($mysqli->error);

$consulta_favoritos = "SELECT * FROM mangas_favoritos";
$mangas_fav = $mysqli->query($consulta_favoritos) or die($mysqli->error);
$qtd_mangas_fav = $mangas_fav->num_rows;

$generos = [
    "ação", "realidade-virtual", "shounen", "shoujo", "ecchi", "hentai", "seinen","sobrenatural", "isekai","Harem",
        "aventura", "jogos", "comédia", "demônios", "escolar"
    ];
$alfabeto = [
    "A", "B", "C","D", "E", "F","G", "H","I", "J", "K","L", "M", "N","O", "P", "Q", "R", "S", "T", "U", "V", "W",
    "X", "Y", "Z"
];

$qtd_mangas = $mangas->num_rows;

if(isset($_POST['id_manga_favoritado'])){
    $id_manga_fav = $_POST['id_manga_favoritado'];
    $query = "INSERT INTO `mangas_favoritos`(`ID`) VALUES ($id_manga_fav)";
    mysqli_query($mysqli, $query);
}

$colocacao_do_manga = 1;


?>


<body style="width:100%;height:100%">

    <!-- Imagem de fundo  -->
    <div style="z-index:0" class="background-box background-image"></div>

    <!-- INCLUSÃO DA NAVBAR  -->
    <?php include "barra_navegação.php" ?>

    <!-- CONTAINER VERTICAL PRINCIPAL  -->
    <div style="width: 100%;height:max-content;display:flex;justify-content:center;flex-direction: column;">
        
        <!-- MENU RANQUEADO  -->
        <div style="order:10;padding-top:10px;display:flex;justify-content:center;flex-direction:column" class="menu-ranqueado-pagina-navbar">
            <!-- Menu colapsável de filtros por nome -->
            <div style="top:0px;order:1" class="filter-panel">
                <ul style="border-color: rgba(0,0,0,0);position:relative;" class="collection-item collapsible z-depth-0">
                    <li>
                        <button type="button" style="width: 100%;height: 40px;left:0px;border-radius:30px;border-top-left-radius: 80px;background-color:rgba(44, 49, 65, 0.8);display:flex;flex-direction:row;justify-content:center" class="btn botão-exp-filtros  hoverable collapsible-header">
                                <i style="position: relative;order:10;left:10px" class="material-icons">arrow_drop_down</i>
                                <span style="position: relative;order:1;font-size:inherit;align-self:center" class="basic-text">Filtrar por nome:</span>
                        </button>
                        <!-- Seção com os botões dos filtros -->
                        <div style="align-self:center;position:relative;background-color: transparent;" class="menu-filtros collapsible-body">
                            <ul style="display:flex;flex-direction:row;flex-wrap:wrap;padding-bottom: 30px;padding-left:15px;padding-right:15px;padding-top:10px" class="center card-content">
                                <?php foreach($alfabeto as $letra) : ?>
                                    <form action="#" method="POST">
                                        <input type="hidden" name="letra" value="<?= $letra ?>">
                                        <button type="submit" style="width:<?= $letra.ob_get_length() ?>px;background-color: rgba(57, 74, 109, 0.8);margin-top:8px" class="center scale-effect btn waves-effect botoes-filtro hoverable"><span style="font-size:var(--font-size-botao-filtro);" class="center basic-text"><?php echo $letra ?></span></button>                                
                                    </form>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
         <!-- Menu colapsável de filtros por gênero -->
            <div style="top:0px;order:5" class="filter-panel">
                <ul style="border-color: rgba(0,0,0,0);position:relative;" class="collection-item collapsible z-depth-0">
                    <li>
                        <button type="button" style="width: 100%;height: 40px;left:0px;border-radius:12px;background-color:rgba(44, 49, 65, 0.8);display:flex;flex-direction:row;justify-content:center" class="btn botão-exp-filtros  hoverable collapsible-header">
                                <i style="position: relative;order:10;left:10px" class="material-icons">arrow_drop_down</i>
                                <span style="position: relative;order:1;font-size:inherit;align-self:center" class="basic-text">Filtrar por Gênero:</span>
                        </button>
                        <!-- Seção com os botões dos filtros -->
                        <div style="align-self:center;position:relative;background-color: transparent;" class="menu-filtros collapsible-body">
                            <ul style="display:flex;flex-direction:row;flex-wrap:wrap;padding-bottom: 30px;padding-left:15px;padding-right:15px;padding-top:10px" class="center card-content">
                                <?php foreach($generos as $genero) : ?>
                                    <form action="#" method="POST">
                                        <input type="hidden" name="letra" value="<?= $genero ?>">
                                        <button id="button_genero<?= $genero ?>" onclick="generoFunc('<?= $genero ?>')" type="button" style="width:<?= $genero.ob_get_length() ?>px;margin-top:10px;background-color: rgba(57, 74, 109, 0.8);" class="center scale-effect btn botoes-filtro hoverable"><span style="font-size:var(--font-size-botao-filtro);" class="center basic-text"><?php echo $genero ?></span></button>
                                    </form>
                                <?php endforeach ?>
                            </ul>
                            <div style="display:flex;justify-content:center;width:100%;height:max-content;position:relative">
                                <form action="#" method="POST">
                                    <input type="hidden" name="aplicar_filtros" value="true">
                                    <button type="submit" style="align-self:center;position:relative;list-style:none;box-shadow:none;background-color: rgba(44, 49, 65, 0.8);border: solid 3px rgba(56, 63, 80, 0.8);color:rgb(200, 235, 235); padding:6px;padding-left:14px;padding-right:14px;border-radius:13px" class="hoverable scale-effect"><span style="font-size:120%;" class="center basic-text">Aplicar filtros</span> </button>
                                </form>
                                <form id="aplicar_filtros" action="expanded_ordenados_por_nome.php" method="POST">
                                    <input type="hidden" name="aplicar_filtros" value="true">
                                </form>
                            </div>
       
                        </div>
                    </li>
                </ul>
            </div>

            <div style="order:10" class="z-depth-0">
                <li style="list-style: none;width: 100%;height: 100%;position: relative;">
                    <?php if(isset($array_generos)) { ?>
                        <div style="position:relative;display:flex;justify-content:center;width:100%;height:max-content;flex-direction:row;flex-wrap:wrap">
                            <?php foreach($array_generos as $genero_str) { 
                            if($genero_str !== "") {
                            ?>

                            <span style="cursor:default;align-self: center;position:relative;background-color:rgba(94, 99, 124, 0.8);border-radius:20px;padding:6px;padding-right:12px;padding-left:12px;font-size:115%;margin-right:3px;margin-left:3px;margin-top:10px;" class="basic-text"><?= $genero_str ?></span>
                            
                            <?php }} ?>
                        </div>
                        <div style="margin-bottom:35px;cursor:pointer;width: 100%;height:max-content;position:relative;display:flex;justify-content:center;">
                            <a href="" onclick="limparFiltros()" style="padding:5px;padding-right:12px;padding-left:12px;border-radius:20px;display:flex;height:max-content;width:max-content;align-self:center;background-color:rgba(40, 47, 74, 0.8);margin-top:16px;" class="scale-effect">
                                <span class="basic-text">Limpar filtros</span>
                                <i style="color:rgb(200, 235, 235);margin-left:7px" class="fa-solid fa-xmark center"></i>
                            </a>
                        </div>
                        <?php } ?>

                        <div>
                            <?php 
                                $array_mangas = array();
                                ?>
                            <?php while($manga = $ranqueados->fetch_array()) {
                                $array_mangas[] = $manga['ID'];
                            } 
                            ?>
                            <?php 
                                $index = 0; 
                                foreach($array_mangas as $manga_id) {
                                    if($index < 15){
                                        $query = "SELECT * FROM mangas WHERE ID LIKE $manga_id";    
                                        $manga_query = mysqli_query($mysqli, $query);
                                        $manga = $manga_query->fetch_array();
                                        $index++;
                                        $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
                                        $array_capitulos = scandir($caminho_capitulos);
                                        $qtd_caps = count($array_capitulos)-2;
                            ?>

                                <div style="width:100%;height:100%;margin-bottom:0px;margin-top:0px;box-shadow:none" class="card transparent">


                                    <!-- Opções do mangá -->
                                    <div id="fixed-btn-pagina-navbar" style="z-index:4" class="floating-btn-pagina-navbar">
                                        <a class="btn-large btn-floating">
                                            <i style="color:inherit" onclick="showOptionsManga(<?= $manga['ID'] ?>)" class="fa-sharp fa-solid fa-ellipsis"></i>
                                        </a>

                                        <div id="opcoes_manga<?= $manga['ID']?>" style="width:max-content;height:58px;right:60px;top:37px;display:flex;flex-direction:row;z-index:-5;opacity:0;position:absolute;transition: opacity 0.5s, top 0.5s, transform 0.8s;transform: scale(0.5)" >
                                           
                                           <!-- BOTÃO DE FAVORITAR MANGÁ  -->
                                            <div style="list-style: none;margin-left:12px">
                                                <a onclick="addFavoritos(<?= $manga['ID'] ?>, 'ordenados_por_nome')" style="background-color: rgb(18, 45, 70)" id="favoritar-pagina-manga" class="btn btn-floating hoverable">
                                                    <i id="icon_favorite<?= $manga['ID'] ?>ordenados_por_nome" class="material-icons">
                                                    <?php        
                                                                                            
                                                        if(isset($_SESSION['ID'])) {
                                                            $nome = $_SESSION['nome'];
                                                            $sql_code = "SELECT favoritos FROM $nome WHERE favoritos LIKE '".$manga["ID"]."'";
                                                            $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                                                            
                                                            if($query->num_rows > 0){
                                                                ?> favorite <?php
                                                                }
                                                                else {
                                                                    ?> favorite_border <?php
                                                                }
                                                            }
                                                            else {
                                                            if(isset($_COOKIE['array_favoritos'])) {
                                                                $array_favoritos = unserialize($_COOKIE['array_favoritos']);
                                                                if( in_array( $manga['ID'], $array_favoritos )) {
                                                                    ?> favorite <?php
                                                                }
                                                                else {
                                                                    ?> favorite_border <?php
                                                                }
                                                            }
                                                            else {
                                                                ?> favorite_border <?php
                                                            }
                                                        }
                                                        
                                                    ?>
                                                    </i>
                                                </a>  
                                            </div>
                                        </div>
                                    </div>



                                    <div style="border:double rgba(73, 84, 97, 0.6);border-width: 4px;margin:0px;margin-top:7px" class="painel-manga-ranqueado-pagina-navbar card">

                                        <div style="display: flex;flex-direction:column;order:15;flex-grow:1;width:100%;height:100%" class="">

                                            <div style="display:flex;width:100%;overflow:visible" class="container-infos-pagina-navbar ">
                                                <!-- Thumb do mangá  -->
                                                <div style="width:max-content" class="container-imagem-manga-pagina-navbar ">
                                                    <form style="width: 100%; height:100%;margin-bottom:inherit;position:absolute" action="pagina_manga.php" method="POST">
                                                        <button style="cursor:pointer;border-radius:15px;border-bottom-right-radius:30px;border-top-left-radius:30px;" type="int" name="dados" value="<?= $manga['ID'] ?>" href="pagina_manga.php" class="cat-manga-ranqueado-pagina-navbar transparent">
                                                            <img class="scale-effect-small" src="<?= $manga['Thumb'] ?>" alt="Thumb">
                                                        </button>
                                                    </form>
                                                </div>

                                                <div style="flex-direction:column;" class="container-vertical-A ">

                                                    <!-- Título do mangá  -->
                                                    <div style="padding-right: 20px;flex-direction:column;" class="container-titulo-pagina-navbar ">
                                                        <form style="width: 100%; height: 100%;" action="pagina_manga.php" method="POST">
                                                            <button  type="int" name="dados" value="<?= $manga['ID'] ?>" style="border-style: none;padding: 0px;position: relative;display: flex;flex-grow:1" class="transparent"><span style="text-align:left;align-self:center;display: flex;position: relative;cursor: pointer;flex-grow:1" class="titulo-manga-pagina-navbar basic-text"><?php echo $manga["Titulo"] ?></span></button>
                                                        </form>
                                                        <div style="order:5;position: relative;left:0px;top: 0px;display: flex;flex-direction: row;height:max-content;flex-grow: 1" class="">
                                                            <span style="text-align:left;position: relative;" class="titulo-autor-pagina-navbar basic-text"><?= $manga['Autor'] ?></span>
                                                        </div>
                                                    </div>

                                                    <!-- BOTÕES DE GENERO DO MANGA  -->
                                                    <div style="order:10;position:relative;flex-wrap:wrap;padding:0px;flex-grow:initial;padding-top:5px" class="container-generos-pagina-navbar ">
                                                        <?php $array_generos_pagina_manga = explode("/",$manga['Genero']) ?>
                                                        <?php $i=0 ?>
                                                        <?php foreach($array_generos_pagina_manga as $genero_manga) : ?>
                                                                <a href="#">
                                                                    <div style="background-color: <?php if(($genero_manga == 'Manhwa') or ($genero_manga == 'Manhua') or ($genero_manga == 'Mangá')){echo '#91c6f4';} ?>" class="hoverable scale-effect botão-genero-pagina-navbar">
                                                                        <span style="align-self:center;font-size:inherit;color:inherit;letter-spacing: 0.6px;" class="basic-text"><?= $genero_manga ?></span>
                                                                    </div>
                                                                </a>
                                                        <?php endforeach ?>
                                                    </div>

                                                </div>

                                            </div>

                                            <!-- BOTÕES INFOS  -->
                                            <div class="sinopse-e-infos-pagina-navbar">

                                                <div class="infos-pagina-navbar hoverable">
													<!-- Visualizacoes do mangá  -->
													<div id="visualizacoes_pagina_navbar" style="position:relative;order:1;margin-left:0px;left: 0px;" class="pontuacao-manga-pagina-navbar">
														<i style="color:rgb(211, 255, 255);order:1;font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
														<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;"><?php echo number_format($manga["Visualizacoes"], 0, ',', '.') ?></text>
													</div>
													<!-- Nota do mangá  -->
													<div style="position:relative;order:1;margin-left:15px;left: 0px;" class="pontuacao-manga-pagina-navbar">
														<i style="color:rgb(211, 255, 255);order:1;font-size: inherit;align-self: center;" class="material-icons">star</i>
														<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;"><?php echo number_format($manga["Nota"], 2, ',', '.') ?></text>
													</div>
													<!-- Quantidade de caps do mangá  -->
													<div style="position:relative;order:1;margin-left:15px;left: 0px;" class="pontuacao-manga-pagina-navbar">
														<i style="color:rgb(211, 255, 255);order:5;font-size: inherit;align-self: center;" class="material-icons">storage</i>
														<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;"><?php echo $qtd_caps ?></text>
													</div>

                                                </div>

                                                <!-- Botão Sinopse  -->
                                                <button style="background-color: rgb(25, 56, 85);position:relative;order:10;margin-bottom: 0px;" class="btn sinopse-button-pagina-navbar scale-effect hoverable"><span class="activator basic-text">Sinopse</span></button>
                                            </div>
                                        </div>

                                        <!-- card-reveal que carregará a sinopse do mangá -->
                                        <div style="z-index:5;overflow:visible;position:absolute;background-color: rgb(35, 44, 54);width: 100%; height: 100%;" class="card-reveal">

                                            <!-- Botão Voltar  -->
                                            <i style="color:white;position:absolute;font-size:var(--fonte-voltar-pagina-navbar);" class="material-icons botão-voltar-sinopse-pagina-navbar card-title">keyboard_return</i>
                                            <span style="color:white;position:absolute;font-size: var(--fonte-texto-voltar-pagina-navbar);" class="texto-voltar-sinopse-pagina-navbar basic-text card-title">Voltar</span>

                                            <!-- Descrição  -->
                                            <div style="border-radius:18px;position:absolute;background-color:rgb(54, 73, 92);" class="center descricao-manga"><text style="word-spacing:inherit;letter-spacing:inherit;font-size:inherit;margin:inherit;color:white;left:5%;right:5%;top:3%;position:absolute;font-family:'Yanone Kaffeesatz';" class="center"><?php echo $manga["Sinopse"] ?></text></div>

                                        </div>

                                    </div>
                                </div>
                            <script>
                                var lenght_screen = screen.width;
                                if(lenght_screen < 350){
                                    var elemento = document.querySelector("#visualizacoes_pagina_navbar");
                                    elemento.parentNode.removeChild(elemento);
                                }
                            </script>
                            <?php }} ?>

                        </div>
                        <script>
                            function showOptionsManga(mangaTitulo) {
                                var opcoesManga = document.getElementById("opcoes_manga" + mangaTitulo);
                                if(opcoesManga.style.opacity <= "0") {
                                    opcoesManga.style.opacity = "1";
                                    opcoesManga.style.zIndex = "5";
                                    opcoesManga.style.top =  10 +"px";
                                    opcoesManga.style.transform = "scale("+1+")";
                                }
                                else {
                                    opcoesManga.style.opacity = "0";
                                    opcoesManga.style.zIndex = "-5";
                                    opcoesManga.style.top =  37 +"px";
                                    opcoesManga.style.transform = "scale("+0.5+")";
                                }
                            }


                        </script>
                </li>
            </div>
            <div id="expanded_list" style="order:15;display: flex;flex-direction:column;justify-content:space-between;position:relative" class="expanded_ordenados_por_nome">
                            
            </div>
            <?php
        echo '<i id=\'btn-expand-list\' onclick=\'expand_list("expanded_ordenados_por_nome.php", "'.$filtro.'", "'.$genero_conc.'")\' style=\'order:20;margin:18px;cursor: pointer;color:rgba(146, 163, 197, 1);font-size:165%;align-self:center;position:relative;width:98%\' class=\'center fa-solid fa-chevron-down\'></i>';
            ?>
        </div>
    </div>
    <!-- INCLUSÃO DAS IMPORTAÇÕES DO JAVASCRIPT  -->
    <?php include "java_import.php" ?>
</body>
</html>
