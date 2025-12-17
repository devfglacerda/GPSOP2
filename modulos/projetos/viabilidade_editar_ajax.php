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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/viabilidade_editar_ajax_pro.php';

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

function exibir_patrocinadores($patrocinadores){
	global $config;
	$patrocinadores_selecionados=explode(',', $patrocinadores);
	$saida_patrocinadores='';
	if (count($patrocinadores_selecionados)) {
			$saida_patrocinadores.= '<table cellpadding=0 cellspacing=0>';
			$saida_patrocinadores.= '<tr><td class="texto" style="width:400px;">'.link_contato($patrocinadores_selecionados[0],'','','esquerda');
			$qnt_lista_patrocinadores=count($patrocinadores_selecionados);
			if ($qnt_lista_patrocinadores > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_patrocinadores; $i < $i_cmp; $i++) $lista.=link_contato($patrocinadores_selecionados[$i],'','','esquerda').'<br>';		
					$saida_patrocinadores.= dica('Outros Patrocinadores', 'Clique para visualizar os demais patrocinadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_patrocinadores\');">(+'.($qnt_lista_patrocinadores - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_patrocinadores"><br>'.$lista.'</span>';
					}
			$saida_patrocinadores.= '</td></tr></table>';
			} 
	else $saida_patrocinadores.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_patrocinadores',"innerHTML", utf8_encode($saida_patrocinadores));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_patrocinadores");

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



function mudar_posicao_gestao($ordem, $projeto_viabilidade_gestao_id, $direcao, $projeto_viabilidade_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $projeto_viabilidade_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_viabilidade_gestao');
		$sql->adOnde('projeto_viabilidade_gestao_id != '.(int)$projeto_viabilidade_gestao_id);
		if ($uuid) $sql->adOnde('projeto_viabilidade_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOrdem('projeto_viabilidade_gestao_ordem');
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
			$sql->adTabela('projeto_viabilidade_gestao');
			$sql->adAtualizar('projeto_viabilidade_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_viabilidade_gestao_id = '.(int)$projeto_viabilidade_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_viabilidade_gestao');
					$sql->adAtualizar('projeto_viabilidade_gestao_ordem', $idx);
					$sql->adOnde('projeto_viabilidade_gestao_id = '.(int)$acao['projeto_viabilidade_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_viabilidade_gestao');
					$sql->adAtualizar('projeto_viabilidade_gestao_ordem', $idx + 1);
					$sql->adOnde('projeto_viabilidade_gestao_id = '.(int)$acao['projeto_viabilidade_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($projeto_viabilidade_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$projeto_viabilidade_id=0, 
	$uuid='',  
	
	$projeto_viabilidade_projeto=null,
	$projeto_viabilidade_tarefa=null,
	$projeto_viabilidade_perspectiva=null,
	$projeto_viabilidade_tema=null,
	$projeto_viabilidade_objetivo=null,
	$projeto_viabilidade_fator=null,
	$projeto_viabilidade_estrategia=null,
	$projeto_viabilidade_meta=null,
	$projeto_viabilidade_pratica=null,
	$projeto_viabilidade_acao=null,
	$projeto_viabilidade_canvas=null,
	$projeto_viabilidade_risco=null,
	$projeto_viabilidade_risco_resposta=null,
	$projeto_viabilidade_indicador=null,
	$projeto_viabilidade_calendario=null,
	$projeto_viabilidade_monitoramento=null,
	$projeto_viabilidade_ata=null,
	$projeto_viabilidade_mswot=null,
	$projeto_viabilidade_swot=null,
	$projeto_viabilidade_operativo=null,
	$projeto_viabilidade_instrumento=null,
	$projeto_viabilidade_recurso=null,
	$projeto_viabilidade_problema=null,
	$projeto_viabilidade_demanda=null,
	$projeto_viabilidade_programa=null,
	$projeto_viabilidade_licao=null,
	$projeto_viabilidade_evento=null,
	$projeto_viabilidade_link=null,
	$projeto_viabilidade_avaliacao=null,
	$projeto_viabilidade_tgn=null,
	$projeto_viabilidade_brainstorm=null,
	$projeto_viabilidade_gut=null,
	$projeto_viabilidade_causa_efeito=null,
	$projeto_viabilidade_arquivo=null,
	$projeto_viabilidade_forum=null,
	$projeto_viabilidade_checklist=null,
	$projeto_viabilidade_agenda=null,
	$projeto_viabilidade_agrupamento=null,
	$projeto_viabilidade_patrocinador=null,
	$projeto_viabilidade_template=null,
	$projeto_viabilidade_painel=null,
	$projeto_viabilidade_painel_odometro=null,
	$projeto_viabilidade_painel_composicao=null,
	$projeto_viabilidade_tr=null,
	$projeto_viabilidade_me=null,
	$projeto_viabilidade_acao_item=null,
	$projeto_viabilidade_beneficio=null,
	$projeto_viabilidade_painel_slideshow=null,
	$projeto_viabilidade_projeto_viabilidade=null,
	$projeto_viabilidade_projeto_abertura=null,
	$projeto_viabilidade_plano_gestao=null,
	$projeto_viabilidade_ssti=null,
	$projeto_viabilidade_laudo=null,
	$projeto_viabilidade_trelo=null,
	$projeto_viabilidade_trelo_cartao=null,
	$projeto_viabilidade_pdcl=null,
	$projeto_viabilidade_pdcl_item=null
	)
	{
	if (
		$projeto_viabilidade_projeto || 
		$projeto_viabilidade_tarefa || 
		$projeto_viabilidade_perspectiva || 
		$projeto_viabilidade_tema || 
		$projeto_viabilidade_objetivo || 
		$projeto_viabilidade_fator || 
		$projeto_viabilidade_estrategia || 
		$projeto_viabilidade_meta || 
		$projeto_viabilidade_pratica || 
		$projeto_viabilidade_acao || 
		$projeto_viabilidade_canvas || 
		$projeto_viabilidade_risco || 
		$projeto_viabilidade_risco_resposta || 
		$projeto_viabilidade_indicador || 
		$projeto_viabilidade_calendario || 
		$projeto_viabilidade_monitoramento || 
		$projeto_viabilidade_ata || 
		$projeto_viabilidade_mswot || 
		$projeto_viabilidade_swot || 
		$projeto_viabilidade_operativo || 
		$projeto_viabilidade_instrumento || 
		$projeto_viabilidade_recurso || 
		$projeto_viabilidade_problema || 
		$projeto_viabilidade_demanda || 
		$projeto_viabilidade_programa || 
		$projeto_viabilidade_licao || 
		$projeto_viabilidade_evento || 
		$projeto_viabilidade_link || 
		$projeto_viabilidade_avaliacao || 
		$projeto_viabilidade_tgn || 
		$projeto_viabilidade_brainstorm || 
		$projeto_viabilidade_gut || 
		$projeto_viabilidade_causa_efeito || 
		$projeto_viabilidade_arquivo || 
		$projeto_viabilidade_forum || 
		$projeto_viabilidade_checklist || 
		$projeto_viabilidade_agenda || 
		$projeto_viabilidade_agrupamento || 
		$projeto_viabilidade_patrocinador || 
		$projeto_viabilidade_template || 
		$projeto_viabilidade_painel || 
		$projeto_viabilidade_painel_odometro || 
		$projeto_viabilidade_painel_composicao || 
		$projeto_viabilidade_tr || 
		$projeto_viabilidade_me || 
		$projeto_viabilidade_acao_item || 
		$projeto_viabilidade_beneficio || 
		$projeto_viabilidade_painel_slideshow || 
		$projeto_viabilidade_projeto_viabilidade || 
		$projeto_viabilidade_projeto_abertura || 
		$projeto_viabilidade_plano_gestao|| 
		$projeto_viabilidade_ssti || 
		$projeto_viabilidade_laudo || 
		$projeto_viabilidade_trelo || 
		$projeto_viabilidade_trelo_cartao || 
		$projeto_viabilidade_pdcl || 
		$projeto_viabilidade_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('projeto_viabilidade_gestao');
			if ($uuid) $sql->adOnde('projeto_viabilidade_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$projeto_viabilidade_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('projeto_viabilidade_gestao');
		$sql->adCampo('count(projeto_viabilidade_gestao_id)');
		if ($uuid) $sql->adOnde('projeto_viabilidade_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$projeto_viabilidade_id);	
		if ($projeto_viabilidade_tarefa) $sql->adOnde('projeto_viabilidade_gestao_tarefa='.(int)$projeto_viabilidade_tarefa);
		elseif ($projeto_viabilidade_projeto) $sql->adOnde('projeto_viabilidade_gestao_projeto='.(int)$projeto_viabilidade_projeto);
		elseif ($projeto_viabilidade_perspectiva) $sql->adOnde('projeto_viabilidade_gestao_perspectiva='.(int)$projeto_viabilidade_perspectiva);
		elseif ($projeto_viabilidade_tema) $sql->adOnde('projeto_viabilidade_gestao_tema='.(int)$projeto_viabilidade_tema);
		elseif ($projeto_viabilidade_objetivo) $sql->adOnde('projeto_viabilidade_gestao_objetivo='.(int)$projeto_viabilidade_objetivo);
		elseif ($projeto_viabilidade_fator) $sql->adOnde('projeto_viabilidade_gestao_fator='.(int)$projeto_viabilidade_fator);
		elseif ($projeto_viabilidade_estrategia) $sql->adOnde('projeto_viabilidade_gestao_estrategia='.(int)$projeto_viabilidade_estrategia);
		elseif ($projeto_viabilidade_acao) $sql->adOnde('projeto_viabilidade_gestao_acao='.(int)$projeto_viabilidade_acao);
		elseif ($projeto_viabilidade_pratica) $sql->adOnde('projeto_viabilidade_gestao_pratica='.(int)$projeto_viabilidade_pratica);
		elseif ($projeto_viabilidade_meta) $sql->adOnde('projeto_viabilidade_gestao_meta='.(int)$projeto_viabilidade_meta);
		elseif ($projeto_viabilidade_canvas) $sql->adOnde('projeto_viabilidade_gestao_canvas='.(int)$projeto_viabilidade_canvas);
		elseif ($projeto_viabilidade_risco) $sql->adOnde('projeto_viabilidade_gestao_risco='.(int)$projeto_viabilidade_risco);
		elseif ($projeto_viabilidade_risco_resposta) $sql->adOnde('projeto_viabilidade_gestao_risco_resposta='.(int)$projeto_viabilidade_risco_resposta);
		elseif ($projeto_viabilidade_indicador) $sql->adOnde('projeto_viabilidade_gestao_indicador='.(int)$projeto_viabilidade_indicador);
		elseif ($projeto_viabilidade_calendario) $sql->adOnde('projeto_viabilidade_gestao_calendario='.(int)$projeto_viabilidade_calendario);
		elseif ($projeto_viabilidade_monitoramento) $sql->adOnde('projeto_viabilidade_gestao_monitoramento='.(int)$projeto_viabilidade_monitoramento);
		elseif ($projeto_viabilidade_ata) $sql->adOnde('projeto_viabilidade_gestao_ata='.(int)$projeto_viabilidade_ata);
		elseif ($projeto_viabilidade_mswot) $sql->adOnde('projeto_viabilidade_gestao_mswot='.(int)$projeto_viabilidade_mswot);
		elseif ($projeto_viabilidade_swot) $sql->adOnde('projeto_viabilidade_gestao_swot='.(int)$projeto_viabilidade_swot);
		elseif ($projeto_viabilidade_operativo) $sql->adOnde('projeto_viabilidade_gestao_operativo='.(int)$projeto_viabilidade_operativo);
		elseif ($projeto_viabilidade_instrumento) $sql->adOnde('projeto_viabilidade_gestao_instrumento='.(int)$projeto_viabilidade_instrumento);
		elseif ($projeto_viabilidade_recurso) $sql->adOnde('projeto_viabilidade_gestao_recurso='.(int)$projeto_viabilidade_recurso);
		elseif ($projeto_viabilidade_problema) $sql->adOnde('projeto_viabilidade_gestao_problema='.(int)$projeto_viabilidade_problema);
		elseif ($projeto_viabilidade_demanda) $sql->adOnde('projeto_viabilidade_gestao_demanda='.(int)$projeto_viabilidade_demanda);
		elseif ($projeto_viabilidade_programa) $sql->adOnde('projeto_viabilidade_gestao_programa='.(int)$projeto_viabilidade_programa);
		elseif ($projeto_viabilidade_licao) $sql->adOnde('projeto_viabilidade_gestao_licao='.(int)$projeto_viabilidade_licao);
		elseif ($projeto_viabilidade_evento) $sql->adOnde('projeto_viabilidade_gestao_evento='.(int)$projeto_viabilidade_evento);
		elseif ($projeto_viabilidade_link) $sql->adOnde('projeto_viabilidade_gestao_link='.(int)$projeto_viabilidade_link);
		elseif ($projeto_viabilidade_avaliacao) $sql->adOnde('projeto_viabilidade_gestao_avaliacao='.(int)$projeto_viabilidade_avaliacao);
		elseif ($projeto_viabilidade_tgn) $sql->adOnde('projeto_viabilidade_gestao_tgn='.(int)$projeto_viabilidade_tgn);
		elseif ($projeto_viabilidade_brainstorm) $sql->adOnde('projeto_viabilidade_gestao_brainstorm='.(int)$projeto_viabilidade_brainstorm);
		elseif ($projeto_viabilidade_gut) $sql->adOnde('projeto_viabilidade_gestao_gut='.(int)$projeto_viabilidade_gut);
		elseif ($projeto_viabilidade_causa_efeito) $sql->adOnde('projeto_viabilidade_gestao_causa_efeito='.(int)$projeto_viabilidade_causa_efeito);
		elseif ($projeto_viabilidade_arquivo) $sql->adOnde('projeto_viabilidade_gestao_arquivo='.(int)$projeto_viabilidade_arquivo);
		elseif ($projeto_viabilidade_forum) $sql->adOnde('projeto_viabilidade_gestao_forum='.(int)$projeto_viabilidade_forum);
		elseif ($projeto_viabilidade_checklist) $sql->adOnde('projeto_viabilidade_gestao_checklist='.(int)$projeto_viabilidade_checklist);
		elseif ($projeto_viabilidade_agenda) $sql->adOnde('projeto_viabilidade_gestao_agenda='.(int)$projeto_viabilidade_agenda);
		elseif ($projeto_viabilidade_agrupamento) $sql->adOnde('projeto_viabilidade_gestao_agrupamento='.(int)$projeto_viabilidade_agrupamento);
		elseif ($projeto_viabilidade_patrocinador) $sql->adOnde('projeto_viabilidade_gestao_patrocinador='.(int)$projeto_viabilidade_patrocinador);
		elseif ($projeto_viabilidade_template) $sql->adOnde('projeto_viabilidade_gestao_template='.(int)$projeto_viabilidade_template);
		elseif ($projeto_viabilidade_painel) $sql->adOnde('projeto_viabilidade_gestao_painel='.(int)$projeto_viabilidade_painel);
		elseif ($projeto_viabilidade_painel_odometro) $sql->adOnde('projeto_viabilidade_gestao_painel_odometro='.(int)$projeto_viabilidade_painel_odometro);
		elseif ($projeto_viabilidade_painel_composicao) $sql->adOnde('projeto_viabilidade_gestao_painel_composicao='.(int)$projeto_viabilidade_painel_composicao);
		elseif ($projeto_viabilidade_tr) $sql->adOnde('projeto_viabilidade_gestao_tr='.(int)$projeto_viabilidade_tr);
		elseif ($projeto_viabilidade_me) $sql->adOnde('projeto_viabilidade_gestao_me='.(int)$projeto_viabilidade_me);
		elseif ($projeto_viabilidade_acao_item) $sql->adOnde('projeto_viabilidade_gestao_acao_item='.(int)$projeto_viabilidade_acao_item);
		elseif ($projeto_viabilidade_beneficio) $sql->adOnde('projeto_viabilidade_gestao_beneficio='.(int)$projeto_viabilidade_beneficio);
		elseif ($projeto_viabilidade_painel_slideshow) $sql->adOnde('projeto_viabilidade_gestao_painel_slideshow='.(int)$projeto_viabilidade_painel_slideshow);
		
		elseif ($projeto_viabilidade_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_gestao_semelhante='.(int)$projeto_viabilidade_projeto_viabilidade);
		
		elseif ($projeto_viabilidade_projeto_abertura) $sql->adOnde('projeto_viabilidade_gestao_projeto_abertura='.(int)$projeto_viabilidade_projeto_abertura);
		elseif ($projeto_viabilidade_plano_gestao) $sql->adOnde('projeto_viabilidade_gestao_plano_gestao='.(int)$projeto_viabilidade_plano_gestao);
		elseif ($projeto_viabilidade_ssti) $sql->adOnde('projeto_viabilidade_gestao_ssti='.(int)$projeto_viabilidade_ssti);
		elseif ($projeto_viabilidade_laudo) $sql->adOnde('projeto_viabilidade_gestao_laudo='.(int)$projeto_viabilidade_laudo);
		elseif ($projeto_viabilidade_trelo) $sql->adOnde('projeto_viabilidade_gestao_trelo='.(int)$projeto_viabilidade_trelo);
		elseif ($projeto_viabilidade_trelo_cartao) $sql->adOnde('projeto_viabilidade_gestao_trelo_cartao='.(int)$projeto_viabilidade_trelo_cartao);
		elseif ($projeto_viabilidade_pdcl) $sql->adOnde('projeto_viabilidade_gestao_pdcl='.(int)$projeto_viabilidade_pdcl);
		elseif ($projeto_viabilidade_pdcl_item) $sql->adOnde('projeto_viabilidade_gestao_pdcl_item='.(int)$projeto_viabilidade_pdcl_item);
	
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('projeto_viabilidade_gestao');
			$sql->adCampo('MAX(projeto_viabilidade_gestao_ordem)');
			if ($uuid) $sql->adOnde('projeto_viabilidade_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$projeto_viabilidade_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('projeto_viabilidade_gestao');
			if ($uuid) $sql->adInserir('projeto_viabilidade_gestao_uuid', $uuid);
			else $sql->adInserir('projeto_viabilidade_gestao_projeto_viabilidade', (int)$projeto_viabilidade_id);
			
			if ($projeto_viabilidade_tarefa) $sql->adInserir('projeto_viabilidade_gestao_tarefa', (int)$projeto_viabilidade_tarefa);
			if ($projeto_viabilidade_projeto) $sql->adInserir('projeto_viabilidade_gestao_projeto', (int)$projeto_viabilidade_projeto);
			elseif ($projeto_viabilidade_perspectiva) $sql->adInserir('projeto_viabilidade_gestao_perspectiva', (int)$projeto_viabilidade_perspectiva);
			elseif ($projeto_viabilidade_tema) $sql->adInserir('projeto_viabilidade_gestao_tema', (int)$projeto_viabilidade_tema);
			elseif ($projeto_viabilidade_objetivo) $sql->adInserir('projeto_viabilidade_gestao_objetivo', (int)$projeto_viabilidade_objetivo);
			elseif ($projeto_viabilidade_fator) $sql->adInserir('projeto_viabilidade_gestao_fator', (int)$projeto_viabilidade_fator);
			elseif ($projeto_viabilidade_estrategia) $sql->adInserir('projeto_viabilidade_gestao_estrategia', (int)$projeto_viabilidade_estrategia);
			elseif ($projeto_viabilidade_acao) $sql->adInserir('projeto_viabilidade_gestao_acao', (int)$projeto_viabilidade_acao);
			elseif ($projeto_viabilidade_pratica) $sql->adInserir('projeto_viabilidade_gestao_pratica', (int)$projeto_viabilidade_pratica);
			elseif ($projeto_viabilidade_meta) $sql->adInserir('projeto_viabilidade_gestao_meta', (int)$projeto_viabilidade_meta);
			elseif ($projeto_viabilidade_canvas) $sql->adInserir('projeto_viabilidade_gestao_canvas', (int)$projeto_viabilidade_canvas);
			elseif ($projeto_viabilidade_risco) $sql->adInserir('projeto_viabilidade_gestao_risco', (int)$projeto_viabilidade_risco);
			elseif ($projeto_viabilidade_risco_resposta) $sql->adInserir('projeto_viabilidade_gestao_risco_resposta', (int)$projeto_viabilidade_risco_resposta);
			elseif ($projeto_viabilidade_indicador) $sql->adInserir('projeto_viabilidade_gestao_indicador', (int)$projeto_viabilidade_indicador);
			elseif ($projeto_viabilidade_calendario) $sql->adInserir('projeto_viabilidade_gestao_calendario', (int)$projeto_viabilidade_calendario);
			elseif ($projeto_viabilidade_monitoramento) $sql->adInserir('projeto_viabilidade_gestao_monitoramento', (int)$projeto_viabilidade_monitoramento);
			elseif ($projeto_viabilidade_ata) $sql->adInserir('projeto_viabilidade_gestao_ata', (int)$projeto_viabilidade_ata);
			elseif ($projeto_viabilidade_mswot) $sql->adInserir('projeto_viabilidade_gestao_mswot', (int)$projeto_viabilidade_mswot);
			elseif ($projeto_viabilidade_swot) $sql->adInserir('projeto_viabilidade_gestao_swot', (int)$projeto_viabilidade_swot);
			elseif ($projeto_viabilidade_operativo) $sql->adInserir('projeto_viabilidade_gestao_operativo', (int)$projeto_viabilidade_operativo);
			elseif ($projeto_viabilidade_instrumento) $sql->adInserir('projeto_viabilidade_gestao_instrumento', (int)$projeto_viabilidade_instrumento);
			elseif ($projeto_viabilidade_recurso) $sql->adInserir('projeto_viabilidade_gestao_recurso', (int)$projeto_viabilidade_recurso);
			elseif ($projeto_viabilidade_problema) $sql->adInserir('projeto_viabilidade_gestao_problema', (int)$projeto_viabilidade_problema);
			elseif ($projeto_viabilidade_demanda) $sql->adInserir('projeto_viabilidade_gestao_demanda', (int)$projeto_viabilidade_demanda);
			elseif ($projeto_viabilidade_programa) $sql->adInserir('projeto_viabilidade_gestao_programa', (int)$projeto_viabilidade_programa);
			elseif ($projeto_viabilidade_licao) $sql->adInserir('projeto_viabilidade_gestao_licao', (int)$projeto_viabilidade_licao);
			elseif ($projeto_viabilidade_evento) $sql->adInserir('projeto_viabilidade_gestao_evento', (int)$projeto_viabilidade_evento);
			elseif ($projeto_viabilidade_link) $sql->adInserir('projeto_viabilidade_gestao_link', (int)$projeto_viabilidade_link);
			elseif ($projeto_viabilidade_avaliacao) $sql->adInserir('projeto_viabilidade_gestao_avaliacao', (int)$projeto_viabilidade_avaliacao);
			elseif ($projeto_viabilidade_tgn) $sql->adInserir('projeto_viabilidade_gestao_tgn', (int)$projeto_viabilidade_tgn);
			elseif ($projeto_viabilidade_brainstorm) $sql->adInserir('projeto_viabilidade_gestao_brainstorm', (int)$projeto_viabilidade_brainstorm);
			elseif ($projeto_viabilidade_gut) $sql->adInserir('projeto_viabilidade_gestao_gut', (int)$projeto_viabilidade_gut);
			elseif ($projeto_viabilidade_causa_efeito) $sql->adInserir('projeto_viabilidade_gestao_causa_efeito', (int)$projeto_viabilidade_causa_efeito);
			elseif ($projeto_viabilidade_arquivo) $sql->adInserir('projeto_viabilidade_gestao_arquivo', (int)$projeto_viabilidade_arquivo);
			elseif ($projeto_viabilidade_forum) $sql->adInserir('projeto_viabilidade_gestao_forum', (int)$projeto_viabilidade_forum);
			elseif ($projeto_viabilidade_checklist) $sql->adInserir('projeto_viabilidade_gestao_checklist', (int)$projeto_viabilidade_checklist);
			elseif ($projeto_viabilidade_agenda) $sql->adInserir('projeto_viabilidade_gestao_agenda', (int)$projeto_viabilidade_agenda);
			elseif ($projeto_viabilidade_agrupamento) $sql->adInserir('projeto_viabilidade_gestao_agrupamento', (int)$projeto_viabilidade_agrupamento);
			elseif ($projeto_viabilidade_patrocinador) $sql->adInserir('projeto_viabilidade_gestao_patrocinador', (int)$projeto_viabilidade_patrocinador);
			elseif ($projeto_viabilidade_template) $sql->adInserir('projeto_viabilidade_gestao_template', (int)$projeto_viabilidade_template);
			elseif ($projeto_viabilidade_painel) $sql->adInserir('projeto_viabilidade_gestao_painel', (int)$projeto_viabilidade_painel);
			elseif ($projeto_viabilidade_painel_odometro) $sql->adInserir('projeto_viabilidade_gestao_painel_odometro', (int)$projeto_viabilidade_painel_odometro);
			elseif ($projeto_viabilidade_painel_composicao) $sql->adInserir('projeto_viabilidade_gestao_painel_composicao', (int)$projeto_viabilidade_painel_composicao);
			elseif ($projeto_viabilidade_tr) $sql->adInserir('projeto_viabilidade_gestao_tr', (int)$projeto_viabilidade_tr);
			elseif ($projeto_viabilidade_me) $sql->adInserir('projeto_viabilidade_gestao_me', (int)$projeto_viabilidade_me);
			elseif ($projeto_viabilidade_acao_item) $sql->adInserir('projeto_viabilidade_gestao_acao_item', (int)$projeto_viabilidade_acao_item);
			elseif ($projeto_viabilidade_beneficio) $sql->adInserir('projeto_viabilidade_gestao_beneficio', (int)$projeto_viabilidade_beneficio);
			elseif ($projeto_viabilidade_painel_slideshow) $sql->adInserir('projeto_viabilidade_gestao_painel_slideshow', (int)$projeto_viabilidade_painel_slideshow);
			elseif ($projeto_viabilidade_projeto_viabilidade) $sql->adInserir('projeto_viabilidade_gestao_semelhante', (int)$projeto_viabilidade_projeto_viabilidade);
			elseif ($projeto_viabilidade_projeto_abertura) $sql->adInserir('projeto_viabilidade_gestao_projeto_abertura', (int)$projeto_viabilidade_projeto_abertura);
			elseif ($projeto_viabilidade_plano_gestao) $sql->adInserir('projeto_viabilidade_gestao_plano_gestao', (int)$projeto_viabilidade_plano_gestao);
			elseif ($projeto_viabilidade_ssti) $sql->adInserir('projeto_viabilidade_gestao_ssti', (int)$projeto_viabilidade_ssti);
			elseif ($projeto_viabilidade_laudo) $sql->adInserir('projeto_viabilidade_gestao_laudo', (int)$projeto_viabilidade_laudo);
			elseif ($projeto_viabilidade_trelo) $sql->adInserir('projeto_viabilidade_gestao_trelo', (int)$projeto_viabilidade_trelo);
			elseif ($projeto_viabilidade_trelo_cartao) $sql->adInserir('projeto_viabilidade_gestao_trelo_cartao', (int)$projeto_viabilidade_trelo_cartao);
			elseif ($projeto_viabilidade_pdcl) $sql->adInserir('projeto_viabilidade_gestao_pdcl', (int)$projeto_viabilidade_pdcl);
			elseif ($projeto_viabilidade_pdcl_item) $sql->adInserir('projeto_viabilidade_gestao_pdcl_item', (int)$projeto_viabilidade_pdcl_item);
		
			$sql->adInserir('projeto_viabilidade_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($projeto_viabilidade_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($projeto_viabilidade_id=0, $uuid='', $projeto_viabilidade_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_viabilidade_gestao');
	$sql->adOnde('projeto_viabilidade_gestao_id='.(int)$projeto_viabilidade_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($projeto_viabilidade_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($projeto_viabilidade_id=0, $uuid=''){	
	$saida=atualizar_gestao($projeto_viabilidade_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($projeto_viabilidade_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('projeto_viabilidade_gestao');
	$sql->adCampo('projeto_viabilidade_gestao.*');
	if ($uuid) $sql->adOnde('projeto_viabilidade_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$projeto_viabilidade_id);	
	$sql->adOrdem('projeto_viabilidade_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
				
		
		if ($gestao_data['projeto_viabilidade_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_viabilidade_gestao_tarefa']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_viabilidade_gestao_projeto']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_viabilidade_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['projeto_viabilidade_gestao_tema']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_viabilidade_gestao_objetivo']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_viabilidade_gestao_fator']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_viabilidade_gestao_estrategia']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_viabilidade_gestao_meta']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_viabilidade_gestao_pratica']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_viabilidade_gestao_acao']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_viabilidade_gestao_canvas']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['projeto_viabilidade_gestao_risco']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_viabilidade_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_viabilidade_gestao_indicador']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_viabilidade_gestao_calendario']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_viabilidade_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_viabilidade_gestao_ata']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_viabilidade_gestao_mswot']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['projeto_viabilidade_gestao_swot']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_viabilidade_gestao_operativo']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_viabilidade_gestao_instrumento']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_viabilidade_gestao_recurso']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['projeto_viabilidade_gestao_problema']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_viabilidade_gestao_demanda']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['projeto_viabilidade_gestao_programa']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_viabilidade_gestao_licao']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_viabilidade_gestao_evento']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['projeto_viabilidade_gestao_link']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_viabilidade_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_viabilidade_gestao_tgn']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_viabilidade_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_viabilidade_gestao_gut']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_viabilidade_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_viabilidade_gestao_arquivo']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_viabilidade_gestao_forum']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_viabilidade_gestao_checklist']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_viabilidade_gestao_agenda']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_viabilidade_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_viabilidade_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['projeto_viabilidade_gestao_template']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['projeto_viabilidade_gestao_painel']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_viabilidade_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_viabilidade_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['projeto_viabilidade_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['projeto_viabilidade_gestao_tr']).'</td>';	
		elseif ($gestao_data['projeto_viabilidade_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['projeto_viabilidade_gestao_me']).'</td>';	
		elseif ($gestao_data['projeto_viabilidade_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_viabilidade_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['projeto_viabilidade_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_viabilidade_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['projeto_viabilidade_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_viabilidade_gestao_painel_slideshow']).'</td>';	
		
		elseif ($gestao_data['projeto_viabilidade_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_viabilidade_gestao_semelhante']).'</td>';	
		
		elseif ($gestao_data['projeto_viabilidade_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_viabilidade_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['projeto_viabilidade_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_viabilidade_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['projeto_viabilidade_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_viabilidade_gestao_ssti']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_viabilidade_gestao_laudo']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_viabilidade_gestao_trelo']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_viabilidade_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_viabilidade_gestao_pdcl']).'</td>';
		elseif ($gestao_data['projeto_viabilidade_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_viabilidade_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['projeto_viabilidade_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}	
	


$xajax->processRequest();

?>