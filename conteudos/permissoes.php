<?php
  class Permissao {

    public function __construct() {
    }

    public function retornaCamposPermissoes($ds_permissoes) {
      require_once 'includes/utilitarios.php';                                 $util= new Utilitario();
    
      $lista_permissoes_1= $this->selectListaPermissoes('0');

      echo "  <table class=\"tabConteudo\">\n";
      $style= "linhaOn";

      if (count($lista_permissoes_1) > 0) {
        $style= "linhaOn";
        echo "    <tr class=\"".$style."\">\n";
        echo "      <td class=\"celConteudo\"><b>Permissões</b></td>\n";
        echo "    </tr>\n";

        foreach ($lista_permissoes_1 as $lp1) {
          $style = "linhaOf";
          $posicao_1 = $lp1['nr_posicao'];
          $permissao_1 = $ds_permissoes[$posicao_1];
          $acesso_1 = $lp1['ds_permissao'];
          
          echo "    <tr class=\"".$style."\">\n";
          echo "      <td class=\"celConteudo\">\n";
          echo "        <input type=\"checkbox\" name=\"pos_".$posicao_1."\" id=\"pos_".$posicao_1."\" ";
          if ($permissao_1 == "1") {
            echo " checked=\"true\" ";
            $display = 'block';
          } else {
            $display = 'none';
          }
          echo "value=\"on\" class=\"celConteudo\" onclick=\"ajustarStatus('$posicao_1');\">\n";
          echo "        ".$acesso_1."\n";
          echo "      </td>\n";
          echo "    </tr>\n";       
                      
          $lista_permissoes_2= $this->selectListaPermissoes($lp1['cd_permissao']); 
          $i = 0;
          $style = "linhaOn";
          foreach ($lista_permissoes_2 as $lp2) {
            $posicao_2 = $lp2['nr_posicao'];
            $permissao_2 = $ds_permissoes[$posicao_2];
            $acesso_2 = $lp2['ds_permissao'];

            $i+= 1;
            echo "    <tr class=\"".$style."\" id=\"linha_pos_".$posicao_1."_".$i."\" style=\"display:$display;\">\n";
            echo "      <td class=\"celConteudo\">\n";
            echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
            echo "          <input type=\"checkbox\" name=\"pos_".$posicao_2."\" ";
            if ($permissao_2 == "1") {          echo " checked=\"true\" ";      } 
            echo "value=\"on\" class=\"celConteudo\">\n";
            echo "        ".$acesso_2."\n";
            echo "      </td>\n";
            echo "    </tr>\n";
          }
          echo "    <input type=\"hidden\" name=\"qt_pos_".$posicao_1."\" id=\"qt_pos_".$posicao_1."\" value=\"".$i."\" />\n";
        }          
      }
      echo "  </table>\n";
    }
                                              
    public function retornaPermissoes() {
      if ((isset($_POST['pos_0']))   && ($_POST['pos_0']))   { $pos_0 = "1"; }   else { $pos_0 = "0";   }
      if ((isset($_POST['pos_1']))   && ($_POST['pos_1']))   { $pos_1 = "1"; }   else { $pos_1 = "0";   }
      if ((isset($_POST['pos_2']))   && ($_POST['pos_2']))   { $pos_2 = "1"; }   else { $pos_2 = "0";   }
      if ((isset($_POST['pos_3']))   && ($_POST['pos_3']))   { $pos_3 = "1"; }   else { $pos_3 = "0";   }
      if ((isset($_POST['pos_4']))   && ($_POST['pos_4']))   { $pos_4 = "1"; }   else { $pos_4 = "0";   }
      if ((isset($_POST['pos_5']))   && ($_POST['pos_5']))   { $pos_5 = "1"; }   else { $pos_5 = "0";   }
      if ((isset($_POST['pos_6']))   && ($_POST['pos_6']))   { $pos_6 = "1"; }   else { $pos_6 = "0";   }
      if ((isset($_POST['pos_7']))   && ($_POST['pos_7']))   { $pos_7 = "1"; }   else { $pos_7 = "0";   }
      if ((isset($_POST['pos_8']))   && ($_POST['pos_8']))   { $pos_8 = "1"; }   else { $pos_8 = "0";   }
      if ((isset($_POST['pos_9']))   && ($_POST['pos_9']))   { $pos_9 = "1"; }   else { $pos_9 = "0";   }
      if ((isset($_POST['pos_10']))  && ($_POST['pos_10']))  { $pos_10 = "1"; }  else { $pos_10 = "0";  }
      if ((isset($_POST['pos_11']))  && ($_POST['pos_11']))  { $pos_11 = "1"; }  else { $pos_11 = "0";  }
      if ((isset($_POST['pos_12']))  && ($_POST['pos_12']))  { $pos_12 = "1"; }  else { $pos_12 = "0";  }
      if ((isset($_POST['pos_13']))  && ($_POST['pos_13']))  { $pos_13 = "1"; }  else { $pos_13 = "0";  }
      if ((isset($_POST['pos_14']))  && ($_POST['pos_14']))  { $pos_14 = "1"; }  else { $pos_14 = "0";  }
      if ((isset($_POST['pos_15']))  && ($_POST['pos_15']))  { $pos_15 = "1"; }  else { $pos_15 = "0";  }
      if ((isset($_POST['pos_16']))  && ($_POST['pos_16']))  { $pos_16 = "1"; }  else { $pos_16 = "0";  }
      if ((isset($_POST['pos_17']))  && ($_POST['pos_17']))  { $pos_17 = "1"; }  else { $pos_17 = "0";  }
      if ((isset($_POST['pos_18']))  && ($_POST['pos_18']))  { $pos_18 = "1"; }  else { $pos_18 = "0";  }
      if ((isset($_POST['pos_19']))  && ($_POST['pos_19']))  { $pos_19 = "1"; }  else { $pos_19 = "0";  }
      if ((isset($_POST['pos_20']))  && ($_POST['pos_20']))  { $pos_20 = "1"; }  else { $pos_20 = "0";  }
      if ((isset($_POST['pos_21']))  && ($_POST['pos_21']))  { $pos_21 = "1"; }  else { $pos_21 = "0";  }
      if ((isset($_POST['pos_22']))  && ($_POST['pos_22']))  { $pos_22 = "1"; }  else { $pos_22 = "0";  }
      if ((isset($_POST['pos_23']))  && ($_POST['pos_23']))  { $pos_23 = "1"; }  else { $pos_23 = "0";  }
      if ((isset($_POST['pos_24']))  && ($_POST['pos_24']))  { $pos_24 = "1"; }  else { $pos_24 = "0";  }
      if ((isset($_POST['pos_25']))  && ($_POST['pos_25']))  { $pos_25 = "1"; }  else { $pos_25 = "0";  }
      if ((isset($_POST['pos_26']))  && ($_POST['pos_26']))  { $pos_26 = "1"; }  else { $pos_26 = "0";  }
      if ((isset($_POST['pos_27']))  && ($_POST['pos_27']))  { $pos_27 = "1"; }  else { $pos_27 = "0";  }
      if ((isset($_POST['pos_28']))  && ($_POST['pos_28']))  { $pos_28 = "1"; }  else { $pos_28 = "0";  }
      if ((isset($_POST['pos_29']))  && ($_POST['pos_29']))  { $pos_29 = "1"; }  else { $pos_29 = "0";  }
      if ((isset($_POST['pos_30']))  && ($_POST['pos_30']))  { $pos_30 = "1"; }  else { $pos_30 = "0";  }
      if ((isset($_POST['pos_31']))  && ($_POST['pos_31']))  { $pos_31 = "1"; }  else { $pos_31 = "0";  }
      if ((isset($_POST['pos_32']))  && ($_POST['pos_32']))  { $pos_32 = "1"; }  else { $pos_32 = "0";  }
      if ((isset($_POST['pos_33']))  && ($_POST['pos_33']))  { $pos_33 = "1"; }  else { $pos_33 = "0";  }
      if ((isset($_POST['pos_34']))  && ($_POST['pos_34']))  { $pos_34 = "1"; }  else { $pos_34 = "0";  }
      if ((isset($_POST['pos_35']))  && ($_POST['pos_35']))  { $pos_35 = "1"; }  else { $pos_35 = "0";  }
      if ((isset($_POST['pos_36']))  && ($_POST['pos_36']))  { $pos_36 = "1"; }  else { $pos_36 = "0";  }
      if ((isset($_POST['pos_37']))  && ($_POST['pos_37']))  { $pos_37 = "1"; }  else { $pos_37 = "0";  }
      if ((isset($_POST['pos_38']))  && ($_POST['pos_38']))  { $pos_38 = "1"; }  else { $pos_38 = "0";  }
      if ((isset($_POST['pos_39']))  && ($_POST['pos_39']))  { $pos_39 = "1"; }  else { $pos_39 = "0";  }
      if ((isset($_POST['pos_40']))  && ($_POST['pos_40']))  { $pos_40 = "1"; }  else { $pos_40 = "0";  }
      if ((isset($_POST['pos_41']))  && ($_POST['pos_41']))  { $pos_41 = "1"; }  else { $pos_41 = "0";  }
      if ((isset($_POST['pos_42']))  && ($_POST['pos_42']))  { $pos_42 = "1"; }  else { $pos_42 = "0";  }
      if ((isset($_POST['pos_43']))  && ($_POST['pos_43']))  { $pos_43 = "1"; }  else { $pos_43 = "0";  }
      if ((isset($_POST['pos_44']))  && ($_POST['pos_44']))  { $pos_44 = "1"; }  else { $pos_44 = "0";  }
      if ((isset($_POST['pos_45']))  && ($_POST['pos_45']))  { $pos_45 = "1"; }  else { $pos_45 = "0";  }
      if ((isset($_POST['pos_46']))  && ($_POST['pos_46']))  { $pos_46 = "1"; }  else { $pos_46 = "0";  }
      if ((isset($_POST['pos_47']))  && ($_POST['pos_47']))  { $pos_47 = "1"; }  else { $pos_47 = "0";  }
      if ((isset($_POST['pos_48']))  && ($_POST['pos_48']))  { $pos_48 = "1"; }  else { $pos_48 = "0";  }
      if ((isset($_POST['pos_49']))  && ($_POST['pos_49']))  { $pos_49 = "1"; }  else { $pos_49 = "0";  }
      if ((isset($_POST['pos_50']))  && ($_POST['pos_50']))  { $pos_50 = "1"; }  else { $pos_50 = "0";  }
      if ((isset($_POST['pos_51']))  && ($_POST['pos_51']))  { $pos_51 = "1"; }  else { $pos_51 = "0";  }
      if ((isset($_POST['pos_52']))  && ($_POST['pos_52']))  { $pos_52 = "1"; }  else { $pos_52 = "0";  }
      if ((isset($_POST['pos_53']))  && ($_POST['pos_53']))  { $pos_53 = "1"; }  else { $pos_53 = "0";  }
      if ((isset($_POST['pos_54']))  && ($_POST['pos_54']))  { $pos_54 = "1"; }  else { $pos_54 = "0";  }
      if ((isset($_POST['pos_55']))  && ($_POST['pos_55']))  { $pos_55 = "1"; }  else { $pos_55 = "0";  }
      if ((isset($_POST['pos_56']))  && ($_POST['pos_56']))  { $pos_56 = "1"; }  else { $pos_56 = "0";  }
      if ((isset($_POST['pos_57']))  && ($_POST['pos_57']))  { $pos_57 = "1"; }  else { $pos_57 = "0";  }
      if ((isset($_POST['pos_58']))  && ($_POST['pos_58']))  { $pos_58 = "1"; }  else { $pos_58 = "0";  }
      if ((isset($_POST['pos_59']))  && ($_POST['pos_59']))  { $pos_59 = "1"; }  else { $pos_59 = "0";  }
      if ((isset($_POST['pos_60']))  && ($_POST['pos_60']))  { $pos_60 = "1"; }  else { $pos_60 = "0";  }
      if ((isset($_POST['pos_61']))  && ($_POST['pos_61']))  { $pos_61 = "1"; }  else { $pos_61 = "0";  }
      if ((isset($_POST['pos_62']))  && ($_POST['pos_62']))  { $pos_62 = "1"; }  else { $pos_62 = "0";  }
      if ((isset($_POST['pos_63']))  && ($_POST['pos_63']))  { $pos_63 = "1"; }  else { $pos_63 = "0";  }
      if ((isset($_POST['pos_64']))  && ($_POST['pos_64']))  { $pos_64 = "1"; }  else { $pos_64 = "0";  }
      if ((isset($_POST['pos_65']))  && ($_POST['pos_65']))  { $pos_65 = "1"; }  else { $pos_65 = "0";  }
      if ((isset($_POST['pos_66']))  && ($_POST['pos_66']))  { $pos_66 = "1"; }  else { $pos_66 = "0";  }
      if ((isset($_POST['pos_67']))  && ($_POST['pos_67']))  { $pos_67 = "1"; }  else { $pos_67 = "0";  }
      if ((isset($_POST['pos_68']))  && ($_POST['pos_68']))  { $pos_68 = "1"; }  else { $pos_68 = "0";  }
      if ((isset($_POST['pos_69']))  && ($_POST['pos_69']))  { $pos_69 = "1"; }  else { $pos_69 = "0";  }
      if ((isset($_POST['pos_70']))  && ($_POST['pos_70']))  { $pos_70 = "1"; }  else { $pos_70 = "0";  }
      if ((isset($_POST['pos_71']))  && ($_POST['pos_71']))  { $pos_71 = "1"; }  else { $pos_71 = "0";  }
      if ((isset($_POST['pos_72']))  && ($_POST['pos_72']))  { $pos_72 = "1"; }  else { $pos_72 = "0";  }
      if ((isset($_POST['pos_73']))  && ($_POST['pos_73']))  { $pos_73 = "1"; }  else { $pos_73 = "0";  }
      if ((isset($_POST['pos_74']))  && ($_POST['pos_74']))  { $pos_74 = "1"; }  else { $pos_74 = "0";  }
      if ((isset($_POST['pos_75']))  && ($_POST['pos_75']))  { $pos_75 = "1"; }  else { $pos_75 = "0";  }
      if ((isset($_POST['pos_76']))  && ($_POST['pos_76']))  { $pos_76 = "1"; }  else { $pos_76 = "0";  }
      if ((isset($_POST['pos_77']))  && ($_POST['pos_77']))  { $pos_77 = "1"; }  else { $pos_77 = "0";  }
      if ((isset($_POST['pos_78']))  && ($_POST['pos_78']))  { $pos_78 = "1"; }  else { $pos_78 = "0";  }
      if ((isset($_POST['pos_79']))  && ($_POST['pos_79']))  { $pos_79 = "1"; }  else { $pos_79 = "0";  }
      if ((isset($_POST['pos_80']))  && ($_POST['pos_80']))  { $pos_80 = "1"; }  else { $pos_80 = "0";  }
      if ((isset($_POST['pos_81']))  && ($_POST['pos_81']))  { $pos_81 = "1"; }  else { $pos_81 = "0";  }
      if ((isset($_POST['pos_82']))  && ($_POST['pos_82']))  { $pos_82 = "1"; }  else { $pos_82 = "0";  }
      if ((isset($_POST['pos_83']))  && ($_POST['pos_83']))  { $pos_83 = "1"; }  else { $pos_83 = "0";  }
      if ((isset($_POST['pos_84']))  && ($_POST['pos_84']))  { $pos_84 = "1"; }  else { $pos_84 = "0";  }
      if ((isset($_POST['pos_85']))  && ($_POST['pos_85']))  { $pos_85 = "1"; }  else { $pos_85 = "0";  }
      if ((isset($_POST['pos_86']))  && ($_POST['pos_86']))  { $pos_86 = "1"; }  else { $pos_86 = "0";  }
      if ((isset($_POST['pos_87']))  && ($_POST['pos_87']))  { $pos_87 = "1"; }  else { $pos_87 = "0";  }
      if ((isset($_POST['pos_88']))  && ($_POST['pos_88']))  { $pos_88 = "1"; }  else { $pos_88 = "0";  }
      if ((isset($_POST['pos_89']))  && ($_POST['pos_89']))  { $pos_89 = "1"; }  else { $pos_89 = "0";  }
      if ((isset($_POST['pos_90']))  && ($_POST['pos_90']))  { $pos_90 = "1"; }  else { $pos_90 = "0";  }
      if ((isset($_POST['pos_91']))  && ($_POST['pos_91']))  { $pos_91 = "1"; }  else { $pos_91 = "0";  }
      if ((isset($_POST['pos_92']))  && ($_POST['pos_92']))  { $pos_92 = "1"; }  else { $pos_92 = "0";  }
      if ((isset($_POST['pos_93']))  && ($_POST['pos_93']))  { $pos_93 = "1"; }  else { $pos_93 = "0";  }
      if ((isset($_POST['pos_94']))  && ($_POST['pos_94']))  { $pos_94 = "1"; }  else { $pos_94 = "0";  }
      if ((isset($_POST['pos_95']))  && ($_POST['pos_95']))  { $pos_95 = "1"; }  else { $pos_95 = "0";  }
      if ((isset($_POST['pos_96']))  && ($_POST['pos_96']))  { $pos_96 = "1"; }  else { $pos_96 = "0";  }
      if ((isset($_POST['pos_97']))  && ($_POST['pos_97']))  { $pos_97 = "1"; }  else { $pos_97 = "0";  }
      if ((isset($_POST['pos_98']))  && ($_POST['pos_98']))  { $pos_98 = "1"; }  else { $pos_98 = "0";  }
      if ((isset($_POST['pos_99']))  && ($_POST['pos_99']))  { $pos_99 = "1"; }  else { $pos_99 = "0";  }

      $pos = array();
      for ($i=0; $i<=99; $i++) {
        $pos[$i] = '0';
      }
      $lista_permissoes_1= $this->selectListaPermissoes('0');
      foreach ($lista_permissoes_1 as $lp1) {
        $nr_posicao = $lp1['nr_posicao'];
        $campo = 'pos_'.$nr_posicao;

        if ((isset($_POST[$campo]))  && ($_POST[$campo]))  { $posicao = "1"; }  else { $posicao = "0";  }
        $pos[$nr_posicao] = $posicao;

        if ($posicao == '1') {
          $lista_permissoes_2= $this->selectListaPermissoes($lp1['cd_permissao']);
          foreach ($lista_permissoes_2 as $lp2) {
            $nr_posicao = $lp2['nr_posicao'];
            $campo = 'pos_'.$nr_posicao;
            if ((isset($_POST[$campo]))  && ($_POST[$campo]))  { $posicao = "1"; }  else { $posicao = "0";  }
            $pos[$nr_posicao] = $posicao;
          }
        }
      }

      if (($pos['31'] == '1') || ($pos['26'] == '1') || ($pos['7'] == '1') || ($pos['2'] == '1') || ($pos['14'] == '1') || ($pos['9'] == '1') || ($pos['23'] == '1') || ($pos['28'] == '1')) {
        $pos[1] = '1';
      }


      $permissao = '';
      foreach ($pos as $p) {
        $permissao .= $p;
      }

      return $permissao;                    
    }                      

//****************************BANCO DE DADOS************************************
    
    public function selectListaPermissoes($cd_agrupador) {
      $sql = "SELECT * ".
             "FROM life_permissoes ".
             "WHERE eh_ativo = '1' ";
      if ($cd_agrupador != '') {
        $sql.= "AND cd_agrupador = '$cd_agrupador' ";
      }
      $sql.= "ORDER BY nr_ordem, ds_permissao ";
      $result_id = @mysql_query($sql) or die ("Erro no banco de dados! - TABELA PERMISSÕES");
      $dados = array();
      while ($linha = mysql_fetch_assoc($result_id) ) {
          $dados[] = $linha;
      }
      return $dados;     
    }


  }
?>