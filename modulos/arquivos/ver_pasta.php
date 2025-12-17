<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo_pasta é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo_pasta diretamente.');

$arquivo_pasta_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$pasta_id = intval(getParam($_REQUEST, 'pasta_id', 0));
$arquivo_pasta_id = intval(getParam($_REQUEST, 'arquivo_pasta_id', 0));
if (isset($_REQUEST['tab'])) $Aplic->setEstado('VePastaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VePastaTab') !== null ? $Aplic->getEstado('VePastaTab') : 0;
$sql = new BDConsulta;


$msg = '';
$obj = new CPastaArquivo();
$podeExcluir = $obj->podeExcluir($msg, $arquivo_pasta_id);
if ($arquivo_pasta_id > 0 && !$obj->load($arquivo_pasta_id)) {
	$Aplic->setMsg('Pasta');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=arquivos');
	}
	
if (!permiteAcessarPasta($obj->arquivo_pasta_acesso,$arquivo_pasta_id) || !$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$editar=permiteEditarPasta($obj->arquivo_pasta_acesso, $arquivo_pasta_id);	

$podeEditarTudo=($obj->arquivo_pasta_acesso>=5 ? $editar && (in_array($obj->arquivo_pasta_dono, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

echo '<form name="env"  method="post">';
echo '<input type="hidden" name="m" value="arquivos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_pasta_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="arquivo_pasta_id" value="'.$arquivo_pasta_id.'" />';



if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Visualizar Pasta', 'pasta_grande.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	}


if (!$dialogo){
	echo estiloTopoCaixa();
	$Aplic->salvarPosicao();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #212121" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Pastas','Clique neste botão para visualizar a lista de pastas.').'Lista de Pastas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=pasta_lista\");");
	
	
	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Nova Pasta', 'Criar uma nova pasta.').'Nova Pasta'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar_pasta\");");
	if ($podeEditar && $editar) {	
		if ($Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_pasta=".$arquivo_pasta_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($podeEditarTudo && $podeEditar) $km->Add("acao","acao_editar",dica('Editar','Editar os detalhes desta pasta.').'Editar Pasta'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar_pasta&arquivo_pasta_id=".$arquivo_pasta_id."\");");
	if ($podeEditarTudo && $podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir desta pasta.').'Excluir Pasta'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a pasta.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&arquivo_pasta_id=".$arquivo_pasta_id."\");");
	if ($Aplic->profissional && $podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ícone '.imagem('icones/exporta_p.png').' para criar um endereço web para visualização em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");	

	echo $km->Render();
	echo '</td></tr></table>';	
	}



if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $obj->arquivo_pasta_cia;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>Pasta</center></font>';
	}

echo '<table cellpadding=1 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%" >';


echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->arquivo_pasta_cor.'" colspan="2"><font color="'.melhorCor($obj->arquivo_pasta_cor).'"><b>'.$obj->arquivo_pasta_nome.'<b></font></td></tr>';

$sql->adTabela('arquivo_pasta_dept');
$sql->adCampo('arquivo_pasta_dept_dept');
$sql->adOnde('arquivo_pasta_dept_pasta ='.(int)$arquivo_pasta_id);
$departamentos = $sql->Lista();
$sql->limpar();


$sql->adTabela('arquivo_pasta_usuario');
$sql->adCampo('arquivo_pasta_usuario_usuario');
$sql->adOnde('arquivo_pasta_usuario_pasta = '.(int)$arquivo_pasta_id);
$designados = $sql->carregarColuna();
$sql->limpar();


if ($obj->arquivo_pasta_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->arquivo_pasta_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('arquivo_pasta_cia');
	$sql->adCampo('arquivo_pasta_cia_cia');
	$sql->adOnde('arquivo_pasta_cia_pasta = '.(int)$arquivo_pasta_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}


if ($obj->arquivo_pasta_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este arquivo_pasta.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->arquivo_pasta_dept).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['arquivo_pasta_dept_dept']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['arquivo_pasta_dept_dept']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com este arquivo_pasta.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

	
if ($obj->arquivo_pasta_dono) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Responsável pelo Pasta', ucfirst($config['usuario']).' responsável por gerenciar o arquivo_pasta.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->arquivo_pasta_dono, '','','esquerda').'</td></tr>';		
$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($designados[0], '','','esquerda');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i], '','','esquerda').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';


$sql->adTabela('arquivo_pasta_gestao');
$sql->adCampo('arquivo_pasta_gestao.*');
$sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$arquivo_pasta_id);	
$sql->adOrdem('arquivo_pasta_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionada', 'A que área a pasta está relacionada.').'Relacionada:'.dicaF().'</td></td><td class="realce">';	
	foreach($lista as $gestao_data){
		if ($gestao_data['arquivo_pasta_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_pasta_gestao_tarefa']);
		elseif ($gestao_data['arquivo_pasta_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_pasta_gestao_projeto']);
		elseif ($gestao_data['arquivo_pasta_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_pasta_gestao_pratica']);
		elseif ($gestao_data['arquivo_pasta_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_pasta_gestao_acao']);
		elseif ($gestao_data['arquivo_pasta_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_pasta_gestao_perspectiva']);
		elseif ($gestao_data['arquivo_pasta_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_pasta_gestao_tema']);
		elseif ($gestao_data['arquivo_pasta_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_pasta_gestao_objetivo']);
		elseif ($gestao_data['arquivo_pasta_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_pasta_gestao_fator']);
		elseif ($gestao_data['arquivo_pasta_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_pasta_gestao_estrategia']);
		elseif ($gestao_data['arquivo_pasta_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_pasta_gestao_meta']);
		elseif ($gestao_data['arquivo_pasta_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_pasta_gestao_canvas']);
		elseif ($gestao_data['arquivo_pasta_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_pasta_gestao_risco']);
		elseif ($gestao_data['arquivo_pasta_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_pasta_gestao_risco_resposta']);
		elseif ($gestao_data['arquivo_pasta_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_pasta_gestao_indicador']);
		elseif ($gestao_data['arquivo_pasta_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['arquivo_pasta_gestao_calendario']);
		elseif ($gestao_data['arquivo_pasta_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_pasta_gestao_monitoramento']);
		elseif ($gestao_data['arquivo_pasta_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['arquivo_pasta_gestao_ata']);
		elseif ($gestao_data['arquivo_pasta_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['arquivo_pasta_gestao_mswot']);
		elseif ($gestao_data['arquivo_pasta_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['arquivo_pasta_gestao_swot']);
		elseif ($gestao_data['arquivo_pasta_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_pasta_gestao_operativo']);
		elseif ($gestao_data['arquivo_pasta_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_pasta_gestao_instrumento']);
		elseif ($gestao_data['arquivo_pasta_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_pasta_gestao_recurso']);
		elseif ($gestao_data['arquivo_pasta_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['arquivo_pasta_gestao_problema']);
		elseif ($gestao_data['arquivo_pasta_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_pasta_gestao_demanda']);	
		elseif ($gestao_data['arquivo_pasta_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_pasta_gestao_programa']);
		elseif ($gestao_data['arquivo_pasta_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_pasta_gestao_licao']);
		elseif ($gestao_data['arquivo_pasta_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_pasta_gestao_evento']);
		elseif ($gestao_data['arquivo_pasta_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['arquivo_pasta_gestao_link']);
		elseif ($gestao_data['arquivo_pasta_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_pasta_gestao_avaliacao']);
		elseif ($gestao_data['arquivo_pasta_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_pasta_gestao_tgn']);
		elseif ($gestao_data['arquivo_pasta_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['arquivo_pasta_gestao_brainstorm']);
		elseif ($gestao_data['arquivo_pasta_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['arquivo_pasta_gestao_gut']);
		elseif ($gestao_data['arquivo_pasta_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['arquivo_pasta_gestao_causa_efeito']);
		elseif ($gestao_data['arquivo_pasta_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['arquivo_pasta_gestao_arquivo']);
		elseif ($gestao_data['arquivo_pasta_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_pasta_gestao_forum']);
		elseif ($gestao_data['arquivo_pasta_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_pasta_gestao_checklist']);
		elseif ($gestao_data['arquivo_pasta_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['arquivo_pasta_gestao_agenda']);
		elseif ($gestao_data['arquivo_pasta_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_pasta_gestao_agrupamento']);
		elseif ($gestao_data['arquivo_pasta_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_pasta_gestao_patrocinador']);
		elseif ($gestao_data['arquivo_pasta_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['arquivo_pasta_gestao_template']);
		elseif ($gestao_data['arquivo_pasta_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['arquivo_pasta_gestao_painel']);
		elseif ($gestao_data['arquivo_pasta_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_pasta_gestao_painel_odometro']);
		elseif ($gestao_data['arquivo_pasta_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['arquivo_pasta_gestao_painel_composicao']);		
		elseif ($gestao_data['arquivo_pasta_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_pasta_gestao_tr']);	
		elseif ($gestao_data['arquivo_pasta_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['arquivo_pasta_gestao_me']);	
		elseif ($gestao_data['arquivo_pasta_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['arquivo_pasta_gestao_acao_item']);	
		elseif ($gestao_data['arquivo_pasta_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['arquivo_pasta_gestao_beneficio']);	
		elseif ($gestao_data['arquivo_pasta_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['arquivo_pasta_gestao_painel_slideshow']);	
		elseif ($gestao_data['arquivo_pasta_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['arquivo_pasta_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['arquivo_pasta_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['arquivo_pasta_gestao_projeto_abertura']);	
		elseif ($gestao_data['arquivo_pasta_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['arquivo_pasta_gestao_plano_gestao']);	
		elseif ($gestao_data['arquivo_pasta_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['arquivo_pasta_gestao_ssti']);
		elseif ($gestao_data['arquivo_pasta_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['arquivo_pasta_gestao_laudo']);
		elseif ($gestao_data['arquivo_pasta_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['arquivo_pasta_gestao_trelo']);
		elseif ($gestao_data['arquivo_pasta_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['arquivo_pasta_gestao_trelo_cartao']);
		elseif ($gestao_data['arquivo_pasta_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['arquivo_pasta_gestao_pdcl']);
		elseif ($gestao_data['arquivo_pasta_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['arquivo_pasta_gestao_pdcl_item']);
		
		elseif ($gestao_data['arquivo_pasta_gestao_semelhante'])  echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pasta_p.png').link_pasta($gestao_data['arquivo_pasta_gestao_semelhante']);	
		elseif ($gestao_data['arquivo_pasta_gestao_usuario'])  echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/usuario_p.gif').link_usuario($gestao_data['arquivo_pasta_gestao_usuario']);	

		
		
		}
	echo '</td></tr>';
	}	
	
if ($obj->arquivo_pasta_descricao) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Descrição', 'A descrição sobre o arquivo_pasta.').'Descrição:'.dicaF().'</td><td class="realce" width="100%">'.$obj->arquivo_pasta_descricao.'</td></tr>';
echo '<tr><td align="right">'.dica('Ativo', 'Indica se arquivo_pasta ainda está ativo.').'Ativo:'.dicaF().'</td><td class="realce" width="100%">'.($obj->arquivo_pasta_ativo  ? 'Sim' : 'Não').'</td></tr>';	
if (!$dialogo && !$Aplic->profissional) echo '<tr><td>'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
echo '</form></table>';

if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';


if (!$dialogo && $Aplic->profissional) {
	$caixaTab = new CTabBox('m=arquivos&a=ver_pasta&arquivo_pasta_id='.$arquivo_pasta_id, '', $tab);
	$qnt_aba=0;
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) {

			$sql->adTabela('arquivo');
			$sql->adCampo('count(arquivo_id)');
			$sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/arquivos/index_tabela', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
			}
		}

	if ($qnt_aba) {
		$caixaTab->mostrar('','','','',true);
		echo estiloFundoCaixa('','', $tab);
		}
	}

?>
<script type="text/javascript">
	
function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link&dialogo=1&tipo=generico', null, window);
	}		
	
function enviarDados() {
	var f = document.env;
	f.submit();
	}
	
function excluir() {
	if (confirm( "Tem certeza de que deseja excluir esta pasta?")) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}
	

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>