<?php

include "conexao.php";

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

if($email !== '' || $senha !== '') {
    if(strlen($email) == 0){
        echo '<span style="color: rgba(250, 23, 23, 1); font-size:120%;order:1;" class="basic-text">** Preencha seu usuário</span>';
    }
    else if(strlen($senha) == 0){
        echo '<span style="color: rgba(250, 23, 23, 1); font-size:120%;order:6;" class="basic-text">** Preencha sua senha</span>';
    }
    else {
        $email = $mysqli->real_escape_string($email);
        $senha = $mysqli->real_escape_string($senha);
        
        $sql_code = "SELECT senha FROM usuarios WHERE email = '$email' OR nome = '$email'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        
        if($sql_query->num_rows > 0){
            $senha_hash = $sql_query->fetch_assoc();
            $senha_hash = $senha_hash['senha'];
            if(password_verify("$senha", "$senha_hash")){
    
                $sql_code = "SELECT * FROM usuarios WHERE email = '$email' OR nome = '$email'";
                $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
    
                $usuario = $sql_query->fetch_array();
    
                if($usuario['situacao'] == 1) {
                    if(!isset($_SESSION)) {
                        session_start();
                    }
                    $_SESSION['ID'] = $usuario['ID'];
                    $_SESSION['nome'] = $usuario['nome'];
                    
                    echo '<script>
                        document.location.reload();
                    </script>';
                }
                else {
                    echo '<p class="basic-text" style="color: rgba(250, 23, 23, 1); font-size:120%;order:6;">** Olá, '.$usuario['nome'].', esqueceu de confirmar seu e-mail bobinho(a)? <br>Verifique sua caixa de e-mail e confirme o cadastro!</p>';
                }
    
            }
        }
        else {
            echo '<span style="color: rgba(250, 23, 23, 1); font-size:120%;order:6;" class="basic-text">Usuário não encontrado. Verifique se digitou seus dados corretamente!</span>';
        }

    }
    
}
else {
    echo '<span style="color: rgba(250, 23, 23, 1); font-size:120%;order:6;" class="basic-text">** Preencha todos os campos!</span>';
}

$logout = filter_input(INPUT_POST, 'logout');
if($logout){
    if(!isset($_SESSION)){
        session_start();
    }
    session_destroy();
    echo '<script>
    document.location.reload();
</script>';
    
}

?>