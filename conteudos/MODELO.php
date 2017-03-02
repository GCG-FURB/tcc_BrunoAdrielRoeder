<?php
  class ObjetoAprendizagemModelo {
    
    public function __construct () {
    }
    
    public function retornaFormularioCadastroEdicao($cd_modelo, $eh_informar_modelo, $eh_manter_configuracoes_originais) {
      if ($cd_modelo > 0) {
        $this->montarFormularioEdicao($cd_modelo, $eh_informar_modelo, $eh_manter_configuracoes_originais);
      } else {
        $this->montarFormularioCadastro($eh_informar_modelo, $eh_manter_configuracoes_originais);
      }
    }
     
    private function montarFormularioCadastro($eh_informar_modelo, $eh_manter_configuracoes_originais) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();


      $this->imprimeFormularioCadastro($eh_informar_modelo, $eh_manter_configuracoes_originais, );
    }
    
    private function montarFormularioEdicao($cd_modelo, $eh_informar_modelo, $eh_manter_configuracoes_originais) {
      require_once 'includes/configuracoes.php';                                $conf = new Configuracao();

      $dados = $this->selectDadosObjetoAprendizagemModelo($cd_modelo);

      if ($eh_manter_configuracoes_originais == '1') {
      } else {
      }
      
      $this->imprimeFormularioCadastro($eh_informar_modelo, $eh_manter_configuracoes_originais, );
    }
    
    public function imprimeFormularioCadastro($eh_informar_modelo, $eh_manter_configuracoes_originais, ) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();

      $util->campoHidden('cd_modelo', $cd_modelo);
      $util->campoHidden('eh_informar_modelo', $eh_informar_modelo);

      
      if ($eh_informar_modelo == '1') {
        if ($ == '1') {
          $util->linhaUmCampoTextHint(0, '', '', '250', '840', $, 1);
        } else {
          $util->campoHidden('', $);
        }

      }
    }

    public function salvarCadastroAlteracao() {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();


      if ($cd_modelo > 0) {
        return $this->alteraModelo($cd_modelo, );
      } else {
        return $this->insereModelo();
      }
    } 

//*********************EXIBICAO PUBLICA*****************************************

//**************BANCO DE DADOS**************************************************
    public function selectDadosObjetoAprendizagemModelo($cd_modelo) {
      $sql  = "SELECT * ".
              "FROM life_modelo ".
              "WHERE cd_modelo = '$cd_modelo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA MODELO!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }

    public function insereModelo() {
      $sql = "INSERT INTO life_modelo ".
             "() ".
             "VALUES ".
             "()";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'modelo');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA MODELO!");
      $saida = mysql_affected_rows();
      if ($saida > 0) {
        $sql  = "SELECT MAX(cd_modelo) codigo ".
                "FROM life_modelo ".
                "WHERE   ";
        $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA MODELO!");
        $dados= mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      } else {
        return 0;
      }     
    }

    public function alteraModelo($cd_modelo, ) {
      $sql = "UPDATE life_modelo SET ".
             "WHERE cd_modelo = '$cd_modelo' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'modelo');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA MODELO!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
                                                            
  }
?>