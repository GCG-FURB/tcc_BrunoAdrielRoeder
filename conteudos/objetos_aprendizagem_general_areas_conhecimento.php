<?php
  class ObjetoAprendizagemGeneralAreaConhecimento {
    
    public function __construct () {
    }

    public function retornaSeletorAreasConhecimentoObjetoAprendizagem($cd_general, $descricao, $denominacao) {
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'conteudos/sub_areas_conhecimento.php';                      $sac = new SubAreaConhecimento();

      if ($cd_general > 0) {
        $relacao = $this->selectAreasConhecimentoObjetoAprendizagem($cd_general, '', '1');
      } else {
        $relacao = array();
      }
      $itens = $are_con->selectAreasConhecimento('1');


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
            echo "              <input type=\"checkbox\" name=\"cd_area_conhecimento_".$item['cd_area_conhecimento']."\" id=\"cd_area_conhecimento_".$item['cd_area_conhecimento']."\" ";
            $setado = false;
            $display = 'none';
            foreach ($relacao as $r) {
              if ($item['cd_area_conhecimento'] == $r['cd_area_conhecimento']) {
                $setado = true;
                $display = 'block';
              }
            }
            if ($setado) {          echo " checked=\"checked\" ";        }
            $cd_area_conhecimento = $item['cd_area_conhecimento'];
            echo " value=\"".$item['cd_area_conhecimento']."\" class=\"fontConteudo\" onClick=\"atualizaCampoSubArea('$cd_area_conhecimento');\"/> ".$item['nm_area_conhecimento'];
            $util->campoHidden('campo_area_'.$i, "cd_area_conhecimento_".$item['cd_area_conhecimento']);
            echo "		        <div id=\"sub_area_".$cd_area_conhecimento."\" style=\"width:100%; display:".$display.";\">\n";
            echo $sac->retornaCadastroSubAreasConhecimentoObjetoAprendizagemMultiplos($cd_area_conhecimento, 100, $cd_general);
            echo "            </div>\n";
            $i+= 1;
          } else {
            echo "              &nbsp;\n";
          }
          echo "            </td>\n";
        }

        echo "          </tr>\n";
      }
      $util->campoHidden('qt_area_conhecimento', count($itens));
      echo "          </table>\n";
      echo "        </td>\n";
      echo "      </tr>\n";
    }


    public function salvarCadastroAlteracao($cd_general) {
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      require_once 'conteudos/sub_areas_conhecimento_general.php';              $sacg = new SubAreaConhecimentoGeneral();

      $itens = $are_con->selectAreasConhecimento('1');

      $this->inativarAreasConhecimentoObjetoAprendizagem($cd_general);
      $sacg->alteraStatusSubAreaConhecimentoGeneral('', $cd_general, '0');

      $erros = false;
      foreach ($itens as $it) {
        $variavel = "cd_area_conhecimento_".$it['cd_area_conhecimento'];
        if (isset($_POST[$variavel])) {
          $cd_area_conhecimento = $it['cd_area_conhecimento'];
          $sacg->salvarSubAreasConhecimentoGeneral($cd_general, $cd_area_conhecimento);
          if ($this->existeRelacao($cd_general, $cd_area_conhecimento, '2')) {
            if (!$this->ativarAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento)) {
              $erros = true;
            }
          } else {
            if (!$this->registrarAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento)) {
              $erros = true;
            }
          }
        }
      }
      return !$erros;
    }

    private function existeRelacao($cd_general, $cd_area_conhecimento, $eh_ativo) {
      $dados = $this->selectDadosAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento, $eh_ativo);
      if ($dados['cd_general_area_conhecimento'] != '') {
        return true;
      } else {
        return false;
      }
    }

    public function selectDadosMaiorAreaConhecimento($cd_general) {
      $relacao = $this->selectAreasConhecimentoObjetoAprendizagem($cd_general, '', '1');
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

    public function retornaDadosAreas($cd_general, $descricao) {
      $relacao = $this->selectAreasConhecimentoObjetoAprendizagem($cd_general, '', '1');

      $niveis = '';
      $i = 1;
      foreach ($relacao as $r) {
        $niveis.= $r['nm_area_conhecimento'];
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


    public function listaAreasConhecimento($cd_general) {
      $relacao = $this->selectAreasConhecimentoObjetoAprendizagem($cd_general, '', '1');

      $niveis = '';
      $i = 1;
      foreach ($relacao as $r) {
        $niveis.= $r['nm_area_conhecimento'];
        if ($i < count($relacao)) {
          $niveis.= ' | ';
        }
        $i+= 1;
      }

      return $niveis;
    }

    public function retornaRelacaoAreas($cd_general) {
      $relacao = $this->selectAreasConhecimentoObjetoAprendizagem($cd_general, '', '1');

      $areas = '';
      foreach ($relacao as $r) {
        $areas.= $r['nm_area_conhecimento']." | ";
      }
      return $areas;
    }

    public function retornaUmaAreaConhecimento($cd_general) {
      $relacao = $this->selectAreasConhecimentoObjetoAprendizagem($cd_general, '', '1');

      $item = $relacao[rand(0,((count($relacao))-1))];

      return $item;
    }


//**************BANCO DE DADOS**************************************************
    public function selectAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento, $eh_ativo) {
      $sql  = "SELECT gne.*, ne.nm_area_conhecimento ".
              "FROM life_general_areas_conhecimento gne, life_areas_conhecimento ne ".
              "WHERE gne.cd_area_conhecimento = ne.cd_area_conhecimento ";
      if ($cd_general != '') {
        $sql.= "AND gne.cd_general = '$cd_general' ";
      }
      if ($cd_area_conhecimento != '') {
        $sql.= "AND gne.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($eh_ativo != '2') {
        $sql.= "AND gne.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ne.nm_area_conhecimento ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL ÁREAS CONHECIMENTO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;
    }

    public function selectDadosAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento, $eh_ativo) {
      $sql  = "SELECT gne.*, ne.nm_area_conhecimento ".
              "FROM life_general_areas_conhecimento gne, life_areas_conhecimento ne ".
              "WHERE gne.cd_area_conhecimento = ne.cd_area_conhecimento ";
      if ($cd_general != '') {
        $sql.= "AND gne.cd_general = '$cd_general' ";
      }
      if ($cd_area_conhecimento != '') {
        $sql.= "AND gne.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($eh_ativo != '2') {
        $sql.= "AND gne.eh_ativo = '$eh_ativo' ";
      }
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL ÁREAS CONHECIMENTO!");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;
    }

    public function registrarAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento) {
      $sql = "INSERT INTO life_general_areas_conhecimento   ".
             "(cd_general, cd_area_conhecimento, eh_ativo) ".
             "VALUES ".
             "(\"$cd_general\", \"$cd_area_conhecimento\", \"1\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general_areas_conhecimento');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL ÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      return $saida;
    }

    public function inativarAreasConhecimentoObjetoAprendizagem($cd_general) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_general_areas_conhecimento SET ".
             "eh_ativo = \"0\" ".
             "WHERE cd_general = '$cd_general' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general_areas_conhecimento');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL ÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

    public function ativarAreasConhecimentoObjetoAprendizagem($cd_general, $cd_area_conhecimento) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_general_areas_conhecimento SET ".
             "eh_ativo = \"1\" ".
             "WHERE cd_general = '$cd_general' ".
             "AND cd_area_conhecimento = '$cd_area_conhecimento' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'general_areas_conhecimento');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA GENERAL ÁREAS CONHECIMENTO!");
      $saida = mysql_affected_rows();
      return $saida;
    }

  }
?>