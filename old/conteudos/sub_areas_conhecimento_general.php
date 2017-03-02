<?php
  class SubAreaConhecimentoGeneral {
    
    public function __construct () {
    }

    public function salvarSubAreasConhecimentoGeneral($cd_general, $cd_area_conhecimento) {
      require_once 'conteudos/sub_areas_conhecimento.php';                      $sac = new SubAreaConhecimento();

      $erros = false;

      $variavel = "cd_sub_area_conhecimento_".$cd_area_conhecimento;
      if (isset($_POST[$variavel])) {
        $sub_areas_conhecimento = $_POST[$variavel];
        foreach ($sub_areas_conhecimento as $cd_sub_area_conhecimento) {
          $cd_sub_area_conhecimento = addslashes($cd_sub_area_conhecimento);
          if ($this->existeRelacao($cd_sub_area_conhecimento, $cd_general, '2')) {
            if (!$this->alteraStatusSubAreaConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, '1')) {
              $erros = true;
            }
          } else {
            if (!$this->insereSubAreaConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, '1')) {
              $erros = true;
            }
          }
        }
      }

      $variavel = "ds_sub_area_conhecimento_".$cd_area_conhecimento;
      $ds_sub_area_conhecimento = addslashes($_POST[$variavel]);
      if ($ds_sub_area_conhecimento != '') {
        $cd_sub_area_conhecimento = $sac->estaCadastrado($ds_sub_area_conhecimento, $cd_area_conhecimento);
        if ($cd_sub_area_conhecimento > 0) {
          if ($this->existeRelacao($cd_sub_area_conhecimento, $cd_general, '0')) {
            if (!$this->alteraStatusSubAreaConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, '1')) {
              $erros = true;
            }
          } elseif ($this->existeRelacao($cd_sub_area_conhecimento, $cd_general, '1')) {
            //se acao
          } else {
            if (!$this->insereSubAreaConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, '1')) {
              $erros = true;
            }
          }
        } else {
          $erros = true;
        }
      }
      
      return !$erros;
    }
    
    private function existeRelacao($cd_sub_area_conhecimento, $cd_general, $eh_ativo) {
      $dados = $this->selectSubAreasConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, $eh_ativo);
      if (count($dados) > 0) {
        return true;
      } else {
        return false;
      }    
    }
    
    public function retornaDados($cd_area_conhecimento, $cd_general) {
      $itens = $this->selectSubAreasConhecimentoGeneralAreasConhecimento($cd_area_conhecimento, $cd_general, '1');
    
      if (count($itens) > 0) {
        $retorno = ' - ';
        if (count($itens) == 1) {
          $it = $itens[0];
          $retorno.= $it['nm_sub_area_conhecimento'];        
        } else {
          $i = 1;
          foreach ($itens as $it) {
            $retorno.= $it['nm_sub_area_conhecimento'];
            if ($i < (count($itens)-1)) {
              $retorno.= ', ';
            } elseif ($i < count($itens)) {
              $retorno.= ' e ';
            }
          }
        } 
        return $retorno;
      } else {
        return '';
      }                 
    }

    public function retornaRelacaoSubAreasConhecimentoGeneralAreasConhecimento($cd_general) {
      $itens = $this->selectSubAreasConhecimentoGeneralAreasConhecimento('', $cd_general, '1');

      $retorno = '';
      foreach ($itens as $it) {
        $retorno.= $it['nm_sub_area_conhecimento']." | ";
      }
      return $retorno;
    }
                                                                
//**************BANCO DE DADOS**************************************************    
    public function selectSubAreasConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, $eh_ativo) {
      $sql  = "SELECT * ".
              "FROM life_sub_areas_conhecimento_general ".
              "WHERE cd_sub_area_conhecimento_general > '0' ";
      if ($cd_sub_area_conhecimento != '') {
        $sql.= "AND cd_sub_area_conhecimento = '$cd_sub_area_conhecimento' ";
      }
      if ($cd_general != '') {
        $sql.= "AND cd_general = '$cd_general' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND eh_ativo = '$eh_ativo' ";
      }
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO GENERAL!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    } 
    
    public function selectSubAreasConhecimentoGeneralAreasConhecimento($cd_area_conhecimento, $cd_general, $eh_ativo) {
      $sql  = "SELECT sg.*, s.*, a.* ".
              "FROM life_sub_areas_conhecimento_general sg, life_sub_areas_conhecimento s,  life_areas_conhecimento a ".
              "WHERE sg.cd_sub_area_conhecimento = s.cd_sub_area_conhecimento ".
              "AND s.cd_area_conhecimento = a.cd_area_conhecimento ";
      if ($cd_area_conhecimento != '') {
        $sql.= "AND a.cd_area_conhecimento = '$cd_area_conhecimento' ";
      }
      if ($cd_general != '') {
        $sql.= "AND sg.cd_general = '$cd_general' ";
      }
      if ($eh_ativo != 2) {
        $sql.= "AND sg.eh_ativo = '$eh_ativo' ";
      }
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO GENERAL!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;        
    }          

    public function insereSubAreaConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO life_sub_areas_conhecimento_general ".
             "(cd_sub_area_conhecimento, cd_general, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$cd_sub_area_conhecimento\", \"$cd_general\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento_general');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO GENERAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

    public function transfereSubAreaConhecimento($cd_sub_area_conhecimento, $cd_sub_area_conhecimento_destino) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_sub_areas_conhecimento_general SET ".
             "cd_sub_area_conhecimento = \"$cd_sub_area_conhecimento_destino\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".
             "WHERE cd_sub_area_conhecimento = '$cd_sub_area_conhecimento' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento_general');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO GENERAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    
    }

    public function alteraStatusSubAreaConhecimentoGeneral($cd_sub_area_conhecimento, $cd_general, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE life_sub_areas_conhecimento_general SET ".
             "eh_ativo = \"$eh_ativo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ";
      if (($cd_sub_area_conhecimento != '') && ($cd_general != '')) {
        $sql.= "WHERE cd_sub_area_conhecimento = '$cd_sub_area_conhecimento' ".
               "AND cd_general = '$cd_general' ";
      } elseif ($cd_sub_area_conhecimento != '') {
        $sql.= "WHERE cd_sub_area_conhecimento = '$cd_sub_area_conhecimento' ";
      } elseif ($cd_general != '') {
        $sql.= "WHERE cd_general = '$cd_general' ";
      } else { 
        $sql.= "WHERE cd_sub_area_conhecimento_general = '0' ";
      }
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'sub_areas_conhecimento_general');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA SUB ÁREAS CONHECIMENTO GENERAL!");
      $saida = mysql_affected_rows();
      return $saida;     
    }   
  }
?>