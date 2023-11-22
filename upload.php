<?php

include "conexao.php";

if(!isset($_SESSION)) {
    session_start();
}

$username = $_SESSION['nome'];
// UPLOAD DE IMAGEM 

$imagem = $_FILES['imagem']['tmp_name'];
$tamanho = $_FILES['imagem']['size'];
$tipo = $_FILES['imagem']['type'];
$nome = $_FILES['imagem']['name'];

if ( $imagem != "none" )
{
    $fp = fopen($imagem, "rb");
    $conteudo = fread($fp, $tamanho);
    $conteudo = addslashes($conteudo);
    fclose($fp);

    $sql_code = "SELECT id FROM thumb_users WHERE username LIKE '$username'";
    $query = $mysqli->query($sql_code);

    if($query->num_rows > 0) 
        $sql_code = "UPDATE thumb_users SET imagem='$conteudo',tipo='$tipo' WHERE username LIKE '$username'";
    else
        $sql_code = "INSERT INTO thumb_users (id, username, imagem, tipo) VALUES (NULL,'$username', '$conteudo', '$tipo')";
        

    $mysqli->query($sql_code) or die("Algo deu errado ao inserir o registro. Tente novamente.");
    echo 'Registro inserido com sucesso!';

    if($mysqli->affected_rows > 0)
        print "A imagem foi salva na base de dados.";
    else 
        print "Não foi possível salvar a imagem na base de dados.";

}
else
    print "Não foi possível carregar a imagem.";

header("Location: gera_imagem.php");
?>