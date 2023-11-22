<!-- Barra de navegação  -->
<nav id="navbar" style="transition: top 0.5s, position 0.5s;order:-5;z-index:7;position:fixed;background-color: rgb(22, 26, 29);display:block;height:58px;width:100%;top:0px" class="nav hoverable z-depth-5">

    <div class="nav-wrapper" style="height:100%;position:relative;padding:0px">
        <div style="display:flex;height:100%;width:100%;position:relative;flex-direction:row;justify-content:space-between;">
            <a id="icon-home" href="index.php" style="font-size:240%;height:100%;order:0;left:8px;position:relative;display:flex;flex-direction:row;width:max-content" ><img style="height:95%;border-radius:20px;font-size:inherit;display:flex;margin-left: 15px;" src="./icons/LOGO_3.2.png" alt="logo" class="center"></img></a>
 
            <div style="order: 10;display:flex;flex-direction:row;justify-content:end;align-self:center;float:right;right:20px;width:max-content;position:relative">
                <i onclick="expandSearchBar()" style="cursor:pointer;order:10;align-self:center;height:max-content;width:max-content;right:0px;position:relative;margin-left:20px" class="fa-solid fa-magnifying-glass"></i>
                <form id="form-pesquisa-manga" method="POST" action="" style="align-self:center">
                    <input onfocus="showPesquisa()" id="pesquisa-manga" style="order:0" type="text" name="pesquisa-manga" autocomplete="off" placeholder="O que desejas, mestre?..." class="browser-default">
                </form>
            </div>

            <div id="opcoes-navbar" class="opcoes-navbar" style="order:5;display:flex;flex-direction:row;">
                <a onmouseenter="showHiddenMangaOpcoes()" onclick="showHiddenMangaOpcoes()" style="height:100%;order:10;align-self: center;cursor: pointer;" class="manga-navbar"><span style="height:95%;padding:12px;font-size:inherit;border-radius:20px;width:max-content;display:flex;" class="center nav-bar basic-text">Mangás</span></a>
                <a onmouseenter="showHiddenMinhasLeiturasOpcoes()" onclick="showHiddenMinhasLeiturasOpcoes()" style="height:100%;order:1;display:flex;justify-content:center;position:relative;cursor: pointer;display:flex;justify-content:center" class="minhas-leituras-navbar"><span style="height:95%;padding:12px;font-size:inherit;border-radius:20px;width:max-content;display:flex;align-self:center" class="nav-bar basic-text">Meu Perfil</span></a>
            </div>
        </div>
    </div>
</nav>
<div style="height:58px;"></div>

<div id="pesquisa" style="position:fixed;" class="pesquisa-mangas">
    <div onblur="hiddenPesquisa()" style="padding-top:120px;top:-80px" class="resultado">

    </div>
</div>

<div  onmouseleave="showHiddenMangaOpcoes()" id="navbar_mangas" class="nav" style="transition:top 0.5s;z-index:-5;width:100%;height:80px;order:10;top:-50px;position:fixed;display: none;justify-content: center;">
    <div class="container-opcoes-manga-navbar">
        <div style="width: max-content;height:max-content;align-self:center;display: flex;position: absolutes;padding: 10px;border-radius:20px;border-bottom-left-radius:50px;border-bottom-right-radius: 50px;z-index: 4;background-color: rgb(22, 26, 29);">
            <a class="opcao-mais-vistos-navbar scale-effect-small" href="ordenados_por_visualizações.php"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Mais Lidos</span></a>
            <a class="opcao-todos-navbar scale-effect-small" href="ordenados_por_nome.php"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Todos</span></a>
            <a class="opcao-melhores-notas-navbar scale-effect-small" href="ordenados_por_nota.php"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Melhores Notas</span></a>
        </div>
    </div>
</div>

<div  onmouseleave="showHiddenMinhasLeiturasOpcoes()" id="navbar_minhas_leituras" class="nav" style="transition:top 0.5s;z-index:-5;width:100%;height:80px;order:10;top:-50px;position:fixed;display: none;justify-content: center;">
    <div class="container-opcoes-minhas-leituras-navbar">
        <div style="width: max-content;height:max-content;align-self:center;display: flex;position: absolutes;padding: 10px;border-radius:20px;border-bottom-left-radius:50px;border-bottom-right-radius: 50px;z-index: 4;background-color: rgb(22, 26, 29);">
            <?php
            if(!isset($_SESSION['ID'])) {
                ?>
            <a onclick="showLoginPanel()" class="opcao-favoritos-navbar scale-effect-small" href="#"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Entrar</span></a>
            <a onclick="showRegisterPanel()" class="opcao-salvos-navbar scale-effect-small" href="#"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Cadastrar</span></a>
            <?php } ?>
            <?php
            if(isset($_SESSION['ID'])){
            ?>
            <a class="opcao-favoritos-navbar scale-effect-small" href="perfil.php"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Ver Perfil</span></a>
            <a onclick="logout()" class="opcao-salvos-navbar scale-effect-small" href="#"><span style="font-size: inherit;color:inherit;font-weight:inherit" class="basic-text">Sair</span></a>
            <?php } ?>
        </div>
    </div>
</div>

<div id="login-panel" class="login-panel">
    <i onclick="showLoginPanel()" style="color: rgba(176, 195, 196, 1);position:absolute;top:10px;left:15px;font-size:190%;cursor:pointer" class="material-icons">keyboard_return</i>
    <span style="align-self: center; font-size:165%;color:rgba(176, 195, 196, 1)" class="basic-text">Fazer Login</span>
    <div style="order:0;display: flex;flex-direction:column;justify-content-center;align-self:center;width:90%">
        <label style="font-size:110%;margin-top:20px">Usuário</label>
            <input id="input_email" style="outline:0;box-shadow: 0 0 0 0;height:30px;width:95%;padding-left:10px;padding-right:10px;align-self:center; background-color:rgba(47, 47, 50, 1);border:3px inset #63666e;border-radius: 20px;color:#9e9e9e" type="text" name="email" autocomplete="on" placeholder="Informe seu e-mail ou nome de usuário" >
        </div>
        <div style="order:5;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%">
            <label style="font-size:110%;margin-top:20px">Senha</label>
            <input id="input_senha" style="outline:0;box-shadow: 0 0 0 0;height:30px;width:95%;padding-left:10px;padding-right:10px;align-self:center; background-color:rgba(47, 47, 50, 1);border:3px inset #63666e;border-radius: 20px;color:#9e9e9e" type="password" name="senha" autocomplete="off" placeholder="Informe sua senha" >
        </div>          
        <div style="order:6;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%" class="login">
        </div>
        <div style="order:10;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%">
            <button onclick="login()" style="background-color:rgba(62, 60, 67, 1);border-radius:15px;border:none;padding:6px;padding-left:20px;padding-right:20px;margin-top:15px;margin-bottom:15px; width:max-content;align-self:center" class="scale-effect" type="submit">
                <span style="font-size:125%" class="center basic-text">
                    Entrar
                </span>
            </button>
        </div>
</div>

<div id="register-panel" class="login-panel">
    <i onclick="showRegisterPanel()" style="color: rgba(176, 195, 196, 1);position:absolute;top:10px;left:15px;font-size:190%;cursor:pointer" class="material-icons">keyboard_return</i>
    <span style="align-self: center; font-size:165%;color:rgba(176, 195, 196, 1)" class="basic-text">Cadastrar</span>
    <div style="order:0;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%">
        <label style="font-size:110%;margin-top:20px">Nome de usuário</label>
            <input id="input_username_cadastro" style="outline:0;box-shadow: 0 0 0 0;height:30px;width:95%;padding-left:10px;padding-right:10px;align-self:center; background-color:rgba(47, 47, 50, 1);border:3px inset #63666e;border-radius: 20px;color:#9e9e9e" type="text" name="username" autocomplete="on" placeholder="Informe seu nome de usuário" >
        </div>
        <div style="order:0;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%">
        <label style="font-size:110%;margin-top:20px">E-mail</label>
            <input id="input_email_cadastro" style="outline:0;box-shadow: 0 0 0 0;height:30px;width:95%;padding-left:10px;padding-right:10px;align-self:center; background-color:rgba(47, 47, 50, 1);border:3px inset #63666e;border-radius: 20px;color:#9e9e9e" type="text" name="email" autocomplete="on" placeholder="Informe seu e-mail" >
        </div>
        <div style="order:5;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%">
            <label style="font-size:110%;margin-top:20px">Senha (mínimo de 6 caracteres)</label>
            <input id="input_senha_cadastro" style="outline:0;box-shadow: 0 0 0 0;height:30px;width:95%;padding-left:10px;padding-right:10px;align-self:center; background-color:rgba(47, 47, 50, 1);border:3px inset #63666e;border-radius: 20px;color:#9e9e9e" type="password" name="senha" autocomplete="on" placeholder="Informe sua senha" >
        </div>
        <div style="order:5;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%">
            <label style="font-size:110%;margin-top:20px">Confirme sua senha</label>
            <input id="input_confirmar_senha_cadastro" style="outline:0;box-shadow: 0 0 0 0;height:30px;width:95%;padding-left:10px;padding-right:10px;align-self:center; background-color:rgba(47, 47, 50, 1);border:3px inset #63666e;border-radius: 20px;color:#9e9e9e" type="password" name="confirmar_senha" autocomplete="on" placeholder="Repita sua senha" >
        </div>
        <div style="order:6;display: flex;flex-direction:column;justify-content:center;align-self:center;width:90%" class="cadastro">
        </div>
        <div style="order:10;display: flex;flex-direction:column;justify-content-center;align-self:center;width:90%">
            <button onclick="register()" style="background-color:rgba(62, 60, 67, 1);border-radius:15px;border:none;padding:6px;padding-left:20px;padding-right:20px;margin-top:15px;margin-bottom:15px; width:max-content;align-self:center" class="scale-effect" type="submit">
                <span style="font-size:125%" class="center basic-text">
                    Registre-se
                </span>
            </button>
        </div>
</div>

<script type="text/javascript">
    function showLoginPanel() {
        var loginPanel = document.getElementById('login-panel');

        if(loginPanel.style.visibility !== 'visible') {
            loginPanel.style.visibility = "visible";
            loginPanel.style.opacity = "1";
            
        }
        else {
            loginPanel.style.opacity = "0";
            loginPanel.style.visibility = 'hidden';
        }
    }



    function showRegisterPanel() {
        var loginPanel = document.getElementById('register-panel');

        if(loginPanel.style.visibility !== 'visible') {
            loginPanel.style.visibility = "visible";
            loginPanel.style.opacity = "1";
            
        }
        else {
            loginPanel.style.opacity = "0";
            loginPanel.style.visibility = 'hidden';
        }
    }
</script>