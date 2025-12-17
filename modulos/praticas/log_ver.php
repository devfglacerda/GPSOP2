<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
 
global $Aplic, $dialogo, $podeExcluir, $m, $tab, 
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

echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="objetivo_id" id="objetivo_id" value="'.$objetivo_id.'" />';
echo '<input type="hidden" name="fator_id" id="fator_id" value="'.$fator_id.'" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="risco_id" id="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" id="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" id="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="monitoramento_id" id="monitoramento_id" value="'.$monitoramento_id.'" />';
echo '<input type="hidden" name="ata_id" id="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="mswot_id" id="mswot_id" value="'.$mswot_id.'" />';
echo '<input type="hidden" name="swot_id" id="swot_id" value="'.$swot_id.'" />';
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
echo '<input type="hidden" name="plano_acao_item_id" id="plano_acao_item_id" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" name="beneficio_id" id="beneficio_id" value="'.$beneficio_id.'" />';
echo '<input type="hidden" name="painel_slideshow_id" id="painel_slideshow_id" value="'.$painel_slideshow_id.'" />';
echo '<input type="hidden" name="projeto_viabilidade_id" id="projeto_viabilidade_id" value="'.$projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="projeto_abertura_id" id="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="pg_id" id="pg_id" value="'.$pg_id.'" />';
echo '<input type="hidden" name="ssti_id" id="ssti_id" value="'.$ssti_id.'" />';
echo '<input type="hidden" name="laudo_id" id="laudo_id" value="'.$laudo_id.'" />';
echo '<input type="hidden" name="trelo_id" id="trelo_id" value="'.$trelo_id.'" />';
echo '<input type="hidden" name="trelo_cartao_id" id="trelo_cartao_id" value="'.$trelo_cartao_id.'" />';
echo '<input type="hidden" name="pdcl_id" id="pdcl_id" value="'.$pdcl_id.'" />';
echo '<input type="hidden" name="pdcl_item_id" id="pdcl_item_id" value="'.$pdcl_item_id.'" />';
if ($tarefa_id) {
	$endereco='m=tarefas&a=ver&tab=0&tarefa_id='.(int)$tarefa_id; 
	
	$sql->adTabela('projetos');
	$sql->esqUnir('tarefas','tarefas', 'projetos.projeto_id=tarefas.tarefa_projeto');
	$sql->adCampo('projeto_moeda');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($projeto_id)  {
	$endereco='m=projetos&a=ver&tab=0&projeto_id='.(int)$projeto_id; 	
	
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_moeda');
	$sql->adOnde('projeto_id ='.(int)$projeto_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pratica_indicador_id)  {
	$endereco='m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.(int)$pratica_indicador_id; 
	
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_moeda');
	$sql->adOnde('pratica_indicador_id ='.(int)$pratica_indicador_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pratica_id)  {
	$endereco='m=praticas&a=pratica_ver&tab=0&pratica_id='.(int)$pratica_id; 
	
	$sql->adTabela('praticas');
	$sql->adCampo('pratica_moeda');
	$sql->adOnde('pratica_id ='.(int)$pratica_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_perspectiva_id)  {
	$endereco='m=praticas&a=perspectiva_ver&tab=0&pg_perspectiva_id='.(int)$pg_perspectiva_id;
	
	$sql->adTabela('perspectivas');
	$sql->adCampo('pg_perspectiva_moeda');
	$sql->adOnde('pg_perspectiva_id ='.(int)$pg_perspectiva_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($tema_id)  {
	$endereco='m=praticas&a=tema_ver&tab=0&tema_id='.(int)$tema_id;
	
	$sql->adTabela('tema');
	$sql->adCampo('tema_moeda');
	$sql->adOnde('tema_id ='.(int)$tema_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($objetivo_id)  {
	$endereco='m=praticas&a=obj_estrategico_ver&tab=0&objetivo_id='.(int)$objetivo_id;
	
	$sql->adTabela('objetivo');
	$sql->adCampo('objetivo_moeda');
	$sql->adOnde('objetivo_id ='.(int)$objetivo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($fator_id)  {
	$endereco='m=praticas&a=fator_ver&tab=0&fator_id='.(int)$fator_id;
	
	$sql->adTabela('fator');
	$sql->adCampo('fator_moeda');
	$sql->adOnde('fator_id ='.(int)$fator_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_estrategia_id)  {
	$endereco='m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.(int)$pg_estrategia_id;
	
	$sql->adTabela('estrategias');
	$sql->adCampo('pg_estrategia_moeda');
	$sql->adOnde('pg_estrategia_id ='.(int)$pg_estrategia_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($plano_acao_id)  {
	$endereco='m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.(int)$plano_acao_id;
	
	$sql->adTabela('plano_acao');
	$sql->adCampo('plano_acao_moeda');
	$sql->adOnde('plano_acao_id ='.(int)$plano_acao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_meta_id)  {
	$endereco='m=praticas&a=meta_ver&tab=0&pg_meta_id='.(int)$pg_meta_id;
	
	$sql->adTabela('metas');
	$sql->adCampo('pg_meta_moeda');
	$sql->adOnde('pg_meta_id ='.(int)$pg_meta_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($canvas_id)  {
	$endereco='m=praticas&a=canvas_pro_ver&tab=0&canvas_id='.(int)$canvas_id;
	
	$sql->adTabela('canvas');
	$sql->adCampo('canvas_moeda');
	$sql->adOnde('canvas_id ='.(int)$canvas_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($risco_id)  {
	$endereco='m=praticas&a=risco_pro_ver&tab=0&risco_id='.(int)$risco_id;
	
	$sql->adTabela('risco');
	$sql->adCampo('risco_moeda');
	$sql->adOnde('risco_id ='.(int)$risco_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($risco_resposta_id)  {
	$endereco='m=praticas&a=risco_resposta_pro_ver&tab=0&risco_resposta_id='.(int)$risco_resposta_id;
	
	$sql->adTabela('risco_resposta');
	$sql->adCampo('risco_resposta_moeda');
	$sql->adOnde('risco_resposta_id ='.(int)$risco_resposta_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($problema_id)  {
	$endereco='m=problema&a=problema_ver&tab=0&problema_id='.(int)$problema_id;
	
	$sql->adTabela('problema');
	$sql->adCampo('problema_moeda');
	$sql->adOnde('problema_id ='.(int)$problema_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($calendario_id)  {
	$endereco='m=sistema&u=calendario&a=calendario_ver&tab=0&calendario_id='.(int)$calendario_id;
	
	$sql->adTabela('calendario');
	$sql->adCampo('calendario_moeda');
	$sql->adOnde('calendario_id ='.(int)$calendario_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($monitoramento_id)  {
	$endereco='m=praticas&a=monitoramento_ver_pro&tab=0&monitoramento_id='.(int)$monitoramento_id;
	
	$sql->adTabela('monitoramento');
	$sql->adCampo('monitoramento_moeda');
	$sql->adOnde('monitoramento_id ='.(int)$monitoramento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($ata_id)  {
	$endereco='m=atas&a=ata_ver&tab=0&ata_id='.(int)$ata_id;
	
	$sql->adTabela('ata');
	$sql->adCampo('ata_moeda');
	$sql->adOnde('ata_id ='.(int)$ata_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($mswot_id)  {
	$endereco='m=swot&a=mswot_ver&tab=0&mswot_id='.(int)$mswot_id;
	
	$sql->adTabela('mswot');
	$sql->adCampo('mswot_moeda');
	$sql->adOnde('mswot_id ='.(int)$mswot_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($swot_id)  {
	$endereco='m=swot&a=swot_ver&tab=0&swot_id='.(int)$swot_id;
	
	$sql->adTabela('swot');
	$sql->adCampo('swot_moeda');
	$sql->adOnde('swot_id ='.(int)$swot_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($operativo_id)  {
	$endereco='m=operativo&a=operativo_ver&tab=0&operativo_id='.(int)$operativo_id;
	
	$sql->adTabela('operativo');
	$sql->adCampo('operativo_moeda');
	$sql->adOnde('operativo_id ='.(int)$operativo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($instrumento_id)  {
	$endereco='m=instrumento&a=instrumento_ver&tab=0&instrumento_id='.(int)$instrumento_id;
	
	$sql->adTabela('instrumento');
	$sql->adCampo('instrumento_moeda');
	$sql->adOnde('instrumento_id ='.(int)$instrumento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($recurso_id)  {
	$endereco='m=recursos&a=ver&tab=0&recurso_id='.(int)$recurso_id;
	
	$sql->adTabela('recursos');
	$sql->adCampo('recurso_moeda');
	$sql->adOnde('recurso_id ='.(int)$recurso_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($demanda_id)  {
	$endereco='m=projetos&a=demanda_ver&tab=0&demanda_id='.(int)$demanda_id;
	
	$sql->adTabela('demandas');
	$sql->adCampo('demanda_moeda');
	$sql->adOnde('demanda_id ='.(int)$demanda_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($programa_id)  {
	$endereco='m=projetos&a=programa_ver&tab=0&programa_id='.(int)$programa_id;
	
	$sql->adTabela('programa');
	$sql->adCampo('programa_moeda');
	$sql->adOnde('programa_id ='.(int)$programa_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($licao_id)  {
	$endereco='m=projetos&a=licao_ver&tab=0&licao_id='.(int)$licao_id;
	
	$sql->adTabela('licao');
	$sql->adCampo('licao_moeda');
	$sql->adOnde('licao_id ='.(int)$licao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($evento_id)  {
	$endereco='m=calendario&a=ver&tab=0&evento_id='.(int)$evento_id;
	
	$sql->adTabela('eventos');
	$sql->adCampo('evento_moeda');
	$sql->adOnde('evento_id ='.(int)$evento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($link_id)  {
	$endereco='m=links&a=ver&tab=0&link_id='.(int)$link_id;
	
	$sql->adTabela('links');
	$sql->adCampo('link_moeda');
	$sql->adOnde('link_id ='.(int)$link_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($avaliacao_id)  {
	$endereco='m=praticas&a=avaliacao_ver&tab=0&avaliacao_id='.(int)$avaliacao_id;
	
	$sql->adTabela('avaliacao');
	$sql->adCampo('avaliacao_moeda');
	$sql->adOnde('avaliacao_id ='.(int)$avaliacao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($tgn_id)  {
	$endereco='m=praticas&a=tgn_pro_ver&tab=0&tgn_id='.(int)$tgn_id;
	
	$sql->adTabela('tgn');
	$sql->adCampo('tgn_moeda');
	$sql->adOnde('tgn_id ='.(int)$tgn_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($brainstorm_id)  {
	$endereco='m=praticas&a=brainstorm_ver&tab=0&brainstorm_id='.(int)$brainstorm_id;
	
	$sql->adTabela('brainstorm');
	$sql->adCampo('brainstorm_moeda');
	$sql->adOnde('brainstorm_id ='.(int)$brainstorm_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($gut_id)  {
	$endereco='m=praticas&a=gut_ver&tab=0&gut_id='.(int)$gut_id;
	
	$sql->adTabela('gut');
	$sql->adCampo('gut_moeda');
	$sql->adOnde('gut_id ='.(int)$gut_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($causa_efeito_id)  {
	$endereco='m=praticas&a=causa_efeito_ver&tab=0&causa_efeito_id='.(int)$causa_efeito_id;
	
	$sql->adTabela('causa_efeito');
	$sql->adCampo('causa_efeito_moeda');
	$sql->adOnde('causa_efeito_id ='.(int)$causa_efeito_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($arquivo_id)  {
	$endereco='m=arquivos&a=ver&tab=0&arquivo_id='.(int)$arquivo_id;
	
	$sql->adTabela('arquivo');
	$sql->adCampo('arquivo_moeda');
	$sql->adOnde('arquivo_id ='.(int)$arquivo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($forum_id)  {
	$endereco='m=foruns&a=ver&tab=0&forum_id='.(int)$forum_id;
	
	$sql->adTabela('foruns');
	$sql->adCampo('forum_moeda');
	$sql->adOnde('forum_id ='.(int)$forum_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($checklist_id)  {
	$endereco='m=praticas&a=checklist_ver&tab=0&checklist_id='.(int)$checklist_id;
	
	$sql->adTabela('checklist');
	$sql->adCampo('checklist_moeda');
	$sql->adOnde('checklist_id ='.(int)$checklist_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($agenda_id)  {
	$endereco='m=email&a=ver_compromisso&tab=0&agenda_id='.(int)$agenda_id;
	
	$sql->adTabela('agenda');
	$sql->adCampo('agenda_moeda');
	$sql->adOnde('agenda_id ='.(int)$agenda_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($agrupamento_id)  {
	$endereco='m=agrupamento&a=agrupamento_ver&tab=0&agrupamento_id='.(int)$agrupamento_id;
	
	$sql->adTabela('agrupamento');
	$sql->adCampo('agrupamento_moeda');
	$sql->adOnde('agrupamento_id ='.(int)$agrupamento_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($patrocinador_id)  {
	$endereco='m=patrocinadores&a=patrocinador_ver&tab=0&patrocinador_id='.(int)$patrocinador_id;
	
	$sql->adTabela('patrocinadores');
	$sql->adCampo('patrocinador_moeda');
	$sql->adOnde('patrocinador_id ='.(int)$patrocinador_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($template_id)  {
	$endereco='m=projetos&a=template_pro_ver&tab=0&template_id='.(int)$template_id;
	
	$sql->adTabela('template');
	$sql->adCampo('template_moeda');
	$sql->adOnde('template_id ='.(int)$template_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_id)  {
	$endereco='m=praticas&a=painel_pro_ver&painel_id='.$painel_id;
	
	$sql->adTabela('painel');
	$sql->adCampo('painel_moeda');
	$sql->adOnde('painel_id ='.(int)$painel_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_odometro_id)  {
	$endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$painel_odometro_id;
	
	$sql->adTabela('painel_odometro');
	$sql->adCampo('painel_odometro_moeda');
	$sql->adOnde('painel_odometro_id ='.(int)$painel_odometro_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_composicao_id)  {
	$endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$painel_composicao_id;
	
	$sql->adTabela('painel_composicao');
	$sql->adCampo('painel_composicao_moeda');
	$sql->adOnde('painel_composicao_id ='.(int)$painel_composicao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($tr_id)  {
	$endereco='m=tr&a=tr_ver&tr_id='.$tr_id;
	
	$sql->adTabela('tr');
	$sql->adCampo('tr_moeda');
	$sql->adOnde('tr_id ='.(int)$tr_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($me_id)  {
	$endereco='m=praticas&a=me_ver_pro&me_id='.$me_id;
	$sql->adTabela('me');
	$sql->adCampo('me_moeda');
	$sql->adOnde('me_id ='.(int)$me_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($plano_acao_item_id)  {
	$endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$plano_acao_item_id;
	$sql->adTabela('plano_acao_item');
	$sql->adCampo('plano_acao_item_moeda');
	$sql->adOnde('plano_acao_item_id ='.(int)$plano_acao_item_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($beneficio_id)  {
	$endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id;
	$sql->adTabela('beneficio');
	$sql->adCampo('beneficio_moeda');
	$sql->adOnde('beneficio_id ='.(int)$beneficio_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($painel_slideshow_id)  {
	$endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$painel_slideshow_id;
	$sql->adTabela('painel_slideshow');
	$sql->adCampo('painel_slideshow_moeda');
	$sql->adOnde('painel_slideshow_id ='.(int)$painel_slideshow_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($projeto_viabilidade_id)  {
	$endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id;
	$sql->adTabela('projeto_viabilidade');
	$sql->adCampo('projeto_viabilidade_moeda');
	$sql->adOnde('projeto_viabilidade_id ='.(int)$projeto_viabilidade_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($projeto_abertura_id)  {
	$endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id;
	$sql->adTabela('projeto_abertura');
	$sql->adCampo('projeto_abertura_moeda');
	$sql->adOnde('projeto_abertura_id ='.(int)$projeto_abertura_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pg_id)  {
	$endereco='m=praticas&u=gestao&a=menu&pg_id='.$pg_id;
	$sql->adTabela('plano_gestao');
	$sql->adCampo('pg_moeda');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($ssti_id)  {
	$endereco='m=ssti&a=ssti_ver&ssti_id='.$ssti_id;
	$sql->adTabela('ssti');
	$sql->adCampo('ssti_moeda');
	$sql->adOnde('ssti_id ='.(int)$ssti_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($laudo_id)  {
	$endereco='m=ssti&a=laudo_ver&laudo_id='.$laudo_id;
	$sql->adTabela('laudo');
	$sql->adCampo('laudo_moeda');
	$sql->adOnde('laudo_id ='.(int)$laudo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($trelo_id)  {
	$endereco='m=trelo&a=trelo_ver&trelo_id='.$trelo_id;
	$sql->adTabela('trelo');
	$sql->adCampo('trelo_moeda');
	$sql->adOnde('trelo_id ='.(int)$trelo_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}			
elseif ($trelo_cartao_id)  {
	$endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$trelo_cartao_id;
	$sql->adTabela('trelo_cartao');
	$sql->adCampo('trelo_cartao_moeda');
	$sql->adOnde('trelo_cartao_id ='.(int)$trelo_cartao_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pdcl_id)  {
	$endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$pdcl_id;
	$sql->adTabela('pdcl');
	$sql->adCampo('pdcl_moeda');
	$sql->adOnde('pdcl_id ='.(int)$pdcl_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}
elseif ($pdcl_item_id)  {
	$endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$pdcl_item_id;
	$sql->adTabela('pdcl_item');
	$sql->adCampo('pdcl_item_moeda');
	$sql->adOnde('pdcl_item_id ='.(int)$pdcl_item_id);	
	$objeto_moeda=$sql->Resultado();
	$sql->limpar();
	}	
$divisor_cotacao=($objeto_moeda > 1 ? cotacao($objeto_moeda, date('Y-m-d')) : 1);


if (!$dialogo){
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo '<td width="98%">&nbsp;</td>';
	echo '<td width="1%" style="white-space: nowrap">'.dica('Filtro', 'Selecione de qual '.$config['usuario'].' deseja ver os registros de cadastrados.').'Filtro'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td>';
	echo '</tr></table>';
	}
else {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] =(int)$cia_id;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>Registro de Ocorrências</center></font>';
	
	}	
	
	
echo '</form>';


echo '<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inserção do registro.').($ordenar=='log_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Nome do registro.').($ordenar=='log_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome'.dicaF().'</a></th>';






echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_tipo_problema&ordem='.($ordem ? '0' : '1').'\');">'.dica('Motivo', 'Motivo do registro, no caso de problema.').($ordenar=='log_tipo_problema' ? imagem('icones/'.$seta[$ordem]) : '').'Motivo'.dicaF().'</a></th>';


if ($projeto_id){
	$logTipo = getSisValor('logTipo');
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_tipo&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tipo', 'Tipo de registro d'.$config['genero_projeto'].' '.$config['projeto'].'.').($ordenar=='log_tipo' ? imagem('icones/'.$seta[$ordem]) : '').'Tipo'.dicaF().'</a></th>';
	
	
	
	$projetoFase = getSisValor('projetoFase');
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_reg_mudanca_fase&ordem='.($ordem ? '0' : '1').'\');">'.dica('Fase', 'Fase d'.$config['genero_projeto'].' '.$config['projeto'].'.').($ordenar=='log_reg_mudanca_fase' ? imagem('icones/'.$seta[$ordem]) : '').'Fase'.dicaF().'</a></th>';
	$projStatus = getSisValor('StatusProjeto');
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_reg_mudanca_status&ordem='.($ordem ? '0' : '1').'\');">'.dica('Status', 'Status d'.$config['genero_projeto'].' '.$config['projeto'].'.').($ordenar=='log_reg_mudanca_status' ? imagem('icones/'.$seta[$ordem]) : '').'Status'.dicaF().'</a></th>';

	}


echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Observação', 'Observação sobre o registro.').($ordenar=='log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Observação'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_criador&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pela inserção do registro.').($ordenar=='log_criador' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \''.$endereco.'&ordenar=log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Referência', 'A forma como se chegou aos dados que estão registrandos.').($ordenar=='log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').'Ref.'.dicaF().'</a></th>';
echo '<th>'.dica('Custos', 'Custos planejados no registro.').'Custos'.dicaF().'</th>';
echo '<th>'.dica('Gastos', 'Gastos efetuados no registro.').'Gastos'.dicaF().'</a></th>';
if (!$dialogo) echo '<th>&nbsp;</th></tr>';


$sql->adTabela('log');
$sql->esqUnir('log', 'log_corrigiu', 'log_corrigiu.log_id=log.log_id');
$sql->esqUnir('log', 'log_corrigido', 'log_corrigido.log_correcao=log.log_id');
$sql->adCampo('log.*, concatenar_tres(formatar_data(log_corrigiu.log_data, "%d/%m/%Y"), \' - \', log_corrigiu.log_nome) AS corrigido, concatenar_tres(formatar_data(log_corrigido.log_data, "%d/%m/%Y"), \' - \', log_corrigido.log_nome) AS corrigiu');
if ($tarefa_id) $sql->adOnde('log.log_tarefa='.(int)$tarefa_id);
elseif ($projeto_id && !($m=='projetos' && $a=='ver')){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=log.log_tarefa');
	$sql->adOnde('log.log_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($projeto_id) $sql->adOnde('log.log_projeto='.(int)$projeto_id);	
elseif ($pg_perspectiva_id) $sql->adOnde('log.log_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('log.log_tema='.(int)$tema_id);
elseif ($objetivo_id) $sql->adOnde('log.log_objetivo='.(int)$objetivo_id);
elseif ($fator_id) $sql->adOnde('log.log_fator='.(int)$fator_id);
elseif ($pg_estrategia_id) $sql->adOnde('log.log_estrategia='.(int)$pg_estrategia_id);
elseif ($pg_meta_id) $sql->adOnde('log.log_meta='.(int)$pg_meta_id);
elseif ($pratica_id) $sql->adOnde('log.log_pratica='.(int)$pratica_id);
elseif ($pratica_indicador_id) $sql->adOnde('log.log_indicador='.(int)$pratica_indicador_id);
elseif ($plano_acao_id) $sql->adOnde('log.log_acao='.(int)$plano_acao_id);
elseif ($canvas_id) $sql->adOnde('log.log_canvas='.(int)$canvas_id);
elseif ($risco_id) $sql->adOnde('log.log_risco='.(int)$risco_id);
elseif ($risco_resposta_id) $sql->adOnde('log.log_risco_resposta='.(int)$risco_resposta_id);
elseif ($calendario_id) $sql->adOnde('log.log_calendario='.(int)$calendario_id);
elseif ($monitoramento_id) $sql->adOnde('log.log_monitoramento='.(int)$monitoramento_id);
elseif ($ata_id) $sql->adOnde('log.log_ata='.(int)$ata_id);
elseif ($mswot_id) $sql->adOnde('log.log_mswot='.(int)$mswot_id);
elseif ($swot_id) $sql->adOnde('log.log_swot='.(int)$swot_id);
elseif ($operativo_id) $sql->adOnde('log.log_operativo='.(int)$operativo_id);
elseif ($instrumento_id) $sql->adOnde('log.log_instrumento='.(int)$instrumento_id);
elseif ($recurso_id) $sql->adOnde('log.log_recurso='.(int)$recurso_id);
elseif ($problema_id) $sql->adOnde('log.log_problema='.(int)$problema_id);
elseif ($demanda_id) $sql->adOnde('log.log_demanda='.(int)$demanda_id);
elseif ($programa_id) $sql->adOnde('log.log_programa='.(int)$programa_id);
elseif ($licao_id) $sql->adOnde('log.log_licao='.(int)$licao_id);
elseif ($evento_id) $sql->adOnde('log.log_evento='.(int)$evento_id);
elseif ($link_id) $sql->adOnde('log.log_link='.(int)$link_id);
elseif ($avaliacao_id) $sql->adOnde('log.log_avaliacao='.(int)$avaliacao_id);
elseif ($tgn_id) $sql->adOnde('log.log_tgn='.(int)$tgn_id);
elseif ($brainstorm_id) $sql->adOnde('log.log_brainstorm='.(int)$brainstorm_id);
elseif ($gut_id) $sql->adOnde('log.log_gut='.(int)$gut_id);
elseif ($causa_efeito_id) $sql->adOnde('log.log_causa_efeito='.(int)$causa_efeito_id);
elseif ($arquivo_id) $sql->adOnde('log.log_arquivo='.(int)$arquivo_id);
elseif ($forum_id) $sql->adOnde('log.log_forum='.(int)$forum_id);
elseif ($checklist_id) $sql->adOnde('log.log_checklist='.(int)$checklist_id);
elseif ($agenda_id) $sql->adOnde('log.log_agenda='.(int)$agenda_id);
elseif ($agrupamento_id) $sql->adOnde('log.log_agrupamento='.(int)$agrupamento_id);
elseif ($patrocinador_id) $sql->adOnde('log.log_patrocinador='.(int)$patrocinador_id);
elseif ($template_id) $sql->adOnde('log.log_template='.(int)$template_id);
elseif ($painel_id) $sql->adOnde('log.log_painel='.(int)$painel_id);
elseif ($painel_odometro_id) $sql->adOnde('log.log_painel_odometro='.(int)$painel_odometro_id);
elseif ($painel_composicao_id) $sql->adOnde('log.log_painel_composicao='.(int)$painel_composicao_id);
elseif ($tr_id) $sql->adOnde('log.log_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('log.log_me='.(int)$me_id);
elseif ($plano_acao_item_id) $sql->adOnde('log.log_acao_item='.(int)$plano_acao_item_id);
elseif ($beneficio_id) $sql->adOnde('log.log_beneficio='.(int)$beneficio_id);
elseif ($painel_slideshow_id) $sql->adOnde('log.log_painel_slideshow='.(int)$painel_slideshow_id);
elseif ($projeto_viabilidade_id) $sql->adOnde('log.log_projeto_viabilidade='.(int)$projeto_viabilidade_id);
elseif ($projeto_abertura_id) $sql->adOnde('log.log_projeto_abertura='.(int)$projeto_abertura_id);
elseif ($pg_id) $sql->adOnde('log.log_plano_gestao='.(int)$pg_id);
elseif ($ssti_id) $sql->adOnde('log.log_ssti='.(int)$ssti_id);
elseif ($laudo_id) $sql->adOnde('log.log_laudo='.(int)$laudo_id);
elseif ($trelo_id) $sql->adOnde('log.log_trelo='.(int)$trelo_id);
elseif ($trelo_cartao_id) $sql->adOnde('log.log_trelo_cartao='.(int)$trelo_cartao_id);
elseif ($pdcl_id) $sql->adOnde('log.log_pdcl='.(int)$pdcl_id);
elseif ($pdcl_item_id) $sql->adOnde('log.log_pdcl_item='.(int)$pdcl_item_id);


if ($usuario_id) $sql->adOnde('log.log_criador = '.(int)$usuario_id);
$sql->adOrdem($ordenacao);
$logs = $sql->Lista();


$qnt=0;
$logTipoProblema = getSisValor('logTipoProblema');
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
	

	if ($pg_perspectiva_id) $podeEditar=permiteEditarPerspectiva($linha['log_acesso'], $pg_perspectiva_id);
	elseif ($tema_id) $podeEditar=permiteEditarTema($linha['log_acesso'], $tema_id);
	elseif ($objetivo_id) $podeEditar=permiteEditarObjetivo($linha['log_acesso'], $objetivo_id);
	elseif ($fator_id) $podeEditar=permiteEditarFator($linha['log_acesso'], $fator_id);
	elseif ($pg_estrategia_id) $podeEditar=permiteEditarEstrategia($linha['log_acesso'], $pg_estrategia_id);
	elseif ($pg_meta_id) $podeEditar=permiteEditarMeta($linha['log_acesso'], $pg_meta_id);
	elseif ($canvas_id) $podeEditar=permiteEditarCanvas($linha['log_acesso'], $canvas_id);
	elseif ($risco_id) $podeEditar=permiteEditarRisco($linha['log_acesso'], $risco_id);
	elseif ($risco_resposta_id) $podeEditar=permiteEditarRiscoResposta($linha['log_acesso'], $risco_resposta_id);
	elseif ($problema_id) $podeEditar=permiteEditarProblema($linha['log_acesso'], $problema_id);
	elseif ($plano_acao_id) $podeEditar=permiteEditarPlanoAcao($linha['log_acesso'], $plano_acao_id);
	elseif ($tarefa_id) $podeEditar=permiteEditar($linha['log_acesso'], $projeto_id, $tarefa_id);
	elseif ($projeto_id) $podeEditar=permiteEditar($linha['log_acesso'], $projeto_id);
	elseif ($pratica_id) $podeEditar=permiteEditarPratica($linha['log_acesso'], $pratica_id);
	elseif ($pratica_indicador_id) $podeEditar=permiteEditarIndicador($linha['log_acesso'], $pratica_indicador_id);
	elseif ($calendario_id) $podeEditar=permiteEditarCalendario($linha['log_acesso'], $calendario_id);
	elseif ($monitoramento_id) $podeEditar=permiteEditarMonitoramento($linha['log_acesso'], $monitoramento_id);
	elseif ($ata_id) $podeEditar=permiteEditarAta($linha['log_acesso'], $ata_id);
	elseif ($mswot_id) $podeEditar=permiteEditarMSWOT($linha['log_acesso'], $mswot_id);
	elseif ($swot_id) $podeEditar=permiteEditarSWOT($linha['log_acesso'], $swot_id);
	elseif ($operativo_id) $podeEditar=permiteEditarOperativo($linha['log_acesso'], $operativo_id);
	elseif ($instrumento_id) $podeEditar=permiteEditarInstrumento($linha['log_acesso'], $instrumento_id);
	elseif ($recurso_id) $podeEditar=permiteEditarRecurso($linha['log_acesso'], $recurso_id);
	elseif ($demanda_id) $podeEditar=permiteEditarDemanda($linha['log_acesso'], $demanda_id);
	elseif ($programa_id) $podeEditar=permiteEditarPrograma($linha['log_acesso'], $programa_id);
	elseif ($licao_id) $podeEditar=permiteEditarLicao($linha['log_acesso'], $licao_id);
	elseif ($evento_id) $podeEditar=permiteEditarEvento($linha['log_acesso'], $evento_id);
	elseif ($link_id) $podeEditar=permiteEditarLink($linha['log_acesso'], $link_id);
	elseif ($avaliacao_id) $podeEditar=permiteEditarAvaliacao($linha['log_acesso'], $avaliacao_id);
	elseif ($tgn_id) $podeEditar=permiteEditarTgn($linha['log_acesso'], $tgn_id);
	elseif ($brainstorm_id) $podeEditar=permiteEditarBrainstorm($linha['log_acesso'], $brainstorm_id);
	elseif ($gut_id) $podeEditar=permiteEditarGut($linha['log_acesso'], $gut_id);
	elseif ($causa_efeito_id) $podeEditar=permiteEditarCausa_efeito($linha['log_acesso'], $causa_efeito_id);
	elseif ($arquivo_id) $podeEditar=permiteEditarArquivo($linha['log_acesso'], $arquivo_id);
	elseif ($forum_id) $podeEditar=permiteEditarForum($linha['log_acesso'], $forum_id);
	elseif ($checklist_id) $podeEditar=permiteEditarChecklist($linha['log_acesso'], $checklist_id);
	elseif ($agenda_id) $podeEditar=true;
	elseif ($agrupamento_id) $podeEditar=permiteEditarAgrupamento($linha['log_acesso'], $agrupamento_id);
	elseif ($patrocinador_id) $podeEditar=permiteEditarPatrocinador($linha['log_acesso'], $patrocinador_id);
	elseif ($template_id) $podeEditar=permiteEditarTemplate($linha['log_acesso'], $template_id);
	elseif ($painel_id) $podeEditar=permiteEditarPainel($linha['log_acesso'], $painel_id);
	elseif ($painel_odometro_id) $podeEditar=permiteEditarOdometro($linha['log_acesso'], $painel_odometro_id);
	elseif ($painel_composicao_id) $podeEditar=permiteEditarPainelComposicao($linha['log_acesso'], $painel_composicao_id);
	elseif ($tr_id) $podeEditar=permiteEditarTR($linha['log_acesso'], $tr_id);
	elseif ($me_id) $podeEditar=permiteEditarMe($linha['log_acesso'], $me_id);
	elseif ($plano_acao_item_id) $podeEditar=permiteEditarPlanoAcaoItem($linha['log_acesso'], $plano_acao_item_id);
	elseif ($beneficio_id) $podeEditar=permiteEditarBeneficio($linha['log_acesso'], $beneficio_id);
	elseif ($painel_slideshow_id) $podeEditar=permiteEditarPainelSlideShow($linha['log_acesso'], $painel_slideshow_id);
	elseif ($projeto_viabilidade_id) $podeEditar=permiteEditarViabilidade($linha['log_acesso'], $projeto_viabilidade_id);
	elseif ($projeto_abertura_id) $podeEditar=permiteEditarTermoAbertura($linha['log_acesso'], $projeto_abertura_id);
	elseif ($pg_id) $podeEditar=permiteEditarPlanoGestao($linha['log_acesso'], $pg_id);
	elseif ($ssti_id)$podeEditar=permiteEditarSSTI($linha['log_acesso'],$ssti_id);
	elseif ($laudo_id)$podeEditar=permiteEditarLaudo($linha['log_acesso'],$laudo_id);
	elseif ($trelo_id)$podeEditar=permiteEditarTrelo($linha['log_acesso'],$trelo_id);
	elseif ($trelo_cartao_id)$podeEditar=permiteEditarTreloCartao($linha['log_acesso'],$trelo_cartao_id);
	elseif ($pdcl_id)$podeEditar=permiteEditarPDCL($linha['log_acesso'],$pdcl_id);
	elseif ($pdcl_item_id)$podeEditar=permiteEditarPDCLItem($linha['log_acesso'],$pdcl_item_id);

	else $podeEditar=false;
	
	$imagem_referencia = '-';
	if ($linha['log_referencia'] > 0) {
		if (isset($RefRegistroAcaoImagem[$linha['log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroAcaoImagem[$linha['log_referencia']], $RefRegistroAcao[$linha['log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroAcao[$linha['log_referencia']])) $imagem_referencia = $RefRegistroAcao[$linha['log_referencia']];
		}
	echo '<tr bgcolor="white" valign="top">';
	if (!$dialogo) echo '<td valign="middle" width=16>'.($podeEditar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=log_editar&log_id='.$linha['log_id'].'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o registro').'</a>' : '&nbsp;').'</td>';
	echo '<td style="white-space: nowrap" valign="middle" valign="middle" width=16>'.retorna_data($linha['log_data'], false).'</td>';
	echo '<td style="white-space: nowrap;'.$estilo.'" valign="middle">'.($linha['corrigido'] ? dica('Registro em que Solucionou Problema', $linha['corrigido']) : '').($linha['corrigiu'] ? dica('Registro Responsável pela Solução', $linha['corrigiu']) : '').($linha['log_nome'] ? $linha['log_nome'] : '&nbsp;').($linha['corrigiu'] || $linha['corrigido'] ? dicaF() : '').$anexo.'</td>';
	echo '<td valign="middle">'.(isset($logTipoProblema[$linha['log_tipo_problema']]) ? $logTipoProblema[$linha['log_tipo_problema']] : '&nbsp;').'</td>';
	
	if ($projeto_id){
		
		
		echo '<td valign="middle">'.(isset($logTipo[$linha['log_tipo']]) ? $logTipo[$linha['log_tipo']] : '&nbsp;').'</td>';
		echo '<td valign="middle">'.(isset($projetoFase[$linha['log_reg_mudanca_fase']]) ? $projetoFase[$linha['log_reg_mudanca_fase']] : '&nbsp;').'</td>';
		echo '<td valign="middle">'.(isset($projStatus[$linha['log_reg_mudanca_status']]) ? $projStatus[$linha['log_reg_mudanca_status']] : '&nbsp;').'</td>';
		}
	echo '<td valign="middle">'.($linha['log_descricao'] ? str_replace("\n", '<br />', $linha['log_descricao']) : '&nbsp;').'</td>';
	echo '<td style="white-space: nowrap" valign="middle">'.link_usuario($linha['log_criador'],'','','esquerda').'</td>';
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
	
	
	if (!$dialogo) echo '<td valign="middle" width=16>'.($podeEditar ?  dica('Excluir Registro', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este registro.').'<a href="javascript:excluir2('.$linha['log_id'].');" >'.imagem('icones/remover.png').'</a>' : '&nbsp;').'</td>';
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
