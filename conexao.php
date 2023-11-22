<?php
$hostname = "localhost";
$bancodedados = "database";
$usuario = "root";
$senha = "";

$mysqli = mysqli_connect($hostname, $usuario, $senha, $bancodedados);

if ($mysqli->connect_errno) {
    echo "Falha ao conectar: (" . $mysqli->connect_errno .") " . $mysqli->connect_error;
}
?>
