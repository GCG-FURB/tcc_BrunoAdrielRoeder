<?php
  class ObjetoAprendizagemGeneralNiveiEducacional {
    
    public function __construct () {
    }

    public function retornaSeletorNiveisEducacionaisObjetoAprendizagem($cd_general, $descricao, $denominacao) {
      require_once 'conteudos/niveis_educacionais.php';                         $niv_edu = new NivelEducacional();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      if ($cd_general > 0) {
        $relacao = $this->selectNiveisEducacionaisObjetoAprendizagem($cd_general, '', '1');
      } else {
        $relacao = array();
      }

      $itens = $niv_edu->selectNiveisEducacionais('1');


      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          ".$denominacao."<br />\n";
      echo "          <table class=\"tabConteudo\">\n";
      $i = 0;
      while ($i < count($itens)) {
        echo "          <tr>\n";
        for ($x=1; $x<=3; $x++) {
          echo "		        <td class=\"celConteudo\" style=\"width:33%;\">\n";
          if (isset($itens[$i])) {
            $item = $itens[$i];
            echo "              <input type=\"checkbox\" name=\"cd_nivel_educacional_".$item['cd_nivel_educacional']."\" id=\"cd_nivel_educacional_".$item['cd_nivel_educacional']."\" ";
            $setado = false;
            foreach ($relacao as $r) {
              if ($item['cd_nivel_educacional'] == $r['cd_nivel_educacional']) {
                $setado = true;
              }
            }
            if ($setado) {          echo " checked=\"checked\" ";        }
            echo " value=\"".$item['cd_nivel_educacional']."\" class=\"fontConteudo\" /> ".$item['nm_nivel_educacional'];
            $util->campoHidden('campo_'.$i, "cd_nivel_educacional_".$item['cd_nivel_educacional']);
          } else {
            echo "              &nbsp;\n";
          }
          echo "            </td>\n";
          $i+= 1;
        }
        echo "          </tr>\n";
      }
      echo "          </table>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
      $util->campoHidden('qt_niveis_educacionais', count($itens));
    }


    public function salvarCadastroAlteracao($cd_general) {
      require_once 'conteudos/niveis_educacionais.php';                         $niv_edu = new NivelEducacional();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $itens = $niv_edu->selectNiveisEducacionais('1');

      $this->inativarNiveisEducacionaisObjetoAprendizagem($cd_general);

      $erros = false;
      foreach ($itens as $it) {
        $variavel = "cd_nivel_educacional_".$it['cd_nivel_educacional'];
        if (isset($_POST[$variavel])) {
          $cd_nivel_educacional = $it['cd_nivel_educacional'];

          if ($this->existeRelacao($cd_general, $cd_nivel_educacional, '2')) {
            if (!$this->ativarNivelEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional)) {
              $erros = true;
            }
          } else {
            if (!$this->registrarNivelEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional)) {
              $erros = true;
            }
          }
        }
      }
      return !$erros;
    }

    private function existeRelacao($cd_general, $cd_nivel_educacional, $eh_ativo) {
      $dados = $this->selectDadosNivelEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional, $eh_ativo);
      if ($dados['cd_general_nivel_educacional'] != '') {
        return true;
      } else {
        return false;
      }
    }

    public function selectDadosMaiorNivelEducacional($cd_general) {
      $relacao = $this->selectNiveisEducacionaisObjetoAprendizagem($cd_general, '', '1');
      $maior = 0;
      $id_maior = '';
      $id = 0;
      foreach ($relacao as $r) {
        if ($r['nr_grau_nivel'] > $maior) {
          $id_maior = $id;
          $maior = $r['nr_grau_nivel'];
        }
        $id += 1;
      }
      $dados = $relacao[$id_maior];

      return $dados;
    }

    public function retornaDadosNiveis($cd_general, $descricao) {
      $relacao = $this->selectNiveisEducacionaisObjetoAprendizagem($cd_general, '', '1');

      $niveis = '';
      $i = 1;
      foreach ($relacao as $r) {
        $niveis.= $r['nm_nivel_educacional'];
        if ($i < (count($relacao) - 1)) {
          $niveis.= ', ';
        } elseif ($i < count($relacao)) {
          $niveis.= ' e ';
        }
        $i+= 1;
      }
      $retorno = "<b>".$descricao."</b> ".$niveis;

      return $retorno;
    }

    public function retornaRelacaoNiveis($cd_general) {
      $relacao = $this->selectNiveisEducacionaisObjetoAprendizagem($cd_general, '', '1');

      $niveis = '';
      $i = 1;
      foreach ($relacao as $r) {
        $niveis.= $r['nm_nivel_educacional'];
        if ($i < count($relacao)) {
          $niveis.= ' | ';
        }
        $i+= 1;
      }
      return $niveis;
    }

//**************BANCO DE DADOS**************************************************
    public function selectNiveisEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional, $eh_ativo) {
      $sql  = "SELECT gne.*, ne.* ".
              "FROM life_general_niveis_educacionais gne, life_niveis_educacionais ne ".
              "WHERE gne.cd_nivel_educacional = ne.cd_nivel_educacional ";
      if ($cd_general != '') {
        $sql.= "AND gne.cd_general = '$cd_general' ";
      }
      if ($cd_nivel_educacional != '') {
        $sql.= "AND gne.cd_nivel_educacional = '$cd_nivel_educacional' ";
      }
      if ($eh_ativo != '2') {
        $sql.= "AND gne.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ne.nm_nivel_educacional ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL NÍVEIS EDUCACIONAIS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;
    }

    public function selectDadosNivelEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional, $eh_ativo) {
      $sql  = "SELECT gne.*, ne.nm_nivel_educacional ".
              "FROM life_general_niveis_educacionais gne, life_niveis_educacionais ne ".
              "WHERE gne.cd_nivel_educacional = ne.cd_nivel_educacional ";
      if ($cd_general != '') {
        $sql.= "AND gne.cd_general = '$cd_general' ";
      }
      if ($cd_nivel_educacional != '') {
        $sql.= "AND gne.cd_nivel_educacional = '$cd_nivel_educacional' ";
      }
      if ($eh_ativo != '2') {
        $sql.= "AND gne.eh_ativo = '$eh_ativo' ";
      }
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL NÍVEIS EDUCACIONAIS!");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;
    }

    public function registrarNivelEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional) {
      $sql = "INSERT INTO life_general_niveis_educacionais   ".
             "(cd_general, cd_nivel_educacional, eh_ativo) ".
             "VALUES ".
             "(\"$cd_general\", \"$cd_nivel_educacional\", \"1\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general_niveis_educacionais');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL NÍVEIS EDUCACIONAIS!");
      $saida = mysql_affected_rows();
      return $saida;
    }

    public function inativarNiveisEducacionaisObjetoAprendizagem($cd_general) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_general_niveis_educacionais SET ".
             "eh_ativo = \"0\" ".
             "WHERE cd_general = '$cd_general' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general_niveis_educacionais');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL NÍVEIS EDUCACIONAIS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

    public function ativarNivelEducacionaisObjetoAprendizagem($cd_general, $cd_nivel_educacional) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_general_niveis_educacionais SET ".
             "eh_ativo = \"1\" ".
             "WHERE cd_general = '$cd_general' ".
             "AND cd_nivel_educacional = '$cd_nivel_educacional' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general_niveis_educacionais');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL NÍVEIS EDUCACIONAIS!");
      $saida = mysql_affected_rows();
      return $saida;
    }

  }
?>