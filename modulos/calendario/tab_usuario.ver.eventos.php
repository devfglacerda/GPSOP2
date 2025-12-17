<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $projeto_id, $negar, $podeAcessar, $podeEditar, $config, $data_inicio, $data_fim, $este_dia, $evento_filtro, $evento_filtro_lista, $usuario_id;
require_once $Aplic->getClasseModulo('calendario');


if(!$usuario_id) $usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$data_inicio =  new CData();
if (isset($_REQUEST['usuario_id']))$usuario_id=getParam($_REQUEST, 'usuario_id', '');
$eventos = CEvento::getEventoParaPeriodo($data_inicio, null, ($evento_filtro ? $evento_filtro : 'todos'), $usuario_id);

$tipos = getSisValor('TipoEvento');
echo '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
echo '<tr><th>'.dica('Data - Hora', 'A data e hora do início e término do evento.').'Data'.dicaF().'</th>';
echo '<th>'.dica('Tipo', 'O tipo de evento.').'Tipo'.dicaF().'</th><th>'.dica('Evento', 'O nome do evento.').'Evento'.dicaF().'</th>';
if ($Aplic->profissional)echo '<th>'.dica('Relacionado', 'A quais partes do sistema o evento está relacionado.').'Relacionado'.dicaF().'</th>';
echo '</tr>';
$qnt_vista=0;

foreach ($eventos as $linha) {
	$qnt_vista++;
	echo '<tr>';

	echo '<td width="25%" style="white-space: nowrap">'.retorna_data($linha['evento_inicio']).'&nbsp;-&nbsp;'.retorna_data($linha['evento_fim']).'</td>';
	echo '<td width="10%" style="white-space: nowrap">'.imagem('icones/evento'.$linha['evento_tipo'].'.png', 'Tipo de Evento', 'Cada evento tem um gráfico diferente para facilitar a identificação visual.').'&nbsp;<b>'.$tipos[$linha['evento_tipo']].'</b></td>';
	echo '<td>'.link_evento($linha['evento_id']).'</td>';
	
	
	
	
	if ($Aplic->profissional){
		$sql = new BDConsulta;
		$sql->adTabela('evento_gestao');
		$sql->adCampo('evento_gestao.*');
		$sql->adOnde('evento_gestao_evento ='.(int)$linha['evento_id']);
		$sql->adOrdem('evento_gestao_ordem');
	  $lista = $sql->Lista();
	  $sql->limpar();
		echo '<td>';
		$qnt_gestao=0;
		foreach($lista as $gestao_data){
			
			if ($gestao_data['evento_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['evento_gestao_tarefa']);
			elseif ($gestao_data['evento_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['evento_gestao_projeto']);
			elseif ($gestao_data['evento_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['evento_gestao_pratica']);
			elseif ($gestao_data['evento_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['evento_gestao_acao']);
			elseif ($gestao_data['evento_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['evento_gestao_perspectiva']);
			elseif ($gestao_data['evento_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['evento_gestao_tema']);
			elseif ($gestao_data['evento_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['evento_gestao_objetivo']);
			elseif ($gestao_data['evento_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['evento_gestao_fator']);
			elseif ($gestao_data['evento_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['evento_gestao_estrategia']);
			elseif ($gestao_data['evento_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['evento_gestao_meta']);
			elseif ($gestao_data['evento_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['evento_gestao_canvas']);
			elseif ($gestao_data['evento_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['evento_gestao_risco']);
			elseif ($gestao_data['evento_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['evento_gestao_risco_resposta']);
			elseif ($gestao_data['evento_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['evento_gestao_indicador']);
			elseif ($gestao_data['evento_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['evento_gestao_calendario']);
			elseif ($gestao_data['evento_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['evento_gestao_monitoramento']);
			elseif ($gestao_data['evento_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['evento_gestao_ata']);
			elseif ($gestao_data['evento_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['evento_gestao_mswot']);
			elseif ($gestao_data['evento_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['evento_gestao_swot']);
			elseif ($gestao_data['evento_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['evento_gestao_operativo']);
			elseif ($gestao_data['evento_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['evento_gestao_instrumento']);
			elseif ($gestao_data['evento_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['evento_gestao_recurso']);
			elseif ($gestao_data['evento_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['evento_gestao_problema']);
			elseif ($gestao_data['evento_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['evento_gestao_demanda']);	
			elseif ($gestao_data['evento_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['evento_gestao_programa']);
			elseif ($gestao_data['evento_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['evento_gestao_licao']);
			
			elseif ($gestao_data['evento_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['evento_gestao_semelhante']);
			
			elseif ($gestao_data['evento_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['evento_gestao_link']);
			elseif ($gestao_data['evento_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['evento_gestao_avaliacao']);
			elseif ($gestao_data['evento_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['evento_gestao_tgn']);
			elseif ($gestao_data['evento_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['evento_gestao_brainstorm']);
			elseif ($gestao_data['evento_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['evento_gestao_gut']);
			elseif ($gestao_data['evento_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['evento_gestao_causa_efeito']);
			elseif ($gestao_data['evento_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['evento_gestao_arquivo']);
			elseif ($gestao_data['evento_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['evento_gestao_forum']);
			elseif ($gestao_data['evento_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['evento_gestao_checklist']);
			elseif ($gestao_data['evento_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['evento_gestao_agenda']);
			elseif ($gestao_data['evento_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['evento_gestao_agrupamento']);
			elseif ($gestao_data['evento_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['evento_gestao_patrocinador']);
			elseif ($gestao_data['evento_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['evento_gestao_template']);
			elseif ($gestao_data['evento_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['evento_gestao_painel']);
			elseif ($gestao_data['evento_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['evento_gestao_painel_odometro']);
			elseif ($gestao_data['evento_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['evento_gestao_painel_composicao']);		
			elseif ($gestao_data['evento_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['evento_gestao_tr']);	
			elseif ($gestao_data['evento_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['evento_gestao_me']);	
			elseif ($gestao_data['evento_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['evento_gestao_acao_item']);	
			elseif ($gestao_data['evento_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['evento_gestao_beneficio']);	
			elseif ($gestao_data['evento_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['evento_gestao_painel_slideshow']);	
			elseif ($gestao_data['evento_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['evento_gestao_projeto_viabilidade']);	
			elseif ($gestao_data['evento_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['evento_gestao_projeto_abertura']);	
			elseif ($gestao_data['evento_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['evento_gestao_plano_gestao']);
			}
		echo '</td>';
		}
	echo '</tr>';
	}
if (!$qnt_vista) echo '<tr><td colspan="3"><p>Nenhum evento encontrado.</p></td></tr>';	
echo '</table>';

?>