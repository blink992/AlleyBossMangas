<?php

include "conexao.php";

if(!isset($_SESSION)) {
    session_start();
}

$username = $_SESSION['nome'];

$exibir = filter_input(INPUT_POST, 'exibir', FILTER_SANITIZE_STRING);

if($exibir == "true") {

    $sql_code = "SELECT imagem, tipo FROM thumb_users WHERE username LIKE '$username'";
    $query = $mysqli->query($sql_code) or die ("Falha na execução do código SQL: " . $mysqli->error);
    $query = $query->fetch_assoc();
    $imagem = $query['imagem'];
    $tipo = $query['tipo'];
    
    Header("Content-type: ".$tipo."");
    echo $imagem;
}

?>