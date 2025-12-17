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

function exibir_usuarios($usuarios, $local=''){
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
	$objResposta->assign($local, "innerHTML", utf8_encode($saida_usuarios));
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


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");		

function mudar_disponiveis($cia_id=null, $pesquisa=null, $campo=null, $posicao=null, $script=null, 
	$projeto=null, 
	$tarefa=null, 
	$perspectiva=null, 
	$tema=null, 
	$objetivo=null, 
	$fator=null, 
	$estrategia=null, 
	$meta=null, 
	$pratica=null, 
	$indicador=null, 
	$acao=null, 
	$canvas=null, 
	$risco=null, 
	$risco_resposta=null, 
	$calendario=null, 
	$monitoramento=null, 
	$ata=null, 
	$mswot=null, 
	$swot=null, 
	$operativo=null, 
	$instrumento=null, 
	$recurso=null, 
	$problema=null, 
	$demanda=null, 
	$programa=null, 
	$licao=null, 
	$evento=null, 
	$link=null, 
	$avaliacao=null, 
	$tgn=null, 
	$brainstorm=null, 
	$gut=null, 
	$causa_efeito=null, 
	$arquivo=null, 
	$forum=null, 
	$checklist=null, 
	$agenda =null, 
	$agrupamento=null, 
	$patrocinador=null, 
	$template=null, 
	$painel=null, 
	$painel_odometro=null, 
	$painel_composicao=null, 
	$tr=null, 
	$me=null,
	$acao_item=null,
	$beneficio=null,
	$painel_slideshow=null,
	$projeto_viabilidade=null,
	$projeto_abertura=null,
	$plano_gestao=null,
	$ssti=null,
	$laudo=null
	){
	global $Aplic;
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	
	$pesquisa=previnirXSS(utf8_decode($pesquisa));
	
	
	if ($projeto){
		$sql->adTabela('projetos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projetos.projeto_cia');
	 	$sql->adCampo('projetos.projeto_id AS campo, projeto_nome AS nome, cia_nome');
	 	$sql->adOnde('projeto_cia='.(int)$cia_id);
	 	$sql->adOnde('projeto_ativo=1');
	 	} 
	elseif ($tarefa){
		$sql->adTabela('tarefas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = tarefas.tarefa_cia');
	 	$sql->adCampo('tarefas.tarefa_id AS campo, tarefa_nome AS nome, cia_nome');
	 	$sql->adOnde('tarefa_cia='.(int)$cia_id);
	 	}
	elseif ($perspectiva){
		$sql->adTabela('perspectivas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = perspectivas.pg_perspectiva_cia');
	 	$sql->adCampo('perspectivas.pg_perspectiva_id AS campo, pg_perspectiva_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
	 	$sql->adOnde('pg_perspectiva_ativo=1');
	 	}
	elseif ($tema){
		$sql->adTabela('tema');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = tema_cia');
	 	$sql->adCampo('tema.tema_id AS campo, tema_nome AS nome, cia_nome');
	 	$sql->adOnde('tema_cia='.(int)$cia_id);
	 	$sql->adOnde('tema_ativo=1');
	 	} 	
	elseif ($objetivo){
		$sql->adTabela('objetivo');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = objetivo_cia');
	 	$sql->adCampo('objetivo.objetivo_id AS campo, objetivo_nome AS nome, cia_nome');
	 	$sql->adOnde('objetivo_cia='.(int)$cia_id);
	 	$sql->adOnde('objetivo_ativo=1');
	 	}  	
	elseif ($fator){
		$sql->adTabela('fator');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = fator_cia');
	 	$sql->adCampo('fator_id AS campo, fator_nome AS nome, cia_nome');
	 	$sql->adOnde('fator_cia='.(int)$cia_id);
	 	$sql->adOnde('fator_ativo=1');
	 	} 	
	elseif ($estrategia){
		$sql->adTabela('estrategias');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_estrategia_cia');
	 	$sql->adCampo('estrategias.pg_estrategia_id AS campo, pg_estrategia_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
	 	$sql->adOnde('pg_estrategia_ativo=1');
	 	}  	
	elseif ($meta){
		$sql->adTabela('metas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_meta_cia');
	 	$sql->adCampo('metas.pg_meta_id AS campo, pg_meta_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_meta_cia='.(int)$cia_id);
	 	$sql->adOnde('pg_meta_ativo=1');
	 	} 	
	elseif ($pratica){
		$sql->adTabela('praticas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = praticas.pratica_cia');
	 	$sql->adCampo('praticas.pratica_id AS campo, pratica_nome AS nome, cia_nome');
	 	$sql->adOnde('pratica_cia='.(int)$cia_id);
	 	$sql->adOnde('pratica_ativa=1');
	 	}	
	elseif ($indicador){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pratica_indicador.pratica_indicador_cia');
	 	$sql->adCampo('pratica_indicador.pratica_indicador_id AS campo, pratica_indicador_nome AS nome, cia_nome');
	 	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	 	$sql->adOnde('pratica_indicador_ativo=1');
	 	} 	
	elseif ($acao){
		$sql->adTabela('plano_acao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = plano_acao_cia');
	 	$sql->adCampo('plano_acao_id AS campo, plano_acao_nome AS nome, cia_nome');
	 	$sql->adOnde('plano_acao_cia='.(int)$cia_id);
	 	$sql->adOnde('plano_acao_ativo=1');
	 	}
	elseif ($canvas){
		$sql->adTabela('canvas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = canvas.canvas_cia');
	 	$sql->adCampo('canvas.canvas_id AS campo, canvas_nome AS nome, cia_nome');
	 	$sql->adOnde('canvas_cia='.(int)$cia_id);
	 	$sql->adOnde('canvas_ativo=1');
	 	}
	elseif ($risco){
		$sql->adTabela('risco');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = risco_cia');
	 	$sql->adCampo('risco_id AS campo, risco_nome AS nome, cia_nome');
	 	$sql->adOnde('risco_cia='.(int)$cia_id);
	 	$sql->adOnde('risco_ativo=1');
	 	} 	
	elseif ($risco_resposta){
		$sql->adTabela('risco_resposta');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = risco_resposta_cia');
	 	$sql->adCampo('risco_resposta_id AS campo, risco_resposta_nome AS nome, cia_nome');
	 	$sql->adOnde('risco_resposta_cia='.(int)$cia_id);
	 	$sql->adOnde('risco_resposta_ativo=1');
	 	} 	
	elseif ($calendario){
		$sql->adTabela('calendario');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = calendario_cia');
	 	$sql->adCampo('calendario_id AS campo, calendario_nome AS nome, cia_nome');
	 	$sql->adOnde('calendario_cia='.(int)$cia_id);
	 	$sql->adOnde('calendario_ativo=1');
	 	} 	 	
	elseif ($monitoramento){
		$sql->adTabela('monitoramento');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = monitoramento_cia');
	 	$sql->adCampo('monitoramento.monitoramento_id AS campo, monitoramento_nome AS nome, cia_nome');
	 	$sql->adOnde('monitoramento_cia='.(int)$cia_id);
	 	$sql->adOnde('monitoramento_ativo=1');
	 	}  		
	elseif ($ata){
		$sql->adTabela('ata');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = ata_cia');
	 	$sql->adCampo('ata_id AS campo, ata_titulo AS nome, cia_nome');
	 	$sql->adOnde('ata_cia='.(int)$cia_id);
	 	$sql->adOnde('ata_ativo=1');
	 	}	 		
	elseif ($mswot){
		$sql->adTabela('mswot');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = mswot_cia');
	 	$sql->adCampo('mswot_id AS campo, mswot_nome AS nome, cia_nome');
	 	$sql->adOnde('mswot_cia='.(int)$cia_id);
	 	$sql->adOnde('mswot_ativo=1');
	 	} 
	elseif ($swot){
		$sql->adTabela('swot');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = swot_cia');
	 	$sql->adCampo('swot_id AS campo, swot_nome AS nome, cia_nome');
	 	$sql->adOnde('swot_cia='.(int)$cia_id);
	 	$sql->adOnde('swot_ativo=1');
	 	}	 		
	elseif ($operativo){
		$sql->adTabela('operativo');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = operativo_cia');
	 	$sql->adCampo('operativo_id AS campo, operativo_nome AS nome, cia_nome');
	 	$sql->adOnde('operativo_cia='.(int)$cia_id);
	 	$sql->adOnde('operativo_ativo=1');
	 	} 	 	
	elseif ($instrumento){
		$sql->adTabela('instrumento');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = instrumento_cia');
	 	$sql->adCampo('instrumento_id AS campo, instrumento_nome AS nome, cia_nome');
	 	$sql->adOnde('instrumento_cia='.(int)$cia_id);
	 	$sql->adOnde('instrumento_ativo=1');
	 	}	 		
	elseif ($recurso){
		$sql->adTabela('recursos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = recurso_cia');
	 	$sql->adCampo('recurso_id AS campo, recurso_nome AS nome, cia_nome');
	 	$sql->adOnde('recurso_cia='.(int)$cia_id);
	 	$sql->adOnde('recurso_ativo=1');
	 	}	
	elseif ($problema){
		$sql->adTabela('problema');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = problema_cia');
	 	$sql->adCampo('problema_id AS campo, problema_nome AS nome, cia_nome');
	 	$sql->adOnde('problema_cia='.(int)$cia_id);
	 	$sql->adOnde('problema_ativo=1');
	 	}	 		
	elseif ($demanda){
		$sql->adTabela('demandas');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = demanda_cia');
	 	$sql->adCampo('demanda_id AS campo, demanda_nome AS nome, cia_nome');
	 	$sql->adOnde('demanda_cia='.(int)$cia_id);
	 	$sql->adOnde('demanda_ativa=1');
	 	} 
	elseif ($programa){
		$sql->adTabela('programa');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = programa_cia');
	 	$sql->adCampo('programa_id AS campo, programa_nome AS nome, cia_nome');
	 	$sql->adOnde('programa_cia='.(int)$cia_id);
	 	$sql->adOnde('programa_ativo=1');
	 	}	 		
	elseif ($licao){
		$sql->adTabela('licao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = licao_cia');
	 	$sql->adCampo('licao_id AS campo, licao_nome AS nome, cia_nome');
	 	$sql->adOnde('licao_cia='.(int)$cia_id);
	 	$sql->adOnde('licao_ativa=1');
	 	} 	 	
	elseif ($evento){
		$sql->adTabela('eventos');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = evento_cia');
	 	$sql->adCampo('evento_id AS campo, evento_titulo AS nome, cia_nome');
	 	$sql->adOnde('evento_cia='.(int)$cia_id);
	 	} 	 		
	elseif ($link){
		$sql->adTabela('links');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = link_cia');
	 	$sql->adCampo('link_id AS campo, link_nome AS nome, cia_nome');
	 	$sql->adOnde('link_cia='.(int)$cia_id);
	 	$sql->adOnde('link_ativo=1');
	 	}
	elseif ($avaliacao){
		$sql->adTabela('avaliacao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = avaliacao_cia');
	 	$sql->adCampo('avaliacao_id AS campo, avaliacao_nome AS nome, cia_nome');
	 	$sql->adOnde('avaliacao_cia='.(int)$cia_id);
	 	$sql->adOnde('avaliacao_ativa=1');
	 	}	 		
	elseif ($tgn){
		$sql->adTabela('tgn');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = tgn_cia');
	 	$sql->adCampo('tgn_id AS campo, tgn_nome AS nome, cia_nome');
	 	$sql->adOnde('tgn_cia='.(int)$cia_id);
	 	$sql->adOnde('tgn_ativo=1');
	 	} 
	elseif ($brainstorm){
		$sql->adTabela('brainstorm');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = brainstorm_cia');
	 	$sql->adCampo('brainstorm_id AS campo,brainstorm_nome AS nome, cia_nome');
	 	$sql->adOnde('brainstorm_cia='.(int)$cia_id);
	 	$sql->adOnde('brainstorm_ativo=1');
	 	} 	 	
	elseif ($gut){
		$sql->adTabela('gut');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = gut_cia');
	 	$sql->adCampo('gut_id AS campo, gut_nome AS nome, cia_nome');
	 	$sql->adOnde('gut_cia='.(int)$cia_id);
	 	$sql->adOnde('gut_ativo=1');
	 	} 	 		
	elseif ($causa_efeito){
		$sql->adTabela('causa_efeito');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = causa_efeito_cia');
	 	$sql->adCampo('causa_efeito_id AS campo, causa_efeito_nome AS nome, cia_nome');
	 	$sql->adOnde('causa_efeito_cia='.(int)$cia_id);
	 	$sql->adOnde('causa_efeito_ativo=1');
	 	}  	 	
	elseif ($arquivo){
		$sql->adTabela('arquivo');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = arquivo_cia');
	 	$sql->adCampo('arquivo_id AS campo, arquivo_nome AS nome, cia_nome');
	 	$sql->adOnde('arquivo_cia='.(int)$cia_id);
	 	$sql->adOnde('arquivo_ativo=1');
	 	} 	 		
	elseif ($forum){
		$sql->adTabela('foruns');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = forum_cia');
	 	$sql->adCampo('forum_id AS campo, forum_nome AS nome, cia_nome');
	 	$sql->adOnde('forum_cia='.(int)$cia_id);
	 	$sql->adOnde('forum_ativo=1');
	 	} 
	elseif ($checklist){
		$sql->adTabela('checklist');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = checklist_cia');
	 	$sql->adCampo('checklist.checklist_id AS campo, checklist_nome AS nome, cia_nome');
	 	$sql->adOnde('checklist_cia='.(int)$cia_id);
	 	$sql->adOnde('checklist_ativo=1');
	 	} 	
	elseif ($agenda){
		$sql->adTabela('agenda');
		$sql->esqUnir('usuarios', 'us', 'us.usuario_id = agenda_dono');
		$sql->esqUnir('contatos', 'co', 'us.usuario_contato = co.contato_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	 	$sql->adCampo('agenda_id AS campo, agenda_nome AS nome, cia_nome');
	 	$sql->adOnde('contato_cia='.(int)$cia_id);
	 	$sql->adOnde('agenda_ativo=1');
	 	} 	 		
	elseif ($agrupamento){
		$sql->adTabela('agrupamento');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = agrupamento_cia');
	 	$sql->adCampo('agrupamento_id AS campo, agrupamento_nome AS nome, cia_nome');
	 	$sql->adOnde('agrupamento_cia='.(int)$cia_id);
	 	$sql->adOnde('agrupamento_ativo=1');
	 	} 
	elseif ($patrocinador){
		$sql->adTabela('patrocinadores');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = patrocinador_cia');
	 	$sql->adCampo('patrocinador_id AS campo, patrocinador_nome AS nome, cia_nome');
	 	$sql->adOnde('patrocinador_cia='.(int)$cia_id);
	 	$sql->adOnde('patrocinador_ativo=1');
	 	} 	 		
	elseif ($template){
		$sql->adTabela('template');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = template_cia');
	 	$sql->adCampo('template_id AS campo, template_nome AS nome, cia_nome');
	 	$sql->adOnde('template_cia='.(int)$cia_id);
	 	$sql->adOnde('template_ativo=1');
	 	}  	 	
	elseif ($painel){
		$sql->adTabela('painel');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = painel_cia');
	 	$sql->adCampo('painel_id AS campo, painel_nome AS nome, cia_nome');
	 	$sql->adOnde('painel_cia='.(int)$cia_id);
	 	$sql->adOnde('painel_ativo=1');
	 	}	 		
	elseif ($painel_odometro){
		$sql->adTabela('painel_odometro');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = painel_odometro_cia');
	 	$sql->adCampo('painel_odometro_id AS campo, painel_odometro_nome AS nome, cia_nome');
	 	$sql->adOnde('painel_odometro_cia='.(int)$cia_id);
	 	$sql->adOnde('painel_odometro_ativo=1');
	 	}  	 	 	
	elseif ($painel_composicao){
		$sql->adTabela('painel_composicao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = painel_composicao_cia');
	 	$sql->adCampo('painel_composicao_id AS campo, painel_composicao_nome AS nome, cia_nome');
	 	$sql->adOnde('painel_composicao_cia='.(int)$cia_id);
	 	$sql->adOnde('painel_composicao_ativo=1');
	 	}
	elseif ($tr){
		$sql->adTabela('tr');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = tr_cia');
	 	$sql->adCampo('tr_id AS campo, tr_nome AS nome, cia_nome');
	 	$sql->adOnde('tr_cia='.(int)$cia_id);
	 	$sql->adOnde('tr_ativo=1');
	 	}
	elseif ($me){
		$sql->adTabela('me');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = me_cia');
	 	$sql->adCampo('me_id AS campo, me_nome AS nome, cia_nome');
	 	$sql->adOnde('me_cia='.(int)$cia_id);
	 	$sql->adOnde('me_ativo=1');
	 	} 	
	elseif ($plano_acao_item){
		$sql->esqUnir('plano_acao_item');
		$sql->esqUnir('cias', 'cias', 'plano_acao_item_cia=cias.cia_id');
	 	$sql->adCampo('plano_acao_item.plano_acao_item_id AS campo, plano_acao_item_nome AS nome, cia_nome');
	 	$sql->adOnde('plano_acao_item_cia='.(int)$cia_id);
	 	$sql->adOnde('plano_acao_item_ativo=1');
	 	}   	
	elseif ($beneficio){
		$sql->esqUnir('beneficio');
		$sql->esqUnir('cias', 'cias', 'beneficio_cia=cias.cia_id');
	 	$sql->adCampo('beneficio.beneficio_id AS campo, beneficio_nome AS nome, cia_nome');
	 	$sql->adOnde('beneficio_cia='.(int)$cia_id);
	 	$sql->adOnde('beneficio_ativo=1');
	 	}   	
	elseif ($painel_slideshow){
		$sql->esqUnir('painel_slideshow');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = painel_slideshow_cia');
	 	$sql->adCampo('painel_slideshow.painel_slideshow_id AS campo, painel_slideshow_nome AS nome, cia_nome');
	 	$sql->adOnde('painel_slideshow_cia='.(int)$cia_id);
	 	$sql->adOnde('painel_slideshow_ativo=1');
	 	}   	
	elseif ($projeto_viabilidade){
		$sql->esqUnir('projeto_viabilidade');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projeto_viabilidade_cia');
	 	$sql->adCampo('projeto_viabilidade.projeto_viabilidade_id AS campo, projeto_viabilidade_nome AS nome, cia_nome');
	 	$sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
	 	$sql->adOnde('projeto_viabilidade_ativo=1');
	 	}  
	elseif ($projeto_abertura){
		$sql->esqUnir('projeto_abertura');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projeto_abertura_cia');
	 	$sql->adCampo('projeto_abertura.projeto_abertura_id AS campo, projeto_abertura_nome AS nome, cia_nome');
	 	$sql->adOnde('projeto_abertura_cia='.(int)$cia_id);
	 	$sql->adOnde('projeto_abertura_ativo=1');
	 	}  
	elseif ($plano_gestao){
		$sql->esqUnir('plano_gestao');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = pg_cia');
	 	$sql->adCampo('plano_gestao.pg_id AS campo, pg_nome AS nome, cia_nome');
	 	$sql->adOnde('pg_cia='.(int)$cia_id);
	 	$sql->adOnde('pg_ativo=1');
	 	}   
	elseif ($ssti){
		$sql->adTabela('ssti');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = ssti_cia');
	 	$sql->adCampo('ssti_id AS campo, ssti_nome AS nome, cia_nome');
	 	$sql->adOnde('ssti_cia='.(int)$cia_id);
	 	$sql->adOnde('ssti_ativo=1');
	 	}	 			
	elseif ($laudo){
		$sql->adTabela('laudo');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = laudo_cia');
	 	$sql->adCampo('laudo_id AS campo, laudo_nome AS nome, cia_nome');
	 	$sql->adOnde('laudo_cia='.(int)$cia_id);
	 	$sql->adOnde('laudo_ativo=1');
	 	}	 			
		
	if ($pesquisa && $projeto) $sql->adOnde('projeto_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $tarefa) $sql->adOnde('tarefa_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $perspectiva) $sql->adOnde('pg_perspectiva_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $tema) $sql->adOnde('tema_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $objetivo) $sql->adOnde('objetivo_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $fator) $sql->adOnde('fator_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $estrategia) $sql->adOnde(' pg_estrategia_nome LIKE \'%'.$pesquisa.'%\'');		
	elseif ($pesquisa && $meta) $sql->adOnde('pg_meta_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $pratica) $sql->adOnde('pratica_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $indicador) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $acao) $sql->adOnde('plano_acao_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $canvas) $sql->adOnde('canvas_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $risco) $sql->adOnde('risco_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $risco_resposta) $sql->adOnde('risco_resposta_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $calendario) $sql->adOnde('calendario_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $monitoramento) $sql->adOnde('monitoramento_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $ata) $sql->adOnde('ata_titulo LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $mswot) $sql->adOnde('mswot_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $swot) $sql->adOnde('swot_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $operativo) $sql->adOnde('operativo_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $instrumento) $sql->adOnde('instrumento_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $recurso) $sql->adOnde('recurso_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $problema) $sql->adOnde('problema_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $demanda) $sql->adOnde('demanda_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $programa) $sql->adOnde('programa_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $licao) $sql->adOnde('licao_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $evento) $sql->adOnde('evento_titulo LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $link) $sql->adOnde('link_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $avaliacao) $sql->adOnde('avaliacao_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $tgn) $sql->adOnde('tgn_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $brainstorm) $sql->adOnde('brainstorm_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $gut) $sql->adOnde('gut_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $causa_efeito) $sql->adOnde('causa_efeito_nome LIKE \'%'.$pesquisa.'%\'');	
 	elseif ($pesquisa && $arquivo) $sql->adOnde('arquivo_nome LIKE \'%'.$pesquisa.'%\'');		
	elseif ($pesquisa && $forum) $sql->adOnde('forum_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $checklist) $sql->adOnde('checklist_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $agenda) $sql->adOnde('agenda_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $agrupamento) $sql->adOnde('agrupamento_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $patrocinador) $sql->adOnde('patrocinador_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $template) $sql->adOnde('template_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $painel) $sql->adOnde('painel_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $painel_odometro) $sql->adOnde('painel_odometro_nome LIKE \'%'.$pesquisa.'%\'');	 	
	elseif ($pesquisa && $painel_composicao) $sql->adOnde('painel_composicao_nome LIKE \'%'.$pesquisa.'%\'');
	elseif ($pesquisa && $tr) $sql->adOnde('tr_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $me) $sql->adOnde('me_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $plano_acao_item) $sql->adOnde('plano_acao_item_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $beneficio) $sql->adOnde('beneficio_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $painel_slideshow) $sql->adOnde('painel_slideshow_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $projeto_viabilidade)	$sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $plano_gestao)	$sql->adOnde('pg_nome LIKE \'%'.$pesquisa.'%\'');	
	
	elseif ($pesquisa && $ssti)	$sql->adOnde('ssti_nome LIKE \'%'.$pesquisa.'%\'');	
	elseif ($pesquisa && $laudo)	$sql->adOnde('laudo_nome LIKE \'%'.$pesquisa.'%\'');		
		
	$sql->adOrdem('nome ASC');
	$lista=$sql->Lista();
	$sql->limpar();
	$campos_dispiniveis=array();
	foreach($lista as $linha) $campos_dispiniveis[$linha['campo']]=utf8_encode($linha['nome'].($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: ''));
	$saida=selecionaVetor($campos_dispiniveis, $campo, $script);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_disponiveis");	

	
$xajax->processRequest();
?>