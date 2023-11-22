<?php 

require_once('src/PHPMailer.php');
require_once('src/SMTP.php');
require_once('src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);


include "conexao.php";

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
$confirmar_senha = filter_input(INPUT_POST, 'confirmarSenha', FILTER_SANITIZE_STRING);

$username = $mysqli->real_escape_string($username);
$email = $mysqli->real_escape_string($email);
$senha = $mysqli->real_escape_string($senha);
$confirmar_senha = $mysqli->real_escape_string($confirmar_senha);

$sql_code = "SELECT nome FROM usuarios WHERE nome LIKE '$username'";
$query = $mysqli->query($sql_code)  or die("Falha na execução do código SQL: " . $mysqli->error);

if($username !== '' and $email !== '' and $senha !== '' and $confirmar_senha !== '') {
    if((strlen($username) > 30) ){
        echo '<title style="color: rgba(250, 23, 23, 1); font-size:120%;order:1;">** Escolha um nome com no máximo 30 caracteres!</title>';
    }
    else if(strlen($senha) < 6){
        echo '<title style="color: rgba(250, 23, 23, 1); font-size:120%;order:1;">** Escolha uma senha com no mínimo 6 caracteres!</title>';
    }
    else if($senha !== $confirmar_senha){
        echo '<title style="color: rgba(250, 23, 23, 1); font-size:120%;order:1;">** Senhas incompatíveis, você errou na digitação, mestre vesguinho!</title>';
    }

    else if($query->num_rows > 0) {
        echo '<title style="color: rgba(250, 23, 23, 1); font-size:120%;order:1;">** O nome de usuário já existe! Que mundo pequeno...</title>';
    }
    else {
        echo '<title style="color: rgba(140, 166, 175, 1); font-size:120%;order:1;">Um e-mail de confirmação foi enviado para '.$email.'</title>';
        echo '<title style="color: rgba(140, 166, 175, 1); font-size:120%;order:1;">Por favor, confirme seu e-mail para finalizar o cadastro!</title>';
        
        $senha_cript = password_hash($senha, PASSWORD_DEFAULT);


        $sql_code = "INSERT INTO usuarios ( nome, email, senha, situacao) VALUES ('$username','$email','$senha_cript',0)";
        $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        
        $sql_code = "SELECT ID FROM usuarios WHERE nome LIKE '$username'";
        $query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        $id = $query->fetch_assoc();
        $id = $id['ID'];


        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'andrewlincoln1900@gmail.com';
            $mail->Password = 'rrredyvhhbtcdazv';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('andrewlincoln1900@gmail.com');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Confirmação de e-mail';
            $mail->Body = 'Olá, '.$username.'!<br></br> Precisamos validar seu e-mail, para tal, clique <a href="http://localhost/alleyboss/ativa_cadastro.php?email='.$email.'&codigo='.$id.'">aqui</a>';
            $mail->AltBody = 'Olá, '.$username.'!\n\nPrecisamos validar seu e-mail, para tal, clique no link: http://localhost/alleyboss/ativa_cadastro.php';
            
            if($mail->send()) {
                echo 'E-mail enviado com sucesso';
            } else {
                echo 'E-mail não enviado';
            }
        
        } catch (Exception $e) {
            echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
        }
    }
    
    

}
else {
    
    echo '<title style="color: rgba(250, 23, 23, 1); font-size:120%;order:1;">** Preencha todos os campos!</title>';
}

?>