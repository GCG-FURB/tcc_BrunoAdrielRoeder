<?php
  class Email {
    
    public function __construct() {
    }

    public function notificarAnaliseComentario($cd_contato, $texto) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $contato = $con->selectDadosContato($cd_contato);
      $nome_site = $conf->retornaNomeSite();
      $titulo = 'Análise sobre comentário de Objeto de Aprendizagem';
      $origem = $conf->retornaEmailOrigemComentarioObjetosAprendizagem();
      $senha = $conf->retornaSenhaEmailOrigemComentarioObjetosAprendizagem();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $mensagem = $cabecalho;
      $mensagem.= "Análise do comentário sobre o objeto de aprendizagem, conforme dados abaixo<br />".$texto;
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      if ($contato['ds_email_01'] != '') {        $mailer->AddAddress($contato['ds_email_01']);       }
      if ($contato['ds_email_02'] != '') {        $mailer->AddAddress($contato['ds_email_02']);       }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarComentario($cd_contato, $texto) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $contato = $con->selectDadosContato($cd_contato);
      $nome_site = $conf->retornaNomeSite();
      $titulo = 'Comentário sobre Objeto de Aprendizagem';
      $origem = $conf->retornaEmailOrigemComentarioObjetosAprendizagem();
      $senha = $conf->retornaSenhaEmailOrigemComentarioObjetosAprendizagem();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $mensagem = $cabecalho;
      $mensagem.= "Comentário sobre o objeto de aprendizagem, conforme dados abaixo<br />".$texto;
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      if ($contato['ds_email_01'] != '') {        $mailer->AddAddress($contato['ds_email_01']);       }
      if ($contato['ds_email_02'] != '') {        $mailer->AddAddress($contato['ds_email_02']);       }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarDenunciaDenunciante($cd_contato, $texto) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $contato = $con->selectDadosContato($cd_contato);
      $nome_site = $conf->retornaNomeSite();
      $titulo = 'Denúncia de Objeto de Aprendizagem';
      $origem = $conf->retornaEmailOrigemDenunciaObjetosAprendizagem();
      $senha = $conf->retornaSenhaEmailOrigemDenunciaObjetosAprendizagem();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $mensagem = $cabecalho;
      $mensagem.= "Você denunciou o objeto de aprendizagem, conforme dados abaixo<br />".$texto;
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      if ($contato['ds_email_01'] != '') {        $mailer->AddAddress($contato['ds_email_01']);       }
      if ($contato['ds_email_02'] != '') {        $mailer->AddAddress($contato['ds_email_02']);       }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarDenunciaDenunciado($cd_contato, $texto) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $contato = $con->selectDadosContato($cd_contato);
      $nome_site = $conf->retornaNomeSite();
      $titulo = 'Denúncia de Objeto de Aprendizagem';
      $origem = $conf->retornaEmailOrigemDenunciaObjetosAprendizagem();
      $senha = $conf->retornaSenhaEmailOrigemDenunciaObjetosAprendizagem();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $mensagem = $cabecalho;
      $mensagem.= "Seu objeto de aprendizagem foi denunciado por um usuário do portal, conforme dados abaixo<br />".
                  $texto."<br /><br />".
                  "Acesse nosso portal, efetue login, e avalie a denuncia para que o objeto de aprendizagem seja liberado para acesso.";
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      if ($contato['ds_email_01'] != '') {        $mailer->AddAddress($contato['ds_email_01']);       }
      if ($contato['ds_email_02'] != '') {        $mailer->AddAddress($contato['ds_email_02']);       }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarAnaliseDenunciaDenunciante($cd_contato, $texto) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $contato = $con->selectDadosContato($cd_contato);
      $nome_site = $conf->retornaNomeSite();
      $titulo = 'Análise de denúncia de Objeto de Aprendizagem';
      $origem = $conf->retornaEmailOrigemDenunciaObjetosAprendizagem();
      $senha = $conf->retornaSenhaEmailOrigemDenunciaObjetosAprendizagem();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $mensagem = $cabecalho;
      $mensagem.= "Uma denúncia de objeto de aprendizagem realizada por você, foi analisada pelo responsável do referido objeto de aprendizagem, conforme dados abaixo<br />".$texto.
                  "<br /><br />Caso discorde da análise, sugerimos fazer contato com ".$nome_site.", através da seção ".
                  "<a href=\"".$_SESSION['life_link_completo']."fale-conosco\">fale conosco</a>, ou realizar nova denúncia contra o objeto de aprendizagem.";
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      if ($contato['ds_email_01'] != '') {        $mailer->AddAddress($contato['ds_email_01']);       }
      if ($contato['ds_email_02'] != '') {        $mailer->AddAddress($contato['ds_email_02']);       }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarSenhaDesbloqueio($nome, $emails, $senha_gerada) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();

      $nome_site = $conf->retornaNomeSite();
      $titulo = "Usuário bloqueado";
      $origem = $conf->retornaEmailOrigemNotificacaoEmailEsqueceuSenha();
      $senha = $conf->retornaSenhaEmailOrigemNotificacaoEmailEsqueceuSenha();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $texto      = "           Prezado(a) ".$nome."<br /><br />".
                    "           Percebemos que sua conta no ".$nome_site." está bloqueada. <br />".
                    "           Geramos uma nova senha para permitir que desbloqueie seu usuário. Pedimos que acesse sua conta utilizando a senha ".$senha_gerada."<br />".
                    "           Depois de acessar, altere sua senha.<br />".
                    "           <br />".
                    "           Orientamos que altere esta senha.<br /><br />".
                    "           Obrigado, <br /><br />";

      $mensagem =  $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      foreach ($emails as $email) {
        $mailer->AddAddress($email); //Destinatários
      }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarEsqueceuSenha($nome, $email, $senha_gerada) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      
      $nome_site = $conf->retornaNomeSite();
      $titulo = $conf->retornaTituloNotificacaoEmailEsqueceuSenha();
      $origem = $conf->retornaEmailOrigemNotificacaoEmailEsqueceuSenha();
      $senha = $conf->retornaSenhaEmailOrigemNotificacaoEmailEsqueceuSenha();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);
      
      $texto      = "           Prezado(a) ".$nome."<br /><br />".
                    "           Recebemos sua solicitação de encaminhamento de senha. <br />".
                    "           Para sua maior segurança, todas as senhas são criptografadas e não podem ser recuperadas de nosso banco de dados, sendo assim, alteramos sua senha para ".$senha_gerada."<br />".
                    "           Depois de acessar, altere sua senha.<br />".
                    "           <br />".
                    "           Orientamos que altere esta senha.<br /><br />".
                    "           Obrigado, <br /><br />";
      
      $mensagem =  $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      $mailer->AddAddress($email); //Destinatários
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

    public function notificarAvaliacaoPedidoAlteracaoCategoria($cd_contato, $texto) {
      require_once 'includes/configuracoes.php';                                $conf= new Configuracao();
      require_once 'conteudos/contatos.php';                                    $con = new Contato();

      $contato = $con->selectDadosContato($cd_contato);
      $nome_site = $conf->retornaNomeSite();
      $titulo = $conf->retornaTituloNotificacaoEmailPedidoAlteracaoCategoria();
      $origem = $conf->retornaEmailOrigemNotificacaoEmailPedidoAlteracaoCategoria();
      $senha = $conf->retornaSenhaEmailOrigemNotificacaoEmailPedidoAlteracaoCategoria();
      $cabecalho = $this->retornaCabecalhoEmail($nome_site);
      $assinatura = $this->retornaAssinaturaEmail($nome_site);

      $mensagem =  $cabecalho;
      $mensagem.= $texto;
      $mensagem.= $assinatura;

      require_once('phpmailer/class.phpmailer.php');
      $mailer = new PHPMailer();
      $mailer->IsSMTP();
      $mailer->SMTPDebug = 1;
      $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
      $mailer->Host = 'localhost'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
      //Para cPanel: 'localhost';
      //Para Plesk 11 / 11.5: 'smtp.dominio.com.br';
      //Descomente a linha abaixo caso revenda seja 'Plesk 11.5 Linux'
      //$mailer->SMTPSecure = 'tls';
      $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
      $mailer->Username = $origem; //Informe o e-mai o completo
      $mailer->Password = $senha; //Senha da caixa postal
      $mailer->FromName = $nome_site; //Nome que será exibido para o destinatário
      $mailer->From = $origem; //Obrigatório ser a mesma caixa postal indicada em "username"
      if ($contato['ds_email_01'] != '') {        $mailer->AddAddress($contato['ds_email_01']);       }
      if ($contato['ds_email_02'] != '') {        $mailer->AddAddress($contato['ds_email_02']);       }
      $mailer->IsHTML(true);
      $mailer->Subject = $titulo;
      $mailer->Body = $mensagem;
      if(!$mailer->Send()) {
        return false;
      } else {
        return true;
      }
    }

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