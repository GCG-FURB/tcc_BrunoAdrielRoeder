<?php
  class UploadArquivo {
  
    public function __construct() {
    
    }
    
    public function uploadArquivoGenerico($campo, $pasta, $tp_origem_arquivo) {
      require_once 'conteudos/arquivos_extensao.php';                           $arq_ext = new ArquivoExtensao();
      require_once 'conteudos/tipos_arquivos.php';                              $tip_arq = new TipoArquivo();
      $dados = $tip_arq->selectDadosTipoArquivoNome($tp_origem_arquivo);
      $extensoes = $arq_ext->selectExtensoes('1', $dados['cd_tipo_arquivo']);
      $conteudo_campo= $_FILES[$campo];
      $mensagem= '';
      $nome= "";

      if ($conteudo_campo['name']) {
        if (isset($_FILES[$campo])) {
          $nome= $tp_origem_arquivo."_".substr(md5(uniqid(time())),0,15).date('Y_m_d')."_".date('H_s_i');
          ini_set("memory_limit","80MB");
          $arquivo = isset($_FILES[$campo]) ? $_FILES[$campo] : FALSE;
          if($arquivo) {
            $destino= $pasta."/".$nome;    
            $eh_aceito = false;
            foreach ($extensoes as $ext) {              
              if (!$eh_aceito) {
                if (preg_match("/".$ext['ds_extensao']."/i", $arquivo["name"])) {
                  $eh_aceito = true;
                }              
              }
            }
            $extensao = $arquivo['name'];
            $tamanho = strlen($extensao);
            $cont = 1;
            $achou = false;
            $posicao = 0;
            while (($cont < $tamanho) && (!$achou)) {
              $atual = $cont * -1;
              if (substr($extensao, $atual, 1) == ".") {
                $achou = true;
                $posicao = $cont;
              } 
              $cont++;
            }
            $posicao = -1 * ($posicao - 1);       
            if ($achou) {
              $extensao = substr($extensao, $posicao);
            }
            if(!$eh_aceito) {
              $mensagem = "Arquivo em formato inválido!";
            } else {
              $dados_arquivo= $_FILES[$campo]['tmp_name'];
              $nome.= ".".$extensao;
              $destino.= ".".$extensao;
              if (!move_uploaded_file($dados_arquivo,$destino)) {
                $mensagem= 'Problemas no envio do arquivo!';
              }
            }
          }
        }
      }
      $retorno= array();
      $retorno[]= $mensagem;
      $retorno[]= $nome;
      $retorno[]= $arquivo['name'];
      return $retorno;    
    }    
    
    public function excluirArquivo($arquivo, $pasta) {
      $caminho= $pasta."/".$arquivo;
      if (@fopen($caminho, 'r')) {
        if (unlink($caminho)) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }
  
  }
?>