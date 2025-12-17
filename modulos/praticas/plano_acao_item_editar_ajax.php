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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/plano_acao_item_editar_ajax_pro.php';
require_once BASE_DIR.'/modulos/tarefas/funcoes.php';
require_once BASE_DIR.'/modulos/praticas/plano_acao_item.class.php';

function calcular_duracao($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$resultado=str_replace('.', ',',$resultado);
	$objResposta->assign("plano_acao_item_duracao","value", $resultado);
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


function exibir_contatos($contatos, $posicao='combo_contatos'){
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

function acao_existe($nome='', $plano_acao_item_id=0){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adCampo('count(plano_acao_item_id)');
	$sql->adOnde('plano_acao_item_nome = "'.$nome.'"');
	if ($plano_acao_item_id) $sql->adOnde('plano_acao_item_id != '.(int)$plano_acao_item_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_acao","value", (int)$existe);
	return $objResposta;
	}
	
$xajax->registerFunction("acao_existe");

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');
	
	if(get_magic_quotes_gpc()) $script = stripslashes($script);

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



function exibir_usuarios2($usuarios){
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
	$objResposta->assign('combo_usuarios2',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios2");

function exibir_depts2($depts){
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
	$objResposta->assign('combo_depts2',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts2");

	
function calcular_duracao2($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$resultado=str_replace('.', ',',$resultado);
	$objResposta->assign("plano_acao_item_duracao","value", $resultado);
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao2");		


function data_final_periodo2($inicio, $dias, $cia_id){
	$dias=float_americano($dias);
	$horas=abs($dias*config('horas_trab_diario'));
	$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id);
	$data=new CData($data_final);
	$objResposta = new xajaxResponse();
	$objResposta->assign("oculto_data_fim2","value", $data->format("%Y-%m-%d"));
	$objResposta->assign("data_fim2","value", $data->format("%d/%m/%Y"));
	$objResposta->assign("fim_hora2","value", $data->format("%H"));
	$objResposta->assign("fim_minuto2","value", $data->format("%M"));
	return $objResposta;
	}	
$xajax->registerFunction("data_final_periodo2");	


function exibir($plano_acao_item_acao=null, $uuid=null){
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir");	

function mudar_posicao_acao_ajax($plano_acao_item_ordem, $plano_acao_item_id, $direcao, $plano_acao_item_acao=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$plano_acao_item_id) {
		$novo_ui_plano_acao_item_ordem = $plano_acao_item_ordem;
		$sql->adTabela('plano_acao_item');
		$sql->adOnde('plano_acao_item_id != '.(int)$plano_acao_item_id);
		
		if ($uuid) $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
		
		$sql->adOrdem('plano_acao_item_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem = count($membros) + 1;
			}
		if ($novo_ui_plano_acao_item_ordem && ($novo_ui_plano_acao_item_ordem <= count($membros) + 1)) {
			$sql->adTabela('plano_acao_item');
			$sql->adAtualizar('plano_acao_item_ordem', $novo_ui_plano_acao_item_ordem);
			$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_plano_acao_item_ordem) {
					$sql->adTabela('plano_acao_item');
					$sql->adAtualizar('plano_acao_item_ordem', $idx);
					$sql->adOnde('plano_acao_item_id = '.(int)$acao['plano_acao_item_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_acao_item');
					$sql->adAtualizar('plano_acao_item_ordem', $idx + 1);
					$sql->adOnde('plano_acao_item_id = '.(int)$acao['plano_acao_item_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_acao_ajax");		
	

function incluir_acao_ajax(
	$plano_acao_item_acao=null, 
	$uuid=null,
	$plano_acao_item_id=null, 
	$uuid2=null,
	$plano_acao_item_responsavel=null,
	$plano_acao_item_cia=null, 
	$plano_acao_item_nome=null, 
	$plano_acao_item_quando=null, 
	$plano_acao_item_oque=null, 
	$plano_acao_item_como=null, 
	$plano_acao_item_onde=null, 
	$plano_acao_item_quanto=null, 
	$plano_acao_item_porque=null, 
	$plano_acao_item_quem=null, 
	$plano_acao_item_inicio=null, 
	$plano_acao_item_fim=null, 
	$plano_acao_item_duracao=null,
	$tem_inicio=null,
	$tem_fim=null,
	$plano_acao_item_usuarios=null,
	$plano_acao_item_depts=null,
	$plano_acao_item_percentagem=null,
	$plano_acao_item_peso=null,
	$plano_acao_item_calculo_porcentagem=null,
	$exibir_porcentagem_item=null,
	
	$plano_acao_item_responsavel_exibe=null,
	$plano_acao_item_usuarios_exibe=null
){
	global $bd, $Aplic;
	$sql = new BDConsulta;
	$plano_acao_item_nome=previnirXSS(utf8_decode($plano_acao_item_nome));
	$plano_acao_item_quando=previnirXSS(utf8_decode($plano_acao_item_quando));
	$plano_acao_item_oque=previnirXSS(utf8_decode($plano_acao_item_oque));
	$plano_acao_item_como=previnirXSS(utf8_decode($plano_acao_item_como));
	$plano_acao_item_onde=previnirXSS(utf8_decode($plano_acao_item_onde));
	$plano_acao_item_quanto=previnirXSS(utf8_decode($plano_acao_item_quanto));
	$plano_acao_item_porque=previnirXSS(utf8_decode($plano_acao_item_porque));
	$plano_acao_item_quem=previnirXSS(utf8_decode($plano_acao_item_quem));
	$plano_acao_item_duracao = float_americano($plano_acao_item_duracao);
	if ($plano_acao_item_id){
		$sql->adTabela('plano_acao_item');
		$sql->adAtualizar('plano_acao_item_responsavel', ($plano_acao_item_responsavel ? $plano_acao_item_responsavel : null));
		$sql->adAtualizar('plano_acao_item_cia', ($plano_acao_item_cia ? $plano_acao_item_cia : null));	
		$sql->adAtualizar('plano_acao_item_nome', $plano_acao_item_nome);	
		$sql->adAtualizar('plano_acao_item_quando', $plano_acao_item_quando);	
		$sql->adAtualizar('plano_acao_item_oque', $plano_acao_item_oque);	
		$sql->adAtualizar('plano_acao_item_como', $plano_acao_item_como);	
		$sql->adAtualizar('plano_acao_item_onde', $plano_acao_item_onde);	
		$sql->adAtualizar('plano_acao_item_quanto', $plano_acao_item_quanto);	
		$sql->adAtualizar('plano_acao_item_porque', $plano_acao_item_porque);	
		$sql->adAtualizar('plano_acao_item_quem', $plano_acao_item_quem);	
		$sql->adAtualizar('plano_acao_item_inicio', ($tem_inicio ? $plano_acao_item_inicio : null));	
		$sql->adAtualizar('plano_acao_item_fim', ($tem_fim ? $plano_acao_item_fim : null));	
		$sql->adAtualizar('plano_acao_item_duracao', $plano_acao_item_duracao);	
		$sql->adAtualizar('plano_acao_item_percentagem', $plano_acao_item_percentagem);	
		$sql->adAtualizar('plano_acao_item_peso', float_americano($plano_acao_item_peso));	 
		$sql->adAtualizar('plano_acao_item_responsavel_exibe', $plano_acao_item_responsavel_exibe);	
		$sql->adAtualizar('plano_acao_item_usuarios_exibe', $plano_acao_item_usuarios_exibe);	
		$sql->adOnde('plano_acao_item_id ='.(int)$plano_acao_item_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('plano_acao_item');
		$sql->adCampo('count(plano_acao_item_id) AS soma');
		
	  if ($uuid) $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
	  
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
		
		$sql->adTabela('plano_acao_item');
		
		if ($uuid) $sql->adInserir('plano_acao_item_uuid', $uuid);
		else $sql->adInserir('plano_acao_item_acao', $plano_acao_item_acao);
		
		$sql->adInserir('plano_acao_item_ordem', $soma_total);
		$sql->adInserir('plano_acao_item_responsavel', ($plano_acao_item_responsavel ? $plano_acao_item_responsavel : null));
		$sql->adInserir('plano_acao_item_cia', ($plano_acao_item_cia ? $plano_acao_item_cia : null));	
		$sql->adInserir('plano_acao_item_nome', $plano_acao_item_nome);	
		$sql->adInserir('plano_acao_item_quando', $plano_acao_item_quando);	
		$sql->adInserir('plano_acao_item_oque', $plano_acao_item_oque);	
		$sql->adInserir('plano_acao_item_como', $plano_acao_item_como);	
		$sql->adInserir('plano_acao_item_onde', $plano_acao_item_onde);	
		$sql->adInserir('plano_acao_item_quanto', $plano_acao_item_quanto);	
		$sql->adInserir('plano_acao_item_porque', $plano_acao_item_porque);	
		$sql->adInserir('plano_acao_item_quem', $plano_acao_item_quem);	
		$sql->adInserir('plano_acao_item_inicio', ($tem_inicio ? $plano_acao_item_inicio : null));	
		$sql->adInserir('plano_acao_item_fim', ($tem_fim ? $plano_acao_item_fim : null));	
		$sql->adInserir('plano_acao_item_duracao', $plano_acao_item_duracao);	
		$sql->adInserir('plano_acao_item_percentagem', $plano_acao_item_percentagem);	
		$sql->adInserir('plano_acao_item_peso', float_americano($plano_acao_item_peso));	
		$sql->adInserir('plano_acao_item_responsavel_exibe', $plano_acao_item_responsavel_exibe);	
		$sql->adInserir('plano_acao_item_usuarios_exibe', $plano_acao_item_usuarios_exibe);	
		$sql->exec();
		$sql->limpar();
		$plano_acao_item_id=$bd->Insert_ID('plano_acao_item','plano_acao_item_id');
		
		if ($Aplic->profissional){
			$sql->adTabela('plano_acao_item_custos');
			$sql->adAtualizar('plano_acao_item_custos_plano_acao_item', $plano_acao_item_id);
			$sql->adOnde('plano_acao_item_custos_uuid =\''.$uuid2.'\'');
			$sql->exec();
		  $sql->limpar();
			}	
			
		}
	$usuarios=explode(',', $plano_acao_item_usuarios);
	$sql->setExcluir('plano_acao_item_usuario');
	$sql->adOnde('plano_acao_item_usuario_item = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	if (count($usuarios)){
		foreach($usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_acao_item_usuario');
				$sql->adInserir('plano_acao_item_usuario_item', (int)$plano_acao_item_id);
				$sql->adInserir('plano_acao_item_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
		}
	$depts=explode(',', $plano_acao_item_depts);
	$sql->setExcluir('plano_acao_item_dept');
	$sql->adOnde('plano_acao_item_dept_plano_acao_item = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	if (count($depts)){
		foreach($depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_acao_item_dept');
				$sql->adInserir('plano_acao_item_dept_plano_acao_item', (int)$plano_acao_item_id);
				$sql->adInserir('plano_acao_item_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
		}	
	if ($uuid2){
		$sql->adTabela('plano_acao_item_custos');
		$sql->adAtualizar('plano_acao_item_custos_plano_acao_item', (int)$plano_acao_item_id);
		$sql->adAtualizar('plano_acao_item_custos_uuid', null);
		$sql->adOnde('plano_acao_item_custos_uuid=\''.$uuid2.'\'');
		$sql->exec();
		$sql->limpar();
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_plano_acao_item', (int)$plano_acao_item_id);
		$sql->adAtualizar('plano_acao_item_gastos_uuid', null);
		$sql->adOnde('plano_acao_item_gastos_uuid=\''.$uuid2.'\'');
		$sql->exec();
		$sql->limpar();	
		}
	//calculo de porcentagem
	if ($Aplic->profissional && $plano_acao_item_calculo_porcentagem){
		$sql->adTabela('plano_acao_item');
		$sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
		$sql->adCampo('plano_acao_item_percentagem, plano_acao_item_peso');
		$lista=$sql->Lista();
		$sql->limpar();
		$numerador=0;
		$denominador=0;
		foreach($lista as $linha) {
			$numerador+=($linha['plano_acao_item_percentagem']*$linha['plano_acao_item_peso']);
			$denominador+=$linha['plano_acao_item_peso'];
			}
		$percentagem_calculada=($denominador ? $numerador/$denominador : 0);
		$obj = new CPlanoAcao();
		$obj->load($plano_acao_item_acao);		
		if ($obj->plano_acao_item_percentagem!=$percentagem_calculada && $Aplic->profissional)	{
			$sql->adTabela('plano_acao_item');
			$sql->adAtualizar('plano_acao_item_percentagem', $percentagem_calculada);
			$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_acao);
			$sql->exec();
			$sql->limpar();			
			$obj->disparo_observador('fisico');
			}
		}	
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_acao_ajax");	


function excluir_acao_ajax($plano_acao_item_id, $plano_acao_item_acao=null, $uuid=null){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_acao_item');
	$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
	$sql->exec();
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_acao_ajax");	


function atualizar_acoes($plano_acao_item_acao=null, $uuid=null){
	global $config, $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	if ($uuid) $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
	else $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
	$sql->adCampo('plano_acao_item.*');
	$sql->adOrdem('plano_acao_item_ordem');
	$acoes=$sql->ListaChave('plano_acao_item_id');
	$sql->limpar();
	$saida='';
	if ($Aplic->profissional){
		$sql->adTabela('campo_formulario');
		$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
		$sql->adOnde('campo_formulario_tipo = \'acao\'');
		$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
		$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
		$sql->limpar();
		}
	if (count($acoes)) {
		$saida.= '<table cellspacing=0 cellpadding=0 width=100%><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left width=100%><tr><th>&nbsp;</th><th>O Que</th><th>Por que</th><th>Onde</th><th>Quando</th><th>Quem</th><th>Como</th><th>Quanto</th>'.($Aplic->profissional && $exibir['porcentagem_item'] ? '<th>Peso</th><th>%</th>' : '').'<th>&nbsp;</th></tr>';
		foreach ($acoes as $plano_acao_item_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverPrimeiro\');">'.imagem('icones/2setacima.gif', 'Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição.').'</a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverParaCima\');">'.imagem('icones/1setacima.gif', 'Posição Acima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover uma posição acima.').'</a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverParaBaixo\');">'.imagem('icones/1setabaixo.gif', 'Posição Abaixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover uma posição abaixo.').'</a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverUltimo\');">'.imagem('icones/2setabaixo.gif', 'Última Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição.').'</a>';
			$saida.= '</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_oque'] ? $linha['plano_acao_item_oque'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_porque'] ? $linha['plano_acao_item_porque'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_onde'] ? $linha['plano_acao_item_onde'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quando'];
				if ($linha['plano_acao_item_quando'] && ($linha['plano_acao_item_inicio'] || $linha['plano_acao_item_fim'])) $saida.= '<br>';
				if ($linha['plano_acao_item_inicio']) $saida.= retorna_data($linha['plano_acao_item_inicio']);
				if ($linha['plano_acao_item_inicio'] && $linha['plano_acao_item_fim']) $saida.= '<br>';
				if ($linha['plano_acao_item_fim']) $saida.= retorna_data($linha['plano_acao_item_fim']);
				if (!$linha['plano_acao_item_quando'] && !$linha['plano_acao_item_inicio'] && !$linha['plano_acao_item_fim']) $saida.= '&nbsp;';	
			$saida.= '</td>';
			
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quem'];
			
			$sql->adTabela('plano_acao_item_usuario');
			$sql->adCampo('plano_acao_item_usuario_usuario');
			$sql->adOnde('plano_acao_item_usuario_item = '.$linha['plano_acao_item_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
		
			$saida_quem='';
			if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
					}
				} 	
			$sql->adTabela('plano_acao_item_dept');
			$sql->adCampo('plano_acao_item_dept_dept');
			$sql->adOnde('plano_acao_item_dept_plano_acao_item = '.$linha['plano_acao_item_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
		
			$saida_dept='';
			if ($depts && count($depts)) {
				$saida_dept.= link_secao($depts[0]);
				$qnt_depts=count($depts);
				if ($qnt_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';		
					$saida_dept.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'depts_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_depts - 1).')</a><span style="display: none" id="depts_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
					}
				} 		
			if ($saida_quem) $saida.= ($linha['plano_acao_item_quem'] ? '<br>' : '').$saida_quem;
			if ($saida_dept) $saida.= ($linha['plano_acao_item_quem'] || $saida_quem ? '<br>' : '').$saida_dept;
			if (!$saida_quem && !$linha['plano_acao_item_quem'] && !$saida_dept) $saida.= '&nbsp;';
			$saida.= '</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_como'] ? $linha['plano_acao_item_como'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quanto'];
			$sql->adTabela('plano_acao_item_custos');
			$sql->adCampo('SUM(((plano_acao_item_custos_quantidade*plano_acao_item_custos_custo*plano_acao_item_custos_cotacao)*((100+plano_acao_item_custos_bdi)/100))) as total');
			$sql->adOnde('plano_acao_item_custos_plano_acao_item = '.$linha['plano_acao_item_id']);
			$custo = $sql->Resultado();
			$sql->limpar();
			if ($custo) $saida.= ($linha['plano_acao_item_quanto']? '<br>' : '').'custo: '.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.');
			$sql->adTabela('plano_acao_item_gastos');
				
			
			$sql->adCampo('SUM(((plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo)*((100+plano_acao_item_gastos_bdi)/100))) as total');
			$sql->adOnde('plano_acao_item_gastos_plano_acao_item = '.$linha['plano_acao_item_id']);
			$gasto = $sql->Resultado();
			$sql->limpar();
			if ($gasto) $saida.= ($linha['plano_acao_item_quanto'] || $custo ? '<br>' : '').'gasto: '.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.');
			if (!$linha['plano_acao_item_quanto']) $saida.= '&nbsp;';
			$saida.= '</td>';
			if ($Aplic->profissional && $exibir['porcentagem_item']){
				$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.($linha['plano_acao_item_peso'] ? number_format($linha['plano_acao_item_peso'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.(int)$linha['plano_acao_item_percentagem'].'</td>';
				}
			
			$saida.= '<td width=32><a href="javascript: void(0);" onclick="editar_acao('.$linha['plano_acao_item_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a ação.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este acao?\')) {excluir_acao('.$linha['plano_acao_item_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir a ação.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;	
	}	

$xajax->registerFunction("atualizar_acoes");		
	
function editar_acao($plano_acao_item_id){
	global $config, $Aplic;
	
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;

	$sql->adTabela('plano_acao_item');
	$sql->adCampo('plano_acao_item.*');
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->adOrdem('plano_acao_item_ordem');
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	
	$objResposta->assign("plano_acao_item_id","value", $plano_acao_item_id);
	$objResposta->assign("plano_acao_item_responsavel","value", ($linha['plano_acao_item_responsavel'] ? $linha['plano_acao_item_responsavel'] : $Aplic->usuario_id));
	$objResposta->assign("nome_responsavel","value", utf8_encode(nome_om(($linha['plano_acao_item_responsavel'] ? $linha['plano_acao_item_responsavel'] : $Aplic->usuario_id), $Aplic->getPref('om_usuario'))));
	$objResposta->assign("plano_acao_item_cia","value", $linha['plano_acao_item_cia']);	
	$objResposta->assign("plano_acao_item_duracao","value", str_replace('.', ',', $linha['plano_acao_item_duracao']));	
	
	$data = new CData($linha['plano_acao_item_inicio']);
	$objResposta->assign("oculto_data_inicio","value", $data->format('%Y-%m-%d'));
	$objResposta->assign("data_inicio","value", $data->format('%d/%m/%Y'));	
	$objResposta->assign("inicio_hora","value", $data->format('%H'));	
	$objResposta->assign("inicio_minuto","value", $data->format('%M'));
	
	$data = new CData($linha['plano_acao_item_fim']);
	$objResposta->assign("oculto_data_fim2","value", $data->format('%Y-%m-%d'));
	$objResposta->assign("data_fim2","value", $data->format('%d/%m/%Y'));	
	$objResposta->assign("fim_hora2","value", $data->format('%H'));	
	$objResposta->assign("fim_minuto2","value", $data->format('%M'));
	
	$objResposta->assign("plano_acao_item_nome","value", utf8_encode($linha['plano_acao_item_nome']));	
	
	$objResposta->assign("apoio_plano_acao_item_quando","value", utf8_encode($linha['plano_acao_item_quando']));	
	$objResposta->assign("apoio_plano_acao_item_oque","value", utf8_encode($linha['plano_acao_item_oque']));	
	$objResposta->assign("apoio_plano_acao_item_como","value", utf8_encode($linha['plano_acao_item_como']));	
	$objResposta->assign("apoio_plano_acao_item_onde","value", utf8_encode($linha['plano_acao_item_onde']));	
	$objResposta->assign("apoio_plano_acao_item_quanto","value", utf8_encode($linha['plano_acao_item_quanto']));	
	$objResposta->assign("apoio_plano_acao_item_porque","value", utf8_encode($linha['plano_acao_item_porque']));	
	$objResposta->assign("apoio_plano_acao_item_quem","value", utf8_encode($linha['plano_acao_item_quem']));	
	
	$objResposta->assign("plano_acao_item_percentagem","value", (int)$linha['plano_acao_item_percentagem']);	
	$objResposta->assign("plano_acao_item_peso","value", number_format($linha['plano_acao_item_peso'], 2, ',', '.'));	
	$objResposta->assign("tem_inicio","checked", ($linha['plano_acao_item_inicio'] ? true : false));	
	$objResposta->assign("tem_fim","checked", ($linha['plano_acao_item_fim'] ? true : false));
	
	$sql->adTabela('plano_acao_item_usuario');
	$sql->adCampo('plano_acao_item_usuario_usuario');
	$sql->adOnde('plano_acao_item_usuario_item = '.(int)$plano_acao_item_id);
	$usuarios=$sql->carregarColuna();
	$sql->limpar();	

	$objResposta->assign("plano_acao_item_usuarios","value", implode(',',$usuarios));
	
	
	$sql->adTabela('plano_acao_item_dept');
	$sql->adCampo('plano_acao_item_dept_dept');
	$sql->adOnde('plano_acao_item_dept_plano_acao_item = '.(int)$plano_acao_item_id);
	$depts=$sql->carregarColuna();
	$sql->limpar();	

	$objResposta->assign("plano_acao_item_depts","value", implode(',',$depts));

	return $objResposta;
	}	

$xajax->registerFunction("editar_acao");		



$xajax->processRequest();
?>