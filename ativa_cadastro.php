<?php
include "cabecalho.php";
include "responsividade.php";
include "conexao.php";

if(isset($_GET['codigo']) and isset($_GET['email'])){
    $email = $_GET['email'];
    $id = $_GET['codigo'];

    $sql_code = "SELECT * FROM usuarios WHERE ID LIKE $id";
    $query = $mysqli->query($sql_code)  or die("Falha na execução do código SQL: " . $mysqli->error);
    $query = $query->fetch_assoc();
    $username = $query['nome'];
    if($query['situacao'] == 0){
        
        $sql_code = "UPDATE usuarios SET situacao=1 WHERE ID LIKE $id";
        $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
    
        $sql_code = "CREATE TABLE $username (
            ID smallint(255) PRIMARY KEY AUTO_INCREMENT,
            favoritos smallint(255),
            historico_id int(255) NULL,
            historico_cap int(255) NULL,
            historico_data varchar(255) NULL, 
        )";
        $mysqli->query($sql_code)  or die("Falha na execução do código SQL: " . $mysqli->error);
    }
}
?>

<body>
    <!-- Imagem de fundo  -->
    <div style="z-index:0" class="background-box background-image"></div>
    <!-- Barra de navegação  -->
    <?php include "barra_navegação.php" ?>

    <div class="mensagem-cadastro-ativo">
        <p style="font-size:150%;text-align:center;position:relative;" class="basic-text">
            Parabéns, <?= $username ?>, você validou o seu cadastro!
            <br>
            Agora você pode voltar para a tela inicial usando a barra de navegação e então fazer seu login!
        </p>
        <i style="position:relative;color:green;align-self:center;font-size:250%" class="fa-solid fa-check"></i>
    </div>

    <?php include "java_import.php" ?>
</body>