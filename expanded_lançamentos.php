<?php
include "conexao.php";
include "responsividade.php";



ini_set('default_charset','UTF-8');

$consulta = "SELECT * FROM mangas ORDER BY Titulo ASC";
$mangas = $mysqli->query($consulta) or die($mysqli->error);
$ranqueados = $mysqli->query($consulta) or die($mysqli->error);
$mais_lidos = $mysqli->query($consulta) or die($mysqli->error);

$consulta_favoritos = "SELECT * FROM mangas_favoritos";
$mangas_fav = $mysqli->query($consulta_favoritos) or die($mysqli->error);
$qtd_mangas_fav = $mangas_fav->num_rows;

$qtd_mangas = $mangas->num_rows;

if(isset($_POST['id_manga_favoritado'])){
    $id_manga_fav = $_POST['id_manga_favoritado'];
    $query = "INSERT INTO `mangas_favoritos`(`ID`) VALUES ($id_manga_fav)";
    mysqli_query($mysqli, $query);
}

$array_ordenado = array();

?>
    <?php
        $array_mangas_lançamentos = array();
        while($manga = $ranqueados->fetch_array()) {
            $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
            $data_diretorio = date('Y/m/d H:i:s', filemtime($caminho_capitulos));
            $array_mangas_lançamentos[$data_diretorio.' : '.$manga['Titulo']] = $manga['ID'];
        }
        krsort($array_mangas_lançamentos, SORT_STRING);
        
        $dados = filter_input(INPUT_POST, 'index_reset', FILTER_SANITIZE_NUMBER_INT);
        $index = 0;
        foreach($array_mangas_lançamentos as $manga_id) {
            if($index < $dados) {

            $query = "SELECT * FROM mangas WHERE ID LIKE ".$manga_id;
            $manga_query = $mysqli->query($query) or die($mysqli->error);
            $manga = $manga_query->fetch_array();
            
            $index++;
            $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
            $i=1;
            $array_capitulos = scandir($caminho_capitulos);
            $qtd_caps = count($array_capitulos)-2;
            
            if($index > 15){
                

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
                
                if(is_dir($caminho_capitulos)) {
                    $arrayCapitulos = array();
                    $diretorio = opendir($caminho_capitulos);
                    while(($arquivo = readdir($diretorio)) !== false) {
                        if(($arquivo !== '..') && ($arquivo !== '.')) {
                            $data_cap = date('Y/m/d H:i:s', filemtime($caminho_capitulos.$arquivo));
                            $data_diretorio = date('m', filemtime($caminho_capitulos));
                            $arrayCapitulos[] = $caminho_capitulos.$arquivo;
                        }
                    }
                }
            if(date('m') == $data_diretorio) {
            echo '
            <div style="border-color:rgba(0,0,0,0);margin-top:0px;margin-bottom:10px" class="collection-item container-manga">

    <div style="width:100%;height:100%;top:0px;position:relative;margin:0px" class="">


       <!-- Opções do mangá -->
       <div id="fixed-btn-pagina-navbar" style="z-index:4;transform:scale(0.91);top:-12px" class="floating-btn-pagina-navbar">
            <a class="btn-large btn-floating">
                <i style="color:inherit" onclick="showOptionsManga('. $manga['ID'] .')" class="fa-sharp fa-solid fa-ellipsis"></i>
            </a>

            <div id="opcoes_manga'. $manga['ID'].'expanded_lançamentos" style="width:max-content;height:58px;right:60px;top:37px;display:flex;flex-direction:row;z-index:-5;opacity:0;position:absolute;transition: opacity 0.5s, top 0.5s, transform 0.8s;transform: scale(0.5)" >
                
                    <!-- BOTÃO DE FAVORITAR MANGÁ  -->
                <div style="list-style: none; margin-left:12px">
                    <a onclick="addFavoritos('.$manga['ID'].','."'expanded_lançamentos'".')" style="background-color: rgb(18, 45, 70)" id="favoritar-pagina-manga" class="btn btn-floating hoverable">
                        <i id="icon_favorite'. $manga['ID'].'expanded_lançamentos" class="material-icons">
                            '.$icon.'
                        </i>
                    </a>  
                </div>
            </div>
        </div>
        <!-- painel de informações do mangá -->
        <div style="" class="hoverable card manga-info-panel">
            <div style="width:100%;height:100%;display: flex; flex-direction:row;justify-content:left">
            
                <!-- Imagem do mangá da seção Lista de mangás -->
                <form style="width: 100%; height: 100%;" action="pagina_manga.php" method="POST">
                    <button style="z-index:4;cursor:pointer;border-style: none;padding: 0px;position: relative;display: inline-block;" type="int" name="dados" value="'. $manga['ID'] .'" class="thumb-lançamentos transparent">
                        <img class="scale-effect-small" style="left:0px;top:0px;position:absolute" src="'. $manga["Thumb"] .'" alt="Thumb">
                    </button>
                    </form>
                
                <div style="position:absolute; left:0px;display:flex; flex-direction:column;width:100%;height:100%">
                    <!-- Título do mangá  -->
                    <div style="position:relative" class="container-titulo-lista-manga">
                    <form style="width: 100%; height: 100%;overflow-x:hidden" action="pagina_manga.php" method="POST">
                    <button type="int" name="dados" value="'. $manga['ID'] .'" style="cursor:pointer;border-style: none;padding: 0px;width: 100%; height: 100%;" class="transparent"><title style="height:max-content;overflow-x:hidden;" class="titulo-manga">'. $manga["Titulo"] .'</title></button>
                            </form>
                    </div>
                    <div style="width:max-content;height:max-content;position:relative;display:flex;flex-direction:row;overflow:visible" class="container-titulo-lista-manga">
                        ';
                        $index_caps = 0;
                        
                            krsort($arrayCapitulos, SORT_STRING);
                            foreach($arrayCapitulos as $capitulo_ordenado) {
                                if(($capitulo_ordenado !== "." && $capitulo_ordenado !== "..") && $index_caps < 4){
                                    $index_caps++;
                                    $capitulo = explode("Capitulos/", $capitulo_ordenado);
                                    echo "       
                                    <form action='capitulo.php' style='z-index:5' method='POST'>
                                        <input type='hidden' name='dados_manga' value='".$manga['ID']."'>
                                        <input type='hidden' name='qtd_caps' value='".$manga['ID']."'>
                                        <input type='hidden' name='capitulo' value='".$capitulo[1]."'>
                                        
                                        ";
                                        if($index_caps == 1){
                                            echo "<input type='submit' style='background-color:rgba(135, 170, 180, 1);border-style:double;border-color:rgba(175, 207, 215, 1);border-width:2px' class='scale-effect caps-lançamentos' value='Cap.".$capitulo[1]."'>";
                                        }
                                        else{
                                            echo "<input type='submit' class='scale-effect caps-lançamentos' value='Cap.".$capitulo[1]."'>";
                                        }
                                    echo "</form>";
                                }
                            }
                        echo '
                    </div>

                </div>
            </div>

            <div class="sinopse-e-infos">

                <div class="infos-lançamentos hoverable">
                    <div style="position:relative;order:10;margin-left:0px;left: 0px;" class="pontuacao-manga">
                        <i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="material-icons">storage</i>
                        <title style="font-weight: 600;transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;">'. $qtd_caps .'</title>
                    </div>
                    <!-- Pontuação do mangá  -->
                    <div style="position:relative;order:5;margin-left:0px;left: 0px;" class="pontuacao-manga">
                        <i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="star material-icons">star</i>
                        <text style="font-weight: 600;transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;">'.number_format($manga["Nota"], 2, ',', '.') .'</text>
                    </div>
                        <!-- Pontuação do mangá  -->
                    <div id="visualizacoes_pagina_navbar" style="position:relative;order:1;margin-left:0px;left: 0px;" class="pontuacao-manga">
                        <i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
                        <text style="font-weight: 600;transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;">'. number_format($manga["Visualizacoes"], 0, ',', '.') .'</text>
                    </div>

                </div>
                <!-- Botão Sinopse  -->
                <button style="background-color: rgb(25, 56, 85);position:relative;order:10;align-self: center;margin-bottom: 0px;" class="btn sinopse-button scale-effect hoverable"><title class="activator">Sinopse</title></button>
            </div>
            
            <!-- card-reveal que carregará a sinopse do mangá -->
            <div style="z-index:8;position:absolute;background-color: rgb(35, 44, 54);width: 100%; height: 100%;" class="card-reveal">

                <!-- Botão Voltar  -->
                <i style="color:white;position:absolute;font-size:var(--fonte-voltar);" class="material-icons botão-voltar-sinopse card-title">keyboard_return</i>
                <title style="color:white;position:absolute;font-size:var(--fonte-texto-voltar);" class="texto-voltar-sinopse card-title">Voltar</title>
                
                <!-- Descrição  -->
                <div style="border-radius:18px;position:absolute;background-color:rgb(54, 73, 92);" class="center descricao-manga"><text style="letter-spacing:inherit;font-size:inherit;margin:inherit;color:white;left:5%;right:5%;top:3%;position:absolute;font-family:"Yanone Kaffeesatz";" class="center">'. $manga["Sinopse"] .'</text></div>

            </div>


        </div>
        </div>
        </div>
<script>
    var lenght_screen = screen.width;
    if(lenght_screen < 350){
        var elemento = document.querySelector("#visualizacoes_pagina_navbar");
        elemento.parentNode.removeChild(elemento);
    }
    function showOptionsManga(mangaTitulo, pagina="expanded_lançamentos") {
    var opcoesManga = document.getElementById("opcoes_manga" + mangaTitulo + pagina);
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
    }
}
    ?>
    
