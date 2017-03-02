<script>
<!--
function valida(f) {

  if (f.termo_1.value == '') {
    alert('Informe o Termo de Pesquisa!');
    return false; 
  }

  var setado = false; 
  if(f.tabela_1.checked){
    setado = true;
  }
  if(f.campo_objetos_aprendizagem1.checked){
    setado = true;
  }
  for(i=0; i<=f.campo_service1.length-1; i++){
    if(f.campo_service1[i].checked){
      setado = true;
    }
  }  
  for(i=0; i<=f.campo_general1.length-1; i++){
    if(f.campo_general1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_technical1.length-1; i++){
    if(f.campo_technical1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_rights1.length-1; i++){
    if(f.campo_rights1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_lyfe_cycle1.length-1; i++){
    if(f.campo_lyfe_cycle1[i].checked){
      setado = true;
    }
  } 
  for(i=0; i<=f.campo_educational1.length-1; i++){
    if(f.campo_educational1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_annotation1.length-1; i++){
    if(f.campo_annotation1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_plataform_specific_features1.length-1; i++){
    if(f.campo_plataform_specific_features1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_segment_information_table1.length-1; i++){
    if(f.campo_segment_information_table1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_classification1.length-1; i++){
    if(f.campo_classification1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_acessibility1.length-1; i++){
    if(f.campo_acessibility1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_meta_metadata1.length-1; i++){
    if(f.campo_meta_metadata1[i].checked){
      setado = true;
    }
  }
  for(i=0; i<=f.campo_relation1.length-1; i++){
    if(f.campo_relation1[i].checked){
      setado = true;
    }
  }
  if (!setado) {
    alert('Marque a Seção na qual deseja realizar a pesquisa!');
    return false;
  }

  return true; 
}

function desmarcarCampos(campo) {

  if (campo != 'tabela_1')                  {  
    cadastro.tabela_1.checked = false;            
  }
  if (campo != 'campo_objetos_aprendizagem1')                  {  
    cadastro.campo_objetos_aprendizagem1.checked = false;    
  } 
  if (campo != 'campo_general1')                  {  
    for(i=0; i<=cadastro.campo_general1.length-1; i++){    
      cadastro.campo_general1[i].checked = false; 
    } 
  }               
  if (campo != 'campo_service1')                  {  
    for(i=0; i<=cadastro.campo_service1.length-1; i++){    
      cadastro.campo_service1[i].checked = false;   
    }  
  }
  if (campo != 'campo_technical1')                  {  
    for(i=0; i<=cadastro.campo_technical1.length-1; i++){    
      cadastro.campo_technical1[i].checked = false;   
    }  
  }
  if (campo != 'campo_rights1')                  {  
    for(i=0; i<=cadastro.campo_rights1.length-1; i++){    
      cadastro.campo_rights1[i].checked = false;   
    }  
  }
  if (campo != 'campo_lyfe_cycle1')                  {  
    for(i=0; i<=cadastro.campo_lyfe_cycle1.length-1; i++){    
      cadastro.campo_lyfe_cycle1[i].checked = false;   
    }  
  }
  if (campo != 'campo_educational1')                  {  
    for(i=0; i<=cadastro.campo_educational1.length-1; i++){    
      cadastro.campo_educational1[i].checked = false;   
    }  
  }
  if (campo != 'campo_annotation1')                  {  
    for(i=0; i<=cadastro.campo_annotation1.length-1; i++){    
      cadastro.campo_annotation1[i].checked = false;   
    }  
  }
  if (campo != 'campo_plataform_specific_features1')                  {  
    for(i=0; i<=cadastro.campo_plataform_specific_features1.length-1; i++){    
      cadastro.campo_plataform_specific_features1[i].checked = false;   
    }  
  }
  if (campo != 'campo_segment_information_table1')                  {  
    for(i=0; i<=cadastro.campo_segment_information_table1.length-1; i++){    
      cadastro.campo_segment_information_table1[i].checked = false;   
    }  
  }
  if (campo != 'campo_classification1')                  {  
    for(i=0; i<=cadastro.campo_classification1.length-1; i++){    
      cadastro.campo_classification1[i].checked = false;   
    }  
  }
  if (campo != 'campo_acessibility1')                  {  
    for(i=0; i<=cadastro.campo_acessibility1.length-1; i++){    
      cadastro.campo_acessibility1[i].checked = false;   
    }  
  }
  if (campo != 'campo_meta_metadata1')                  {  
    for(i=0; i<=cadastro.campo_meta_metadata1.length-1; i++){    
      cadastro.campo_meta_metadata1[i].checked = false;   
    }  
  }
  if (campo != 'campo_relation1')                  {  
    for(i=0; i<=cadastro.campo_relation1.length-1; i++){    
      cadastro.campo_relation1[i].checked = false;   
    }  
  }

/*
  if (campo != 'tabela_1')                                            {    document.getElementById('tabela_1').checked = false;                                 } else {   alert(campo); }           
  if (campo != 'campo_objetos_aprendizagem1')                         {    document.getElementById('campo_objetos_aprendizagem1').checked = false;              } else {   alert(campo); }
  if (campo != 'campo_service1')                                      {    document.getElementById('campo_service1').checked = false;                           } else {   alert(campo); }
  if (campo != 'campo_general1')                                      {    document.getElementById('campo_general1').checked = false;                           } else {   alert(campo); }
  if (campo != 'campo_technical1')                                    {    document.getElementById('campo_technical1').checked = false;                         } else {   alert(campo); }
  if (campo != 'campo_rights1')                                       {    document.getElementById('campo_rights1').checked = false;                            } else {   alert(campo); }
  if (campo != 'campo_lyfe_cycle1')                                   {    document.getElementById('campo_lyfe_cycle1').checked = false;                        } else {   alert(campo); }
  if (campo != 'campo_educational1')                                  {    document.getElementById('campo_educational1').checked = false;                       } else {   alert(campo); } 
  if (campo != 'campo_annotation1')                                   {    document.getElementById('campo_annotation1').checked = false;                        } else {   alert(campo); }
  if (campo != 'campo_plataform_specific_features1')                  {    document.getElementById('campo_plataform_specific_features1').checked = false;       } else {   alert(campo); }
  if (campo != 'campo_segment_information_table1')                    {    document.getElementById('campo_segment_information_table1').checked = false;         } else {   alert(campo); }
  if (campo != 'campo_classification1')                               {    document.getElementById('campo_classification1').checked = false;                    } else {   alert(campo); }
  if (campo != 'campo_acessibility1')                                 {    document.getElementById('campo_acessibility1').checked = false;                      } else {   alert(campo); }
  if (campo != 'campo_meta_metadata1')                                {    document.getElementById('campo_meta_metadata1').checked = false;                     } else {   alert(campo); }
  if (campo != 'campo_relation1')                                     {    document.getElementById('campo_relation1').checked = false;                          } else {   alert(campo); }
*/  
}
-->    
</script>