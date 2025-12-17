<?php

include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);

$rodape_varivel=array();
$rodape_varivel['gerente']=(int)getParam($_REQUEST, 'gerente', 0);
$rodape_varivel['supervisor']=(int)getParam($_REQUEST, 'supervisor', 0);
$rodape_varivel['autoridade']=(int)getParam($_REQUEST, 'autoridade', 0);
$rodape_varivel['cliente']=(int)getParam($_REQUEST, 'cliente', 0);
$rodape_varivel['barra']=(int)getParam($_REQUEST, 'barra', 0);

if ($rodape_varivel['barra'] && $Aplic->profissional) {
	$barra=codigo_barra('projeto', $projeto_id, $baseline_id);
	if ($barra['cabecalho']) echo $barra['imagem'];
	}
	
$sql = new BDConsulta();
$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos', 'projetos');
$sql->adCampo('projeto_id, projeto_cia, projeto_nome, projeto_codigo');
$sql->adOnde('projeto_id = '.(int)$projeto_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$dados = $sql->Linha();
$sql->limpar();

$sql->adTabela('artefatos_tipo');
$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
$sql->adOnde('artefato_tipo_arquivo=\'matriz_indicadores.html\'');
$linha = $sql->linha();
$sql->limpar();
$campos = unserialize($linha['artefato_tipo_campos']);

$modelo= new Modelo;
$modelo->set_modelo_tipo(1);
foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
$modelo->set_modelo($tpl);


	
echo '<table align="left" cellspacing=0 cellpadding=0 width="100%"><tr><td>';
for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	} 
echo $tpl->exibir($modelo->edicao); 
echo '</td></tr>';
echo '</table>';

include_once BASE_DIR.'/modulos/projetos/projeto_impressao_funcao_pro.php';
echo impressao_rodape_projeto($rodape_varivel, $projeto_id, $baseline_id, 'font-family:Times New Roman, Times, serif; font-size:12pt;');

if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
?>