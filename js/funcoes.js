<script>
<!--
<?php
  sajax_show_javascript();
?>

function alterarBorda(e,f) {
	if (f==1) {
		e.style.borderTop = '#041ba6 1px solid';
		e.style.borderLeft = '#041ba6 1px solid';
		e.style.borderRight = '#041ba6 1px solid';
		e.style.borderBottom = '#041ba6 1px solid';
	} else {
		e.style.borderTop = '#e89d25 1px solid';
		e.style.borderLeft = '#e89d25 1px solid';
		e.style.borderRight = '#e89d25 1px solid';
		e.style.borderBottom = '#e89d25 1px solid';
	}
}

  function atualizandoCampoTipoArquivo(retorno) {
    document.getElementById('celula_arquivo_o_a').innerHTML = retorno;
  }
  function atualizarCampoTipoArquivo() {
    var cd_formato = document.getElementById('cd_technical_format').value;
    var cd_formato_antigo = document.getElementById('cd_technical_format_original').value;
    var eh_setado = document.getElementById('eh_setado').value;
    var ds_location = document.getElementById('ds_technical_location_original').value;
    x_atualizarCampoTipoArquivo(cd_formato, cd_formato_antigo, eh_setado, ds_location, atualizandoCampoTipoArquivo);
  }


  function atualizandoCampoSubAreasConhecimento(retorno) {
    document.getElementById('celula_sub_areas_conhecimento').innerHTML = retorno;
  }
  function atualizarCampoSubAreasConhecimento() {
    var cd_general = document.getElementById('cd_general').value;
    var cd_area_conhecimento = document.getElementById('cd_general_coverage').value;
    x_atualizarCampoSubAreasConhecimento(cd_general, cd_area_conhecimento, atualizandoCampoSubAreasConhecimento);
  }

  function detalhandoDadosObjetoAprendizagem(retorno) {
    document.getElementById('detalhamentoOAConteudo').innerHTML = retorno;
    document.getElementById('detalhamentoOA').style.display = 'block';
  }
  function detalharDadosObjetoAprendizagem(cd_objeto_aprendizagem) {
    if (cd_objeto_aprendizagem > 0) {
      x_detalharDadosObjetoAprendizagem(cd_objeto_aprendizagem, detalhandoDadosObjetoAprendizagem);
    }
  }

  function fecharDadosObjetoAprendizagem() {
    document.getElementById('detalhamentoOA').style.display = 'none';
  }

  function chamarPesquisa(event) {
    if (event.keyCode == 13 || event.which == 13 || event.type == "click"){
      document.getElementById('fundo_tela').style.display = 'block';
      document.getElementById('tela_pesquisa').style.display = 'block';
      document.getElementById("termo").focus();
    }
  }
  function ocultarPesquisa(event) {
    // 13 = enter, 27 = esc
    if (event.keyCode == 13 || event.which == 13 || event.keyCode == 27 || event.which == 27 || event.type == "click"){
      document.getElementById('fundo_tela').style.display = 'none';
      document.getElementById('tela_pesquisa').style.display = 'none';
    }
  }

  function chamarLogin(event) {
    if (event.keyCode == 13 || event.which == 13 || event.type == "click"){
      document.getElementById('fundo_tela').style.display = 'block';
      document.getElementById('tela_login').style.display = 'block';
      document.getElementById("usuario").focus();
    }
  }
  function ocultarLogin(event) {
    // 13 = enter, 27 = esc
    if (event.keyCode == 13 || event.which == 13 || event.keyCode == 27 || event.which == 27 || event.type == "click"){
      document.getElementById('fundo_tela').style.display = 'none';
      document.getElementById('tela_login').style.display = 'none';
    }
  }

  function chamarCadastro(event) {
    if (event.keyCode == 13 || event.which == 13 || event.type == "click"){
      document.getElementById('fundo_tela').style.display = 'block';
      document.getElementById('tela_cadastro').style.display = 'block';
      document.getElementById("nm_pessoa").focus();
    }
  }
  function ocultarCadastro(event) {
    // 13 = enter, 27 = esc
    if (event.keyCode == 13 || event.which == 13 || event.keyCode == 27 || event.which == 27 || event.type == "click"){
      document.getElementById('fundo_tela').style.display = 'none';
      document.getElementById('tela_cadastro').style.display = 'none';
    }
  }

  function abrirGerenciamentoUsuario() {
    document.getElementById('fundo_tela').style.display = 'block';
    document.getElementById('fundo_tela_dados_login').style.display = 'block';
    document.getElementById('tela_dados_login').style.display = 'block';
  }
  function fecharGerenciamentoUsuario() {
    document.getElementById('fundo_tela').style.display = 'none';
    document.getElementById('fundo_tela_dados_login').style.display = 'none';
    document.getElementById('tela_dados_login').style.display = 'none';
  }

  function focoTelaPrincipal() {
    if (document.getElementById('fundo_tela').style.display == 'block') {
      document.getElementById('fundo_tela').style.display = 'none';
      document.getElementById('tela_pesquisa').style.display = 'none';
      if (document.getElementById('tela_login')) {
        document.getElementById('tela_login').style.display = 'none';
      }
      if (document.getElementById('tela_cadastro')) {
        document.getElementById('tela_cadastro').style.display = 'none';
      }
      if (document.getElementById('fundo_tela_dados_login')) {
        document.getElementById('fundo_tela_dados_login').style.display = 'none';
        document.getElementById('tela_dados_login').style.display = 'none';
      }
    }
  }


function atualizaCidade(cidade) {
  document.getElementById("cidade").innerHTML = cidade;
}

function buscaCidades(sufixo) {
  var estado = document.getElementById("estado").value;
  if (estado != "") {
    x_buscaCidades(estado, atualizaCidade);
  }
}

function marcarEstrelas(id) {
  var caminho = document.getElementById('caminho').value;
  if (id == '1') {
    document.getElementById('estrela_01').src = caminho+'estrela_100.png';
  } else {
    if (id == '2') {
      document.getElementById('estrela_01').src = caminho+'estrela_100.png';
      document.getElementById('estrela_02').src = caminho+'estrela_100.png';
    } else {
      if (id == '3') {
        document.getElementById('estrela_01').src = caminho+'estrela_100.png';
        document.getElementById('estrela_02').src = caminho+'estrela_100.png';
        document.getElementById('estrela_03').src = caminho+'estrela_100.png';
      } else {
        if (id == '4') {
          document.getElementById('estrela_01').src = caminho+'estrela_100.png';
          document.getElementById('estrela_02').src = caminho+'estrela_100.png';
          document.getElementById('estrela_03').src = caminho+'estrela_100.png';
          document.getElementById('estrela_04').src = caminho+'estrela_100.png';
        } else {
          if (id == '5') {
            document.getElementById('estrela_01').src = caminho+'estrela_100.png';
            document.getElementById('estrela_02').src = caminho+'estrela_100.png';
            document.getElementById('estrela_03').src = caminho+'estrela_100.png';
            document.getElementById('estrela_04').src = caminho+'estrela_100.png';
            document.getElementById('estrela_05').src = caminho+'estrela_100.png';
          }
        }
      }
    }
  }
}

function desmarcarEstrelas() {
  var caminho = document.getElementById('caminho').value;
  document.getElementById('estrela_01').src = caminho+'estrela_025.png';
  document.getElementById('estrela_02').src = caminho+'estrela_025.png';
  document.getElementById('estrela_03').src = caminho+'estrela_025.png';
  document.getElementById('estrela_04').src = caminho+'estrela_025.png';
  document.getElementById('estrela_05').src = caminho+'estrela_025.png';
}

function marcarDenunciar() {
  var caminho = document.getElementById('caminho').value;
  document.getElementById('denunciar').src = caminho+'denunciar.png';
}

function desmarcarDenunciar() {
  var caminho = document.getElementById('caminho').value;
  document.getElementById('denunciar').src = caminho+'denunciar_of.png';
}

function marcarComentar() {
  var caminho = document.getElementById('caminho').value;
  document.getElementById('comentar').src = caminho+'comentar.png';
}

function desmarcarComentar() {
  var caminho = document.getElementById('caminho').value;
  document.getElementById('comentar').src = caminho+'comentar_of.png';
}

function avaliando(saida) {
  alert(saida);
}
function avaliar(estrelas) {
  x_avaliar(avaliando);
}

function atualizaCampoSubArea(codigo) {
  var campo = "cd_area_conhecimento_"+codigo;
  var local = "sub_area_"+codigo;
  if (document.getElementById(campo).checked) {
    document.getElementById(local).style.display = 'block';
  } else {
    document.getElementById(local).style.display = 'none';
  }
}

function definirStatus(variavel, variavel_acao) {
  var configuracao = document.getElementById(variavel).value;
  if (configuracao == '1') {
    document.getElementById(variavel_acao).disabled = true;
    document.getElementById(variavel_acao).value = '1';
  } else {
    document.getElementById(variavel_acao).disabled = false;
  }
}

function marcarDigitou() {
  if (document.getElementById('eh_form')) {
    document.getElementById('digitou').value = '1';
  }
}
function desmarcarDigitou() {
  document.getElementById('digitou').value = '0';
}

function ajustarStatus(posicao) {
  var flag = 'pos_'+posicao;
  if (document.getElementById(flag).checked) {
    var display = 'block';
  } else {
    var display = 'none';
  }
  var qtd = 'qt_pos_'+posicao;
  var qt = document.getElementById(qtd).value;
  for (i=1; i<=qt; i++) {
    var linha = 'linha_pos_'+posicao+'_'+i;
    document.getElementById(linha).style.display = display;
  }
}

function mudandoTamanhoFonte() {
}

function mudaTamanho(acao){
  var tamanho = parseInt(document.getElementById('tamanho_fonte').value);
  var elementoTopoSiteControleFonte = document.getElementById("topoSiteControleFonte");            
  var atualTopoSiteControleFonte = elementoTopoSiteControleFonte.style.fontSize;

  if (acao == 'a') {
    atualTopoSiteControleFonte = parseInt(atualTopoSiteControleFonte)+2+'px';
    tamanho = tamanho + 1;
  } else {
    if (acao == 'd') {
      atualTopoSiteControleFonte = parseInt(atualTopoSiteControleFonte)-2+'px';
      tamanho = tamanho - 1;
    } else {
      if (acao == 'p') {
        atualTopoSiteControleFonte = '16px';
        tamanho = 4;
      } else {
        tamanho = parseInt(acao);
        atualTopoSiteControleFonte =  (tamanho * 2) + 8;
      }
    }
  }

  ajustar = true;
  if (tamanho < 0) {    tamanho = 0;    ajustar = false;      }
  if (tamanho > 8) {    tamanho = 8;    ajustar = false;      }

  var elemento = document.getElementsByTagName('p');
  var tamanhos = new Array('6px','8px','10px','11px','12px','13px','14px','16px','18px');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('td');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('input');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('select');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('textarea');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('h1');
  var tamanhos = new Array('10px','12px','14px','15px','16px','17px','18px','20px','22px');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('h2');
  var tamanhos = new Array('8px','10px','12px','13px','14px','15px','16px','18px','20px');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  var elemento = document.getElementsByTagName('h3');
  var tamanhos = new Array('6px','8px','10px','11px','12px','13px','14px','16px','18px');
  var este_tamanho = tamanhos[tamanho];
  for (i=0; i<elemento.length; i++) {
    elemento[i].style.fontSize = este_tamanho;
  }

  if (ajustar) {
    elementoTopoSiteControleFonte.style.fontSize = atualTopoSiteControleFonte;
  }

  document.getElementById('tamanho_fonte').value = tamanho;
  x_mudarTamanhoFonte(tamanho, mudandoTamanhoFonte);
}

function mudarEstilo(event){
    // verifica se atualmente usa estilo com contraste
    var atual = document.getElementById("link-pretoebranco").getAttribute("href");
    var isPb = readCookie('contrastChanged');
    // inicializa cookie para manter o contraste na navegacao
    if(isPb == null){
      document.cookie = "contrastChanged=false";
    }
    // nao usa e o cookie eh true, entao deve alterar o contraste
    if(atual === '' && isPb == 'true'){
      document.getElementById("link-pretoebranco").setAttribute("href", document.getElementById("link-pretoebranco").getAttribute("data-href"));
      document.cookie = "contrastChanged=true";
      return;
    }

    if (event.keyCode == 13 || event.which == 13 || event.type == "click"){
      // alterar o contraste
      if(atual === '' && isPb == 'false' && event != undefined)
        {
          document.getElementById("link-pretoebranco").setAttribute("href", document.getElementById("link-pretoebranco").getAttribute("data-href"));
          document.cookie = "contrastChanged=true";
        }
        // retomar contraste original
        if(atual !== '' && isPb == 'true' && event != undefined){
        document.getElementById("link-pretoebranco").setAttribute("href", "");
        document.cookie = "contrastChanged=false";
      }
    }
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
-->
</script>