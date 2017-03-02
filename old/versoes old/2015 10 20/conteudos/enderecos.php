<?php
  class Endereco {
  
    public function __construct() {
    }

    public function apresentaFormularioEndereco($titulo, $cd_endereco, $tem_campos_obrigatorios = true) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      require_once 'conteudos/cidades.php';                                     $cid = new Cidade();  
      require_once 'conteudos/estados.php';                                     $est = new Estado();

      if ($cd_endereco == '') {
        $ds_rua = "";
        $ds_numero = "";
        $ds_complemento = "";
        $ds_cep = "";
        $ds_bairro = "";
        $cd_cidade = "5508";      
      } else {
        $dados_end= $this->selectEndereco($cd_endereco);

        $cd_cidade = $dados_end['cd_cidade'];
        $ds_rua = $dados_end['ds_rua'];
        $ds_numero = $dados_end['ds_numero'];
        $ds_complemento = $dados_end['ds_complemento'];
        $ds_bairro = $dados_end['ds_bairro'];
        $ds_cep = $dados_end['ds_cep'];
      }

      if ($tem_campos_obrigatorios) {
        $obrigatorios = '1';             $simbolo = '*';
      } else {
        $obrigatorios = '0';             $simbolo = '';
      }
      
      include_once 'js/js_mascaras_validacoes.js';
      
      $util->linhaComentario($titulo);
      $util->campoHidden('cd_endereco', $cd_endereco);
      echo "      <tr>\n";                                                                          
      echo "		    <td class=\"celConteudo\">Rua: ".$simbolo."</td>\n";
      echo "  	    <td class=\"celConteudo\">\n";
      echo "          <input type=\"text\" maxlength=\"150\" name=\"ds_rua\" id=\"ds_rua\" value=\"".$ds_rua."\" size=\"45\" class=\"fontConteudo\" />\n";
      echo "          Nº ".$simbolo." \n";
      echo "          <input type=\"text\" maxlength=\"10\" name=\"ds_numero\" id=\"ds_numero\" value=\"".$ds_numero."\" size=\"7\" class=\"fontConteudo\" />\n";
      echo "        </td>\n";
      echo "      </tr>\n";    
      $util->linhaUmCampoText(0, 'Complemento: ', 'ds_complemento', 150, 70, $ds_complemento);
      $util->linhaUmCampoText($obrigatorios, 'Bairro: ', 'ds_bairro', 80, 40, $ds_bairro);
      $util->linhaUmCampoTextAcao($obrigatorios, 'CEP: ', 'ds_cep', 10, 20, $ds_cep, " onKeyPress=\"mascaraCep('ds_cep')\"; onBlur=\"validaCep('ds_cep');\" ");
      echo "  	<tr>\n";
      echo "  		<td class=\"celConteudo\">UF/Município: </td>\n";
      echo "  		<td class=\"celConteudo\">\n";
      if ($cd_cidade != "") {
        $cidade = $cid->selectDadosCidade($cd_cidade);
        $estado = $est->selectDadosEstado($cid['cd_estado']);
        echo $est->retornaSeletorEstadosAjax($estado['cd_estado']);
      } else {
        $cd_estado = '';    
        echo $est->retornaSeletorEstadosAjax($estado['cd_estado']);
      }
      echo "        <span id=\"cidade\">";
      echo $cid->apresentaSeletorCidadesEstado($cd_estado, $cd_cidade);
      echo "        </span>\n";
      echo "      </td>\n";
      echo "  	</tr>\n";   
    }
    
    public function salvarCadastroEdicao() {
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();

      $cd_endereco = addslashes($_POST['cd_endereco']);
      $ds_rua = $util->limparVariavel($_POST['ds_rua']);
      $ds_numero = $util->limparVariavel($_POST['ds_numero']);
      $ds_complemento = $util->limparVariavel($_POST['ds_complemento']);
      $ds_cep = $util->limparVariavel($_POST['ds_cep']);
      $ds_bairro = $util->limparVariavel($_POST['ds_bairro']);
      if (isset($_POST['cidade'])) {
        $cd_cidade = addslashes($_POST['cidade']);
      } else {
        $cd_cidade = '5508';
      }

      if ($cd_endereco != '') {
        if ($this->alterarEndereco($cd_endereco, $cd_cidade, $ds_rua, $ds_numero, $ds_complemento, $ds_bairro, $ds_cep)) {
          return $cd_endereco;
        } else {
          return 0;
        }
      } else {
        return $this->inserirEndereco($cd_cidade, $ds_rua, $ds_numero, $ds_complemento, $ds_bairro, $ds_cep);
      }    
    }
    
    public function retornaSeletorUnidadeExpedidora($uf_expedicao) {
      require_once 'includes/utilitarios.php';                                  $util = new Utilitario();
      $itens = $this->selectEstados();
      
      $opcoes = array();
      $opcao = array();         $opcao[] = '';                    $opcao[] = 'Seleciona a UF Expedidora';                   $opcoes[] = $opcao;
      foreach ($itens as $it) {
        $opcao = array();       $opcao[] = $it['sg_estado'];      $opcao[] = $it['sg_estado']." - ".$it['nm_estado'];       $opcoes[] = $opcao;
      }
      $opcao = array();         $opcao[] = 'Internacional';       $opcao[] = 'Documento Internacional';                     $opcoes[] = $opcao;
      $util->linhaSeletor('UF Expedidora: ', 'uf_expedicao', $uf_expedicao, $opcoes);
    }

    public function retornaDetalhesEnderecoCompleto($cd_endereco) {
      $dados = $this->selectEnderecoCompleto($cd_endereco);
    
      $retorno = "";
      $retorno.= "Rua: ".$dados['ds_rua'].", ".$dados['ds_numero']."<br />\n";
      if ($dados['ds_complemento'] != '') {
        $retorno.= "Complemento: ".$dados['ds_complemento']."<br />\n";      
      }
      $retorno.= "Bairro: ".$dados['ds_bairro']." / ".$dados['nm_cidade']." - ".$dados['sg_estado']."<br />\n";
      $retorno.= "Cep.: ".$dados['ds_cep']."<br />\n";
      return $retorno;
    }

//***************************BANCO DE DADOS*************************************
    public function selectDadosEndereco($codigo) {
      $sql  = "SELECT * ".
              "FROM life_enderecos ".
              "WHERE cd_endereco = '$codigo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ENDEREÇOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;         
    }

    public function selectEnderecoCompleto($codigo) {
      $sql  = "SELECT ed.*, cd.nm_cidade, es.sg_estado ".
              "FROM life_enderecos ed, life_cidade cd, life_estado es ".
              "WHERE ed.cd_cidade = cd.cd_cidade ".
              "and cd.cd_estado = es.cd_estado ".
              "and ed.cd_endereco =  '$codigo' ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados - TABELA ENDEREÇOS!");
      $dados= mysql_fetch_assoc($result_id);
      return $dados;         
    }

    public function inserirEndereco($cd_cidade, $ds_rua, $ds_numero, $ds_complemento, $ds_bairro, $ds_cep) {
      $sql  = "INSERT INTO life_enderecos ".
              "(cd_cidade, ds_rua, ds_numero, ds_complemento, ds_bairro, ds_cep) ".
              "VALUES ".
              "('$cd_cidade', '$ds_rua', '$ds_numero', '$ds_complemento', '$ds_bairro', '$ds_cep')";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'enderecos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ENDEREÇOS!");
      $saida = mysql_affected_rows();
      if ($saida) {
        $sql= "SELECT MAX(cd_endereco) codigo ".
              "FROM life_enderecos ".
              "WHERE cd_cidade = '$cd_cidade' ".
              "AND ds_rua = '$ds_rua' ".
              "AND ds_numero = '$ds_numero'";
        $result_id = mysql_query($sql) or die ("Erro no banco de dados - TABELA ENDEREÇOS!");
        $dados = mysql_fetch_assoc($result_id);
        return $dados['codigo'];
      }
      else {
        return 0;
      }      
    }
    
    public function alterarEndereco($cd_endereco, $cd_cidade, $ds_rua, $ds_numero, $ds_complemento, $ds_bairro, $ds_cep) {
      $sql  = "UPDATE life_enderecos ".
              "SET cd_cidade= '$cd_cidade', ".
              "ds_rua= '$ds_rua', ".
              "ds_numero= '$ds_numero', ".
              "ds_complemento= '$ds_complemento', ".
              "ds_bairro= '$ds_bairro', ".
              "ds_cep= '$ds_cep' ".
              "WHERE cd_endereco= '$cd_endereco'";
      require_once 'includes/utilitarios.php';                                  $util= new Utilitario();
      $util->gerarLog($sql, 'enderecos');            
      mysql_query($sql) or die ("Erro no banco de dados - TABELA ENDEREÇOS!");
      $saida = mysql_affected_rows();
      return $saida;
    }

  }
?>