<?php
    include "conexao.php";

    if(!isset($_SESSION)){
        session_start();
    }

    $manga_id = $_GET['dados_manga'];
    $capitulo = $_GET['capitulo'];
    $qtd_caps = $_GET['qtd_caps'];

    if(isset($_SESSION['ID'])) {
        $username = $_SESSION['nome'];
        $sql_code = "SELECT * FROM $username WHERE historico_id LIKE $manga_id";
        $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        
        $data = date('Y/m/d H:i:s');

        if($query->num_rows > 0){
            $sql_code = "UPDATE $username SET historico_cap='$capitulo', historico_data='$data' WHERE historico_id LIKE $manga_id";
            $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        }
        else {
            $sql_code_2 = "SELECT * FROM $username WHERE favoritos LIKE $manga_id";
            $query_2 = $mysqli->query($sql_code_2) or die("Falha na execução do código SQL: " . $mysqli->error);

            if($query_2->num_rows > 0){
                $sql_code_2 = "UPDATE $username SET historico_id=$manga_id, historico_cap='$capitulo', historico_data='$data' WHERE favoritos LIKE $manga_id";
                $mysqli->query($sql_code_2) or die("Falha na execução do código SQL: " . $mysqli->error);
        }
        else {
                $sql_code_3 = "INSERT INTO $username (favoritos, historico_id, historico_cap, historico_data) VALUES (NULL, $manga_id, $capitulo, '$data')";
                $mysqli->query($sql_code_3) or die("Falha na execução do código SQL: " . $mysqli->error);
        }
    }
}
    if(! isset( $_COOKIE["10_ultimos_mangas"] )) {
        $array = array();
        $array[] = $manga_id;

        $array_ultimo_cap = array();
        $array_ultimo_cap[$manga_id] = $capitulo;

        $arrayCookie = serialize($array);
        setcookie("10_ultimos_mangas", $arrayCookie, time()+(86400 * 365));
        setcookie("ultimos_caps", serialize($array_ultimo_cap), time() + (86400 * 365));
    }
    else {
        $arrayCookie = unserialize($_COOKIE["10_ultimos_mangas"]);
        $array_ultimo_cap = unserialize($_COOKIE["ultimos_caps"]);
        $array_ultimo_cap[$manga_id] = $capitulo;
        setcookie("ultimos_caps", serialize($array_ultimo_cap), time() + (86400 * 365));

        if(! in_array($manga_id, $arrayCookie)) {
            array_unshift($arrayCookie, $manga_id);
            $arrayCookie = serialize($arrayCookie);
            setcookie("10_ultimos_mangas", $arrayCookie, time()+(86400 * 365));
        }
        else {
            $key = array_search($manga_id, $arrayCookie);
            unset($arrayCookie[$key]);
            array_unshift($arrayCookie, $manga_id);
            $arrayCookie = serialize($arrayCookie);
            setcookie("10_ultimos_mangas", $arrayCookie, time()+(86400 * 365));


        }
    }

    
    
    $consulta = "SELECT * FROM mangas WHERE ID LIKE ".$manga_id;
    $mangas = $mysqli->query($consulta) or die($mysqli->error);
    $manga = $mangas->fetch_array();
    
    $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
    $caminho_paginas = $caminho_capitulos.$capitulo.'/';

    $diretorio = opendir($caminho_capitulos);

    $array_capitulos = array();
    while(($cap = readdir($diretorio)) !== false) {
        if(($cap !== '.') and ($cap !== '..')) {
            $array_capitulos[] = $cap;
        }
    }
    sort($array_capitulos);
    $ultimo_cap = $array_capitulos[count($array_capitulos) - 1];
    $primeiro_cap = $array_capitulos[0];
    
    $qtd_paginas = scandir($caminho_paginas);
    $qtd_paginas = count($qtd_paginas)-2;
    
    include "cabecalho.php";
    include "responsividade.php";
?>
    <title>
        <?= $manga['Titulo'] ?> - Capítulo <?= $capitulo ?>
    </title>

</head>


<style>
    ::-webkit-scrollbar {
        width: 15px;
        background-color: rgb(34, 44, 53);
    }
    ::-webkit-scrollbar-thumb{
        background-color: rgb(119, 141, 141);
        border-radius: 20px;
    }

    .body-selecionar-cap::-webkit-scrollbar{
        width: 0px;
    }
</style>
<body>

    
    <div style="z-index:-1" class="background-box background-pagina-manga"></div>

    <?php include "barra_navegação.php" ?>
    
    <div class="container-vertical-capitulo">
        
        <div style="background-color:rgb(22, 26, 29)" class="container-horizontal-botões-capitulo">
            <div style="justify-content: center;height: 50%;display: flex;flex-direction:row;order:1">

                <!-- BOTÃO VOLTAR CAPITULO  -->
                <div style="order: 1;left: 2px;position: absolute;" class="botão-voltar-proximo-capitulo <?php if($capitulo !== $primeiro_cap){ echo 'scale-effect'; } ?>">
                    <?php if($capitulo !== $primeiro_cap) : ?>
                    <form style="z-index:5;transform:scale(0.95);top:8px;position:absolute" action="capitulo.php" method="GET">
                        <button style="box-shadow:none;" class="transparent btn btn-floating" type="int" name="capitulo" value="<?= $capitulo - 1 ?>"></button>
                        <input type="hidden" name="dados_manga" value="<?= $manga_id ?>">
                        <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                    </form>
                    <?php endif ?>
                    <a style="background-color: rgb(35, 44, 54);<?php if($capitulo == $primeiro_cap){echo 'background-color: rgba(137, 137, 137, 1)';}?>" class="btn btn-floating hoverable">
                        <i style="transform: scale(1.4);<?php if($capitulo == $primeiro_cap){echo 'color: rgba(248, 248, 248, 0.8)';}?>" class="material-icons">
                            chevron_left

                        </i>
                    </a>  
                </div>    
                <!-- BOTÃO PAGINA INICIAL  -->
                <div style="order: 5;align-self: center;position:absolute" class="scale-effect">
                    <a href="pagina_manga.php" style="display:flex;justify-content: center;background-color: rgb(35, 44, 54);width: max-content;border-radius: 20px;padding:5px;padding-left: 12px;padding-right: 12px;" class="btn btn-floating hoverable">
                        <form style="z-index:5;align-self:center;width:100%;height:100%;position:absolute" action="pagina_manga.php" method="POST">
                            <button style="box-shadow:none;width: 100%;height: 100%;" class="transparent btn" type="int" name="dados_manga" value="<?= $manga_id ?>"></button>
                        </form>
                        <span style="align-self:center" class="basic-text">
                            Pagina inicial
                        </span>
                    </a>  
                </div> 
                
                 <!-- BOTÃO IR PRA COMENTÁRIOS  -->
                 <div style="order: 8;right: 60px;position: absolute;" class="botão-voltar-proximo-capitulo scale-effect">
                    <a href="#disqus_thread" style="background-color: rgb(35, 44, 54)" class="btn btn-floating hoverable">
                        <i style="transform: scale(0.85);" class="fa-solid fa-comment"></i>
                    </a>  
                </div>
                
                <div style="order: 10;display:flex;flex-direction:row">
                    <!-- BOTÃO AVANÇAR CAPITULO  -->
                    <div style="order: 10;flex-direction: row;right: 2px;position: absolute;" class="botão-voltar-proximo-capitulo <?php if($capitulo !== $ultimo_cap){ echo 'scale-effect'; } ?>">                        
                        <?php if($capitulo !== $ultimo_cap) : ?>
                        <form style="z-index:5;transform:scale(0.95);top:8px;position:absolute" action="capitulo.php" method="GET">
                            <button style="box-shadow:none;" class="transparent btn btn-floating" type="int" name="capitulo" value="<?= $capitulo + 1 ?>"></button>
                            <input type="hidden" name="dados_manga" value="<?= $manga_id ?>">
                            <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                        </form>
                        <?php endif ?>
                        <a style="background-color: rgb(35, 44, 54);<?php if($capitulo == $ultimo_cap){echo 'background-color: rgba(137, 137, 137, 1)';}?>" class="btn btn-floating hoverable">
                            <i style="transform: scale(1.4);<?php if($capitulo == $ultimo_cap){echo 'color: rgba(248, 248, 248, 0.8)';}?>" class="material-icons">
                                chevron_right

                            </i>
                        </a>  
                    </div>
                </div>

            </div>
            <div style="flex-direction: row;display:flex;height:50%;width:max-content;align-self:center;justify-content: center;order:10;">
                 <!-- SEÇAO SELECIONAR CAPÍTULO  -->
                 <div style="margin-bottom:10px;width:max-content;align-self:center;order: 5;" class="selecionar-capitulo">
                    <ul style="width:max-content;height:100%;" class="collapsible z-depth-0" data-collapsible="accordion">
                        <li style="width:max-content;height:100%;display: flex;justify-content: center;top:0px;position: relative;">
                            <div style="width:120px;align-items: center;background-color: rgb(35, 44, 54);padding:5px;border-radius: 15px;position:relative" class="scale-effect hoverable card selecionar-cap-button collapsible-header"><i style="font-size: var(--fonte-icone-selecionar-cap);" class="icone-selecionar-cap material-icons">view_headline</i><span class="basic-text">CAPITULOS</span></div>
                            <div style="background-color:rgb(22, 26, 29);border:4px double #3a4146;" class="body-selecionar-cap collapsible-body">
                            <div style="max-height: 300px;overflow-y:scroll">

                                
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
                                <form style="position:relative" action="capitulo.php" method="GET">
                                    <input type="hidden" name="capitulo" value="<?= $cap ?>">
                                    <button style="<?php if($cap == $ultimo_cap){echo 'margin-bottom:15px';} if($cap == $primeiro_cap){echo 'margin-top:15px';} ?>" type="int" name="dados_manga" value="<?= $manga_id ?>" class="capitulo-selecionar-cap scale-effect-small"><span class="titulo-cap basic-text">Capitulo <?= $cap ?> </span></button>
                                    <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                                </form>
                                <?php } ?>
                                
                                
                            </div>
                            </div>
                        </li>
                    <ul>
                </div>
               
            </div>
        </div>

        <div class="container-paginas-capitulo">
            <?php 
                $dir_paginas = opendir($caminho_paginas);
                if ($dir_paginas) {
                    $paginas = array();
                    while (($pag = readdir($dir_paginas)) !== false) {
                        if ($pag == '.' || $pag == '..') {
                            continue;
                            
                        }
                        else{
                            $paginas[] = $pag;
                        }
                        sort($paginas, SORT_NATURAL);
                    }
                    foreach($paginas as $pagina) {
                        ?>
                <div style="padding:0px;margin:0px;" class="cat-pagina-capitulo">
                    <img class="img-paginas-capitulo" src="<?= $caminho_paginas.$pagina ?>" alt="">
                </div>
            <?php } ?>
            <?php } ?>            
        </div>


        <div style="order: 10;background-color:rgb(22, 26, 29)" class="container-horizontal-botões-capitulo">
            <div style="justify-content: center;height: 50%;display: flex;flex-direction:row;order:1">

                     <!-- BOTÃO VOLTAR CAPITULO  -->
                     <div style="order: 1;left: 2px;position: absolute;" class="botão-voltar-proximo-capitulo <?php if($capitulo !== $primeiro_cap){ echo 'scale-effect'; } ?>">
                    <?php if($capitulo !== $primeiro_cap) : ?>
                    <form style="z-index:5;transform:scale(0.95);top:8px;position:absolute" action="capitulo.php" method="GET">
                        <button style="box-shadow:none;" class="transparent btn btn-floating" type="int" name="capitulo" value="<?= $capitulo - 1 ?>"></button>
                        <input type="hidden" name="dados_manga" value="<?= $manga_id ?>">
                        <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                    </form>
                    <?php endif ?>
                    <a style="background-color: rgb(35, 44, 54);<?php if($capitulo == $primeiro_cap){echo 'background-color: rgba(137, 137, 137, 1)';}?>" class="btn btn-floating hoverable">
                        <i style="transform: scale(1.4);<?php if($capitulo == $primeiro_cap){echo 'color: rgba(248, 248, 248, 0.8)';}?>" class="material-icons">
                            chevron_left

                        </i>
                    </a>  
                </div>    
                <!-- BOTÃO PAGINA INICIAL  -->
                <div style="order: 5;align-self: center;position:absolute" class="scale-effect">
                    <a href="pagina_manga.php" style="display:flex;justify-content: center;background-color: rgb(35, 44, 54);width: max-content;border-radius: 20px;padding:5px;padding-left: 12px;padding-right: 12px;" class="btn btn-floating hoverable">
                        <form style="z-index:5;align-self:center;width:100%;height:100%;position:absolute" action="pagina_manga.php" method="POST">
                            <button style="box-shadow:none;width: 100%;height: 100%;" class="transparent btn" type="int" name="dados_manga" value="<?= $manga_id ?>"></button>
                        </form>
                        <span style="align-self:center" class="basic-text">
                            Pagina inicial
                        </span>
                    </a>  
                </div> 
                
                
                <div style="order: 10;display:flex;flex-direction:row">
                    <!-- BOTÃO AVANÇAR CAPITULO  -->
                    <div style="order: 10;flex-direction: row;right: 2px;position: absolute;" class="botão-voltar-proximo-capitulo <?php if($capitulo !== $ultimo_cap){ echo 'scale-effect'; } ?>">                        
                        <?php if($capitulo !== $ultimo_cap) : ?>
                        <form style="z-index:5;transform:scale(0.95);top:8px;position:absolute" action="capitulo.php" method="GET">
                            <button style="box-shadow:none;" class="transparent btn btn-floating" type="int" name="capitulo" value="<?= $capitulo + 1 ?>"></button>
                            <input type="hidden" name="dados_manga" value="<?= $manga_id ?>">
                            <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                        </form>
                        <?php endif ?>
                        <a style="background-color: rgb(35, 44, 54);<?php if($capitulo == $ultimo_cap){echo 'background-color: rgba(137, 137, 137, 1)';}?>" class="btn btn-floating hoverable">
                            <i style="transform: scale(1.4);<?php if($capitulo == $ultimo_cap){echo 'color: rgba(248, 248, 248, 0.8)';}?>" class="material-icons">
                                chevron_right

                            </i>
                        </a>  
                    </div>
                </div>
            
                 <!-- BOTÃO SUBIR PÁGINA  -->
                 <div style="order: 8;right: 60px;position: absolute;" class="botão-voltar-proximo-capitulo scale-effect">
                    <a href="#" style="background-color: rgb(35, 44, 54)" class="btn btn-floating hoverable">
                        <i style="transform: scale(1.1);" class="material-icons">
                            arrow_upward
                        </i>
                    </a>  
                </div>

            </div>
            <div style="flex-direction: row;display:flex;height:50%;width:max-content;align-self:center;justify-content: center;order:10;">
                 <!-- SEÇAO SELECIONAR CAPÍTULO  -->
                 <div style="margin-bottom:10px;width:max-content;align-self:center;order: 5;" class="selecionar-capitulo">
                    <ul style="width:max-content;height:100%;" class="collapsible z-depth-0" data-collapsible="accordion">
                        <li style="width:max-content;height:100%;display: flex;justify-content: center;top:0px;position: relative;">
                            <div style="width:120px;align-items: center;background-color: rgb(35, 44, 54);padding:5px;border-radius: 15px;position:relative" class="scale-effect hoverable card selecionar-cap-button collapsible-header"><i style="font-size: var(--fonte-icone-selecionar-cap);" class="icone-selecionar-cap material-icons">view_headline</i><span class="basic-text">CAPITULOS</span></div>
                            <div style="background-color:rgb(22, 26, 29);border:4px double #3a4146;" class="body-selecionar-cap collapsible-body">
                            <div style="max-height: 300px;overflow-y:scroll">

                                
                                <?php 
   
                                        foreach($caps as $cap) {
                                            ?>
                                <form style="position:relative" action="capitulo.php" method="GET">
                                    <input type="hidden" name="capitulo" value="<?= $cap ?>">
                                    <button style="<?php if($cap == $ultimo_cap){echo 'margin-bottom:15px';} if($cap == $primeiro_cap){echo 'margin-top:15px';} ?>" type="int" name="dados_manga" value="<?= $manga_id ?>" class="capitulo-selecionar-cap scale-effect-small"><span class="titulo-cap basic-text">Capitulo <?= $cap ?> </span></button>
                                    <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                                </form>
                                <?php } ?>
                                
                                
                            </div>
                            </div>

                        </li>
                    <ul>
                </div>
               
            </div>
        </div>
    </div>
    
<div id="disqus_thread" style="background-color: rgb(22, 26, 29); border-radius:30px;padding-top:30px;padding-right:15px;padding-left:15px"></div>
<script>

    var disqus_config = function () {
    this.page.url = window.location.href;  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = window.location.href; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };
    (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://alleyboss-mangas.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

    <?php include "java_import.php" ?>
</body>    
</html>