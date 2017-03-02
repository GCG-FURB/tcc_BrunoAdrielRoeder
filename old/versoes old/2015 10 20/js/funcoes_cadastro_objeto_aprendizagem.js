<script>
<!--
<?php
  sajax_show_javascript();
?>

  function atualizandoCampoTipoArquivo(retorno) {
    document.getElementById('celula_arquivo_o_a').innerHTML = retorno;
  } 
  function atualizarCampoTipoArquivo() {
    var cd_formato = document.getElementById('cd_technical_format').value;
    var cd_formato_antigo = document.getElementById('cd_technical_format_original').value;
    var eh_setado = document.getElementById('eh_setado').value;
    var ds_location = document.getElementById('ds_technical_location_original').value;
    x_atualizarCampoTipoArquivo(cd_formato, cd_formato_antigo, eh_setado, ds_location, atualizandoCampoTipoArquivo);
  }    
  
      
  function atualizandoCampoSubAreasConhecimento(retorno) {
    document.getElementById('celula_sub_areas_conhecimento').innerHTML = retorno;
  }      
  function atualizarCampoSubAreasConhecimento() {
    var cd_general = document.getElementById('cd_general').value;
    var cd_area_conhecimento = document.getElementById('cd_general_coverage').value;
    x_atualizarCampoSubAreasConhecimento(cd_general, cd_area_conhecimento, atualizandoCampoSubAreasConhecimento);
  }    
    
  function detalhandoDadosObjetoAprendizagem(retorno) {
    document.getElementById('detalhamentoOAConteudo').innerHTML = retorno;
    document.getElementById('detalhamentoOA').style.display = 'block';
  }    
  function detalharDadosObjetoAprendizagem(cd_objeto_aprendizagem) {
    if (cd_objeto_aprendizagem > 0) {
      x_detalharDadosObjetoAprendizagem(cd_objeto_aprendizagem, detalhandoDadosObjetoAprendizagem);
    }  
  }
  
  function fecharDadosObjetoAprendizagem() {
    document.getElementById('detalhamentoOA').style.display = 'none';
  }
-->    
</script>