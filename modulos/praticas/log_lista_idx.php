<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
 
global $Aplic, $dialogo, $podeExcluir, $m, $tab, $pesquisar_texto,
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

$sql = new BDConsulta;

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();




$log_id=getParam($_REQUEST, 'log_id', 0);
$cia_id=getParam($_REQUEST, 'cia_id', 0);

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', 0));
$usuario_id = $Aplic->getEstado('usuario_id') ? $Aplic->getEstado('usuario_id') : 0;


$nd=array(0 => '');
$nd+= getSisValorND();
$RefRegistroAcao = getSisValor('RefRegistroTarefa');
$RefRegistroAcaoImagem = getSisValor('RefRegistroTarefaImagem');
$ordenar=getParam($_REQUEST, 'ordenar', 'log_data');
$ordem=getParam($_REQUEST, 'ordem', '0');

$ordenacao=$ordenar.($ordem ? ' DESC' : ' ASC');

$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_checklist']);
$xmin = $xtamanhoPagina * ($pagina - 1); 


if (!$tarefa_id) $tarefa_id=getParam($_REQUEST, 'tarefa_id', null);
if (!$projeto_id) $projeto_id=getParam($_REQUEST, 'projeto_id', null);
if (!$pg_perspectiva_id) $pg_perspectiva_id=getParam($_REQUEST, 'pg_perspectiva_id', null);
if (!$tema_id) $tema_id=getParam($_REQUEST, 'tema_id', null);
if (!$objetivo_id) $objetivo_id=getParam($_REQUEST, 'objetivo_id', null);
if (!$fator_id) $fator_id=getParam($_REQUEST, 'fator_id', null);
if (!$pg_estrategia_id) $pg_estrategia_id=getParam($_REQUEST, 'pg_estrategia_id', null);
if (!$pg_meta_id) $pg_meta_id=getParam($_REQUEST, 'pg_meta_id', null);
if (!$pratica_id) $pratica_id=getParam($_REQUEST, 'pratica_id', null);
if (!$pratica_indicador_id) $pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', null);
if (!$plano_acao_id) $plano_acao_id=getParam($_REQUEST, 'plano_acao_id', null);
if (!$canvas_id) $canvas_id=getParam($_REQUEST, 'canvas_id', null);
if (!$risco_id) $risco_id=getParam($_REQUEST, 'risco_id', null);
if (!$risco_resposta_id) $risco_resposta_id=getParam($_REQUEST, 'risco_resposta_id', null);
if (!$calendario_id) $calendario_id=getParam($_REQUEST, 'calendario_id', null);
if (!$monitoramento_id) $monitoramento_id=getParam($_REQUEST, 'monitoramento_id', null);
if (!$ata_id) $ata_id=getParam($_REQUEST, 'ata_id', null);
if (!$mswot_id) $mswot_id=getParam($_REQUEST, 'mswot_id', null);
if (!$swot_id) $swot_id=getParam($_REQUEST, 'swot_id', null);
if (!$operativo_id) $operativo_id=getParam($_REQUEST, 'operativo_id', null);
if (!$instrumento_id) $instrumento_id=getParam($_REQUEST, 'instrumento_id', null);
if (!$recurso_id) $recurso_id=getParam($_REQUEST, 'recurso_id', null);
if (!$problema_id) $problema_id=getParam($_REQUEST, 'problema_id', null);
if (!$demanda_id) $demanda_id=getParam($_REQUEST, 'demanda_id', null);
if (!$programa_id) $programa_id=getParam($_REQUEST, 'programa_id', null);
if (!$licao_id) $licao_id=getParam($_REQUEST, 'licao_id', null);
if (!$evento_id) $evento_id=getParam($_REQUEST, 'evento_id', null);
if (!$link_id) $link_id=getParam($_REQUEST, 'link_id', null);
if (!$avaliacao_id) $avaliacao_id=getParam($_REQUEST, 'avaliacao_id', null);
if (!$tgn_id) $tgn_id=getParam($_REQUEST, 'tgn_id', null);
if (!$brainstorm_id) $brainstorm_id=getParam($_REQUEST, 'brainstorm_id', null);
if (!$gut_id) $gut_id=getParam($_REQUEST, 'gut_id', null);
if (!$causa_efeito_id) $causa_efeito_id=getParam($_REQUEST, 'causa_efeito_id', null);
if (!$arquivo_id) $arquivo_id=getParam($_REQUEST, 'arquivo_id', null);
if (!$forum_id) $forum_id=getParam($_REQUEST, 'forum_id', null);
if (!$checklist_id) $checklist_id=getParam($_REQUEST, 'checklist_id', null);
if (!$agenda_id) $agenda_id=getParam($_REQUEST, 'agenda_id', null);
if (!$agrupamento_id) $agrupamento_id=getParam($_REQUEST, 'agrupamento_id', null);
if (!$patrocinador_id) $patrocinador_id=getParam($_REQUEST, 'patrocinador_id', null);
if (!$template_id) $template_id=getParam($_REQUEST, 'template_id', null);
if (!$painel_id) $painel_id=getParam($_REQUEST, 'painel_id', null);
if (!$painel_odometro_id) $painel_odometro_id=getParam($_REQUEST, 'painel_odometro_id', null);
if (!$painel_composicao_id) $painel_composicao_id=getParam($_REQUEST, 'painel_composicao_id', null);
if (!$tr_id) $tr_id=getParam($_REQUEST, 'tr_id', null);
if (!$me_id) $me_id=getParam($_REQUEST, 'me_id', null);
if (!$plano_acao_item_id) $plano_acao_item_id=getParam($_REQUEST, 'plano_acao_item_id', null);
if (!$beneficio_id) $beneficio_id=getParam($_REQUEST, 'beneficio_id', null);
if (!$painel_slideshow_id) $painel_slideshow_id=getParam($_REQUEST, 'painel_slideshow_id', null);
if (!$projeto_viabilidade_id) $projeto_viabilidade_id=getParam($_REQUEST, 'projeto_viabilidade_id', null);
if (!$projeto_abertura_id) $projeto_abertura_id=getParam($_REQUEST, 'projeto_abertura_id', null);
if (!$pg_id) $pg_id=getParam($_REQUEST, 'pg_id', null);
if (!$ssti_id) $ssti_id=getParam($_REQUEST, 'ssti_id', null);
if (!$laudo_id) $laudo_id=getParam($_REQUEST, 'laudo_id', null);
if (!$trelo_id) $trelo_id=getParam($_REQUEST, 'trelo_id', null);
if (!$trelo_cartao_id) $trelo_cartao_id=getParam($_REQUEST, 'trelo_cartao_id', null);
if (!$pdcl_id) $pdcl_id=getParam($_REQUEST, 'pdcl_id', null);
if (!$pdcl_item_id) $pdcl_item_id=getParam($_REQUEST, 'pdcl_item_id', null);

echo '<form name="frmExcluir2" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="fazerSQL" value="log_fazer_sql" />';
echo '<input type="hidden" name="log_id" value="" />';
echo '<input type="hidden" name="log_tarefa" id="log_tarefa" value="'.$tarefa_id .'" />';
echo '<input type="hidden" name="log_projeto" id="log_projeto" value="'.$projeto_id .'" />';
echo '<input type="hidden" name="log_perspectiva" id="log_perspectiva" value="'.$pg_perspectiva_id .'" />';
echo '<input type="hidden" name="log_tema" id="log_tema" value="'.$tema_id .'" />';
echo '<input type="hidden" name="log_objetivo" id="log_objetivo" value="'.$objetivo_id .'" />';
echo '<input type="hidden" name="log_fator" id="log_fator" value="'.$fator_id .'" />';
echo '<input type="hidden" name="log_estrategia" id="log_estrategia" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="log_meta" id="log_meta" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="log_pratica" id="log_pratica" value="'.$pratica_id .'" />';
echo '<input type="hidden" name="log_acao" id="log_acao" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="log_canvas" id="log_canvas" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="log_risco" id="log_risco" value="'.$risco_id.'" />';
echo '<input type="hidden" name="log_risco_resposta" id="log_risco_resposta" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="log_indicador" id="log_indicador" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="log_calendario" id="log_calendario" value="'.$calendario_id .'" />';
echo '<input type="hidden" name="log_monitoramento" id="log_monitoramento" value="'.$monitoramento_id .'" />';
echo '<input type="hidden" name="log_ata" id="log_ata" value="'.$ata_id .'" />';
echo '<input type="hidden" name="log_mswot" id="log_mswot" value="'.$mswot_id .'" />';
echo '<input type="hidden" name="log_swot" id="log_swot" value="'.$swot_id .'" />';
echo '<input type="hidden" name="log_operativo" id="log_operativo" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="log_instrumento" id="log_instrumento" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="log_recurso" id="log_recurso" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="log_problema" id="log_problema" value="'.$problema_id.'" />';
echo '<input type="hidden" name="log_demanda" id="log_demanda" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="log_programa" id="log_programa" value="'.$programa_id.'" />';
echo '<input type="hidden" name="log_licao" id="log_licao" value="'.$licao_id.'" />';
echo '<input type="hidden" name="log_evento" id="log_evento" value="'.$evento_id.'" />';
echo '<input type="hidden" name="log_link" id="log_link" value="'.$link_id.'" />';
echo '<input type="hidden" name="log_avaliacao" id="log_avaliacao" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="log_tgn" id="log_tgn" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="log_brainstorm" id="log_brainstorm" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="log_gut" id="log_gut" value="'.$gut_id.'" />';
echo '<input type="hidden" name="log_causa_efeito" id="log_causa_efeito" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="log_arquivo" id="log_arquivo" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="log_forum" id="log_forum" value="'.$forum_id.'" />';
echo '<input type="hidden" name="log_checklist" id="log_checklist" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="log_agenda" id="log_agenda" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="log_agrupamento" id="log_agrupamento" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="log_patrocinador" id="log_patrocinador" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="log_template" id="log_template" value="'.$template_id.'" />';
echo '<input type="hidden" name="log_painel" id="log_painel" value="'.$painel_id.'" />';
echo '<input type="hidden" name="log_painel_odometro" id="log_painel_odometro" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="log_painel_composicao" id="log_painel_composicao" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="log_tr" id="log_tr" value="'.$tr_id.'" />';
echo '<input type="hidden" name="log_me" id="log_me" value="'.$me_id.'" />';
echo '<input type="hidden" name="log_acao_item" id="log_acao_item" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" name="log_beneficio" id="log_beneficio" value="'.$beneficio_id.'" />';
echo '<input type="hidden" name="log_painel_slideshow" id="log_painel_slideshow" value="'.$painel_slideshow_id.'" />';
echo '<input type="hidden" name="log_projeto_viabilidade" id="log_projeto_viabilidade" value="'.$projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="log_projeto_abertura" id="log_projeto_abertura" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="log_plano_gestao" id="log_plano_gestao" value="'.$pg_id.'" />';
echo '<input type="hidden" name="log_ssti" id="log_ssti" value="'.$ssti_id.'" />';
echo '<input type="hidden" name="log_laudo" id="log_laudo" value="'.$laudo_id.'" />';
echo '<input type="hidden" name="log_trelo" id="log_trelo" value="'.$trelo_id.'" />';
echo '<input type="hidden" name="log_trelo_cartao" id="log_trelo_cartao" value="'.$trelo_cartao_id.'" />';
echo '<input type="hidden" name="log_pdcl" id="log_pdcl" value="'.$pdcl_id.'" />';
echo '<input type="hidden" name="log_pdcl_item" id="log_pdcl_item" value="'.$pdcl_item_id.'" />';

echo '<input type="hidden" name="del" value="1" />';
echo '</form>';


echo '<form name="frmFiltro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';

echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id .'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id .'" />';
echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id .'" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id .'" />';
echo '<input type="hidden" name="objetivo_id" id="objetivo_id" value="'.$objetivo_id .'" />';
echo '<input type="hidden" name="fator_id" id="fator_id" value="'.$fator_id .'" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id .'" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id .'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id .'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id .'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="'.$canvas_id .'" />';
echo '<input type="hidden" name="risco_id" id="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" id="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" id="calendario_id" value="'.$calendario_id .'" />';
echo '<input type="hidden" name="monitoramento_id" id="monitoramento_id" value="'.$monitoramento_id .'" />';
echo '<input type="hidden" name="ata_id" id="ata_id" value="'.$ata_id .'" />';
echo '<input type="hidden" name="mswot_id" id="mswot_id" value="'.$mswot_id .'" />';
echo '<input type="hidden" name="swot_id" id="swot_id" value="'.$swot_id .'" />';
echo '<input type="hidden" name="operativo_id" id="operativo_id" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="recurso_id" id="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="problema_id" id="problema_id" value="'.$problema_id.'" />';
echo '<input type="hidden" name="demanda_id" id="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="programa_id" id="programa_id" value="'.$programa_id.'" />';
echo '<input type="hidden" name="licao_id" id="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="link_id" id="link_id" value="'.$link_id.'" />';
echo '<input type="hidden" name="avaliacao_id" id="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="tgn_id" id="tgn_id" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="brainstorm_id" id="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="gut_id" id="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="causa_efeito_id" id="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="arquivo_id" id="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="forum_id" id="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="agenda_id" id="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agrupamento_id" id="agrupamento_id" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="patrocinador_id" id="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'" />';
echo '<input type="hidden" name="painel_id" id="painel_id" value="'.$painel_id.'" />';
echo '<input type="hidden" name="painel_odometro_id" id="painel_odometro_id" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="painel_composicao_id" id="painel_composicao_id" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="tr_id" id="tr_id" value="'.$tr_id.'" />';
echo '<input type="hidden" name="me_id" id="me_id" value="'.$me_id.'" />';
echo '<input type="hidden" name="log_acao_item" id="log_acao_item" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" name="log_beneficio" id="log_beneficio" value="'.$beneficio_id.'" />';
echo '<input type="hidden" name="log_painel_slideshow" id="log_painel_slideshow" value="'.$painel_slideshow_id.'" />';
echo '<input type="hidden" name="log_projeto_viabilidade" id="log_projeto_viabilidade" value="'.$projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="log_projeto_abertura" id="log_projeto_abertura" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="log_plano_gestao" id="log_plano_gestao" value="'.$pg_id.'" />';
echo '</form>';


if ($tarefa_id) {
	$endereco='tarefa_id='.$tarefa_id; 
	
	$sql->adTabela('projetos');
	$sql->esqUnir('tarefas','tarefas', 'projetos.projeto_id=tarefas.tarefa_projeto');
	$sql->adCampo('projeto_moeda');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($projeto_id)  {
	$endereco='projeto_id='.$projeto_id; 	
	
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_moeda');
	$sql->adOnde('projeto_id ='.(int)$projeto_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pratica_indicador_id)  {
	$endereco='pratica_indicador_id='.$pratica_indicador_id; 
	
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_moeda');
	$sql->adOnde('pratica_indicador_id ='.(int)$pratica_indicador_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pratica_id)  {
	$endereco='pratica_id='.$pratica_id; 
	
	$sql->adTabela('praticas');
	$sql->adCampo('pratica_moeda');
	$sql->adOnde('pratica_id ='.(int)$pratica_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_perspectiva_id)  {
	$endereco='pg_perspectiva_id='.$pg_perspectiva_id;
	
	$sql->adTabela('perspectivas');
	$sql->adCampo('pg_perspectiva_moeda');
	$sql->adOnde('pg_perspectiva_id ='.(int)$pg_perspectiva_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($tema_id)  {
	$endereco='tema_id='.$tema_id;
	
	$sql->adTabela('tema');
	$sql->adCampo('tema_moeda');
	$sql->adOnde('tema_id ='.(int)$tema_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($objetivo_id)  {
	$endereco='objetivo_id='.$objetivo_id;
	
	$sql->adTabela('objetivo');
	$sql->adCampo('objetivo_moeda');
	$sql->adOnde('objetivo_id ='.(int)$objetivo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($fator_id)  {
	$endereco='fator_id='.$fator_id;
	
	$sql->adTabela('fator');
	$sql->adCampo('fator_moeda');
	$sql->adOnde('fator_id ='.(int)$fator_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_estrategia_id)  {
	$endereco='pg_estrategia_id='.$pg_estrategia_id;
	
	$sql->adTabela('estrategias');
	$sql->adCampo('pg_estrategia_moeda');
	$sql->adOnde('pg_estrategia_id ='.(int)$pg_estrategia_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($plano_acao_id)  {
	$endereco='plano_acao_id='.$plano_acao_id;
	
	$sql->adTabela('plano_acao');
	$sql->adCampo('plano_acao_moeda');
	$sql->adOnde('plano_acao_id ='.(int)$plano_acao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_meta_id)  {
	$endereco='pg_meta_id='.$pg_meta_id;
	
	$sql->adTabela('metas');
	$sql->adCampo('pg_meta_moeda');
	$sql->adOnde('pg_meta_id ='.(int)$pg_meta_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($canvas_id)  {
	$endereco='canvas_id='.$canvas_id;
	
	$sql->adTabela('canvas');
	$sql->adCampo('canvas_moeda');
	$sql->adOnde('canvas_id ='.(int)$canvas_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($risco_id)  {
	$endereco='risco_id='.$risco_id;
	
	$sql->adTabela('risco');
	$sql->adCampo('risco_moeda');
	$sql->adOnde('risco_id ='.(int)$risco_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($risco_resposta_id)  {
	$endereco='risco_resposta_id='.$risco_resposta_id;
	
	$sql->adTabela('risco_resposta');
	$sql->adCampo('risco_resposta_moeda');
	$sql->adOnde('risco_resposta_id ='.(int)$risco_resposta_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($problema_id)  {
	$endereco='problema_id='.$problema_id;
	
	$sql->adTabela('problema');
	$sql->adCampo('problema_moeda');
	$sql->adOnde('problema_id ='.(int)$problema_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($calendario_id)  {
	$endereco='calendario_id='.$calendario_id;
	
	$sql->adTabela('calendario');
	$sql->adCampo('calendario_moeda');
	$sql->adOnde('calendario_id ='.(int)$calendario_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($monitoramento_id)  {
	$endereco='monitoramento_id='.$monitoramento_id;
	
	$sql->adTabela('monitoramento');
	$sql->adCampo('monitoramento_moeda');
	$sql->adOnde('monitoramento_id ='.(int)$monitoramento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($ata_id)  {
	$endereco='ata_id='.$ata_id;
	
	$sql->adTabela('ata');
	$sql->adCampo('ata_moeda');
	$sql->adOnde('ata_id ='.(int)$ata_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($mswot_id)  {
	$endereco='mswot_id='.$mswot_id;
	
	$sql->adTabela('mswot');
	$sql->adCampo('mswot_moeda');
	$sql->adOnde('mswot_id ='.(int)$mswot_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($swot_id)  {
	$endereco='swot_id='.$swot_id;
	
	$sql->adTabela('swot');
	$sql->adCampo('swot_moeda');
	$sql->adOnde('swot_id ='.(int)$swot_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($operativo_id)  {
	$endereco='perativo_id='.$operativo_id;
	
	$sql->adTabela('operativo');
	$sql->adCampo('operativo_moeda');
	$sql->adOnde('operativo_id ='.(int)$operativo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($instrumento_id)  {
	$endereco='instrumento_id='.$instrumento_id;
	
	$sql->adTabela('instrumento');
	$sql->adCampo('instrumento_moeda');
	$sql->adOnde('instrumento_id ='.(int)$instrumento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($recurso_id)  {
	$endereco='recurso_id='.$recurso_id;
	
	$sql->adTabela('recursos');
	$sql->adCampo('recurso_moeda');
	$sql->adOnde('recurso_id ='.(int)$recurso_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($demanda_id)  {
	$endereco='demanda_id='.$demanda_id;
	
	$sql->adTabela('demandas');
	$sql->adCampo('demanda_moeda');
	$sql->adOnde('demanda_id ='.(int)$demanda_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($programa_id)  {
	$endereco='programa_id='.$programa_id;
	
	$sql->adTabela('programa');
	$sql->adCampo('programa_moeda');
	$sql->adOnde('programa_id ='.(int)$programa_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($licao_id)  {
	$endereco='licao_id='.$licao_id;
	
	$sql->adTabela('licao');
	$sql->adCampo('licao_moeda');
	$sql->adOnde('licao_id ='.(int)$licao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($evento_id)  {
	$endereco='evento_id='.$evento_id;
	
	$sql->adTabela('eventos');
	$sql->adCampo('evento_moeda');
	$sql->adOnde('evento_id ='.(int)$evento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($link_id)  {
	$endereco='link_id='.$link_id;
	
	$sql->adTabela('links');
	$sql->adCampo('link_moeda');
	$sql->adOnde('link_id ='.(int)$link_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($avaliacao_id)  {
	$endereco='avaliacao_id='.$avaliacao_id;
	
	$sql->adTabela('avaliacao');
	$sql->adCampo('avaliacao_moeda');
	$sql->adOnde('avaliacao_id ='.(int)$avaliacao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($tgn_id)  {
	$endereco='tgn_id='.$tgn_id;
	
	$sql->adTabela('tgn');
	$sql->adCampo('tgn_moeda');
	$sql->adOnde('tgn_id ='.(int)$tgn_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($brainstorm_id)  {
	$endereco='brainstorm_id='.$brainstorm_id;
	
	$sql->adTabela('brainstorm');
	$sql->adCampo('brainstorm_moeda');
	$sql->adOnde('brainstorm_id ='.(int)$brainstorm_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($gut_id)  {
	$endereco='gut_id='.$gut_id;
	
	$sql->adTabela('gut');
	$sql->adCampo('gut_moeda');
	$sql->adOnde('gut_id ='.(int)$gut_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($causa_efeito_id)  {
	$endereco='causa_efeito_id='.$causa_efeito_id;
	
	$sql->adTabela('causa_efeito');
	$sql->adCampo('causa_efeito_moeda');
	$sql->adOnde('causa_efeito_id ='.(int)$causa_efeito_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($arquivo_id)  {
	$endereco='arquivo_id='.$arquivo_id;
	
	$sql->adTabela('arquivo');
	$sql->adCampo('arquivo_moeda');
	$sql->adOnde('arquivo_id ='.(int)$arquivo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($forum_id)  {
	$endereco='forum_id='.$forum_id;
	
	$sql->adTabela('foruns');
	$sql->adCampo('forum_moeda');
	$sql->adOnde('forum_id ='.(int)$forum_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($checklist_id)  {
	$endereco='checklist_id='.$checklist_id;
	
	$sql->adTabela('checklist');
	$sql->adCampo('checklist_moeda');
	$sql->adOnde('checklist_id ='.(int)$checklist_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($agenda_id)  {
	$endereco='agenda_id='.$agenda_id;
	
	$sql->adTabela('agenda');
	$sql->adCampo('agenda_moeda');
	$sql->adOnde('agenda_id ='.(int)$agenda_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($agrupamento_id)  {
	$endereco='agrupamento_id='.$agrupamento_id;
	
	$sql->adTabela('agrupamento');
	$sql->adCampo('agrupamento_moeda');
	$sql->adOnde('agrupamento_id ='.(int)$agrupamento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($patrocinador_id)  {
	$endereco='patrocinador_id='.$patrocinador_id;
	
	$sql->adTabela('patrocinadores');
	$sql->adCampo('patrocinador_moeda');
	$sql->adOnde('patrocinador_id ='.(int)$patrocinador_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($template_id)  {
	$endereco='template_id='.$template_id;
	
	$sql->adTabela('template');
	$sql->adCampo('template_moeda');
	$sql->adOnde('template_id ='.(int)$template_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_id)  {
	$endereco='painel_id='.$painel_id;
	
	$sql->adTabela('painel');
	$sql->adCampo('painel_moeda');
	$sql->adOnde('painel_id ='.(int)$painel_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_odometro_id)  {
	$endereco='painel_odometro_id='.$painel_odometro_id;
	
	$sql->adTabela('painel_odometro');
	$sql->adCampo('painel_odometro_moeda');
	$sql->adOnde('painel_odometro_id ='.(int)$painel_odometro_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_composicao_id)  {
	$endereco='painel_composicao_id='.$painel_composicao_id;
	
	$sql->adTabela('painel_composicao');
	$sql->adCampo('painel_composicao_moeda');
	$sql->adOnde('painel_composicao_id ='.(int)$painel_composicao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($tr_id)  {
	$endereco='tr_id='.$tr_id;
	
	$sql->adTabela('tr');
	$sql->adCampo('tr_moeda');
	$sql->adOnde('tr_id ='.(int)$tr_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($me_id)  {
	$endereco='me_id='.$me_id;
	$sql->adTabela('me');
	$sql->adCampo('me_moeda');
	$sql->adOnde('me_id ='.(int)$me_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}

elseif ($plano_acao_item_id)  {
	$endereco='plano_acao_item_id='.$plano_acao_item_id;
	$sql->adTabela('plano_acao_item');
	$sql->adCampo('plano_acao_item_moeda');
	$sql->adOnde('plano_acao_item_id ='.(int)$plano_acao_item_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
elseif ($beneficio_id)  {
	$endereco='beneficio_id='.$beneficio_id;
	$sql->adTabela('beneficio');
	$sql->adCampo('beneficio_moeda');
	$sql->adOnde('beneficio_id ='.(int)$beneficio_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
elseif ($painel_slideshow_id)  {
	$endereco='painel_slideshow_id='.$painel_slideshow_id;
	$sql->adTabela('painel_slideshow');
	$sql->adCampo('painel_slideshow_moeda');
	$sql->adOnde('painel_slideshow_id ='.(int)$painel_slideshow_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
elseif ($projeto_viabilidade_id)  {
	$endereco='projeto_viabilidade_id='.$projeto_viabilidade_id;
	$sql->adTabela('projeto_viabilidade');
	$sql->adCampo('projeto_viabilidade_moeda');
	$sql->adOnde('projeto_viabilidade_id ='.(int)$projeto_viabilidade_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
elseif ($projeto_abertura_id)  {
	$endereco='projeto_abertura_id='.$projeto_abertura_id;
	$sql->adTabela('projeto_abertura');
	$sql->adCampo('projeto_abertura_moeda');
	$sql->adOnde('projeto_abertura_id ='.(int)$projeto_abertura_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
elseif ($pg_id)  {
	$endereco='pg_id='.$pg_id;
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_moeda');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}						
elseif ($ssti_id)  {
	$endereco='ssti_id='.$ssti_id;
	$sql->adTabela('ssti');
	$sql->adCampo('ssti_moeda');
	$sql->adOnde('ssti_id ='.(int)$ssti_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($laudo_id)  {
	$endereco='laudo_id='.$laudo_id;
	$sql->adTabela('laudo');
	$sql->adCampo('laudo_moeda');
	$sql->adOnde('laudo_id ='.(int)$laudo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($trelo_id)  {
	$endereco='trelo_id='.$trelo_id;
	$sql->adTabela('trelo');
	$sql->adCampo('trelo_moeda');
	$sql->adOnde('trelo_id ='.(int)$trelo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}			
elseif ($trelo_cartao_id)  {
	$endereco='trelo_cartao_id='.$trelo_cartao_id;
	$sql->adTabela('trelo_cartao');
	$sql->adCampo('trelo_cartao_moeda');
	$sql->adOnde('trelo_cartao_id ='.(int)$trelo_cartao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pdcl_id)  {
	$endereco='pdcl_id='.$pdcl_id;
	$sql->adTabela('pdcl');
	$sql->adCampo('pdcl_moeda');
	$sql->adOnde('pdcl_id ='.(int)$pdcl_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pdcl_item_id)  {
	$endereco='pdcl_item_id='.$pdcl_item_id;
	$sql->adTabela('pdcl_item');
	$sql->adCampo('pdcl_item_moeda');
	$sql->adOnde('pdcl_item_id ='.(int)$pdcl_item_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
	
	
	
	
	
	
else {
	$objeto_moeda=1;
	$endereco='';
	}
$endereco='m='.$m.'&a='.$a.($endereco ? '&'.$endereco : '');
$divisor_cotacao=($objeto_moeda > 1 ? cotacao($objeto_moeda, date('Y-m-d')) : 1);

$sql->adTabela('log');
$sql->esqUnir('tarefas','t', 'log.log_tarefa=t.tarefa_id');
$sql->adCampo('count(DISTINCT log_id)');
if ($projeto_id) $sql->adOnde('tarefa_projeto IN ('.$projeto_id.')');
elseif ($pg_perspectiva_id) $sql->adOnde('log.log_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('log.log_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('log.log_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('log.log_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('log.log_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('log.log_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('log.log_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('log.log_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('log.log_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('log.log_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('log.log_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('log.log_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('log.log_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('log.log_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('log.log_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('log.log_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('log.log_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('log.log_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('log.log_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('log.log_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('log.log_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('log.log_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('log.log_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('log.log_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('log.log_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('log.log_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('log.log_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('log.log_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('log.log_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('log.log_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('log.log_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('log.log_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('log.log_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('log.log_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('log.log_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('log.log_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('log.log_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('log.log_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('log.log_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('log.log_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('log.log_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('log.log_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('log.log_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('log.log_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('log.log_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('log.log_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('log.log_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('log.log_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('log.log_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('log.log_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('log.log_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('log.log_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('log.log_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('log.log_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('log.log_pdcl_item IN ('.$pdcl_item_id.')');
if ($usuario_id) $sql->adOnde('log.log_criador = '.(int)$usuario_id);
if ($pesquisar_texto) $sql->adOnde('log.log_nome LIKE \'%'.$pesquisar_texto.'%\' OR log.log_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$xtotalregistros = $sql->Resultado();






$sql->adTabela('log');
$sql->esqUnir('log', 'log_corrigiu', 'log_corrigiu.log_id=log.log_id');
$sql->esqUnir('tarefas','t', 'log.log_tarefa=t.tarefa_id');
$sql->esqUnir('log', 'log_corrigido', 'log_corrigido.log_correcao=log.log_id');
$sql->adCampo('log.*, concatenar_tres(formatar_data(log_corrigiu.log_data, "%d/%m/%Y"), \' - \', log_corrigiu.log_nome) AS corrigido, concatenar_tres(formatar_data(log_corrigido.log_data, "%d/%m/%Y"), \' - \', log_corrigido.log_nome) AS corrigiu');
if ($projeto_id) $sql->adOnde('tarefa_projeto IN ('.$projeto_id.')');
elseif ($pg_perspectiva_id) $sql->adOnde('log.log_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('log.log_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('log.log_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('log.log_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('log.log_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('log.log_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('log.log_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('log.log_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('log.log_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('log.log_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('log.log_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('log.log_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('log.log_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('log.log_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('log.log_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('log.log_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('log.log_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('log.log_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('log.log_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('log.log_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('log.log_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('log.log_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('log.log_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('log.log_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('log.log_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('log.log_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('log.log_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('log.log_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('log.log_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('log.log_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('log.log_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('log.log_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('log.log_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('log.log_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('log.log_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('log.log_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('log.log_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('log.log_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('log.log_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('log.log_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('log.log_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('log.log_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('log.log_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('log.log_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('log.log_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('log.log_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('log.log_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('log.log_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('log.log_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('log.log_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('log.log_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('log.log_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('log.log_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('log.log_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('log.log_pdcl_item IN ('.$pdcl_item_id.')');


if ($usuario_id) $sql->adOnde('log.log_criador = '.(int)$usuario_id);
if ($pesquisar_texto) $sql->adOnde('log.log_nome LIKE \'%'.$pesquisar_texto.'%\' OR log.log_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$sql->adOrdem($ordenacao);
$sql->setLimite($xmin, $xtamanhoPagina);
$sql->adGrupo('log.log_id');

$logs = $sql->Lista();	
	


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Registros', 'Registro','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inserção do registro.').($ordenar=='log_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Nome do registro.').($ordenar=='log_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Título'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Observação', 'Observação sobre o registro.').($ordenar=='log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Observação'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_criador&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pela inserção do registro.').($ordenar=='log_criador' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável'.dicaF().'</a></th>';


echo '<th>'.dica('Relacionado', 'A qual objeto o registro de ocorrência está relacionadoo.').'Relacionado'.dicaF().'</th>';



echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Referência', 'A forma como se chegou aos dados que estão registrandos.').($ordenar=='log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').'Ref.'.dicaF().'</a></th>';
echo '<th>'.dica('Custos', 'Custos planejados no registro.').'Custos'.dicaF().'</th>';
echo '<th>'.dica('Gastos', 'Gastos efetuados no registro.').'Gastos'.dicaF().'</a></th>';
echo '</tr>';




$qnt=0;

foreach ($logs as $linha) {
	$qnt++;
	
	if ($linha['log_correcao']) $estilo='background-color:#a1fb99;color:#000000';
	else if ($linha['corrigiu'])	$estilo='background-color:#e9ea87;color:#000000';
	else if ($linha['log_corrigir']) $estilo='background-color:#cc6666;color:#ffffff';
	else $estilo='';


	$sql->adTabela('log_arquivo');
	$sql->adCampo('count(log_arquivo_id)');
	$sql->adOnde('log_arquivo_log='.(int)$linha['log_id']);
	$arquivos=$sql->Resultado();
	$sql->limpar();
	$plural=($arquivos > 1 ? 's' : '');
	$anexo=($arquivos ? '<a href="javascript:popArquivos('.$linha['log_id'].');" >'.imagem('icones/clip.png','Anexo'.$plural,'Clique neste ícone '.imagem('icones/clip.png').' para visualizar o'.$plural.($plural=='s' ? ' '.$arquivos : '').' anexo'.$plural.'.').'</a>' : '');
	
	
	$imagem_referencia = '-';
	if ($linha['log_referencia'] > 0) {
		if (isset($RefRegistroAcaoImagem[$linha['log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroAcaoImagem[$linha['log_referencia']], $RefRegistroAcao[$linha['log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroAcao[$linha['log_referencia']])) $imagem_referencia = $RefRegistroAcao[$linha['log_referencia']];
		}
	

	echo '<tr bgcolor="white" valign="top">';
	echo '<td style="white-space: nowrap" valign="middle" valign="middle" width=16>'.retorna_data($linha['log_data'], false).'</td>';
	echo '<td style="white-space: nowrap;'.$estilo.'" valign="middle">'.($linha['corrigido'] ? dica('Registro em que Solucionou Problema', $linha['corrigido']) : '').($linha['corrigiu'] ? dica('Registro Responsável pela Solução', $linha['corrigiu']) : '').($linha['log_nome'] ? $linha['log_nome'] : '&nbsp;').($linha['corrigiu'] || $linha['corrigido'] ? dicaF() : '').$anexo.'</td>';
	echo '<td valign="middle">'.($linha['log_descricao'] ? str_replace("\n", '<br />', $linha['log_descricao']) : '&nbsp;').'</td>';
	echo '<td style="white-space: nowrap" valign="middle">'.link_usuario($linha['log_criador'],'','','esquerda').'</td>';
	
	
	echo '<td>';
	
	if ($linha['log_tarefa']) echo imagem('icones/tarefa_p.gif').link_tarefa($linha['log_tarefa']);
	elseif ($linha['log_projeto']) echo imagem('icones/projeto_p.gif').link_projeto($linha['log_projeto']);
	elseif ($linha['log_pratica']) echo imagem('icones/pratica_p.gif').link_pratica($linha['log_pratica']);
	elseif ($linha['log_acao']) echo imagem('icones/plano_acao_p.gif').link_acao($linha['log_acao']);
	elseif ($linha['log_perspectiva']) echo imagem('icones/perspectiva_p.png').link_perspectiva($linha['log_perspectiva']);
	elseif ($linha['log_tema']) echo imagem('icones/tema_p.png').link_tema($linha['log_tema']);
	elseif ($linha['log_objetivo']) echo imagem('icones/obj_estrategicos_p.gif').link_objetivo($linha['log_objetivo']);
	elseif ($linha['log_fator']) echo imagem('icones/fator_p.gif').link_fator($linha['log_fator']);
	elseif ($linha['log_estrategia']) echo imagem('icones/estrategia_p.gif').link_estrategia($linha['log_estrategia']);
	elseif ($linha['log_meta']) echo imagem('icones/meta_p.gif').link_meta($linha['log_meta']);
	elseif ($linha['log_canvas']) echo imagem('icones/canvas_p.png').link_canvas($linha['log_canvas']);
	elseif ($linha['log_risco']) echo imagem('icones/risco_p.png').link_risco($linha['log_risco']);
	elseif ($linha['log_risco_resposta']) echo imagem('icones/risco_resposta_p.png').link_risco_resposta($linha['log_risco_resposta']);
	elseif ($linha['log_indicador']) echo imagem('icones/indicador_p.gif').link_indicador($linha['log_indicador']);
	elseif ($linha['log_calendario']) echo imagem('icones/calendario_p.png').link_calendario($linha['log_calendario']);
	elseif ($linha['log_monitoramento']) echo imagem('icones/monitoramento_p.gif').link_monitoramento($linha['log_monitoramento']);
	elseif ($linha['log_ata']) echo imagem('icones/ata_p.png').link_ata_pro($linha['log_ata']);
	elseif ($linha['log_mswot']) echo imagem('icones/mswot_p.png').link_mswot($linha['log_mswot']);
	elseif ($linha['log_swot']) echo imagem('icones/swot_p.png').link_swot($linha['log_swot']);
	elseif ($linha['log_operativo']) echo imagem('icones/operativo_p.png').link_operativo($linha['log_operativo']);
	elseif ($linha['log_instrumento']) echo imagem('icones/instrumento_p.png').link_instrumento($linha['log_instrumento']);
	elseif ($linha['log_recurso']) echo imagem('icones/recursos_p.gif').link_recurso($linha['log_recurso']);
	elseif ($linha['log_problema']) echo imagem('icones/problema_p.png').link_problema($linha['log_problema']);
	elseif ($linha['log_demanda']) echo imagem('icones/demanda_p.gif').link_demanda($linha['log_demanda']);
	elseif ($linha['log_programa']) echo imagem('icones/programa_p.png').link_programa($linha['log_programa']);
	elseif ($linha['log_licao']) echo imagem('icones/licoes_p.gif').link_licao($linha['log_licao']);
	elseif ($linha['log_evento']) echo imagem('icones/calendario_p.png').link_evento($linha['log_evento']);
	elseif ($linha['log_link']) echo imagem('icones/links_p.gif').link_link($linha['log_link']);
	elseif ($linha['log_avaliacao']) echo imagem('icones/avaliacao_p.gif').link_avaliacao($linha['log_avaliacao']);
	elseif ($linha['log_tgn']) echo imagem('icones/tgn_p.png').link_tgn($linha['log_tgn']);
	elseif ($linha['log_brainstorm']) echo imagem('icones/brainstorm_p.gif').link_brainstorm($linha['log_brainstorm']);
	elseif ($linha['log_gut']) echo imagem('icones/gut_p.gif').link_gut($linha['log_gut']);
	elseif ($linha['log_causa_efeito']) echo imagem('icones/causaefeito_p.png').link_causa_efeito($linha['log_causa_efeito']);
	elseif ($linha['log_arquivo']) echo imagem('icones/arquivo_p.png').link_arquivo($linha['log_arquivo']);
	elseif ($linha['log_forum']) echo imagem('icones/forum_p.gif').link_forum($linha['log_forum']);
	elseif ($linha['log_checklist']) echo imagem('icones/todo_list_p.png').link_checklist($linha['log_checklist']);
	elseif ($linha['log_agenda']) echo imagem('icones/calendario_p.png').link_agenda($linha['log_agenda']);
	elseif ($linha['log_agrupamento']) echo imagem('icones/agrupamento_p.png').link_agrupamento($linha['log_agrupamento']);
	elseif ($linha['log_patrocinador']) echo imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($linha['log_patrocinador']);
	elseif ($linha['log_template']) echo imagem('icones/template_p.gif').link_template($linha['log_template']);
	elseif ($linha['log_painel']) echo imagem('icones/indicador_p.gif').link_painel($linha['log_painel']);
	elseif ($linha['log_painel_odometro']) echo imagem('icones/odometro_p.png').link_painel_odometro($linha['log_painel_odometro']);
	elseif ($linha['log_painel_composicao']) echo imagem('icones/composicao_p.gif').link_painel_composicao($linha['log_painel_composicao']);		
	elseif ($linha['log_tr']) echo imagem('icones/tr_p.png').link_tr($linha['log_tr']);	
	elseif ($linha['log_me']) echo imagem('icones/me_p.png').link_me($linha['log_me']);
	elseif ($linha['log_acao_item']) echo imagem('icones/acao_item_p.png').link_acao_item($linha['log_acao_item']);	
	elseif ($linha['log_beneficio']) echo imagem('icones/beneficio_p.png').link_beneficio($linha['log_beneficio']);	
	elseif ($linha['log_painel_slideshow']) echo imagem('icones/slideshow_p.gif').link_painel_slideshow($linha['log_painel_slideshow']);	
	elseif ($linha['log_projeto_viabilidade']) echo imagem('icones/viabilidade_p.gif').link_viabilidade($linha['log_projeto_viabilidade']);	
	elseif ($linha['log_projeto_abertura']) echo imagem('icones/anexo_projeto_p.png').link_termo_abertura($linha['log_projeto_abertura']);	
	elseif ($linha['log_plano_gestao']) echo imagem('icones/planogestao_p.png').link_plano_gestao($linha['log_plano_gestao']);	
	elseif ($linha['log_ssti']) echo imagem('icones/ssti_p.png').link_ssti($linha['log_ssti']);
	elseif ($linha['log_laudo']) echo imagem('icones/laudo_p.png').link_laudo($linha['log_laudo']);
	elseif ($linha['log_trelo']) echo imagem('icones/trelo_p.png').link_trelo($linha['log_trelo']);
	elseif ($linha['log_trelo_cartao']) echo imagem('icones/trelo_cartao_p.png').link_trelo_cartao($linha['log_trelo_cartao']);
	elseif ($linha['log_pdcl']) echo imagem('icones/pdcl_p.png').link_pdcl($linha['log_pdcl']);
	elseif ($linha['log_pdcl_item']) echo imagem('icones/pdcl_item_p.png').link_pdcl_item($linha['log_pdcl_item']);
	echo '</td>';
	
	
	
	echo '<td align="center" valign="middle" width=16>'.$imagem_referencia.'</td>';


	$sql->adTabela('custo');
	$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS valor');
	$sql->adOnde('custo_log ='.(int)$linha['log_id']);	
	$sql->adOnde('custo_gasto!=1');
	$custo=$sql->Resultado();
	$sql->limpar();

	$sql->adTabela('custo');
	$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS valor');
	$sql->adOnde('custo_log ='.(int)$linha['log_id']);	
	$sql->adOnde('custo_gasto=1');
	$gasto=$sql->Resultado();
	$sql->limpar();
	
	echo '<td width=16 align="right" valign="middle" style="white-space: nowrap">'.($custo ? $moedas[$objeto_moeda].' '.number_format($custo/$divisor_cotacao, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:planilha_custo('.$linha['log_id'].', 0)">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a>' : '&nbsp;').'</td>';
	echo '<td width=16 align="right" valign="middle" style="white-space: nowrap">'.($gasto ? $moedas[$objeto_moeda].' '.number_format($gasto/$divisor_cotacao, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:planilha_custo('.$linha['log_id'].', 1)">'.dica('Planilha de Gastos Efetuados', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos efetuados.').imagem('icones/planilha_gasto.gif').dicaF().'</a>' : '&nbsp;').'</td>';
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td bgcolor="white" colspan=20><p>Nenhum registro de ocorrência encontrado.</p></td></tr>';	
echo '</table>';


if (!$dialogo && $qnt){	
	echo '<table class="std" width=100%><tr><td style="width:80px;">Legenda:</td>';
	echo '<td style="width:32px;">&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px; width:16px;">&nbsp; &nbsp;</td><td style="width:70px;">'.dica('Registro Normal', 'Todos os registros que não forem marcados como tendo problema serão considerados normais.').'Normal'.dicaF().'</td>';
	echo '<td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px; width:16px;">&nbsp; &nbsp;</td><td style="width:80px;">'.dica('Registro de Problema', 'Todos os registros que forem marcados como tendo problema aparecerão com o sumário na cor vermelha.').'Problema'.dicaF().'</td>';
	echo '<td bgcolor="#e9ea87" style="border-style:solid;	border-width:1px 1px 1px 1px; width:16px;">&nbsp; &nbsp;</td><td style="width:150px;">'.dica('Problema Solucionado', 'Todos os registros marcados como problema em que outro registro tenha sido marcado como tendo solicionado estes problemas.').'Problema Solucionado'.dicaF().'</td>';
	echo '<td bgcolor="#a1fb99" style="border-style:solid;	border-width:1px 1px 1px 1px; width:16px;">&nbsp; &nbsp;</td><td style="width:120px;">'.dica('Solucionou Problema', 'Todos os registros que forem marcados como tendo solucionado problema de outro registro.').'Solucionou Problema'.dicaF().'</td>';
	echo '<td>&nbsp;</td>';
	echo '</tr></table>';
	}
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';



?>



<script type="text/javascript">

function planilha_custo(log_id, gasto){
	parent.gpwebApp.popUp('Planilha de '+(gasto ? 'Gasto' : 'Custo'), 1000, 500, 'm=praticas&a=log_custo&log_id='+log_id+'&gasto='+gasto, null, window);
	}

function popArquivos(log_id){
	parent.gpwebApp.popUp("Arquivos", 400, 400, "m=praticas&a=log_anexos&dialogo=1&log_id="+log_id, null, window);
	}
	
function popUsuario(campo) {
	
	parent.gpwebApp.popUp("Usuário", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, setUsuario, window);
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	frmFiltro.submit();
	}	

	
function excluir2(id) {
	if (confirm( 'Tem certeza que deseja excluir o registro da ocorrência?' )) {
		document.frmExcluir2.log_id.value = id;
		document.frmExcluir2.submit();
		}
	}
</script>
