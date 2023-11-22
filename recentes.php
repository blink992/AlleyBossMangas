<div style="order:0;height:215px;width:100%;align-self:center;justify-content:center;display:flex;position:relative" class="container-menu_favoritos">
    <nav style="overflow:hidden;position:relative;align-self:center;left:initial" id="menu-h" class="">
        <div id="div-superior" style="display:flex;flex-direction:row;justify-content:center" class="">
            <i class="fa-solid fa-clock" style="height:max-content;font-size:78%;color:rgba(172, 177, 229, 1);align-self:center;margin-right:12px"></i>
            <span style="align-self:center;position:relative;left:initial" class="basic-text">
                Continuar Leitura
            </span>
        </div>
        <div id="div-inferior" class="">       
            <div id="carousel" style="display:flex;justify-content:left;flex-direction:column" class="carousel cat-favoritos">
                <?php 
                    $recentes = $_COOKIE["10_ultimos_mangas"];
                    $recentes = unserialize($recentes);
                    $index = 0;
                ?>
                <?php foreach($recentes as $id) : ?>
                    <?php $index++ ?>
                    <?php if($index < 11) { ?>
                    <?php
                        $query = "SELECT * FROM mangas WHERE ID LIKE $id";
                        $mangas_recentes = mysqli_query($mysqli,$query) or die($mysqli->error); 
                        $manga = $mangas_recentes->fetch_array();

                        $ultimo_cap = unserialize($_COOKIE["ultimos_caps"]);

                        $caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/";
                        $array_capitulos = scandir($caminho_capitulos);
                        $qtd_caps = count($array_capitulos)-2;
                    ?>

                    <span style="user-select:none;position:absolute;align-self:baseline;float:left;top:-10px;" class="carousel-item" href="#one!">
                        <div>
                            <img src="<?= $manga['Thumb'] ?>" alt="Thumb-Favoritos">
                            <form action="pagina_manga.php" method="POST">
                                <button type="int" name="dados" value="<?= $manga['ID'] ?>" style="width: 90px;height: 90%;top: 40px;left: 50px;position: absolute;border:none" class="transparent">
                                </button>
                            </form>
                            
                            <form action="capitulo.php" style="display:flex;justify-content:center;margin-right:10px;position:relative;height:max-content;" method="GET">
                                <button type="submit" name="capitulo" value="<?= $ultimo_cap[$manga['ID']] ?>" style="border: solid 3px rgba(52, 55, 73, 1);background-color:rgb(60, 74, 82);border-radius:8px;position:relative;bottom:5px;z-index:5;align-self:center;margin-right:5px; color:rgb(230, 255, 255)" class="scale-effect caps-recentes">Cap.<?= $ultimo_cap[$manga['ID']] ?></button>
                                <?php
                                if(is_dir($caminho_capitulos = "./Mangas/".$manga['Nome_Pasta']."/Capitulos/". ($ultimo_cap[$manga["ID"]] + 1))) {                                
                                ?>
                                    <button type="submit" name="capitulo" value="<?= $ultimo_cap[$manga["ID"]] + 1 ?>" style="z-index:5;position:relative;bottom:5px;background-color:rgb(60, 74, 82);z-index:5;align-self:center;width:max-content;height:max-content" class="scale-effect caps-recentes"><i style="height:auto;font-size:125%;color:rgba(179, 180, 185, 1)" class="fa-solid fa-forward"></i></button>
                                <?php } ?>
                                <input type="hidden" name="dados_manga" value="<?= $manga['ID'] ?>">
                                <input type="hidden" name="qtd_caps" value="<?= $qtd_caps ?>">
                            </form>
                        </div>
                    </span>
                    <?php } ?>
                <?php endforeach ?>
                    
            
            </div>
        </div>
    </nav>
</div>
