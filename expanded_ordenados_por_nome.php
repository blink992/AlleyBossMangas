<?php
include "conexao.php";
include "responsividade.php";



ini_set('default_charset','UTF-8');



$filtro = filter_input(INPUT_POST, 'letraFiltro', FILTER_SANITIZE_STRING);
if($filtro == "") {
    $consulta = "SELECT * FROM mangas WHERE Titulo LIKE '%' ORDER BY Titulo";
}
else {
    $consulta = "SELECT * FROM mangas WHERE Titulo LIKE '%$filtro%' ORDER BY Titulo";
}

$genero_conc = filter_input(INPUT_POST, 'generoFiltro',  FILTER_SANITIZE_STRING);
$string_conc = "";

if($genero_conc !== '') {
    $array_generos = explode('%', $genero_conc);
    foreach($array_generos as $genero_str) {
        $string_conc .= " AND Genero Like '%$genero_str%'";
    }
    $consulta = "SELECT * FROM mangas WHERE Genero LIKE '%'$string_conc";
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

$qtd_mangas = $mangas->num_rows;

if(isset($_POST['id_manga_favoritado'])){
    $id_manga_fav = $_POST['id_manga_favoritado'];
    $query = "INSERT INTO `mangas_favoritos`(`ID`) VALUES ($id_manga_fav)";
    mysqli_query($mysqli, $query);
}

$array_ordenado = array();

?>
    <?php
        $dados = filter_input(INPUT_POST, 'index_reset', FILTER_SANITIZE_NUMBER_INT);
        $index = 0;
        while(($manga = $ranqueados->fetch_array()) and ($index < $dados)) {

            $index++;
            $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
            $i=1;
            $array_capitulos = scandir($caminho_capitulos);
            $qtd_caps = count($array_capitulos)-2;
            
            if($index > 15){

                $id_manga = $manga['ID']."expanded_ordenados_por_nome";
            
                if(isset($_SESSION['ID'])) {
                    $nome = $_SESSION['nome'];
                    $sql_code = "SELECT favoritos FROM $nome WHERE favoritos LIKE '".$manga["ID"]."'";
                    $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                    
                    if($query->num_rows > 0){
                        $icon = 'favorite';
                        }
                        else {
                            $icon = 'favorite_border';
                        }
                    }
                    else {
                    if(isset($_COOKIE['array_favoritos'])) {
                        $array_favoritos = unserialize($_COOKIE['array_favoritos']);
                        if( in_array( $manga['ID'], $array_favoritos )) {
                            $icon = 'favorite';
                        }
                        else {
                            $icon = 'favorite_border';
                        }
                    }
                    else {
                        $icon = 'favorite_border';
                    }
                }
                
            
            echo '<div id="ex" style="width:100%;height:100%;margin-bottom:0px;margin-top:7px;box-shadow:none" class="card transparent">

            <!-- Opções do mangá -->
            <div style="z-index:4" class="floating-btn-pagina-navbar">
                <a class="btn-large btn-floating">
                    <i style="color:inherit" onclick="showOptionsManga('.$manga['ID'].')" class="fa-sharp fa-solid fa-ellipsis"></i>
                </a>

                <div id="opcoes_manga'.$manga['ID'].'" style="width:max-content;height:58px;right:60px;top:37px;display:flex;flex-direction:row;z-index:-5;opacity:0;position:absolute;transition: opacity 0.5s, top 0.5s, transform 0.8s;transform: scale(0.5)">
                   
                    <!-- BOTÃO DE FAVORITAR MANGÁ  -->
                    <div style="list-style: none; margin-left:12px">
                        <a onclick="addFavoritos('.$manga['ID'].','."'expanded_ordenados_por_nome'".')" style="background-color: rgb(18, 45, 70)" id="favoritar-pagina-manga" class="btn btn-floating hoverable">
                            <i id="icon_favorite'. $manga['ID'].'expanded_ordenados_por_nome" class="material-icons">
                                '.$icon.'
                            </i>
                        </a>  
                    </div>
                </div>
            </div>



            <div style="border:double rgba(73, 84, 97, 0.6);border-width: 4px;margin:0px;" class="painel-manga-ranqueado-pagina-navbar card">

                    <div style="display: flex;flex-direction:column;order:15;flex-grow:1;width:100%;height:100%">

                        
                        <div style="display:flex;width:100%;overflow:visible" class="container-infos-pagina-navbar ">
                            <!-- Thumb do mangá  -->
                            <div style="width:max-content" class="container-imagem-manga-pagina-navbar ">

                                <form style="width: 100%; height:100%;margin-bottom:inherit;position:absolute" action="pagina_manga.php" method="POST">
                                    <button style="cursor:pointer;border-radius:15px;border-bottom-right-radius:30px;border-top-left-radius:30px;" type="int" name="dados" value="'. $manga['ID'] .'" href="pagina_manga.php" class="cat-manga-ranqueado-pagina-navbar transparent">
                                        <img class="scale-effect-small" src="'.$manga['Thumb'] .'" alt="Thumb">
                                    </button>
                                </form>
                            </div>

                            <div style="flex-direction:column;" class="container-vertical-A ">

                                <!-- Título do mangá  -->
                                <div style="padding-right: 20px;flex-direction:column;" class="container-titulo-pagina-navbar ">
                                    <form style="width: 100%; height: 100%;" action="pagina_manga.php" method="POST">
                                        <button  type="int" name="dados" value="'. $manga['ID'] .'" style="border-style: none;padding: 0px;position: relative;display: flex;flex-grow:1" class="transparent"><title style="text-align:left;align-self:center;display: flex;position: relative;cursor: pointer;flex-grow:1" class="titulo-manga-pagina-navbar">'. $manga["Titulo"] .'</title></button>
                                    </form>
                                    <div style="order:5;position: relative;left:0px;top: 0px;display: flex;flex-direction: row;height:max-content;flex-grow: 1" class="">
                                        <title style="text-align:left;position: relative;" class="titulo-autor-pagina-navbar">'. $manga['Autor'] .'</title>
                                    </div>
                                </div>

                                <!-- BOTÕES DE GENERO DO MANGA  -->
                                <div style="order:10;position:relative;padding:0px;flex-grow:initial;padding-top:5px" class="container-generos-pagina-navbar ">';
                                    $array_generos_pagina_manga = explode("/",$manga['Genero']);
                                    $i = 0;
                                    foreach($array_generos_pagina_manga as $genero_manga) {
                                        $i++;
                                        if($i < 5) {

                                            echo '<a href="#">
                                                <div style="background-color: '; if(($genero_manga == 'Manhwa') or ($genero_manga == 'Manhua') or ($genero_manga == 'Mangá')){echo '#91c6f4';} echo '" class="hoverable scale-effect botão-genero-pagina-navbar">
                                                    <title style="align-self:center;font-size:inherit;font-weight:inherit;color:inherit;letter-spacing: 0.6px;">'. $genero_manga .'</title>
                                                </div>
                                            </a>';
                                        }   
                                    }
                                echo '</div>


                            </div>

                        </div>

                        <!-- BOTÕES INFOS  -->
                        <div id="ex" class="sinopse-e-infos-pagina-navbar">

                            <div class="infos-pagina-navbar hoverable">
                                <!-- Visualizacoes do mangá  -->
                                <div id="visualizacoes_pagina_navbar" style="position:relative;order:1;margin-left:0px;left: 0px;" class="pontuacao-manga-pagina-navbar">
                                    <i style="color:rgb(211, 255, 255);order:1;font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
                                    <text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. $manga["Visualizacoes"] .'</text>
                                </div>
                                <!-- Nota do mangá -->
                                <div style="position:relative;order:1;margin-left:15px;left: 0px;" class="pontuacao-manga-pagina-navbar">
                                    <i style="color:rgb(211, 255, 255);order:1;font-size: inherit;align-self: center;" class="material-icons">star</i>
                                    <text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. number_format($manga["Nota"], 2, '.', "") .'</text>
                                </div>
                                <!-- Quantidade de caps do mangá  -->
                                <div style="position:relative;order:1;margin-left:15px;left: 0px;" class="pontuacao-manga-pagina-navbar">
                                    <i style="color:rgb(211, 255, 255);order:5;font-size: inherit;align-self: center;" class="material-icons">storage</i>
                                    <text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. $qtd_caps .'</text>
                                </div>
                            </div>

                            <!-- Botão Sinopse  -->
                            <button style="background-color: rgb(25, 56, 85);position:relative;order:10;margin-bottom: 0px;" class="btn sinopse-button-pagina-navbar scale-effect hoverable"><title class="activator">Sinopse</title></button>
                        </div>
                    </div>

                    <!-- card-reveal que carregará a sinopse do mangá -->
                    <div style="z-index:5;overflow:visible;position:absolute;background-color: rgb(35, 44, 54);width: 100%; height: 100%;" class="card-reveal">

                        <!-- Botão Voltar  -->
                        <i style="color:white;position:absolute;font-size:var(--fonte-voltar-pagina-navbar);" class="material-icons botão-voltar-sinopse-pagina-navbar card-title">keyboard_return</i>
                        <title style="color:white;position:absolute;font-size: var(--fonte-texto-voltar-pagina-navbar);" class="texto-voltar-sinopse-pagina-navbar card-title">Voltar</title>

                        <!-- Descrição  -->
                        <div style="border-radius:18px;position:absolute;background-color:rgb(54, 73, 92);" class="center descricao-manga"><text style="word-spacing:inherit;letter-spacing:inherit;font-size:inherit;margin:inherit;color:white;left:5%;right:5%;top:3%;position:absolute;font-family:"Yanone Kaffeesatz";" class="center">'. $manga["Sinopse"] .'</text></div>

                    </div>

                </div>
            </div>
            <script>
            function showOptionsManga(mangaTitulo) {
                var opcoesManga = document.getElementById("opcoes_manga" + mangaTitulo);
                
                if(opcoesManga.style.opacity <= "0") {
                    opcoesManga.style.opacity = "1";
                    opcoesManga.style.zIndex = "5";
                    opcoesManga.style.top =  20 +"px";
                    opcoesManga.style.transform = "scale("+1+")";
                }
                else {
                    opcoesManga.style.opacity = "0";
                    opcoesManga.style.zIndex = "-5";
                    opcoesManga.style.top =  37 +"px";
                    opcoesManga.style.transform = "scale("+0.5+")";
                }
            }
     
            function addFavoritos(idManga, pagina) {
                var elem = document.getElementById("icon_favorite" + idManga + pagina);
                if(elem.innerHTML == "favorite"){
                    elem.innerHTML = "favorite_border";
                    var dados = {
                        id_manga_favoritado : idManga,
                        is_favorited : "false"
                    }
                }
                else {
                    elem.innerHTML = "favorite";
                    var dados = {
                        id_manga_favoritado : idManga,
                        is_favorited : "true"
                    }
                }
            
                $.post("add_fav.php", dados, function(retorna){
                    $("").html(retorna);
                  });
            }
            
        </script>
            ';
            }
        }
    ?>

