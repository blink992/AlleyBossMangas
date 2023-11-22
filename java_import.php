<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="js/jQuery/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script src="js/materialize.min.js"></script>
<script src="js/javascript.js"></script>

<script>
function ajaxMethodInput(idInput, destino){
  $(idInput).keyup(function(){
    var pesquisa = $(this).val();

    if(pesquisa != ''){
      var dados = {
        palavra : pesquisa
      }
      $.post(destino, dados, function(retorna){
        $(".resultado").html(retorna);
      });

    }
  });


var ultima_pos = 0;
var scrolling = '';

document.addEventListener('scroll', function() {

    setTimeout(() => {
        var navbar = document.getElementById('navbar');

        pos_atual = window.scrollY;
    
        if(pos_atual > ultima_pos){
            navbar.style.top = '-100px';

        }
        else if(pos_atual < ultima_pos){
            
            navbar.style.top = '0px';
            

        }
        else {
            scrolling = 'parado';
        }

        ultima_pos = pos_atual;
    }, 100);

})



$("#form-pesquisa-manga").submit(function(e){
    e.preventDefault();
  });
}

function logout() {

    var dados = {
        logout : true
    }

    $.post('login.php', dados, function(retorna){
        $(".login").html(retorna);
    });
}

function register() {
    var inputUsername = document.getElementById('input_username_cadastro');
    var inputEmail = document.getElementById('input_email_cadastro');
    var inputSenha = document.getElementById('input_senha_cadastro');
    var inputConfirmarSenha = document.getElementById('input_confirmar_senha_cadastro');

    var usernameValue = inputUsername.value;
    var emailValue = inputEmail.value;
    var senhaValue = inputSenha.value;
    var confirmarSenhaValue = inputConfirmarSenha.value;

    var dados = {
        username : usernameValue,
        email : emailValue,
        senha : senhaValue,
        confirmarSenha : confirmarSenhaValue
    }
    $.post('cadastro.php', dados, function(retorna){
        $(".cadastro").html(retorna);
    });
}


function login() {
    var inputEmail = document.getElementById('input_email');
    var inputSenha = document.getElementById('input_senha');
    
    var emailValue = inputEmail.value;
    var senhaValue = inputSenha.value;

    var dados = {
        email : emailValue,
        senha : senhaValue
    }
    $.post('login.php', dados, function(retorna){
        $(".login").html(retorna);
    });
    }



var count = 15;
function expand_list(pagina, filtro="", filtroGenero="") {
    count = count + 15;

    var classe = "." + pagina;
    classe = classe.substring(classe.length - 4, 0);
    
    
    var dados = {
        index_reset : count,
        letraFiltro : filtro,
        generoFiltro : filtroGenero
    }
    $.post(pagina, dados, function(retorna){
        $(classe).html(retorna);
      });
    

};



function limparFiltros() {
    dados = {
        limpar : true
    }
    
    $.post("recebe_dados.php", dados, function(retorna){
        $(".generos").html(retorna);
    });
}


button = "%";
function concGeneros(buttonName) {
    button += buttonName+"%";
    return button;
}
function aplicarFiltros() {
    alert();
    dados = {
        aplicarFiltros : 'true'
    }
    $.post("expanded_ordenados_por_nome.php", dados, function(retorna){
        $("ex").html(retorna);
    });
}
function generoFunc(buttonName) {
    
    var buttonGenero = document.getElementById('button_genero'+buttonName);

    
    if(buttonGenero.style.transform == "scale(0.9)"){
        buttonGenero.style.backgroundColor = "rgba(57, 74, 109, 0.8)";
        buttonGenero.style.border = "none";
        buttonGenero.style.transform = "scale(1)";
        button = button.replace('%'+buttonName, '');
        var generosConcatenados = button;
    }
    else {
        buttonGenero.style.backgroundColor = "rgba(36, 43, 70, 1)";
        buttonGenero.style.border = "solid 3px rgba(64, 73, 99, 1)";
        buttonGenero.style.transform = "scale(0.9)";
        var generosConcatenados = concGeneros(buttonName);
    }
    
    dados = {
        generos : generosConcatenados,
    }
    
    $.post("recebe_dados.php", dados, function(retorna){
        $(".generos").html(retorna);
    });
    
}

function incrementaNota(operacao) {
    var notaUser = document.getElementById('nota_do_usuario');

    switch(operacao){
        case "+":
            if((notaUser.innerHTML > 0) && (notaUser.innerHTML < 10)){
                notaUser.innerHTML++;
            }
            break;
        case "-":
            if((notaUser.innerHTML > 1) && (notaUser.innerHTML <= 10)){
                notaUser.innerHTML--;
            }
            break;
    }
}

function salvarNotaUsuario(idManga) {
    var nota = document.getElementById('nota_do_usuario');

    var dados = {
        id_manga : idManga,
        nota_manga : nota.innerHTML
    }
    $.post("recebe_dados.php", dados, function(retorna){
        $(".echo").html(retorna);
    });
}




function addFavoritos(idManga, pagina) {
    
    var elem = document.getElementById("icon_favorite"+idManga+pagina);
    if(elem.innerHTML == "favorite"){
        elem.innerHTML = "favorite_border";
        var dados = {
            id_manga_favoritado : idManga,
            is_favorited : "false"
        }
    }
    else {
        elem.innerHTML = "favorite";
        var dados = {
            id_manga_favoritado : idManga,
            is_favorited : "true"
        }
    }
    
    $.post("add_fav.php", dados, function(retorna){
        $("").html(retorna);
      });
}

function refresh() {
    document.location.reload();
}

function adicioneDescricao() {
    var desc = document.getElementById('descrição');
    desc.parentNode.removeChild(desc);
    var desc_input = document.getElementById('descrição_input');

    desc_input.innerHTML = ''+
        '<form action=\"perfil.php\" method=\"POST\" style=\"justify-content:center;display:flex;flex-direction:column\">'+
            '<textarea style="outline:0;box-shadow: 0 0 0 0;width:250px;align-self:center;position:relative;border:3px inset #4f4c5b;background-color:#2b2e37;border-radius:10px;padding:8px;font-size:130%" class="basic-text" type=\"text\" name=\"descrição_perfil\" placeholder=\"Digite sua descrição\"></textarea>'+
            '<div style="display:flex;flex-direction:row;align-self:center">'+
            '   <button class=\"scale-effect\" style=\"margin-right:10px;display:flex;justify-content:center;width:max-content;padding-right:20px;padding-left:20px;margin-top:20px;height:max-content;background-color:rgba(46, 48, 58, 1);border:none;align-self:center;border-radius:25px\" type=\"submit\"><h5 style=\"align-self:center;font-size:120%\" class=\"basic-text\">Salvar</h5></button>'+
            '   <button class=\"scale-effect\" style=\"display:flex;justify-content:center;width:max-content;padding-right:20px;padding-left:20px;margin-top:20px;height:max-content;background-color:rgba(46, 48, 58, 1);border:none;align-self:center;border-radius:25px\" onclick=\"refresh()\" type=\"button\"><h5 style=\"align-self:center;font-size:120%\" class=\"basic-text\">Voltar</h5></button>'+
            '</div>'+
        '</form>';
    
}



function ajaxMethodVal(dados, destino){
      $.post(destino, dados, function(retorna){
        $(".index").html();
      });
}

$('#pesquisa-manga').on('keyup', ajaxMethodInput("#pesquisa-manga", "busca.php"));


// carousel
$(document).ready(function(){
    $('.carousel').carousel({
        padding: numPadding,
        dist: numDist,
        fullWidth: false,
        numVisible: qtdNumVisible,
        indicators: true,
        noWrap: false
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
      direction: 'left'
    });
  });

        
document.addEventListener('DOMContentLoaded', function() {
M.AutoInit();

var elemsCollapsible = document.querySelectorAll('.collapsible');
var optionsCollapsible = {
    inDuration: inDuration,
    outDuration: outDuration
}
var instancesCollapsible = M.Collapsible.init(elemsCollapsible, optionsCollapsible);

});




















function showMinhasLeiturasOpcoes() {
    showHiddenMangaOpcoes()
    var navbar = document.getElementById('navbar_minhas_leituras');
    navbar.style.display = 'flex';
    setTimeout(() => {        
        navbar.style.top = 60 + 'px';
        navbar.style.zIndex = '5';
    }, 10);

    }

function showHiddenMinhasLeiturasOpcoes() {
        hiddenMangaOpcoes();
        var navbar = document.getElementById('navbar_minhas_leituras');
        if(navbar.style.top <= -50 + 'px'){
            navbar.style.display = 'flex';
            setTimeout(() => {        
        navbar.style.top = 60 + 'px';
        navbar.style.zIndex = '5';
    }, 10);
        }
        else{
            navbar.style.top = -50 + 'px';
            navbar.style.zIndex = '4';
            setTimeout(() => {
            navbar.style.display = 'none';
        }, 200);

        }
}
function hiddenMinhasLeiturasOpcoes(){

    var navbar = document.getElementById('navbar_minhas_leituras');
    navbar.style.top = -50 + 'px';
    navbar.style.zIndex = '4';
    setTimeout(() => {
            navbar.style.display = 'none';
        }, 200);
}


function showMangaOpcoes() {
    showHiddenMinhasLeiturasOpcoes(false)
    var navbar = document.getElementById('navbar_mangas');
    navbar.style.display = 'flex';
    setTimeout(() => {        
        navbar.style.top = 60 + 'px';
        navbar.style.zIndex = '5';
    }, 10);

}


function showHiddenMangaOpcoes() {
    hiddenMinhasLeiturasOpcoes();
    var navbar = document.getElementById('navbar_mangas');
    if(navbar.style.top <= -50 + 'px'){
        navbar.style.display = 'flex';
        setTimeout(() => {        
        navbar.style.top = 60 + 'px';
        navbar.style.zIndex = '5';
    }, 10);
    }
    else{
        navbar.style.top = -50 + 'px';
        navbar.style.zIndex = '4';
        setTimeout(() => {
            navbar.style.display = 'none';
        }, 200);    }
    
}
function hiddenMangaOpcoes() {
    var navbar = document.getElementById('navbar_mangas');
    navbar.style.top = -50 + 'px';
    navbar.style.zIndex = '4';
    setTimeout(() => {
            navbar.style.display = 'none';
        }, 200);
}

function expandSearchBar() {
var searchBar = document.getElementById('pesquisa-manga');

if(searchBar.style.width <= 0 + 'px') {
    if(screen.width < 400){
        setTimeout(function(){
            searchBar.style.width = (screen.width - 80) + 'px';
            searchBar.style.paddingLeft = 12 + 'px';
            searchBar.style.paddingRight = 12 + 'px';
            searchBar.style.borderWidth = 3 + 'px';
        },400);
    }
    if((screen.width >= 400) && (screen.width < 650)){
        setTimeout(function(){
            searchBar.style.width = (screen.width - 80) + 'px';
            searchBar.style.paddingLeft = 12 + 'px';
            searchBar.style.paddingRight = 12 + 'px';
            searchBar.style.borderWidth = 3 + 'px';
        },400);
    }
    if((screen.width >= 650) && (screen.width < 750)){
        setTimeout(function(){
            searchBar.style.width = 500 + 'px';
            searchBar.style.paddingLeft = 12 + 'px';
            searchBar.style.paddingRight = 12 + 'px';
            searchBar.style.borderWidth = 3 + 'px';
        },300);
    }
    if((screen.width >= 750) && (screen.width < 1000)){
        setTimeout(function(){
            searchBar.style.width = 350 + 'px';
            searchBar.style.paddingLeft = 18 + 'px';
            searchBar.style.paddingRight = 18 + 'px';
            searchBar.style.borderWidth = 3 + 'px';
        },300);
    }
    if((screen.width >= 1000) && (screen.width < 1300)){
        setTimeout(function(){
            searchBar.style.width = 500 + 'px';
            searchBar.style.paddingLeft = 20 + 'px';
            searchBar.style.paddingRight = 20 + 'px';
            searchBar.style.borderWidth = 3 + 'px';
        },300);
    }
    if(screen.width >= 1300){
        setTimeout(function(){
            searchBar.style.width = 630 + 'px';
            searchBar.style.paddingLeft = 20 + 'px';
            searchBar.style.paddingRight = 20 + 'px';
            searchBar.style.borderWidth = 3 + 'px';
        },300);
    }
    if(screen.width < 750) {
        var opcoesNavbar = document.getElementById('opcoes-navbar');
        opcoesNavbar.style.opacity = 0;
        opcoesNavbar.style.zIndex = -5;
        setTimeout(function(){
            opcoesNavbar.style.position = 'absolute';
        },500)
    }
    if(screen.width < 650) {
        var homeNavbar = document.getElementById('icon-home');
        homeNavbar.style.opacity = 0;
        homeNavbar.style.zIndex = -5;
        setTimeout(function(){
            homeNavbar.style.width = 0 + 'px';
        },500)
    }}
else {
    searchBar.style.width = 0 + 'px';
    searchBar.style.paddingLeft = 0 + 'px';
    searchBar.style.paddingRight = 0 + 'px';
    searchBar.style.borderWidth = 0 + 'px';

    var pesquisaMangas = document.getElementById('pesquisa');
    pesquisaMangas.style.top = -100 + 'px';
    pesquisaMangas.style.opacity = '0';
    pesquisaMangas.style.zIndex = '-5';

    setTimeout(function(){
        var opcoesNavbar = document.getElementById('opcoes-navbar');
        opcoesNavbar.style.opacity = 1;
        opcoesNavbar.style.zIndex = 5;
        opcoesNavbar.style.position = 'relative';

        var homeNavbar = document.getElementById('icon-home');
        homeNavbar.style.opacity = 1;
        homeNavbar.style.zIndex = 5;
        homeNavbar.style.position = 'relative';
    },400);
}
}

function showPesquisa() {
    var pesquisa = document.getElementById('pesquisa');

    pesquisa.style.top = 50 + 'px';
    pesquisa.style.opacity = '1';
    pesquisa.style.zIndex = '6';
}
function hiddenPesquisa() {
    var pesquisa = document.getElementById('pesquisa');
    pesquisa.style.top = -100 + 'px';
    pesquisa.style.opacity = '0';
    setTimeout(() => {
        pesquisa.style.zIndex = '-5';
    }, 200);
}

</script>
