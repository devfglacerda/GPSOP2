<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $projeto_tipos, $podeEditar, $ordemDir, $ordenarPor, $projeto_id, $responsavel, $supervisor, $autoridade, $cliente, $nao_apenas_superiores,$lista_cias, $projeto_expandido, $favorito_id, $dept_id, $lista_depts, $pesquisar_texto, $projeto_tipo,  $projetostatus, $Aplic, $cia_id, $tab, $projStatus, $projetos_status, $projeto_setor, $projeto_segmento, $projeto_intervencao, $projeto_tipo_intervencao, $estado_sigla, $municipio_id, $dialogo, $filtro_area, $filtro_criterio, $filtro_opcao, $filtro_prioridade, $filtro_perspectiva, $filtro_tema, $filtro_objetivo, $filtro_fator, $filtro_estrategia, $filtro_meta, $filtro_canvas, $campos_extras,
			$pg_perspectiva_id,
			$tema_id,
			$pg_objetivo_estrategico_id,
			$pg_fator_critico_id,
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
			$xpg_totalregistros_projetos,
      $xpg_totalregistros_recebidos;
$df = '%d/%m/%Y';
$PrioridadeProjeto = getSisValor('PrioridadeProjeto');
$projetoStatus = getSisValor('StatusProjeto');

$sp_obj = new CProjeto();
$sp_obj->load($projeto_id);
$original_projeto_id = $sp_obj->projeto_superior_original;
$projetosEstruturados = getProjetosEstruturados($original_projeto_id);
echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" ><tr><td><table border=0 cellpadding="5" cellspacing="1" bgcolor="black"><tr>';
echo '<th width="12">&nbsp;</th>';
echo '<th>'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nome dos mesmos.').ucfirst($config['projeto']). dicaF().'</th>';



echo '<th>'.dica('Prioridade', 'A prioridade para fins de filtragem.').'P'.dicaF().'</th>';
echo '<th>'.dica(ucfirst($config['organizacao']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_organizacao'].' '.$config['organizacao'].'.').$config['organizacao'].dicaF().'</th>';
echo '<th>'.dica('Responsável', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_usuario'].'s '.$config['usuarios'].' responsáveis pelos mesmos').'Responsável'.dicaF().'</th>';
echo '<th>'.dica('Início', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de início.').'Início'.dicaF().'</th>';
echo '<th>'.dica('Término', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de término.').'Término'.dicaF().'</th>';


echo '<th>'.dica('Fiscal', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_usuario'].'s '.$config['usuarios'].' responsáveis pelos mesmos').'Fiscal'.dicaF().'</th>';

echo '<th>'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Visualizar os Status d'.$config['genero_projeto'].'s '.$config['projetos'].', que podem ser sete a saber: <ul><li>Não definido - caso em que ainda não há muitos dados concretos sobre o mesmo, ou que ainda não tem um responsável efetivo.</li><li>Proposto - quando já tem um responsável efetivo definido, porem não iniciou ainda os trabalhos.</li><li>Em Planejamento - quando não foi iniciado nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', porem a equipe designada já estão realizando trabalhos prepratórios</li><li>Em andamento - quando está em execução, com ao menos algum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' com mais de 0% realizado e que não esteja em <b>espera</b>.</li><li>Em Espera - quando '.$config['genero_projeto'].' '.$config['projeto'].' iniciou, mas por algum motivo incontra-se interrompido. A interrupção pode ser permanente ou provisória.</li><li>Completado - quando todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' atingiram 100% executadas.</li><li>Modelo - quando '.$config['genero_projeto'].' '.$config['projeto'].' e su'.$config['genero_tarefa'].'s '.$config['tarefas'].' sirvam apenas de referência, para outr'.$config['genero_projeto'].'s '.$config['projetos'].', não sendo um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' real.</li></ul>.').'Status'.dicaF().'</th>';




echo '</tr>';
$s = '';
$x=0;
foreach ($st_projetos_arr as $projeto) {
	$linha = $projeto[0];
	$nivel = $projeto[1];
	if ($linha['projeto_id']) {
		$s_projeto = new CProjeto();
		$s_projeto->load($linha['projeto_id']);
		$data_inicio = intval($s_projeto->projeto_data_inicio) ? new CData($s_projeto->projeto_data_inicio) : null;
		$data_fim = intval($s_projeto->projeto_data_fim) ? new CData($s_projeto->projeto_data_fim) : null;
		$data_fim_atual = intval($s_projeto->projeto_fim_atualizado) ? new CData($s_projeto->projeto_fim_atualizado) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		$x++;
		$linha_class = ($x % 2) ? 'style="background:#fff;"' : 'style="background:#f0f0f0;"';
		$linha_classr = ($x % 2) ? 'style="background:#fff;text-align:right;"' : 'style="background:#f0f0f0;text-align:right;"';
		$s .= '<tr><td '.$linha_class.' align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id='.$linha['projeto_id'].'\');">'.dica('Editar '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').'<img src="'.acharImagem('icones/editar.gif').'" border=0 />'.dicaF().'</a></td>';
		if ($nivel) $sd = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($nivel - 1)).imagem('icones/subnivel.gif').'&nbsp;'.link_projeto($linha['projeto_id']);
		else $sd = link_projeto($linha['projeto_id']);
		$s .= '<td '.$linha_class.' align="left">'.$sd.'</td>';
		$s .= '<td '.$linha_class.' align="center">'.prioridade($s_projeto->projeto_prioridade, true).'</td>';
		$s .= '<td '.$linha_class.' align="center">'.link_cia($s_projeto->projeto_cia).'</td>';
		$s .= '<td '.$linha_class.' align="center">'.link_usuario($s_projeto->projeto_responsavel).'</td>';
		$s .= '<td '.$linha_class.' align="center">'.($data_inicio ? $data_inicio->format($df):'&nbsp;').'</td>';
		$s .= '<td '.$linha_class.' align="center">'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td>';
		
			$s .= '<td '.$linha_class.' align="center">'.link_usuario($s_projeto->projeto_cliente).'</td>';
			
		$s .= '<td '.$linha_class.' align="center">'.$projetoStatus[$s_projeto->projeto_status].'</td></tr>';

		
		}
	}
echo $s;
/*
echo '</table><table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td colspan="20">&nbsp;</td></tr><tr><td align="left" colspan="20">';
$src = "?m=projetos&a=ver_sub_projetos_gantt&sem_cabecalho=1&mostrarLegendas=1&proFiltro=&mostrarInativo=1mostrarTodoGantt=1&original_projeto_id=$original_projeto_id&width=' + ((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<script>document.write('<img src=\"$src\">')</script>";*/


echo '</td></tr></table></td></tr></table><br>';
?>
