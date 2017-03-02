<?php
  class Contato {
    
    public function __construct () {
    }
    
    public function imprimeFormularioContatos($titulo, $cd_contato, $exibir_residencial=true) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      if ($cd_contato == '') {
        $nr_telefone_comercial = "";
        $nr_telefone_residencial = "";
        $nr_telefone_celular = "";
        $ds_email_01 = "";
        $ds_email_02 = "";
        $ds_link = "";
      } else {
        $dados_con = $this->selectDadosContato($cd_contato);
        $nr_telefone_comercial = $dados_con['nr_telefone_comercial'];
        $nr_telefone_residencial = $dados_con['nr_telefone_residencial'];
        $nr_telefone_celular = $dados_con['nr_telefone_celular'];
        $ds_email_01 = $dados_con['ds_email_01'];
        $ds_email_02 = $dados_con['ds_email_02'];
        $ds_link = $dados_con['ds_link'];

        if ($nr_telefone_comercial == '') {          $nr_telefone_comercial = '';          }
        if ($nr_telefone_residencial == '') {        $nr_telefone_residencial = '';        }
        if ($nr_telefone_celular == '') {            $nr_telefone_celular = '';            }
      }

      include_once 'js/js_mascaras_validacoes.js';      
      $util->linhaComentario($titulo);
      $util->campoHidden('cd_contato', $cd_contato);
      $util->linhaUmCampoTextAcao(0, 'Telefone comercial: ', 'nr_telefone_comercial', 14, 100, $nr_telefone_comercial, " onKeyPress=\"mascaraTelefone('nr_telefone_comercial')\"; onBlur=\"validaTelefone('nr_telefone_comercial');\" ");
      if ($exibir_residencial) {
        $util->linhaUmCampoTextAcao(0, 'Telefone residencial: ', 'nr_telefone_residencial', 14, 100, $nr_telefone_residencial, " onKeyPress=\"mascaraTelefone('nr_telefone_residencial')\"; onBlur=\"validaTelefone('nr_telefone_residencial');\" ");
      } else {
        $util->campoHidden('nr_telefone_residencial', $nr_telefone_residencial);
      }
      $util->linhaUmCampoTextAcao(0, 'Telefone celular: ', 'nr_telefone_celular', 14, 100, $nr_telefone_celular, " onKeyPress=\"mascaraTelefone('nr_telefone_celular')\"; onBlur=\"validaTelefone('nr_telefone_celular');\" ");
      $util->linhaComentario('&nbsp;');
      $util->linhaUmCampoText(1, 'E-mail 01: ', 'ds_email_01', 100, 100, $ds_email_01);
      $util->linhaUmCampoText(0, 'E-mail 02: ', 'ds_email_02', 100, 100, $ds_email_02);
      $util->linhaUmCampoText(0, 'Site: ', 'ds_link', 100, 100, $ds_link);
    }

    public function salvarCadastroEdicao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $cd_contato = addslashes($_POST['cd_contato']);
      $nr_telefone_comercial = $util->limparVariavel($_POST['nr_telefone_comercial']);
      $nr_telefone_residencial = $util->limparVariavel($_POST['nr_telefone_residencial']);
      $nr_telefone_celular = $util->limparVariavel($_POST['nr_telefone_celular']);

      if ($nr_telefone_comercial == '') {          $nr_telefone_comercial = '';          }
      if ($nr_telefone_residencial == '') {        $nr_telefone_residencial = '';        }
      if ($nr_telefone_celular == '') {            $nr_telefone_celular = '';            }

      $ds_email_01 = $util->limparVariavel($_POST['ds_email_01']);
      $ds_email_02 = $util->limparVariavel($_POST['ds_email_02']);
      $ds_link = $util->limparVariavel($_POST['ds_link']);

      if ($cd_contato != '') {
        if ($this->alteraContato($cd_contato, $nr_telefone_comercial, $nr_telefone_residencial, $nr_telefone_celular, $ds_email_01, $ds_email_02, $ds_link)) {
          return $cd_contato;
        } else {
          return 0;
        }      
      } else {
        return $this->insereContato($nr_telefone_comercial, $nr_telefone_residencial, $nr_telefone_celular, $ds_email_01, $ds_email_02, $ds_link);
      }    
    }



    public function detalharContato($cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      if ($dados_con['nr_telefone_comercial'] != '')    {        $retorno.= "Telefone comercial: ".$dados_con['nr_telefone_comercial']."<br />\n";          }
      if ($dados_con['nr_telefone_residencial'] != '')  {        $retorno.= "Telefone residencial: ".$dados_con['nr_telefone_residencial']."<br />\n";      }
      if ($dados_con['nr_telefone_celular'] != '')      {        $retorno.= "Telefone celular: ".$dados_con['nr_telefone_celular']."<br />\n";              }

      if (($dados_con['ds_email_01'] != '') || ($dados_con['ds_email_02'] != '')) {
        $retorno.= "E-mail(s): \n";
        if ($dados_con['ds_email_01'] != '') {        $retorno.= $dados_con['ds_email_01']."\n";            } 
        if ($dados_con['ds_email_02'] != '') {        $retorno.= " - ".$dados_con['ds_email_02']."\n";      } 
        $retorno.= "<br />\n";
      }
      if ($dados_con['ds_link'] != '')      {        $retorno.= "Link: ".$dados_con['ds_link']."<br />\n";          }
      return $retorno;
    }
    
    public function detalharContatoTelefones($comerciais, $residenciais, $celulares, $cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      if ($comerciais &&($dados_con['nr_telefone_comercial'] != ''))       {        $retorno.= "Telefone comercial: ".$dados_con['nr_telefone_comercial']."<br />\n";          }
      if ($residenciais && ($dados_con['nr_telefone_residencial'] != ''))  {        $retorno.= "Telefone residencial: ".$dados_con['nr_telefone_residencial']."<br />\n";      }
      if ($celulares && ($dados_con['nr_telefone_celular'] != ''))         {        $retorno.= "Telefone celular: ".$dados_con['nr_telefone_celular']."<br />\n";              }
      return $retorno;
    }    
    
    public function detalharContatoEmails($cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      $retorno.= "<br />\n";
      $retorno.= "E-mail(s): \n";
      if ($dados_con['ds_email_01'] != '') {        $retorno.= "<a href=\"mailto:".$dados_con['ds_email_01']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_01']."</a>\n";         } 
      if ($dados_con['ds_email_02'] != '') {        $retorno.= " - <a href=\"mailto:".$dados_con['ds_email_02']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_02']."</a>\n";      } 
      return $retorno;
    }    

    public function retornaContatosTelefonePaginaInicial($cd_contato) {            
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";
      if ($dados_con['nr_telefone_comercial'] != '')    {        $retorno.= $dados_con['nr_telefone_comercial'];          }
      return $retorno;
    }    

    public function retornaContatosEmailPaginaInicial($cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      if ($dados_con['ds_email_01'] != '') {        $retorno.= "<a href=\"mailto:".$dados_con['ds_email_01']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_01']."</a>\n";         } 
      if ($dados_con['ds_email_02'] != '') {        $retorno.= " - <a href=\"mailto:".$dados_con['ds_email_02']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_02']."</a>\n";      } 
      return $retorno;
    }    


//**************BANCO DE DADOS**************************************************    
    public function selectDadosContato($cd_contato) {
      $sql  = "SELECT *  ".
              "FROM life_contatos ".
              "WHERE cd_contato = '$cd_contato' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
      $dados = mysql_fetch_assoc($result_id);
      return $dados;        
    }    
    

    public function insereContato($nr_telefone_comercial, $nr_telefone_residencial, $nr_telefone_celular, $ds_email_01, $ds_email_02, $ds_link) {
      $sql = "INSERT INTO life_contatos ".
             "(nr_telefone_comercial, nr_telefone_residencial, nr_telefone_celular, ds_email_01, ds_email_02, ds_link) ".
             "VALUES ".
             "(\"$nr_telefone_comercial\", \"$nr_telefone_residencial\", \"$nr_telefone_celular\", \"$ds_email_01\", \"$ds_email_02\", \"$ds_link\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'contatos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
      $saida = mysql_affected_rows();
      if ($saida) {
        $sql= "SELECT MAX(cd_contato) codigo ".
              "FROM life_contatos ".
              "WHERE nr_telefone_comercial = '$nr_telefone_comercial' ".
              "AND nr_telefone_residencial = '$nr_telefone_residencial' ".
              "AND nr_telefone_celular = '$nr_telefone_celular' ";
        $result_id = mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      }
      else {
        return 0;
      }      
      
      
      return $saida;     
    }

    public function alteraContato($cd_contato, $nr_telefone_comercial, $nr_telefone_residencial, $nr_telefone_celular, $ds_email_01, $ds_email_02, $ds_link) {
      $sql = "UPDATE life_contatos SET ".
             "nr_telefone_comercial = \"$nr_telefone_comercial\", ".
             "nr_telefone_residencial = \"$nr_telefone_residencial\", ".
             "nr_telefone_celular = \"$nr_telefone_celular\", ".
             "ds_email_01 = \"$ds_email_01\", ".
             "ds_email_02 = \"$ds_email_02\", ".
             "ds_link = \"$ds_link\" ".
             "WHERE cd_contato = '$cd_contato'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'contatos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
    
    
  }
?>