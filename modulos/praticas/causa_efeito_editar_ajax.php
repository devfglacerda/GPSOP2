<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
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


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/causa_efeito_editar_ajax_pro.php';	

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

function exibir_cias($cias){
	global $config;
	$cias_selecionadas=explode(',', $cias);
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0>';
			$saida_cias.= '<tr><td class="texto" style="width:400px;">'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';		
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			} 
	else 	$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_cias',"innerHTML", utf8_encode($saida_cias));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_cias");	
	

function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_secao($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");

function mudar_posicao_gestao($ordem, $causa_efeito_gestao_id, $direcao, $causa_efeito_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $causa_efeito_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('causa_efeito_gestao');
		$sql->adOnde('causa_efeito_gestao_id != '.(int)$causa_efeito_gestao_id);
		if ($uuid) $sql->adOnde('causa_efeito_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('causa_efeito_gestao_causa_efeito = '.(int)$causa_efeito_id);
		$sql->adOrdem('causa_efeito_gestao_ordem');
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
			$sql->adTabela('causa_efeito_gestao');
			$sql->adAtualizar('causa_efeito_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('causa_efeito_gestao_id = '.(int)$causa_efeito_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('causa_efeito_gestao');
					$sql->adAtualizar('causa_efeito_gestao_ordem', $idx);
					$sql->adOnde('causa_efeito_gestao_id = '.(int)$acao['causa_efeito_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('causa_efeito_gestao');
					$sql->adAtualizar('causa_efeito_gestao_ordem', $idx + 1);
					$sql->adOnde('causa_efeito_gestao_id = '.(int)$acao['causa_efeito_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($causa_efeito_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$causa_efeito_id=0, 
	$uuid='',  
	
	$causa_efeitojeto=null,
	$causa_efeito_tarefa=null,
	$causa_efeito_perspectiva=null,
	$causa_efeito_tema=null,
	$causa_efeito_objetivo=null,
	$causa_efeito_fator=null,
	$causa_efeito_estrategia=null,
	$causa_efeito_meta=null,
	$causa_efeito_pratica=null,
	$causa_efeito_acao=null,
	$causa_efeito_canvas=null,
	$causa_efeito_risco=null,
	$causa_efeito_risco_resposta=null,
	$causa_efeito_indicador=null,
	$causa_efeito_calendario=null,
	$causa_efeito_monitoramento=null,
	$causa_efeito_ata=null,
	$causa_efeito_mswot=null,
	$causa_efeito_swot=null,
	$causa_efeito_operativo=null,
	$causa_efeito_instrumento=null,
	$causa_efeito_recurso=null,
	$causa_efeitoblema=null,
	$causa_efeito_demanda=null,
	$causa_efeitograma=null,
	$causa_efeito_licao=null,
	$causa_efeito_evento=null,
	$causa_efeito_link=null,
	$causa_efeito_avaliacao=null,
	$causa_efeito_tgn=null,
	$causa_efeito_brainstorm=null,
	$causa_efeito_gut=null,
	$causa_efeito_causa_efeito=null,
	$causa_efeito_arquivo=null,
	$causa_efeito_forum=null,
	$causa_efeito_checklist=null,
	$causa_efeito_agenda=null,
	$causa_efeito_agrupamento=null,
	$causa_efeito_patrocinador=null,
	$causa_efeito_template=null,
	$causa_efeito_painel=null,
	$causa_efeito_painel_odometro=null,
	$causa_efeito_painel_composicao=null,
	$causa_efeito_tr=null,
	$causa_efeito_me=null,
	$causa_efeito_acao_item=null,
	$causa_efeito_beneficio=null,
	$causa_efeito_painel_slideshow=null,
	$causa_efeitojeto_viabilidade=null,
	$causa_efeitojeto_abertura=null,
	$causa_efeito_plano_gestao=null,
	$causa_efeito_ssti=null,
	$causa_efeito_laudo=null,
	$causa_efeito_trelo=null,
	$causa_efeito_trelo_cartao=null,
	$causa_efeito_pdcl=null,
	$causa_efeito_pdcl_item=null
	)
	{
	if (
		$causa_efeitojeto || 
		$causa_efeito_tarefa || 
		$causa_efeito_perspectiva || 
		$causa_efeito_tema || 
		$causa_efeito_objetivo || 
		$causa_efeito_fator || 
		$causa_efeito_estrategia || 
		$causa_efeito_meta || 
		$causa_efeito_pratica || 
		$causa_efeito_acao || 
		$causa_efeito_canvas || 
		$causa_efeito_risco || 
		$causa_efeito_risco_resposta || 
		$causa_efeito_indicador || 
		$causa_efeito_calendario || 
		$causa_efeito_monitoramento || 
		$causa_efeito_ata || 
		$causa_efeito_mswot || 
		$causa_efeito_swot || 
		$causa_efeito_operativo || 
		$causa_efeito_instrumento || 
		$causa_efeito_recurso || 
		$causa_efeitoblema || 
		$causa_efeito_demanda || 
		$causa_efeitograma || 
		$causa_efeito_licao || 
		$causa_efeito_evento || 
		$causa_efeito_link || 
		$causa_efeito_avaliacao || 
		$causa_efeito_tgn || 
		$causa_efeito_brainstorm || 
		$causa_efeito_gut || 
		$causa_efeito_causa_efeito || 
		$causa_efeito_arquivo || 
		$causa_efeito_forum || 
		$causa_efeito_checklist || 
		$causa_efeito_agenda || 
		$causa_efeito_agrupamento || 
		$causa_efeito_patrocinador || 
		$causa_efeito_template || 
		$causa_efeito_painel || 
		$causa_efeito_painel_odometro || 
		$causa_efeito_painel_composicao || 
		$causa_efeito_tr || 
		$causa_efeito_me || 
		$causa_efeito_acao_item || 
		$causa_efeito_beneficio || 
		$causa_efeito_painel_slideshow || 
		$causa_efeitojeto_viabilidade || 
		$causa_efeitojeto_abertura || 
		$causa_efeito_plano_gestao|| 
		$causa_efeito_ssti || 
		$causa_efeito_laudo || 
		$causa_efeito_trelo || 
		$causa_efeito_trelo_cartao || 
		$causa_efeito_pdcl || 
		$causa_efeito_pdcl_item
		){
		global $Aplic;
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('causa_efeito_gestao');
			if ($uuid) $sql->adOnde('causa_efeito_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('causa_efeito_gestao_causa_efeito ='.(int)$causa_efeito_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('causa_efeito_gestao');
		$sql->adCampo('count(causa_efeito_gestao_id)');
		if ($uuid) $sql->adOnde('causa_efeito_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('causa_efeito_gestao_causa_efeito ='.(int)$causa_efeito_id);	
		if ($causa_efeito_tarefa) $sql->adOnde('causa_efeito_gestao_tarefa='.(int)$causa_efeito_tarefa);
		elseif ($causa_efeitojeto) $sql->adOnde('causa_efeito_gestao_projeto='.(int)$causa_efeitojeto);
		elseif ($causa_efeito_perspectiva) $sql->adOnde('causa_efeito_gestao_perspectiva='.(int)$causa_efeito_perspectiva);
		elseif ($causa_efeito_tema) $sql->adOnde('causa_efeito_gestao_tema='.(int)$causa_efeito_tema);
		elseif ($causa_efeito_objetivo) $sql->adOnde('causa_efeito_gestao_objetivo='.(int)$causa_efeito_objetivo);
		elseif ($causa_efeito_fator) $sql->adOnde('causa_efeito_gestao_fator='.(int)$causa_efeito_fator);
		elseif ($causa_efeito_estrategia) $sql->adOnde('causa_efeito_gestao_estrategia='.(int)$causa_efeito_estrategia);
		elseif ($causa_efeito_acao) $sql->adOnde('causa_efeito_gestao_acao='.(int)$causa_efeito_acao);
		elseif ($causa_efeito_pratica) $sql->adOnde('causa_efeito_gestao_pratica='.(int)$causa_efeito_pratica);
		elseif ($causa_efeito_meta) $sql->adOnde('causa_efeito_gestao_meta='.(int)$causa_efeito_meta);
		elseif ($causa_efeito_canvas) $sql->adOnde('causa_efeito_gestao_canvas='.(int)$causa_efeito_canvas);
		elseif ($causa_efeito_risco) $sql->adOnde('causa_efeito_gestao_risco='.(int)$causa_efeito_risco);
		elseif ($causa_efeito_risco_resposta) $sql->adOnde('causa_efeito_gestao_risco_resposta='.(int)$causa_efeito_risco_resposta);
		elseif ($causa_efeito_indicador) $sql->adOnde('causa_efeito_gestao_indicador='.(int)$causa_efeito_indicador);
		elseif ($causa_efeito_calendario) $sql->adOnde('causa_efeito_gestao_calendario='.(int)$causa_efeito_calendario);
		elseif ($causa_efeito_monitoramento) $sql->adOnde('causa_efeito_gestao_monitoramento='.(int)$causa_efeito_monitoramento);
		elseif ($causa_efeito_ata) $sql->adOnde('causa_efeito_gestao_ata='.(int)$causa_efeito_ata);
		elseif ($causa_efeito_mswot) $sql->adOnde('causa_efeito_gestao_mswot='.(int)$causa_efeito_mswot);
		elseif ($causa_efeito_swot) $sql->adOnde('causa_efeito_gestao_swot='.(int)$causa_efeito_swot);
		elseif ($causa_efeito_operativo) $sql->adOnde('causa_efeito_gestao_operativo='.(int)$causa_efeito_operativo);
		elseif ($causa_efeito_instrumento) $sql->adOnde('causa_efeito_gestao_instrumento='.(int)$causa_efeito_instrumento);
		elseif ($causa_efeito_recurso) $sql->adOnde('causa_efeito_gestao_recurso='.(int)$causa_efeito_recurso);
		elseif ($causa_efeitoblema) $sql->adOnde('causa_efeito_gestao_problema='.(int)$causa_efeitoblema);
		elseif ($causa_efeito_demanda) $sql->adOnde('causa_efeito_gestao_demanda='.(int)$causa_efeito_demanda);
		elseif ($causa_efeitograma) $sql->adOnde('causa_efeito_gestao_programa='.(int)$causa_efeitograma);
		elseif ($causa_efeito_licao) $sql->adOnde('causa_efeito_gestao_licao='.(int)$causa_efeito_licao);
		elseif ($causa_efeito_evento) $sql->adOnde('causa_efeito_gestao_evento='.(int)$causa_efeito_evento);
		elseif ($causa_efeito_link) $sql->adOnde('causa_efeito_gestao_link='.(int)$causa_efeito_link);
		elseif ($causa_efeito_avaliacao) $sql->adOnde('causa_efeito_gestao_avaliacao='.(int)$causa_efeito_avaliacao);
		elseif ($causa_efeito_tgn) $sql->adOnde('causa_efeito_gestao_tgn='.(int)$causa_efeito_tgn);
		elseif ($causa_efeito_brainstorm) $sql->adOnde('causa_efeito_gestao_brainstorm='.(int)$causa_efeito_brainstorm);
		elseif ($causa_efeito_gut) $sql->adOnde('causa_efeito_gestao_gut='.(int)$causa_efeito_gut);
		
		elseif ($causa_efeito_causa_efeito) $sql->adOnde('causa_efeito_gestao_semelhante='.(int)$causa_efeito_causa_efeito);
		
		elseif ($causa_efeito_arquivo) $sql->adOnde('causa_efeito_gestao_arquivo='.(int)$causa_efeito_arquivo);
		elseif ($causa_efeito_forum) $sql->adOnde('causa_efeito_gestao_forum='.(int)$causa_efeito_forum);
		elseif ($causa_efeito_checklist) $sql->adOnde('causa_efeito_gestao_checklist='.(int)$causa_efeito_checklist);
		elseif ($causa_efeito_agenda) $sql->adOnde('causa_efeito_gestao_agenda='.(int)$causa_efeito_agenda);
		elseif ($causa_efeito_agrupamento) $sql->adOnde('causa_efeito_gestao_agrupamento='.(int)$causa_efeito_agrupamento);
		elseif ($causa_efeito_patrocinador) $sql->adOnde('causa_efeito_gestao_patrocinador='.(int)$causa_efeito_patrocinador);
		elseif ($causa_efeito_template) $sql->adOnde('causa_efeito_gestao_template='.(int)$causa_efeito_template);
		elseif ($causa_efeito_painel) $sql->adOnde('causa_efeito_gestao_painel='.(int)$causa_efeito_painel);
		elseif ($causa_efeito_painel_odometro) $sql->adOnde('causa_efeito_gestao_painel_odometro='.(int)$causa_efeito_painel_odometro);
		elseif ($causa_efeito_painel_composicao) $sql->adOnde('causa_efeito_gestao_painel_composicao='.(int)$causa_efeito_painel_composicao);
		elseif ($causa_efeito_tr) $sql->adOnde('causa_efeito_gestao_tr='.(int)$causa_efeito_tr);
		elseif ($causa_efeito_me) $sql->adOnde('causa_efeito_gestao_me='.(int)$causa_efeito_me);
		elseif ($causa_efeito_acao_item) $sql->adOnde('causa_efeito_gestao_acao_item='.(int)$causa_efeito_acao_item);
		elseif ($causa_efeito_beneficio) $sql->adOnde('causa_efeito_gestao_beneficio='.(int)$causa_efeito_beneficio);
		elseif ($causa_efeito_painel_slideshow) $sql->adOnde('causa_efeito_gestao_painel_slideshow='.(int)$causa_efeito_painel_slideshow);
		elseif ($causa_efeitojeto_viabilidade) $sql->adOnde('causa_efeito_gestao_projeto_viabilidade='.(int)$causa_efeitojeto_viabilidade);
		elseif ($causa_efeitojeto_abertura) $sql->adOnde('causa_efeito_gestao_projeto_abertura='.(int)$causa_efeitojeto_abertura);
		elseif ($causa_efeito_plano_gestao) $sql->adOnde('causa_efeito_gestao_plano_gestao='.(int)$causa_efeito_plano_gestao);
		elseif ($causa_efeito_ssti) $sql->adOnde('causa_efeito_gestao_ssti='.(int)$causa_efeito_ssti);
		elseif ($causa_efeito_laudo) $sql->adOnde('causa_efeito_gestao_laudo='.(int)$causa_efeito_laudo);
		elseif ($causa_efeito_trelo) $sql->adOnde('causa_efeito_gestao_trelo='.(int)$causa_efeito_trelo);
		elseif ($causa_efeito_trelo_cartao) $sql->adOnde('causa_efeito_gestao_trelo_cartao='.(int)$causa_efeito_trelo_cartao);
		elseif ($causa_efeito_pdcl) $sql->adOnde('causa_efeito_gestao_pdcl='.(int)$causa_efeito_pdcl);
		elseif ($causa_efeito_pdcl_item) $sql->adOnde('causa_efeito_gestao_pdcl_item='.(int)$causa_efeito_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('causa_efeito_gestao');
			$sql->adCampo('MAX(causa_efeito_gestao_ordem)');
			if ($uuid) $sql->adOnde('causa_efeito_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('causa_efeito_gestao_causa_efeito ='.(int)$causa_efeito_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('causa_efeito_gestao');
			if ($uuid) $sql->adInserir('causa_efeito_gestao_uuid', $uuid);
			else $sql->adInserir('causa_efeito_gestao_causa_efeito', (int)$causa_efeito_id);
			
			if ($causa_efeito_tarefa) $sql->adInserir('causa_efeito_gestao_tarefa', (int)$causa_efeito_tarefa);
			if ($causa_efeitojeto) $sql->adInserir('causa_efeito_gestao_projeto', (int)$causa_efeitojeto);
			elseif ($causa_efeito_perspectiva) $sql->adInserir('causa_efeito_gestao_perspectiva', (int)$causa_efeito_perspectiva);
			elseif ($causa_efeito_tema) $sql->adInserir('causa_efeito_gestao_tema', (int)$causa_efeito_tema);
			elseif ($causa_efeito_objetivo) $sql->adInserir('causa_efeito_gestao_objetivo', (int)$causa_efeito_objetivo);
			elseif ($causa_efeito_fator) $sql->adInserir('causa_efeito_gestao_fator', (int)$causa_efeito_fator);
			elseif ($causa_efeito_estrategia) $sql->adInserir('causa_efeito_gestao_estrategia', (int)$causa_efeito_estrategia);
			elseif ($causa_efeito_acao) $sql->adInserir('causa_efeito_gestao_acao', (int)$causa_efeito_acao);
			elseif ($causa_efeito_pratica) $sql->adInserir('causa_efeito_gestao_pratica', (int)$causa_efeito_pratica);
			elseif ($causa_efeito_meta) $sql->adInserir('causa_efeito_gestao_meta', (int)$causa_efeito_meta);
			elseif ($causa_efeito_canvas) $sql->adInserir('causa_efeito_gestao_canvas', (int)$causa_efeito_canvas);
			elseif ($causa_efeito_risco) $sql->adInserir('causa_efeito_gestao_risco', (int)$causa_efeito_risco);
			elseif ($causa_efeito_risco_resposta) $sql->adInserir('causa_efeito_gestao_risco_resposta', (int)$causa_efeito_risco_resposta);
			elseif ($causa_efeito_indicador) $sql->adInserir('causa_efeito_gestao_indicador', (int)$causa_efeito_indicador);
			elseif ($causa_efeito_calendario) $sql->adInserir('causa_efeito_gestao_calendario', (int)$causa_efeito_calendario);
			elseif ($causa_efeito_monitoramento) $sql->adInserir('causa_efeito_gestao_monitoramento', (int)$causa_efeito_monitoramento);
			elseif ($causa_efeito_ata) $sql->adInserir('causa_efeito_gestao_ata', (int)$causa_efeito_ata);
			elseif ($causa_efeito_mswot) $sql->adInserir('causa_efeito_gestao_mswot', (int)$causa_efeito_mswot);
			elseif ($causa_efeito_swot) $sql->adInserir('causa_efeito_gestao_swot', (int)$causa_efeito_swot);
			elseif ($causa_efeito_operativo) $sql->adInserir('causa_efeito_gestao_operativo', (int)$causa_efeito_operativo);
			elseif ($causa_efeito_instrumento) $sql->adInserir('causa_efeito_gestao_instrumento', (int)$causa_efeito_instrumento);
			elseif ($causa_efeito_recurso) $sql->adInserir('causa_efeito_gestao_recurso', (int)$causa_efeito_recurso);
			elseif ($causa_efeitoblema) $sql->adInserir('causa_efeito_gestao_problema', (int)$causa_efeitoblema);
			elseif ($causa_efeito_demanda) $sql->adInserir('causa_efeito_gestao_demanda', (int)$causa_efeito_demanda);
			elseif ($causa_efeitograma) $sql->adInserir('causa_efeito_gestao_programa', (int)$causa_efeitograma);
			elseif ($causa_efeito_licao) $sql->adInserir('causa_efeito_gestao_licao', (int)$causa_efeito_licao);
			elseif ($causa_efeito_evento) $sql->adInserir('causa_efeito_gestao_evento', (int)$causa_efeito_evento);
			elseif ($causa_efeito_link) $sql->adInserir('causa_efeito_gestao_link', (int)$causa_efeito_link);
			elseif ($causa_efeito_avaliacao) $sql->adInserir('causa_efeito_gestao_avaliacao', (int)$causa_efeito_avaliacao);
			elseif ($causa_efeito_tgn) $sql->adInserir('causa_efeito_gestao_tgn', (int)$causa_efeito_tgn);
			elseif ($causa_efeito_brainstorm) $sql->adInserir('causa_efeito_gestao_brainstorm', (int)$causa_efeito_brainstorm);
			elseif ($causa_efeito_gut) $sql->adInserir('causa_efeito_gestao_gut', (int)$causa_efeito_gut);
			
			elseif ($causa_efeito_causa_efeito) $sql->adInserir('causa_efeito_gestao_semelhante', (int)$causa_efeito_causa_efeito);
			
			elseif ($causa_efeito_arquivo) $sql->adInserir('causa_efeito_gestao_arquivo', (int)$causa_efeito_arquivo);
			elseif ($causa_efeito_forum) $sql->adInserir('causa_efeito_gestao_forum', (int)$causa_efeito_forum);
			elseif ($causa_efeito_checklist) $sql->adInserir('causa_efeito_gestao_checklist', (int)$causa_efeito_checklist);
			elseif ($causa_efeito_agenda) $sql->adInserir('causa_efeito_gestao_agenda', (int)$causa_efeito_agenda);
			elseif ($causa_efeito_agrupamento) $sql->adInserir('causa_efeito_gestao_agrupamento', (int)$causa_efeito_agrupamento);
			elseif ($causa_efeito_patrocinador) $sql->adInserir('causa_efeito_gestao_patrocinador', (int)$causa_efeito_patrocinador);
			elseif ($causa_efeito_template) $sql->adInserir('causa_efeito_gestao_template', (int)$causa_efeito_template);
			elseif ($causa_efeito_painel) $sql->adInserir('causa_efeito_gestao_painel', (int)$causa_efeito_painel);
			elseif ($causa_efeito_painel_odometro) $sql->adInserir('causa_efeito_gestao_painel_odometro', (int)$causa_efeito_painel_odometro);
			elseif ($causa_efeito_painel_composicao) $sql->adInserir('causa_efeito_gestao_painel_composicao', (int)$causa_efeito_painel_composicao);
			elseif ($causa_efeito_tr) $sql->adInserir('causa_efeito_gestao_tr', (int)$causa_efeito_tr);
			elseif ($causa_efeito_me) $sql->adInserir('causa_efeito_gestao_me', (int)$causa_efeito_me);
			elseif ($causa_efeito_acao_item) $sql->adInserir('causa_efeito_gestao_acao_item', (int)$causa_efeito_acao_item);
			elseif ($causa_efeito_beneficio) $sql->adInserir('causa_efeito_gestao_beneficio', (int)$causa_efeito_beneficio);
			elseif ($causa_efeito_painel_slideshow) $sql->adInserir('causa_efeito_gestao_painel_slideshow', (int)$causa_efeito_painel_slideshow);
			elseif ($causa_efeitojeto_viabilidade) $sql->adInserir('causa_efeito_gestao_projeto_viabilidade', (int)$causa_efeitojeto_viabilidade);
			elseif ($causa_efeitojeto_abertura) $sql->adInserir('causa_efeito_gestao_projeto_abertura', (int)$causa_efeitojeto_abertura);
			elseif ($causa_efeito_plano_gestao) $sql->adInserir('causa_efeito_gestao_plano_gestao', (int)$causa_efeito_plano_gestao);
			elseif ($causa_efeito_ssti) $sql->adInserir('causa_efeito_gestao_ssti', (int)$causa_efeito_ssti);
			elseif ($causa_efeito_laudo) $sql->adInserir('causa_efeito_gestao_laudo', (int)$causa_efeito_laudo);
			elseif ($causa_efeito_trelo) $sql->adInserir('causa_efeito_gestao_trelo', (int)$causa_efeito_trelo);
			elseif ($causa_efeito_trelo_cartao) $sql->adInserir('causa_efeito_gestao_trelo_cartao', (int)$causa_efeito_trelo_cartao);
			elseif ($causa_efeito_pdcl) $sql->adInserir('causa_efeito_gestao_pdcl', (int)$causa_efeito_pdcl);
			elseif ($causa_efeito_pdcl_item) $sql->adInserir('causa_efeito_gestao_pdcl_item', (int)$causa_efeito_pdcl_item);
			$sql->adInserir('causa_efeito_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($causa_efeito_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($causa_efeito_id=0, $uuid='', $causa_efeito_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('causa_efeito_gestao');
	$sql->adOnde('causa_efeito_gestao_id='.(int)$causa_efeito_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($causa_efeito_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($causa_efeito_id=0, $uuid=''){	
	$saida=atualizar_gestao($causa_efeito_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($causa_efeito_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('causa_efeito_gestao');
	$sql->adCampo('causa_efeito_gestao.*');
	if ($uuid) $sql->adOnde('causa_efeito_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('causa_efeito_gestao_causa_efeito ='.(int)$causa_efeito_id);	
	$sql->adOrdem('causa_efeito_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['causa_efeito_gestao_ordem'].', '.$gestao_data['causa_efeito_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['causa_efeito_gestao_ordem'].', '.$gestao_data['causa_efeito_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['causa_efeito_gestao_ordem'].', '.$gestao_data['causa_efeito_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['causa_efeito_gestao_ordem'].', '.$gestao_data['causa_efeito_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['causa_efeito_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['causa_efeito_gestao_tarefa']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['causa_efeito_gestao_projeto']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['causa_efeito_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['causa_efeito_gestao_tema']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['causa_efeito_gestao_objetivo']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['causa_efeito_gestao_fator']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['causa_efeito_gestao_estrategia']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['causa_efeito_gestao_meta']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['causa_efeito_gestao_pratica']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['causa_efeito_gestao_acao']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['causa_efeito_gestao_canvas']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['causa_efeito_gestao_risco']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['causa_efeito_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['causa_efeito_gestao_indicador']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['causa_efeito_gestao_calendario']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['causa_efeito_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['causa_efeito_gestao_ata']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['causa_efeito_gestao_mswot']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['causa_efeito_gestao_swot']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['causa_efeito_gestao_operativo']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['causa_efeito_gestao_instrumento']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['causa_efeito_gestao_recurso']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['causa_efeito_gestao_problema']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['causa_efeito_gestao_demanda']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['causa_efeito_gestao_programa']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['causa_efeito_gestao_licao']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['causa_efeito_gestao_evento']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['causa_efeito_gestao_link']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['causa_efeito_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['causa_efeito_gestao_tgn']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['causa_efeito_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['causa_efeito_gestao_gut']).'</td>';
		
		elseif ($gestao_data['causa_efeito_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['causa_efeito_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['causa_efeito_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['causa_efeito_gestao_arquivo']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['causa_efeito_gestao_forum']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['causa_efeito_gestao_checklist']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['causa_efeito_gestao_agenda']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['causa_efeito_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['causa_efeito_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['causa_efeito_gestao_template']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['causa_efeito_gestao_painel']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['causa_efeito_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['causa_efeito_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['causa_efeito_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['causa_efeito_gestao_tr']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['causa_efeito_gestao_me']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['causa_efeito_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['causa_efeito_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['causa_efeito_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['causa_efeito_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['causa_efeito_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['causa_efeito_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['causa_efeito_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['causa_efeito_gestao_ssti']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['causa_efeito_gestao_laudo']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['causa_efeito_gestao_trelo']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['causa_efeito_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['causa_efeito_gestao_pdcl']).'</td>';
		elseif ($gestao_data['causa_efeito_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['causa_efeito_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['causa_efeito_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		






$xajax->processRequest();
?>	