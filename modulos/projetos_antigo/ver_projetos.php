<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id;

$ordenar=getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta;


echo '<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th></th><th>Cor</th><th>Nome</th><th>Físico</th><th>'.ucfirst($config['organizacao']).'</th><th>Início</th><th>Término</th><th>'.ucfirst($config['gerente']).'</th>'.($Aplic->profissional ? '<th>Relacionad'.$config['genero_projeto'].'</th>' : '').'</tr>';
$sql->adTabela('projetos');
$sql->adCampo('projetos.*');

$sql->esqUnir('projeto_gestao','projeto_gestao','projeto_gestao_projeto = projeto.projeto_id');
if ($tarefa_id) $sql->adOnde('projeto_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id) $sql->adOnde('projeto_gestao_semelhante IN ('.$projeto_id.')');
elseif ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('projeto_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('projeto_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('projeto_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('projeto_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('projeto_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('projeto_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('projeto_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('projeto_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('projeto_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('projeto_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('projeto_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('projeto_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('projeto_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('projeto_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('projeto_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('projeto_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('projeto_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('projeto_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('projeto_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('projeto_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('projeto_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('projeto_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('projeto_gestao_plano_gestao IN ('.$pg_id.')');

		
$sql->adOrdem('projeto_nome');
$sql->adGrupo('projeto_id');
$projetos=$sql->lista();
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projetos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projetos\'');
$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
$diff = array_diff_key($exibir, $exibir2);
if($diff) $exibir = array_merge($exibir2, $diff);
else $exibir = $exibir2;

$projStatus = getSisValor('StatusProjeto');
$qnt=0;
foreach ($projetos as $linha){
	if ($Aplic->usuario_super_admin || permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])){
		$qnt++;
		$nenhum = false;
		$editar = permiteEditar($linha['projeto_acesso'], $linha['projeto_id']);
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		$data_fim_atual = intval($linha['projeto_fim_atualizado']) ? new CData($linha['projeto_fim_atualizado']) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		echo '<tr id="projeto_'.$linha['projeto_id'].'" onmouseover="iluminar_tds(this, true, '.$linha['projeto_id'].')" onmouseout="iluminar_tds(this, false, '.$linha['projeto_id'].')" onclick="selecionar_projeto('.$linha['projeto_id'].')">';
		
		echo '<td style="white-space: nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';

		
		if ($exibir['cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.link_projeto($linha["projeto_id"],'','','','','',true).'</td>';
		echo '<td width="45" align="right">'.number_format($linha['projeto_percentagem'], 2, ',', '.').'</td>';		
		echo '<td>'.link_cia($linha['projeto_cia']).'</td>';
		echo '<td width="80px" style="white-space: nowrap" align="center">'.($data_inicio ? $data_inicio->format("%d/%m/%Y") : '&nbsp;').'</td>';
		echo '<td width="80px" style="white-space: nowrap" align="center">'.($data_fim ? $data_fim->format("%d/%m/%Y") : '&nbsp;').'</td>';
		echo '<td>'.link_usuario($linha['projeto_responsavel'],'','','esquerda').'</td>';
		
		if ($Aplic->profissional){
			$sql->adTabela('projeto_gestao');
			$sql->adCampo('projeto_gestao.*');
			$sql->adOnde('projeto_gestao_projeto ='.(int)$linha['projeto_id']);	
			$sql->adOrdem('projeto_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['projeto_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_gestao_tarefa']);
					elseif ($gestao_data['projeto_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_gestao_projeto']);
					elseif ($gestao_data['projeto_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']);
					elseif ($gestao_data['projeto_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']);
					elseif ($gestao_data['projeto_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']);
					elseif ($gestao_data['projeto_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']);
					elseif ($gestao_data['projeto_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']);
					elseif ($gestao_data['projeto_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']);
					elseif ($gestao_data['projeto_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']);
					elseif ($gestao_data['projeto_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']);
					elseif ($gestao_data['projeto_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']);
					elseif ($gestao_data['projeto_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']);
					elseif ($gestao_data['projeto_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']);
					elseif ($gestao_data['projeto_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']);
					elseif ($gestao_data['projeto_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_gestao_calendario']);
					elseif ($gestao_data['projeto_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']);
					elseif ($gestao_data['projeto_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']);
					elseif ($gestao_data['projeto_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_gestao_mswot']);
					elseif ($gestao_data['projeto_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']);
					elseif ($gestao_data['projeto_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']);
					elseif ($gestao_data['projeto_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']);
					elseif ($gestao_data['projeto_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']);
					elseif ($gestao_data['projeto_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['projeto_gestao_problema']);
					elseif ($gestao_data['projeto_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']);	
					elseif ($gestao_data['projeto_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']);
					elseif ($gestao_data['projeto_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']);
					elseif ($gestao_data['projeto_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']);
					elseif ($gestao_data['projeto_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']);
					elseif ($gestao_data['projeto_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']);
					elseif ($gestao_data['projeto_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']);
					elseif ($gestao_data['projeto_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_gestao_brainstorm']);
					elseif ($gestao_data['projeto_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_gestao_gut']);
					elseif ($gestao_data['projeto_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_gestao_causa_efeito']);
					elseif ($gestao_data['projeto_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']);
					elseif ($gestao_data['projeto_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']);
					elseif ($gestao_data['projeto_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']);
					elseif ($gestao_data['projeto_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_gestao_agenda']);
					elseif ($gestao_data['projeto_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']);
					elseif ($gestao_data['projeto_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']);
					elseif ($gestao_data['projeto_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['projeto_gestao_template']);
					elseif ($gestao_data['projeto_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['projeto_gestao_painel']);
					elseif ($gestao_data['projeto_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']);
					elseif ($gestao_data['projeto_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']);		
					elseif ($gestao_data['projeto_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']);	
					elseif ($gestao_data['projeto_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']);	
					elseif ($gestao_data['projeto_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_gestao_acao_item']);	
					elseif ($gestao_data['projeto_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_gestao_beneficio']);	
					elseif ($gestao_data['projeto_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_gestao_painel_slideshow']);	
					elseif ($gestao_data['projeto_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['projeto_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_gestao_projeto_abertura']);	
					elseif ($gestao_data['projeto_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_gestao_plano_gestao']);	
					}
				}	
			echo '</td>';	
			}	
		echo '</tr>';
		}
	}
if (!$qnt) echo '<tr><td colspan=20>Nenhum'.($config['genero_projeto']=='a' ? 'a' :'').' '.$config['projeto'].' vinculad'.$config['genero_projeto'].'</td></tr>';	
echo '</table>';