<?php
  session_start();

  include_once "includes/conectar.php";
  require_once 'includes/configuracoes.php';                                    $conf= new Configuracao();
  $_SESSION['life_tipo_equipamento_acesso'] = 'pc';
  $_SESSION['life_link_completo'] = $conf->retornaLinkCompletoAplicacao();
  $nm_site = $conf->retornaNomeSite();

  $nom = 'calendario.php'; // nome deste arquivo
               
  $tmp = $_GET["tmpx"];
  if($tmp){
    $data = $tmp;
  } else {
    $data = date("Y-m-d");
  }
  
  $dxx = date('d', strtotime($data));
  $mxx = date('n', strtotime($data));
  $axx = date('Y', strtotime($data));

  $formulario = $_GET["formulario"];
  $campo      = $_GET["campo"];

  $descricao_secao = 'Calendário';
  $descricao_site = ".:. ".$nm_site." .:. Calendário"; 
  $palavras_chave = $conf->retornaPalavrasChave();

  $javascript = "js/funcoes_cadastro_data.js";

  $link_atual = "http://".$_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'];

  //inclusao de arquivo para montagem do cabecalho
  include "includes/cabecalho.php"; 
  //criacao do cabecalho
  $cabecalho= new Cabecalho($nm_site, '0', '0', '0', array());

  $cabecalho->setLink($link_atual);
  $cabecalho->setDescricao($descricao_site);
  $cabecalho->setPalavrasChave($palavras_chave);
  //inclusao do arquivo de css
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/divisoes.css");
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/fontes.css");
  $cabecalho->insereArquivoCss($_SESSION['life_link_completo']."css/calendario.css");

  if ($javascript != '') {
    //inclusao do arquivo javascript
    $cabecalho->insereArquivoJavaScript($javascript);
  }
  //ordem de impressao do cabecalho 
  $cabecalho->imprimeCabecalhoHTML(false, $nm_site);
?>  
  <body class="geral">
<link rel="stylesheet" type="text/css" href="arquivos/estilo.css">
  <table align="center" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#999999">
    <tr>
      <td>
<?php
class calendario{
  var $mes = array(
                   '1' => 'JANEIRO',
                   '2' => 'FEVEREIRO',
                   '3' => 'MARÇO',
                   '4' => 'ABRIL',
                   '5' => 'MAIO',
                   '6' => 'JUNHO',
                   '7' => 'JULHO',
                   '8' => 'AGOSTO',
                   '9' => 'SETEMBRO',
                   '10' => 'OUTUBRO',
                   '11' => 'NOVEMBRO',
                   '12' => 'DEZEMBRO'
                  );

  function mes($dia,$ano){
    global $nom, $formulario, $campo;
    $mes_abreviado = array(
                   '1' => 'J',
                   '2' => 'F',
                   '3' => 'M',
                   '4' => 'A',
                   '5' => 'M',
                   '6' => 'J',
                   '7' => 'J',
                   '8' => 'A',
                   '9' => 'S',
                   '10' => 'O',
                   '11' => 'N',
                   '12' => 'D'
                  );
    for ($i=1;$i<=12;$i++) {
      $tmp = $ano."-".$i."-".$dia;
      echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">'.$mes_abreviado[$i].'</a>&nbsp;';    
    }
  }
  
  function mes_anterior($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    if($mes == 1){
       $man = 12;
       $aan = $ano - 1;
    } else {
       $man = $mes - 1;
       $aan = $ano;
    }
    $tmp = $aan."-".$man."-".$dia;
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">«</a>';
  }

  function mes_proximo($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    if($mes == 12){
       $mpr = 1;
       $apr = $ano + 1;
    } else {
       $mpr = $mes + 1;
       $apr = $ano;
    }

    $tmp = $apr."-".$mpr."-".$dia;
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">»</a>';
  }

  function ano_anterior($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    $aan = $ano - 1;

    $tmp = $aan."-".$mes."-".$dia;
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">«</a>';
  }

  function ano_proximo($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    $apr = $ano + 1;

    $tmp = $apr."-".$mes."-".$dia;
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">»</a>';
  }

  function cria($data){
    global $nom, $dxx, $mxx, $axx, $formulario, $campo;
    $dia = date('j', strtotime($data));
    $mes = date('n', strtotime($data));
    $ano = date('Y', strtotime($data));
    if(($dia == '') OR ($mes = '') OR ($ano = '')){
      $data = date("d/m/Y");
      $dia = date('j', strtotime($data));
      $mes = date('n', strtotime($data));
      $ano = date('Y', strtotime($data));
    }

    $dia = date('j', strtotime($data));
    $mes = date('n', strtotime($data));
    $ano = date('Y', strtotime($data));


    while (!checkdate($mes,$dia,$ano)) {
      $nova_data = mktime(0,0,0, $mes, $dia+1, $ano);
      $dia = date('j', strtotime($nova_data));
      $mes = date('n', strtotime($nova_data));
      $ano = date('Y', strtotime($nova_data));
    } 

    $ult = date("d", mktime(0,0,0,$mes+1,0,$ano));
    $dse = date("w", mktime(0,0,0,$mes,1,$ano));

    $tot = $ult+$dse;
    if($tot != 0){
      $tot = $tot+7-($tot%7);
    }

    for($i=0;$i<$tot;$i++){
      $dat = $i-$dse+1;
      if(($i >= $dse) AND ($i < ($dse+$ult))){
        $aux[$i]  = '
          <td ';

        if(($dat == $dxx) AND ($mes == $mxx) AND ($ano == $axx)){
          $aux[$i] .= 'class="calendario_dias_hoje"';
        } else {
          $aux[$i] .= 'class="calendario_dias"';
        }

        $aux[$i] .= '><a href="'.$nom.'?tmpx=&data='.sprintf("%02.0f",$dat).'/'.$mes.'/'.$ano.'&formulario='.$formulario.'&campo='.$campo.'" onclick="valor(\''.sprintf("%02.0f",$dat).'/'.$mes.'/'.$ano.'\', \''.$campo.'\')">'.$dat.'</a>
          </td>
        ';
      } else {
        $aux[$i] = '
          <td>
          </td>
        ';
    }

    if(($i%7) == 0){
      $aux[$i] = '<tr align="center">'.$aux[$i];
    }

    if(($i%7) == 6){
      $aux[$i] .= '</tr>';
    }
  }

  echo '
  <table cellspacing="0" cellpadding="0" class="calendario_tabela">
    <tr>
      <td>
        <table cellspacing="1" cellpadding="1">
          <tr class="calendario_mes_ano">
            <td colspan="7">
  ';
  $this->mes($dia,$ano);
  echo '
            </td>
          </tr>
          <tr class="calendario_mes_ano">
            <td>
  ';
  $this->mes_anterior($dia,$mes,$ano);
  echo '
            </td>
            <td colspan="5">'.$this->mes[$mes].'</td>
            <td>
  ';
  $this->mes_proximo($dia,$mes,$ano);
  echo '
</td>
          </tr>

          <tr class="calendario_mes_ano">
            <td>
  ';
  $this->ano_anterior($dia,$mes,$ano);
  echo '
            </td>
            <td colspan="5">'.$ano.'</td>
            <td>
  ';
  $this->ano_proximo($dia,$mes,$ano);
  echo '
            </td>
          </tr>

          <tr class="calendario_semana">
            <td WIDTH="30">D</td>
            <td WIDTH="30">S</td>
            <td WIDTH="30">T</td>
            <td WIDTH="30">Q</td>
            <td WIDTH="30">Q</td>
            <td WIDTH="30">S</td>
            <td WIDTH="30">S</td>
          </tr>
  ';
  echo implode(' ',$aux);
  if(count($aux) == 35){
    echo '
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
    ';
  };
  echo '
          <tr>
            <td class="calendario_mes_ano" colspan="7" align="center">[ <a href="'.$nom.'?&formulario='.$formulario.'&campo='.$campo.'" onclick="valor(\''.date('d').'/'.date('m').'/'.date('Y').'\', \'".$campo."\');">Hoje</a> ]</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  ';
   }
}

$teste = new calendario;
$teste->cria($data);
?>
      </td>
    </tr>
  </table>
</body>
</html>
