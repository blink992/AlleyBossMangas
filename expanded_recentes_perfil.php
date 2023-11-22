<?php
  if(!isset($_SESSION)){
    session_start();
}      

include "responsividade.php";
include "conexao.php";

$username = $_SESSION['nome'];

$sql_code = "SELECT historico_id FROM $username WHERE historico_id <> 'NULL' ORDER BY historico_data DESC";
$sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

$dados = filter_input(INPUT_POST, 'index_reset', FILTER_SANITIZE_NUMBER_INT);

$index = 0;
while(($mangas = $sql_query->fetch_array()) and ($index < $dados)) {
    $manga_id = $mangas['historico_id'];
    $sql_code_2 = "SELECT * FROM mangas WHERE ID LIKE $manga_id";
    $sql_query_2 = $mysqli->query($sql_code_2) or die("Falha na execução do código SQL: " . $mysqli->error);
    $manga = $sql_query_2->fetch_assoc();
    
    $index++;
    $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
    $i=1;
    $array_capitulos = scandir($caminho_capitulos);
    $qtd_caps = count($array_capitulos)-2;

        
    $sql_code = "SELECT historico_cap FROM $username WHERE historico_id LIKE $manga_id";
    $sql_query_2 = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
    $sql_query_2 = $sql_query_2->fetch_assoc();
    $ultimo_cap = $sql_query_2['historico_cap'];
    
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
        echo '
        <div style="width:100%;height:100%;margin-bottom:0px;margin-top:0px;box-shadow:none;" class="card transparent">


        <!-- Opções do mangá -->
        <div id="fixed-btn-pagina-navbar" style="z-index:4;transform:scale(0.86);top:-10px;right:4px" class="floating-btn-pagina-navbar">
             <a class="btn-large btn-floating">
                 <i style="color:inherit" onclick="showOptionsManga('. $manga["ID"] .', \'expanded_recentes_perfil\')" class="fa-sharp fa-solid fa-ellipsis"></i>
             </a>
 
             <div id="opcoes_manga'. $manga["ID"] .'expanded_recentes_perfil" style="width:max-content;height:58px;right:60px;top:37px;display:flex;flex-direction:row;z-index:-5;opacity:0;position:absolute;transition: opacity 0.5s, top 0.5s, transform 0.8s;transform: scale(0.5)" >
             
                 <!-- BOTÃO DE FAVORITAR MANGÁ  -->
                 <li style="list-style: none; margin-left:12px">
                     <a onclick="addFavoritos('.$manga['ID'].','."'expanded_recentes_perfil'".')" style="background-color: rgb(18, 45, 70)" id="favoritar-pagina-manga" class="btn btn-floating hoverable">
                         <i id="icon_favorite'. $manga['ID'].'expanded_recentes_perfil" class="material-icons">
                             '.$icon.'
                         </i>
                     </a>  
                 </li>
             </div>
         </div>
 
 
 
 
         <div class="painel-manga-ranqueado card">
 
             <!-- Thumb do mangá  -->
             <form style="width: 100%; height:100%;margin-bottom:inherit;position:absolute" action="pagina_manga.php" method="POST">
                 <button style="cursor:pointer;border-radius:15px;border-bottom-right-radius:30px;border-top-left-radius:30px;" type="int" name="dados" value="'. $manga['ID'] .'" href="pagina_manga.php" class="cat-manga-ranqueado transparent">
                         <img class="scale-effect-small" src="'. $manga['Thumb'] .'" alt="Thumb">
                 </button>
             </form>
 
             <!-- Título do mangá  -->
             <div class="container-titulo-menus-direita">
                 <form style="width: 100%; height: 100%;" action="pagina_manga.php" method="POST">
                     <button  type="int" name="dados" value="'. $manga['ID'] .'" style="border-style: none;padding: 0px;" class="transparent"><title style="text-align:justify;cursor:pointer" class="titulo-manga-ranqueado left">'. $manga["Titulo"] .'</title></button>
                 </form>
             </div>
 
             <div class="sinopse-e-infos-menus-direita">

                <form action="capitulo.php" style="display:flex;justify-content:center;margin-right:15px;bottom:-10px;position:relative;height:max-content;" method="POST">
                    <button type="submit" name="capitulo" value="'. $ultimo_cap .'" style="border: solid 3px rgba(52, 55, 73, 1);background-color:rgb(60, 74, 82);border-radius:8px;position:relative;bottom:5px;z-index:5;align-self:center;margin-right:5px; color:rgb(230, 255, 255)" class="scale-effect caps-historico">Cap.'. $ultimo_cap .'</button>
                    <input type="hidden" name="qtd_caps" value="'. $qtd_caps .'">
                    <input type="hidden" name="dados_manga" value="'. $manga['ID'] .'">';
                    
                    if(is_dir($caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/". ($ultimo_cap + 1))) {                                
                    echo '<button type="submit" name="capitulo" value="'. ($ultimo_cap + 1) .'" style="z-index:5;position:relative;bottom:5px;background-color:rgb(60, 74, 82);z-index:5;align-self:center;width:max-content;height:max-content" class="scale-effect caps-historico"><i style="height:auto;font-size:125%;color:rgba(179, 180, 185, 1)" class="fa-solid fa-forward"></i></button>
                    '; }
                    echo '
                </form>

                 <div style="position: relative;order:1;margin-right: 10px;margin-bottom:0px;" class="infos-top-10 hoverable card">
                 <!-- Visualizacoes do mangá  -->
					<div style="position:relative;order:1;margin-left:0px;left: 0px;font-size: 120%;" class="pontuacao-manga">
						<i style="color:rgb(211, 255, 255);order:5;font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
						<text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:900;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. $manga["Visualizacoes"] .'</text>
					</div>
                     <!-- Pontuação do mangá  -->
                     <div style="position:relative;order:1;margin-left:0px;left: 0px;font-size: 120%;" class="pontuacao-manga">
                         <i style="color:rgb(211, 255, 255);order:5;font-size: inherit;align-self: center;" class="star material-icons">star</i>
                         <text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:900;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. number_format($manga["Nota"], 2, ',', '.') .'</text>
                     </div>
                     <!-- Quantidade de caps do mangá  -->
                     <div style="position:relative;order:1;margin-left:0px;left: 0px;font-size: 120%;" class="pontuacao-manga">
                         <i style="color:rgb(211, 255, 255);order:5;font-size: inherit;align-self: center;" class="material-icons">storage</i>
                         <text style="color:rgb(137, 199, 199);transform:scale(0.95);font-weight:900;margin-left: 5px;order:10;font-size: inherit;align-self: center;">'. $qtd_caps .'</text>
                     </div>
                 </div>
                 <!-- Botão Sinopse  -->
                 <button style="background-color: rgb(25, 56, 85);position:relative;order:10;margin-bottom: 0px;" class="btn sinopse-button-ranqueado scale-effect hoverable"><title class="activator">Sinopse</title></button>
             </div>
 
             <!-- card-reveal que carregará a sinopse do mangá -->
             <div style="z-index:5;overflow:visible;position:absolute;background-color: rgb(35, 44, 54);width: 100%; height: 100%;" class="card-reveal">
 
                 <!-- Botão Voltar  -->
                 <i style="color:white;position:absolute;font-size:var(--fonte-voltar);" class="material-icons botão-voltar-sinopse card-title">keyboard_return</i>
                 <title style="color:white;position:absolute;font-size: var(--fonte-voltar);" class="texto-voltar-sinopse card-title">Voltar</title>
 
                 <!-- Descrição  -->
                 <div style="border-radius:18px;position:absolute;background-color:rgb(54, 73, 92);" class="center descricao-manga"><text style="letter-spacing:inherit;font-size:inherit;margin:inherit;color:white;left:5%;right:5%;top:3%;position:absolute;font-family:\'Yanone Kaffeesatz\';" class="center">'. $manga["Sinopse"] .'</text></div>
 
             </div>
         </div>
 
     </div>
  
 <script>
     function showOptionsManga(mangaTitulo, pagina) {
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

 var lenght_screen = screen.width;
 if(lenght_screen < 350){
     var elemento = document.getElementById("infos");
     elemento.parentNode.removeChild(elemento);
 }
 else if((lenght_screen > 750) && (lenght_screen <= 1050)){
     var elemento = document.getElementById("infos");
     elemento.parentNode.removeChild(elemento);
 }
 </script>
            ';
        }
    }
?>