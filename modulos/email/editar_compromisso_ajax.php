<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);




require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

function calcular_duracao($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$resultado=str_replace('.', ',',$resultado);
	$objResposta->assign("agenda_duracao","value", $resultado);
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao");		


function data_final_periodo($inicio, $dias, $cia_id){
	$dias=float_americano($dias);
	$horas=abs($dias*config('horas_trab_diario'));
	$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id);
	$data=new CData($data_final);
	$objResposta = new xajaxResponse();
	$objResposta->assign("oculto_data_fim","value", $data->format("%Y-%m-%d"));
	$objResposta->assign("data_fim","value", $data->format("%d/%m/%Y"));
	$objResposta->assign("fim_hora","value", $data->format("%H"));
	$objResposta->assign("fim_minuto","value", $data->format("%M"));
	return $objResposta;
	}	
$xajax->registerFunction("data_final_periodo");	

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");









function mudar_usuario_ajax($cia_id=null, $usuario_id=null, $campo=null, $posicao=null, $script=null, $pesquisa=null, $grupo_id=null){
	global $Aplic;
	$pesquisa=previnirXSS(utf8_decode($pesquisa));
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$saida=mudar_usuario_em_dept(true, $cia_id, 0, $campo, $posicao, $script, null, null, null, null, $pesquisa, null, null, $grupo_id);
	$objResposta = New xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");	


function mudar_usuario_grupo_ajax($grupo_id=null, $pesquisar=null){
	global $Aplic, $config;
	$pesquisar=previnirXSS(utf8_decode($pesquisar));
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('grupo_usuario','grupo_usuario','grupo_usuario_usuario=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
	if ($grupo_id > 0) $sql->adOnde('grupo_usuario_grupo='.(int)$grupo_id);
	elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
  $sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');	
	$usuarios = $sql->Lista();
	$sql->limpar();

	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';

	$saida.='</select>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_grupo_ajax");	











function mudar_posicao_gestao($ordem, $agenda_gestao_id, $direcao, $agenda_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $agenda_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('agenda_gestao');
		$sql->adOnde('agenda_gestao_id != '.(int)$agenda_gestao_id);
		if ($uuid) $sql->adOnde('agenda_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('agenda_gestao_agenda = '.(int)$agenda_id);
		$sql->adOrdem('agenda_gestao_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('agenda_gestao');
			$sql->adAtualizar('agenda_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('agenda_gestao_id = '.(int)$agenda_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('agenda_gestao');
					$sql->adAtualizar('agenda_gestao_ordem', $idx);
					$sql->adOnde('agenda_gestao_id = '.(int)$acao['agenda_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('agenda_gestao');
					$sql->adAtualizar('agenda_gestao_ordem', $idx + 1);
					$sql->adOnde('agenda_gestao_id = '.(int)$acao['agenda_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($agenda_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$agenda_id=0, 
	$uuid='',  
	
	$agenda_projeto=null,
	$agenda_tarefa=null,
	$agenda_perspectiva=null,
	$agenda_tema=null,
	$agenda_objetivo=null,
	$agenda_fator=null,
	$agenda_estrategia=null,
	$agenda_meta=null,
	$agenda_pratica=null,
	$agenda_acao=null,
	$agenda_canvas=null,
	$agenda_risco=null,
	$agenda_risco_resposta=null,
	$agenda_indicador=null,
	$agenda_calendario=null,
	$agenda_monitoramento=null,
	$agenda_ata=null,
	$agenda_mswot=null,
	$agenda_swot=null,
	$agenda_operativo=null,
	$agenda_instrumento=null,
	$agenda_recurso=null,
	$agenda_problema=null,
	$agenda_demanda=null,
	$agenda_programa=null,
	$agenda_licao=null,
	$agenda_evento=null,
	$agenda_link=null,
	$agenda_avaliacao=null,
	$agenda_tgn=null,
	$agenda_brainstorm=null,
	$agenda_gut=null,
	$agenda_causa_efeito=null,
	$agenda_arquivo=null,
	$agenda_forum=null,
	$agenda_checklist=null,
	$agenda_agenda=null,
	$agenda_agrupamento=null,
	$agenda_patrocinador=null,
	$agenda_template=null,
	$agenda_painel=null,
	$agenda_painel_odometro=null,
	$agenda_painel_composicao=null,
	$agenda_tr=null,
	$agenda_me=null,
	$agenda_acao_item=null,
	$agenda_beneficio=null,
	$agenda_painel_slideshow=null,
	$agenda_projeto_viabilidade=null,
	$agenda_projeto_abertura=null,
	$agenda_plano_gestao=null
	)
	{
	if (
		$agenda_projeto || 
		$agenda_tarefa || 
		$agenda_perspectiva || 
		$agenda_tema || 
		$agenda_objetivo || 
		$agenda_fator || 
		$agenda_estrategia || 
		$agenda_meta || 
		$agenda_pratica || 
		$agenda_acao || 
		$agenda_canvas || 
		$agenda_risco || 
		$agenda_risco_resposta || 
		$agenda_indicador || 
		$agenda_calendario || 
		$agenda_monitoramento || 
		$agenda_ata || 
		$agenda_mswot || 
		$agenda_swot || 
		$agenda_operativo || 
		$agenda_instrumento || 
		$agenda_recurso || 
		$agenda_problema || 
		$agenda_demanda || 
		$agenda_programa || 
		$agenda_licao || 
		$agenda_evento || 
		$agenda_link || 
		$agenda_avaliacao || 
		$agenda_tgn || 
		$agenda_brainstorm || 
		$agenda_gut || 
		$agenda_causa_efeito || 
		$agenda_arquivo || 
		$agenda_forum || 
		$agenda_checklist || 
		$agenda_agenda || 
		$agenda_agrupamento || 
		$agenda_patrocinador || 
		$agenda_template || 
		$agenda_painel || 
		$agenda_painel_odometro || 
		$agenda_painel_composicao || 
		$agenda_tr || 
		$agenda_me || 
		$agenda_acao_item || 
		$agenda_beneficio || 
		$agenda_painel_slideshow || 
		$agenda_projeto_viabilidade || 
		$agenda_projeto_abertura || 
		$agenda_plano_gestao
		){
		global $Aplic;
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('agenda_gestao');
			if ($uuid) $sql->adOnde('agenda_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('agenda_gestao_agenda ='.(int)$agenda_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('agenda_gestao');
		$sql->adCampo('count(agenda_gestao_id)');
		if ($uuid) $sql->adOnde('agenda_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('agenda_gestao_agenda ='.(int)$agenda_id);	
		if ($agenda_tarefa) $sql->adOnde('agenda_gestao_tarefa='.(int)$agenda_tarefa);
		elseif ($agenda_projeto) $sql->adOnde('agenda_gestao_projeto='.(int)$agenda_projeto);
		elseif ($agenda_perspectiva) $sql->adOnde('agenda_gestao_perspectiva='.(int)$agenda_perspectiva);
		elseif ($agenda_tema) $sql->adOnde('agenda_gestao_tema='.(int)$agenda_tema);
		elseif ($agenda_objetivo) $sql->adOnde('agenda_gestao_objetivo='.(int)$agenda_objetivo);
		elseif ($agenda_fator) $sql->adOnde('agenda_gestao_fator='.(int)$agenda_fator);
		elseif ($agenda_estrategia) $sql->adOnde('agenda_gestao_estrategia='.(int)$agenda_estrategia);
		elseif ($agenda_acao) $sql->adOnde('agenda_gestao_acao='.(int)$agenda_acao);
		elseif ($agenda_pratica) $sql->adOnde('agenda_gestao_pratica='.(int)$agenda_pratica);
		elseif ($agenda_meta) $sql->adOnde('agenda_gestao_meta='.(int)$agenda_meta);
		elseif ($agenda_canvas) $sql->adOnde('agenda_gestao_canvas='.(int)$agenda_canvas);
		elseif ($agenda_risco) $sql->adOnde('agenda_gestao_risco='.(int)$agenda_risco);
		elseif ($agenda_risco_resposta) $sql->adOnde('agenda_gestao_risco_resposta='.(int)$agenda_risco_resposta);
		elseif ($agenda_indicador) $sql->adOnde('agenda_gestao_indicador='.(int)$agenda_indicador);
		elseif ($agenda_calendario) $sql->adOnde('agenda_gestao_calendario='.(int)$agenda_calendario);
		elseif ($agenda_monitoramento) $sql->adOnde('agenda_gestao_monitoramento='.(int)$agenda_monitoramento);
		elseif ($agenda_ata) $sql->adOnde('agenda_gestao_ata='.(int)$agenda_ata);
		elseif ($agenda_mswot) $sql->adOnde('agenda_gestao_mswot='.(int)$agenda_mswot);
		elseif ($agenda_swot) $sql->adOnde('agenda_gestao_swot='.(int)$agenda_swot);
		elseif ($agenda_operativo) $sql->adOnde('agenda_gestao_operativo='.(int)$agenda_operativo);
		elseif ($agenda_instrumento) $sql->adOnde('agenda_gestao_instrumento='.(int)$agenda_instrumento);
		elseif ($agenda_recurso) $sql->adOnde('agenda_gestao_recurso='.(int)$agenda_recurso);
		elseif ($agenda_problema) $sql->adOnde('agenda_gestao_problema='.(int)$agenda_problema);
		elseif ($agenda_demanda) $sql->adOnde('agenda_gestao_demanda='.(int)$agenda_demanda);
		elseif ($agenda_programa) $sql->adOnde('agenda_gestao_programa='.(int)$agenda_programa);
		elseif ($agenda_licao) $sql->adOnde('agenda_gestao_licao='.(int)$agenda_licao);
		elseif ($agenda_evento) $sql->adOnde('agenda_gestao_evento='.(int)$agenda_evento);
		elseif ($agenda_link) $sql->adOnde('agenda_gestao_link='.(int)$agenda_link);
		elseif ($agenda_avaliacao) $sql->adOnde('agenda_gestao_avaliacao='.(int)$agenda_avaliacao);
		elseif ($agenda_tgn) $sql->adOnde('agenda_gestao_tgn='.(int)$agenda_tgn);
		elseif ($agenda_brainstorm) $sql->adOnde('agenda_gestao_brainstorm='.(int)$agenda_brainstorm);
		elseif ($agenda_gut) $sql->adOnde('agenda_gestao_gut='.(int)$agenda_gut);
		elseif ($agenda_causa_efeito) $sql->adOnde('agenda_gestao_causa_efeito='.(int)$agenda_causa_efeito);
		elseif ($agenda_arquivo) $sql->adOnde('agenda_gestao_arquivo='.(int)$agenda_arquivo);
		elseif ($agenda_forum) $sql->adOnde('agenda_gestao_forum='.(int)$agenda_forum);
		elseif ($agenda_checklist) $sql->adOnde('agenda_gestao_checklist='.(int)$agenda_checklist);
		
		elseif ($agenda_agenda) $sql->adOnde('agenda_gestao_semelhante='.(int)$agenda_agenda);
		
		elseif ($agenda_agrupamento) $sql->adOnde('agenda_gestao_agrupamento='.(int)$agenda_agrupamento);
		elseif ($agenda_patrocinador) $sql->adOnde('agenda_gestao_patrocinador='.(int)$agenda_patrocinador);
		elseif ($agenda_template) $sql->adOnde('agenda_gestao_template='.(int)$agenda_template);
		elseif ($agenda_painel) $sql->adOnde('agenda_gestao_painel='.(int)$agenda_painel);
		elseif ($agenda_painel_odometro) $sql->adOnde('agenda_gestao_painel_odometro='.(int)$agenda_painel_odometro);
		elseif ($agenda_painel_composicao) $sql->adOnde('agenda_gestao_painel_composicao='.(int)$agenda_painel_composicao);
		elseif ($agenda_tr) $sql->adOnde('agenda_gestao_tr='.(int)$agenda_tr);
		elseif ($agenda_me) $sql->adOnde('agenda_gestao_me='.(int)$agenda_me);
		elseif ($agenda_acao_item) $sql->adOnde('agenda_gestao_acao_item='.(int)$agenda_acao_item);
		elseif ($agenda_beneficio) $sql->adOnde('agenda_gestao_beneficio='.(int)$agenda_beneficio);
		elseif ($agenda_painel_slideshow) $sql->adOnde('agenda_gestao_painel_slideshow='.(int)$agenda_painel_slideshow);
		elseif ($agenda_projeto_viabilidade) $sql->adOnde('agenda_gestao_projeto_viabilidade='.(int)$agenda_projeto_viabilidade);
		elseif ($agenda_projeto_abertura) $sql->adOnde('agenda_gestao_projeto_abertura='.(int)$agenda_projeto_abertura);
		elseif ($agenda_plano_gestao) $sql->adOnde('agenda_gestao_plano_gestao='.(int)$agenda_plano_gestao);

	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('agenda_gestao');
			$sql->adCampo('MAX(agenda_gestao_ordem)');
			if ($uuid) $sql->adOnde('agenda_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('agenda_gestao_agenda ='.(int)$agenda_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('agenda_gestao');
			if ($uuid) $sql->adInserir('agenda_gestao_uuid', $uuid);
			else $sql->adInserir('agenda_gestao_agenda', (int)$agenda_id);
			
			if ($agenda_tarefa) $sql->adInserir('agenda_gestao_tarefa', (int)$agenda_tarefa);
			if ($agenda_projeto) $sql->adInserir('agenda_gestao_projeto', (int)$agenda_projeto);
			elseif ($agenda_perspectiva) $sql->adInserir('agenda_gestao_perspectiva', (int)$agenda_perspectiva);
			elseif ($agenda_tema) $sql->adInserir('agenda_gestao_tema', (int)$agenda_tema);
			elseif ($agenda_objetivo) $sql->adInserir('agenda_gestao_objetivo', (int)$agenda_objetivo);
			elseif ($agenda_fator) $sql->adInserir('agenda_gestao_fator', (int)$agenda_fator);
			elseif ($agenda_estrategia) $sql->adInserir('agenda_gestao_estrategia', (int)$agenda_estrategia);
			elseif ($agenda_acao) $sql->adInserir('agenda_gestao_acao', (int)$agenda_acao);
			elseif ($agenda_pratica) $sql->adInserir('agenda_gestao_pratica', (int)$agenda_pratica);
			elseif ($agenda_meta) $sql->adInserir('agenda_gestao_meta', (int)$agenda_meta);
			elseif ($agenda_canvas) $sql->adInserir('agenda_gestao_canvas', (int)$agenda_canvas);
			elseif ($agenda_risco) $sql->adInserir('agenda_gestao_risco', (int)$agenda_risco);
			elseif ($agenda_risco_resposta) $sql->adInserir('agenda_gestao_risco_resposta', (int)$agenda_risco_resposta);
			elseif ($agenda_indicador) $sql->adInserir('agenda_gestao_indicador', (int)$agenda_indicador);
			elseif ($agenda_calendario) $sql->adInserir('agenda_gestao_calendario', (int)$agenda_calendario);
			elseif ($agenda_monitoramento) $sql->adInserir('agenda_gestao_monitoramento', (int)$agenda_monitoramento);
			elseif ($agenda_ata) $sql->adInserir('agenda_gestao_ata', (int)$agenda_ata);
			elseif ($agenda_mswot) $sql->adInserir('agenda_gestao_mswot', (int)$agenda_mswot);
			elseif ($agenda_swot) $sql->adInserir('agenda_gestao_swot', (int)$agenda_swot);
			elseif ($agenda_operativo) $sql->adInserir('agenda_gestao_operativo', (int)$agenda_operativo);
			elseif ($agenda_instrumento) $sql->adInserir('agenda_gestao_instrumento', (int)$agenda_instrumento);
			elseif ($agenda_recurso) $sql->adInserir('agenda_gestao_recurso', (int)$agenda_recurso);
			elseif ($agenda_problema) $sql->adInserir('agenda_gestao_problema', (int)$agenda_problema);
			elseif ($agenda_demanda) $sql->adInserir('agenda_gestao_demanda', (int)$agenda_demanda);
			elseif ($agenda_programa) $sql->adInserir('agenda_gestao_programa', (int)$agenda_programa);
			elseif ($agenda_licao) $sql->adInserir('agenda_gestao_licao', (int)$agenda_licao);
			elseif ($agenda_evento) $sql->adInserir('agenda_gestao_evento', (int)$agenda_evento);
			elseif ($agenda_link) $sql->adInserir('agenda_gestao_link', (int)$agenda_link);
			elseif ($agenda_avaliacao) $sql->adInserir('agenda_gestao_avaliacao', (int)$agenda_avaliacao);
			elseif ($agenda_tgn) $sql->adInserir('agenda_gestao_tgn', (int)$agenda_tgn);
			elseif ($agenda_brainstorm) $sql->adInserir('agenda_gestao_brainstorm', (int)$agenda_brainstorm);
			elseif ($agenda_gut) $sql->adInserir('agenda_gestao_gut', (int)$agenda_gut);
			elseif ($agenda_causa_efeito) $sql->adInserir('agenda_gestao_causa_efeito', (int)$agenda_causa_efeito);
			elseif ($agenda_arquivo) $sql->adInserir('agenda_gestao_arquivo', (int)$agenda_arquivo);
			elseif ($agenda_forum) $sql->adInserir('agenda_gestao_forum', (int)$agenda_forum);
			elseif ($agenda_checklist) $sql->adInserir('agenda_gestao_checklist', (int)$agenda_checklist);
			
			elseif ($agenda_agenda) $sql->adInserir('agenda_gestao_semelhante', (int)$agenda_agenda);
			
			elseif ($agenda_agrupamento) $sql->adInserir('agenda_gestao_agrupamento', (int)$agenda_agrupamento);
			elseif ($agenda_patrocinador) $sql->adInserir('agenda_gestao_patrocinador', (int)$agenda_patrocinador);
			elseif ($agenda_template) $sql->adInserir('agenda_gestao_template', (int)$agenda_template);
			elseif ($agenda_painel) $sql->adInserir('agenda_gestao_painel', (int)$agenda_painel);
			elseif ($agenda_painel_odometro) $sql->adInserir('agenda_gestao_painel_odometro', (int)$agenda_painel_odometro);
			elseif ($agenda_painel_composicao) $sql->adInserir('agenda_gestao_painel_composicao', (int)$agenda_painel_composicao);
			elseif ($agenda_tr) $sql->adInserir('agenda_gestao_tr', (int)$agenda_tr);
			elseif ($agenda_me) $sql->adInserir('agenda_gestao_me', (int)$agenda_me);
			elseif ($agenda_acao_item) $sql->adInserir('agenda_gestao_acao_item', (int)$agenda_acao_item);
			elseif ($agenda_beneficio) $sql->adInserir('agenda_gestao_beneficio', (int)$agenda_beneficio);
			elseif ($agenda_painel_slideshow) $sql->adInserir('agenda_gestao_painel_slideshow', (int)$agenda_painel_slideshow);
			elseif ($agenda_projeto_viabilidade) $sql->adInserir('agenda_gestao_projeto_viabilidade', (int)$agenda_projeto_viabilidade);
			elseif ($agenda_projeto_abertura) $sql->adInserir('agenda_gestao_projeto_abertura', (int)$agenda_projeto_abertura);
			elseif ($agenda_plano_gestao) $sql->adInserir('agenda_gestao_plano_gestao', (int)$agenda_plano_gestao);
			$sql->adInserir('agenda_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($agenda_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($agenda_id=0, $uuid='', $agenda_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('agenda_gestao');
	$sql->adOnde('agenda_gestao_id='.(int)$agenda_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($agenda_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($agenda_id=0, $uuid=''){	
	$saida=atualizar_gestao($agenda_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($agenda_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('agenda_gestao');
	$sql->adCampo('agenda_gestao.*');
	if ($uuid) $sql->adOnde('agenda_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('agenda_gestao_agenda ='.(int)$agenda_id);	
	$sql->adOrdem('agenda_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['agenda_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['agenda_gestao_tarefa']).'</td>';
		elseif ($gestao_data['agenda_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['agenda_gestao_projeto']).'</td>';
		elseif ($gestao_data['agenda_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['agenda_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['agenda_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['agenda_gestao_tema']).'</td>';
		elseif ($gestao_data['agenda_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['agenda_gestao_objetivo']).'</td>';
		elseif ($gestao_data['agenda_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['agenda_gestao_fator']).'</td>';
		elseif ($gestao_data['agenda_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['agenda_gestao_estrategia']).'</td>';
		elseif ($gestao_data['agenda_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['agenda_gestao_meta']).'</td>';
		elseif ($gestao_data['agenda_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['agenda_gestao_pratica']).'</td>';
		elseif ($gestao_data['agenda_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['agenda_gestao_acao']).'</td>';
		elseif ($gestao_data['agenda_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['agenda_gestao_canvas']).'</td>';
		elseif ($gestao_data['agenda_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['agenda_gestao_risco']).'</td>';
		elseif ($gestao_data['agenda_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['agenda_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['agenda_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['agenda_gestao_indicador']).'</td>';
		elseif ($gestao_data['agenda_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['agenda_gestao_calendario']).'</td>';
		elseif ($gestao_data['agenda_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['agenda_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['agenda_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['agenda_gestao_ata']).'</td>';
		elseif ($gestao_data['agenda_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['agenda_gestao_mswot']).'</td>';
		elseif ($gestao_data['agenda_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['agenda_gestao_swot']).'</td>';
		elseif ($gestao_data['agenda_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['agenda_gestao_operativo']).'</td>';
		elseif ($gestao_data['agenda_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['agenda_gestao_instrumento']).'</td>';
		elseif ($gestao_data['agenda_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['agenda_gestao_recurso']).'</td>';
		elseif ($gestao_data['agenda_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['agenda_gestao_problema']).'</td>';
		elseif ($gestao_data['agenda_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['agenda_gestao_demanda']).'</td>';
		elseif ($gestao_data['agenda_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['agenda_gestao_programa']).'</td>';
		elseif ($gestao_data['agenda_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['agenda_gestao_licao']).'</td>';
		elseif ($gestao_data['agenda_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['agenda_gestao_evento']).'</td>';
		elseif ($gestao_data['agenda_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['agenda_gestao_link']).'</td>';
		elseif ($gestao_data['agenda_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['agenda_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['agenda_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['agenda_gestao_tgn']).'</td>';
		elseif ($gestao_data['agenda_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['agenda_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['agenda_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['agenda_gestao_gut']).'</td>';
		elseif ($gestao_data['agenda_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['agenda_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['agenda_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['agenda_gestao_arquivo']).'</td>';
		elseif ($gestao_data['agenda_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['agenda_gestao_forum']).'</td>';
		elseif ($gestao_data['agenda_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['agenda_gestao_checklist']).'</td>';
		
		elseif ($gestao_data['agenda_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['agenda_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['agenda_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['agenda_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['agenda_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['agenda_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['agenda_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['agenda_gestao_template']).'</td>';
		elseif ($gestao_data['agenda_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['agenda_gestao_painel']).'</td>';
		elseif ($gestao_data['agenda_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['agenda_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['agenda_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['agenda_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['agenda_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['agenda_gestao_tr']).'</td>';	
		elseif ($gestao_data['agenda_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['agenda_gestao_me']).'</td>';	
		elseif ($gestao_data['agenda_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['agenda_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['agenda_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['agenda_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['agenda_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['agenda_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['agenda_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['agenda_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['agenda_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['agenda_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['agenda_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['agenda_gestao_plano_gestao']).'</td>';	
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['agenda_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		


$xajax->processRequest();
?>