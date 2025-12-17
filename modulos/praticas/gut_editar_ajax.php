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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/gut_editar_ajax_pro.php';	

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
	
function mudar_posicao_gut_ajax($ordem, $gut_linha_id, $direcao, $gut_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $gut_linha_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('gut_linha');
		$sql->adOnde('gut_linha_id != '.$gut_linha_id);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('gut_id = '.$gut_id);
		$sql->adOrdem('ordem');
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
			$sql->adTabela('gut_linha');
			$sql->adAtualizar('ordem', $novo_ui_ordem);
			$sql->adOnde('gut_linha_id = '.$gut_linha_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('gut_linha');
					$sql->adAtualizar('ordem', $idx);
					$sql->adOnde('gut_linha_id = '.$acao['gut_linha_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('gut_linha');
					$sql->adAtualizar('ordem', $idx + 1);
					$sql->adOnde('gut_linha_id = '.$acao['gut_linha_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_guts($gut_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("guts","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_gut_ajax");		

function incluir_gut_ajax($gut_linha_id=0, $gut_id=0, $uuid='', $gut_g, $gut_u, $gut_t, $gut_texto){

	$gut_texto=previnirXSS(utf8_decode($gut_texto));
	$sql = new BDConsulta;
	
	if ($gut_linha_id){
		$sql->adTabela('gut_linha');
		$sql->adAtualizar('gut_g', $gut_g);	
		$sql->adAtualizar('gut_u', $gut_u);
		$sql->adAtualizar('gut_t', $gut_t);
		$sql->adAtualizar('gut_texto', $gut_texto);	
		$sql->adOnde('gut_linha_id ='.(int)$gut_linha_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('gut_linha');
		$sql->adCampo('count(DISTINCT gut_linha_id) AS soma');
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('gut_id ='.(int)$gut_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('gut_linha');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('gut_id', $gut_id);
		$sql->adInserir('ordem', $soma_total);
		$sql->adInserir('gut_g', $gut_g);
		$sql->adInserir('gut_u', $gut_u);
		$sql->adInserir('gut_t', $gut_t);
		$sql->adInserir('gut_texto', $gut_texto);
		$sql->exec();
		}
	$saida=atualizar_guts($gut_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("guts","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("incluir_gut_ajax");	

function excluir_gut_ajax($gut_linha_id, $gut_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('gut_linha');
	$sql->adOnde('gut_linha_id='.(int)$gut_linha_id);
	$sql->exec();
	$saida=atualizar_guts($gut_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("guts","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("excluir_gut_ajax");	

function atualizar_guts($gut_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('gut_linha');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('gut_id = '.$gut_id);
	$sql->adCampo('gut_linha.*');
	$sql->adOrdem('ordem');
	$gut=$sql->ListaChave('gut_linha_id');
	$sql->limpar();
	$saida='';
	if (count($gut)) {
		$saida.= '<table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th></th><th>'.dica('Texto', 'Texto da matriz GUT').'Texto'.dicaF().'</th><th>'.dica('Gravidade','A componente de gravidade da matriz GUT.').'Gravidade'.dicaF().'</th><th>'.dica('Urgência','A componente de urgência da matriz GUT.').'Urgência'.dicaF().'</th><th>'.dica('Tendência','A componente de tendência da matriz GUT.').'Tendência'.dicaF().'</th><th></th></tr>';
		foreach ($gut as $gut_linha_id => $data) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gut('.(int)$data['ordem'].', '.$data['gut_linha_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gut('.(int)$data['ordem'].', '.$data['gut_linha_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gut('.(int)$data['ordem'].', '.$data['gut_linha_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gut('.(int)$data['ordem'].', '.$data['gut_linha_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.$data['gut_texto'].'</td>';
			$saida.= '<td align="center">'.$data['gut_g'].'</td>';
			$saida.= '<td align="center">'.$data['gut_u'].'</td>';
			$saida.= '<td align="center">'.$data['gut_t'].'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_gut('.$data['gut_linha_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a linha.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta linha da matriz GUT?\')) {excluir_gut('.$data['gut_linha_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir a linha.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}
	
	

function editar_gut($gut_linha_id){
	global $config, $Aplic;

	$sql = new BDConsulta;
	$sql->adTabela('gut_linha');
	$sql->adOnde('gut_linha_id = '.(int)$gut_linha_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$objResposta = new xajaxResponse();
	$objResposta->assign("gut_linha_id","value", $gut_linha_id);
	$objResposta->assign("gut_g","value", $linha['gut_g']);
	$objResposta->assign("gut_u","value", $linha['gut_u']);
	$objResposta->assign("gut_t","value", $linha['gut_t']);
	$objResposta->assign("gut_texto","value", utf8_encode($linha['gut_texto']));	
	$objResposta->assign("apoio1","value", utf8_encode($linha['gut_texto']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_gut");	



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

function mudar_posicao_gestao($ordem, $gut_gestao_id, $direcao, $gut_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $gut_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('gut_gestao');
		$sql->adOnde('gut_gestao_id != '.(int)$gut_gestao_id);
		if ($uuid) $sql->adOnde('gut_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('gut_gestao_gut = '.(int)$gut_id);
		$sql->adOrdem('gut_gestao_ordem');
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
			$sql->adTabela('gut_gestao');
			$sql->adAtualizar('gut_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('gut_gestao_id = '.(int)$gut_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('gut_gestao');
					$sql->adAtualizar('gut_gestao_ordem', $idx);
					$sql->adOnde('gut_gestao_id = '.(int)$acao['gut_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('gut_gestao');
					$sql->adAtualizar('gut_gestao_ordem', $idx + 1);
					$sql->adOnde('gut_gestao_id = '.(int)$acao['gut_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($gut_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$gut_id=0, 
	$uuid='',  
	
	$gutjeto=null,
	$gut_tarefa=null,
	$gut_perspectiva=null,
	$gut_tema=null,
	$gut_objetivo=null,
	$gut_fator=null,
	$gut_estrategia=null,
	$gut_meta=null,
	$gut_pratica=null,
	$gut_acao=null,
	$gut_canvas=null,
	$gut_risco=null,
	$gut_risco_resposta=null,
	$gut_indicador=null,
	$gut_calendario=null,
	$gut_monitoramento=null,
	$gut_ata=null,
	$gut_mswot=null,
	$gut_swot=null,
	$gut_operativo=null,
	$gut_instrumento=null,
	$gut_recurso=null,
	$gutblema=null,
	$gut_demanda=null,
	$gutgrama=null,
	$gut_licao=null,
	$gut_evento=null,
	$gut_link=null,
	$gut_avaliacao=null,
	$gut_tgn=null,
	$gut_brainstorm=null,
	$gut_gut=null,
	$gut_causa_efeito=null,
	$gut_arquivo=null,
	$gut_forum=null,
	$gut_checklist=null,
	$gut_agenda=null,
	$gut_agrupamento=null,
	$gut_patrocinador=null,
	$gut_template=null,
	$gut_painel=null,
	$gut_painel_odometro=null,
	$gut_painel_composicao=null,
	$gut_tr=null,
	$gut_me=null,
	$gut_acao_item=null,
	$gut_beneficio=null,
	$gut_painel_slideshow=null,
	$gutjeto_viabilidade=null,
	$gutjeto_abertura=null,
	$gut_plano_gestao=null,
	$gut_ssti=null,
	$gut_laudo=null,
	$gut_trelo=null,
	$gut_trelo_cartao=null,
	$gut_pdcl=null,
	$gut_pdcl_item=null
	)
	{
	if (
		$gutjeto || 
		$gut_tarefa || 
		$gut_perspectiva || 
		$gut_tema || 
		$gut_objetivo || 
		$gut_fator || 
		$gut_estrategia || 
		$gut_meta || 
		$gut_pratica || 
		$gut_acao || 
		$gut_canvas || 
		$gut_risco || 
		$gut_risco_resposta || 
		$gut_indicador || 
		$gut_calendario || 
		$gut_monitoramento || 
		$gut_ata || 
		$gut_mswot || 
		$gut_swot || 
		$gut_operativo || 
		$gut_instrumento || 
		$gut_recurso || 
		$gutblema || 
		$gut_demanda || 
		$gutgrama || 
		$gut_licao || 
		$gut_evento || 
		$gut_link || 
		$gut_avaliacao || 
		$gut_tgn || 
		$gut_brainstorm || 
		$gut_gut || 
		$gut_causa_efeito || 
		$gut_arquivo || 
		$gut_forum || 
		$gut_checklist || 
		$gut_agenda || 
		$gut_agrupamento || 
		$gut_patrocinador || 
		$gut_template || 
		$gut_painel || 
		$gut_painel_odometro || 
		$gut_painel_composicao || 
		$gut_tr || 
		$gut_me || 
		$gut_acao_item || 
		$gut_beneficio || 
		$gut_painel_slideshow || 
		$gutjeto_viabilidade || 
		$gutjeto_abertura || 
		$gut_plano_gestao || 
		$gut_ssti || 
		$gut_laudo || 
		$gut_trelo || 
		$gut_trelo_cartao || 
		$gut_pdcl || 
		$gut_pdcl_item
		){
		global $Aplic;
			
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('gut_gestao');
			if ($uuid) $sql->adOnde('gut_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('gut_gestao_gut ='.(int)$gut_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('gut_gestao');
		$sql->adCampo('count(gut_gestao_id)');
		if ($uuid) $sql->adOnde('gut_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('gut_gestao_gut ='.(int)$gut_id);	
		if ($gut_tarefa) $sql->adOnde('gut_gestao_tarefa='.(int)$gut_tarefa);
		elseif ($gutjeto) $sql->adOnde('gut_gestao_projeto='.(int)$gutjeto);
		elseif ($gut_perspectiva) $sql->adOnde('gut_gestao_perspectiva='.(int)$gut_perspectiva);
		elseif ($gut_tema) $sql->adOnde('gut_gestao_tema='.(int)$gut_tema);
		elseif ($gut_objetivo) $sql->adOnde('gut_gestao_objetivo='.(int)$gut_objetivo);
		elseif ($gut_fator) $sql->adOnde('gut_gestao_fator='.(int)$gut_fator);
		elseif ($gut_estrategia) $sql->adOnde('gut_gestao_estrategia='.(int)$gut_estrategia);
		elseif ($gut_acao) $sql->adOnde('gut_gestao_acao='.(int)$gut_acao);
		elseif ($gut_pratica) $sql->adOnde('gut_gestao_pratica='.(int)$gut_pratica);
		elseif ($gut_meta) $sql->adOnde('gut_gestao_meta='.(int)$gut_meta);
		elseif ($gut_canvas) $sql->adOnde('gut_gestao_canvas='.(int)$gut_canvas);
		elseif ($gut_risco) $sql->adOnde('gut_gestao_risco='.(int)$gut_risco);
		elseif ($gut_risco_resposta) $sql->adOnde('gut_gestao_risco_resposta='.(int)$gut_risco_resposta);
		elseif ($gut_indicador) $sql->adOnde('gut_gestao_indicador='.(int)$gut_indicador);
		elseif ($gut_calendario) $sql->adOnde('gut_gestao_calendario='.(int)$gut_calendario);
		elseif ($gut_monitoramento) $sql->adOnde('gut_gestao_monitoramento='.(int)$gut_monitoramento);
		elseif ($gut_ata) $sql->adOnde('gut_gestao_ata='.(int)$gut_ata);
		elseif ($gut_mswot) $sql->adOnde('gut_gestao_mswot='.(int)$gut_mswot);
		elseif ($gut_swot) $sql->adOnde('gut_gestao_swot='.(int)$gut_swot);
		elseif ($gut_operativo) $sql->adOnde('gut_gestao_operativo='.(int)$gut_operativo);
		elseif ($gut_instrumento) $sql->adOnde('gut_gestao_instrumento='.(int)$gut_instrumento);
		elseif ($gut_recurso) $sql->adOnde('gut_gestao_recurso='.(int)$gut_recurso);
		elseif ($gutblema) $sql->adOnde('gut_gestao_problema='.(int)$gutblema);
		elseif ($gut_demanda) $sql->adOnde('gut_gestao_demanda='.(int)$gut_demanda);
		elseif ($gutgrama) $sql->adOnde('gut_gestao_programa='.(int)$gutgrama);
		elseif ($gut_licao) $sql->adOnde('gut_gestao_licao='.(int)$gut_licao);
		elseif ($gut_evento) $sql->adOnde('gut_gestao_evento='.(int)$gut_evento);
		elseif ($gut_link) $sql->adOnde('gut_gestao_link='.(int)$gut_link);
		elseif ($gut_avaliacao) $sql->adOnde('gut_gestao_avaliacao='.(int)$gut_avaliacao);
		elseif ($gut_tgn) $sql->adOnde('gut_gestao_tgn='.(int)$gut_tgn);
		elseif ($gut_brainstorm) $sql->adOnde('gut_gestao_brainstorm='.(int)$gut_brainstorm);
		
		elseif ($gut_gut) $sql->adOnde('gut_gestao_semelhante='.(int)$gut_gut);
		
		elseif ($gut_causa_efeito) $sql->adOnde('gut_gestao_causa_efeito='.(int)$gut_causa_efeito);
		elseif ($gut_arquivo) $sql->adOnde('gut_gestao_arquivo='.(int)$gut_arquivo);
		elseif ($gut_forum) $sql->adOnde('gut_gestao_forum='.(int)$gut_forum);
		elseif ($gut_checklist) $sql->adOnde('gut_gestao_checklist='.(int)$gut_checklist);
		elseif ($gut_agenda) $sql->adOnde('gut_gestao_agenda='.(int)$gut_agenda);
		elseif ($gut_agrupamento) $sql->adOnde('gut_gestao_agrupamento='.(int)$gut_agrupamento);
		elseif ($gut_patrocinador) $sql->adOnde('gut_gestao_patrocinador='.(int)$gut_patrocinador);
		elseif ($gut_template) $sql->adOnde('gut_gestao_template='.(int)$gut_template);
		elseif ($gut_painel) $sql->adOnde('gut_gestao_painel='.(int)$gut_painel);
		elseif ($gut_painel_odometro) $sql->adOnde('gut_gestao_painel_odometro='.(int)$gut_painel_odometro);
		elseif ($gut_painel_composicao) $sql->adOnde('gut_gestao_painel_composicao='.(int)$gut_painel_composicao);
		elseif ($gut_tr) $sql->adOnde('gut_gestao_tr='.(int)$gut_tr);
		elseif ($gut_me) $sql->adOnde('gut_gestao_me='.(int)$gut_me);
		elseif ($gut_acao_item) $sql->adOnde('gut_gestao_acao_item='.(int)$gut_acao_item);
		elseif ($gut_beneficio) $sql->adOnde('gut_gestao_beneficio='.(int)$gut_beneficio);
		elseif ($gut_painel_slideshow) $sql->adOnde('gut_gestao_painel_slideshow='.(int)$gut_painel_slideshow);
		elseif ($gutjeto_viabilidade) $sql->adOnde('gut_gestao_projeto_viabilidade='.(int)$gutjeto_viabilidade);
		elseif ($gutjeto_abertura) $sql->adOnde('gut_gestao_projeto_abertura='.(int)$gutjeto_abertura);
		elseif ($gut_plano_gestao) $sql->adOnde('gut_gestao_plano_gestao='.(int)$gut_plano_gestao);
		elseif ($gut_ssti) $sql->adOnde('gut_gestao_ssti='.(int)$gut_ssti);
		elseif ($gut_laudo) $sql->adOnde('gut_gestao_laudo='.(int)$gut_laudo);
		elseif ($gut_trelo) $sql->adOnde('gut_gestao_trelo='.(int)$gut_trelo);
		elseif ($gut_trelo_cartao) $sql->adOnde('gut_gestao_trelo_cartao='.(int)$gut_trelo_cartao);
		elseif ($gut_pdcl) $sql->adOnde('gut_gestao_pdcl='.(int)$gut_pdcl);
		elseif ($gut_pdcl_item) $sql->adOnde('gut_gestao_pdcl_item='.(int)$gut_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('gut_gestao');
			$sql->adCampo('MAX(gut_gestao_ordem)');
			if ($uuid) $sql->adOnde('gut_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('gut_gestao_gut ='.(int)$gut_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('gut_gestao');
			if ($uuid) $sql->adInserir('gut_gestao_uuid', $uuid);
			else $sql->adInserir('gut_gestao_gut', (int)$gut_id);
			
			if ($gut_tarefa) $sql->adInserir('gut_gestao_tarefa', (int)$gut_tarefa);
			if ($gutjeto) $sql->adInserir('gut_gestao_projeto', (int)$gutjeto);
			elseif ($gut_perspectiva) $sql->adInserir('gut_gestao_perspectiva', (int)$gut_perspectiva);
			elseif ($gut_tema) $sql->adInserir('gut_gestao_tema', (int)$gut_tema);
			elseif ($gut_objetivo) $sql->adInserir('gut_gestao_objetivo', (int)$gut_objetivo);
			elseif ($gut_fator) $sql->adInserir('gut_gestao_fator', (int)$gut_fator);
			elseif ($gut_estrategia) $sql->adInserir('gut_gestao_estrategia', (int)$gut_estrategia);
			elseif ($gut_acao) $sql->adInserir('gut_gestao_acao', (int)$gut_acao);
			elseif ($gut_pratica) $sql->adInserir('gut_gestao_pratica', (int)$gut_pratica);
			elseif ($gut_meta) $sql->adInserir('gut_gestao_meta', (int)$gut_meta);
			elseif ($gut_canvas) $sql->adInserir('gut_gestao_canvas', (int)$gut_canvas);
			elseif ($gut_risco) $sql->adInserir('gut_gestao_risco', (int)$gut_risco);
			elseif ($gut_risco_resposta) $sql->adInserir('gut_gestao_risco_resposta', (int)$gut_risco_resposta);
			elseif ($gut_indicador) $sql->adInserir('gut_gestao_indicador', (int)$gut_indicador);
			elseif ($gut_calendario) $sql->adInserir('gut_gestao_calendario', (int)$gut_calendario);
			elseif ($gut_monitoramento) $sql->adInserir('gut_gestao_monitoramento', (int)$gut_monitoramento);
			elseif ($gut_ata) $sql->adInserir('gut_gestao_ata', (int)$gut_ata);
			elseif ($gut_mswot) $sql->adInserir('gut_gestao_mswot', (int)$gut_mswot);
			elseif ($gut_swot) $sql->adInserir('gut_gestao_swot', (int)$gut_swot);
			elseif ($gut_operativo) $sql->adInserir('gut_gestao_operativo', (int)$gut_operativo);
			elseif ($gut_instrumento) $sql->adInserir('gut_gestao_instrumento', (int)$gut_instrumento);
			elseif ($gut_recurso) $sql->adInserir('gut_gestao_recurso', (int)$gut_recurso);
			elseif ($gutblema) $sql->adInserir('gut_gestao_problema', (int)$gutblema);
			elseif ($gut_demanda) $sql->adInserir('gut_gestao_demanda', (int)$gut_demanda);
			elseif ($gutgrama) $sql->adInserir('gut_gestao_programa', (int)$gutgrama);
			elseif ($gut_licao) $sql->adInserir('gut_gestao_licao', (int)$gut_licao);
			elseif ($gut_evento) $sql->adInserir('gut_gestao_evento', (int)$gut_evento);
			elseif ($gut_link) $sql->adInserir('gut_gestao_link', (int)$gut_link);
			elseif ($gut_avaliacao) $sql->adInserir('gut_gestao_avaliacao', (int)$gut_avaliacao);
			elseif ($gut_tgn) $sql->adInserir('gut_gestao_tgn', (int)$gut_tgn);
			elseif ($gut_brainstorm) $sql->adInserir('gut_gestao_brainstorm', (int)$gut_brainstorm);
			
			elseif ($gut_gut) $sql->adInserir('gut_gestao_semelhante', (int)$gut_gut);
			
			elseif ($gut_causa_efeito) $sql->adInserir('gut_gestao_causa_efeito', (int)$gut_causa_efeito);
			elseif ($gut_arquivo) $sql->adInserir('gut_gestao_arquivo', (int)$gut_arquivo);
			elseif ($gut_forum) $sql->adInserir('gut_gestao_forum', (int)$gut_forum);
			elseif ($gut_checklist) $sql->adInserir('gut_gestao_checklist', (int)$gut_checklist);
			elseif ($gut_agenda) $sql->adInserir('gut_gestao_agenda', (int)$gut_agenda);
			elseif ($gut_agrupamento) $sql->adInserir('gut_gestao_agrupamento', (int)$gut_agrupamento);
			elseif ($gut_patrocinador) $sql->adInserir('gut_gestao_patrocinador', (int)$gut_patrocinador);
			elseif ($gut_template) $sql->adInserir('gut_gestao_template', (int)$gut_template);
			elseif ($gut_painel) $sql->adInserir('gut_gestao_painel', (int)$gut_painel);
			elseif ($gut_painel_odometro) $sql->adInserir('gut_gestao_painel_odometro', (int)$gut_painel_odometro);
			elseif ($gut_painel_composicao) $sql->adInserir('gut_gestao_painel_composicao', (int)$gut_painel_composicao);
			elseif ($gut_tr) $sql->adInserir('gut_gestao_tr', (int)$gut_tr);
			elseif ($gut_me) $sql->adInserir('gut_gestao_me', (int)$gut_me);
			elseif ($gut_acao_item) $sql->adInserir('gut_gestao_acao_item', (int)$gut_acao_item);
			elseif ($gut_beneficio) $sql->adInserir('gut_gestao_beneficio', (int)$gut_beneficio);
			elseif ($gut_painel_slideshow) $sql->adInserir('gut_gestao_painel_slideshow', (int)$gut_painel_slideshow);
			elseif ($gutjeto_viabilidade) $sql->adInserir('gut_gestao_projeto_viabilidade', (int)$gutjeto_viabilidade);
			elseif ($gutjeto_abertura) $sql->adInserir('gut_gestao_projeto_abertura', (int)$gutjeto_abertura);
			elseif ($gut_plano_gestao) $sql->adInserir('gut_gestao_plano_gestao', (int)$gut_plano_gestao);
			elseif ($gut_ssti) $sql->adInserir('gut_gestao_ssti', (int)$gut_ssti);
			elseif ($gut_laudo) $sql->adInserir('gut_gestao_laudo', (int)$gut_laudo);
			elseif ($gut_trelo) $sql->adInserir('gut_gestao_trelo', (int)$gut_trelo);
			elseif ($gut_trelo_cartao) $sql->adInserir('gut_gestao_trelo_cartao', (int)$gut_trelo_cartao);
			elseif ($gut_pdcl) $sql->adInserir('gut_gestao_pdcl', (int)$gut_pdcl);
			elseif ($gut_pdcl_item) $sql->adInserir('gut_gestao_pdcl_item', (int)$gut_pdcl_item);
			$sql->adInserir('gut_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($gut_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($gut_id=0, $uuid='', $gut_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('gut_gestao');
	$sql->adOnde('gut_gestao_id='.(int)$gut_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($gut_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($gut_id=0, $uuid=''){	
	$saida=atualizar_gestao($gut_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($gut_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('gut_gestao');
	$sql->adCampo('gut_gestao.*');
	if ($uuid) $sql->adOnde('gut_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('gut_gestao_gut ='.(int)$gut_id);	
	$sql->adOrdem('gut_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['gut_gestao_ordem'].', '.$gestao_data['gut_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['gut_gestao_ordem'].', '.$gestao_data['gut_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['gut_gestao_ordem'].', '.$gestao_data['gut_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['gut_gestao_ordem'].', '.$gestao_data['gut_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['gut_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['gut_gestao_tarefa']).'</td>';
		elseif ($gestao_data['gut_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['gut_gestao_projeto']).'</td>';
		elseif ($gestao_data['gut_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['gut_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['gut_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['gut_gestao_tema']).'</td>';
		elseif ($gestao_data['gut_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['gut_gestao_objetivo']).'</td>';
		elseif ($gestao_data['gut_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['gut_gestao_fator']).'</td>';
		elseif ($gestao_data['gut_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['gut_gestao_estrategia']).'</td>';
		elseif ($gestao_data['gut_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['gut_gestao_meta']).'</td>';
		elseif ($gestao_data['gut_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['gut_gestao_pratica']).'</td>';
		elseif ($gestao_data['gut_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['gut_gestao_acao']).'</td>';
		elseif ($gestao_data['gut_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['gut_gestao_canvas']).'</td>';
		elseif ($gestao_data['gut_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['gut_gestao_risco']).'</td>';
		elseif ($gestao_data['gut_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['gut_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['gut_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['gut_gestao_indicador']).'</td>';
		elseif ($gestao_data['gut_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['gut_gestao_calendario']).'</td>';
		elseif ($gestao_data['gut_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['gut_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['gut_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['gut_gestao_ata']).'</td>';
		elseif ($gestao_data['gut_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['gut_gestao_mswot']).'</td>';
		elseif ($gestao_data['gut_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['gut_gestao_swot']).'</td>';
		elseif ($gestao_data['gut_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['gut_gestao_operativo']).'</td>';
		elseif ($gestao_data['gut_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['gut_gestao_instrumento']).'</td>';
		elseif ($gestao_data['gut_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['gut_gestao_recurso']).'</td>';
		elseif ($gestao_data['gut_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['gut_gestao_problema']).'</td>';
		elseif ($gestao_data['gut_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['gut_gestao_demanda']).'</td>';
		elseif ($gestao_data['gut_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['gut_gestao_programa']).'</td>';
		elseif ($gestao_data['gut_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['gut_gestao_licao']).'</td>';
		elseif ($gestao_data['gut_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['gut_gestao_evento']).'</td>';
		elseif ($gestao_data['gut_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['gut_gestao_link']).'</td>';
		elseif ($gestao_data['gut_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['gut_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['gut_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['gut_gestao_tgn']).'</td>';
		elseif ($gestao_data['gut_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['gut_gestao_brainstorm']).'</td>';
		
		elseif ($gestao_data['gut_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['gut_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['gut_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['gut_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['gut_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['gut_gestao_arquivo']).'</td>';
		elseif ($gestao_data['gut_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['gut_gestao_forum']).'</td>';
		elseif ($gestao_data['gut_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['gut_gestao_checklist']).'</td>';
		elseif ($gestao_data['gut_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['gut_gestao_agenda']).'</td>';
		elseif ($gestao_data['gut_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['gut_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['gut_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['gut_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['gut_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['gut_gestao_template']).'</td>';
		elseif ($gestao_data['gut_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['gut_gestao_painel']).'</td>';
		elseif ($gestao_data['gut_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['gut_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['gut_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['gut_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['gut_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['gut_gestao_tr']).'</td>';	
		elseif ($gestao_data['gut_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['gut_gestao_me']).'</td>';	
		elseif ($gestao_data['gut_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['gut_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['gut_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['gut_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['gut_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['gut_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['gut_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['gut_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['gut_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['gut_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['gut_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['gut_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['gut_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['gut_gestao_ssti']).'</td>';
		elseif ($gestao_data['gut_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['gut_gestao_laudo']).'</td>';
		elseif ($gestao_data['gut_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['gut_gestao_trelo']).'</td>';
		elseif ($gestao_data['gut_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['gut_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['gut_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['gut_gestao_pdcl']).'</td>';
		elseif ($gestao_data['gut_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['gut_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['gut_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		





$xajax->processRequest();
?>	