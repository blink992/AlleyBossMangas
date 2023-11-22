<?php   
    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['ID'])){
        $nome = $_SESSION['nome'];
        $sql_code = "SELECT favoritos FROM $nome WHERE favoritos <> 'NULL'";
        $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $mangas_favoritados = array();
        
        while($id_manga = $query->fetch_array()){
            $mangas_favoritados[] = $id_manga['favoritos'];
        }
    }
    else {
        if(isset($_COOKIE['array_favoritos'])) {
            $mangas_favoritados = unserialize($_COOKIE['array_favoritos']);
        }
    }
?>

<div style="order:0;height:215px;justify-content:center;display:flex;position:relative;" class="container-menu_favoritos">
    <nav style="overflow:hidden;align-self: center;position:relative;left:initial" id="menu-h" class="">
        <div id="div-superior" style="display:flex;flex-direction:row;justify-content:center" class="">
            <i class="material-icons" style="font-size:78%;color:rgba(172, 177, 229, 1);align-self:center;margin-right:12px">favorite</i>
            <span style="align-self:center;position:relative;left:initial" class="basic-text">
                Favoritos
            </span>
        </div>
        <div id="div-inferior">       
            <div style="display:flex;justify-content:left;flex-direction:column" class="carousel cat-favoritos">
                <?php foreach($mangas_favoritados as $id_manga_favoritado) : ?>
                    <?php
                        $consulta_favs = "SELECT * FROM mangas WHERE ID LIKE ".$id_manga_favoritado;
                        $manga_fav = mysqli_query($mysqli,$consulta_favs) or die($mysqli->error); 
                        $manga = $manga_fav->fetch_array();
                    ?>

                    <span style="user-select:none;position:absolute;align-self:baseline;float:left;top:-10px" class="carousel-item">
                        <img src="<?= $manga['Thumb'] ?>" alt="Thumb-Favoritos">
                        <form action="pagina_manga.php" method="POST">
                            <button type="int" name="dados" value="<?= $manga['ID'] ?>" style="width: 90px;height: 90%;top: 40px;left: 40px;position: absolute;border:none" class="transparent">
                            </button>
                        </form>
                            
                    </span>
                <?php endforeach ?>
               
            </div>
        </div>
    </nav>
</div>
