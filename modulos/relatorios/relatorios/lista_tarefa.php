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

global $Aplic, $cal_sdf, $ver_todos_projetos;
$mostrarNomeProjeto=nome_projeto($projeto_id);
$qnt=0;
$Aplic->carregarCalendarioJS();
$usuario_id=getParam($_REQUEST, 'usuario_id', '');
$grupo=getParam($_REQUEST, 'grupo', 'designado');
$fazer_relatorio=getParam($_REQUEST, 'fazer_relatorio', 0);
$usar_periodo=getParam($_REQUEST, 'usar_periodo', 0);
$log_pdf = 1;
$dias=getParam($_REQUEST, 'dias', 30);
$data_inicio= getParam($_REQUEST, 'reg_data_inicio', '');
$data_fim= getParam($_REQUEST, 'reg_data_fim', '');
$fazer_relatorio=getParam($_REQUEST, 'fazer_relatorio', 0);
$periodo_valor=getParam($_REQUEST, 'pvalor', 1);

if(!$data_inicio){
	$data_inicio = new CData();
	$data_inicio->subtrairIntervalo(new Data_Intervalo('14,0,0,0'));
	}
else $data_inicio = new CData($data_inicio); 

if(!$data_fim) $data_fim = new CData();
else $data_fim = new CData($data_fim);
	
$data_fim->setTime(23, 59, 59);

echo '<input type="hidden" name="fazer_relatorio" id="fazer_relatorio" value="" />';
$data = new CData();	
$titulo=($usar_periodo ? $data_inicio->format('%d/%m/%Y').' à '.$data_fim->format('%d/%m/%Y') : $data->format('%d/%m/%Y')).' - Lista de  '.$config['tarefas'].($usuario_id ? ' ao '.link_usuario($usuario_id): ' a todos '.$config['genero_usuario'].'s '.$config['usuarios']).($projeto_id && (!$ver_todos_projetos) ? ' n'.$config['genero_projeto'].' '.$config['projeto'].' '.$mostrarNomeProjeto : ' em tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']);
if (!$dialogo){
	$Aplic->salvarPosicao();
	echo '<table width="100%">';
	echo '<tr><td width="22">&nbsp;</td>';
	echo '<td align="center">';
	echo '<font size="4"><center><h3>'.$titulo.'</h3></center></font>';
	echo '</td>';
	echo '<td width="16">'.dica('Imprimir o relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'<a href="javascript: void(0);" onclick="env.target=\'popup\'; env.dialogo.value=1; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();"><img src="'.acharImagem('imprimir_p.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td>';
	echo '<td width="16">'.dica('Exportar o relatório para Pdf', 'Clique neste ícone '.imagem('pdf_2.png').' para exportar o relatório no formato Pdf.').'<a href="javascript: void(0);" onclick="env.target=\'\'; env.dialogo.value=1; env.sem_cabecalho.value=1; env.pdf.value=1; env.page_orientation.value=\'P\'; env.submit();"><img src="'.acharImagem('pdf_2.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td>';
	if ($Aplic->profissional) echo '<td width="16">'.dica('Exportar Link' , 'Clique neste ícone '.imagem('relatorio_externo_p.png').' para gerar um endereço web para visualização em ambiente externo do relatório.').'<a href="javascript: void(0)" onclick="exportar_link();"><img src="'.acharImagem('icones/relatorio_externo_p.png').'" border=0 /></a>'.dicaF().'</td>';
	echo '</tr>';
	echo '</table>';
	}
else if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $Aplic->usuario_cia;
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
	echo 	'<font size="4"><center>'.$titulo.'</center></font>';
	}
else echo '<font size="4"><center>'.$titulo.'</center></font>';

if (!$dialogo) echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding="4" border=0 width="100%" '.(!$dialogo ? 'class="std"' : '') .'>';
if (!$dialogo){ 
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<td style="white-space: nowrap">'.dica('Data Inicial', 'Digite ou escolha no calendário a data de início da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'De:'.dicaF().'<input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td>';
	echo '<td style="white-space: nowrap">'.dica('Data Final', 'Digite ou escolha no calendário a data final da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'Até:'.dicaF().'<input type="hidden" name="reg_data_fim" id="reg_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\');" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término da pesquisa das horas atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td>';
	echo '<td style="white-space: nowrap"> '.dica('Filtro por '.ucfirst($config['usuario']), 'Selecione na caixa à direita para qual '.$config['usuario'].' deseja visualizar os resultados.').ucfirst($config['usuario']).': '.dicaF().'<input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td>';
	echo '</tr></table></td></tr>';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap"><input type="checkbox" name="usar_periodo" id="usar_periodo" value="1" '.($usar_periodo ? 'checked="checked"' :'').' />'.dica('Usar o Período', 'Selecione esta caixa para exibir o resultado da pesquisa na faixa de tempo selecionada.').'<label for="usar_periodo">Usar o período</label>'.dicaF().'</td>';	
	echo '<td nowrap="nowratp"><input type="radio" id="responsavel" name="grupo" value="responsavel" '.($grupo == 'responsavel' ? "checked" : '').' />'.dica('Responsáveis', 'Serão considerados apenas os responsáveis pel'.$config['genero_tarefa'].'s '.$config['tarefas'].', sendo ignorado '.$config['genero_usuario'].'s '.$config['usuarios'].' designados por executá-las.').'<label for="responsavel">Responsáveis</label>'.dicaF().'<input type="radio" id="designado" name="grupo" value="designado" '.($grupo == 'designado' ? "checked" : '').' />'.dica('Designados', 'Serão considerados apenas os designados para executar '.$config['genero_tarefa'].'s '.$config['tarefas'].', sendo ignorado '.$config['genero_usuario'].'s '.$config['usuarios'].' responsáveis pelas mesmas.').'<label for="designado">Designados</label>'.dicaF().'</td>';
	echo '<td align="right" width="50%" style="white-space: nowrap">'.botao('exibir', 'Exibir', 'Exibir o resultado da pesquisa.','','env.fazer_relatorio.value=1; env.target=\'\'; env.dialogo.value=0; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();').'</td>';
	echo '</tr></table></td></tr>';
	}
if ($fazer_relatorio || $dialogo) {
	$sql = new BDConsulta();
	$sql->adTabela('tarefas', 't');	
	$sql->esqUnir('projetos', 'pr', 't.tarefa_projeto = pr.projeto_id');
	$sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
	$sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
	$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
	$sql->adOnde('pr.projeto_template=0 OR pr.projeto_template IS NULL');
	
	if ($projeto_plano_gestao) {
		$string_projeto_plano_gestao=projetos_plano_gestao($projeto_plano_gestao);
		if ($string_projeto_plano_gestao) $sql->adOnde('pr.projeto_id IN ('.$string_projeto_plano_gestao.')');
		}
	
	$sql->esqUnir('projeto_gestao', 'projeto_gestao', 'pr.projeto_id=projeto_gestao_projeto');
	
	if ($tarefa_id) $sql->adOnde('projeto_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($gestao_projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_gestao_tarefa');
		$sql->adOnde('projeto_gestao_semelhante IN ('.$gestao_projeto_id.') OR tarefas2.tarefa_projeto IN ('.$gestao_projeto_id.')');
		}
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
	elseif ($ssti_id) $sql->adOnde('projeto_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('projeto_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('projeto_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('projeto_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('projeto_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('projeto_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($filtro_criterio){
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		}
		
	if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta)	{
		$filtragem=array();
		if ($filtro_criterio) $filtragem[]='pratica_item_criterio IN ('.$filtro_criterio.')';
		if ($filtro_perspectiva) $filtragem[]='projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
		if ($filtro_tema) $filtragem[]='projeto_gestao_tema IN ('.$filtro_tema.')';
		if ($filtro_objetivo) $filtragem[]='projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
		if ($filtro_fator) $filtragem[]='projeto_gestao_fator IN ('.$filtro_fator.')';
		if ($filtro_estrategia) $filtragem[]='projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
		if ($filtro_meta) $filtragem[]='projeto_gestao_meta IN ('.$filtro_meta.')';
		if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
		}	

	
	
	if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
	if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
	if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
	elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
	if ($portfolio_pai){
		$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
		$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
		}
	if ($favorito_id){
		$sql->internoUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
		$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
		$sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
		}
	if($dept_id) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');
	if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');		
	
	if ($projetostatus && !$projeto_expandido){
		if ($Aplic->profissional){
			$projetostatus=explode(',',$projetostatus);
			if (in_array(-1,$projetostatus) && !in_array(-2,$projetostatus)) $sql->adOnde('projeto_ativo=1');
			if (in_array(-2,$projetostatus) && !in_array(-1,$projetostatus)) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			$qnt_status=0;
			$status_fim='';
			foreach($projetostatus as $status) if ($status > 0) $status_fim.=($qnt_status++ ? ',' : '').$status;
			if ($status_fim) $sql->adOnde('projeto_status IN ('.$status_fim.')');
			}
		else {
			if ($projetostatus == -1) $sql->adOnde('projeto_ativo=1');
			elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
			}
		}
	
	if($dept_id) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.')');	
	if ($cia_id  && !$lista_cias && !$favorito_id)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
	elseif ($lista_cias && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
	if ($projeto_tipo > -1)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
	if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
	if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
	if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
	if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
	if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
	if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
	if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
	if (trim($pesquisar_texto)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');
	$sql->adOnde('projeto_template = 0');
	if ($grupo=='designado') $sql->internoUnir('tarefa_designados', 'td','t.tarefa_id = td.tarefa_id');
	if ($usar_periodo) $sql->adOnde('(tarefa_inicio BETWEEN \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\' AND \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\' OR tarefa_fim BETWEEN \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\' AND \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\' OR (tarefa_inicio <= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\' AND tarefa_fim >= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\') )');
	$sql->adOnde('tarefa_fim IS NOT NULL');
	$sql->adOnde('tarefa_inicio IS NOT NULL');
	$sql->adOnde('tarefa_dinamica != 1');
	$sql->adOnde('tarefa_marco != 1');
	$sql->adOnde('tarefa_duracao  > 0');
	if ($grupo=='responsavel' && $usuario_id) $sql->adOnde('t.tarefa_dono = '.(int)$usuario_id);
	if ($grupo=='designado' && $usuario_id) $sql->adOnde('td.usuario_id = '.(int)$usuario_id);
	if ($projeto_id) $sql->adOnde('t.tarefa_projeto = '.(int)$projeto_id);
	if ($grupo!='designado' && !$usuario_id) $sql->adOnde('t.tarefa_dono IS NOT NULL AND t.tarefa_dono!=0');
	$sql->adCampo('projeto_nome, tarefa_projeto, t.tarefa_id, tarefa_dono, tarefa_duracao');
	$sql->adGrupo('tarefa_id');
	$sql->adOrdem('tarefa_projeto, tarefa_inicio');
	$lista=$sql->Lista();
	$sql->limpar();

	$antigo_projeto='';
	$soma_horas=0;
	$usuario_usado=array();
	echo '<tr><td colspan=20 align=center><table cellspacing=0 cellpadding=2 border=0 class="tbl1">';


	echo '<tr><th>Projeto</th><th>Tarefa</th><th>Responsável</th><th>Designados</th></tr>';
	foreach($lista as $linha){
		echo '<tr>';
		
	if ($linha['tarefa_projeto']!=$antigo_projeto){
			echo '<td>'.link_projeto($linha['tarefa_projeto']).'</td>';
			$antigo_projeto=$linha['tarefa_projeto'];
			}
		else echo '<td>'.$linha['projeto_nome'].'</td>';	
		
		echo '<td>'.link_tarefa($linha['tarefa_id']).'</td>';

		if (!in_array($linha['tarefa_dono'], $usuario_usado)){
			echo '<td>'.link_usuario($linha['tarefa_dono']).'</td>';
			$usuario_usado[]=$linha['tarefa_dono'];
			}
		else echo '<td>'.nome_usuario($linha['tarefa_dono']).'</td>';
		
		$sql->adTabela('tarefa_designados');	
		$sql->esqUnir('usuarios', 'u', 'u.usuario_id = tarefa_designados.usuario_id');
		$sql->esqUnir('contatos', 'con', 'u.usuario_contato = con.contato_id');
		$sql->adCampo(''.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, perc_designado, tarefa_designados.usuario_id');
		$sql->adOnde('tarefa_id='.(int)$linha['tarefa_id']);
		$designados=$sql->lista();
		$sql->limpar();
		
		echo '<td>';
		$qnt=0;
		foreach($designados as $campo){
			if ($qnt++) echo '<br>';
			if (!in_array($campo['usuario_id'], $usuario_usado)){
				echo link_usuario($campo['usuario_id']).' - '.$campo['perc_designado'].'%';
				$usuario_usado[]=$campo['usuario_id'];
				}
			else echo $campo['nome'].' - '.$campo['perc_designado'].'%';
			}
		if (!count($designados)) echo '&nbsp;';
		echo '</td>';
		
		echo'</tr>';
		}
	

	echo '</table></td></tr></table>';




	}
if (!$dialogo) echo estiloFundoCaixa();	
?>
<script type="text/javascript">

function exportar_link(tipo) {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link&dialogo=1&tipo=generico', null, window);
	}	
	

function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}
		
function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
 			} 
   	else{
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		} 
	else campo_data_real.value = '';
	}

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "reg_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "reg_data_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide(); 
  	}
  });
		
</script>
