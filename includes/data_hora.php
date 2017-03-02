<?php
  class DataHora {
  
    public function __construct() {
    }
    
    public function inverterData($data) {
      if(count(explode("/",$data)) > 1) {
        return implode("-",array_reverse(explode("/",$data)));
      } elseif (count(explode("-",$data)) > 1) {
        return implode("/",array_reverse(explode("-",$data)));
      }
    }    
    
    public function imprimirData($data) {
      if ($data != '0000-00-00') {
        $dia = date('j', strtotime($data));
        $mes = date('n', strtotime($data));
        $ano = date('Y', strtotime($data));
        $retorno = $dia."/".$mes."/".$ano;
        return $retorno;
      } else {
        return '';
      }
    }
    
    public function retonaDataExtenso($data) {
      if ($data != '0000-00-00') {
        $dia = date('j', strtotime($data));
        $mes = date('n', strtotime($data));
        $ano = date('Y', strtotime($data));
        return $dia." de ".$this->retornaMes($mes)." de ".$ano;
      } else {
        return '';
      }
    }

    public function imprimirMesAno($data) {
      if ($data != '0000-00-00') {
        $mes = date('n', strtotime($data));
        $ano = date('Y', strtotime($data));
        return $this->retornaMes($mes)."/".$ano;
      } else {
        return '';
      }
    }
    
    public function retornaMes($mes) {
      switch ($mes) {
        case "1":       return "Janeiro";         break;
        case "2":       return "Fevereiro";       break;
        case "3":       return "Março";           break;
        case "4":       return "Abril";           break;
        case "5":       return "Maio";            break;
        case "6":       return "Junho";           break;
        case "7":       return "Julho";           break;
        case "8":       return "Agosto";          break;
        case "9":       return "Setembro";        break;
        case "10":      return "Outubro";         break;
        case "11":      return "Novembro";        break;
        case "12":      return "Dezembro";        break;
      }
    }    
    
    public function imprimirHora($hora) {
      $hr = date('H', strtotime($hora));
      $mn = date('i', strtotime($hora));
      $retorno = $hr.":".$mn;
      return $retorno;
    }
        
    //funcao para exibir data na tela
    public function exibirDataAtual() {
      $data= date('d') . '/' . date('m') . '/' . date('Y');
      echo "Hoje é " . $data;
    }
    
    public function imprimeCamposHiddenData($data, $sufixo) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
    
      $dia= date('j', strtotime($data));
      $mes= date('n', strtotime($data));
      $ano= date('Y', strtotime($data));
      
      $util->campoHidden("dia_".$sufixo, $dia);
      $util->campoHidden("mes_".$sufixo, $mes);
      $util->campoHidden("ano_".$sufixo, $ano);      
    }

    public function imprimeFormularioDataComandos($data, $sufixo) {
      $dia= date('j', strtotime($data));
      $mes= date('n', strtotime($data));
      $ano= date('Y', strtotime($data));
          
      $saida  = "<select id=\"dia_".$sufixo."\" name=\"dia_".$sufixo."\" class=\"fontComandosFiltros\">\n";
      for ($i=1; $i<=31; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        $saida .= "  <option value=\"".$ic."\"";
        if ($i == $dia) {
          $saida .= "selected=\"selected\"";
        } 
        $saida .= ">".$ic."</option>\n";
      }
      $saida .= "</select>\n";

      $saida .= "<select id=\"mes_".$sufixo."\" name=\"mes_".$sufixo."\" class=\"fontComandosFiltros\">\n";
      for ($i=1; $i<=12; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        $saida .= "  <option value=\"".$ic."\"";
        if ($i == $mes) {
          $saida .= "selected=\"selected\"";
        } 
        $saida .= ">".$ic."</option>\n";
      }
      $saida .= "</select>\n";
      
      $saida .= "<select id=\"ano_".$sufixo."\" name=\"ano_".$sufixo."\" class=\"fontComandosFiltros\">\n";
      for ($i=($ano - 100); $i<=($ano + 50); $i++) {
        $saida .= "  <option value=\"".$i."\"";
        if ($i == $ano) {
          $saida .= "selected=\"selected\"";
        } 
        $saida .= ">".$i."</option>\n";
      }
      $saida .= "</select>\n";
            
      return $saida;  
    }
    

    public function imprimeFormularioData($descricao, $data, $sufixo) {
      $dia= date('j', strtotime($data));
      $mes= date('n', strtotime($data));
      $ano= date('Y', strtotime($data));

      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$descricao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <select id=\"dia_".$sufixo."\" name=\"dia_".$sufixo."\" class=\"fontConteudoCampoTextHint\" style=\"width:25%;\">\n";
      for ($i=1; $i<=31; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        echo "            <option value=\"".$ic."\"";
        if ($i == $dia) {
          echo "selected=\"selected\"";
        }
        echo ">".$ic."</option>\n";
      }
      echo "          </select>\n";
      echo "          <select id=\"mes_".$sufixo."\" name=\"mes_".$sufixo."\" class=\"fontConteudoCampoTextHint\" style=\"margin-left:4px;width:25%;\">\n";
      for ($i=1; $i<=12; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        echo "            <option value=\"".$ic."\"";
        if ($i == $mes) {
          echo "selected=\"selected\"";
        }
        echo ">".$ic."</option>\n";
      }
      echo "          </select>\n";
      echo "          <select id=\"ano_".$sufixo."\" name=\"ano_".$sufixo."\" class=\"fontConteudoCampoTextHint\" style=\"margin-left:3px;width:48%;\">\n";
      for ($i=($ano - 100); $i<=($ano + 50); $i++) {
        echo "            <option value=\"".$i."\"";
        if ($i == $ano) {
          echo "selected=\"selected\"";
        }
        echo ">".$i."</option>\n";
      }
      echo "          </select>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
    }

    public function camposHiddenHora($valor, $sufixo) {
      $hora    = date('H', strtotime($valor));
      $minuto  = date('i', strtotime($valor));
      $segundo = date('s', strtotime($valor));    
      echo "      <input type=\"hidden\" name=\"hora_".$sufixo."\" id=\"hora_".$sufixo."\" value=\"".$hora."\" />\n";
      echo "      <input type=\"hidden\" name=\"minuto_".$sufixo."\" id=\"minuto_".$sufixo."\" value=\"".$minuto."\" />\n";
      echo "      <input type=\"hidden\" name=\"segundo_".$sufixo."\" id=\"segundo_".$sufixo."\" value=\"".$segundo."\" />\n";
    }

    public function imprimeFormularioHorarioHint($obrigatorio, $descricao, $sufixo, $tamanho, $valor, $exibir_ajuda, $exibir_hora, $exibir_minuto, $exibir_segundo) {
      $hora    = date('H', strtotime($valor));
      $minuto  = date('i', strtotime($valor));
      $segundo = date('s', strtotime($valor));

      $campos = 0;
      if ($exibir_hora == 1)    {        $campos += 1;      }
      if ($exibir_minuto == 1)  {        $campos += 1;      }
      if ($exibir_segundo == 1) {        $campos += 1;      }
      
      $tamanho_campo = $tamanho / $campos;

      $ajuda = "";
      if ($obrigatorio) {
        $ajuda = "Este campo é de preenchimento obrigatório<br />";
        $css = 'fontConteudoCampoSeletorHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoSeletorHint';
      }      
      echo "      <tr>\n";
      echo " 	      <td class=\"celConteudoCentralizado\" colspan=\"2\">\n";

      if ($exibir_hora == 1) {
        echo "          <select name=\"hora_".$sufixo."\" id=\"hora_".$sufixo."\" style=\"width:".$tamanho_campo."px;\" alt=\"".$descricao." - Horas\" title=\"".$descricao." - Horas\" class=\"".$css."\" placeholder=\"".$descricao." - Horas\" tabindex=\"1\">\n";
        for ($i=0; $i<=23; $i++) {
          if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
          echo "  <option value=\"".$ic."\"";
          if ($i == $hora) {          echo " selected=\"selected\"";        } 
          echo ">".$ic."</option>\n";
        }
        echo "          </select>\n";
      } else {
        echo "      <input type=\"hidden\" name=\"hora_".$sufixo."\" id=\"hora_".$sufixo."\" value=\"".$hora."\" />\n";
      }
      if ($exibir_minuto == 1) {
        echo "          <select name=\"minuto_".$sufixo."\" id=\"minuto_".$sufixo."\" style=\"width:".$tamanho_campo."px;\" alt=\"".$descricao." - Minutos\" title=\"".$descricao." - Minutos\" class=\"".$css."\" placeholder=\"".$descricao." - Minutos\" tabindex=\"1\">\n";
        for ($i=0; $i<=59; $i++) {
          if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
          echo "  <option value=\"".$ic."\"";
          if ($i == $minuto) {          echo " selected=\"selected\"";        } 
          echo ">".$ic."</option>\n";
        }
        echo "          </select>\n";
      } else {
        echo "      <input type=\"hidden\" name=\"minuto_".$sufixo."\" id=\"minuto_".$sufixo."\" value=\"".$minuto."\" />\n";
      }
      if ($exibir_segundo == 1) {
        echo "          <select name=\"segundo_".$sufixo."\" id=\"segundo_".$sufixo."\" style=\"width:".$tamanho_campo."px;\" alt=\"".$descricao." - Segundos\" title=\"".$descricao." - Segundos\" class=\"".$css."\" placeholder=\"".$descricao." - Segundos\" tabindex=\"1\">\n";
        for ($i=0; $i<=59; $i++) {
          if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
          echo "  <option value=\"".$ic."\"";
          if ($i == $segundo) {          echo " selected=\"selected\"";        } 
          echo ">".$ic."</option>\n";
        }
        echo "          </select>\n";
      } else {
        echo "      <input type=\"hidden\" name=\"segundo_".$sufixo."\" id=\"segundo_".$sufixo."\" value=\"".$segundo."\" />\n";
      }
      if (($exibir_ajuda == '1') || ($ajuda != '')) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda == '1') {
          echo "            Campo do Tipo Seletor para definição de horas\n";
        }
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\">\n";
      }
      echo "        </td>\n";
      echo "      </tr>\n";   
    }    
    

    public function imprimeFormularioMesAno($data, $sufixo) {
      $mes= date('n', strtotime($data));
      $ano= date('Y', strtotime($data));
          
      $saida = "<select id=\"mes_".$sufixo."\" name=\"mes_".$sufixo."\" class=\"fontConteudo\">\n";
      for ($i=1; $i<=12; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        $saida .= "  <option value=\"".$ic."\"";
        if ($i == $mes) {
          $saida .= "selected=\"selected\"";
        } 
        $saida .= ">".$ic."</option>\n";
      }
      $saida .= "</select>\n";
      
      $saida .= "<select id=\"ano_".$sufixo."\" name=\"ano_".$sufixo."\" class=\"fontConteudo\">\n";
      for ($i=($ano - 100); $i<=($ano + 50); $i++) {
        $saida .= "  <option value=\"".$i."\"";
        if ($i == $ano) {
          $saida .= "selected=\"selected\"";
        } 
        $saida .= ">".$i."</option>\n";
      }
      $saida .= "</select>\n";
            
      return $saida;  
    }    
    
    public function imprimeFormularioHora($horario, $sufixo) {
      $hora= date('H', strtotime($horario));
      $minuto= date('i', strtotime($horario));
          
      $saida  = "<select id=\"hora_".$sufixo."\" name=\"hora_".$sufixo."\" class=\"fontConteudo\">\n";
      for ($i=0; $i<=23; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        $saida .= "  <option value=\"".$ic."\"";
        if ($i == $hora) {          $saida .= " selected=\"selected\"";        } 
        $saida .= ">".$ic."</option>\n";
      }
      $saida .= "</select>\n";

      $saida .= "<select id=\"minuto_".$sufixo."\" name=\"minuto_".$sufixo."\" class=\"fontConteudo\">\n";
      for ($i=0; $i<=59; $i++) {
        if ($i < 10) {          $ic = '0'.$i;        } else {          $ic = $i;        }
        $saida .= "  <option value=\"".$ic."\"";
        if ($i == $minuto) {        $saida .= " selected=\"selected\"";        } 
        $saida .= ">".$ic."</option>\n";
      }
      $saida .= "</select>\n";
      
      return $saida;                                                                       
    } 
    
    public function camposHiddenData($sufixo, $data) {
      if (($data != '0000-00-00') && ($data != '    -  -  ')) {
        require_once 'includes/utilitarios.php';                                $util = new Utilitario();
        $dia = date('j', strtotime($data));                                     $util->campoHidden('dia_'.$sufixo, $dia);
        $mes = date('n', strtotime($data));                                     $util->campoHidden('mes_'.$sufixo, $mes);
        $ano = date('Y', strtotime($data));                                     $util->campoHidden('ano_'.$sufixo, $ano);        
      }
    }       
    
      
    public function somarData($date, $dias) {
      //passe a data no formato Ymd 
      $thisyear = date('Y', strtotime($date));
      $thismonth = date('n', strtotime($date));
      $thisday =  date('j', strtotime($date));
      $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $dias, $thisyear );
      return strftime("%Y%m%d", $nextdate);
    }

    public function somarMeses($date, $meses) {
      //passe a data no formato Ymd 
      $thisyear = date('Y', strtotime($date));
      $thismonth = date('n', strtotime($date));
      $thisday =  date('j', strtotime($date));
      $nextdate = mktime ( 0, 0, 0, $thismonth + $meses, $thisday, $thisyear );
      return strftime("%Y%m%d", $nextdate);
    }

    public function subtrairData($date, $dias) {
      //passe a data no formato Ymd 
      $thisyear = date('Y', strtotime($date));
      $thismonth = date('n', strtotime($date));
      $thisday =  date('j', strtotime($date));
      $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $dias, $thisyear );
      return strftime("%Y%m%d", $nextdate);
    }
    
    public function compararDatas($data1, $data2) {
      /*
       *  funcao verifica se data 1 um é menor, igual ou maior que data 2
       */
      $dia1 = date('j', strtotime($data1));
      $mes1 = date('n', strtotime($data1));
      $ano1 = date('Y', strtotime($data1));

      $dia2 = date('j', strtotime($data2));
      $mes2 = date('n', strtotime($data2));
      $ano2 = date('Y', strtotime($data2));
    
      if ($ano1 < $ano2) {
        return '<';
      } elseif ($ano1 == $ano2) {
        if ($mes1 < $mes2) {
          return '<';
        } elseif ($mes1 == $mes2) {
          if ($dia1 < $dia2) {
            return '<';
          } elseif ($dia1 == $dia2) {
            return '=';
          } else {
            return '>';
          }          
        } else {
          return '>';
        }          
      } else {
        return '>';
      }          
    }
    

    public function retornaTempoSubtracaoHoras($hora_inicial, $hora_final) {
      $minutos = $this->retornaMinutosSubtracaoHoras($hora_inicial, $hora_final);
      
      $horas = 0;
      while ($minutos >= 60) {
        $minutos -= 60;
        $horas += 1;        
      }

      $retorno = '';
      if ($horas <= 10) {
        $retorno.= "0".$horas.":";
      } else {
        $retorno.= $horas.":";
      }

      if ($minutos <= 10) {
        $retorno.= "0".$minutos.":00";
      } else {
        $retorno.= $minutos.":00";
      }

      return $retorno;
    } 
    
    public function retornaMinutosSubtracaoHoras($hora_inicial, $hora_final) {
      list($horas_inicial, $minutos_inicial, $segundos_inicial) = explode(":", $hora_inicial);

      $horas_inicial = $horas_inicial * 3600;
      $minutos_inicial = $minutos_inicial * 60;

      $total_inicial = $horas_inicial + $minutos_inicial + $segundos_inicial;

      list($horas_final, $minutos_final, $segundos_final) = explode(":", $hora_final);

      $horas_final = $horas_final * 3600;
      $minutos_final = $minutos_final * 60;

      $total_final = $horas_final + $minutos_final + $segundos_final;
      $total = ($total_final - $total_inicial);
      $total = $total / 60;
      
      return $total;
    } 
    
    public function retornaSegundosSubtracaoHoras($hora_inicial, $hora_final) {
      list($horas_inicial, $minutos_inicial, $segundos_inicial) = explode(":", $hora_inicial);

      $horas_inicial = $horas_inicial * 3600;
      $minutos_inicial = $minutos_inicial * 60;

      $total_inicial = $horas_inicial + $minutos_inicial + $segundos_inicial;

      list($horas_final, $minutos_final, $segundos_final) = explode(":", $hora_final);

      $horas_final = $horas_final * 3600;
      $minutos_final = $minutos_final * 60;

      $total_final = $horas_final + $minutos_final + $segundos_final;
      $total = ($total_final - $total_inicial);

      return $total;
    }
    
    public function compararHoraInicialFinal($hora_01, $hora_02) {
      //retorna true se hora_01 é menor que hora_02
      $hr_01 = date('H', strtotime($hora_01)); 
      $hr_02 = date('H', strtotime($hora_02));
      if ($hr_01 < $hr_02) {
        return true;
      } elseif ($hr_01 > $hr_02) {
        return false;
      } else {
        $mn_01 = date('i', strtotime($hora_01));
        $mn_02 = date('i', strtotime($hora_02));
        if ($mn_01 < $mn_02) {
          return true;
        } elseif ($mn_01 > $mn_02) {
          return false;
        } else {
          $se_01 = date('i', strtotime($hora_01));
          $se_02 = date('i', strtotime($hora_02));
          if ($se_01 < $se_02) {
            return true;
          } else {
            return false;
          }        
        }
      }
    }
    
    
     
  } 
?>
