<?php
  class Fotos {

    public function __construct() {
    }

    public function listarFotos($link, $codigo, $tp_associacao) {
      $colunas= 4;
      $largura_tabela = 740;
      $largura_coluna = $largura_tabela / $colunas;
      $largura_foto_max = 600;
      $largura_miniaturas = $largura_foto_max / $colunas;
      $altura_miniaturas = 100; 

      $cd_associacao= $codigo;
      $fotos= $this->selectFotos($cd_associacao, $tp_associacao);
      if (count($fotos) > 0)    {        $imagem_max= $fotos[0];                                                }
      if (isset($_GET['acao'])) {        $acao= $_GET['acao'];                  } else {        $acao= "";      }

      if (isset($_GET['ft'])) {
        if ($acao != 'exc_foto') {       $cd_foto= addslashes($_GET['ft']);     } else {        $cd_foto= "";   }
      } else {
        if (count($fotos) > 0) {
          $imagem= $fotos[0];
          if ($imagem['cd_foto'] != '') {            $cd_foto= $imagem['cd_foto'];          } else {            $cd_foto= "";          }
        } else {
          $cd_foto= "";
        }
      }
      
      $nr_linhas= (int)(count($fotos) / $colunas);
      if (count($fotos) % $colunas) {        $nr_linhas+= 1;      }
      echo "    <table class=\"tabConteudo\">\n";
      $nr_foto= 0;
      for ($linha= 0; $linha < $nr_linhas; $linha++) {
        echo "      <tr>\n";
        for ($coluna= 0; $coluna < $colunas; $coluna++) {
          echo "        <td class=\"celFoto\">\n";
          if ($nr_foto < count($fotos)) {
            $imagem= $fotos[$nr_foto];
            if ($imagem['cd_foto'] == $cd_foto) {
              $imagem_max= $imagem;
            }
            echo "          <a href=\"".$link."&ft=".$imagem['cd_foto']."&acao=fotos\" class=\"fontLink\"><img src=\"".$imagem['ds_pasta']."/".$imagem['ds_arquivo']."\" alt=\"".$imagem['ds_foto']."\" title=\"".$imagem['ds_foto']."\" border=\"0\" height=\"".$altura_miniaturas."\"></a>\n";
            echo "          <br />\n";
            echo "          <a href=\"#\" class=\"dcontexto\"><img src=\"icones/informacoes.png\" border=\"0\"><span class=\"fontdDetalhar\">".$this->detalharFoto($imagem['cd_foto'])."</span></a>\n";
            echo "          <a href=\"".$link."&ft=".$imagem['cd_foto']."&acao=edi_foto\"><img src=\"icones/editar.png\" border=\"0\" alt=\"Editar Dados da Foto\" title=\"Editar Dados da Foto\"></a>\n";
            echo "          <a href=\"".$link."&ft=".$imagem['cd_foto']."&acao=exc_foto\"><img src=\"icones/excluir.png\" border=\"0\" alt=\"Excluir Foto\" title=\"Excluir Foto\"></a>\n";
          } else {
            echo "          &nbsp;\n";
          }
          $nr_foto+= 1;
          echo "        </td>\n";
        }
        echo "      </tr>\n";
      }
      if ($cd_foto != '') {
        echo "      <tr>\n";
        echo "        <td class=\"celFoto\" colspan=\"".$colunas."\" width=\"".$largura_tabela."\">\n";
        echo "          <img src=\"".$imagem_max['ds_pasta']."/".$imagem_max['ds_arquivo']."\" alt=\"".$imagem_max['ds_arquivo']."\" title=\"".$imagem_max['ds_arquivo']."\" border=\"0\" width=\"500\">\n";        
        echo "          <p class=\"fontConteudoCentralizado\">\n";
        //detalhes da foto
        if ($imagem_max['ds_foto'] != '') {
          echo "          <b>Descrição:</b> ".$imagem_max['ds_foto']."<br />\n";
        }
        echo "          </p>\n";
        echo "        </td>\n";
        echo "      </tr>\n";
      }
      echo "    </table>\n";
      echo "    <br /><br />\n";    
    }    

    private function detalharFoto($cd_foto) {
      require_once 'usuarios/usuarios.php';                                     $usu = new Usuario();
      require_once 'includes/data_hora.php';                                    $dh = new DataHora();
                            
      $dados = $this->selectDadosFoto($cd_foto);
                            
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

    public function montarFormularioCadastroFoto($link, $cd_associacao, $tp_associacao) {
      $dados_pasta = $this->selectDadosTiposAssociacoesFotos($tp_associacao);
      $ds_pasta = $dados_pasta['ds_pasta_tipo_associacao_foto'];
      
      $cd_foto = "";
      $ds_foto = "";
      $ds_arquivo = "";
      $ds_autor = "";
      $ds_email_autor = "";
      $ds_fonte = "";
      $ds_link_fonte = "";
      $eh_ativo = "1";
      
      $_SESSION['life_edicao'] = '1';

      echo "  <h2>Cadastro de Foto</h2>\n";
      $this->formularioCadastroFoto($link, $cd_foto, $cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo);
    }
    
    public function montarFormularioEdicaoFoto($link, $cd_associacao, $tp_associacao) {
      $cd_foto= addslashes($_GET['ft']);
      $dados= $this->selectDadosFoto($cd_foto);
      $cd_associacao = $dados['cd_associacao'];

      if ($dados['cd_associacao'] != $cd_associacao) {
        return false;
      }

      $tp_associacao = $dados['tp_associacao'];
      $ds_foto = $dados['ds_foto'];
      $ds_arquivo = $dados['ds_arquivo'];
      $ds_pasta = $dados['ds_pasta'];
      $ds_autor = $dados['ds_autor'];
      $ds_email_autor = $dados['ds_email_autor'];
      $ds_fonte = $dados['ds_fonte'];
      $ds_link_fonte = $dados['ds_link_fonte'];
      $eh_ativo = $dados['eh_ativo'];
      
      $_SESSION['life_edicao'] = '1';

      echo "  <h2>Edição de Foto</h2>\n";
      $this->formularioCadastroFoto($link, $cd_foto, $cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo);
    }
                                           
    public function formularioCadastroFoto($link, $cd_foto, $cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo) {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
    
      include 'js/js_cadastro_foto.js';
      echo "<form method=\"POST\" name=\"cadastro\" action=\"".$link."&acao=salv_foto\" enctype=\"multipart/form-data\" onSubmit=\"return  valida(this);\">\n";
      echo "  <table class=\"tabConteudo\">\n";
      $util->campoHidden('cd_foto', $cd_foto);
      $util->campoHidden('cd_associacao', $cd_associacao);
      $util->campoHidden('tp_associacao', $tp_associacao);
      $util->campoHidden('ds_pasta', $ds_pasta);
      $util->campoHidden('eh_ativo', $eh_ativo);
      $util->linhaUmCampoTextHint(1, 'Descrição da Foto/Imagem ', 'ds_foto', 250, 840, $ds_foto, 1);
      $util->linhaUmCampoTextHint(0, 'Autor ', 'ds_autor', 150, 840, $ds_autor, 1);
      $util->linhaUmCampoTextHint(0, 'E-mail Autor ', 'ds_email_autor', 100, 840, $ds_email_autor, 1);
      $util->linhaUmCampoTextHint(0, 'Fonte ', 'ds_fonte', 150, 840, $ds_fonte, 1);
      $util->linhaUmCampoTextHint(0, 'Link da Fonte ', 'ds_link_fonte', 100, 840, $ds_link_fonte, 1);
      if ($cd_foto > 0) {
        $util->campoHidden('ds_arquivo', $ds_arquivo);
        echo "      <tr>\n";
        echo "        <td class=\"celFoto\" colspan=\"2\">\n";
        echo "          <img src=\"".$ds_pasta."/".$ds_arquivo."\" alt=\"".$ds_foto."\" title=\"".$ds_foto."\" border=\"0\" width=\"550\">\n";
        echo "        </td>\n";
        echo "      </tr>\n";        
      } else {
        $util->linhaUmCampoArquivoHint(1, 'Foto: ', 'ds_arquivo', 150, 840, '', 1);
      }
      if ($cd_foto > 0) {        $util->linhaBotao('Editar');      } else {        $util->linhaBotao('Cadastrar');      }       echo "  </table>\n";
      
      if ($cd_foto == 0) {      
        echo "  <p class=\"fontConteudoAlerta\">Utilize preferencialmente fotos no formato '.jpg'. <br />Caso o tamanho do arquivo supere '1,5MB' reduza-o utilizando um software de edição de imagens.</p>\n";
      } else {
        echo "  <p class=\"fontConteudoAlerta\">Para substituir uma foto, exclua a foto atual e insira nova foto.</p>\n";
      }
      echo "</form>\n"; 
      $util->posicionarCursor('cadastro', 'ds_foto');            
    }
    
    public function salvarFoto() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
          
      $cd_foto = addslashes($_POST['cd_foto']);
      $cd_associacao = addslashes($_POST['cd_associacao']);
      $tp_associacao = addslashes($_POST['tp_associacao']);
      $ds_foto = $util->limparVariavel($_POST['ds_foto']);
      $ds_pasta = $util->limparVariavel($_POST['ds_pasta']);
      $ds_autor = $util->limparVariavel($_POST['ds_autor']);
      $ds_email_autor = $util->limparVariavel($_POST['ds_email_autor']);
      $ds_fonte = $util->limparVariavel($_POST['ds_fonte']);
      $ds_link_fonte = $util->limparVariavel($_POST['ds_link_fonte']);
      $eh_ativo = addslashes($_POST['eh_ativo']);

      if ($cd_foto != 0) {      
        $ds_arquivo= $util->limparVariavel($_POST['ds_arquivo']);
        if ($this->editarFoto($cd_foto, $cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo)) {
          echo "<p class=\"fontConteudoSucesso\">Dados da Foto editados com Sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problemas ao editar Dados da Foto, ou nenhuma Informação Alterada!</p>\n";
        }
      } else {
        $campo= "ds_arquivo";
        $foto= $this->enviarFoto($campo, $ds_pasta, $cd_associacao, $tp_associacao);  
        if ($foto[0] != '') {
          echo "<p class=\"fontConteudoAlerta\">".$foto[0]."</p>\n";
        } else {
          $ds_arquivo= $foto[1];
          if ($this->inserirFoto($cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo)) {
            echo "<p class=\"fontConteudoSucesso\">Foto cadastrada com Sucesso!</p>\n";
          } else {
            echo "<p class=\"fontConteudoAlerta\">Problemas ao cadastrar Foto!</p>\n";
          }
        }
      }    
    }

    public function enviarFoto($campo, $pasta, $tp_associacao, $proporcao_largura) {
      $mensagem  = '';
      $foto_temp = "";
      $foto_n    = "";
      $foto      = "";
      if (isset($_FILES[$campo])) {
        $nome= "life_".$tp_associacao."_".date('Y_m_d')."_".date('H_i_s')."_".mt_rand(0, '9999999');
        //setar novo tamanho de buffer para criar arquivo - padrao é 8M
        ini_set("memory_limit","80M");
        // Prepara a variável do arquivo
        $arquivo = isset($_FILES[$campo]) ? $_FILES[$campo] : FALSE;
        $foto_temp= $nome;

        // Formulário postado... executa as ações
        if($arquivo) {
          $nome_arquivo = $arquivo["name"];
          preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $extensao);        

          $foton = $foto_temp.".".$extensao[1];
          $foto= $foton;
          $destino= $pasta."/".$foton;
          $criou_imagem = false;    
          // Verifica se o mime-type do arquivo é de imagem
          if (preg_match("/jpg/i", $arquivo["name"])) {
            // Verificação de dados OK, nenhum erro ocorrido, executa então o upload...
            $dados_arquivo= $_FILES[$campo]['tmp_name'];
            //descobrir tamanho da imagem
            $img = imagecreatefromjpeg($dados_arquivo);

            $largura = imagesx($img);
            $altura  = imagesy($img);
    
            if ($proporcao_largura > 0) {
              //calcular novas dimensões
              $proporcao = $largura/$proporcao_largura;
              $nova_largura = $largura/$proporcao;
              $nova_altura = $altura/$proporcao;
            } else {
              $nova_largura = imagesx($img);
              $nova_altura  = imagesy($img);
            }

            $imgnova= imagecreatetruecolor($nova_largura, $nova_altura);
            // Faz o redimensionamento da imagem
            if (imagecopyresampled($imgnova, $img, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura)) {
              if (!imagejpeg($imgnova,$destino)) {
                $mensagem= 'Problemas no envio da foto!';
              }
            } else {
              $mensagem= 'Problemas no redimensionamento da foto!';
            }
            imagedestroy($img);
          } elseif (preg_match("/png/i", $arquivo["name"])) {
            // Verificação de dados OK, nenhum erro ocorrido, executa então o upload...
            $dados_arquivo= $_FILES[$campo]['tmp_name'];
            //descobrir tamanho da imagem
            $img = imagecreatefrompng($dados_arquivo);
            $largura = imagesx($img);
            $altura  = imagesy($img);
    
            $nova = imagecreatetruecolor($largura, $altura);
            imagealphablending ($nova, true);
            $transparente = imagecolorallocatealpha ($nova, 0, 0, 0, 127);
            imagefill ($nova, 0, 0, $transparente);
            imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $largura, $altura);
            imagesavealpha($nova, true);
            
            imagepng($nova, $destino);
            imagedestroy($img);
            imagedestroy($nova);
          } else {
            $mensagem = "Arquivo de Foto em formato inválido (.jpg ou .png)!";
          }
        } else {
          $foto = "";
        }
      } else {
        $foto = "";
      }
      $retorno = array();
      $retorno[]= $mensagem;
      $retorno[]= $foto;
      return $retorno;
    }      

    public function exclusaoFoto($link, $cd_associacao, $tp_associacao) {
      $cd_foto= addslashes($_GET['ft']);

      $dados= $this->selectDadosFoto($cd_foto);
      if ($dados['cd_associacao'] != $cd_associacao) {
        return false;
      }
      
      if ($this->excluirFoto($cd_foto)) {
        if (unlink($dados['ds_pasta']."/".$dados['ds_arquivo'])) {
          echo "<p class=\"fontConteudoSucesso\">Foto Excluída com Sucesso!</p>\n";
        } else {
          echo "<p class=\"fontConteudoAlerta\">Problema ao Excluir Arquivo da Foto!</p>\n";
        }
      } else {
        echo "<p class=\"fontConteudoAlerta\">Problema ao Excluir Foto da Base de Dados!</p>\n";
      }
    }
/*      
    
    public function itemPossuiFoto($cd_associacao, $tp_associacao) {
      $fotos= $this->selectFotos($cd_associacao, $tp_associacao);
      if (count($fotos) > 0) {        return true;      } else {        return false;      }
    }
    
    public function exibeFotos($cd_associacao, $tp_associacao, $titulo, $imprimir) {
      if (!isset($_SESSION['life_link_completo'])) {
        require_once 'includes/configuracoes.php';                              $conf= new Configuracao();
        $_SESSION['life_link_completo'] = $conf->retornaLinkCompletoAplicacao();
      }   
      $_SESSION['life_id_foto'] = 0;
      $_SESSION['life_id_miniatura'] = 0; 
      $possui_foto= $this->itemPossuiFoto($cd_associacao, $tp_associacao);
      if ($possui_foto) {
        $fotos= $this->selectFotos($cd_associacao, $tp_associacao);
        $this->exibeListaFotos($fotos, $titulo, $imprimir, $cd_associacao, $tp_associacao);
      }
    }
                       
    public function exibeListaFotos($fotos, $titulo, $imprimir, $cd_associacao, $tp_associacao) {
      $fotos_pacote = 6;
    
      echo "  <div class=\"divFotosGeral\">\n";
      echo "    <br />\n";
      echo "    <div class=\"divAreaFotoCentral\">\n";
      $primeira = $fotos[0];
      echo "      <img src=\"".$_SESSION['life_link_completo'].$primeira['ds_pasta']."/".$primeira['ds_arquivo']."\" alt=\"".$titulo."\" title=\"".$titulo."\" border=\"0\" width=\"70%\" id=\"foto_".$cd_associacao."_".$tp_associacao."\">\n";
      echo "      <div class=\"divDescricaoFotoCentral\">\n";
      echo "        <label class=\"fontDescricaoFoto\" id=\"texto_foto_".$cd_associacao."_".$tp_associacao."\">".$primeira['ds_foto']."</label>\n";
      echo "      </div>\n";
      echo "    </div>\n";         
      
      $miniaturas = array();
      $i = 0;
      $pacote = array();
      foreach ($fotos as $f) {
        $pacote[] = $f;
        $i += 1;
        if ($i == $fotos_pacote) {
          $miniaturas[] = $pacote;
          $pacote = array();
          $i = 0;
        }
      }
      if ($i != 0) {
        $miniaturas[] = $pacote;
      }   
      echo "      <div class=\"divFotosVoltar\">\n";
      echo "        <img id=\"fotos_voltar\" src=\"".$_SESSION['life_link_completo']."imagens/fotos_voltar.png\" style=\"cursor: pointer;\" alt=\"Fotos Anteriores\" title=\"Fotos Anteriores\" border=\"0\" height=\"65\" onClick=\"voltarMiniaturas('$cd_associacao', '$tp_associacao');\">\n";
      echo "      </div>\n";
      echo "      <div class=\"divMiniaturasFotos\">\n";
      $pacote = $miniaturas[0];
      $id_miniatura = 1;
      foreach ($pacote as $p) {
        echo "        <img id=\"miniatura_".$id_miniatura."_".$cd_associacao."_".$tp_associacao."\" src=\"".$_SESSION['life_link_completo'].$p['ds_pasta']."/".$p['ds_arquivo']."\" style=\"cursor: pointer;\" alt=\"[+] Clique para Ampliar\" title=\"[+] Clique para Ampliar\" border=\"0\" height=\"70\" onClick=\"trocarMiniatura('$id_miniatura', '$cd_associacao', '$tp_associacao');\">\n";
        $id_miniatura += 1;       
      }
      echo "      </div>\n";
      echo "      <div class=\"divFotosAvancar\">\n";
      echo "        <img id=\"fotos_avancar\" src=\"".$_SESSION['life_link_completo']."imagens/fotos_avancar.png\"  style=\"cursor: pointer;\" alt=\"Próximas Fotos\" title=\"Próximas Fotos\" border=\"0\" height=\"65\" onClick=\"avancarMiniaturas('$cd_associacao', '$tp_associacao');\">\n";
      echo "      </div>\n";
      echo "      <div class=\"divFotoPaginacao\">\n";
      $i = 1;
      $total = count($miniaturas);
      foreach ($miniaturas as $m) {
        $campo = $total - $i + 1;
        $posicao = $total - $i; 
        echo "      <div class=\"divFotoPagina\" style=\"cursor: pointer;\" onClick=\"posicionarMiniaturas('$posicao', '$cd_associacao', '$tp_associacao');\">\n";        
        echo "        <label class=\"fontDescricaoFoto\" style=\"cursor: pointer;\">".$campo."</label>\n";
        echo "      </div>\n";
        $i+= 1;
      }  
      echo "      </div>\n"; 
      echo "    </div>\n"; 
      echo "    <input type=\"hidden\" name=\"pacote\" id=\"pacote_".$cd_associacao."_".$tp_associacao."\" value=\"0\" />\n";
      echo "    <input type=\"hidden\" name=\"ultimo_pacote\" id=\"ultimo_pacote_".$cd_associacao."_".$tp_associacao."\" value=\"".count($miniaturas)."\" />\n";
      $id_m = 0;
      foreach ($miniaturas as $m) {
        $id_miniatura = 1;  
        foreach ($m as $p) {
          $id_foto = "miniatura_".$id_m."_".$id_miniatura."_".$cd_associacao."_".$tp_associacao;
          echo "    <input type=\"hidden\" name=\"".$id_foto."\" id=\"".$id_foto."\" value=\"".$p['ds_foto']."\" />\n";
          $id_miniatura += 1;
        }
        $id_m += 1;
      }

      if (isset($_SESSION['life_mostrar_fotos_pacote'])) {
        $funcao = $_SESSION['life_mostrar_fotos_pacote']; 
      } else {
        $funcao = "";
      }
      $funcao .="          if ((cd_associacao == '".$cd_associacao."') && (tp_associacao == '".$tp_associacao."')) {\n";
      $id_m = 0;
      foreach ($miniaturas as $m) {  
        $funcao .="          if (pacote == ".$id_m.") {\n";
        $id_miniatura = 1;
        foreach ($m as $p) {
          $id_foto = "miniatura_".$id_miniatura."_".$cd_associacao."_".$tp_associacao;
          $funcao .="        document.getElementById(\"".$id_foto."\").src = \"".$_SESSION['life_link_completo'].$p['ds_pasta']."/".$p['ds_arquivo']."\";\n";
          $id_miniatura += 1;
        }
        while ($id_miniatura <= $fotos_pacote) {
          $id_foto = "miniatura_".$id_miniatura."_".$cd_associacao."_".$tp_associacao;
          $funcao .="        document.getElementById(\"".$id_foto."\").src = \"".$_SESSION['life_link_completo']."imagens/foto_vazia.png\";\n";
          $id_miniatura += 1;
        }
        $funcao .="          }\n";
        $id_m += 1;
      }
      $funcao .="          }\n";
      $_SESSION['life_mostrar_fotos_pacote'] = $funcao;      
    }   
    
    public function geraFuncaoMostrarFotosPacote() {
      echo "    <script>\n";
      echo "      <!--\n";
      echo "        function mostrarFotosPacote(pacote, cd_associacao, tp_associacao) {\n";
      echo $_SESSION['life_mostrar_fotos_pacote'];
      echo "        }\n";
      echo "      -->\n";
      echo "    </script>\n";    
      unset($_SESSION['life_mostrar_fotos_pacote']);
    }
    
    public function exibeUmaFotoAreaCentral($cd_associacao, $tp_associacao) {
      $fotos = $this->selectFotos($cd_associacao, $tp_associacao);
      $qtd_fotos = count($fotos);
      if ($qtd_fotos > 0) {
        $nro_sorteado= rand(0, $qtd_fotos-1);
        $foto = $fotos[$nro_sorteado];

        echo "  <div class=\"divFotoAreaCentral\">\n";
        echo "    <img src=\"".$_SESSION['life_link_completo'].$foto['ds_pasta']."/".$foto['ds_arquivo']."\" alt=\"".$foto['ds_foto']."\" title=\"".$foto['ds_foto']."\" width=\"750\">\n";
        echo "  </div>\n";
      } else {
        return "";
      }
    }
    
    public function retornaUmaFoto($cd_associacao, $tp_associacao) {
      $fotos= $this->selectFotos($cd_associacao, $tp_associacao);
      $qtd_fotos= count($fotos);
      if ($qtd_fotos > 0) {
        $nro_sorteado= rand(0, $qtd_fotos-1);
        return $fotos[$nro_sorteado];
      } else {
        return "";
      }
    }
*/                         
//*******************************BANCO DE DADOS*********************************

    public function selectFotos($cd_associacao, $tp_associacao) {
      $sql = "SELECT * ".
             "FROM life_fotos ".
             "WHERE cd_associacao = '$cd_associacao' ".
             "AND tp_associacao = '$tp_associacao' ".
             "AND eh_ativo = '1' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA FOTOS!");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
        $dados[] = $linha;
      }
      return $dados;    
    }
                        
    public function selectDadosFoto($cd_foto) {
      $sql = "SELECT * ".
             "FROM life_fotos ".
             "WHERE cd_foto = '$cd_foto' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA FOTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;    
    }    
                           

    private function inserirFoto($cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo) {
      $cd_usuario_cadastro = $_SESSION['life_codigo'];
      $dt_cadastro = date('Y-m-d');
      $sql  = "INSERT INTO life_fotos ".
              "(cd_associacao, tp_associacao, ds_foto, ds_arquivo, ds_pasta, ds_autor, ds_email_autor, ds_fonte, ds_link_fonte, eh_ativo, cd_usuario_cadastro, dt_cadastro) ".
              "VALUES ".
              "(\"$cd_associacao\", \"$tp_associacao\", \"$ds_foto\", \"$ds_arquivo\", \"$ds_pasta\", \"$ds_autor\", \"$ds_email_autor\", \"$ds_fonte\", \"$ds_link_fonte\", \"$eh_ativo\", \"$cd_usuario_cadastro\", \"$dt_cadastro\")";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'fotos');              
      mysql_query($sql) or die ("Erro no banco de dados!");
      return mysql_affected_rows();   
    }
                                          
    private function editarFoto($cd_foto, $cd_associacao, $tp_associacao, $ds_foto, $ds_arquivo, $ds_pasta, $ds_autor, $ds_email_autor, $ds_fonte, $ds_link_fonte, $eh_ativo) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql  = "UPDATE life_fotos SET ".
              "cd_associacao = \"$cd_associacao\", ".
              "tp_associacao = \"$tp_associacao\", ".
              "ds_foto = \"$ds_foto\", ".
              "ds_arquivo = \"$ds_arquivo\", ".
              "ds_pasta = \"$ds_pasta\", ".
              "ds_autor = \"$ds_autor\", ".
              "ds_email_autor = \"$ds_email_autor\", ".
              "ds_fonte = \"$ds_fonte\", ".
              "ds_link_fonte = \"$ds_link_fonte\", ".
              "eh_ativo = \"$eh_ativo\", ".
              "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
              "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
              "WHERE cd_foto= '$cd_foto' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'fotos');              
      mysql_query($sql) or die ("Erro no banco de dados!");
      return mysql_affected_rows();   
    }                            
                               
    public function excluirFoto($cd_foto) {
      $cd_usuario_ultima_atualizacao = $_SESSION['life_codigo'];
      $dt_ultima_atualizacao = date('Y-m-d');
      $sql  = "UPDATE life_fotos SET ".
              "eh_ativo = '0', ".
              "cd_usuario_ultima_atualizacao = \"$cd_usuario_ultima_atualizacao\", ".
              "dt_ultima_atualizacao = \"$dt_ultima_atualizacao\" ".             
              "WHERE cd_foto= '$cd_foto' ";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'fotos');              
      mysql_query($sql) or die ("Erro no banco de dados - TABELA FOTOS!");
      return mysql_affected_rows();   
    }

    public function selectDadosTiposAssociacoesFotos($tp_associacao) {
      $sql = "SELECT * ".
             "FROM life_tipo_associacoes_fotos ".
             "WHERE nm_tipo_associacao_foto = '$tp_associacao' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA TIPOS ASSOCIAÇÕES FOTOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;        
    }
  
  } 
?>