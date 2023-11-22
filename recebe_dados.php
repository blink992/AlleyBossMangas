<?php

include_once "conexao.php";

$dados = filter_input(INPUT_POST, 'index', FILTER_SANITIZE_NUMBER_INT);
$ranqueados = filter_input(INPUT_POST, 'mangas');


if ($dados = 15) {
	$index = 0;
}



$id_manga = filter_input(INPUT_POST, 'id_manga', FILTER_SANITIZE_NUMBER_INT);
$nota_manga = filter_input(INPUT_POST, 'nota_manga', FILTER_SANITIZE_NUMBER_INT);

if(($nota_manga != null) and ($id_manga != null)) {
	if(isset($_COOKIE['nota_manga_'.$id_manga])){
		$nome = "nota_manga_".$id_manga;

		$nota_antiga = $_COOKIE[$nome];
		
		$query = "UPDATE mangas SET Total_Notas = Total_Notas - ".$nota_antiga." WHERE ID LIKE ".$id_manga;
		mysqli_query($mysqli, $query);
		
		$query = "UPDATE mangas SET Total_Notas = Total_Notas + ".$nota_manga." WHERE ID LIKE ".$id_manga;
		mysqli_query($mysqli, $query);

		$query = "UPDATE mangas SET Nota = Total_Notas / Qtd_Notas WHERE ID LIKE ".$id_manga;
		mysqli_query($mysqli, $query);
		
		setcookie($nome, $nota_manga);

	}
	else {
		$nome = "nota_manga_".$id_manga;
		setcookie($nome, $nota_manga);
		
		$query = "UPDATE mangas SET Total_Notas = Total_Notas + ".$nota_manga." WHERE ID LIKE ".$id_manga;
		mysqli_query($mysqli, $query);
		
		$query = "UPDATE mangas SET Qtd_Notas = Qtd_Notas + 1 WHERE ID LIKE ".$id_manga;
		mysqli_query($mysqli, $query);
		
		
		$query = "UPDATE mangas SET Nota = Total_Notas / Qtd_Notas WHERE ID LIKE ".$id_manga;
		mysqli_query($mysqli, $query);
	}
}

$generosConc = filter_input(INPUT_POST, 'generos', FILTER_SANITIZE_STRING);
setcookie('generos_selecionados', $generosConc, time() + 3600);

$index = 0;
$limite = 10;

$index_reset = filter_input(INPUT_POST, 'index_reset', FILTER_SANITIZE_NUMBER_INT);

if($index_reset) {
	$limite = $index_reset;
}

$array_lançamentos_in_fav = unserialize(filter_input(INPUT_POST, 'array_lançamentos_in_fav'));


foreach($array_lançamentos_in_fav as $manga_id) {
	
	$sql_code = "SELECT * FROM mangas WHERE ID LIKE $manga_id";
	$query = $mysqli->query($sql_code) or die ('Falha na execução do código SQL: ' . $mysqli->error);
	$manga = $query->fetch_assoc();
	
	$caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
	
	$array_capitulos = scandir($caminho_capitulos);
    $qtd_caps = count($array_capitulos)-2;
	
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
	if($index < $limite){
		$index++;
	echo '<!-- collection-item de mangás -->
	<div style="border-color:rgba(0,0,0,0);" class="collection-item container-manga">
	
		<div style="width:100%;height:100%;top:0px;position:relative;margin:0px" class="">
	
	
		   <!-- Opções do mangá -->
		   <div id="fixed-btn-pagina-navbar" style="z-index:4;transform:scale(0.91);top:-12px" class="floating-btn-pagina-navbar">
				<a class="btn-large btn-floating">
					<i style="color:inherit" onclick="showOptionsManga('. $manga['ID'] .', \'lançamentos\')" class="fa-sharp fa-solid fa-ellipsis"></i>
				</a>
	
				<div id="opcoes_manga'. $manga['ID'] .'lançamentos" style="width:max-content;height:58px;right:60px;top:37px;display:flex;flex-direction:row;z-index:-5;opacity:0;position:absolute;transition: opacity 0.5s, top 0.5s, transform 0.8s;transform: scale(0.5)" >
					
					<!-- BOTÃO DE FAVORITAR MANGÁ  -->
					<div style="list-style: none;margin-left:12px">
						<a onclick="addFavoritos('. $manga['ID'] .', \'ordenados_por_nota\')" style="background-color: rgb(18, 45, 70)" id="favoritar-pagina-manga" class="btn btn-floating hoverable">
							<i id="icon_favorite'. $manga['ID'] .'ordenados_por_nota" class="material-icons">';
								
									if(!isset($_SESSION)){
										session_start();
									}
	
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
									
								
								echo '
							</i>
						</a>  
					</div>
				</div>
			</div>
			<!-- painel de informações do mangá -->
			<div class="hoverable card manga-info-panel">
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
						<div style="width:max-content;height:max-content;position:relative;display:flex;flex-direction:row;overflow:visible" class="container-titulo-lista-manga">';
	
						
						krsort($arrayCapitulos, SORT_STRING);
								$array = array();
								foreach($arrayCapitulos as $capitulo_ordenado) {
									if($capitulo_ordenado !== "." && $capitulo_ordenado !== ".."){
										$capitulo = explode("Capitulos/", $capitulo_ordenado);
										$array[] = $capitulo[1];
									}
								}
								rsort($array);
								$index_caps = 0;
								foreach($array as $capitulo) {
									if($index_caps < 4){
										$index_caps++;
										echo '
											<form action=\'capitulo.php\' style=\'z-index:5\' method=\'GET\'>
											<input type=\'hidden\' name=\'capitulo\' value=\''. $capitulo .'\'>
												<input type=\'hidden\' name=\'dados_manga\' value=\''.$manga['ID'].'\'>
												<input type=\'hidden\' name=\'qtd_caps\' value=\''. $qtd_caps .'\'>
												';
												if($index_caps == 1){
													echo "<input type='submit' style='background-color:rgba(135, 170, 180, 1);border-style:double;border-color:rgba(175, 207, 215, 1);border-width:2px' class='scale-effect caps-lançamentos' value='Cap.".$capitulo."'>";
												}
												else{
													echo "<input type='submit' class='scale-effect caps-lançamentos' value='Cap.".$capitulo."'>";
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
							<span style="font-weight: 600;transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;" class="basic-text">'. $qtd_caps .'</span>
						</div>
						<!-- Pontuação do mangá  -->
						<div style="position:relative;order:5;margin-left:0px;left: 0px;" class="pontuacao-manga">
							<i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="star material-icons">star</i>
							<text style="font-weight: 600;transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;">'. number_format($manga["Nota"], 2, ',', '.') .'</text>
						</div>
							<!-- Pontuação do mangá  -->
						<div id="visualizacoes_pagina_navbar" style="position:relative;order:1;margin-left:0px;left: 0px;" class="pontuacao-manga">
							<i style="color:rgb(211, 255, 255);font-size: inherit;align-self: center;" class="fa-solid fa-eye"></i>
							<text style="font-weight: 600;transform:scale(0.85);color:rgb(137, 199, 199);align-self: center;margin-left: 8px;font-size: inherit;">'. number_format($manga["Visualizacoes"], 0, ',', '.') .'</text>
						</div>
	
					</div>
					<!-- Botão Sinopse  -->
					<button style="background-color: rgb(25, 56, 85);position:relative;order:10;align-self: center;margin-bottom: 0px;" class="btn waves-effect sinopse-button scale-effect hoverable"><span class="activator basic-text">Sinopse</span></button>
				</div>
	
				<!-- card-reveal que carregará a sinopse do mangá -->
				<div style="z-index:9;position:absolute;background-color: rgb(35, 44, 54);width: 100%; height: 100%;" class="card-reveal">
	
					<!-- Botão Voltar  -->
					<i style="color:white;position:absolute;font-size:var(--fonte-voltar);" class="material-icons botão-voltar-sinopse card-title">keyboard_return</i>
					<span style="color:white;position:absolute;font-size:var(--fonte-texto-voltar);" class="texto-voltar-sinopse card-title basic-text">Voltar</span>
	
					<!-- Descrição  -->
					<div style="border-radius:18px;position:absolute;background-color:rgb(54, 73, 92);" class="center descricao-manga"><text style="letter-spacing:inherit;font-size:inherit;margin:inherit;color:white;left:5%;right:5%;top:3%;position:absolute;font-family:\'Yanone Kaffeesatz\';" class="center">'. $manga["Sinopse"] .'</text></div>
	
				</div>
	
	
			</div>
		</div>
	</div>
	';
?>
	<script>
		var lenght_screen = screen.width;
		if(lenght_screen < 350){
			var elemento = document.querySelector("#visualizacoes_pagina_navbar");
			elemento.parentNode.removeChild(elemento);
		}
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
	</script>
<?php
	}
}


?>