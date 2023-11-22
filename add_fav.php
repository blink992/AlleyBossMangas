<?php
    include "conexao.php";

    if(!isset($_SESSION)){
        session_start();
    }

    
    if(isset($_SESSION['ID'])){
        $id_manga_fav = filter_input(INPUT_POST, 'id_manga_favoritado', FILTER_SANITIZE_NUMBER_INT);
        
        if ($id_manga_fav !== false) {
            $is_favorited = filter_input(INPUT_POST, 'is_favorited', FILTER_SANITIZE_STRING);
            if($is_favorited == "true") {
                $username = $_SESSION['nome'];
                $sql_code = "SELECT * FROM $username WHERE favoritos LIKE $id_manga_fav";
                $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                if($query->num_rows == 0){                            
                    $sql_code = "INSERT INTO $username (favoritos) VALUES ($id_manga_fav)";
                    $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                }
                else {
                    $sql_code = "DELETE FROM $username WHERE favoritos LIKE $id_manga_fav";
                    $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
                }
            }
            else {
                $sql_code = "DELETE FROM $username WHERE favoritos LIKE $id_manga_fav";
                $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
            }
        }
    }
    else {
            $id_manga_fav = filter_input(INPUT_POST, 'id_manga_favoritado', FILTER_SANITIZE_NUMBER_INT);
        
            if ($id_manga_fav !== false) {
                $is_favorited = filter_input(INPUT_POST, 'is_favorited', FILTER_SANITIZE_STRING);
                if($is_favorited == "true") {
                    
                    if(isset($_COOKIE['array_favoritos'])) {
                        $array_favoritos = unserialize($_COOKIE['array_favoritos']);
                        if(in_array($id_manga_fav, $array_favoritos)) {
                            $key = array_search($id_manga_fav, $array_favoritos);
                            unset($array_favoritos[$key]);
                        }
                        $array_favoritos[] = $id_manga_fav;
                        setcookie('array_favoritos', serialize($array_favoritos), time() + (86400 * 60));
                    }
                    else {
                        $array_favoritos = array();
                        $array_favoritos[] = $id_manga_fav;
                        setcookie('array_favoritos', serialize($array_favoritos), time() + (86400 * 60));
                    }

                }
                else {
                    $array_favoritos = unserialize($_COOKIE['array_favoritos']);
                    
                    $key = array_search($id_manga_fav, $array_favoritos);
                    unset($array_favoritos[$key]);
        
                    setcookie('array_favoritos', serialize($array_favoritos), time() + (86400 * 60));

                }
            }
    }
?>