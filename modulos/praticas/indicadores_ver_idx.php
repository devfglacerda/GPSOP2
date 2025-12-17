<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
if (!isset($indicadores)) global $indicadores, $ordenar, $ordem;
global $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $perms, $Aplic, $cia_id, $tab, $praticas_criterios, $dialogo, $indicador_expandido, $ano, $filtro_prioridade_indicador,
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id;

$vetor_selecionado=explode(',', $selecionado);
$selecionado=array();
foreach($vetor_selecionado as $valor) $selecionado[$valor]=$valor;

if ($tarefa_id) $extra='&tarefa_id='.(int)$tarefa_id;
elseif ($projeto_id) $extra='&projeto_id='.(int)$projeto_id;
elseif ($pg_perspectiva_id) $extra='&pg_perspectiva_id='.(int)$pg_perspectiva_id;
elseif ($tema_id) $extra='&tema_id='.(int)$tema_id;
elseif ($objetivo_id) $extra='&objetivo_id='.(int)$objetivo_id;
elseif ($fator_id) $extra='&fator_id='.(int)$fator_id;
elseif ($pg_estrategia_id) $extra='&pg_estrategia_id='.(int)$pg_estrategia_id;
elseif ($pg_meta_id) $extra='&pg_meta_id='.(int)$pg_meta_id;
elseif ($pratica_id) $extra='&pratica_id='.(int)$pratica_id;
elseif ($pratica_indicador_id) $extra='&pratica_indicador_id='.(int)$pratica_indicador_id;
elseif ($plano_acao_id) $extra='&plano_acao_id='.(int)$plano_acao_id;
elseif ($canvas_id) $extra='&canvas_id='.(int)$canvas_id;
elseif ($risco_id) $extra='&risco_id='.(int)$risco_id;
elseif ($risco_resposta_id) $extra='&risco_resposta_id='.(int)$risco_resposta_id;
elseif ($calendario_id) $extra='&calendario_id='.(int)$calendario_id;
elseif ($monitoramento_id) $extra='&monitoramento_id='.(int)$monitoramento_id;
elseif ($ata_id) $extra='&ata_id='.(int)$ata_id;
elseif ($mswot_id) $extra='&mswot_id='.(int)$mswot_id;
elseif ($swot_id) $extra='&swot_id='.(int)$swot_id;
elseif ($operativo_id) $extra='&operativo_id='.(int)$operativo_id;
elseif ($instrumento_id) $extra='&instrumento_id='.(int)$instrumento_id;
elseif ($recurso_id) $extra='&recurso_id='.(int)$recurso_id;
elseif ($problema_id) $extra='&problema_id='.(int)$problema_id;
elseif ($demanda_id) $extra='&demanda_id='.(int)$demanda_id;
elseif ($programa_id) $extra='&programa_id='.(int)$programa_id;
elseif ($licao_id) $extra='&licao_id='.(int)$licao_id;
elseif ($evento_id) $extra='&evento_id='.(int)$evento_id;
elseif ($link_id) $extra='&link_id='.(int)$link_id;
elseif ($avaliacao_id) $extra='&avaliacao_id='.(int)$avaliacao_id;
elseif ($tgn_id) $extra='&tgn_id='.(int)$tgn_id;
elseif ($brainstorm_id) $extra='&brainstorm_id='.(int)$brainstorm_id;
elseif ($gut_id) $extra='&gut_id='.(int)$gut_id;
elseif ($causa_efeito_id) $extra='&causa_efeito_id='.(int)$causa_efeito_id;
elseif ($arquivo_id) $extra='&arquivo_id='.(int)$arquivo_id;
elseif ($forum_id) $extra='&forum_id='.(int)$forum_id;
elseif ($checklist_id) $extra='&checklist_id='.(int)$checklist_id;
elseif ($agenda_id) $extra='&agenda_id='.(int)$agenda_id;
elseif ($agrupamento_id) $extra='&agrupamento_id='.(int)$agrupamento_id;
elseif ($patrocinador_id) $extra='&patrocinador_id='.(int)$patrocinador_id;
elseif ($template_id) $extra='&template_id='.(int)$template_id;
elseif ($painel_id) $extra='&painel_id='.(int)$painel_id;
elseif ($painel_odometro_id) $extra='&painel_odometro_id='.(int)$painel_odometro_id;
elseif ($painel_composicao_id) $extra='&painel_composicao_id='.(int)$painel_composicao_id;
elseif ($tr_id) $extra='&tr_id='.(int)$tr_id;
elseif ($me_id) $extra='&me_id='.(int)$me_id;
elseif ($plano_acao_item_id) $extra='&plano_acao_item_id='.(int)$plano_acao_item_id;
elseif ($beneficio_id) $extra='&beneficio_id='.(int)$beneficio_id;
elseif ($painel_slideshow_id) $extra='&painel_slideshow_id='.(int)$painel_slideshow_id;
elseif ($projeto_viabilidade_id) $extra='&projeto_viabilidade_id='.(int)$projeto_viabilidade_id;
elseif ($projeto_abertura_id) $extra='&projeto_abertura_id='.(int)$projeto_abertura_id;
elseif ($pg_id) $extra='&pg_id='.(int)$pg_id;
elseif ($ssti_id) $extra='&ssti_id='.(int)$ssti_id;
elseif ($laudo_id) $extra='&laudo_id='.(int)$laudo_id;
elseif ($trelo_id) $extra='&trelo_id='.(int)$trelo_id;
elseif ($trelo_cartao_id) $extra='&trelo_cartao_id='.(int)$trelo_cartao_id;
elseif ($pdcl_id) $extra='&pdcl_id='.(int)$pdcl_id;
elseif ($pdcl_item_id) $extra='&pdcl_item_id='.(int)$pdcl_item_id;
else $extra='';



$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano', 'nenhum' => 'Nenhum agrupamento');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$tab = $Aplic->getEstado('PraticaIdxTab') !== null ? $Aplic->getEstado('PraticaIdxTab') : 0;
$pagina=getParam($_REQUEST, 'pagina', 1);
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$tipo_acumulacao=array('media_simples' => 'Média', 'soma' => 'Soma', 'saldo' => 'Último Valor');

$ordenar=getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');


$sql = new BDConsulta;
$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'indicadores\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'indicadores\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}

$xpg_tamanhoPagina = (!$selecao && ($impressao || $dialogo || $m=='projetos') ? 10000 : $config['qnt_indicadores']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 



$xpg_totalregistros = ($indicadores ? count($indicadores) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'indicador', 'indicadores','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if ($exibir['pratica_indicador_cor']) echo '<th width='.($ordenar=='pratica_indicador_cor' ? '32' : '16').' style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_cor&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Cor', 'Neste campo fica a cor de identificação do indicador.').($ordenar=='pratica_indicador_cor' ? imagem('icones/'.$seta[$ordem]) : '').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_nome&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Nome', 'Neste campo fica o nome para identificação do indicador.').($ordenar=='pratica_indicador_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_aprovado&ordem='.($ordem ? '0' : '1').$extra.'\');">'.($ordenar=='pratica_indicador_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_indicador) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').$extra.'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_tendencia']) echo '<th style="white-space: nowrap" width="16">'.dica('Tendência', 'Tendência apresentada pelos últimos 3 valores registrados no indicador.').'T'.dicaF().'</th>';
if ($exibir['pratica_indicador_pontuacao']) echo '<th style="white-space: nowrap">'.dica('Pontuação', 'Resultado do indicador em relação à meta estipulada.').'P'.dicaF().'</th>';
if ($exibir['pratica_indicador_valor']) echo '<th style="white-space: nowrap" width="60">'.dica('Valor', 'Resultado do indicador em termos de sua unidade de referência.').'Valor'.dicaF().'</th>';
if ($exibir['pratica_indicador_meta']) echo '<th style="white-space: nowrap" width="60">'.dica('Meta', 'A meta estabelecida para o indicador.').'Meta'.dicaF().'</th>';
if ($exibir['pratica_indicador_unidade']) echo '<th style="white-space: nowrap" width="40"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_unidade&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('U.M.', 'A unidade de medida da meta estabelecida para o indicador.').($ordenar=='pratica_indicador_unidade' ? imagem('icones/'.$seta[$ordem]) : '').'U.M.'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_data_meta']) echo '<th style="white-space: nowrap" width="80">'.dica('Data Meta', 'A data limite para se alcançar a meta estabelecida.').'Data Meta'.dicaF().'</th>';
if ($exibir['pratica_indicador_agrupar']) echo '<th style="white-space: nowrap" width="30"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_agrupar&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Periodicidade', 'O período padrão para agrupar valores.').($ordenar=='pratica_indicador_agrupar' ? imagem('icones/'.$seta[$ordem]) : '').'Pe'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_acumulacao']) echo '<th style="white-space: nowrap" width="30"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_acumulacao&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Acumulação', 'A forma de acumulação de valores.').($ordenar=='pratica_indicador_acumulacao' ? imagem('icones/'.$seta[$ordem]) : '').'Ac'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_data_alteracao']) echo '<th style="white-space: nowrap" width="80"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=ultima_data&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('A Data da Última Alteração', 'A data do último valor inserido no indicador.').($ordenar=='ultima_data' ? imagem('icones/'.$seta[$ordem]) : '').'Alteração'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_sentido']) echo '<th style="white-space: nowrap" width="30"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_sentido&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Polaridade', 'A polaridade dos valores.').($ordenar=='pratica_indicador_sentido' ? imagem('icones/'.$seta[$ordem]) : '').'Pol.'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_periodo_anterior']) echo '<th style="white-space: nowrap" width="30"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_periodo_anterior&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Pontuação do Período Anterior', 'Caso se vertifica a pontuação apenas do período anterior (pois o atual não fecha até o final do mesmo).').($ordenar=='pratica_indicador_periodo_anterior' ? imagem('icones/'.$seta[$ordem]) : '').'PA'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_tolerancia']) echo '<th style="white-space: nowrap" width="30"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_tolerancia&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Tolerância', 'Qual a tolerância da pontuação obtida em relação à meta..').($ordenar=='pratica_indicador_tolerancia' ? imagem('icones/'.$seta[$ordem]) : '').'Toler.'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_codigo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_codigo&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Código', 'O campo código do indicador.').($ordenar=='pratica_indicador_codigo' ? imagem('icones/'.$seta[$ordem]) : '').'Código'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_descricao&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Descrição', 'O campo Descrição do indicador.').($ordenar=='pratica_indicador_requisito_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Descrição'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_oque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_oque&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('O Que', 'O campo O que do indicador.').($ordenar=='pratica_indicador_requisito_oque' ? imagem('icones/'.$seta[$ordem]) : '').'O Que'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_onde']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_onde&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Onde', 'O campo Onde do indicador.').($ordenar=='pratica_indicador_requisito_onde' ? imagem('icones/'.$seta[$ordem]) : '').'Onde'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_quando&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Quando', 'O campo Quando do indicador.').($ordenar=='pratica_indicador_requisito_quando' ? imagem('icones/'.$seta[$ordem]) : '').'Quando'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_como']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_como&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Como', 'O campo Como do indicador.').($ordenar=='pratica_indicador_requisito_como' ? imagem('icones/'.$seta[$ordem]) : '').'Como'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_porque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_porque&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Porque', 'O campo Porque do indicador.').($ordenar=='pratica_indicador_requisito_porque' ? imagem('icones/'.$seta[$ordem]) : '').'Porque'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_quanto']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_quanto&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Quanto', 'O campo Quanto do indicador.').($ordenar=='pratica_indicador_requisito_quanto' ? imagem('icones/'.$seta[$ordem]) : '').'Quanto'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_quem']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_quem&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Quem', 'O campo Quem do indicador.').($ordenar=='pratica_indicador_requisito_quem' ? imagem('icones/'.$seta[$ordem]) : '').'Quem'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_requisito_melhorias']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_requisito_melhorias&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Melhorias', 'O campo Melhorias do indicador.').($ordenar=='pratica_indicador_requisito_melhorias' ? imagem('icones/'.$seta[$ordem]) : '').'Melhorias'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';
if ($exibir['pratica_indicador_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=pratica_indicador_cia&ordem='.($ordem ? '0' : '1').$extra.'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='pratica_indicador_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['pratica_indicador_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['pratica_indicador_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=pratica_indicador_dept&ordem='.($ordem ? '0' : '1').$extra.'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='pratica_indicador_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['pratica_indicador_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['pratica_indicador_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_indicador_responsavel&ordem='.($ordem ? '0' : '1').$extra.'\');">'.dica('Responsável', 'O '.$config['usuario'].' responsável pelo indicador.').($ordenar=='pratica_indicador_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável'.dicaF().'</a></th>';
if ($exibir['pratica_indicador_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para o indicador.').'Designados'.dicaF().'</th>';
if (!isset($detalhe_projeto) && $exibir['pratica_indicador_marcadores']) echo '<th style="white-space: nowrap" width="16">'.dica('Quantidade de Marcadores', 'A quantidade de marcadores relacionados à pauta selecionada neste indicador.').'Qnt'.dicaF().'</th>';
echo '</tr>';


$qnt=0;

$pontos_totais=0;
$qnt_indicadores=0;
$sem_permissao=0;

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'indicador');


include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $indicadores[$i];
	$qnt++;
	
	$editar=permiteEditarIndicador($linha['pratica_indicador_acesso'],$linha['pratica_indicador_id']);
	if ($edicao && !$editar) continue;
	
	$podeEditarTudo=($linha['pratica_indicador_acesso']>=5 ? $editar && (in_array($linha['pratica_indicador_responsavel'], $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);


	if ($Aplic->profissional) $bloquear=($linha['pratica_indicador_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
	else $bloquear=0;
	
	
	$ver=permiteAcessarIndicador($linha['pratica_indicador_acesso'],$linha['pratica_indicador_id']);

	if ($ver){
		echo '<tr>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['pratica_indicador_id'].'" value="'.$linha['pratica_indicador_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['pratica_indicador_id'].'" value="'.$linha['pratica_indicador_id'].'" '.(isset($selecionado[$linha['pratica_indicador_id']]) ? 'checked="checked"' : '').' />';

		$podeValor=permiteEditarValorIndicador($linha['pratica_indicador_id']);
		

		if (!$impressao  && !$dialogo) echo '<td style="white-space: nowrap" width="32">'.
		($podeEditar && $podeEditarTudo && !$bloquear ? dica('Editar Indicador', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este indicador.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_editar&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : imagem('icones/vazio14.gif')).
		
		
		((($podeEditar && $podeEditarTudo) || $podeValor) && (!$linha['pratica_indicador_composicao'] && !$linha['pratica_indicador_formula'] && !$linha['pratica_indicador_campo_projeto'] && !$linha['pratica_indicador_campo_tarefa'] && !$linha['pratica_indicador_campo_acao'] && !$linha['pratica_indicador_externo']) ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a='.($linha['pratica_indicador_checklist'] ? 'checklist_editar_valor' : ($linha['pratica_indicador_formula_simples'] ? 'indicador_editar_valor_pro' : 'indicador_editar_valor')).'&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/adicionar.png','Inserir','Clique neste ícone '.imagem('icones/adicionar.png').' para inserir um novo valor.').'</a>' : '').($linha['pratica_indicador_externo'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id='.$linha['pratica_indicador_id'].'\');">'.imagem('icones/adicionar.png','Importar','Clique neste ícone '.imagem('icones/adicionar.png').' para importar um novo valor.').'</a>' : '').'</td>';
		if ($exibir['pratica_indicador_cor']) echo '<td align="right" style="background-color:#'.$linha['pratica_indicador_cor'].'; white-space: nowrap"><font color="'.melhorCor($linha['pratica_indicador_cor']).'">&nbsp;&nbsp;</font></td>';
		$icone='';
		if ($indicador_expandido!=$linha['pratica_indicador_id']){
			
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('count(pratica_indicador_id)');
			$sql->adOnde('pratica_indicador_superior='.$linha['pratica_indicador_id'].' AND pratica_indicador_id!='.$linha['pratica_indicador_id']);
			$subordinados=$sql->Resultado();
			$sql->limpar();
			$icone=($subordinados > 0 ? ($indicador_expandido ? imagem('icones/subnivel.gif') : '').'<a href="javascript:void(0);" onclick="env.indicador_expandido.value='.$linha['pratica_indicador_id'].'; env.submit();">'.imagem('icones/expandir.gif', 'Ver Subordinados', 'Clique neste ícone '.imagem('icones/expandir.gif').' para expandir os indicadores subordinados a este').'</a>' : ( $indicador_expandido ? imagem('icones/subnivel.gif') : ''));
			}
		else{
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_superior');
			$sql->adOnde('pratica_indicador_id='.$linha['pratica_indicador_id'].' AND pratica_indicador_superior!=pratica_indicador_id');
			$superior=$sql->Resultado();
			$sql->limpar();
			$icone='<a href="javascript:void(0);" onclick="env.indicador_expandido.value='.($superior ? $superior : 0).'; env.submit();">'.imagem('icones/colapsar.gif', 'Colapsar Subordinados', 'Clique neste ícone '.imagem('icones/colapsar.gif').' para colapsar os indicadores subordinados a este').'</a>';
			}
		
		if ($selecao) echo '<td id="pratica_indicador_nome_'.$linha['pratica_indicador_id'].'">'.$linha['pratica_indicador_nome'].'</td>';
		else echo '<td>'.$icone.link_indicador($linha['pratica_indicador_id'], null, null, null, null, null, true, $ano).'</td>';
		
		if ($exibir['pratica_indicador_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['pratica_indicador_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_indicador) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';

		
		
		$obj_indicador = new Indicador($linha['pratica_indicador_id'], $ano);
		
		if ($exibir['pratica_indicador_tendencia']) echo '<td '.tendencia($obj_indicador->Tendencia()).'>&nbsp;&nbsp;</td>';
	
		$qnt_indicadores++;
		
		if ($exibir['pratica_indicador_pontuacao']) {
			$pontos=$obj_indicador->Pontuacao($ano);
			$pontos_totais+=$pontos;
			
			echo '<td align=center '.referencial($pontos).'>'.number_format($pontos, 0).'</td>';
			}
		
		if ($exibir['pratica_indicador_valor']) {
			$valor=$obj_indicador->Valor_atual($linha['pratica_indicador_agrupar'], $ano);
			echo '<td style="white-space: nowrap" align=right>'.number_format($valor, $config['casas_decimais'], ',', '.').'</td>';
			}
			
		if ($exibir['pratica_indicador_meta']) {
			$meta=number_format($obj_indicador->pratica_indicador_valor_meta, $config['casas_decimais'], ',', '.');
			echo '<td style="white-space: nowrap" align=right>'.$meta.'</td>';
			}
			
		if ($exibir['pratica_indicador_unidade']) echo '<td align=center>'.($linha['pratica_indicador_unidade'] ? $linha['pratica_indicador_unidade'] : '').'</td>';
		
		if ($exibir['pratica_indicador_data_meta']) echo '<td style="white-space: nowrap" align=center '.dataMeta($obj_indicador->pratica_indicador_data_meta, $pontos).'>'.retorna_data($obj_indicador->pratica_indicador_data_meta, false).'</td>';
		
		if ($exibir['pratica_indicador_agrupar']) echo '<td style="white-space: nowrap" align=center>'.(isset($tipo_agrupamento[$linha['pratica_indicador_agrupar']]) ? $tipo_agrupamento[$linha['pratica_indicador_agrupar']] : '&nbsp;').'</td>';
		
		
		if ($exibir['pratica_indicador_acumulacao']) echo '<td style="white-space: nowrap" align=center>'.(isset($tipo_acumulacao[$linha['pratica_indicador_acumulacao']]) ? $tipo_acumulacao[$linha['pratica_indicador_acumulacao']] : '&nbsp;').'</td>';
		
		if ($exibir['pratica_indicador_data_alteracao']) {
			echo '<td style="white-space: nowrap" align=center>';
			if (!$linha['pratica_indicador_composicao'] && !$linha['pratica_indicador_formula'] && !$linha['pratica_indicador_campo_projeto'] && !$linha['pratica_indicador_campo_tarefa'] && !$linha['pratica_indicador_campo_acao']) {
				echo $linha['ultima_data'];
				}
			else echo 'N/A';
			echo '</td>';
			}
	
			
		if ($exibir['pratica_indicador_sentido']){
			
			echo '<td align=center>';
			if ($linha['pratica_indicador_sentido']==2) echo imagem('icones/seta_meio.gif');
			elseif ($linha['pratica_indicador_sentido']==1) echo imagem('icones/seta_acima.gif');
			else echo imagem('icones/seta_baixo.gif');
			echo '</td>';
			}	

		if ($exibir['pratica_indicador_periodo_anterior']) echo '<td style="white-space: nowrap; font-weight:bold;" width="16" align=center>'.($linha['pratica_indicador_periodo_anterior'] ? 'X' : '').'</td>';	
		if ($exibir['pratica_indicador_tolerancia']) echo '<td style="white-space: nowrap" align=center>'.number_format($linha['pratica_indicador_tolerancia'], 0, ',', '.').'%</td>';		
			
		if ($exibir['pratica_indicador_codigo']) echo '<td align=center>'.($linha['pratica_indicador_codigo'] ? $linha['pratica_indicador_codigo'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_descricao']) echo '<td>'.($linha['pratica_indicador_requisito_descricao'] ? $linha['pratica_indicador_requisito_descricao'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_oque'])echo '<td>'.($linha['pratica_indicador_requisito_oque'] ? $linha['pratica_indicador_requisito_oque'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_onde']) echo '<td>'.($linha['pratica_indicador_requisito_onde'] ? $linha['pratica_indicador_requisito_onde'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_quando']) echo '<td>'.($linha['pratica_indicador_requisito_quando'] ? $linha['pratica_indicador_requisito_quando'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_como']) echo '<td>'.($linha['pratica_indicador_requisito_como'] ? $linha['pratica_indicador_requisito_como'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_porque']) echo '<td>'.($linha['pratica_indicador_requisito_porque'] ? $linha['pratica_indicador_requisito_porque'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_quanto']) echo '<td>'.($linha['pratica_indicador_requisito_quanto'] ? $linha['pratica_indicador_requisito_quanto'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_quem']) echo '<td>'.($linha['pratica_indicador_requisito_quem'] ? $linha['pratica_indicador_requisito_quem'] : '&nbsp;').'</td>';
		if ($exibir['pratica_indicador_requisito_melhorias']) echo '<td>'.($linha['pratica_indicador_requisito_melhorias'] ? $linha['pratica_indicador_requisito_melhorias'] : '&nbsp;').'</td>';
			
		if ($exibir['pratica_indicador_relacionado']){
			$sql->adTabela('pratica_indicador_gestao');
			$sql->adCampo('pratica_indicador_gestao.*');
			$sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$linha['pratica_indicador_id']);	
			$sql->adOrdem('pratica_indicador_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['pratica_indicador_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_indicador_gestao_tarefa']);
					elseif ($gestao_data['pratica_indicador_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_indicador_gestao_projeto']);
					elseif ($gestao_data['pratica_indicador_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_indicador_gestao_pratica']);
					elseif ($gestao_data['pratica_indicador_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_indicador_gestao_acao']);
					elseif ($gestao_data['pratica_indicador_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_indicador_gestao_perspectiva']);
					elseif ($gestao_data['pratica_indicador_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['pratica_indicador_gestao_tema']);
					elseif ($gestao_data['pratica_indicador_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_indicador_gestao_objetivo']);
					elseif ($gestao_data['pratica_indicador_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_indicador_gestao_fator']);
					elseif ($gestao_data['pratica_indicador_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_indicador_gestao_estrategia']);
					elseif ($gestao_data['pratica_indicador_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_indicador_gestao_meta']);
					elseif ($gestao_data['pratica_indicador_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_indicador_gestao_canvas']);
					elseif ($gestao_data['pratica_indicador_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['pratica_indicador_gestao_risco']);
					elseif ($gestao_data['pratica_indicador_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_indicador_gestao_risco_resposta']);
					
					elseif ($gestao_data['pratica_indicador_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['pratica_indicador_gestao_semelhante']);
					
					elseif ($gestao_data['pratica_indicador_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['pratica_indicador_gestao_calendario']);
					elseif ($gestao_data['pratica_indicador_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_indicador_gestao_monitoramento']);
					elseif ($gestao_data['pratica_indicador_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['pratica_indicador_gestao_ata']);
					elseif ($gestao_data['pratica_indicador_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['pratica_indicador_gestao_mswot']);
					elseif ($gestao_data['pratica_indicador_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['pratica_indicador_gestao_swot']);
					elseif ($gestao_data['pratica_indicador_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_indicador_gestao_operativo']);
					elseif ($gestao_data['pratica_indicador_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_indicador_gestao_instrumento']);
					elseif ($gestao_data['pratica_indicador_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_indicador_gestao_recurso']);
					elseif ($gestao_data['pratica_indicador_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['pratica_indicador_gestao_problema']);
					elseif ($gestao_data['pratica_indicador_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_indicador_gestao_demanda']);	
					elseif ($gestao_data['pratica_indicador_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['pratica_indicador_gestao_programa']);
					elseif ($gestao_data['pratica_indicador_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_indicador_gestao_licao']);
					elseif ($gestao_data['pratica_indicador_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_indicador_gestao_evento']);
					elseif ($gestao_data['pratica_indicador_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['pratica_indicador_gestao_link']);
					elseif ($gestao_data['pratica_indicador_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_indicador_gestao_avaliacao']);
					elseif ($gestao_data['pratica_indicador_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_indicador_gestao_tgn']);
					elseif ($gestao_data['pratica_indicador_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['pratica_indicador_gestao_brainstorm']);
					elseif ($gestao_data['pratica_indicador_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['pratica_indicador_gestao_gut']);
					elseif ($gestao_data['pratica_indicador_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['pratica_indicador_gestao_causa_efeito']);
					elseif ($gestao_data['pratica_indicador_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_indicador_gestao_arquivo']);
					elseif ($gestao_data['pratica_indicador_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_indicador_gestao_forum']);
					elseif ($gestao_data['pratica_indicador_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_indicador_gestao_checklist']);
					elseif ($gestao_data['pratica_indicador_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['pratica_indicador_gestao_agenda']);
					elseif ($gestao_data['pratica_indicador_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['pratica_indicador_gestao_agrupamento']);
					elseif ($gestao_data['pratica_indicador_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_indicador_gestao_patrocinador']);
					elseif ($gestao_data['pratica_indicador_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['pratica_indicador_gestao_template']);
					elseif ($gestao_data['pratica_indicador_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['pratica_indicador_gestao_painel']);
					elseif ($gestao_data['pratica_indicador_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_indicador_gestao_painel_odometro']);
					elseif ($gestao_data['pratica_indicador_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['pratica_indicador_gestao_painel_composicao']);		
					elseif ($gestao_data['pratica_indicador_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['pratica_indicador_gestao_tr']);	
					elseif ($gestao_data['pratica_indicador_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['pratica_indicador_gestao_me']);	
					elseif ($gestao_data['pratica_indicador_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['pratica_indicador_gestao_acao_item']);	
					elseif ($gestao_data['pratica_indicador_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['pratica_indicador_gestao_beneficio']);	
					elseif ($gestao_data['pratica_indicador_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['pratica_indicador_gestao_painel_slideshow']);	
					elseif ($gestao_data['pratica_indicador_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['pratica_indicador_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['pratica_indicador_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['pratica_indicador_gestao_projeto_abertura']);	
					elseif ($gestao_data['pratica_indicador_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['pratica_indicador_gestao_plano_gestao']);	
					elseif ($gestao_data['pratica_indicador_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['pratica_indicador_gestao_ssti']);	
					elseif ($gestao_data['pratica_indicador_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['pratica_indicador_gestao_laudo']);	
					elseif ($gestao_data['pratica_indicador_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['pratica_indicador_gestao_trelo']);	
					elseif ($gestao_data['pratica_indicador_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['pratica_indicador_gestao_trelo_cartao']);	
					elseif ($gestao_data['pratica_indicador_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['pratica_indicador_gestao_pdcl']);	
					elseif ($gestao_data['pratica_indicador_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['pratica_indicador_gestao_pdcl_item']);	

					
					}
				}	
			echo '</td>';	
			}	
		
		
		
		
		if ($exibir['pratica_indicador_cia']) echo '<td>'.link_cia($linha['pratica_indicador_cia']).'</td>';
		if ($exibir['pratica_indicador_cias']){
			$sql->adTabela('indicador_cia');
			$sql->adCampo('indicador_cia_cia');
			$sql->adOnde('indicador_cia_indicador = '.(int)$linha['pratica_indicador_id']);
			$cias = $sql->carregarColuna();
			$sql->limpar();
			$saida_cias='';
			if (isset($cias) && count($cias)) {
				$plural=(count($cias)>1 ? 's' : '');
				$saida_cias.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_cias.= '<tr><td style="border:0px;">'.link_cia($cias[0]);
				$qnt_cias=count($cias);
				if ($qnt_cias > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_cias; $j < $i_cmp; $j++) $lista.=link_cia($cias[$j]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['pratica_indicador_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['pratica_indicador_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		if ($exibir['pratica_indicador_dept']) echo '<td>'.link_secao($linha['pratica_indicador_dept']).'</td>';	
		if ($exibir['pratica_indicador_depts']){
			$sql->adTabela('pratica_indicador_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('pratica_indicador_id = '.(int)$linha['pratica_indicador_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
			$saida_depts='';
			if (isset($depts) && count($depts)) {
				$plural=(count($depts)>1 ? 's' : '');
				$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_depts.= '<tr><td style="border:0px;">'.link_secao($depts[0]);
				$qnt_depts=count($depts);
				if ($qnt_depts > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_depts; $j < $i_cmp; $j++) $lista.=link_secao($depts[$j]).'<br>';
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['pratica_indicador_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['pratica_indicador_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
		
		
		if ($exibir['pratica_indicador_responsavel']) echo '<td>'.link_usuario($linha['pratica_indicador_responsavel'],'','','esquerda').'</td>';
		
		if ($exibir['pratica_indicador_designados']) {
			$sql->adTabela('pratica_indicador_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('pratica_indicador_id = '.(int)$linha['pratica_indicador_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['pratica_indicador_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['pratica_indicador_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		
			
		if (!isset($detalhe_projeto) && $exibir['pratica_indicador_marcadores']) echo '<td style="white-space: nowrap" align=center>'.$linha['qnt_marcador'].'</td>';
	
		echo '</tr>';
		}
	else $sem_permissao++;
	}
if ($sem_permissao) echo '<tr><td colspan="20"><p>Não '.($sem_permissao > 1 ? 'foram apresentados '.$sem_permissao.' indicadores' :  'foi apresentado 1 indicador').' por não ter permissão de visualiza-lo'.($sem_permissao > 1 ? 's' : '').'.</p></td></tr>';	
if (!count($indicadores)) echo '<tr><td colspan="20"><p>Nenhum indicador encontrado.</p></td></tr>';
elseif ($exibir['pratica_indicador_pontuacao']) {
	
	$num_col=2;
	if ($exibir['pratica_indicador_cor'])$num_col++;
	if ($exibir['pratica_indicador_aprovado'] && $Aplic->profissional)$num_col++;
	if ($filtro_prioridade_indicador) $num_col++;
	if ($exibir['pratica_indicador_tendencia'])$num_col++;
	$ponto_final=number_format(($qnt_indicadores ? $pontos_totais/$qnt_indicadores : 0), 0);
	echo '<tr><td colspan='.$num_col.'>&nbsp;</td><td align=center '.referencial($ponto_final).'>'.$ponto_final.'</td><td colspan=30>&nbsp;</td><tr>';
	}
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS()').'</td></tr></table></td></tr>';

echo '</table>';

if (!$selecao){
	echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 '.(!$dialogo ? 'class="std2"' : '').'><tr>';
	echo '<td width="5%"></td><td><table border=0 cellpadding=0 cellspacing=0>';
	echo '<tr>';
	echo '<td>'.dica('Tendência', 'Legenda para a tendênciado indicador, considerando os três últimos períodos.').'Tendência:'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #83c26c;">&nbsp; &nbsp;</td><td>'.dica('Tendência Positiva', 'Indicador com tendência positiva, considerando os três últimos períodos.').'&nbsp;Positiva'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #ffa4a4;">&nbsp; &nbsp;</td><td>'.dica('Tendência Negativa', 'Indicador com tendência negativa, considerando os três últimos períodos.').'&nbsp;Negativa'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #dddddd;">&nbsp; &nbsp;</td><td>'.dica('Sem Tendência','Indicador não possui tendência.').'&nbsp;Sem'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	/*
	echo '<td>'.dica('Prazo para Meta', 'Legenda para o alcanço da meta dentro do prazo estipulado.').'Meta Alcançada:'.dicaF().'</td><td>&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #83c26c;">&nbsp; &nbsp;</td><td>'.dica('Sim', 'A meta foi alcançada.').'&nbsp;Sim'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #ffa4a4;">&nbsp; &nbsp;</td><td>'.dica('Não', 'O prazo para cumprir a meta acabou sem alcançar o objetivo.').'&nbsp;Não'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	echo '<td style="border-style:solid;border-width:1px; background: #dddddd;">&nbsp; &nbsp;</td><td>'.dica('Dentro do Prazo', 'Ainda há tempo para alcançar a meta.').'&nbsp;Dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;</td>';
	*/
	echo '</tr>';
	echo '</table></td></tr></table>';
	}



function referencial($valor_referencial){
	global $config;
	$cores=retornar_cor($valor_referencial);
	$cor='style="border-style:solid;border-width:1px; background: #'.$cores.';"'; 
	return $cor;
	}


function dataMeta($data, $pontos){
	if (($data < date('Y-m-d')) && $pontos<100) $cor='style="border-style:solid;border-width:1px; background: #ffa4a4;"';
	elseif($pontos<100) $cor='style="border-style:solid;border-width:1px; background: #dddddd;"';
	else $cor='style="border-style:solid;border-width:1px; background: #83c26c;"'; 
	return $cor;
	}


function tendencia($valor_tendencia){
	if($valor_tendencia=='positiva') $tendencia='style="border-style:solid;border-width:1px; background: #83c26c;"';
	elseif($valor_tendencia=='negativa') $tendencia='style="border-style:solid;border-width:1px; background: #ffa4a4;"';
	else $tendencia='style="border-style:solid;border-width:1px; background: #dddddd;"';	
	return $tendencia;
	}
	
if (!$selecao && ($impressao  || $dialogo) && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';	
?>
<script type="text/javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
	
function selecionar(pratica_indicador_id){
	var nome=document.getElementById('pratica_indicador_nome_'+pratica_indicador_id).innerHTML;
	setFechar(pratica_indicador_id, nome);
	}
	

function selecionar_multiplo(){
	var qnt=0;	
	var saida='';
	 with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			if (thiselm.checked && thiselm.name=='campos[]') saida=saida+(qnt++ ? ',' : '')+thiselm.value;
      }
    }
	setFechar(saida, '');
	}	


function setFechar(chave, valor){
	if(parent && parent.gpwebApp){
		if (chave) parent.gpwebApp._popupCallback(chave, valor); 
		else parent.gpwebApp._popupCallback(null, "");
		} 
	else {
		if (chave!=0) <?php echo 'window.opener.'.($chamarVolta ? $chamarVolta : 'vazio').'(chave, valor);'?>
		else <?php echo 'window.opener.'.($chamarVolta ? $chamarVolta : 'vazio').'(null, "");'?>  
		window.close();
		}
	}
	
function cancelarSelecao(){
	if(parent && parent.gpwebApp && parent.gpwebApp._popupWin) parent.gpwebApp._popupWin.close(); 
	else window.close();
	}

function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }	
</script>	