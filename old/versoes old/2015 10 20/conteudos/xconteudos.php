<?php
  class Conteudo {
    
    public function __construct () {
    }
    
    public function detalharConteudo($cd_conteudo) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
      
      $dados = $this->selectDadosConteudo($cd_conteudo);
      
      $retorno = "";
      $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_cadastro']);
      $retorno.= "Cadastrado por: ".$dados_usuario['nm_usuario']."<br />\n";
      $retorno.= "Data do Cadastro: ".$dh->imprimirData($dados['dt_cadastro'])."<br />\n";
      if ($dados['cd_usuario_ultima_atualizacao'] != '') {
        $dados_usuario = $usu->selectDadosUsuario($dados['cd_usuario_ultima_atualizacao']);
        $retorno.= "Última Atualização por: ".$dados_usuario['nm_usuario']."<br />\n";
        $retorno.= "Data do Última Atualização: ".$dh->imprimirData($dados['dt_ultima_atualizacao'])."\n";
      }
      return $retorno;
    }

    
    public function retornaCamposConteudo($tt_conteudo, $ds_conteudo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
    
      $util->linhaUmCampoText(1, 'Título: ', 'tt_conteudo', 100, 100, $tt_conteudo);
      $util->linhaTexto(1, 'Conteúdo: ', 'ds_conteudo', $ds_conteudo, '15', '965');    
    }             


    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $cd_conteudo = addslashes($_POST['cd_conteudo']);
      $tt_conteudo = $util->limparVariavel($_POST['tt_conteudo']);
      $ds_conteudo = $util->limparVariavel($_POST['ds_conteudo']);
      $eh_ativo = addslashes($_POST['eh_conteudo_ativo']);

      $lk_seo = $util->retornaLinkSEO($tt_conteudo, 'c_o_conteudos', 'lk_seo', '150', $cd_conteudo);

      if ($cd_conteudo > 0) {
        if ($this->alterarConteudo($cd_conteudo, $tt_conteudo, $ds_conteudo, $eh_ativo, $lk_seo)) {
          return $cd_conteudo;   
        } else {
          return 0;
        }        
      } else {
        $cd_conteudo = $this->inserirConteudo($tt_conteudo, $ds_conteudo, $eh_ativo, $lk_seo);
        if ($cd_conteudo > 0) {
          return $cd_conteudo;   
        } else {
          return 0;
        }
      }
    }              
                    
//**************BANCO DE DADOS**************************************************    

    public function selectDadosConteudo($cd_conteudo) {
      $sql  = "SELECT * ".
              "FROM c_o_conteudos ".
              "WHERE cd_conteudo = '$cd_conteudo' "; 
      $result_id = @mysql_query($sql) or die ("CONTEÚDOS - Erro no banco de dados!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
    
    public function inserirConteudo($tt_conteudo, $ds_conteudo, $eh_ativo, $lk_seo) {
      $cd_usuario_cadastro = $_SESSION['c_o_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql = "INSERT INTO c_o_conteudos ".
             "(tt_conteudo, ds_conteudo, eh_ativo, lk_seo, cd_usuario_cadastro, dt_cadastro) ".
             "VALUES ".
             "(\"$tt_conteudo\", \"$ds_conteudo\", \"$eh_ativo\", \"$lk_seo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'conteudos');            
      mysql_query($sql) or die ("CONTEÚDOS - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_conteudo) codigo ".
                "FROM c_o_conteudos ".
                "WHERE lk_seo = '$lk_seo' ". 
                "AND tt_conteudo = '$tt_conteudo' ";               
        $result_id = @mysql_query($sql) or die ("CONTEÚDOS - Erro no banco de dados!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];        
      } else {
        return 0;
      }      
    }
    
    public function alterarConteudo($cd_conteudo, $tt_conteudo, $ds_conteudo, $eh_ativo, $lk_seo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['c_o_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql = "UPDATE c_o_conteudos SET ".
             "tt_conteudo = \"$tt_conteudo\", ".
             "ds_conteudo = \"$ds_conteudo\", ".
             "eh_ativo = \"$eh_ativo\", ".
             "lk_seo = \"$lk_seo\", ".
             "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
             "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
             "WHERE cd_conteudo= '$cd_conteudo' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'conteudos');            
      mysql_query($sql) or die ("CONTEÚDOS - Erro no banco de dados!");
      $saida = mysql_affected_rows();
      return $saida;     
    }

  }
?>