<?php

include_once "conexao.php";

$mangas = filter_input(INPUT_POST, 'palavra', FILTER_SANITIZE_STRING);
$result_mangas = "SELECT * FROM mangas WHERE Titulo LIKE '%$mangas%' LIMIT 15";
$resultado_mangas = mysqli_query($mysqli, $result_mangas);

if(($resultado_mangas) AND ($resultado_mangas->num_rows != 0)) {
    while($row_manga = mysqli_fetch_assoc($resultado_mangas)){
        $caminho_capitulos = "./Mangas/".$row_manga['Nome_Pasta']."/Capitulos/";
        $array_capitulos = scandir($caminho_capitulos);
        $qtd_caps = count($array_capitulos)-2;

        $favorite_icon = 'favorite_border';
        echo '

        <div style="border:double rgba(73, 84, 97, 0.6);border-width: 4px;" class="painel-manga-ranqueado-pagina-navbar card">
            
        <div style="display: flex;flex-direction:column;order:15;flex-grow:1;width:100%;height:100%" class="">

            <div style="display:flex;width:100%;overflow:visible" class="container-infos-pagina-navbar ">
                <!-- Thumb do mangá  -->
                <div id="box-img-pesquisa" class="container-imagem-manga-pesquisa ">
                    <form style="width: 100%; height:100%;margin-bottom:inherit;position:absolute" action="pagina_manga.php" method="POST">
                        <button style="width:100%;height:100%;cursor:pointer;border-radius:15px;border-bottom-right-radius:30px;border-top-left-radius:30px;" type="int" name="dados" value="'. $row_manga['ID'] .'" href="pagina_manga.php" class="cat-manga-ranqueado-pagina-navbar transparent">
                            <img class="scale-effect-small" src="'. $row_manga['Thumb'] .'" alt="Thumb">
                        </button>
                    </form>
                </div>

                <div style="flex-direction:column;" class="container-vertical-A ">

                    <!-- Título do mangá  -->
                    <div id="container-titulo-manga-pesquisa" style="padding-right: 20px;flex-direction:column;" class="container-titulo-pagina-navbar ">
                        <form style="width: 100%; height: 100%;" action="pagina_manga.php" method="POST">
                            <button  type="int" name="dados" value="'. $row_manga['ID'] .'" style="border-style: none;padding: 0px;position: relative;display: flex;flex-grow:1" class="transparent"><title id="titulo-manga-pesquisa" style="text-align:left;align-self:center;display: flex;position: relative;cursor: pointer;flex-grow:1" class="titulo-manga-pagina-navbar">'. $row_manga["Titulo"] .'</title></button>
                        </form>
                        <div style="order:5;position: relative;left:0px;top: 0px;display: flex;flex-direction: row;height:max-content;flex-grow: 1" class="">
                            <title id="titulo-autor-pesquisa" style="text-align:left;position: relative;" class="titulo-autor-pagina-navbar">'. $row_manga['Autor'] .'</title>
                        </div>
                    </div>

                    <!-- BOTÕES DE GENERO DO MANGA  -->
                    <div id="container-generos" style="order:10;position:relative;flex-wrap:;padding:0px;flex-grow:initial;padding-top:5px" class="container-generos-pagina-navbar ">';
                    $array_generos_pagina_manga = explode("/",$row_manga['Genero']);
                    $i=0;
                        foreach($array_generos_pagina_manga as $genero_manga) {
                            $i++;
                            if($i < 5) {
                                echo '<a href="#">
                                    <div style="background-color: '; if(($genero_manga == 'Manhwa') or ($genero_manga == 'Manhua') or ($genero_manga == 'Mangá')){echo '#91c6f4';} echo '" id="button-genero-pesquisa" class="hoverable scale-effect botão-genero-pagina-navbar">
                                        <title style="align-self:center;font-size:inherit;font-weight:inherit;color:inherit;letter-spacing: 0.6px;">'. $genero_manga .'</title>
                                    </div>
                                </a>';
                            }
                        }
                    echo '</div>

                </div>

            </div>

            <!-- BOTÕES INFOS  -->
            <div class="sinopse-e-infos-pagina-navbar">

                <div class="infos-pagina-navbar hoverable">
				<!-- Visualizacoes do mangá  -->
				<div id="visualizacoes_pagina_navbar" style="position:relative;order:1;margin-left:0px;left: 0px;" class="pontuacao-manga-pagina-navbar">
					<i style="color:rgb(211, 255, 255);order:1;font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
					<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. number_format($row_manga["Visualizacoes"], 0, ',', '.') .'</text>
				</div>
				<!-- Nota do mangá  -->
				<div style="position:relative;order:1;margin-left:15px;left: 0px;" class="pontuacao-manga-pagina-navbar">
					<i style="color:rgb(211, 255, 255);order:1;font-size: inherit;align-self: center;" class="material-icons">star</i>
					<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. number_format($row_manga["Nota"], 2, ',', '.') .'</text>
				</div>
				<!-- Quantidade de caps do mangá  -->
				<div style="position:relative;order:1;margin-left:15px;left: 0px;" class="pontuacao-manga-pagina-navbar">
					<i style="color:rgb(211, 255, 255);order:5;font-size: inherit;align-self: center;" class="material-icons">storage</i>
					<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:600;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. $qtd_caps .'</text>
				</div>

                </div>

                <!-- Botão Sinopse  -->
                <button style="background-color: rgb(25, 56, 85);position:relative;order:10;margin-bottom: 0px;" class="btn waves-effect sinopse-button-pagina-navbar scale-effect hoverable"><title class="activator">Sinopse</title></button>
            </div>
        </div>

        <!-- card-reveal que carregará a sinopse do mangá -->
        <div style="z-index:5;overflow:visible;position:absolute;background-color: rgb(35, 44, 54);width: 100%; height: 100%;" class="card-reveal">

            <!-- Botão Voltar  -->
            <i style="color:white;position:absolute;font-size:var(--fonte-voltar-pagina-navbar);" class="material-icons botão-voltar-sinopse-pagina-navbar card-title">keyboard_return</i>
            <title style="color:white;position:absolute;font-size: var(--fonte-texto-voltar-pagina-navbar);" class="texto-voltar-sinopse-pagina-navbar card-title">Voltar</title>

            <!-- Descrição  -->
            <div style="border-radius:18px;position:absolute;background-color:rgb(54, 73, 92);" class="center descricao-manga"><text style="word-spacing:inherit;letter-spacing:inherit;font-size:inherit;margin:inherit;color:white;left:5%;right:5%;top:3%;position:absolute;font-family:"Yanone Kaffeesatz";" class="center">'. $row_manga["Sinopse"] .'</text></div>

        </div>

    </div>
</div>
<script>
var lenght_screen = screen.width;
if(lenght_screen < 350){
    var elemento = document.querySelector("#visualizacoes_pagina_navbar");
    elemento.parentNode.removeChild(elemento);
}
</script>';
    }
}else{
    echo '<title class="header-pesquisa-vazia">Nenhum mangá encontrado...</title>';
}
