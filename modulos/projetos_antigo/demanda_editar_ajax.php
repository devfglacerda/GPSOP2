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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/demanda_editar_ajax_pro.php';



function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');
	$lista=$sql->Lista();
	$sql->limpar();
	$vetor=array(0 => '&nbsp;');	
	foreach($lista as $linha) $vetor[utf8_encode($linha['sisvalor_valor_id'])]=utf8_encode($linha['sisvalor_valor']);	
	$saida=selecionaVetor($vetor, $campo, $script);
	$objResposta = new xajaxResponse(); 
	$objResposta->assign($posicao,"innerHTML", $saida); 
	return $objResposta; 
	}	
$xajax->registerFunction("mudar_ajax");
	

function exibir_contatos($contatos){
	global $config;
	$contatos_selecionados=explode(',', $contatos);
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0>';
			$saida_contatos.= '<tr><td class="texto" style="width:400px;">'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';		
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			} 
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_contatos',"innerHTML", utf8_encode($saida_contatos));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_contatos");

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


function mudar_posicao_gestao($ordem, $demanda_gestao_id, $direcao, $demanda_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $demanda_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('demanda_gestao');
		$sql->adOnde('demanda_gestao_id != '.(int)$demanda_gestao_id);
		if ($uuid) $sql->adOnde('demanda_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('demanda_gestao_demanda = '.(int)$demanda_id);
		$sql->adOrdem('demanda_gestao_ordem');
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
			$sql->adTabela('demanda_gestao');
			$sql->adAtualizar('demanda_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('demanda_gestao_id = '.(int)$demanda_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('demanda_gestao');
					$sql->adAtualizar('demanda_gestao_ordem', $idx);
					$sql->adOnde('demanda_gestao_id = '.(int)$acao['demanda_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('demanda_gestao');
					$sql->adAtualizar('demanda_gestao_ordem', $idx + 1);
					$sql->adOnde('demanda_gestao_id = '.(int)$acao['demanda_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($demanda_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$demanda_id=0, 
	$uuid='',  
	
	$demanda_projeto=null,
	$demanda_tarefa=null,
	$demanda_perspectiva=null,
	$demanda_tema=null,
	$demanda_objetivo=null,
	$demanda_fator=null,
	$demanda_estrategia=null,
	$demanda_meta=null,
	$demanda_pratica=null,
	$demanda_acao=null,
	$demanda_canvas=null,
	$demanda_risco=null,
	$demanda_risco_resposta=null,
	$demanda_indicador=null,
	$demanda_calendario=null,
	$demanda_monitoramento=null,
	$demanda_ata=null,
	$demanda_mswot=null,
	$demanda_swot=null,
	$demanda_operativo=null,
	$demanda_instrumento=null,
	$demanda_recurso=null,
	$demanda_problema=null,
	$demanda_demanda=null,
	$demanda_programa=null,
	$demanda_licao=null,
	$demanda_evento=null,
	$demanda_link=null,
	$demanda_avaliacao=null,
	$demanda_tgn=null,
	$demanda_brainstorm=null,
	$demanda_gut=null,
	$demanda_causa_efeito=null,
	$demanda_arquivo=null,
	$demanda_forum=null,
	$demanda_checklist=null,
	$demanda_agenda=null,
	$demanda_agrupamento=null,
	$demanda_patrocinador=null,
	$demanda_template=null,
	$demanda_painel=null,
	$demanda_painel_odometro=null,
	$demanda_painel_composicao=null,
	$demanda_tr=null,
	$demanda_me=null,
	$demanda_acao_item=null,
	$demanda_beneficio=null,
	$demanda_painel_slideshow=null,
	$demanda_projeto_viabilidade=null,
	$demanda_projeto_abertura=null,
	$demanda_plano_gestao=null,
	$demanda_ssti=null,
	$demanda_laudo=null,
	$demanda_trelo=null,
	$demanda_trelo_cartao=null,
	$demanda_pdcl=null,
	$demanda_pdcl_item=null
	)
	{
	if (
		$demanda_projeto || 
		$demanda_tarefa || 
		$demanda_perspectiva || 
		$demanda_tema || 
		$demanda_objetivo || 
		$demanda_fator || 
		$demanda_estrategia || 
		$demanda_meta || 
		$demanda_pratica || 
		$demanda_acao || 
		$demanda_canvas || 
		$demanda_risco || 
		$demanda_risco_resposta || 
		$demanda_indicador || 
		$demanda_calendario || 
		$demanda_monitoramento || 
		$demanda_ata || 
		$demanda_mswot || 
		$demanda_swot || 
		$demanda_operativo || 
		$demanda_instrumento || 
		$demanda_recurso || 
		$demanda_problema || 
		$demanda_demanda || 
		$demanda_programa || 
		$demanda_licao || 
		$demanda_evento || 
		$demanda_link || 
		$demanda_avaliacao || 
		$demanda_tgn || 
		$demanda_brainstorm || 
		$demanda_gut || 
		$demanda_causa_efeito || 
		$demanda_arquivo || 
		$demanda_forum || 
		$demanda_checklist || 
		$demanda_agenda || 
		$demanda_agrupamento || 
		$demanda_patrocinador || 
		$demanda_template || 
		$demanda_painel || 
		$demanda_painel_odometro || 
		$demanda_painel_composicao || 
		$demanda_tr || 
		$demanda_me || 
		$demanda_acao_item || 
		$demanda_beneficio || 
		$demanda_painel_slideshow || 
		$demanda_projeto_viabilidade || 
		$demanda_projeto_abertura || 
		$demanda_plano_gestao|| 
		$demanda_ssti || 
		$demanda_laudo || 
		$demanda_trelo || 
		$demanda_trelo_cartao || 
		$demanda_pdcl || 
		$demanda_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('demanda_gestao');
			if ($uuid) $sql->adOnde('demanda_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('demanda_gestao_demanda ='.(int)$demanda_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('demanda_gestao');
		$sql->adCampo('count(demanda_gestao_id)');
		if ($uuid) $sql->adOnde('demanda_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('demanda_gestao_demanda ='.(int)$demanda_id);	
		if ($demanda_tarefa) $sql->adOnde('demanda_gestao_tarefa='.(int)$demanda_tarefa);
		elseif ($demanda_projeto) $sql->adOnde('demanda_gestao_projeto='.(int)$demanda_projeto);
		elseif ($demanda_perspectiva) $sql->adOnde('demanda_gestao_perspectiva='.(int)$demanda_perspectiva);
		elseif ($demanda_tema) $sql->adOnde('demanda_gestao_tema='.(int)$demanda_tema);
		elseif ($demanda_objetivo) $sql->adOnde('demanda_gestao_objetivo='.(int)$demanda_objetivo);
		elseif ($demanda_fator) $sql->adOnde('demanda_gestao_fator='.(int)$demanda_fator);
		elseif ($demanda_estrategia) $sql->adOnde('demanda_gestao_estrategia='.(int)$demanda_estrategia);
		elseif ($demanda_acao) $sql->adOnde('demanda_gestao_acao='.(int)$demanda_acao);
		elseif ($demanda_pratica) $sql->adOnde('demanda_gestao_pratica='.(int)$demanda_pratica);
		elseif ($demanda_meta) $sql->adOnde('demanda_gestao_meta='.(int)$demanda_meta);
		elseif ($demanda_canvas) $sql->adOnde('demanda_gestao_canvas='.(int)$demanda_canvas);
		elseif ($demanda_risco) $sql->adOnde('demanda_gestao_risco='.(int)$demanda_risco);
		elseif ($demanda_risco_resposta) $sql->adOnde('demanda_gestao_risco_resposta='.(int)$demanda_risco_resposta);
		elseif ($demanda_indicador) $sql->adOnde('demanda_gestao_indicador='.(int)$demanda_indicador);
		elseif ($demanda_calendario) $sql->adOnde('demanda_gestao_calendario='.(int)$demanda_calendario);
		elseif ($demanda_monitoramento) $sql->adOnde('demanda_gestao_monitoramento='.(int)$demanda_monitoramento);
		elseif ($demanda_ata) $sql->adOnde('demanda_gestao_ata='.(int)$demanda_ata);
		elseif ($demanda_mswot) $sql->adOnde('demanda_gestao_mswot='.(int)$demanda_mswot);
		elseif ($demanda_swot) $sql->adOnde('demanda_gestao_swot='.(int)$demanda_swot);
		elseif ($demanda_operativo) $sql->adOnde('demanda_gestao_operativo='.(int)$demanda_operativo);
		elseif ($demanda_instrumento) $sql->adOnde('demanda_gestao_instrumento='.(int)$demanda_instrumento);
		elseif ($demanda_recurso) $sql->adOnde('demanda_gestao_recurso='.(int)$demanda_recurso);
		elseif ($demanda_problema) $sql->adOnde('demanda_gestao_problema='.(int)$demanda_problema);
		
		elseif ($demanda_demanda) $sql->adOnde('demanda_gestao_semelhante='.(int)$demanda_demanda);
		
		elseif ($demanda_programa) $sql->adOnde('demanda_gestao_programa='.(int)$demanda_programa);
		elseif ($demanda_licao) $sql->adOnde('demanda_gestao_licao='.(int)$demanda_licao);
		elseif ($demanda_evento) $sql->adOnde('demanda_gestao_evento='.(int)$demanda_evento);
		elseif ($demanda_link) $sql->adOnde('demanda_gestao_link='.(int)$demanda_link);
		elseif ($demanda_avaliacao) $sql->adOnde('demanda_gestao_avaliacao='.(int)$demanda_avaliacao);
		elseif ($demanda_tgn) $sql->adOnde('demanda_gestao_tgn='.(int)$demanda_tgn);
		elseif ($demanda_brainstorm) $sql->adOnde('demanda_gestao_brainstorm='.(int)$demanda_brainstorm);
		elseif ($demanda_gut) $sql->adOnde('demanda_gestao_gut='.(int)$demanda_gut);
		elseif ($demanda_causa_efeito) $sql->adOnde('demanda_gestao_causa_efeito='.(int)$demanda_causa_efeito);
		elseif ($demanda_arquivo) $sql->adOnde('demanda_gestao_arquivo='.(int)$demanda_arquivo);
		elseif ($demanda_forum) $sql->adOnde('demanda_gestao_forum='.(int)$demanda_forum);
		elseif ($demanda_checklist) $sql->adOnde('demanda_gestao_checklist='.(int)$demanda_checklist);
		elseif ($demanda_agenda) $sql->adOnde('demanda_gestao_agenda='.(int)$demanda_agenda);
		elseif ($demanda_agrupamento) $sql->adOnde('demanda_gestao_agrupamento='.(int)$demanda_agrupamento);
		elseif ($demanda_patrocinador) $sql->adOnde('demanda_gestao_patrocinador='.(int)$demanda_patrocinador);
		elseif ($demanda_template) $sql->adOnde('demanda_gestao_template='.(int)$demanda_template);
		elseif ($demanda_painel) $sql->adOnde('demanda_gestao_painel='.(int)$demanda_painel);
		elseif ($demanda_painel_odometro) $sql->adOnde('demanda_gestao_painel_odometro='.(int)$demanda_painel_odometro);
		elseif ($demanda_painel_composicao) $sql->adOnde('demanda_gestao_painel_composicao='.(int)$demanda_painel_composicao);
		elseif ($demanda_tr) $sql->adOnde('demanda_gestao_tr='.(int)$demanda_tr);
		elseif ($demanda_me) $sql->adOnde('demanda_gestao_me='.(int)$demanda_me);
		elseif ($demanda_acao_item) $sql->adOnde('demanda_gestao_acao_item='.(int)$demanda_acao_item);
		elseif ($demanda_beneficio) $sql->adOnde('demanda_gestao_beneficio='.(int)$demanda_beneficio);
		elseif ($demanda_painel_slideshow) $sql->adOnde('demanda_gestao_painel_slideshow='.(int)$demanda_painel_slideshow);
		elseif ($demanda_projeto_viabilidade) $sql->adOnde('demanda_gestao_projeto_viabilidade='.(int)$demanda_projeto_viabilidade);
		elseif ($demanda_projeto_abertura) $sql->adOnde('demanda_gestao_projeto_abertura='.(int)$demanda_projeto_abertura);
		elseif ($demanda_plano_gestao) $sql->adOnde('demanda_gestao_plano_gestao='.(int)$demanda_plano_gestao);	
		elseif ($demanda_ssti) $sql->adOnde('demanda_gestao_ssti='.(int)$demanda_ssti);
		elseif ($demanda_laudo) $sql->adOnde('demanda_gestao_laudo='.(int)$demanda_laudo);
		elseif ($demanda_trelo) $sql->adOnde('demanda_gestao_trelo='.(int)$demanda_trelo);
		elseif ($demanda_trelo_cartao) $sql->adOnde('demanda_gestao_trelo_cartao='.(int)$demanda_trelo_cartao);
		elseif ($demanda_pdcl) $sql->adOnde('demanda_gestao_pdcl='.(int)$demanda_pdcl);
		elseif ($demanda_pdcl_item) $sql->adOnde('demanda_gestao_pdcl_item='.(int)$demanda_pdcl_item);

	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('demanda_gestao');
			$sql->adCampo('MAX(demanda_gestao_ordem)');
			if ($uuid) $sql->adOnde('demanda_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('demanda_gestao_demanda ='.(int)$demanda_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('demanda_gestao');
			if ($uuid) $sql->adInserir('demanda_gestao_uuid', $uuid);
			else $sql->adInserir('demanda_gestao_demanda', (int)$demanda_id);
			
			if ($demanda_tarefa) $sql->adInserir('demanda_gestao_tarefa', (int)$demanda_tarefa);
			if ($demanda_projeto) $sql->adInserir('demanda_gestao_projeto', (int)$demanda_projeto);
			elseif ($demanda_perspectiva) $sql->adInserir('demanda_gestao_perspectiva', (int)$demanda_perspectiva);
			elseif ($demanda_tema) $sql->adInserir('demanda_gestao_tema', (int)$demanda_tema);
			elseif ($demanda_objetivo) $sql->adInserir('demanda_gestao_objetivo', (int)$demanda_objetivo);
			elseif ($demanda_fator) $sql->adInserir('demanda_gestao_fator', (int)$demanda_fator);
			elseif ($demanda_estrategia) $sql->adInserir('demanda_gestao_estrategia', (int)$demanda_estrategia);
			elseif ($demanda_acao) $sql->adInserir('demanda_gestao_acao', (int)$demanda_acao);
			elseif ($demanda_pratica) $sql->adInserir('demanda_gestao_pratica', (int)$demanda_pratica);
			elseif ($demanda_meta) $sql->adInserir('demanda_gestao_meta', (int)$demanda_meta);
			elseif ($demanda_canvas) $sql->adInserir('demanda_gestao_canvas', (int)$demanda_canvas);
			elseif ($demanda_risco) $sql->adInserir('demanda_gestao_risco', (int)$demanda_risco);
			elseif ($demanda_risco_resposta) $sql->adInserir('demanda_gestao_risco_resposta', (int)$demanda_risco_resposta);
			elseif ($demanda_indicador) $sql->adInserir('demanda_gestao_indicador', (int)$demanda_indicador);
			elseif ($demanda_calendario) $sql->adInserir('demanda_gestao_calendario', (int)$demanda_calendario);
			elseif ($demanda_monitoramento) $sql->adInserir('demanda_gestao_monitoramento', (int)$demanda_monitoramento);
			elseif ($demanda_ata) $sql->adInserir('demanda_gestao_ata', (int)$demanda_ata);
			elseif ($demanda_mswot) $sql->adInserir('demanda_gestao_mswot', (int)$demanda_mswot);
			elseif ($demanda_swot) $sql->adInserir('demanda_gestao_swot', (int)$demanda_swot);
			elseif ($demanda_operativo) $sql->adInserir('demanda_gestao_operativo', (int)$demanda_operativo);
			elseif ($demanda_instrumento) $sql->adInserir('demanda_gestao_instrumento', (int)$demanda_instrumento);
			elseif ($demanda_recurso) $sql->adInserir('demanda_gestao_recurso', (int)$demanda_recurso);
			elseif ($demanda_problema) $sql->adInserir('demanda_gestao_problema', (int)$demanda_problema);
			
			elseif ($demanda_demanda) $sql->adInserir('demanda_gestao_semelhante', (int)$demanda_demanda);
			
			elseif ($demanda_programa) $sql->adInserir('demanda_gestao_programa', (int)$demanda_programa);
			elseif ($demanda_licao) $sql->adInserir('demanda_gestao_licao', (int)$demanda_licao);
			elseif ($demanda_evento) $sql->adInserir('demanda_gestao_evento', (int)$demanda_evento);
			elseif ($demanda_link) $sql->adInserir('demanda_gestao_link', (int)$demanda_link);
			elseif ($demanda_avaliacao) $sql->adInserir('demanda_gestao_avaliacao', (int)$demanda_avaliacao);
			elseif ($demanda_tgn) $sql->adInserir('demanda_gestao_tgn', (int)$demanda_tgn);
			elseif ($demanda_brainstorm) $sql->adInserir('demanda_gestao_brainstorm', (int)$demanda_brainstorm);
			elseif ($demanda_gut) $sql->adInserir('demanda_gestao_gut', (int)$demanda_gut);
			elseif ($demanda_causa_efeito) $sql->adInserir('demanda_gestao_causa_efeito', (int)$demanda_causa_efeito);
			elseif ($demanda_arquivo) $sql->adInserir('demanda_gestao_arquivo', (int)$demanda_arquivo);
			elseif ($demanda_forum) $sql->adInserir('demanda_gestao_forum', (int)$demanda_forum);
			elseif ($demanda_checklist) $sql->adInserir('demanda_gestao_checklist', (int)$demanda_checklist);
			elseif ($demanda_agenda) $sql->adInserir('demanda_gestao_agenda', (int)$demanda_agenda);
			elseif ($demanda_agrupamento) $sql->adInserir('demanda_gestao_agrupamento', (int)$demanda_agrupamento);
			elseif ($demanda_patrocinador) $sql->adInserir('demanda_gestao_patrocinador', (int)$demanda_patrocinador);
			elseif ($demanda_template) $sql->adInserir('demanda_gestao_template', (int)$demanda_template);
			elseif ($demanda_painel) $sql->adInserir('demanda_gestao_painel', (int)$demanda_painel);
			elseif ($demanda_painel_odometro) $sql->adInserir('demanda_gestao_painel_odometro', (int)$demanda_painel_odometro);
			elseif ($demanda_painel_composicao) $sql->adInserir('demanda_gestao_painel_composicao', (int)$demanda_painel_composicao);
			elseif ($demanda_tr) $sql->adInserir('demanda_gestao_tr', (int)$demanda_tr);
			elseif ($demanda_me) $sql->adInserir('demanda_gestao_me', (int)$demanda_me);
			elseif ($demanda_acao_item) $sql->adInserir('demanda_gestao_acao_item', (int)$demanda_acao_item);
			elseif ($demanda_beneficio) $sql->adInserir('demanda_gestao_beneficio', (int)$demanda_beneficio);
			elseif ($demanda_painel_slideshow) $sql->adInserir('demanda_gestao_painel_slideshow', (int)$demanda_painel_slideshow);
			elseif ($demanda_projeto_viabilidade) $sql->adInserir('demanda_gestao_projeto_viabilidade', (int)$demanda_projeto_viabilidade);
			elseif ($demanda_projeto_abertura) $sql->adInserir('demanda_gestao_projeto_abertura', (int)$demanda_projeto_abertura);
			elseif ($demanda_plano_gestao) $sql->adInserir('demanda_gestao_plano_gestao', (int)$demanda_plano_gestao);
			elseif ($demanda_ssti) $sql->adInserir('demanda_gestao_ssti', (int)$demanda_ssti);
			elseif ($demanda_laudo) $sql->adInserir('demanda_gestao_laudo', (int)$demanda_laudo);
			elseif ($demanda_trelo) $sql->adInserir('demanda_gestao_trelo', (int)$demanda_trelo);
			elseif ($demanda_trelo_cartao) $sql->adInserir('demanda_gestao_trelo_cartao', (int)$demanda_trelo_cartao);
			elseif ($demanda_pdcl) $sql->adInserir('demanda_gestao_pdcl', (int)$demanda_pdcl);
			elseif ($demanda_pdcl_item) $sql->adInserir('demanda_gestao_pdcl_item', (int)$demanda_pdcl_item);
			$sql->adInserir('demanda_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($demanda_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($demanda_id=0, $uuid='', $demanda_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('demanda_gestao');
	$sql->adOnde('demanda_gestao_id='.(int)$demanda_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($demanda_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($demanda_id=0, $uuid=''){	
	$saida=atualizar_gestao($demanda_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($demanda_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('demanda_gestao');
	$sql->adCampo('demanda_gestao.*');
	if ($uuid) $sql->adOnde('demanda_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('demanda_gestao_demanda ='.(int)$demanda_id);	
	$sql->adOrdem('demanda_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['demanda_gestao_ordem'].', '.$gestao_data['demanda_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['demanda_gestao_ordem'].', '.$gestao_data['demanda_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['demanda_gestao_ordem'].', '.$gestao_data['demanda_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['demanda_gestao_ordem'].', '.$gestao_data['demanda_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
				
		
		if ($gestao_data['demanda_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['demanda_gestao_tarefa']).'</td>';
		elseif ($gestao_data['demanda_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['demanda_gestao_projeto']).'</td>';
		elseif ($gestao_data['demanda_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['demanda_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['demanda_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['demanda_gestao_tema']).'</td>';
		elseif ($gestao_data['demanda_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['demanda_gestao_objetivo']).'</td>';
		elseif ($gestao_data['demanda_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['demanda_gestao_fator']).'</td>';
		elseif ($gestao_data['demanda_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['demanda_gestao_estrategia']).'</td>';
		elseif ($gestao_data['demanda_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['demanda_gestao_meta']).'</td>';
		elseif ($gestao_data['demanda_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['demanda_gestao_pratica']).'</td>';
		elseif ($gestao_data['demanda_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['demanda_gestao_acao']).'</td>';
		elseif ($gestao_data['demanda_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['demanda_gestao_canvas']).'</td>';
		elseif ($gestao_data['demanda_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['demanda_gestao_risco']).'</td>';
		elseif ($gestao_data['demanda_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['demanda_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['demanda_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['demanda_gestao_indicador']).'</td>';
		elseif ($gestao_data['demanda_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['demanda_gestao_calendario']).'</td>';
		elseif ($gestao_data['demanda_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['demanda_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['demanda_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['demanda_gestao_ata']).'</td>';
		elseif ($gestao_data['demanda_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['demanda_gestao_mswot']).'</td>';
		elseif ($gestao_data['demanda_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['demanda_gestao_swot']).'</td>';
		elseif ($gestao_data['demanda_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['demanda_gestao_operativo']).'</td>';
		elseif ($gestao_data['demanda_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['demanda_gestao_instrumento']).'</td>';
		elseif ($gestao_data['demanda_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['demanda_gestao_recurso']).'</td>';
		elseif ($gestao_data['demanda_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['demanda_gestao_problema']).'</td>';
		
		elseif ($gestao_data['demanda_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['demanda_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['demanda_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['demanda_gestao_programa']).'</td>';
		elseif ($gestao_data['demanda_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['demanda_gestao_licao']).'</td>';
		elseif ($gestao_data['demanda_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['demanda_gestao_evento']).'</td>';
		elseif ($gestao_data['demanda_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['demanda_gestao_link']).'</td>';
		elseif ($gestao_data['demanda_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['demanda_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['demanda_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['demanda_gestao_tgn']).'</td>';
		elseif ($gestao_data['demanda_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['demanda_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['demanda_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['demanda_gestao_gut']).'</td>';
		elseif ($gestao_data['demanda_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['demanda_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['demanda_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['demanda_gestao_arquivo']).'</td>';
		elseif ($gestao_data['demanda_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['demanda_gestao_forum']).'</td>';
		elseif ($gestao_data['demanda_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['demanda_gestao_checklist']).'</td>';
		elseif ($gestao_data['demanda_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['demanda_gestao_agenda']).'</td>';
		elseif ($gestao_data['demanda_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['demanda_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['demanda_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['demanda_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['demanda_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['demanda_gestao_template']).'</td>';
		elseif ($gestao_data['demanda_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['demanda_gestao_painel']).'</td>';
		elseif ($gestao_data['demanda_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['demanda_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['demanda_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['demanda_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['demanda_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['demanda_gestao_tr']).'</td>';	
		elseif ($gestao_data['demanda_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['demanda_gestao_me']).'</td>';	
		elseif ($gestao_data['demanda_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['demanda_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['demanda_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['demanda_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['demanda_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['demanda_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['demanda_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['demanda_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['demanda_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['demanda_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['demanda_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['demanda_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['demanda_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['demanda_gestao_ssti']).'</td>';
		elseif ($gestao_data['demanda_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['demanda_gestao_laudo']).'</td>';
		elseif ($gestao_data['demanda_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['demanda_gestao_trelo']).'</td>';
		elseif ($gestao_data['demanda_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['demanda_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['demanda_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['demanda_gestao_pdcl']).'</td>';
		elseif ($gestao_data['demanda_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['demanda_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['demanda_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}
	


$xajax->processRequest();

?>