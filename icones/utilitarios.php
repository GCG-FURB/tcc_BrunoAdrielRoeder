<?php
  class Utilitario {
  
    public function __construct() {
    }
    
    function mudarTamanhoFonte($acao) {
      $tamanho_atual = $_SESSION['life_tamanho_fonte'];
      $tamanho = $tamanho_atual;
      if ($acao == 'reduzir-tamanho-fonte')         {        $tamanho = $tamanho_atual - 1;
      } elseif ($acao == 'aumentar-tamanho-fonte')  {        $tamanho = $tamanho_atual + 1;
      } elseif ($acao == 'tamanho-fonte-regular')   {        $tamanho = 4;
      }
      if ($tamanho > 8) {        $tamanho = 8;      } elseif ($tamanho < 0) {        $tamanho = 0;      }
      $_SESSION['life_tamanho_fonte'] = $tamanho;
    }

    public function sortearValorNaoRepetido($valores, $inicial, $final) {
      $valido = false;
      while (!$valido) {
        $numero = rand($inicial, $final);
        $achou = false;
        foreach ($valores as $v) {
          if ($numero == $v) {
            $achou = true;
          }
        }
        if (!$achou) {
          $valido = true;
        }
      }  
      return $numero;
    }    
    
    public function retornaLinkSEO($nome, $tabela, $campo, $limite, $codigo) {
      //tirar acentos
      $from = 'ÀÁÃÂÉÊÍÓÕÔÚÜÇàáãâéêíóõôúüç';
      $to   = 'AAAAEEIOOOUUCaaaaeeiooouuc';
      $nome = strtr($nome, $from, $to);
      $nome = str_replace(",","",$nome);
      $nome = str_replace(".","",$nome);
      $nome = str_replace("?","",$nome);
      $nome = str_replace("!","",$nome);
      $nome = str_replace(":","",$nome);
      $nome = str_replace(";","",$nome);
      $nome = str_replace("\"","",$nome);
      $nome = str_replace("'","",$nome);
      $nome = str_replace("\\","",$nome);
      $nome = str_replace("/","",$nome);
      $nome = str_replace("+","",$nome);
      $nome = str_replace("-","",$nome);
      $nome = str_replace("*","",$nome);
      $nome = str_replace("-","",$nome);
      $nome = str_replace("_","",$nome);
      $nome = str_replace("&","",$nome);
      $nome = str_replace("@","",$nome);
      $nome = str_replace("#","",$nome);
      $nome = str_replace("%","",$nome);
      $nome = str_replace("$","",$nome);
             
      //jogar tudo caixa baixa
      $nome = strtolower($nome);

      //Separar todas as palavras baseadas em espaços*/     
      $array_entrada = explode(' ',$nome);       

      //Criar o array de saída     
      $resultado = array();       
      foreach($array_entrada as $palavra)     {         
        if (!in_array($palavra,$resultado)) {             
          $resultado[] = $palavra;         
        }     
      }

      $existe = true;
      $link = '';
      while ($existe) {
        $link = '';
        for ($i = 0; $i<count($resultado); $i++) {
          //checar se da para incluir nova palavra pelo tamanho
          if ((strlen($link) + strlen($resultado[$i])) < $limite) {
            //incluir
            $link.= $resultado[$i]."-";
          }
        }
        if (isset($link[(strlen($link)-1)])) {
          if ($link[(strlen($link)-1)] == '-') {
            $link = substr($link, 0, (strlen($link)-1));
          }
        } else {
          $existe = false;
        }
        
        //verificar se nao existe
        $dados = $this->pesquisaGenerica($tabela, $campo, $link);
        if (count($dados) == 0) {
          $existe = false;
        } else {
          $dados = $dados[0];
          $indexador = '';
          foreach ($dados as $d) {
            if ($indexador == '') {
              $indexador = $d;
            }
          }          
          if ($indexador == $codigo) {
            $existe = false;
          }
        }
        $limite -= 1;
      }
      return $link;
    }
        
    public function limparVariavel($variavel) {
      $variavel= addslashes($variavel);
      $variavel= str_replace( chr(13), " ", $variavel);
      $variavel= str_replace( chr(11), " ", $variavel);
      $variavel= str_replace("\"","'",$variavel);
      $variavel= str_replace("<P>", "", $variavel);
      $variavel= str_replace("<p>", "", $variavel);
      $variavel= str_replace("</P>", "", $variavel);
      $variavel= str_replace("</p>", "", $variavel);
      return $variavel;   
    }
    
    public function abreviar($termo, $tamanho) {
      $termos = explode(" ", $termo);
      $limite = 0;
      $acabou = false;
      $i = 0;
      $saida = '';
      while ((!$acabou) && (count($termos) < $i)) {
        $t = $termos[$i];
        $i += 1;
        if (($limite + strlen($t)) > $tamanho) {
          $acabou = true;
        } else {
          $saida.= $t.' ';
          $limite += strlen($t);
        }
      }
      if ($saida == '') {
        $saida = substr($termo,0,$tamanho);
      }
      if ($saida != $termo) {
        $saida.= '...';
      }
      return $saida;
    }

    public function retornaPrimeiroCaracter($termo) {
      if (strlen($termo) >= 1)  {
        return substr($termo,0,1);
      } else {
        return $termo;
      }    
    }

//*****************************CAMPOS COM HINT**********************************
    public function linhaUmCampoTextHint($obrigatorio, $descricao, $denominacao, $nome, $maximo, $tamanho, $valor, $tipo) {
      $ajuda = "\n\n";
      if ($obrigatorio) {
        $ajuda.= "- Campo é de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoTextHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoTextHint';
      }      
      if ($tipo == '1') {
        $ajuda.= '- Campo do Tipo Texto com capacidade para até '.$maximo.' caracteres';
      } elseif ($tipo == '2') { 
        $ajuda.= '- Campo do Tipo Numérico com capacidade para até '.$maximo.' caracteres.\n'.
                 '- Formato = 000000000,00';
      } elseif ($tipo == '3') { 
        $ajuda.= '- Campo do Tipo Hora com capacidade para até '.$maximo.' caracteres.\n'.
                 '- Formato = 00:00:00';
      }
      
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" style=\"width:".$tamanho."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" class=\"".$css."\" placeholder=\"".$descricao."\" tabindex=\"1\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\" />\n";
/*
      if ($ajuda != '') {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
*/
      echo "        </td>\n";
      echo "      </tr>\n";    
    }
    
    public function linhaUmCampoDataHint($obrigatorio, $descricao, $denominacao, $nome, $maximo, $tamanho, $valor, $exibir_ajuda) {
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      $ajuda = "\n\n";
      if ($obrigatorio) {
        $ajuda.= "- Campo de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoTextHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoTextHint';
      }             
      if ($exibir_ajuda) {
        $ajuda.= "- Campo do Tipo Data com capacidade para até ".$maximo." caracteres.\n".
                 "- Formato = Dia/Mês/Ano";
      }
      $data = $dh->inverterData($valor);
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$data."\" style=\"width:".$tamanho."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" class=\"".$css."\" placeholder=\"".$descricao."\" tabindex=\"1\" onKeyPress=\"AbreCalendario(260,230,'cadastro','".$nome."','".$valor."');\" onClick=\"AbreCalendario(260,230,'cadastro','".$nome."','".$valor."');\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
      echo "          <a href=\"javascript:AbreCalendario(260,230,'cadastro','".$nome."','".$valor."');\"><img src=\"icones/calendario.png\" border=\"0\" alt=\"Clique aqui para selecionar uma data!\"></a>\n";
      /*
      if (($ajuda != '') || ($exibir_ajuda)) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda) {
          echo "            Campo do Tipo Data com capacidade para até ".$maximo." caracteres.<br />Formato = Dia/Mês/Ano";
        }
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
      */
      echo "        </td>\n";
      echo "      </tr>\n";    
    }    
    
    

    public function linhaTextoHint($obrigatorio, $descricao, $denominacao, $nome, $valor, $altura, $largura, $exibir_ajuda) {
      $ajuda = "\n\n";
      if ($obrigatorio) {
        $ajuda.= "- Campo de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoTextoHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoTextoHint';
      }      
      if ($exibir_ajuda == '1') {
        $ajuda.= "- Campo do Tipo Texto com capacidade para até 65.535 caracteres\n";
      }
      echo "  	 <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <textarea name=\"".$nome."\" id=\"".$nome."\" rows=\"".$altura."\" class=\"".$css."\" style=\"width:".$largura."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" placeholder=\"".$descricao."\" tabindex=\"1\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">".$valor."</textarea>\n";
      /*
      if (($exibir_ajuda == '1') || ($ajuda != '')) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda == '1') {
          echo "            Campo do Tipo Texto com capacidade para até 65.535 caracteres\n";
        }
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
      */
      echo "        </td>\n";
      echo "      </tr>\n";
    }

    public function linhaSeletorHint($descricao, $denominacao, $nome, $setado, $opcoes, $tamanho, $obrigatorio, $exibir_ajuda) {
      $ajuda = "\n\n";
      if ($obrigatorio) {
        $ajuda.= "- Campo de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoSeletorHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoSeletorHint';
      }      
      if ($exibir_ajuda == '1') {
        $ajuda.= "- Campo do Tipo Seletor";
      }
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" class=\"".$css."\" placeholder=\"".$descricao."\" tabindex=\"1\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
      foreach ($opcoes as $op) {
        echo "  			    <option ";
        if ($op[0] == $setado) {          echo " selected ";        }
        echo " value=\"".$op[0]."\">".$op[1]."</option>\n";
      }
      echo "          </select>\n";
/*
      if (($exibir_ajuda == '1') || ($ajuda != '')) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda == '1') {
          echo "            Campo do Tipo Seletor\n";
        }
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
*/
      echo "        </td>\n";
      echo "      </tr>\n";    
    }
    
    public function linhaSeletorAcaoHint($descricao, $denominacao, $nome, $setado, $opcoes, $tamanho, $obrigatorio, $exibir_ajuda, $acao) {
      $ajuda = "\n\n";
      if ($obrigatorio) {
        $ajuda.= "- Campo de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoSeletorHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoSeletorHint';
      }      
      if ($exibir_ajuda == '1') {
        $ajuda.= "- Campo do Tipo Seletor\n";
      }
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <select name=\"".$nome."\" id=\"".$nome."\" style=\"width:".$tamanho."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" class=\"".$css."\" placeholder=\"".$descricao."\" tabindex=\"1\" ".$acao." onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
      foreach ($opcoes as $op) {
        echo "  			    <option ";
        if ($op[0] == $setado) {          echo " selected ";        }
        echo " value=\"".$op[0]."\">".$op[1]."</option>\n";
      }
      echo "          </select>\n";
/*
      if (($exibir_ajuda == '1') || ($ajuda != '')) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda == '1') {
          echo "            Campo do Tipo Seletor\n";
        }
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
*/
      echo "        </td>\n";
      echo "      </tr>\n";    
    }    

    public function linhaUmCampoArquivoHint($obrigatorio, $descricao, $denominacao, $nome, $maximo, $tamanho, $valor, $exibir_ajuda) {
      $ajuda = "";
      if ($obrigatorio) {
        $ajuda.= "- Campo de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoSeletorHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoSeletorHint';
      }     
      if ($exibir_ajuda == '1') {
        $ajuda.= "- Campo do Tipo Seletor de Arquivo\n";
      }
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"file\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\"  style=\"width:".$tamanho."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" class=\"".$css."\" placeholder=\"".$descricao."\" tabindex=\"1\" onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
/*
      if (($exibir_ajuda == '1') || ($ajuda != '')) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda == '1') {
          echo "            Campo do Tipo Seletor de Arquivo\n";
        }
        echo "          </span>\n";
        echo "        </a>\n";      
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }      
*/
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaUmCampoArquivoHintAcao($obrigatorio, $descricao, $denominacao, $nome, $maximo, $tamanho, $valor, $exibir_ajuda, $acao) {
      $ajuda = "";
      if ($obrigatorio) {
        $ajuda.= "- Campo de preenchimento obrigatório\n";
        $css = 'fontConteudoCampoSeletorHintObrigatorio';
      } else {
        $css = 'fontConteudoCampoSeletorHint';
      }
      if ($exibir_ajuda == '1') {
        $ajuda.= "- Campo do Tipo Seletor de Arquivo\n";
      }
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$denominacao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"file\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\"  style=\"width:".$tamanho."%;\" alt=\"".$descricao.$ajuda."\" title=\"".$descricao.$ajuda."\" class=\"".$css."\" placeholder=\"".$descricao."\" tabindex=\"1\" ".$acao." onFocus=\"alterarBorda(this,1)\" onBlur=\"alterarBorda(this,0)\">\n";
/*
      if (($exibir_ajuda == '1') || ($ajuda != '')) {
        echo "        <a href=\"#\" class=\"dcontexto\">\n";
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help.png\"border=\"0\">\n";
        echo "          <span class=\"fontdDetalhar\">\n";
        echo "            ".$ajuda."\n";
        if ($exibir_ajuda == '1') {
          echo "            Campo do Tipo Seletor de Arquivo\n";
        }
        echo "          </span>\n";
        echo "        </a>\n";
      } else {
        echo "          <img src=\"".$_SESSION['life_link_completo']."icones/help_vazio.png\"border=\"0\" alt=\"Sem Ajuda Disponível\" title=\"Sem Ajuda Disponível\">\n";
      }
*/
      echo "        </td>\n";
      echo "      </tr>\n";
    }




//******************************************************************************    
    
    public function campoHidden($nome, $valor) {
      echo "      <input type=\"hidden\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" />\n";    
    }

    public function linhaComentario($descricao) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          ".$descricao."\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaComentarioDestaque($descricao) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoDestaque\" colspan=\"2\">\n";
      echo "          ".$descricao."\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaComentarioChamada($descricao) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\"><h2>".$descricao."</h2></td>\n";
      echo "      </tr>\n";    
    }

    public function linhaComentarioAlerta($descricao) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoAlerta\" colspan=\"2\">\n";
      echo "          ".$descricao."\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaDuasColunasComentario($descricao, $valor) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$descricao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">".$valor."</td>\n";
      echo "      </tr>\n";    
    }
            
    public function linhaUmCampoText($obrigatorio, $descricao, $nome, $maximo, $tamanho, $valor) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" class=\"fontConteudoCampoTextHint\" style=\"width:".$tamanho."%;\"  tabindex=\"1\"/>\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }


    public function linhaUmCampoTextAcao($obrigatorio, $descricao, $nome, $maximo, $tamanho, $valor, $acao) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" class=\"fontConteudoCampoTextHint\" style=\"width:".$tamanho."%;\" ".$acao." tabindex=\"1\"/>\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaTresCamposTextAcao($obrigatorio, $descricao, $nome_1, $maximo_1, $tamanho_1, $valor_1, $acao_1, $nome_2, $maximo_2, $tamanho_2, $valor_2, $acao_2, $nome_3, $maximo_3, $tamanho_3, $valor_3, $acao_3) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo_1."\" name=\"".$nome_1."\" id=\"".$nome_1."\" value=\"".$valor_1."\" class=\"fontConteudoCampoTextHint\" style=\"width:".$tamanho_1."%;\" ".$acao_1." tabindex=\"1\"/>\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo_2."\" name=\"".$nome_2."\" id=\"".$nome_2."\" value=\"".$valor_2."\" class=\"fontConteudoCampoTextHint\" style=\"width:".$tamanho_2."%;\" ".$acao_2." tabindex=\"1\"/>\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo_3."\" name=\"".$nome_3."\" id=\"".$nome_3."\" value=\"".$valor_3."\" class=\"fontConteudoCampoTextHint\" style=\"width:".$tamanho_3."%;\" ".$acao_3." tabindex=\"1\"/>\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaTresCamposText($obrigatorio, $descricao, $nome_1, $maximo_1, $tamanho_1, $valor_1, $nome_2, $maximo_2, $tamanho_2, $valor_2, $nome_3, $maximo_3, $tamanho_3, $valor_3) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo_1."\" name=\"".$nome_1."\" id=\"".$nome_1."\" value=\"".$valor_1."\" class=\"fontConteudo\" style=\"width:".$tamanho_1."%;\" tabindex=\"1\" />\n";
      echo "          &nbsp;/&nbsp;\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo_2."\" name=\"".$nome_2."\" id=\"".$nome_2."\" value=\"".$valor_2."\" class=\"fontConteudo\" style=\"width:".$tamanho_2."%;\" tabindex=\"1\" />\n";
      echo "          &nbsp;/&nbsp;\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo_3."\" name=\"".$nome_3."\" id=\"".$nome_3."\" value=\"".$valor_3."\" class=\"fontConteudo\" style=\"width:".$tamanho_3."%;\" tabindex=\"1\" />\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaUmCampoSenha($obrigatorio, $descricao, $nome, $maximo, $tamanho, $valor) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"password\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" class=\"fontConteudo\" style=\"width:".$tamanho."%;\" tabindex=\"1\" />\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaUmCampoTextBotao($obrigatorio, $descricao, $nome, $maximo, $tamanho, $valor, $botao) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" class=\"fontConteudo\" style=\"width:".$tamanho."%;\" tabindex=\"1\" />\n";
      echo "  		    <input type=\"submit\" class=\"botao\" value=\"".$botao."\" tabindex=\"1\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }


    public function linhaDoisCamposText($obrigatorio, $descricao, $nome1, $maximo1, $tamanho1, $valor1, $nome2, $maximo2, $tamanho2, $valor2) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo1."\" name=\"".$nome1."\" id=\"".$nome1."\" value=\"".$valor1."\" class=\"fontConteudo\" style=\"width:".$tamanho_1."%;\"  tabindex=\"1\"/>\n";
      echo "          <input type=\"text\" maxlength=\"".$maximo2."\" name=\"".$nome2."\" id=\"".$nome2."\" value=\"".$valor2."\" class=\"fontConteudo\" style=\"width:".$tamanho_2."%;\" tabindex=\"1\" />\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }
                               
    public function linhaTexto($obrigatorio, $descricao, $nome, $valor, $altura, $largura) {
      echo "  	 <tr>\n";
      echo "  	   <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "<br />\n";
      echo "          <textarea name=\"".$nome."\" id=\"".$nome."\" rows=\"".$altura."\" class=\"fontConteudoCampoTextoHint\" style=\"width:".$largura."%;\">".$valor."</textarea>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
    
    }
    
    
    public function linhaSeletor($descricao, $nome, $setado, $opcoes, $tamanho) {
      echo "      <tr>\n";
      echo " 	      <td class=\"celConteudoChamada\">".$descricao."</td>\n";
      echo "  		  <td class=\"celConteudoCampo\">\n";
      echo "          <select name=\"".$nome."\" id=\"".$nome."\" class=\"fontConteudoCampoSeletorHint\" style=\"width:".$tamanho."%;\" tabindex=\"1\">\n";
      foreach ($opcoes as $op) {
        echo "  			    <option ";
        if ($op[0] == $setado) {
          echo " selected ";
        }
        echo " value=\"".$op[0]."\" class=\"fontConteudoCampoSeletorHint\">".$op[1]."</option>\n";
      }
      echo "          </select>\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaSeletorAcao($descricao, $nome, $setado, $opcoes, $tamanho, $acao) {
      echo "      <tr>\n";
      echo " 	      <td class=\"celConteudoChamada\">".$descricao."</td>\n";
      echo "  		  <td class=\"celConteudoCampo\">\n";
      echo "          <select name=\"".$nome."\" id=\"".$nome."\" class=\"fontConteudoCampoSeletorHint\" style=\"width:".$tamanho."%;\" $acao>\n";
      foreach ($opcoes as $op) {
        echo "  			    <option ";
        if ($op[0] == $setado) {
          echo " selected ";
        }
        echo " value=\"".$op[0]."\" class=\"fontConteudoCampoSeletorHint\">".$op[1]."</option>\n";
      }
      echo "          </select>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
    }

    public function linhaSeletorNLinhas($descricao, $nome, $setado, $opcoes, $linhas, $multiplo) {
      if ($multiplo) {        $array = '[]';      } else {        $array = '';      }
      echo "      <tr>\n";
      echo " 	      <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao."\n";
      echo "        </td>\n";
      echo "  		  <td class=\"celConteudoCampo\">\n";
      echo "          <select name=\"".$nome.$array."\" id=\"".$nome."\" size=\"".$linhas."\" ";
      if ($multiplo) {
        echo " multiple ";
      }
      echo "class=\"fontConteudo\">\n";
      foreach ($opcoes as $op) {
        echo "  			    <option ";
        foreach ($setado as $s) {
          if ($op[0] == $s) {
            echo " selected ";
          }
        }
        echo " value=\"".$op[0]."\" class=\"fontConteudo\">".$op[1]."</option>\n";
      }
      echo "          </select>\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }
    
    public function linhaSeletorBotao($descricao, $nome, $setado, $opcoes, $botao) {
      echo "      <tr>\n";
      echo " 	      <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao."\n";
      echo "        </td>\n";
      echo "  		  <td class=\"celConteudoCampo\">\n";
      echo "          <select name=\"".$nome."\" id=\"".$nome."\" class=\"fontConteudo\">\n";
      foreach ($opcoes as $op) {
        echo "  			    <option ";
        if ($op[0] == $setado) {
          echo " selected ";
        }
        echo " value=\"".$op[0]."\" class=\"fontConteudo\">".$op[1]."</option>\n";
      }
      echo "          </select>\n";
      echo "  		  <input type=\"submit\" class=\"botao\" value=\"".$botao."\" tabindex=\"1\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }
        
    public function linhaBotao($valor, $acao) {
      echo "  	<tr>\n";
      echo "  		<td class=\"celConteudoBotao\" style=\"text-align:center;\" colspan=\"2\">\n";
      echo "  		  <input type=\"button\" class=\"botao\" value=\"".$valor."\" onClick=\"".$acao."\" tabindex=\"1\">\n";
      echo "      </td>\n";
      echo "  	</tr>\n";					    
    }

    public function linhaBotoesConfirmarCancelar($link) {
      echo "  	<tr>\n";
      echo "  		<td class=\"celConteudo\" style=\"text-align:center;\" colspan=\"2\">\n";
      echo "        <input type=\"button\" value=\"Cancelar\"  class=\"botao\" onclick=\"location.href='".$link."'\" tabindex=\"1\" />\n";
      echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "  		  <input type=\"submit\" value=\"Confirmar\" class=\"botao\" tabindex=\"1\">\n";
      echo "      </td>\n";
      echo "  	</tr>\n";					    
    }


    public function linhaUmCampoArquivo($obrigatorio, $descricao, $nome, $maximo, $tamanho, $valor) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">\n";
      echo "          ".$descricao." ";
      if ($obrigatorio) {
        echo " *";       
      }
      echo "\n";
      echo "        </td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo "          <input type=\"file\" maxlength=\"".$maximo."\" name=\"".$nome."\" id=\"".$nome."\" value=\"".$valor."\" class=\"fontConteudo\" style=\"width:".$tamanho."%;\" tabindex=\"1\" />\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    public function linhaUmCampoCheckBox($descricao, $nome, $valor, $setado) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          <input type=\"checkbox\" name=\"".$nome."\" id=\"".$nome."\" ";
      if ($setado) {
        echo " checked=\"checked\" ";
      } 
      echo " value=\"".$valor."\" class=\"fontConteudo\"  tabindex=\"1\"/> ".$descricao."\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
    }

    
    public function linhaNCampoCheckBox($lista, $nome, $setado) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      foreach ($lista as $lt) {
        echo "          <input type=\"checkbox\" name=\"".$nome."\" ";
        if ($setado == $lt[0]) {
          echo " checked=\"checked\" ";
        } 
        echo " value=\"".$lt[0]."\" class=\"fontConteudo\"  tabindex=\"1\"/> ".$lt[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      }
      echo "        </td>\n";
      echo "      </tr>\n";    
    }    

    public function linhaNCampoRadio($lista, $nome, $setado, $quebrar=false) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      foreach ($lista as $lt) {
        echo "          <input type=\"radio\" name=\"".$nome."\" id=\"".$nome."\" ";
        if ($setado == $lt[0]) {
          echo " checked=\"checked\" ";
        } 
        echo " value=\"".$lt[0]."\" class=\"fontConteudo\"  tabindex=\"1\"/> ".$lt[1];
        if ($quebrar) {
          echo "<br />\n";
        } else {
          echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        }      
      }
      echo "        </td>\n";
      echo "      </tr>\n";    
    }    

    public function colunaNCampoRadio($lista, $nome, $setado) {
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      foreach ($lista as $lt) {
        echo "          <input type=\"radio\" name=\"".$nome."\" id=\"".$nome."\" ";
        if ($setado == $lt[0]) {
          echo " checked=\"checked\" ";
        } 
        echo " value=\"".$lt[0]."\" class=\"fontConteudo\" tabindex=\"1\" /> ".$lt[1]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        echo "<br />\n";      
      }
      echo "        </td>\n";
      echo "      </tr>\n";    
    }    

    public function duasColunasNCampoRadio($lista, $nome, $setado) {
      $ind= 0;
      while ($ind < count($lista)) {
        $lt= $lista[$ind];
        echo "      <tr>\n";
        echo "		    <td class=\"celConteudoChamada\">\n";
        echo "          <input type=\"radio\" name=\"".$nome."\" id=\"".$nome."\" ";
        if ($setado == $lt[0]) {          echo " checked=\"checked\" ";        } 
        echo " value=\"".$lt[0]."\" class=\"fontConteudo\" tabindex=\"1\" /> ".$lt[1]."\n";
        echo "        </td>\n";
        $ind+= 1;
        if (isset($lista[$ind])) {
          $lt= $lista[$ind];
          echo "		    <td class=\"celConteudoCampo\">\n";
          echo "          <input type=\"radio\" name=\"".$nome."\" id=\"".$nome."\" ";
          if ($setado == $lt[0]) {          echo " checked=\"checked\" ";        } 
          echo " value=\"".$lt[0]."\" class=\"fontConteudo\" tabindex=\"1\" /> ".$lt[1]."\n";
          echo "        </td>\n";
        }
        echo "      </tr>\n";    
        $ind+= 1;
      }
    }    
    
    public function linhaDataInicio($descricao, $dia, $mes, $ano) {
      require_once 'includes/data_hora.php';
      $data_hora= new DataHora();
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$descricao."</td>\n";
      echo "  	    <td class=\"celConteudoCampo\">\n";
      echo $data_hora->apresentaFormularioDataInicio($dia, $mes, $ano);
      echo "        </td>\n";
      echo "      </tr>\n";    
    }
            
    public function linhaDuasDatas($descricao1, $dia1, $mes1, $ano1, $sufixo1, $descricao2, $dia2, $mes2, $ano2, $sufixo2) {
      require_once 'includes/data_hora.php';
      $data_hora= new DataHora();
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">".$descricao1."\n";
      echo $data_hora->apresentaFormularioData($dia1, $mes1, $ano1, $sufixo1);
      echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
      echo "		    ".$descricao2."\n";
      echo $data_hora->apresentaFormularioData($dia2, $mes2, $ano2, $sufixo2);
      echo "        </td>\n";
      echo "      </tr>\n";        
    }

    public function linhaData($descricao, $data, $sufixo) {
      require_once 'includes/data_hora.php';
      $data_hora= new DataHora();
      $dia= date('j', strtotime($data));
      $mes= date('n', strtotime($data));
      $ano= date('Y', strtotime($data));               
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$descricao."</td>\n";
      echo "		    <td class=\"celConteudoCampo\">\n";
      echo $data_hora->apresentaFormularioData($dia, $mes, $ano, $sufixo);
      echo "        </td>\n";
      echo "      </tr>\n";        
    }

    public function linhaDuasDatasBotao($descricao1, $dia1, $mes1, $ano1, $sufixo1, $descricao2, $dia2, $mes2, $ano2, $sufixo2, $botao) {
      require_once 'includes/data_hora.php';
      $data_hora= new DataHora();
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudoChamada\">".$descricao1."\n";
      echo $data_hora->apresentaFormularioData($dia1, $mes1, $ano1, $sufixo1);
      echo "        </td>\n";
      echo "		    <td class=\"celConteudoCampo\">".$descricao2."\n";
      echo $data_hora->apresentaFormularioData($dia2, $mes2, $ano2, $sufixo2);
      echo "  		  <input type=\"submit\" class=\"botao\" value=\"".$botao."\" tabindex=\"1\">\n";
      echo "        </td>\n";
      echo "      </tr>\n";        
    }
    
    public function posicionarCursor($formulario, $campo) {
      echo "<script>\n";
      echo "  document.".$formulario.".".$campo.".focus();\n";
      echo "</script>\n";    
    }


    public function linhaDuasColunasComentarioRelatorio($descricao, $valor) {
      $retorno = "      <tr>\n";
      $retorno.= "		    <td class=\"celConteudoChamada\">".$descricao."&nbsp;</td>\n";
      $retorno.= "		    <td class=\"celConteudoCampo\">".$valor."&nbsp;</td>\n";
      $retorno.= "      </tr>\n";
      return $retorno;    
    }

    public function linhaComentarioRelatorio($descricao) {
      $retorno = "      <tr>\n";
      $retorno.= "		    <td class=\"celConteudo\" colspan=\"2\">".$descricao."</td>\n";
      $retorno.= "      </tr>\n";
      return $retorno;    
    }
    
    public function existeElementoArray($elemento, $chave, $array) {
      foreach ($array as $elemento_atual) {
        if ($elemento[$chave] == $elemento_atual[$chave]) {
          return true;
        }      
      }
      return false;
    }
    
    public function ordenaArrayBidimensionalBubbleSort($array, $chave) {
      $tamanho = count($array);
      for ($i=0; $i<$tamanho; $i++) {
        for ($ii=0; $ii<($tamanho-1); $ii++) {
          $elemento_1 = $array[$ii+1];
          $elemento_2 = $array[$ii];
          if ($elemento_1[$chave] <= $elemento_2[$chave]) {
            $array[$ii+1] = $elemento_2;
            $array[$ii] = $elemento_1;
          }
        }
      }
      return $array;    
    
    }

    public function gerarLog($ds_comando, $nm_tabela) {
      if (isset($_SESSION['life_codigo'])) {
        $cd_usuario= $_SESSION['life_codigo'];
      } else {
        $cd_usuario= 0;
      }
      $ip_maquina= $_SERVER['REMOTE_ADDR'];
      $ds_comando= $this->limparVariavel($ds_comando).";";
      $sql  = "INSERT INTO life_log ".
              "(cd_usuario, dt_log, ip_maquina, ds_comando, nm_tabela) ".
              "VALUES ".
              "('$cd_usuario', now(), '$ip_maquina', \"$ds_comando\", '$nm_tabela')";     
      mysql_query($sql) or die ("Erro no banco de dados - TABELA LOG!");
      $saida = mysql_affected_rows();
      return $saida;    
    }
    
    public function pesquisaGenerica($tabela, $campo, $valor) {
      $sql = "SELECT * ".
             "FROM ".$tabela." ".
             "WHERE ".$campo." = '".$valor."' "; 
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERICA!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
        $dados[] = $linha;
      }
      return $dados;      
    } 
  }
  
?>