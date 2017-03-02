<?php
  class Contato {
    
    public function __construct () {
    }
    
    public function imprimeFormularioContatos($titulo, $cd_contato, $exibir_residencial=true) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      if ($cd_contato == '') {
        $nr_telefone_comercial_01 = "";
        $nr_telefone_comercial_02 = "";
        $nr_telefone_comercial_03 = "";
        $nr_telefone_residencial_01 = "";
        $nr_telefone_residencial_02 = "";
        $nr_telefone_residencial_03 = "";
        $nr_telefone_celular_01 = "";
        $nr_telefone_celular_02 = "";
        $nr_telefone_celular_03 = "";
        $ds_email_01 = "";
        $ds_email_02 = "";
        $ds_email_03 = "";
        $ds_skype = "";
        $ds_messenger = "";
        $ds_gtalk = "";
        $ds_link = "";            
      } else {
        $dados_con = $this->selectDadosContato($cd_contato);
        $nr_telefone_comercial_01 = $dados_con['nr_telefone_comercial_01'];
        $nr_telefone_comercial_02 = $dados_con['nr_telefone_comercial_02'];
        $nr_telefone_comercial_03 = $dados_con['nr_telefone_comercial_03'];
        $nr_telefone_residencial_01 = $dados_con['nr_telefone_residencial_01'];
        $nr_telefone_residencial_02 = $dados_con['nr_telefone_residencial_02'];
        $nr_telefone_residencial_03 = $dados_con['nr_telefone_residencial_03'];
        $nr_telefone_celular_01 = $dados_con['nr_telefone_celular_01'];
        $nr_telefone_celular_02 = $dados_con['nr_telefone_celular_02'];
        $nr_telefone_celular_03 = $dados_con['nr_telefone_celular_03'];
        $ds_email_01 = $dados_con['ds_email_01'];
        $ds_email_02 = $dados_con['ds_email_02'];
        $ds_email_03 = $dados_con['ds_email_03'];
        $ds_skype = $dados_con['ds_skype'];
        $ds_messenger = $dados_con['ds_messenger'];
        $ds_gtalk = $dados_con['ds_gtalk'];
        $ds_link = $dados_con['ds_link'];      

        if ($nr_telefone_comercial_01 == '') {          $nr_telefone_comercial_01 = '';          }
        if ($nr_telefone_comercial_02 == '') {          $nr_telefone_comercial_02 = '';          }
        if ($nr_telefone_comercial_03 == '') {          $nr_telefone_comercial_03 = '';          }
        if ($nr_telefone_residencial_01 == '') {        $nr_telefone_residencial_01 = '';        }
        if ($nr_telefone_residencial_02 == '') {        $nr_telefone_residencial_02 = '';        }
        if ($nr_telefone_residencial_03 == '') {        $nr_telefone_residencial_03 = '';        }
        if ($nr_telefone_celular_01 == '') {            $nr_telefone_celular_01 = '';            }
        if ($nr_telefone_celular_02 == '') {            $nr_telefone_celular_02 = '';            }
        if ($nr_telefone_celular_03 == '') {            $nr_telefone_celular_03 = '';            }
      }     

      include_once 'js/js_mascaras_validacoes.js';      
      $util->linhaComentario($titulo);
      $util->campoHidden('cd_contato', $cd_contato);
      $util->linhaTresCamposTextAcao(0, 'Telefone(s) Comercial(is): ', 'nr_telefone_comercial_01', 14, 15, $nr_telefone_comercial_01, " onKeyPress=\"mascaraTelefone('nr_telefone_comercial_01')\"; onBlur=\"validaTelefone('nr_telefone_comercial_01');\" ", 'nr_telefone_comercial_02', 14, 15, $nr_telefone_comercial_02, " onKeyPress=\"mascaraTelefone('nr_telefone_comercial_02')\"; onBlur=\"validaTelefone('nr_telefone_comercial_02');\" ", 'nr_telefone_comercial_03', 14, 15, $nr_telefone_comercial_03, " onKeyPress=\"mascaraTelefone('nr_telefone_comercial_03')\"; onBlur=\"validaTelefone('nr_telefone_comercial_03');\" ");
      if ($exibir_residencial) {
        $util->linhaTresCamposTextAcao(0, 'Telefone(s) Residencial(is): ', 'nr_telefone_residencial_01', 14, 15, $nr_telefone_residencial_01, " onKeyPress=\"mascaraTelefone('nr_telefone_residencial_01')\"; onBlur=\"validaTelefone('nr_telefone_residencial_01');\" ", 'nr_telefone_residencial_02', 14, 15, $nr_telefone_residencial_02, " onKeyPress=\"mascaraTelefone('nr_telefone_residencial_02')\"; onBlur=\"validaTelefone('nr_telefone_residencial_02');\" ", 'nr_telefone_residencial_03', 14, 15, $nr_telefone_residencial_03, " onKeyPress=\"mascaraTelefone('nr_telefone_residencial_03')\"; onBlur=\"validaTelefone('nr_telefone_residencial_03');\" ");
      } else {
        $util->campoHidden('nr_telefone_residencial_01', $nr_telefone_residencial_01);
        $util->campoHidden('nr_telefone_residencial_02', $nr_telefone_residencial_02);
        $util->campoHidden('nr_telefone_residencial_03', $nr_telefone_residencial_03);
      }
      $util->linhaTresCamposTextAcao(0, 'Telefone(s) Celular(es): ', 'nr_telefone_celular_01', 14, 15, $nr_telefone_celular_01, " onKeyPress=\"mascaraTelefone('nr_telefone_celular_01')\"; onBlur=\"validaTelefone('nr_telefone_celular_01');\" ", 'nr_telefone_celular_02', 14, 15, $nr_telefone_celular_02, " onKeyPress=\"mascaraTelefone('nr_telefone_celular_02')\"; onBlur=\"validaTelefone('nr_telefone_celular_02');\" ", 'nr_telefone_celular_03', 14, 15, $nr_telefone_celular_03, " onKeyPress=\"mascaraTelefone('nr_telefone_celular_03')\"; onBlur=\"validaTelefone('nr_telefone_celular_03');\" ");
    
      $util->linhaUmCampoText(0, 'E-mail 01: ', 'ds_email_01', 100, 70, $ds_email_01);
      $util->linhaUmCampoText(0, 'E-mail 02: ', 'ds_email_02', 100, 70, $ds_email_02);
      $util->linhaUmCampoText(0, 'E-mail 03: ', 'ds_email_03', 100, 70, $ds_email_03);
      $util->linhaUmCampoText(0, 'Skype: ', 'ds_skype', 100, 70, $ds_skype);
      $util->linhaUmCampoText(0, 'MSN: ', 'ds_messenger', 100, 70, $ds_messenger);
      $util->linhaUmCampoText(0, 'GTalk: ', 'ds_gtalk', 100, 70, $ds_gtalk);
      $util->linhaUmCampoText(0, 'Site: ', 'ds_link', 100, 70, $ds_link);      
    }

    public function salvarCadastroEdicao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $cd_contato = addslashes($_POST['cd_contato']);
      $nr_telefone_comercial_01 = $util->limparVariavel($_POST['nr_telefone_comercial_01']);
      $nr_telefone_comercial_02 = $util->limparVariavel($_POST['nr_telefone_comercial_02']);
      $nr_telefone_comercial_03 = $util->limparVariavel($_POST['nr_telefone_comercial_03']);
      $nr_telefone_residencial_01 = $util->limparVariavel($_POST['nr_telefone_residencial_01']);
      $nr_telefone_residencial_02 = $util->limparVariavel($_POST['nr_telefone_residencial_02']);
      $nr_telefone_residencial_03 = $util->limparVariavel($_POST['nr_telefone_residencial_03']);
      $nr_telefone_celular_01 = $util->limparVariavel($_POST['nr_telefone_celular_01']);
      $nr_telefone_celular_02 = $util->limparVariavel($_POST['nr_telefone_celular_02']);
      $nr_telefone_celular_03 = $util->limparVariavel($_POST['nr_telefone_celular_03']);

      if ($nr_telefone_comercial_01 == '') {          $nr_telefone_comercial_01 = '';          }
      if ($nr_telefone_comercial_02 == '') {          $nr_telefone_comercial_02 = '';          }
      if ($nr_telefone_comercial_03 == '') {          $nr_telefone_comercial_03 = '';          }
      if ($nr_telefone_residencial_01 == '') {        $nr_telefone_residencial_01 = '';        }
      if ($nr_telefone_residencial_02 == '') {        $nr_telefone_residencial_02 = '';        }
      if ($nr_telefone_residencial_03 == '') {        $nr_telefone_residencial_03 = '';        }
      if ($nr_telefone_celular_01 == '') {            $nr_telefone_celular_01 = '';            }
      if ($nr_telefone_celular_02 == '') {            $nr_telefone_celular_02 = '';            }
      if ($nr_telefone_celular_03 == '') {            $nr_telefone_celular_03 = '';            }

      $ds_email_01 = $util->limparVariavel($_POST['ds_email_01']);
      $ds_email_02 = $util->limparVariavel($_POST['ds_email_02']);
      $ds_email_03 = $util->limparVariavel($_POST['ds_email_03']);
      $ds_skype = $util->limparVariavel($_POST['ds_skype']);
      $ds_messenger = $util->limparVariavel($_POST['ds_messenger']);
      $ds_gtalk = $util->limparVariavel($_POST['ds_gtalk']);
      $ds_link = $util->limparVariavel($_POST['ds_link']);

      if ($cd_contato != '') {
        if ($this->alteraContato($cd_contato, $nr_telefone_comercial_01, $nr_telefone_comercial_02, $nr_telefone_comercial_03, $nr_telefone_residencial_01, $nr_telefone_residencial_02, $nr_telefone_residencial_03, $nr_telefone_celular_01, $nr_telefone_celular_02, $nr_telefone_celular_03, $ds_email_01, $ds_email_02, $ds_email_03, $ds_skype, $ds_messenger, $ds_gtalk, $ds_link)) {
          return $cd_contato;
        } else {
          return 0;
        }      
      } else {
        return $this->insereContato($nr_telefone_comercial_01, $nr_telefone_comercial_02, $nr_telefone_comercial_03, $nr_telefone_residencial_01, $nr_telefone_residencial_02, $nr_telefone_residencial_03, $nr_telefone_celular_01, $nr_telefone_celular_02, $nr_telefone_celular_03, $ds_email_01, $ds_email_02, $ds_email_03, $ds_skype, $ds_messenger, $ds_gtalk, $ds_link);      
      }    
    }



    public function detalharContato($cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      if (($dados_con['nr_telefone_comercial_01'] != '') || ($dados_con['nr_telefone_comercial_02'] != '') || ($dados_con['nr_telefone_comercial_03'] != '')) {
        $retorno.= "Telefone(s) Comercial(is): \n";
        if ($dados_con['nr_telefone_comercial_01'] != '') {        $retorno.= $dados_con['nr_telefone_comercial_01']."\n";            } 
        if ($dados_con['nr_telefone_comercial_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_comercial_02']."\n";      } 
        if ($dados_con['nr_telefone_comercial_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_comercial_03']."\n";      } 
        $retorno.= "<br />\n";
      }
      if (($dados_con['nr_telefone_residencial_01'] != '') || ($dados_con['nr_telefone_residencial_02'] != '') || ($dados_con['nr_telefone_residencial_03'] != '')) {
        $retorno.= "Telefone(s) Residencial(is): \n";
        if ($dados_con['nr_telefone_residencial_01'] != '') {        $retorno.= $dados_con['nr_telefone_residencial_01']."\n";            } 
        if ($dados_con['nr_telefone_residencial_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_residencial_02']."\n";      } 
        if ($dados_con['nr_telefone_residencial_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_residencial_03']."\n";      } 
        $retorno.= "<br />\n";
      }
      if (($dados_con['nr_telefone_celular_01'] != '') || ($dados_con['nr_telefone_celular_02'] != '') || ($dados_con['nr_telefone_celular_03'] != '')) {
        $retorno.= "Telefone(s) Celular(es): \n";
        if ($dados_con['nr_telefone_celular_01'] != '') {        $retorno.= $dados_con['nr_telefone_celular_01']."\n";            } 
        if ($dados_con['nr_telefone_celular_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_celular_02']."\n";      } 
        if ($dados_con['nr_telefone_celular_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_celular_03']."\n";      } 
        $retorno.= "<br />\n";
      }
      if (($dados_con['ds_email_01'] != '') || ($dados_con['ds_email_02'] != '') || ($dados_con['ds_email_03'] != '')) {
        $retorno.= "E-mail(s): \n";
        if ($dados_con['ds_email_01'] != '') {        $retorno.= $dados_con['ds_email_01']."\n";            } 
        if ($dados_con['ds_email_02'] != '') {        $retorno.= " - ".$dados_con['ds_email_02']."\n";      } 
        if ($dados_con['ds_email_03'] != '') {        $retorno.= " - ".$dados_con['ds_email_03']."\n";      } 
        $retorno.= "<br />\n";
      }
      if ($dados_con['ds_skype'] != '')     {        $retorno.= "Skype: ".$dados_con['ds_skype']."<br />\n";        } 
      if ($dados_con['ds_messenger'] != '') {        $retorno.= "MSN: ".$dados_con['ds_messenger']."<br />\n";      } 
      if ($dados_con['ds_gtalk'] != '')     {        $retorno.= "GTalk: ".$dados_con['ds_gtalk']."<br />\n";        }
      if ($dados_con['ds_link'] != '')      {        $retorno.= "Link: ".$dados_con['ds_link']."<br />\n";          } 
      return $retorno;
    }
    
    public function detalharContatoTelefones($comerciais, $residenciais, $celulares, $cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      if ($comerciais && (($dados_con['nr_telefone_comercial_01'] != '') || ($dados_con['nr_telefone_comercial_02'] != '') || ($dados_con['nr_telefone_comercial_03'] != ''))) {
        $retorno.= "<br />\n";
        $retorno.= "Telefone(s) Comercial(is): \n";
        if ($dados_con['nr_telefone_comercial_01'] != '') {        $retorno.= $dados_con['nr_telefone_comercial_01']."\n";            } 
        if ($dados_con['nr_telefone_comercial_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_comercial_02']."\n";      } 
        if ($dados_con['nr_telefone_comercial_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_comercial_03']."\n";      }
      } 

      if ($residenciais && (($dados_con['nr_telefone_residencial_01'] != '') || ($dados_con['nr_telefone_residencial_02'] != '') || ($dados_con['nr_telefone_residencial_03'] != ''))) {
        $retorno.= "<br />\n";
        $retorno.= "Telefone(s) Residencial(is): \n";
        if ($dados_con['nr_telefone_residencial_01'] != '') {        $retorno.= $dados_con['nr_telefone_residencial_01']."\n";            } 
        if ($dados_con['nr_telefone_residencial_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_residencial_02']."\n";      } 
        if ($dados_con['nr_telefone_residencial_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_residencial_03']."\n";      } 
      }

      if ($celulares && (($dados_con['nr_telefone_celular_01'] != '') || ($dados_con['nr_telefone_celular_02'] != '') || ($dados_con['nr_telefone_celular_03'] != ''))) {
        $retorno.= "<br />\n";
        $retorno.= "Telefone(s) Celular(es): \n";
        if ($dados_con['nr_telefone_celular_01'] != '') {        $retorno.= $dados_con['nr_telefone_celular_01']."\n";            } 
        if ($dados_con['nr_telefone_celular_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_celular_02']."\n";      } 
        if ($dados_con['nr_telefone_celular_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_celular_03']."\n";      }
      } 
      return $retorno;
    }    
    
    public function detalharContatoEmails($cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      $retorno.= "<br />\n";
      $retorno.= "E-mail(s): \n";
      if ($dados_con['ds_email_01'] != '') {        $retorno.= "<a href=\"mailto:".$dados_con['ds_email_01']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_01']."</a>\n";         } 
      if ($dados_con['ds_email_02'] != '') {        $retorno.= " - <a href=\"mailto:".$dados_con['ds_email_02']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_02']."</a>\n";      } 
      if ($dados_con['ds_email_03'] != '') {        $retorno.= " - <a href=\"mailto:".$dados_con['ds_email_03']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_03']."</a>\n";      } 
      return $retorno;
    }    

    public function retornaContatosTelefonePaginaInicial($cd_contato) {            
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";
      if (($dados_con['nr_telefone_comercial_01'] != '') || ($dados_con['nr_telefone_comercial_02'] != '') || ($dados_con['nr_telefone_comercial_03'] != '')) {
        if ($dados_con['nr_telefone_comercial_01'] != '') {        $retorno.= $dados_con['nr_telefone_comercial_01']."\n";            } 
        if ($dados_con['nr_telefone_comercial_02'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_comercial_02']."\n";      } 
        if ($dados_con['nr_telefone_comercial_03'] != '') {        $retorno.= " - ".$dados_con['nr_telefone_comercial_03']."\n";      }
      } 
      return $retorno;
    }    

    public function retornaContatosEmailPaginaInicial($cd_contato) {
      $dados_con = $this->selectDadosContato($cd_contato);
 
      $retorno = "";

      if ($dados_con['ds_email_01'] != '') {        $retorno.= "<a href=\"mailto:".$dados_con['ds_email_01']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_01']."</a>\n";         } 
      if ($dados_con['ds_email_02'] != '') {        $retorno.= " - <a href=\"mailto:".$dados_con['ds_email_02']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_02']."</a>\n";      } 
      if ($dados_con['ds_email_03'] != '') {        $retorno.= " - <a href=\"mailto:".$dados_con['ds_email_03']."\" class=\"fontLink\" target=\"_blank\">".$dados_con['ds_email_03']."</a>\n";      } 
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
    

    public function insereContato($nr_telefone_comercial_01, $nr_telefone_comercial_02, $nr_telefone_comercial_03, $nr_telefone_residencial_01, $nr_telefone_residencial_02, $nr_telefone_residencial_03, $nr_telefone_celular_01, $nr_telefone_celular_02, $nr_telefone_celular_03, $ds_email_01, $ds_email_02, $ds_email_03, $ds_skype, $ds_messenger, $ds_gtalk, $ds_link) {
      $sql = "INSERT INTO life_contatos ".
             "(nr_telefone_comercial_01, nr_telefone_comercial_02, nr_telefone_comercial_03, nr_telefone_residencial_01, nr_telefone_residencial_02, nr_telefone_residencial_03, nr_telefone_celular_01, nr_telefone_celular_02, nr_telefone_celular_03, ds_email_01, ds_email_02, ds_email_03, ds_skype, ds_messenger, ds_gtalk, ds_link) ".
             "VALUES ".
             "(\"$nr_telefone_comercial_01\", \"$nr_telefone_comercial_02\", \"$nr_telefone_comercial_03\", \"$nr_telefone_residencial_01\", \"$nr_telefone_residencial_02\", \"$nr_telefone_residencial_03\", \"$nr_telefone_celular_01\", \"$nr_telefone_celular_02\", \"$nr_telefone_celular_03\", \"$ds_email_01\", \"$ds_email_02\", \"$ds_email_03\", \"$ds_skype\", \"$ds_messenger\", \"$ds_gtalk\", \"$ds_link\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'contatos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
      $saida = mysql_affected_rows();
      if ($saida) {
        $sql= "SELECT MAX(cd_contato) codigo ".
              "FROM life_contatos ".
              "WHERE nr_telefone_comercial_01 = '$nr_telefone_comercial_01' ".
              "AND nr_telefone_comercial_02 = '$nr_telefone_comercial_02' ".
              "AND nr_telefone_comercial_03 = '$nr_telefone_comercial_03' ".
              "AND nr_telefone_residencial_01 = '$nr_telefone_residencial_01' ".
              "AND nr_telefone_residencial_02 = '$nr_telefone_residencial_02' ".
              "AND nr_telefone_residencial_03 = '$nr_telefone_residencial_03' ".
              "AND nr_telefone_celular_01 = '$nr_telefone_celular_01' ".
              "AND nr_telefone_celular_02 = '$nr_telefone_celular_02' ".
              "AND nr_telefone_celular_03 = '$nr_telefone_celular_03' ";
        $result_id = mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      }
      else {
        return 0;
      }      
      
      
      return $saida;     
    }

    public function alteraContato($cd_contato, $nr_telefone_comercial_01, $nr_telefone_comercial_02, $nr_telefone_comercial_03, $nr_telefone_residencial_01, $nr_telefone_residencial_02, $nr_telefone_residencial_03, $nr_telefone_celular_01, $nr_telefone_celular_02, $nr_telefone_celular_03, $ds_email_01, $ds_email_02, $ds_email_03, $ds_skype, $ds_messenger, $ds_gtalk, $ds_link) {
      $sql = "UPDATE life_contatos SET ".
             "nr_telefone_comercial_01 = \"$nr_telefone_comercial_01\", ".
             "nr_telefone_comercial_02 = \"$nr_telefone_comercial_02\", ".
             "nr_telefone_comercial_03 = \"$nr_telefone_comercial_03\", ".
             "nr_telefone_residencial_01 = \"$nr_telefone_residencial_01\", ".
             "nr_telefone_residencial_02 = \"$nr_telefone_residencial_02\", ".
             "nr_telefone_residencial_03 = \"$nr_telefone_residencial_03\", ".
             "nr_telefone_celular_01 = \"$nr_telefone_celular_01\", ".
             "nr_telefone_celular_02 = \"$nr_telefone_celular_02\", ".
             "nr_telefone_celular_03 = \"$nr_telefone_celular_03\", ".
             "ds_email_01 = \"$ds_email_01\", ".
             "ds_email_02 = \"$ds_email_02\", ".
             "ds_email_03 = \"$ds_email_03\", ".
             "ds_skype = \"$ds_skype\", ".
             "ds_messenger = \"$ds_messenger\", ".
             "ds_link = \"$ds_link\", ".
             "ds_gtalk = \"$ds_gtalk\" ".
             "WHERE cd_contato = '$cd_contato'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'contatos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA CONTATOS!");
      $saida = mysql_affected_rows();
      return $saida;     
    }    
    
    
  }
?>