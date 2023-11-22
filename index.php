
<?php
if(!isset($_SESSION)){
    session_start();
}

if(isset($_COOKIE["10_ultimos_mangas"])){
    
    $cookie = unserialize($_COOKIE["10_ultimos_mangas"]);
}

    include "cabecalho.php";
    include "responsividade.php";   
    
    ini_set('default_charset','UTF-8');

    $query = "SELECT * FROM mangas";
    $mangas = $mysqli->query($query) or die($mysqli->error);

    $consulta_favoritos = "SELECT * FROM mangas_favoritos";
    $mangas_fav = $mysqli->query($consulta_favoritos) or die($mysqli->error);
    $qtd_mangas_fav = $mangas_fav->num_rows;


    $mangas = $mysqli->query($query) or die($mysqli->error);
    $qtd_mangas = $mangas->num_rows;

    ?>

    <title>Mangá Boss | Leitor de Mangás Online - Ler Mangás Grátis</title>

</head>
<script>

    $length_screen = screen.width

    if ($length_screen < 475) {
        var numPadding =  -145
        var numDist = -45
        var isFullWidth = false
        var qtdNumVisible = 13
        var inDuration = 0
        var outDuration = 0
    }
    else if (($length_screen > 475) && ($length_screen < 620)) {
        var numPadding =  -140
        var numDist = -40
        var isFullWidth = false
        var qtdNumVisible = 20
        var inDuration = 600
        var outDuration = 800
    }
    else if (($length_screen > 620) && ($length_screen < 870)) {
        var numPadding =  -140
        var numDist = -45
        var isFullWidth = false
        var qtdNumVisible = 15
        var inDuration = 600
        var outDuration = 800
    }
    else if ($length_screen > 870) {
        var numPadding =  -130
        var numDist = -45
        var isFullWidth = false
        var qtdNumVisible = 20
        var inDuration = 600
        var outDuration = 800

    }

</script>


<body id="screen" style="height: max-content; width: 100%;" >
    <div  style="display:flex;flex-direction:column;width:100%;" class="container-principal">


        <!-- Imagem de fundo  -->
        <div style="z-index:0" class="background-box background-image"></div>
        <!-- Barra de navegação  -->
        <?php include "barra_navegação.php" ?>
        <?php
            if(isset($_COOKIE["10_ultimos_mangas"])){
                $justify = "space-around";
            }
            else {
                $justify = "none";
            }
        ?>


            <!-- Menu favoritos e recentes  -->
            <div style="justify-content:<?= $justify ?>" class="container-favoritos-e-recentes">
             <!-- Menu vertical direito  -->
             <?php
                    if(isset($_COOKIE["10_ultimos_mangas"])){
                        echo '<div class="container-vertical-direito">';
                        
                        // Painel de recentes
                        include "recentes.php";
                        echo '</div>';
                }
                
            ?>
            <!-- Menu vertical esquerdo  -->
                <?php
                    if(!isset($_SESSION['ID'])){
                        if(isset($_COOKIE['array_favoritos'])){
    
                            $array_favs = unserialize($_COOKIE['array_favoritos']);
                            if(count($array_favs) > 0) {
                                echo '<div class="container-vertical-esquerdo">';
                                // Painel de favoritos
                                include "favoritos.php";
                                echo '</div>';
                            }
                        }
                    }
                    else {
                        $nome = $_SESSION['nome'];
                        $sql_code = "SELECT favoritos FROM $nome";
                        $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

                        if(($query->num_rows) > 0){
                            echo '<div class="container-vertical-esquerdo">';
                            // Painel de favoritos
                            include "favoritos.php";
                            echo '</div>';
                        }

                    }
            ?>
        </div>
    <!-- Menus verticais  -->
    <div class="container-menus-verticais">
        <!-- Menu vertical direito  -->
        <div style="display:flex;flex-direction:column" class="container-vertical-direito">
            <!-- MENU DESTAQUES -->
            <!-- <div style="margin-bottom:15px;" class="menu-ranqueado">
                <div style="border-color:rgba(0,0,0,0);" class="seção-header-lançamentos">
                    <title style="font-size:inherit;display:flex;width:50%;left:25%;padding-top:5px;" class="titulo-lançamentos center">Recomendação do diaii</title>
                </div>
            </div> -->

            <!-- MENU RANQUEADO  -->
            <div style="margin-bottom:15px;" class="menu-ranqueado">

                <div style="border-color:rgba(0,0,0,0);" class="seção-header-lançamentos">
                    <span style="font-size:inherit;display:flex;width:50%;left:25%;padding-top:5px;" class="titulo-lançamentos center basic-text">#10 melhores notas</span>
                </div>
                <div class="collapsible z-depth-0">
                    <li style="list-style: none;width: 100%;height: 100%;position: relative;">
                        <div style="background-color:transparent;" class="collapsible-header "><i style="transform:scale(1.5);color:rgb(194, 194, 194);width: 20%;left:40%;position: relative;" class="material-icons">arrow_drop_down</i></div>
                        <div class="collapsible-body">
                            <div style="padding:3px;padding-top:5px;" class="ordenador">
                                <?php include "menu_ranqueado.php" ?>
                            </div>
                            <div style="height: 50px;width:100%;display:flex;justify-content:center;position:relative">
                                <button style="position:relative;left:0px;display:flex;justify-content:center;align-self:center;font-size:250%;border:none;box-shadow:none" class="circle transparent btn collapsible-header">
                                    <i style="font-size:inherit;color:aliceblue" class="material-icons">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                    </li>
                </div>
            </div>
            <!-- MENU DE MAIS LIDOS  -->
            <div class="menu-mais-lidos">
                <div style="border-color:rgba(0,0,0,0);" class="seção-header-lançamentos">
                    <span style="font-size:inherit;display:flex;width:50%;left:25%;padding-top:5px" class="titulo-lançamentos center basic-text">#10 mais lidos</span>
                </div>
                <div class="collapsible z-depth-0">
                    <li style="list-style: none;width: 100%;height: 100%;position: relative;">
                        <div style="background-color:transparent" class="collapsible-header "><i style="transform:scale(1.5);color:rgb(194, 194, 194);width: 20%;left:40%;position: relative;" class="material-icons">arrow_drop_down</i></div>
                        <div class="collapsible-body">
                            <div style="padding:3px;padding-top:5px;" class="ordenador-mais-lidos">
                                <?php include "menu_mais_lidos.php" ?>
                            </div>
                            <div style="height: 50px;width:100%;display:flex;justify-content:center;position:relative">
                                <button style="position:relative;left:0px;display:flex;justify-content:center;align-self:center;font-size:250%;border:none;box-shadow:none" class="collapsible-header btn circle transparent">
                                    <i style="font-size:inherit;color:aliceblue" class="material-icons">arrow_drop_up</i>
                                </button>
                            </div>
                        </div>
                    </li>
                </div>
                
            </div>
            
            
        </div>
        

        <!-- Menu vertical esquerdo  -->
        <div style="display:flex;flex-direction:column" class="container-vertical-esquerdo">
            <!-- Seção que vai tratar do painel de Lançamentos -->
            <div style="align-self:center;left:initial;border-color:rgba(0,0,0,0);display:flex;flex-direction:column" class="hoverable lançamentos z-depth-5">
                
                <!-- Título da seção Lançamentos  -->
                <div style="border-color:rgba(0,0,0,0);align-self:center;justify-content:center;display:flex;" class="seção-header-lançamentos">
                    <i class="fa-solid fa-circle-plus" style="position:relative;height:max-content;font-size:78%;color:rgba(172, 177, 229, 1);align-self:center;margin-right:12px"></i>
                    <span style="font-size:inherit;align-self:center;width:max-content;left:initial;position:relative" class="titulo-lançamentos basic-text">Atualizados recentemente</span>
                </div>
                
                <?php include "lançamentos.php" ?>
                
            </div>
        </div>
    </div>
    
    <!-- Importando o JavaScript  -->
    <?php
        include "java_import.php";
    ?>


</body >

</html>
