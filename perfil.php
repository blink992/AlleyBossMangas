
<body>
<?php
if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION['ID'])) {
    header('Location: index.php');
}

include "cabecalho.php";
include "conexao.php";
include "responsividade.php";

$username = $_SESSION['nome'];

if(isset($_POST['descrição_perfil'])) {
    $descricao = $_POST['descrição_perfil'];
    $sql_code = "UPDATE usuarios SET descricao='$descricao' WHERE nome LIKE '$username'";
    $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
}

$sql_code = "SELECT descricao FROM usuarios WHERE nome LIKE '$username'";
$query = $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
$query = $query->fetch_assoc();

$descricao = $query['descricao'];
$username = $_SESSION['nome'];
$id = $_SESSION['ID'];


if(isset($_POST['salvar_imagem'])) {
    $imagem = $_FILES['imagem'];
    $tamanho = $_FILES['imagem']['size'];

    $imagem_name = explode('.', $imagem['name']);

    if(($imagem_name[sizeof($imagem_name) - 1] != 'jpg') and ($imagem_name[sizeof($imagem_name) - 1] != 'png')) {
        die('Você não pode usar este tipo de arquivo!');
    } else {
        if($tamanho == 0){
            echo '<script>
                alert("Selecione uma imagem com menos de 2 Mb!! \nTÁ QUERENDO LOTAR NOSSO SERVIDOR!?");
            </script>';
        }else {
            $caminho = 'uploads/' . 'thumb_' . $username . '_' . $id .'.'. 'jpg';
            move_uploaded_file($imagem['tmp_name'], $caminho);
    
            $sql_code = "UPDATE usuarios SET thumb='$caminho' WHERE nome LIKE '$username'";
            $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
            
        }
    }
    
}

$sql_code = "SELECT thumb FROM usuarios WHERE nome LIKE '$username'";
$sql_query = $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
$sql_query = $sql_query->fetch_assoc();
$caminho = $sql_query['thumb'];

if($caminho == NULL) {
    $caminho = 'Imagens/default-avatar.jpg';
}

?>
    <!-- Imagem de fundo  -->
    <div style="z-index:0" class="background-box background-image"></div>
    <!-- Barra de navegação  -->
    <?php include "barra_navegação.php" ?>
        <img id="img_perfil" src="<?= $caminho ?>" style="object-fit:cover;top:30px" class="imagem-perfil scale-effect" alt="Avatar">

        <div style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;padding-bottom:35px;" class="painel-principal">
            <div id="div_seletor" style="z-index:4;display: none;flex-direction:row;justify-content:center;background-color:rgb(28, 33, 40);width:max-content;height:max-content;border-radius:20px;padding:20px;padding-bottom:10px;padding-top:10px;" class="teste">
                <i id="close_div_seletor" class="fa-solid fa-xmark" style="align-self:center;margin-right:20px;color:rgb(200, 235, 235);font-size:150%;cursor:pointer"></i>
                <form style="align-self:center;position:relative;display:flex;margin:0px" action="perfil.php" method="POST" enctype="multipart/form-data">
                    <label id="seletor_label" onclick="showNameImg()" for="seletor_arquivo" style="cursor:pointer;background-color:rgb(53, 59, 72);border: 3px outset rgb(62, 67, 79);padding:5px;padding-right:10px;padding-left:10px;border-radius:15px;font-size:130%;margin-right:25px" class="basic-text scale-effect">Selecionar imagem...</label>
                    <input  style="display:none" id="seletor_arquivo" type="file" required name="imagem">
                    <input style="cursor:pointer;background-color:rgb(53, 59, 72);border: 3px outset rgb(62, 67, 79);padding:5px;padding-right:10px;padding-left:10px;border-radius:15px;font-size:130%" class="basic-text scale-effect" type="submit" name="salvar_imagem" value="Salvar">
                </form>
            </div>

            <h1 style="position:relative;margin:0px;margin-top:100px;align-self:center" class="basic-text"><?= $username ?></h1>
            <?php 
                if($descricao !== NULL) {

                    echo '<h3 id="descrição" style="cursor:pointer;position:relative;margin:0px;margin-top:5px;text-align:center;align-self:center;color:rgba(128, 159, 170, 1)" class="basic-text descricao-perfil">'. $descricao .'
                    <i onclick="adicioneDescricao()" style="margin-top:25px;margin-left:15px;color:rgba(158, 187, 195, 1)" class="fa-solid fa-pen-to-square icon-descricao-perfil"></i>
                    </h3>
                    ';
                }
                else {
                    echo '<h5 id="descrição" onclick="adicioneDescricao()" style="position:relative;margin:0px;margin-top:5px;align-self:center;color:rgba(128, 159, 170, 1);cursor:pointer" class="basic-text">{ Adicione uma descrição }</h5>';
                }
            ?>
            <div id="descrição_input"></div>
            <div style="display: flex; flex-direction: row;position:relative; justify-content:center">
            </div>
            
            <div class="paineis-favoritos-historico">
                
                <?php include "favoritos_perfil.php" ?>
                <?php include "recentes_perfil.php" ?>

            </div>
        
            
        
        </div>
    
<script>
    $('#seletor_arquivo').on('change', function() {
        var seletor = document.getElementById('seletor_label');
        seletor.innerHTML = 'Selecionado';
        seletor.style.backgroundColor = 'rgb(39, 43, 51)';
    })

    var close = document.getElementById('close_div_seletor');
    close.addEventListener('click', function() {

        var div_seletor = document.getElementById('div_seletor');

        div_seletor.style.display = "none";
    });

    var img_perfil = document.getElementById('img_perfil');
    img_perfil.addEventListener('click', function(e) {
        
        var div_seletor = document.getElementById('div_seletor');
        
        div_seletor.style.position = "fixed";
        div_seletor.style.display = "flex";
        div_seletor.style.top = e.clientY + "px";
        div_seletor.style.left = e.clientX + "px";
        
    })
    
</script>
    <?php include "java_import.php" ?>
</body>
</html>