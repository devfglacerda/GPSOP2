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
	
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/editar_ajax_pro.php';

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

function exibir_municipios($municipios){
	global $config;
	$municipios_selecionados=explode(',', $municipios);
	$saida_municipios='';
	if (count($municipios_selecionados)) {
			$saida_municipios.= '<table cellpadding=0 cellspacing=0>';
			$saida_municipios.= '<tr><td class="texto" style="width:400px;">'.link_municipio($municipios_selecionados[0]);
			$qnt_lista_municipios=count($municipios_selecionados);
			if ($qnt_lista_municipios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=link_municipio($municipios_selecionados[$i]).'<br>';		
					$saida_municipios.= dica('Outros municípios', 'Clique para visualizar os demais municípios.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
					}
			$saida_municipios.= '</td></tr></table>';
			} 
	else $saida_municipios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_municipios',"innerHTML", utf8_encode($saida_municipios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_municipios");


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
	else 	$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");

function projeto_existe($nome='', $projeto_id=0){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adCampo('count(projeto_id)');
	$sql->adOnde('projeto_nome = "'.$nome.'"');
	if ($projeto_id) $sql->adOnde('projeto_id != '.(int)$projeto_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_projeto","value", (int)$existe);
	return $objResposta;
	}
	
$xajax->registerFunction("projeto_existe");	

function acao_ajax($social_id=0){
	$sql = new BDConsulta;	
	$lista_acoes=array('' => '');
	$sql->adTabela('social_acao');
	$sql->adCampo('social_acao_id, social_acao_nome');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOrdem('social_acao_nome');
	$lista=$sql->Lista();
	$sql->limpar();
	foreach ($lista as $linha) $lista_acoes[$linha['social_acao_id']]=utf8_encode($linha['social_acao_nome']);
	$saida=selecionaVetor($lista_acoes, 'projeto_social_acao', 'size="1" class="texto" style="width:284px;"');

	$objResposta = new xajaxResponse();
	$objResposta->assign("acao_combo","innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("acao_ajax");		

function selecionar_comunidade_ajax($municipio_id='', $campo='', $posicao='', $script='', $vazio='', $projeto_comunidade=0){
	//$saida=selecionar_comunidade_para_ajax($municipio_id, $campo, $script, $vazio, $projeto_comunidade);
	
	$sql = new BDConsulta;
	$sql->adTabela('social_comunidade');
	$sql->adCampo('social_comunidade_id, social_comunidade_nome');
	$sql->adOrdem('social_comunidade_nome ASC');
	$sql->adOnde('social_comunidade_municipio="'.$municipio_id.'"');
	$comunidades=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$vetor['']=$vazio;
	foreach($comunidades as $linha) $vetor[utf8_encode($linha['social_comunidade_id'])]=utf8_encode($linha['social_comunidade_nome']);
	$saida=selecionaVetor($vetor, $campo, $script);
	
	
	
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_comunidade_ajax");		
	
function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}		
$xajax->registerFunction("selecionar_cidades_ajax");	

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script, $projeto_id=null){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id); 
	else  $sql->adOnde('sisvalor_projeto IS NULL');
	
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
	
function mudar_posicao_envolvido_ajax($ordem, $projeto_contato_id, $direcao, $projeto_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $projeto_contato_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_contatos');
		$sql->adOnde('projeto_contato_id != '.$projeto_contato_id);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
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
			$sql->adTabela('projeto_contatos');
			$sql->adAtualizar('ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_contato_id = '.$projeto_contato_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_contatos');
					$sql->adAtualizar('ordem', $idx);
					$sql->adOnde('projeto_contato_id = '.$acao['projeto_contato_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_contatos');
					$sql->adAtualizar('ordem', $idx + 1);
					$sql->adOnde('projeto_contato_id = '.$acao['projeto_contato_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_envolvidos($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("envolvidos","innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_envolvido_ajax");		

function incluir_envolvido_ajax($projeto_id=0, $uuid='', $contato_id, $envolvimento, $perfil){
	$envolvimento=previnirXSS(utf8_decode($envolvimento));
	$perfil=previnirXSS(utf8_decode($perfil));
	$sql = new BDConsulta;
	//verificar se já existe
	$sql->adTabela('projeto_contatos');
	$sql->adCampo('count(projeto_contato_id) AS soma');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_id ='.$projeto_id);	
	$sql->adOnde('contato_id ='.$contato_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->limpar();

	if ($ja_existe){
		$sql->adTabela('projeto_contatos');
		$sql->adAtualizar('envolvimento', $envolvimento);	
		$sql->adAtualizar('perfil', $perfil);	
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
		$sql->adOnde('contato_id ='.$contato_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('projeto_contatos');
		$sql->adCampo('count(projeto_contato_id) AS soma');
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('projeto_contatos');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', $soma_total);
		$sql->adInserir('envolvimento', $envolvimento);
		$sql->adInserir('perfil', $perfil);
		$sql->adInserir('contato_id', $contato_id);
		$sql->exec();
		}
	$saida=atualizar_envolvidos($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("envolvidos","innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("incluir_envolvido_ajax");	

function excluir_envolvido_ajax($projeto_contato_id, $projeto_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_contatos');
	$sql->adOnde('projeto_contato_id='.$projeto_contato_id);
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_id='.$projeto_id);
	$sql->exec();
	$saida=atualizar_envolvidos($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("envolvidos","innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("excluir_envolvido_ajax");	

function atualizar_envolvidos($projeto_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('projeto_contatos', 'pc');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
	if ($uuid) $sql->adOnde('pc.uuid = \''.$uuid.'\'');
	else $sql->adOnde('pc.projeto_id = '.$projeto_id);
	$sql->adCampo('cia_nome, projeto_contato_id, contato_funcao, envolvimento, perfil, pc.contato_id, ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
	$sql->adOrdem('ordem');
	$contatos=$sql->ListaChave('contato_id');
	$sql->limpar();
	$saida='';
	if (count($contatos)) {
		$saida.='<table cellspacing=0 cellpadding=0 class="tbl1" align=left>';
		$saida.= '<tr><th></th><th>Nome</th><th>'.utf8_encode($config['organizacao']).'</th><th>'.utf8_encode('Função').'</th><th>'.utf8_encode('Relevância').'</th><th>'.utf8_encode('Característica/Perfil').'</th><th></th></tr>';
		foreach ($contatos as $contato_id => $contato_data) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left" style="white-space: nowrap">'.utf8_encode($contato_data['nome_contato']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['cia_nome']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['contato_funcao']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['envolvimento']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['perfil']).'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_envolvido('.$contato_data['projeto_contato_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este envolvido?\')) {excluir_envolvido('.$contato_data['projeto_contato_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}

function mudar_posicao_integrante_ajax($ordem, $projeto_integrantes_id, $direcao, $projeto_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$projeto_integrantes_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_integrantes');
		$sql->adOnde('projeto_integrantes_id != '.$projeto_integrantes_id);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
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
			$sql->adTabela('projeto_integrantes');
			$sql->adAtualizar('ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_integrantes_id = '.$projeto_integrantes_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_integrantes');
					$sql->adAtualizar('ordem', $idx);
					$sql->adOnde('projeto_integrantes_id = '.$acao['projeto_integrantes_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_integrantes');
					$sql->adAtualizar('ordem', $idx + 1);
					$sql->adOnde('projeto_integrantes_id = '.$acao['projeto_integrantes_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_integrantes($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("integrantes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_integrante_ajax");	

function incluir_integrante_ajax($projeto_id=0, $uuid='', $contato_id, $projeto_integrante_competencia, $projeto_integrante_atributo='', $projeto_integrantes_situacao='', $projeto_integrantes_necessidade=''){
	$sql = new BDConsulta;
	$projeto_integrante_competencia=previnirXSS(utf8_decode($projeto_integrante_competencia));
	$projeto_integrante_atributo=previnirXSS(utf8_decode($projeto_integrante_atributo));
	$projeto_integrantes_situacao=previnirXSS(utf8_decode($projeto_integrantes_situacao));
	$projeto_integrantes_necessidade=previnirXSS(utf8_decode($projeto_integrantes_necessidade));
	//verificar se já existe
	$sql->adTabela('projeto_integrantes');
	$sql->adCampo('count(projeto_integrantes_id) AS soma');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_id ='.(int)$projeto_id);	
	$sql->adOnde('contato_id ='.(int)$contato_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->limpar();

	if ($ja_existe){
		$sql->adTabela('projeto_integrantes');
		$sql->adAtualizar('projeto_integrante_competencia', $projeto_integrante_competencia);	
		$sql->adAtualizar('projeto_integrante_atributo', $projeto_integrante_atributo);
		$sql->adAtualizar('projeto_integrantes_situacao', $projeto_integrantes_situacao);
		$sql->adAtualizar('projeto_integrantes_necessidade', $projeto_integrantes_necessidade);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
		$sql->adOnde('contato_id ='.$contato_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('projeto_integrantes');
		$sql->adCampo('count(projeto_integrantes_id) AS soma');
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('projeto_integrantes');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', $soma_total);
		$sql->adInserir('projeto_integrante_competencia', $projeto_integrante_competencia);
		$sql->adInserir('projeto_integrante_atributo', $projeto_integrante_atributo);
		$sql->adInserir('projeto_integrantes_situacao', $projeto_integrantes_situacao);
		$sql->adInserir('projeto_integrantes_necessidade', $projeto_integrantes_necessidade);
		$sql->adInserir('contato_id', $contato_id);
		$sql->exec();
		}
	$saida=atualizar_integrantes($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("integrantes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_integrante_ajax");

function excluir_integrante_ajax($projeto_integrantes_id, $projeto_id, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_integrantes');
	$sql->adOnde('projeto_integrantes_id='.$projeto_integrantes_id);
	$sql->exec();
	$saida=atualizar_integrantes($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("integrantes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_integrante_ajax");	

function atualizar_integrantes($projeto_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('projeto_integrantes', 'pc');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pc.projeto_id = '.$projeto_id);
	$sql->adCampo('cia_nome, projeto_integrantes_id, contato_funcao, projeto_integrante_competencia, projeto_integrante_atributo, projeto_integrantes_situacao, projeto_integrantes_necessidade, pc.contato_id, ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
	$sql->adOrdem('ordem');
	$integrantes=$sql->ListaChave('contato_id');
	$sql->limpar();
	$saida='';
	if (count($integrantes)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'Nome do contato d'.$config['genero_projeto'].' '.$config['projeto'].' que tem envolvimento. No caso de inserção de dados n'.$config['genero_projeto'].' '.$config['projeto'].' poderão ser informados automaticamente por e-mail.').'Nome'.dicaF().'</th><th>'.$config['organizacao'].'</th><th>Função</th><th>Competência</th><th>Atributos</th><th>Situação</th><th>Necessidade</th><th></th></tr>';
		foreach ($integrantes as $contato_id => $integrante) {
			$saida.= '<tr align="center">';
			$saida.= '<td>';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left" style="white-space: nowrap">'.$integrante['nome_contato'].'</td>';
			$saida.= '<td align="left">'.$integrante['cia_nome'].'</td>';
			$saida.= '<td align="left">'.$integrante['contato_funcao'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrante_competencia'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrante_atributo'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrantes_situacao'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrantes_necessidade'].'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_integrante('.$integrante['projeto_integrantes_id'].');">'.imagem('icones/editar.gif', 'Editar Integrante', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o contato integrante com '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este integrante?\')) {excluir_integrante('.$integrante['projeto_integrantes_id'].');}">'.imagem('icones/remover.png', 'Excluir Integrante', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o contato integrante com '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}


function editar_integrante($projeto_integrantes_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('projeto_integrantes');
	$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = projeto_integrantes.contato_id');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao, projeto_integrante_competencia, projeto_integrantes.contato_id, projeto_integrante_atributo, projeto_integrantes_situacao, projeto_integrantes_necessidade');
	$sql->adOnde('projeto_integrantes_id = '.(int)$projeto_integrantes_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$nome=$linha['nome'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao'] : '').($linha['cia_nome'] && $Aplic->getPref('om_usuario') ? ' - '.$linha['cia_nome'] : '');
	
	$objResposta->assign("projeto_integrantes_id","value", $projeto_integrantes_id);
	$objResposta->assign("nome_integrante","value", utf8_encode($nome));
	$objResposta->assign("integrante_id","value", $linha['contato_id']);	
	$objResposta->assign("projeto_integrante_atributo","value", utf8_encode($linha['projeto_integrante_atributo']));	
	$objResposta->assign("apoio1","value", utf8_encode($linha['projeto_integrante_atributo']));	
	
	$objResposta->assign("projeto_integrantes_situacao","value", utf8_encode($linha['projeto_integrantes_situacao']));	
	$objResposta->assign("apoio2","value", utf8_encode($linha['projeto_integrantes_situacao']));	
	
	$objResposta->assign("projeto_integrantes_necessidade","value", utf8_encode($linha['projeto_integrantes_necessidade']));	
	$objResposta->assign("apoio3","value", utf8_encode($linha['projeto_integrantes_necessidade']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_integrante");	



function editar_envolvido($projeto_contato_id){
	global $config, $Aplic;

	$sql = new BDConsulta;
	$sql->adTabela('projeto_contatos');
	$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = projeto_contatos.contato_id');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao, envolvimento, perfil, projeto_contatos.contato_id');
	$sql->adOnde('projeto_contato_id = '.(int)$projeto_contato_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$nome=$linha['nome'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao'] : '').($linha['cia_nome'] && $Aplic->getPref('om_usuario') ? ' - '.$linha['cia_nome'] : '');
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("projeto_contato_id","value", $projeto_contato_id);
	$objResposta->assign("nome_envolvido","value", utf8_encode($nome));
	$objResposta->assign("envolvimento","value", utf8_encode($linha['envolvimento']));
	$objResposta->assign("envolvido_id","value", $linha['contato_id']);	
	$objResposta->assign("perfil","value", utf8_encode($linha['perfil']));	
	$objResposta->assign("apoio1","value", utf8_encode($linha['perfil']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_envolvido");	



function exibir_combo($posicao, $tabela, $chave='', $campo='', $onde='', $ordem='', $script='', $campo_id='', $campoatual='', $campobranco=true, $tabela2='', $uniao2='', $tabela3='', $uniao3=''){
	$sql = new BDConsulta;
	$sql->adTabela($tabela);
	if ($tabela2) $sql->esqUnir($tabela2, $tabela2, $uniao2);
	if ($tabela3) $sql->esqUnir($tabela3, $tabela3, $uniao3);
	if ($chave) $sql->adCampo($chave);
	if ($campo) $sql->adCampo($campo);
	if ($onde) $sql->adOnde($onde);
	if ($ordem) $sql->adOrdem($ordem);
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$chave=explode('.',$chave); 
	$chave = array_pop($chave);
	if ($campobranco) $vetor[]='';
	foreach($linhas as $linha)$vetor[$linha[$chave]]=utf8_encode($linha[$campo]);
	$saida=selecionaVetor($vetor, $campo_id, $script, $campoatual);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("exibir_combo");	
		
function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");			




function mudar_posicao_gestao($ordem, $projeto_gestao_id, $direcao, $projeto_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $projeto_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_gestao');
		$sql->adOnde('projeto_gestao_id != '.(int)$projeto_gestao_id);
		if ($uuid) $sql->adOnde('projeto_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_gestao_projeto = '.(int)$projeto_id);
		$sql->adOrdem('projeto_gestao_ordem');
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
			$sql->adTabela('projeto_gestao');
			$sql->adAtualizar('projeto_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_gestao_id = '.(int)$projeto_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_gestao');
					$sql->adAtualizar('projeto_gestao_ordem', $idx);
					$sql->adOnde('projeto_gestao_id = '.(int)$acao['projeto_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_gestao');
					$sql->adAtualizar('projeto_gestao_ordem', $idx + 1);
					$sql->adOnde('projeto_gestao_id = '.(int)$acao['projeto_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$projeto_id=0, 
	$uuid='',  
	
	$projeto_projeto=null,
	$projeto_tarefa=null,
	$projeto_perspectiva=null,
	$projeto_tema=null,
	$projeto_objetivo=null,
	$projeto_fator=null,
	$projeto_estrategia=null,
	$projeto_meta=null,
	$projeto_pratica=null,
	$projeto_acao=null,
	$projeto_canvas=null,
	$projeto_risco=null,
	$projeto_risco_resposta=null,
	$projeto_indicador=null,
	$projeto_calendario=null,
	$projeto_monitoramento=null,
	$projeto_ata=null,
	$projeto_mswot=null,
	$projeto_swot=null,
	$projeto_operativo=null,
	$projeto_instrumento=null,
	$projeto_recurso=null,
	$projeto_problema=null,
	$projeto_demanda=null,
	$projeto_programa=null,
	$projeto_licao=null,
	$projeto_evento=null,
	$projeto_link=null,
	$projeto_avaliacao=null,
	$projeto_tgn=null,
	$projeto_brainstorm=null,
	$projeto_gut=null,
	$projeto_causa_efeito=null,
	$projeto_arquivo=null,
	$projeto_forum=null,
	$projeto_checklist=null,
	$projeto_agenda=null,
	$projeto_agrupamento=null,
	$projeto_patrocinador=null,
	$projeto_template=null,
	$projeto_painel=null,
	$projeto_painel_odometro=null,
	$projeto_painel_composicao=null,
	$projeto_tr=null,
	$projeto_me=null,
	$projeto_acao_item=null,
	$projeto_beneficio=null,
	$projeto_painel_slideshow=null,
	$projeto_projeto_viabilidade=null,
	$projeto_projeto_abertura=null,
	$projeto_plano_gestao=null,
	$projeto_ssti=null,
	$projeto_laudo=null,
	$projeto_trelo=null,
	$projeto_trelo_cartao=null,
	$projeto_pdcl=null,
	$projeto_pdcl_item=null
	)
	{
	if (
		$projeto_projeto || 
		$projeto_tarefa || 
		$projeto_perspectiva || 
		$projeto_tema || 
		$projeto_objetivo || 
		$projeto_fator || 
		$projeto_estrategia || 
		$projeto_meta || 
		$projeto_pratica || 
		$projeto_acao || 
		$projeto_canvas || 
		$projeto_risco || 
		$projeto_risco_resposta || 
		$projeto_indicador || 
		$projeto_calendario || 
		$projeto_monitoramento || 
		$projeto_ata || 
		$projeto_mswot || 
		$projeto_swot || 
		$projeto_operativo || 
		$projeto_instrumento || 
		$projeto_recurso || 
		$projeto_problema || 
		$projeto_demanda || 
		$projeto_programa || 
		$projeto_licao || 
		$projeto_evento || 
		$projeto_link || 
		$projeto_avaliacao || 
		$projeto_tgn || 
		$projeto_brainstorm || 
		$projeto_gut || 
		$projeto_causa_efeito || 
		$projeto_arquivo || 
		$projeto_forum || 
		$projeto_checklist || 
		$projeto_agenda || 
		$projeto_agrupamento || 
		$projeto_patrocinador || 
		$projeto_template || 
		$projeto_painel || 
		$projeto_painel_odometro || 
		$projeto_painel_composicao || 
		$projeto_tr || 
		$projeto_me || 
		$projeto_acao_item || 
		$projeto_beneficio || 
		$projeto_painel_slideshow || 
		$projeto_projeto_viabilidade || 
		$projeto_projeto_abertura || 
		$projeto_plano_gestao|| 
		$projeto_ssti || 
		$projeto_laudo || 
		$projeto_trelo || 
		$projeto_trelo_cartao || 
		$projeto_pdcl || 
		$projeto_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;


		if (!$Aplic->profissional) {
			$sql->setExcluir('projeto_gestao');
			if ($uuid) $sql->adOnde('projeto_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('projeto_gestao_projeto ='.(int)$projeto_id);	
			$sql->exec();
			}

		//verificar se já não inseriu antes
		$sql->adTabela('projeto_gestao');
		$sql->adCampo('count(projeto_gestao_id)');
		if ($uuid) $sql->adOnde('projeto_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_gestao_projeto ='.(int)$projeto_id);	
		
		if ($projeto_tarefa) $sql->adOnde('projeto_gestao_tarefa='.(int)$projeto_tarefa);
		
		elseif ($projeto_projeto) $sql->adOnde('projeto_gestao_semelhante='.(int)$projeto_projeto);
		
		elseif ($projeto_perspectiva) $sql->adOnde('projeto_gestao_perspectiva='.(int)$projeto_perspectiva);
		elseif ($projeto_tema) $sql->adOnde('projeto_gestao_tema='.(int)$projeto_tema);
		elseif ($projeto_objetivo) $sql->adOnde('projeto_gestao_objetivo='.(int)$projeto_objetivo);
		elseif ($projeto_fator) $sql->adOnde('projeto_gestao_fator='.(int)$projeto_fator);
		elseif ($projeto_estrategia) $sql->adOnde('projeto_gestao_estrategia='.(int)$projeto_estrategia);
		elseif ($projeto_acao) $sql->adOnde('projeto_gestao_acao='.(int)$projeto_acao);
		elseif ($projeto_pratica) $sql->adOnde('projeto_gestao_pratica='.(int)$projeto_pratica);
		elseif ($projeto_meta) $sql->adOnde('projeto_gestao_meta='.(int)$projeto_meta);
		elseif ($projeto_canvas) $sql->adOnde('projeto_gestao_canvas='.(int)$projeto_canvas);
		elseif ($projeto_risco) $sql->adOnde('projeto_gestao_risco='.(int)$projeto_risco);
		elseif ($projeto_risco_resposta) $sql->adOnde('projeto_gestao_risco_resposta='.(int)$projeto_risco_resposta);
		elseif ($projeto_indicador) $sql->adOnde('projeto_gestao_indicador='.(int)$projeto_indicador);
		elseif ($projeto_calendario) $sql->adOnde('projeto_gestao_calendario='.(int)$projeto_calendario);
		elseif ($projeto_monitoramento) $sql->adOnde('projeto_gestao_monitoramento='.(int)$projeto_monitoramento);
		elseif ($projeto_ata) $sql->adOnde('projeto_gestao_ata='.(int)$projeto_ata);
		elseif ($projeto_mswot) $sql->adOnde('projeto_gestao_mswot='.(int)$projeto_mswot);
		elseif ($projeto_swot) $sql->adOnde('projeto_gestao_swot='.(int)$projeto_swot);
		elseif ($projeto_operativo) $sql->adOnde('projeto_gestao_operativo='.(int)$projeto_operativo);
		elseif ($projeto_instrumento) $sql->adOnde('projeto_gestao_instrumento='.(int)$projeto_instrumento);
		elseif ($projeto_recurso) $sql->adOnde('projeto_gestao_recurso='.(int)$projeto_recurso);
		elseif ($projeto_problema) $sql->adOnde('projeto_gestao_problema='.(int)$projeto_problema);
		elseif ($projeto_demanda) $sql->adOnde('projeto_gestao_demanda='.(int)$projeto_demanda);
		elseif ($projeto_programa) $sql->adOnde('projeto_gestao_programa='.(int)$projeto_programa);
		elseif ($projeto_licao) $sql->adOnde('projeto_gestao_licao='.(int)$projeto_licao);
		elseif ($projeto_evento) $sql->adOnde('projeto_gestao_evento='.(int)$projeto_evento);
		elseif ($projeto_link) $sql->adOnde('projeto_gestao_link='.(int)$projeto_link);
		elseif ($projeto_avaliacao) $sql->adOnde('projeto_gestao_avaliacao='.(int)$projeto_avaliacao);
		elseif ($projeto_tgn) $sql->adOnde('projeto_gestao_tgn='.(int)$projeto_tgn);
		elseif ($projeto_brainstorm) $sql->adOnde('projeto_gestao_brainstorm='.(int)$projeto_brainstorm);
		elseif ($projeto_gut) $sql->adOnde('projeto_gestao_gut='.(int)$projeto_gut);
		elseif ($projeto_causa_efeito) $sql->adOnde('projeto_gestao_causa_efeito='.(int)$projeto_causa_efeito);
		elseif ($projeto_arquivo) $sql->adOnde('projeto_gestao_arquivo='.(int)$projeto_arquivo);
		elseif ($projeto_forum) $sql->adOnde('projeto_gestao_forum='.(int)$projeto_forum);
		elseif ($projeto_checklist) $sql->adOnde('projeto_gestao_checklist='.(int)$projeto_checklist);
		elseif ($projeto_agenda) $sql->adOnde('projeto_gestao_agenda='.(int)$projeto_agenda);
		elseif ($projeto_agrupamento) $sql->adOnde('projeto_gestao_agrupamento='.(int)$projeto_agrupamento);
		elseif ($projeto_patrocinador) $sql->adOnde('projeto_gestao_patrocinador='.(int)$projeto_patrocinador);
		elseif ($projeto_template) $sql->adOnde('projeto_gestao_template='.(int)$projeto_template);
		elseif ($projeto_painel) $sql->adOnde('projeto_gestao_painel='.(int)$projeto_painel);
		elseif ($projeto_painel_odometro) $sql->adOnde('projeto_gestao_painel_odometro='.(int)$projeto_painel_odometro);
		elseif ($projeto_painel_composicao) $sql->adOnde('projeto_gestao_painel_composicao='.(int)$projeto_painel_composicao);
		elseif ($projeto_tr) $sql->adOnde('projeto_gestao_tr='.(int)$projeto_tr);
		elseif ($projeto_me) $sql->adOnde('projeto_gestao_me='.(int)$projeto_me);
		elseif ($projeto_acao_item) $sql->adOnde('projeto_gestao_acao_item='.(int)$projeto_acao_item);
		elseif ($projeto_beneficio) $sql->adOnde('projeto_gestao_beneficio='.(int)$projeto_beneficio);
		elseif ($projeto_painel_slideshow) $sql->adOnde('projeto_gestao_painel_slideshow='.(int)$projeto_painel_slideshow);
		elseif ($projeto_projeto_viabilidade) $sql->adOnde('projeto_gestao_projeto_viabilidade='.(int)$projeto_projeto_viabilidade);
		elseif ($projeto_projeto_abertura) $sql->adOnde('projeto_gestao_projeto_abertura='.(int)$projeto_projeto_abertura);
		elseif ($projeto_plano_gestao) $sql->adOnde('projeto_gestao_plano_gestao='.(int)$projeto_plano_gestao);
		elseif ($projeto_ssti) $sql->adOnde('projeto_gestao_ssti='.(int)$projeto_ssti);
		elseif ($projeto_laudo) $sql->adOnde('projeto_gestao_laudo='.(int)$projeto_laudo);
		elseif ($projeto_trelo) $sql->adOnde('projeto_gestao_trelo='.(int)$projeto_trelo);
		elseif ($projeto_trelo_cartao) $sql->adOnde('projeto_gestao_trelo_cartao='.(int)$projeto_trelo_cartao);
		elseif ($projeto_pdcl) $sql->adOnde('projeto_gestao_pdcl='.(int)$projeto_pdcl);
		elseif ($projeto_pdcl_item) $sql->adOnde('projeto_gestao_pdcl_item='.(int)$projeto_pdcl_item);
	
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('projeto_gestao');
			$sql->adCampo('MAX(projeto_gestao_ordem)');
			if ($uuid) $sql->adOnde('projeto_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('projeto_gestao_projeto ='.(int)$projeto_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('projeto_gestao');
			if ($uuid) $sql->adInserir('projeto_gestao_uuid', $uuid);
			else $sql->adInserir('projeto_gestao_projeto', (int)$projeto_id);
			
			if ($projeto_tarefa) $sql->adInserir('projeto_gestao_tarefa', (int)$projeto_tarefa);
			
			elseif ($projeto_projeto) $sql->adInserir('projeto_gestao_semelhante', (int)$projeto_projeto);
			
			elseif ($projeto_perspectiva) $sql->adInserir('projeto_gestao_perspectiva', (int)$projeto_perspectiva);
			elseif ($projeto_tema) $sql->adInserir('projeto_gestao_tema', (int)$projeto_tema);
			elseif ($projeto_objetivo) $sql->adInserir('projeto_gestao_objetivo', (int)$projeto_objetivo);
			elseif ($projeto_fator) $sql->adInserir('projeto_gestao_fator', (int)$projeto_fator);
			elseif ($projeto_estrategia) $sql->adInserir('projeto_gestao_estrategia', (int)$projeto_estrategia);
			elseif ($projeto_acao) $sql->adInserir('projeto_gestao_acao', (int)$projeto_acao);
			elseif ($projeto_pratica) $sql->adInserir('projeto_gestao_pratica', (int)$projeto_pratica);
			elseif ($projeto_meta) $sql->adInserir('projeto_gestao_meta', (int)$projeto_meta);
			elseif ($projeto_canvas) $sql->adInserir('projeto_gestao_canvas', (int)$projeto_canvas);
			elseif ($projeto_risco) $sql->adInserir('projeto_gestao_risco', (int)$projeto_risco);
			elseif ($projeto_risco_resposta) $sql->adInserir('projeto_gestao_risco_resposta', (int)$projeto_risco_resposta);
			elseif ($projeto_indicador) $sql->adInserir('projeto_gestao_indicador', (int)$projeto_indicador);
			elseif ($projeto_calendario) $sql->adInserir('projeto_gestao_calendario', (int)$projeto_calendario);
			elseif ($projeto_monitoramento) $sql->adInserir('projeto_gestao_monitoramento', (int)$projeto_monitoramento);
			elseif ($projeto_ata) $sql->adInserir('projeto_gestao_ata', (int)$projeto_ata);
			elseif ($projeto_mswot) $sql->adInserir('projeto_gestao_mswot', (int)$projeto_mswot);
			elseif ($projeto_swot) $sql->adInserir('projeto_gestao_swot', (int)$projeto_swot);
			elseif ($projeto_operativo) $sql->adInserir('projeto_gestao_operativo', (int)$projeto_operativo);
			elseif ($projeto_instrumento) $sql->adInserir('projeto_gestao_instrumento', (int)$projeto_instrumento);
			elseif ($projeto_recurso) $sql->adInserir('projeto_gestao_recurso', (int)$projeto_recurso);
			elseif ($projeto_problema) $sql->adInserir('projeto_gestao_problema', (int)$projeto_problema);
			elseif ($projeto_demanda) $sql->adInserir('projeto_gestao_demanda', (int)$projeto_demanda);
			elseif ($projeto_programa) $sql->adInserir('projeto_gestao_programa', (int)$projeto_programa);
			elseif ($projeto_licao) $sql->adInserir('projeto_gestao_licao', (int)$projeto_licao);
			elseif ($projeto_evento) $sql->adInserir('projeto_gestao_evento', (int)$projeto_evento);
			elseif ($projeto_link) $sql->adInserir('projeto_gestao_link', (int)$projeto_link);
			elseif ($projeto_avaliacao) $sql->adInserir('projeto_gestao_avaliacao', (int)$projeto_avaliacao);
			elseif ($projeto_tgn) $sql->adInserir('projeto_gestao_tgn', (int)$projeto_tgn);
			elseif ($projeto_brainstorm) $sql->adInserir('projeto_gestao_brainstorm', (int)$projeto_brainstorm);
			elseif ($projeto_gut) $sql->adInserir('projeto_gestao_gut', (int)$projeto_gut);
			elseif ($projeto_causa_efeito) $sql->adInserir('projeto_gestao_causa_efeito', (int)$projeto_causa_efeito);
			elseif ($projeto_arquivo) $sql->adInserir('projeto_gestao_arquivo', (int)$projeto_arquivo);
			elseif ($projeto_forum) $sql->adInserir('projeto_gestao_forum', (int)$projeto_forum);
			elseif ($projeto_checklist) $sql->adInserir('projeto_gestao_checklist', (int)$projeto_checklist);
			elseif ($projeto_agenda) $sql->adInserir('projeto_gestao_agenda', (int)$projeto_agenda);
			elseif ($projeto_agrupamento) $sql->adInserir('projeto_gestao_agrupamento', (int)$projeto_agrupamento);
			elseif ($projeto_patrocinador) $sql->adInserir('projeto_gestao_patrocinador', (int)$projeto_patrocinador);
			elseif ($projeto_template) $sql->adInserir('projeto_gestao_template', (int)$projeto_template);
			elseif ($projeto_painel) $sql->adInserir('projeto_gestao_painel', (int)$projeto_painel);
			elseif ($projeto_painel_odometro) $sql->adInserir('projeto_gestao_painel_odometro', (int)$projeto_painel_odometro);
			elseif ($projeto_painel_composicao) $sql->adInserir('projeto_gestao_painel_composicao', (int)$projeto_painel_composicao);
			elseif ($projeto_tr) $sql->adInserir('projeto_gestao_tr', (int)$projeto_tr);
			elseif ($projeto_me) $sql->adInserir('projeto_gestao_me', (int)$projeto_me);
			elseif ($projeto_acao_item) $sql->adInserir('projeto_gestao_acao_item', (int)$projeto_acao_item);
			elseif ($projeto_beneficio) $sql->adInserir('projeto_gestao_beneficio', (int)$projeto_beneficio);
			elseif ($projeto_painel_slideshow) $sql->adInserir('projeto_gestao_painel_slideshow', (int)$projeto_painel_slideshow);
			elseif ($projeto_projeto_viabilidade) $sql->adInserir('projeto_gestao_projeto_viabilidade', (int)$projeto_projeto_viabilidade);
			elseif ($projeto_projeto_abertura) $sql->adInserir('projeto_gestao_projeto_abertura', (int)$projeto_projeto_abertura);
			elseif ($projeto_plano_gestao) $sql->adInserir('projeto_gestao_plano_gestao', (int)$projeto_plano_gestao);
			elseif ($projeto_ssti) $sql->adInserir('projeto_gestao_ssti', (int)$projeto_ssti);
			elseif ($projeto_laudo) $sql->adInserir('projeto_gestao_laudo', (int)$projeto_laudo);
			elseif ($projeto_trelo) $sql->adInserir('projeto_gestao_trelo', (int)$projeto_trelo);
			elseif ($projeto_trelo_cartao) $sql->adInserir('projeto_gestao_trelo_cartao', (int)$projeto_trelo_cartao);
			elseif ($projeto_pdcl) $sql->adInserir('projeto_gestao_pdcl', (int)$projeto_pdcl);
			elseif ($projeto_pdcl_item) $sql->adInserir('projeto_gestao_pdcl_item', (int)$projeto_pdcl_item);
			
			$sql->adInserir('projeto_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($projeto_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($projeto_id=0, $uuid='', $projeto_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_gestao');
	$sql->adOnde('projeto_gestao_id='.(int)$projeto_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($projeto_id=0, $uuid=''){	
	$saida=atualizar_gestao($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($projeto_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('projeto_gestao');
	$sql->adCampo('projeto_gestao.*');
	if ($uuid) $sql->adOnde('projeto_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_gestao_projeto ='.(int)$projeto_id);	
	$sql->adOrdem('projeto_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['projeto_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_gestao_tarefa']).'</td>';
		
		elseif ($gestao_data['projeto_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['projeto_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['projeto_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']).'</td>';
		elseif ($gestao_data['projeto_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']).'</td>';
		elseif ($gestao_data['projeto_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']).'</td>';
		elseif ($gestao_data['projeto_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']).'</td>';
		elseif ($gestao_data['projeto_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']).'</td>';
		elseif ($gestao_data['projeto_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']).'</td>';
		elseif ($gestao_data['projeto_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']).'</td>';
		elseif ($gestao_data['projeto_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']).'</td>';
		elseif ($gestao_data['projeto_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']).'</td>';
		elseif ($gestao_data['projeto_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['projeto_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']).'</td>';
		elseif ($gestao_data['projeto_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_gestao_calendario']).'</td>';
		elseif ($gestao_data['projeto_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['projeto_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']).'</td>';
		elseif ($gestao_data['projeto_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_gestao_mswot']).'</td>';
		elseif ($gestao_data['projeto_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']).'</td>';
		elseif ($gestao_data['projeto_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']).'</td>';
		elseif ($gestao_data['projeto_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']).'</td>';
		elseif ($gestao_data['projeto_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']).'</td>';
		elseif ($gestao_data['projeto_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['projeto_gestao_problema']).'</td>';
		elseif ($gestao_data['projeto_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']).'</td>';
		elseif ($gestao_data['projeto_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']).'</td>';
		elseif ($gestao_data['projeto_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']).'</td>';
		elseif ($gestao_data['projeto_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']).'</td>';
		elseif ($gestao_data['projeto_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']).'</td>';
		elseif ($gestao_data['projeto_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['projeto_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']).'</td>';
		elseif ($gestao_data['projeto_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['projeto_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_gestao_gut']).'</td>';
		elseif ($gestao_data['projeto_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['projeto_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']).'</td>';
		elseif ($gestao_data['projeto_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']).'</td>';
		elseif ($gestao_data['projeto_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']).'</td>';
		elseif ($gestao_data['projeto_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_gestao_agenda']).'</td>';
		elseif ($gestao_data['projeto_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['projeto_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['projeto_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['projeto_gestao_template']).'</td>';
		elseif ($gestao_data['projeto_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['projeto_gestao_painel']).'</td>';
		elseif ($gestao_data['projeto_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['projeto_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['projeto_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']).'</td>';	
		elseif ($gestao_data['projeto_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']).'</td>';	
		elseif ($gestao_data['projeto_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['projeto_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['projeto_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['projeto_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['projeto_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['projeto_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['projeto_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_gestao_ssti']).'</td>';
		elseif ($gestao_data['projeto_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_gestao_laudo']).'</td>';
		elseif ($gestao_data['projeto_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_gestao_trelo']).'</td>';
		elseif ($gestao_data['projeto_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['projeto_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_gestao_pdcl']).'</td>';
		elseif ($gestao_data['projeto_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_gestao_pdcl_item']).'</td>';
		
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['projeto_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}		



$xajax->processRequest();
?>