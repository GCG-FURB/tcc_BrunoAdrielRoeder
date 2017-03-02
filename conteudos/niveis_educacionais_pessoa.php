<?php
  class NivelEducacionalPessoa {
    
    public function __construct () {
    }

    public function retornaSeletorNivelEducacionalPessoa($cd_pessoa) {
      require_once 'conteudos/niveis_educacionais.php';                          $niv_edu = new NivelEducacional();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      
      $areas = $niv_edu->selectNiveisEducacionais('1');
      $itens = $this->selectNivelEducacionalPessoa('', $cd_pessoa, '1');
      
      echo "      <tr>\n";
      echo "		    <td class=\"celConteudo\" colspan=\"2\">\n";
      echo "          Níveis educacionais de interesse<br />\n";
      echo "          <table class=\"tabConteudo\">\n";
      $i = 0;
      while ($i < count($areas)) {
        echo "          <tr>\n";
        for ($x=1; $x<=3; $x++) {
          echo "		        <td class=\"celConteudo\" style=\"width:33%;\">\n";
          if (isset($areas[$i])) {
            $area = $areas[$i];
            echo "              <input type=\"checkbox\" name=\"cd_nivel_educacional_".$area['cd_nivel_educacional']."\" id=\"cd_nivel_educacional_".$area['cd_nivel_educacional']."\" ";
            $setado = false;
            foreach ($itens as $it) {
              if ($it['cd_nivel_educacional'] == $area['cd_nivel_educacional']) {
                $setado = true;
              }
            }
            if ($setado) {          echo " checked=\"checked\" ";        }
            echo " value=\"".$area['cd_nivel_educacional']."\" class=\"fontConteudo\" /> ".$area['nm_nivel_educacional'];
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
      require_once 'conteudos/niveis_educacionais.php';                          $niv_edu = new NivelEducacional();
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $areas = $niv_edu->selectNiveisEducacionais('1');

      $cd_pessoa = addslashes($_POST['cd_pessoa']);
      $this->inativarNivelEducacionalPessoa($cd_pessoa);

      $erros = false;
      foreach ($areas as $area) {
        $variavel = "cd_nivel_educacional_".$area['cd_nivel_educacional'];
        if (isset($_POST[$variavel])) {
          $cd_nivel_educacional = $area['cd_nivel_educacional'];

          if ($this->existeRelacao($cd_pessoa, $cd_nivel_educacional, '2')) {
            if (!$this->ativarNivelEducacionalPessoa($cd_pessoa, $cd_nivel_educacional)) {
              $erros = true;
            }
          } else {
            if (!$this->registrarNivelEducacionalPessoa($cd_pessoa, $cd_nivel_educacional)) {
              $erros = true;
            }
          }
        }
      }
      return !$erros;
    }

    private function existeRelacao($cd_pessoa, $cd_nivel_educacional, $eh_ativo) {
      $dados = $this->selectDadosNivelEducacionalPessoa($cd_nivel_educacional, $cd_pessoa, $eh_ativo);
      if ($dados['cd_nivel_educacional_pessoa'] != '') {
        return true;
      } else {
        return false;
      }
    }


//**************BANCO DE DADOS**************************************************    
    public function selectNivelEducacionalPessoa($cd_nivel_educacional, $cd_pessoa, $eh_ativo) {
      $sql  = "SELECT acp.*, ac.nm_nivel_educacional ".
              "FROM life_niveis_educacionais_pessoa acp, life_niveis_educacionais ac ".
              "WHERE acp.cd_nivel_educacional = ac.cd_nivel_educacional ";
      if ($cd_pessoa != '') {
        $sql.= "AND acp.cd_pessoa = '$cd_pessoa' ";
      }
      if ($cd_nivel_educacional != '') {
        $sql.= "AND acp.cd_nivel_educacional = '$cd_nivel_educacional' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND acp.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ac.nm_nivel_educacional ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS PESSOA!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;
    }

    public function selectDadosNivelEducacionalPessoa($cd_nivel_educacional, $cd_pessoa, $eh_ativo) {
      $sql  = "SELECT acp.*, ac.nm_nivel_educacional ".
              "FROM life_niveis_educacionais_pessoa acp, life_niveis_educacionais ac ".
              "WHERE acp.cd_nivel_educacional = ac.cd_nivel_educacional ";
      if ($cd_pessoa != '') {
        $sql.= "AND acp.cd_pessoa = '$cd_pessoa' ";
      }
      if ($cd_nivel_educacional != '') {
        $sql.= "AND acp.cd_nivel_educacional = '$cd_nivel_educacional' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND acp.eh_ativo = '$eh_ativo' ";
      }
      $sql.= "ORDER BY ac.nm_nivel_educacional ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS PESSOA!");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;
    }



    private function registrarNivelEducacionalPessoa($cd_pessoa, $cd_nivel_educacional) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_niveis_educacionais_pessoa ".
             "(cd_nivel_educacional, cd_pessoa, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_nivel_educacional\", \"$cd_pessoa\", \"1\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'niveis_educacionais_pessoa');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS PESSOA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    private function inativarNivelEducacionalPessoa($cd_pessoa) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_niveis_educacionais_pessoa SET ".
             "eh_ativo = \"0\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_pessoa = '$cd_pessoa' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'niveis_educacionais_pessoa');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS PESSOA!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    

    private function ativarNivelEducacionalPessoa($cd_pessoa, $cd_nivel_educacional) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_niveis_educacionais_pessoa SET ".
             "eh_ativo = \"1\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_nivel_educacional= '$cd_nivel_educacional' ".
             "AND cd_pessoa = '$cd_pessoa' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'niveis_educacionais_pessoa');
      mysql_query($sql) or die ("Erro no banco de dados - TABELA NÍVEIS EDUCACIONAIS PESSOA!");
      $saida = mysql_affected_rows();
      return $saida;
    }
  }
?>