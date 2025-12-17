<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once (BASE_DIR.'/modulos/projetos/termo_abertura.class.php');

$sql = new BDConsulta;

$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));
$nao_aprovar = intval(getParam($_REQUEST, 'nao_aprovar', 0));
$projeto_abertura_id=getParam($_REQUEST, 'projeto_abertura_id', null);
$Aplic->setMsg('Termo de Abertura');

$obj = new CTermoAbertura();


if ($nao_aprovar && $projeto_abertura_id) {
	$sql->adTabela('projeto_abertura');
	$sql->adAtualizar('projeto_abertura_aprovado', -1);
	$sql->adAtualizar('projeto_abertura_recusa', getParam($_REQUEST, 'projeto_abertura_recusa', ''));
	$sql->adAtualizar('projeto_abertura_data', date('Y-m-d H:i:s'));
	$sql->adOnde('projeto_abertura_id='.(int)$projeto_abertura_id);
	$sql->exec();
	$sql->limpar();
	
	$Aplic->setMsg('não aprovado', UI_MSG_ALERTA, true);
	$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id);
	exit();	
	}


if ($aprovar && $projeto_abertura_id) {
	$obj->load($projeto_abertura_id);

	$sql = new BDConsulta;
	
	$sql->adTabela('projetos');
	$sql->adInserir('projeto_acesso', $obj->projeto_abertura_acesso);
	if ($obj->projeto_abertura_cia) $sql->adInserir('projeto_cia', $obj->projeto_abertura_cia);
	if ($obj->projeto_abertura_nome) $sql->adInserir('projeto_nome', $obj->projeto_abertura_nome);
	if ($obj->projeto_abertura_codigo) $sql->adInserir('projeto_codigo', $obj->projeto_abertura_codigo);
	if ($obj->projeto_abertura_setor) $sql->adInserir('projeto_setor', $obj->projeto_abertura_setor);
	if ($obj->projeto_abertura_segmento) $sql->adInserir('projeto_segmento', $obj->projeto_abertura_segmento);
	if ($obj->projeto_abertura_intervencao) $sql->adInserir('projeto_intervencao', $obj->projeto_abertura_intervencao);
	if ($obj->projeto_abertura_tipo_intervencao) $sql->adInserir('projeto_tipo_intervencao', $obj->projeto_abertura_tipo_intervencao);
	if ($obj->projeto_abertura_ano) $sql->adInserir('projeto_ano', $obj->projeto_abertura_ano);
	if ($obj->projeto_abertura_gerente_projeto) $sql->adInserir('projeto_responsavel', $obj->projeto_abertura_gerente_projeto);
	if ($obj->projeto_abertura_autoridade) $sql->adInserir('projeto_autoridade', $obj->projeto_abertura_autoridade);
	if ($obj->projeto_abertura_cia) $sql->adInserir('projeto_criador', $obj->projeto_abertura_autoridade);
	if ($obj->projeto_abertura_cor) $sql->adInserir('projeto_cor', $obj->projeto_abertura_cor);
	$sql->adInserir('projeto_data_inicio',  date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_data_fim',  date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_fim_atualizado',  date('Y-m-d H:i:s'));
	$sql->adInserir('projeto_status', 1);
	if ($obj->projeto_abertura_justificativa) $sql->adInserir('projeto_justificativa', $obj->projeto_abertura_justificativa);
	if ($obj->projeto_abertura_objetivo) $sql->adInserir('projeto_objetivo', $obj->projeto_abertura_objetivo);
	if ($obj->projeto_abertura_escopo) $sql->adInserir('projeto_escopo', $obj->projeto_abertura_escopo);
	if ($obj->projeto_abertura_nao_escopo) $sql->adInserir('projeto_nao_escopo', $obj->projeto_abertura_nao_escopo);
	if ($obj->projeto_abertura_premissas) $sql->adInserir('projeto_premissas', $obj->projeto_abertura_premissas);
	if ($obj->projeto_abertura_restricoes) $sql->adInserir('projeto_restricoes', $obj->projeto_abertura_restricoes);
	if ($obj->projeto_abertura_custo) $sql->adInserir('projeto_orcamento', $obj->projeto_abertura_custo);
	if ($obj->projeto_abertura_requisito) $sql->adInserir('projeto_requisito', $obj->projeto_abertura_requisito);
	$sql->exec();
	$projeto_id = $bd->Insert_ID('projetos','projeto_id');
	$sql->limpar();

	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_superior', (int)$projeto_id);
	$sql->adAtualizar('projeto_superior_original', (int)$projeto_id);
	$sql->adOnde('projeto_id='.(int)$projeto_id);
	$sql->exec();
	$sql->limpar();

	

	

	
	if ($Aplic->profissional){
		
		
		
		
		
		$sql->adTabela('projeto_abertura_cia');
		$sql->adCampo('projeto_abertura_cia_cia');
		$sql->adOnde('projeto_abertura_cia_projeto_abertura = '.(int)$obj->projeto_abertura_id);
		$lista = $sql->carregarColuna();
		$sql->limpar();
		foreach ($lista as $linha){
			$sql->adTabela('projeto_cia');
			$sql->adInserir('projeto_cia_projeto', $projeto_id);
			$sql->adInserir('projeto_cia_cia', $linha);	
			$sql->exec();
			$sql->limpar();
			}
		
		$sql->adTabela('projeto_abertura_dept');
		$sql->adCampo('projeto_abertura_dept_dept');
		$sql->adOnde('projeto_abertura_dept_projeto_abertura = '.(int)$obj->projeto_abertura_id);
		$lista = $sql->carregarColuna();
		$sql->limpar();
		foreach ($lista as $linha){
			$sql->adTabela('projeto_depts');
			$sql->adInserir('projeto_id', $projeto_id);
			$sql->adInserir('departamento_id', $linha);	
			$sql->exec();
			$sql->limpar();
			}
		
		

		$sql->adTabela('projeto_abertura_custo');
		$sql->adCampo('projeto_abertura_custo.*');
		$sql->adOnde('projeto_abertura_custo_projeto_abertura = '.(int)$obj->projeto_abertura_id);
		$lista = $sql->lista();
		$sql->limpar();
		
		foreach ($lista as $linha){
			$sql->adTabela('projeto_custo');
			$sql->adInserir('projeto_custo_projeto', $projeto_id);
			$sql->adInserir('projeto_custo_nome', $linha['projeto_abertura_custo_nome']);	
			$sql->adInserir('projeto_custo_tipo', $linha['projeto_abertura_custo_tipo']);
			$sql->adInserir('projeto_custo_quantidade', $linha['projeto_abertura_custo_quantidade']);
			$sql->adInserir('projeto_custo_custo', $linha['projeto_abertura_custo_custo']);
			$sql->adInserir('projeto_custo_descricao', $linha['projeto_abertura_custo_descricao']);
			$sql->adInserir('projeto_custo_nd', $linha['projeto_abertura_custo_nd']);
			$sql->adInserir('projeto_custo_categoria_economica', $linha['projeto_abertura_custo_categoria_economica']);
			$sql->adInserir('projeto_custo_grupo_despesa', $linha['projeto_abertura_custo_grupo_despesa']);
			$sql->adInserir('projeto_custo_modalidade_aplicacao', $linha['projeto_abertura_custo_modalidade_aplicacao']);
			$sql->adInserir('projeto_custo_data_limite', $linha['projeto_abertura_custo_data_limite']);
			$sql->adInserir('projeto_custo_usuario', $linha['projeto_abertura_custo_usuario']);
			$sql->adInserir('projeto_custo_data', date('Y-m-d H:i:s'));
			$sql->adInserir('projeto_custo_bdi', $linha['projeto_abertura_custo_bdi']);
			$sql->adInserir('projeto_custo_moeda', $linha['projeto_abertura_custo_moeda']);
			$sql->adInserir('projeto_custo_data_moeda', $linha['projeto_abertura_custo_data_moeda']);
			$sql->adInserir('projeto_custo_cotacao', $linha['projeto_abertura_custo_cotacao']);
			$sql->adInserir('projeto_custo_codigo', $linha['projeto_abertura_custo_codigo']);
			$sql->adInserir('projeto_custo_fonte', $linha['projeto_abertura_custo_fonte']);
			$sql->adInserir('projeto_custo_regiao', $linha['projeto_abertura_custo_regiao']);
			$sql->adInserir('projeto_custo_ordem', $linha['projeto_abertura_custo_ordem']);
			$sql->exec();
			$sql->limpar();
			}

		//se for um portifilio de demandas colocar nos filhos
		
		$sql->adTabela('demandas');
		$sql->adCampo('demanda_id');
		$sql->adOnde('demanda_termo_abertura='.(int)$projeto_abertura_id);
		$demanda_pai = $sql->resultado();
		$sql->limpar();

		$sql->adTabela('demanda_portfolio');
		$sql->adCampo('demanda_portfolio_filho');
		$sql->adOnde('demanda_portfolio_pai='.(int)$demanda_pai);
		$demandas_filhas = $sql->carregarColuna();
		$sql->limpar();
		
		foreach($demandas_filhas as $demanda_filha){
			$sql->adTabela('demandas');
			$sql->adAtualizar('demanda_projeto', (int)$projeto_id);
			$sql->adOnde('demanda_id='.(int)$demanda_filha);
			$sql->adOnde('demanda_projeto IS NULL');
			$sql->exec();
			$sql->limpar();
			}
		
		
		$sql->adTabela('projeto_abertura_gestao');
		$sql->adCampo('projeto_abertura_gestao.*');
		$sql->adOnde('projeto_abertura_gestao_projeto_abertura ='.(int)$projeto_abertura_id);	
		$sql->adOrdem('projeto_abertura_gestao_ordem');
		$lista_gestao = $sql->Lista();
		$sql->limpar();
		
		foreach($lista_gestao as $gestao){
			$sql->adTabela('projeto_gestao');
			$sql->adInserir('projeto_gestao_projeto',  $projeto_id);
			
			if ($gestao['projeto_abertura_gestao_projeto']) $sql->adInserir('projeto_gestao_semelhante', $gestao['projeto_abertura_gestao_projeto']);
			
			elseif ($gestao['projeto_abertura_gestao_tarefa']) $sql->adInserir('projeto_gestao_tarefa', $gestao['projeto_abertura_gestao_tarefa']);
			elseif ($gestao['projeto_abertura_gestao_perspectiva']) $sql->adInserir('projeto_gestao_perspectiva', $gestao['projeto_abertura_gestao_perspectiva']);
			elseif ($gestao['projeto_abertura_gestao_tema']) $sql->adInserir('projeto_gestao_tema', $gestao['projeto_abertura_gestao_tema']);
			elseif ($gestao['projeto_abertura_gestao_objetivo']) $sql->adInserir('projeto_gestao_objetivo', $gestao['projeto_abertura_gestao_objetivo']);
			elseif ($gestao['projeto_abertura_gestao_fator']) $sql->adInserir('projeto_gestao_fator', $gestao['projeto_abertura_gestao_fator']);
			elseif ($gestao['projeto_abertura_gestao_estrategia']) $sql->adInserir('projeto_gestao_estrategia', $gestao['projeto_abertura_gestao_estrategia']);
			elseif ($gestao['projeto_abertura_gestao_meta']) $sql->adInserir('projeto_gestao_meta', $gestao['projeto_abertura_gestao_meta']);
			elseif ($gestao['projeto_abertura_gestao_pratica']) $sql->adInserir('projeto_gestao_pratica', $gestao['projeto_abertura_gestao_pratica']);
			elseif ($gestao['projeto_abertura_gestao_indicador']) $sql->adInserir('projeto_gestao_indicador', $gestao['projeto_abertura_gestao_indicador']);
			elseif ($gestao['projeto_abertura_gestao_acao']) $sql->adInserir('projeto_gestao_acao', $gestao['projeto_abertura_gestao_acao']);
			elseif ($gestao['projeto_abertura_gestao_canvas']) $sql->adInserir('projeto_gestao_canvas', $gestao['projeto_abertura_gestao_canvas']);
			elseif ($gestao['projeto_abertura_gestao_risco']) $sql->adInserir('projeto_gestao_risco', $gestao['projeto_abertura_gestao_risco']);
			elseif ($gestao['projeto_abertura_gestao_risco_resposta']) $sql->adInserir('projeto_gestao_risco_resposta', $gestao['projeto_abertura_gestao_risco_resposta']);
			elseif ($gestao['projeto_abertura_gestao_calendario']) $sql->adInserir('projeto_gestao_calendario', $gestao['projeto_abertura_gestao_calendario']);
			elseif ($gestao['projeto_abertura_gestao_monitoramento']) $sql->adInserir('projeto_gestao_monitoramento', $gestao['projeto_abertura_gestao_monitoramento']);
			elseif ($gestao['projeto_abertura_gestao_ata']) $sql->adInserir('projeto_gestao_ata', $gestao['projeto_abertura_gestao_ata']);
			elseif ($gestao['projeto_abertura_gestao_mswot']) $sql->adInserir('projeto_gestao_mswot', $gestao['projeto_abertura_gestao_mswot']);
			elseif ($gestao['projeto_abertura_gestao_swot']) $sql->adInserir('projeto_gestao_swot', $gestao['projeto_abertura_gestao_swot']);
			elseif ($gestao['projeto_abertura_gestao_operativo']) $sql->adInserir('projeto_gestao_operativo', $gestao['projeto_abertura_gestao_operativo']);
			elseif ($gestao['projeto_abertura_gestao_instrumento']) $sql->adInserir('projeto_gestao_instrumento', $gestao['projeto_abertura_gestao_instrumento']);
			elseif ($gestao['projeto_abertura_gestao_recurso']) $sql->adInserir('projeto_gestao_recurso', $gestao['projeto_abertura_gestao_recurso']);
			elseif ($gestao['projeto_abertura_gestao_problema']) $sql->adInserir('projeto_gestao_problema', $gestao['projeto_abertura_gestao_problema']);
			elseif ($gestao['projeto_abertura_gestao_demanda']) $sql->adInserir('projeto_gestao_demanda', $gestao['projeto_abertura_gestao_demanda']);
			elseif ($gestao['projeto_abertura_gestao_programa']) $sql->adInserir('projeto_gestao_programa', $gestao['projeto_abertura_gestao_programa']);
			elseif ($gestao['projeto_abertura_gestao_licao']) $sql->adInserir('projeto_gestao_licao', $gestao['projeto_abertura_gestao_licao']);
			elseif ($gestao['projeto_abertura_gestao_evento']) $sql->adInserir('projeto_gestao_evento', $gestao['projeto_abertura_gestao_evento']);
			elseif ($gestao['projeto_abertura_gestao_link']) $sql->adInserir('projeto_gestao_link', $gestao['projeto_abertura_gestao_link']);
			elseif ($gestao['projeto_abertura_gestao_avaliacao']) $sql->adInserir('projeto_gestao_avaliacao', $gestao['projeto_abertura_gestao_avaliacao']);
			elseif ($gestao['projeto_abertura_gestao_tgn']) $sql->adInserir('projeto_gestao_tgn', $gestao['projeto_abertura_gestao_tgn']);
			elseif ($gestao['projeto_abertura_gestao_brainstorm']) $sql->adInserir('projeto_gestao_brainstorm', $gestao['projeto_abertura_gestao_brainstorm']);
			elseif ($gestao['projeto_abertura_gestao_gut']) $sql->adInserir('projeto_gestao_gut', $gestao['projeto_abertura_gestao_gut']);
			elseif ($gestao['projeto_abertura_gestao_causa_efeito']) $sql->adInserir('projeto_gestao_causa_efeito', $gestao['projeto_abertura_gestao_causa_efeito']);
			elseif ($gestao['projeto_abertura_gestao_arquivo']) $sql->adInserir('projeto_gestao_arquivo', $gestao['projeto_abertura_gestao_arquivo']);
			elseif ($gestao['projeto_abertura_gestao_forum']) $sql->adInserir('projeto_gestao_forum', $gestao['projeto_abertura_gestao_forum']);
			elseif ($gestao['projeto_abertura_gestao_checklist']) $sql->adInserir('projeto_gestao_checklist', $gestao['projeto_abertura_gestao_checklist']);
			elseif ($gestao['projeto_abertura_gestao_agenda']) $sql->adInserir('projeto_gestao_agenda', $gestao['projeto_abertura_gestao_agenda']);
			elseif ($gestao['projeto_abertura_gestao_agrupamento']) $sql->adInserir('projeto_gestao_agrupamento', $gestao['projeto_abertura_gestao_agrupamento']);
			elseif ($gestao['projeto_abertura_gestao_patrocinador']) $sql->adInserir('projeto_gestao_patrocinador', $gestao['projeto_abertura_gestao_patrocinador']);
			elseif ($gestao['projeto_abertura_gestao_template']) $sql->adInserir('projeto_gestao_template', $gestao['projeto_abertura_gestao_template']);
			elseif ($gestao['projeto_abertura_gestao_painel']) $sql->adInserir('projeto_gestao_painel', $gestao['projeto_abertura_gestao_painel']);
			elseif ($gestao['projeto_abertura_gestao_painel_odometro']) $sql->adInserir('projeto_gestao_painel_odometro', $gestao['projeto_abertura_gestao_painel_odometro']);
			elseif ($gestao['projeto_abertura_gestao_painel_composicao']) $sql->adInserir('projeto_gestao_painel_composicao', $gestao['projeto_abertura_gestao_painel_composicao']);
			elseif ($gestao['projeto_abertura_gestao_tr']) $sql->adInserir('projeto_gestao_tr', $gestao['projeto_abertura_gestao_tr']);
			elseif ($gestao['projeto_abertura_gestao_me']) $sql->adInserir('projeto_gestao_me', $gestao['projeto_abertura_gestao_me']);
			elseif ($gestao['projeto_abertura_gestao_acao_item']) $sql->adInserir('projeto_gestao_acao_item', $gestao['projeto_abertura_gestao_acao_item']);
			elseif ($gestao['projeto_abertura_gestao_beneficio']) $sql->adInserir('projeto_gestao_beneficio', $gestao['projeto_abertura_gestao_beneficio']);
			elseif ($gestao['projeto_abertura_gestao_painel_slideshow']) $sql->adInserir('projeto_gestao_painel_slideshow', $gestao['projeto_abertura_gestao_painel_slideshow']);
			elseif ($gestao['projeto_abertura_gestao_projeto_viabilidade']) $sql->adInserir('projeto_gestao_projeto_viabilidade', $gestao['projeto_abertura_gestao_projeto_viabilidade']);
			
			elseif ($gestao['projeto_abertura_gestao_semelhante']) $sql->adInserir('projeto_gestao_projeto_abertura', $gestao['projeto_abertura_gestao_semelhante']);

			elseif ($gestao['projeto_abertura_gestao_plano_gestao']) $sql->adInserir('projeto_gestao_plano_gestao', $gestao['projeto_abertura_gestao_plano_gestao']);
			elseif ($gestao['projeto_abertura_gestao_ssti']) $sql->adInserir('projeto_gestao_ssti', $gestao['projeto_abertura_gestao_ssti']);
			elseif ($gestao['projeto_abertura_gestao_laudo']) $sql->adInserir('projeto_gestao_laudo', $gestao['projeto_abertura_gestao_laudo']);
			elseif ($gestao['projeto_abertura_gestao_trelo']) $sql->adInserir('projeto_gestao_trelo', $gestao['projeto_abertura_gestao_trelo']);
			elseif ($gestao['projeto_abertura_gestao_trelo_cartao']) $sql->adInserir('projeto_gestao_trelo_cartao', $gestao['projeto_abertura_gestao_trelo_cartao']);
			elseif ($gestao['projeto_abertura_gestao_pdcl']) $sql->adInserir('projeto_gestao_pdcl', $gestao['projeto_abertura_gestao_pdcl']);
			elseif ($gestao['projeto_abertura_gestao_pdcl_item']) $sql->adInserir('projeto_gestao_pdcl_item', $gestao['projeto_abertura_gestao_pdcl_item']);
			
			if ($gestao['projeto_abertura_gestao_ordem']) $sql->adInserir('projeto_gestao_ordem', $gestao['projeto_abertura_gestao_ordem']);
	
			$sql->exec();
			$sql->limpar();
			}
		

		$sql->adTabela('priorizacao');
		$sql->esqUnir('priorizacao_modelo', 'priorizacao_modelo', 'priorizacao_modelo=priorizacao_modelo_id');
		$sql->adCampo('priorizacao.*');
		$sql->adOnde('priorizacao_demanda ='.(int)$demanda_pai);	
		$sql->adOnde('priorizacao_modelo_projeto = 1');
		$lista_priorizacao = $sql->Lista();
		$sql->limpar();
		
		foreach($lista_priorizacao as $priorizacao){
			$sql->adTabela('priorizacao');
			$sql->adInserir('priorizacao_projeto',  $projeto_id);
			if ($priorizacao['priorizacao_modelo']) $sql->adInserir('priorizacao_modelo', $priorizacao['priorizacao_modelo']);
			if ($priorizacao['priorizacao_valor']) $sql->adInserir('priorizacao_valor', $priorizacao['priorizacao_valor']);
			$sql->exec();
			$sql->limpar();
			}	
		}
		
	
	$sql->adTabela('projeto_abertura');
	$sql->adAtualizar('projeto_abertura_aprovado', 1);
	$sql->adAtualizar('projeto_abertura_projeto', (int)$projeto_id);
	$sql->adAtualizar('projeto_abertura_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('projeto_abertura_aprovacao', getParam($_REQUEST, 'projeto_abertura_aprovacao', ''));
	$sql->adOnde('projeto_abertura_id='.(int)$projeto_abertura_id);
	$sql->exec();
	$sql->limpar();

	$sql->adTabela('demandas');
	$sql->adAtualizar('demanda_projeto', (int)$projeto_id);
	$sql->adOnde('demanda_termo_abertura='.(int)$projeto_abertura_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->adTabela('projeto_viabilidade');
	$sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura.projeto_abertura_demanda=projeto_viabilidade.projeto_viabilidade_demanda');
	$sql->adCampo('projeto_viabilidade_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$projeto_viabilidade_id = $sql->resultado();
	$sql->limpar();
	
	$sql->adTabela('projeto_viabilidade');
	$sql->adAtualizar('projeto_viabilidade_projeto', (int)$projeto_id);
	$sql->adOnde('projeto_viabilidade_id='.(int)$projeto_viabilidade_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura_usuarios');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=projeto_abertura_usuarios.usuario_id');
	$sql->adCampo('usuario_contato');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_usuarios = $sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura_patrocinadores');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_patrocinadores = $sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('projeto_abertura_interessados');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
	$lista_interessados = $sql->carregarColuna();
	$sql->limpar();
	
	$ordem=0;
	foreach($lista_usuarios as $contato_id){
		$sql->adTabela('projeto_integrantes');
		$sql->adInserir('contato_id',  $contato_id);
		$sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}


	$ordem=0;
	foreach($lista_interessados as $contato_id){
		$sql->adTabela('projeto_contatos');
		$sql->adInserir('contato_id',  $contato_id);
		$sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}

	$ordem=0;
	foreach($lista_patrocinadores as $contato_id){
		$sql->adTabela('projeto_stakeholder');
		$sql->adInserir('projeto_stakeholder_contato',  $contato_id);
		$sql->adInserir('projeto_stakeholder_projeto', $projeto_id);
		$sql->adInserir('projeto_stakeholder_perfil', 2);
		$sql->adInserir('projeto_stakeholder_ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}

	foreach($lista_interessados as $contato_id){
		$sql->adTabela('projeto_stakeholder');
		$sql->adInserir('projeto_stakeholder_contato',  $contato_id);
		$sql->adInserir('projeto_stakeholder_projeto', $projeto_id);
		$sql->adInserir('projeto_stakeholder_perfil', 4);
		$sql->adInserir('projeto_stakeholder_ordem', ++$ordem);
		$sql->exec();
		$sql->limpar();
		}



	$projeto = new CProjeto(false);
	$projeto->load($projeto_id, true);

	$codigo=$projeto->getCodigo();
	if ($codigo) {
		$sql->adTabela('projetos');
		$sql->adAtualizar('projeto_codigo', $codigo);
		$sql->adOnde('projeto_id='.(int)$projeto_id);
		$sql->exec();
		$sql->limpar();
		}
		
	$projeto->setSequencial();

	if ($projeto_id){
		$Aplic->setMsg('aprovado', UI_MSG_OK, true);
		$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.$projeto_id);
		}
	else{
		$Aplic->setMsg('Houve erro ao tentar criar nov'.$config['genero_projeo'].' '.$config['projeo'], UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id);
		}	
		
	exit();	
	}


if ($excluir) {
	$obj->load($projeto_abertura_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=viabilidade_lista&tab=0');
		}
	exit();	
	}


if ($projeto_abertura_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=viabilidade_lista&tab=0');
	}

$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_abertura_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_abertura_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
	
$obj->setSequencial();	


if ($Aplic->profissional && !$projeto_abertura_id){

	$sql->adTabela('projeto_viabilidade');
	$sql->adCampo('projeto_viabilidade_id');
	$sql->adOnde('projeto_viabilidade_demanda = '.(int)$obj->projeto_abertura_demanda);
	$projeto_viabilidade_id = $sql->resultado();
	$sql->limpar();
	
	
	$sql->adTabela('projeto_viabilidade_custo');
	$sql->adCampo('projeto_viabilidade_custo.*');
	$sql->adOnde('projeto_viabilidade_custo_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
	
	$lista = $sql->lista();
	$sql->limpar();
	
	foreach ($lista as $linha){
		$sql->adTabela('projeto_abertura_custo');
		$sql->adInserir('projeto_abertura_custo_projeto_abertura', $obj->projeto_abertura_id);
		$sql->adInserir('projeto_abertura_custo_nome', $linha['projeto_viabilidade_custo_nome']);	
		$sql->adInserir('projeto_abertura_custo_tipo', $linha['projeto_viabilidade_custo_tipo']);
		$sql->adInserir('projeto_abertura_custo_quantidade', $linha['projeto_viabilidade_custo_quantidade']);
		$sql->adInserir('projeto_abertura_custo_custo', $linha['projeto_viabilidade_custo_custo']);
		$sql->adInserir('projeto_abertura_custo_descricao', $linha['projeto_viabilidade_custo_descricao']);
		$sql->adInserir('projeto_abertura_custo_nd', $linha['projeto_viabilidade_custo_nd']);
		$sql->adInserir('projeto_abertura_custo_categoria_economica', $linha['projeto_viabilidade_custo_categoria_economica']);
		$sql->adInserir('projeto_abertura_custo_grupo_despesa', $linha['projeto_viabilidade_custo_grupo_despesa']);
		$sql->adInserir('projeto_abertura_custo_modalidade_aplicacao', $linha['projeto_viabilidade_custo_modalidade_aplicacao']);
		$sql->adInserir('projeto_abertura_custo_data_limite', $linha['projeto_viabilidade_custo_data_limite']);
		$sql->adInserir('projeto_abertura_custo_usuario', $linha['projeto_viabilidade_custo_usuario']);
		$sql->adInserir('projeto_abertura_custo_data', date('Y-m-d H:i:s'));
		$sql->adInserir('projeto_abertura_custo_bdi', $linha['projeto_viabilidade_custo_bdi']);
		$sql->adInserir('projeto_abertura_custo_moeda', $linha['projeto_viabilidade_custo_moeda']);
		$sql->adInserir('projeto_abertura_custo_data_moeda', $linha['projeto_viabilidade_custo_data_moeda']);
		$sql->adInserir('projeto_abertura_custo_cotacao', $linha['projeto_viabilidade_custo_cotacao']);
		$sql->adInserir('projeto_abertura_custo_codigo', $linha['projeto_viabilidade_custo_codigo']);
		$sql->adInserir('projeto_abertura_custo_fonte', $linha['projeto_viabilidade_custo_fonte']);
		$sql->adInserir('projeto_abertura_custo_regiao', $linha['projeto_viabilidade_custo_regiao']);
		$sql->adInserir('projeto_abertura_custo_ordem', $linha['projeto_viabilidade_custo_ordem']);
		$sql->exec();
		$sql->limpar();
		}
	}	


$sql->adTabela('projeto_abertura_gestao');
$sql->adCampo('projeto_abertura_gestao.*');
$sql->adOnde('projeto_abertura_gestao_projeto_abertura='.(int)$obj->projeto_abertura_id);
$sql->adOrdem('projeto_abertura_gestao_ordem ASC');
$linha=$sql->linha();
$sql->limpar();

$sql->adTabela('projeto_abertura_gestao');
$sql->adCampo('count(projeto_abertura_gestao_id)');
$sql->adOnde('projeto_abertura_gestao_projeto_abertura='.(int)$obj->projeto_abertura_id);
$qnt=$sql->Resultado();
$sql->limpar();

if ($linha['projeto_abertura_gestao_tarefa'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['projeto_abertura_gestao_tarefa'];
elseif ($linha['projeto_abertura_gestao_projeto'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['projeto_abertura_gestao_projeto'];
elseif ($linha['projeto_abertura_gestao_perspectiva'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['projeto_abertura_gestao_perspectiva'];
elseif ($linha['projeto_abertura_gestao_tema'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['projeto_abertura_gestao_tema'];
elseif ($linha['projeto_abertura_gestao_objetivo'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['projeto_abertura_gestao_objetivo'];
elseif ($linha['projeto_abertura_gestao_fator'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['projeto_abertura_gestao_fator'];
elseif ($linha['projeto_abertura_gestao_estrategia'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['projeto_abertura_gestao_estrategia'];
elseif ($linha['projeto_abertura_gestao_meta'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['projeto_abertura_gestao_meta'];
elseif ($linha['projeto_abertura_gestao_pratica'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['projeto_abertura_gestao_pratica'];
elseif ($linha['projeto_abertura_gestao_indicador'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['projeto_abertura_gestao_indicador'];
elseif ($linha['projeto_abertura_gestao_acao'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['projeto_abertura_gestao_acao'];
elseif ($linha['projeto_abertura_gestao_canvas'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['projeto_abertura_gestao_canvas'];
elseif ($linha['projeto_abertura_gestao_risco'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['projeto_abertura_gestao_risco'];
elseif ($linha['projeto_abertura_gestao_risco_resposta'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['projeto_abertura_gestao_risco_resposta'];
elseif ($linha['projeto_abertura_gestao_calendario'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['projeto_abertura_gestao_calendario'];
elseif ($linha['projeto_abertura_gestao_monitoramento'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['projeto_abertura_gestao_monitoramento'];
elseif ($linha['projeto_abertura_gestao_ata'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['projeto_abertura_gestao_ata'];
elseif ($linha['projeto_abertura_gestao_mswot'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['projeto_abertura_gestao_mswot'];
elseif ($linha['projeto_abertura_gestao_swot'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['projeto_abertura_gestao_swot'];
elseif ($linha['projeto_abertura_gestao_operativo'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['projeto_abertura_gestao_operativo'];
elseif ($linha['projeto_abertura_gestao_instrumento'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['projeto_abertura_gestao_instrumento'];
elseif ($linha['projeto_abertura_gestao_recurso'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['projeto_abertura_gestao_recurso'];
elseif ($linha['projeto_abertura_gestao_problema'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['projeto_abertura_gestao_problema'];
elseif ($linha['projeto_abertura_gestao_demanda'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['projeto_abertura_gestao_demanda'];
elseif ($linha['projeto_abertura_gestao_programa'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['projeto_abertura_gestao_programa'];
elseif ($linha['projeto_abertura_gestao_licao'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['projeto_abertura_gestao_licao'];
elseif ($linha['projeto_abertura_gestao_evento'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=calendario&a=ver&evento_id='.$linha['projeto_abertura_gestao_evento'];
elseif ($linha['projeto_abertura_gestao_link'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=links&a=ver&link_id='.$linha['projeto_abertura_gestao_link'];
elseif ($linha['projeto_abertura_gestao_avaliacao'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['projeto_abertura_gestao_avaliacao'];
elseif ($linha['projeto_abertura_gestao_tgn'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['projeto_abertura_gestao_tgn'];
elseif ($linha['projeto_abertura_gestao_brainstorm'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['projeto_abertura_gestao_brainstorm'];
elseif ($linha['projeto_abertura_gestao_gut'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['projeto_abertura_gestao_gut'];
elseif ($linha['projeto_abertura_gestao_causa_efeito'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['projeto_abertura_gestao_causa_efeito'];
elseif ($linha['projeto_abertura_gestao_arquivo'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['projeto_abertura_gestao_arquivo'];
elseif ($linha['projeto_abertura_gestao_forum'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=foruns&a=ver&forum_id='.$linha['projeto_abertura_gestao_forum'];
elseif ($linha['projeto_abertura_gestao_checklist'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['projeto_abertura_gestao_checklist'];
elseif ($linha['projeto_abertura_gestao_agenda'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['projeto_abertura_gestao_agenda'];
elseif ($linha['projeto_abertura_gestao_agrupamento'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['projeto_abertura_gestao_agrupamento'];
elseif ($linha['projeto_abertura_gestao_patrocinador'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['projeto_abertura_gestao_patrocinador'];
elseif ($linha['projeto_abertura_gestao_template'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['projeto_abertura_gestao_template'];
elseif ($linha['projeto_abertura_gestao_painel'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['projeto_abertura_gestao_painel'];
elseif ($linha['projeto_abertura_gestao_painel_odometro'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['projeto_abertura_gestao_painel_odometro'];
elseif ($linha['projeto_abertura_gestao_painel_composicao'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['projeto_abertura_gestao_painel_composicao'];
elseif ($linha['projeto_abertura_gestao_tr'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['projeto_abertura_gestao_tr'];
elseif ($linha['projeto_abertura_gestao_me'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['projeto_abertura_gestao_me'];
elseif ($linha['projeto_abertura_gestao_acao_item'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['projeto_abertura_gestao_acao_item'];
elseif ($linha['projeto_abertura_gestao_beneficio'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['projeto_abertura_gestao_beneficio'];
elseif ($linha['projeto_abertura_gestao_painel_slideshow'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['projeto_abertura_gestao_painel_slideshow'];
elseif ($linha['projeto_abertura_gestao_projeto_viabilidade'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['projeto_abertura_gestao_projeto_viabilidade'];

elseif ($linha['projeto_abertura_gestao_semelhante'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['projeto_abertura_gestao_semelhante'];

elseif ($linha['projeto_abertura_gestao_plano_gestao'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['projeto_abertura_gestao_plano_gestao'];
elseif ($linha['projeto_abertura_gestao_ssti'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['projeto_abertura_gestao_ssti'];
elseif ($linha['projeto_abertura_gestao_laudo'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['projeto_abertura_gestao_laudo'];
elseif ($linha['projeto_abertura_gestao_trelo'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['projeto_abertura_gestao_trelo'];
elseif ($linha['projeto_abertura_gestao_trelo_cartao'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['projeto_abertura_gestao_trelo_cartao'];
elseif ($linha['projeto_abertura_gestao_pdcl'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['projeto_abertura_gestao_pdcl'];
elseif ($linha['projeto_abertura_gestao_pdcl_item'] && $qnt==1 && !$projeto_abertura_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['projeto_abertura_gestao_pdcl_item'];
else $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.(int)$obj->projeto_abertura_id;
$Aplic->redirecionar($endereco);	
?>