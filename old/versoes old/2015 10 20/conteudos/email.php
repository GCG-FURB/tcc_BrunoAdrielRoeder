<?php
  class Email {
    
    public function __construct() {
    }
/*
    public function notificarCadastroAluno($nome, $username, $email) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();

      $nome_site = $conf->retornaNomeSite();
      $titulo = ".:. [".$nome_site."] - Cadastro .:.";
        
      $origem = $conf->retornaEmailOrigemRespostasMensagensAlteracaoSenha();

      $headers = "From:".$origem." <".$origem." >\n";
      $headers.= "X-Mailer: PHP\n";
      $headers.= "X-Priority: 3\n";
      $headers.= "MIME-Version: 1.0\n"; 
      $headers.= "Content-type: text/html; charset=iso-8859-1\n"; 
      $headers.= "Return-Path: <".$origem." >\n";

      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $texto      = "           Prezado(a) ".$nome."<br /><br />".
                    "           Seja bem vindo(a) ao ".$nome_site."!<br /><br />".
                    "           Seu cadastro foi realizado com sucesso e você deve efetuar login utilizando o nome de usuário é ".$username.".<br /><br />".
                    "           Naveque pelo site e inscreva-se nos cursos de seu interesse.<br /><br />".
                    "           Obrigado, <br /><br />";

      $mensagem = $cabecalho;           
      $mensagem.= $texto;
      $mensagem.= $assinatura;
      
      if (!mail($email, $titulo, $mensagem, $headers, "-f$origem")) {
        return false;
      } else {
        return true;
      }    
    }
    
    public function notificarAtualizacaoCadastralAluno($nome, $username, $email) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();

      $nome_site = $conf->retornaNomeSite();
      $titulo = ".:. [".$nome_site."] - Cadastro .:.";
        
      $origem = $conf->retornaEmailOrigemRespostasMensagensAlteracaoSenha();

      $headers = "From:".$origem." <".$origem." >\n";
      $headers.= "X-Mailer: PHP\n";
      $headers.= "X-Priority: 3\n";
      $headers.= "MIME-Version: 1.0\n"; 
      $headers.= "Content-type: text/html; charset=iso-8859-1\n"; 
      $headers.= "Return-Path: <".$origem." >\n";

      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $texto      = "           Prezado(a) ".$nome."<br /><br />".
                    "           Seus dados cadastrais foram atualizados com sucesso.<br /><br />".
                    "           Naveque pelo site e inscreva-se nos cursos de seu interesse.<br /><br />".
                    "           Obrigado, <br /><br />";

      $mensagem = $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;
      
      if (!mail($email, $titulo, $mensagem, $headers, "-f$origem")) {
        return false;
      } else {
        return true;
      }    
    }    
    
  */  
    public function notificarEsqueceuSenha($nome, $email, $senha) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      
      $nome_site = $conf->retornaNomeSite();
      $titulo = $conf->retornaTituloNotificacaoEmailEsqueceuSenha();
      $origem = $conf->retornaEmailOrigemNotificacaoEmailEsqueceuSenha();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);
      
      $texto      = "           Prezado(a) ".$nome."<br /><br />".
                    "           Recebemos sua solicitação de encaminhamento de senha. <br />".
                    "           Para sua maior segurança, todas as senhas são criptografadas e não podem ser recuperadas de nosso Banco de Dados, sendo assim, alteramos sua senha para [ ".$senha." ].<br />".
                    "           <br />".
                    "           Orientamos que altere esta senha.<br /><br />".
                    "           Obrigado, <br /><br />";
      
      $mensagem =  $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;

      $headers = "MIME-Version: 1.1\n";
      $headers .= "Content-type: text/html; charset=iso-8859-1\n";
      $headers .= "From: $origem\n"; // remetente
      $headers .= "Return-Path: $origem\n"; // return-path

      if(!mail($email, $titulo, $mensagem, $headers ,"-r".$origem)){ // Se for Postfix
        $headers .= "Return-Path: ".$origem."\n"; // Se "não for Postfix"
        if (mail($email, $titulo, $mensagem, $headers )) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }      
    }  
/*    
    public function enviarNotificacoesPrazoAcessoConteudos($inscricoes, $nr_dias_prazo) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();

      $nome_site = $conf->retornaNomeSite();
      $titulo = ".:. [".$nome_site."] - Prazo de acesso .:.";
        
      $origem = $conf->retornaEmailOrigemInformacoesPrazoAcesso();
      
      $headers = "From:".$origem." <".$origem." >\n";
      $headers.= "X-Mailer: PHP\n";
      $headers.= "X-Priority: 3\n";
      $headers.= "MIME-Version: 1.0\n"; 
      $headers.= "Content-type: text/html; charset=iso-8859-1\n"; 
      $headers.= "Return-Path: <".$origem." >\n";

      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      foreach ($inscricoes as $i) {
        $texto      = "           Prezado(a) ".$i['nm_aluno']."<br /><br />".
                      "           Verificamos que seu prazo de acesso ao Curso ".$i['nm_curso']." expira em ".$nr_dias_prazo.".<br />".
                      "           Aproveite este prazo para acessar o curso e rever conteúdos.<br />".
                      "           Caso o prazo não seja suficiente, prorrogue sua assinatura conosco.".
                      "           <br />".
                      "           Obrigado, <br /><br />";

        $mensagem = $cabecalho;
        $mensagem.= $texto;
        $mensagem.= $assinatura;
        mail($i['ds_email_01'], $titulo, $mensagem, $headers, "-f$origem");
        if ($i['ds_email_02'] != '') {
          mail($i['ds_email_02'], $titulo, $mensagem, $headers, "-f$origem");
        }
        if ($i['ds_email_03'] != '') {
          mail($i['ds_email_03'], $titulo, $mensagem, $headers, "-f$origem");
        }
      }    
    }
    
    public function notificarAdministradorFaltaAvaliacao($nm_curso) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();

      $nome_site = $conf->retornaNomeSite();
      $titulo = ".:. [".$nome_site."] - Questões para avaliação .:.";
        
      $origem = $conf->retornaEmailOrigemDestinoNotificacoesErroSite();
      
      $headers = "From:".$origem." <".$origem." >\n";
      $headers.= "X-Mailer: PHP\n";
      $headers.= "X-Priority: 3\n";
      $headers.= "MIME-Version: 1.0\n"; 
      $headers.= "Content-type: text/html; charset=iso-8859-1\n"; 
      $headers.= "Return-Path: <".$origem." >\n";

      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $texto      = "           Erro! Falta de Questões para aplicação de avaliação<br /><br />".
                    "           Curso: ".$nm_curso."<br />".

      $mensagem = $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;
      mail($origem, $titulo, $mensagem, $headers, "-f$origem");
    }
    
    public function notificarAlunoEmissaoCertificado($cd_curso, $cd_aluno) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/cursos.php';                                      $cur = new Curso();
      require_once 'conteudos/alunos.php';                                      $alu = new Aluno();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $dados_curso = $cur->selectDadosCurso($cd_curso);
      $dados_aluno = $alu->selectDadosAluno($cd_aluno);

      $nome_site = $conf->retornaNomeSite();
      $titulo = ".:. [".$nome_site."] - Emissão de Certificados .:.";
        
      $origem = $conf->retornaEmailOrigemNotificacoesCertificados();
      
      $headers = "From:".$origem." <".$origem." >\n";
      $headers.= "X-Mailer: PHP\n";
      $headers.= "X-Priority: 3\n";
      $headers.= "MIME-Version: 1.0\n"; 
      $headers.= "Content-type: text/html; charset=iso-8859-1\n"; 
      $headers.= "Return-Path: <".$origem." >\n";

      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $texto      = "           Prezado(a) ".$dados_aluno['nm_aluno']."<br /><br />".
                    "           Seu certificado para o curso ".$dados_curso['nm_curso']." foi emitido.<br />".
                    "           Acesse o certificado na sua área de aluno.<br />".
                    "           <br />".
                    "           Obrigado, <br /><br />";

      $mensagem = $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;
      
      $contato = $con->selectDadosContato($dados_aluno['cd_contato']);
      mail($contato['ds_email_01'], $titulo, $mensagem, $headers, "-f$origem");
      if ($contato['ds_email_02'] != '') {
        mail($contato['ds_email_02'], $titulo, $mensagem, $headers, "-f$origem");
      }
      if ($contato['ds_email_03'] != '') {
        mail($contato['ds_email_03'], $titulo, $mensagem, $headers, "-f$origem");
      }
    }                                                                 
*/    
    private function retornaCabecalhoEmail($nome_site) {
      return "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">".
             "<html>".
             "  <body style=\"text-align:center;\">".
             "    <table width=\"800\">".
             "      <tr>".
             "        <td height=\"135px\">".
             "          <a href=\"".$_SESSION['life_link_completo']."\" style=\"text-decoration:none;\">".
             "            <img src=\"".$_SESSION['life_link_completo']."imagens/topo_email.jpg\" alt=\"".$nome_site."\" title=\"".$nome_site."\" height=\"70\" width=\"800\" border=\"0\">".
             "          </a>".
             "        </td>".
             "      </tr>".
             "      <tr>".
             "        <td>";
    }
                   
    private function retornaAssinaturaEmail($nome_site) {
      return "        </td>".
             "      </tr>".
             "      <tr>".
             "        <td>".
             "          <hr>".
             "             Mensagem gerada automaticamente pelo site <a href=\"".$_SESSION['life_link_completo']."\">".$nome_site."</a>.<br />".
             "             Pedimos não responder à esta mensagem.<br />".
             "             Para contato utilize a seção <a href=\"".$_SESSION['life_link_completo']."fale-conosco\">Fale Conosco</a>.<br />".
             "        </td>".
             "      </tr>".
             "    </table>".
             "  </body>".
             "</html>";  
    }

  }
?>