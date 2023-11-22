<?php

if(!isset($_SESSION)){
    session_start();
}

include "cabecalho.php";
include "responsividade.php";


    if(isset($_POST['dados'])) {
        $id = $_POST['dados'];
    }
    if(isset($_POST['dados_manga'])){
        $id = $_POST['dados_manga'];
    }
    $consulta = "SELECT * FROM mangas WHERE ID LIKE '".$id."'";
    $mangas = $mysqli->query($consulta) or die($mysqli->error);
    $manga = $mangas->fetch_array();

    $query_increment_view = "UPDATE mangas SET Visualizacoes = Visualizacoes+1 WHERE ID LIKE ".$id;
    mysqli_query($mysqli, $query_increment_view);
    
    $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
    $array_capitulos = scandir($caminho_capitulos);
    $qtd_caps = count($array_capitulos)-2;
    $cap_inicial = $manga['Capitulo-Inicial']
?>



<body style="height: 100%; width: 100%;">

    <div>

        <!-- Imagem de fundo  -->
        <div style="z-index:0;" class="background-box background-image"></div>
    
        <?php include "barra_navegação.php" ?>    
    
    
        <div style="width:100%;display:flex;justify-content: center;flex-direction: column;top:-300px;position:absolute;z-index:4">
        <!-- Importando a barra de navegação  -->

        <div id="thumb-large" style="display:flex;align-items: center;justify-content: center;" class="cat-pagina-manga center">
            <a href="#">
                <img class="center" src="<?= $manga["Thumb"] ?>">
            </a>
        </div>
        <!-- Painel do mangá -->
        <div style="border-bottom-left-radius:0px;border-bottom-right-radius:0px" class="painel-pagina-manga z-depth-5 hoverable">
            
            <div class="container-infos">
                
                
                <div style="cursor:default;width: <?= strlen($manga['Titulo'])*16 ?>px;height:max-content;" class="container-titulo-pagina-manga">
                    <!-- Titulo do mangá  -->
                    <title style="display: flex;text-align: center;margin-bottom:0px" class="titulo-pagina-manga"><?= $manga["Titulo"] ?></title>
                </div>
                <title style="cursor:default;text-align:center;" class="titulo-autor-pagina-manga"><?= $manga['Autor'] ?></title>
                
                <div style="margin-top:40px" class="container-botoes">
                    <div style="order:0;cursor:default;display:flex;flex-direction:row" class="container-de-sua-nota">
                        <span class="basic-text">Sua avaliação:</span>
                        <div style=" display:flex;justify-content:center;flex-direction:row;margin-left:10px;padding:5px;padding-left:10px;padding-right:10px;border-radius:50px">
                            <i onclick="incrementaNota('-')" style="background-color: rgb(194, 213, 229);border-radius:100px;border:3px outset rgb(218, 224, 230);padding:5px;color:rgba(124, 133, 134, 1);cursor:pointer" class=" fa-solid center fa-chevron-left scale-effect hoverable"></i>
                            <span id="nota_do_usuario" style="align-self:center;position:relative;margin-right:10px;margin-left:10px" class="basic-text">                            
                                1
                            </span>
                            <i onclick="incrementaNota('+')" style="background-color: rgb(194, 213, 229);border-radius:100px;border:3px outset rgb(218, 224, 230);padding:5px;color:rgba(124, 133, 134, 1);cursor:pointer" class="fa-solid center fa-chevron-right scale-effect hoverable"></i>
                            <div onclick="salvarNotaUsuario(<?= $manga['ID'] ?>)" style="margin-left: 8px;background-color: rgb(194, 213, 229);border-radius:100px;border:3px outset rgb(218, 224, 230);color:rgba(124, 133, 134, 1);cursor:pointer;display:flex;flex-direction:row" class="hoverable scale-effect-small">
                                <span style="color:rgba(97, 100, 103, 1);margin-right:8px;margin-left:8px" class="basic-text">Salvar</span>
                                <i style="margin-right:12px;font-size:140%;color:rgba(76, 89, 101, 1)" class="fa-solid center fa-floppy-disk "></i>
                            </div>
                        </div>
                    </div>
                    
        
                    <div style="cursor:default;" class="container-horizontal-botões">

                        <!-- Container com os principais botões  -->
                        <div style="position: relative;order:5;left:initial;margin-right:20px;align-self: center;border-radius:30px;padding:5px;padding-left:10px;padding-right:10px" class="infos-pagina-manga hoverable card">
                            <div style="position:relative;order:10;margin-left:0px;left: 0px;" class="pontuacao-manga">
                                <i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="material-icons">storage</i>
                                <span style="transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;" class="basic-text"><?= $qtd_caps ?></span>
                            </div>
                            <!-- Pontuação do mangá  -->
                            <div style="position:relative;order:5;margin-left:0px;left: 0px;" class="pontuacao-manga">
                                <i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="star material-icons">star</i>
                                <text style="transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;"><?php echo number_format($manga["Nota"], 2, ',','.') ?></text>
                            </div>
                                <!-- Pontuação do mangá  -->
                                <div style="position:relative;order:1;margin-left:0px;left: 0px;" class="pontuacao-manga">
                                <i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
                                <text style="transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;"><?php echo number_format($manga["Visualizacoes"], 0, ',', '.') ?></text>
                            </div>
                        </div>
                        
                        <!-- Principais botões  -->
                        <div style="order:10;position:relative;display: flex;flex-direction: row;justify-content: center; align-self:center">
                            <!-- Botão de favoritar  -->
                            <div class="botão-favoritar scale-effect">
                                <a onclick="addFavoritos(<?= $manga['ID'] ?>, 'pagina_manga')" id="favoritar-pagina-manga" class="btn btn-floating hoverable">
                                    <i id="icon_favorite<?= $manga['ID'] ?>pagina_manga" class="material-icons">
                                    <?php        
                                        if(isset($_SESSION['ID'])) {
                                            $nome = $_SESSION['nome'];
                                            $sql_code = "SELECT favoritos FROM $nome WHERE favoritos LIKE '".$manga["ID"]."'";
                                            $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                                            
                                                if($query->num_rows != 0){
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
                    
                    <div style="position:relative" class="container-generos-pagina-manga">
                        
                        <?php $array_generos_pagina_manga = explode("/",$manga['Genero']) ?>
                        <?php foreach($array_generos_pagina_manga as $genero_manga) : ?>                        
                            <a href="#">
                                <div style="background-color:<?php if(($genero_manga == 'Manhwa') or ($genero_manga == 'Manhua') or ($genero_manga == 'Mangá')){echo '#91c6f4';} ?>" class="hoverable scale-effect-small botão-genero-pagina-manga center">
                                    <span style="align-self:center;color:inherit;font-weight:inherit;font-size:inherit" class="basic-text"><?= $genero_manga ?></span>
                                </div>
                            </a>
                        <?php endforeach ?>
                    </div>
                
                        
                    </div>
                    
                    
                    <!-- TABS DE SINOPSE E AVALIAÇÃO  -->
                    <div style="padding-top:20px;position:relative;top:20px" class="container-tabs row">
                        <div class="col s12">
                            <ul class="tabs">
                                <li style="width: 40%;" class=" tab col s3 tab-sinopse-pagina-manga"><a style="width: 100%;" href="#tab-sinopse"><span style="font-size: var(--fonte-tabs-pagina-manga);" class="center basic-text">Sinopse</span></a></li>
                                <li style="width: 20%;" class=" tab col s3 tab-sinopse-pagina-manga"><a style="width: 100%;" class="active" href="#tab-vazia" ><i style="top:5px;position:relative;color: rgb(200, 235, 235);" class="material-icons">arrow_drop_up</i></a></li>
                                <li style="width: 40%;" class="transparent tab col s3 tab-sinopse-pagina-manga"><a style="width: 100%;" href="#tab-avaliacao"><span style="font-size: var(--fonte-tabs-pagina-manga);" class="center basic-text">Avaliação</span></a></li>
                            </ul>
                        </div>
                        <div id="tab-sinopse" class="col s12">
                            <div class="container-tab-sinopse">
                                <span class="texto-tab-sinopse center basic-text">
                                    <?= $manga["Sinopse"] ?>
                                </span>
                            </div>
                        </div>
                        <div id="tab-avaliacao" class="col s12">
                            <div class="container-tab-sinopse">
                                <span class="texto-tab-sinopse center basic-text">
                                    <?= $manga["Avaliacao"] ?>
                                </span>
                            </div>
                        </div>
                    <div id="tab-vazia" class="col s12"></div>
                    
                </div>
        
                <!-- Loop com os capitulos  -->
                <div style="height:max-content;padding-bottom:25px;background-color:rgb(35, 44, 54);border:4px double rgb(58, 65, 70)" class="lista-caps">
                    <div style="height:50px;width: 100%;position: relative;;" class="">
                        <span class="titulo-seção-caps center basic-text">Capítulos</span>

                    </div>
                    <?php 
                            $dir = dir($caminho_capitulos);
                            if ($dir) {
                                while (($cap = $dir->read()) !== false) {
                                        if ($cap == '.' || $cap == '..') {
                                            continue;
                                        }
                                        else{
                                            $caps[] = $cap;
                                        }
                                        sort($caps, SORT_NATURAL);
                                    }
                                }
                                foreach($caps as $cap) {
                        
                        ?>

                    <form action="capitulo.php" method="GET">
                        <input type="hidden" name="capitulo" value="<?= $cap ?>">
                        <button type="int" name="dados_manga" value="<?= $id ?>" style="background-color:rgb(59, 71, 83);border:none" class="hoverable capitulo scale-effect-smaller"><span class="titulo-cap basic-text">Capitulo <?= $cap ?> </span></button>
                        <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                        
                    </form>
                    
                    <?php } ?>
                </div>
            </div>
            
    
        </div>
    </div>
    </div>
    <!-- Importando o JavaScript  -->
    <?php include "java_import.php" ?>
</body>
</html>