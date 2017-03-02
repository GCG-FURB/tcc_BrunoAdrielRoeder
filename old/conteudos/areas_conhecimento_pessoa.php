<?php
  class AreasConhecimentoPessoa {
    
    public function __construct () {
    }

    public function retornaSeletorAreasConhecimentoPessoa($cd_pessoa) {
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $areas = $are_con->selectAreasConhecimento('1');
      $itens = $this->selectAreasConhecimentoPessoa('', $cd_pessoa, '1');
      
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          Áreas de Interesses:<br />\n";
      echo "          <table class=\"tabConteudo\">\n";
      $i = 0;
      while ($i < count($areas)) {
        echo "          <tr>\n";
        for ($x=1; $x<=3; $x++) {
          echo "		        <td class=\"celConteudo\" style=\"width:33%;\">\n";
          if (isset($areas[$i])) {
            $area = $areas[$i];
            echo "              <input type=\"checkbox\" name=\"cd_area_conhecimento_".$area['cd_area_conhecimento']."\" id=\"cd_area_conhecimento_".$area['cd_area_conhecimento']."\" ";
            $setado = false;
            foreach ($itens as $it) {
              if ($it['cd_area_conhecimento'] == $area['cd_area_conhecimento']) {
                $setado = true;
              }
            }
            if ($setado) {          echo " checked=\"checked\" ";        }
            echo " value=\"".$area['cd_area_conhecimento']."\" class=\"fontConteudo\" /> ".$area['nm_area_conhecimento'];
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
    }


    public function salvarCadastroAlteracao() {
      require_once 'conteudos/areas_conhecimento.php';                          $are_con = new AreaConhecimento();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $areas = $are_con->selectAreasConhecimento('1');

      $cd_pessoa = addslashes($_POST['cd_pessoa']);
      $this->inativarAreasConhecimentoPessoa($cd_pessoa);

      $erros = false;
      foreach ($areas as $area) {
        $variavel = "cd_area_conhecimento_".$area['cd_area_conhecimento'];
        if (isset($_POST[$variavel])) {
          $cd_area_conhecimento = $area['cd_area_conhecimento'];

          if ($this->existeRelacao($cd_pessoa, $cd_area_conhecimento, '2')) {
            if (!$this->ativarAreasConhecimentoPessoa($cd_pessoa, $cd_area_conhecimento)) {
              $erros = true;
            }
          } else {
            if (!$this->registrarAreasConhecimentoPessoa($cd_pessoa, $cd_area_conhecimento)) {
              $erros = true;
            }
          }
        }
      }
      return !$erros;
    }

    private function existeRelacao($cd_pessoa, $cd_area_conhecimento, $eh_ativo) {
      $dados = $this->selectDadosAreaConhecimentoPessoa($cd_area_conhecimento, $cd_pessoa, $eh_ativo);
      if ($dados['cd_area_conhecimento_pessoa'] != '') {
        return true;
      } else {
        return false;
      }
    }


//**************BANCO DE DADOS**************************************************    
    public function selectAreasConhecimentoPessoa($cd_area_conhecimento, $cd_pessoa, $eh_ativo) {
      $sql  = "SELECT acp.*, ac.nm_area_conhecimento ".
              "FROM life_areas_conhecimento_pessoa acp, life_areas_conhecimento ac ".
              "WHERE acp.cd_area_conhecimento = ac.cd_area_conhecimento ";
      if ($cd_pessoa != '') {
        $sql.= "AND acp.cd_pessoa = '$cd_pessoa' ";
      }
      if ($cd_area_conhecimento != '') {
        $sql.= "AND acp.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND acp.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ac.nm_area_conhecimento ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;
    }

    public function selectDadosAreaConhecimentoPessoa($cd_area_conhecimento, $cd_pessoa, $eh_ativo) {
      $sql  = "SELECT acp.*, ac.nm_area_conhecimento ".
              "FROM life_areas_conhecimento_pessoa acp, life_areas_conhecimento ac ".
              "WHERE acp.cd_area_conhecimento = ac.cd_area_conhecimento ";
      if ($cd_pessoa != '') {
        $sql.= "AND acp.cd_pessoa = '$cd_pessoa' ";
      }
      if ($cd_area_conhecimento != '') {
        $sql.= "AND acp.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND acp.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ac.nm_area_conhecimento ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS FORMAÇÃO!");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;
    }



    private function registrarAreasConhecimentoPessoa($cd_pessoa, $cd_area_conhecimento) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_areas_conhecimento_pessoa ".
             "(cd_area_conhecimento, cd_pessoa, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_area_conhecimento\", \"$cd_pessoa\", \"1\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_conhecimento_pessoa');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO PESSOA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    private function inativarAreasConhecimentoPessoa($cd_pessoa) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_areas_conhecimento_pessoa SET ".
             "eh_ativo = \"0\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_pessoa = '$cd_pessoa' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_conhecimento_pessoa');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO PESSOA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

    private function ativarAreasConhecimentoPessoa($cd_pessoa, $cd_area_conhecimento) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_areas_conhecimento_pessoa SET ".
             "eh_ativo = \"1\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_area_conhecimento= '$cd_area_conhecimento' ".
             "AND cd_pessoa = '$cd_pessoa' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'areas_conhecimento_pessoa');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ÁREAS CONHECIMENTO PESSOA!");
      $saida = mysql_affected_rows();
      return $saida;
    }
  }
?>