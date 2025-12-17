<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$data_texto = new CData();

$log_id=getParam($_REQUEST, 'log_id', 0);

$tarefa_id=getParam($_REQUEST, 'tarefa_id', null);
$projeto_id=getParam($_REQUEST, 'projeto_id', null);
$pg_perspectiva_id=getParam($_REQUEST, 'pg_perspectiva_id', null);
$tema_id=getParam($_REQUEST, 'tema_id', null);
$objetivo_id=getParam($_REQUEST, 'objetivo_id', null);
$fator_id=getParam($_REQUEST, 'fator_id', null);
$pg_estrategia_id=getParam($_REQUEST, 'pg_estrategia_id', null);
$pg_meta_id=getParam($_REQUEST, 'pg_meta_id', null);
$pratica_id=getParam($_REQUEST, 'pratica_id', null);
$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', null);
$plano_acao_id=getParam($_REQUEST, 'plano_acao_id', null);
$canvas_id=getParam($_REQUEST, 'canvas_id', null);
$risco_id=getParam($_REQUEST, 'risco_id', null);
$risco_resposta_id=getParam($_REQUEST, 'risco_resposta_id', null);
$calendario_id=getParam($_REQUEST, 'calendario_id', null);
$monitoramento_id=getParam($_REQUEST, 'monitoramento_id', null);
$ata_id=getParam($_REQUEST, 'ata_id', null);
$mswot_id=getParam($_REQUEST, 'mswot_id', null);
$swot_id=getParam($_REQUEST, 'swot_id', null);
$operativo_id=getParam($_REQUEST, 'operativo_id', null);
$instrumento_id=getParam($_REQUEST, 'instrumento_id', null);
$recurso_id=getParam($_REQUEST, 'recurso_id', null);
$problema_id=getParam($_REQUEST, 'problema_id', null);
$demanda_id=getParam($_REQUEST, 'demanda_id', null);
$programa_id=getParam($_REQUEST, 'programa_id', null);
$licao_id=getParam($_REQUEST, 'licao_id', null);
$evento_id=getParam($_REQUEST, 'evento_id', null);
$link_id=getParam($_REQUEST, 'link_id', null);
$avaliacao_id=getParam($_REQUEST, 'avaliacao_id', null);
$tgn_id=getParam($_REQUEST, 'tgn_id', null);
$brainstorm_id=getParam($_REQUEST, 'brainstorm_id', null);
$gut_id=getParam($_REQUEST, 'gut_id', null);
$causa_efeito_id=getParam($_REQUEST, 'causa_efeito_id', null);
$arquivo_id=getParam($_REQUEST, 'arquivo_id', null);
$forum_id=getParam($_REQUEST, 'forum_id', null);
$checklist_id=getParam($_REQUEST, 'checklist_id', null);
$agenda_id=getParam($_REQUEST, 'agenda_id', null);
$agrupamento_id=getParam($_REQUEST, 'agrupamento_id', null);
$patrocinador_id=getParam($_REQUEST, 'patrocinador_id', null);
$template_id=getParam($_REQUEST, 'template_id', null);
$painel_id=getParam($_REQUEST, 'painel_id', null);
$painel_odometro_id=getParam($_REQUEST, 'painel_odometro_id', null);
$painel_composicao_id=getParam($_REQUEST, 'painel_composicao_id', null);
$tr_id=getParam($_REQUEST, 'tr_id', null);
$me_id=getParam($_REQUEST, 'me_id', null);
$plano_acao_item_id=getParam($_REQUEST, 'plano_acao_item_id', null);
$beneficio_id=getParam($_REQUEST, 'beneficio_id', null);
$painel_slideshow_id=getParam($_REQUEST, 'painel_slideshow_id', null);
$projeto_viabilidade_id=getParam($_REQUEST, 'projeto_viabilidade_id', null);
$projeto_abertura_id=getParam($_REQUEST, 'projeto_abertura_id', null);
$pg_id=getParam($_REQUEST, 'pg_id', null);
$ssti_id=getParam($_REQUEST, 'ssti_id', null);
$laudo_id=getParam($_REQUEST, 'laudo_id', null);
$trelo_id=getParam($_REQUEST, 'trelo_id', null);
$trelo_cartao_id=getParam($_REQUEST, 'trelo_cartao_id', null);
$pdcl_id=getParam($_REQUEST, 'pdcl_id', null);
$pdcl_item_id=getParam($_REQUEST, 'pdcl_item_id', null);

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$Aplic->carregarCalendarioJS();

require_once (BASE_DIR.'/modulos/praticas/log.class.php');
$log = new CLog();
if ($log_id) {
	$log->load($log_id);

	$tarefa_id=$log->log_tarefa;
	$projeto_id=$log->log_projeto;
	$pg_perspectiva_id=$log->log_perspectiva;
	$tema_id=$log->log_tema;
	$objetivo_id=$log->log_objetivo;
	$fator_id=$log->log_fator;
	$pg_estrategia_id=$log->log_estrategia;
	$pg_meta_id=$log->log_meta;
	$pratica_id=$log->log_pratica ;
	$pratica_indicador_id=$log->log_acao;
	$plano_acao_id=$log->log_canvas;
	$canvas_id=$log->log_risco;
	$risco_id=$log->log_risco_resposta;
	$risco_resposta_id=$log->log_indicador;
	$calendario_id=$log->log_calendario;
	$monitoramento_id=$log->log_monitoramento;
	$ata_id=$log->log_ata;
	$mswot_id=$log->log_mswot;
	$swot_id=$log->log_swot;
	$operativo_id=$log->log_operativo;
	$instrumento_id=$log->log_instrumento;
	$recurso_id=$log->log_recurso;
	$problema_id=$log->log_problema;
	$demanda_id=$log->log_demanda;
	$programa_id=$log->log_programa;
	$licao_id=$log->log_licao;
	$evento_id=$log->log_evento;
	$link_id=$log->log_link;
	$avaliacao_id=$log->log_avaliacao;
	$tgn_id=$log->log_tgn;
	$brainstorm_id=$log->log_brainstorm;
	$gut_id=$log->log_gut;
	$causa_efeito_id=$log->log_causa_efeito;
	$arquivo_id=$log->log_arquivo;
	$forum_id=$log->log_forum;
	$checklist_id=$log->log_checklist;
	$agenda_id=$log->log_agenda;
	$agrupamento_id=$log->log_agrupamento;
	$patrocinador_id=$log->log_patrocinador;
	$template_id=$log->log_template;
	$painel_id = $log->log_painel;
	$painel_odometro_id = $log->log_painel_odometro;
	$painel_composicao_id = $log->log_painel_composicao;
	$tr_id = $log->log_tr;
	$me_id = $log->log_me;
	$plano_acao_item_id = $log->log_acao_item;
	$beneficio_id = $log->log_beneficio;
	$painel_slideshow_id = $log->log_painel_slideshow;
	$projeto_viabilidade_id = $log->log_projeto_viabilidade;
	$projeto_abertura_id = $log->log_projeto_abertura;
	$pg_id = $log->log_plano_gestao;
	$ssti_id = $log->log_ssti;
	$laudo_id = $log->log_laudo;
	$trelo_id = $log->log_trelo;
	$trelo_cartao_id = $log->log_trelo_cartao;
	$pdcl_id = $log->log_pdcl;
	$pdcl_item_id = $log->log_pdcl_item;
	
	
	
	}
else {
	if ($tarefa_id) $log->log_tarefa = $tarefa_id;
	if ($projeto_id) $log->log_projeto = $projeto_id;
	elseif ($pg_perspectiva_id) $log->log_perspectiva = $pg_perspectiva_id;
	elseif ($tema_id) $log->log_tema  = $tema_id;
	elseif ($objetivo_id) $log->log_objetivo  = $objetivo_id;
	elseif ($fator_id) $log->log_fator = $fator_id;
	elseif ($pg_estrategia_id) $log->log_estrategia  = $pg_estrategia_id;
	elseif ($pg_meta_id) $log->log_meta = $pg_meta_id;
	elseif ($pratica_id) $log->log_pratica = $pratica_id;
	elseif ($pratica_indicador_id) $log->log_indicador  = $pratica_indicador_id;
	elseif ($plano_acao_id) $log->log_acao = $plano_acao_id;
	elseif ($canvas_id) $log->log_canvas = $canvas_id;
	elseif ($risco_id) $log->log_risco = $risco_id;
	elseif ($risco_resposta_id) $log->log_risco_resposta = $risco_resposta_id;
	elseif ($calendario_id) $log->log_calendario = $calendario_id;
	elseif ($monitoramento_id) $log->log_monitoramento = $monitoramento_id;
	elseif ($ata_id) $log->log_ata = $ata_id;
	elseif ($mswot_id) $log->log_mswot = $mswot_id;
	elseif ($swot_id) $log->log_swot = $swot_id;
	elseif ($operativo_id) $log->log_operativo = $operativo_id;
	elseif ($instrumento_id) $log->log_instrumento = $instrumento_id;
	elseif ($recurso_id) $log->log_recurso = $recurso_id;
	elseif ($problema_id) $log->log_problema = $problema_id;
	elseif ($demanda_id) $log->log_demanda = $demanda_id;
	elseif ($programa_id) $log->log_programa = $programa_id;
	elseif ($licao_id) $log->log_licao = $licao_id;
	elseif ($evento_id) $log->log_evento = $evento_id;
	elseif ($link_id) $log->log_link = $link_id;
	elseif ($avaliacao_id) $log->log_avaliacao = $avaliacao_id;
	elseif ($tgn_id) $log->log_tgn = $tgn_id;
	elseif ($brainstorm_id) $log->log_brainstorm = $brainstorm_id;
	elseif ($gut_id) $log->log_gut = $gut_id;
	elseif ($causa_efeito_id) $log->log_causa_efeito = $causa_efeito_id;
	elseif ($arquivo_id) $log->log_arquivo = $arquivo_id;
	elseif ($forum_id) $log->log_forum = $forum_id;
	elseif ($checklist_id) $log->log_checklist = $checklist_id;
	elseif ($agenda_id) $log->log_agenda = $agenda_id;
	elseif ($agrupamento_id) $log->log_agrupamento = $agrupamento_id;
	elseif ($patrocinador_id) $log->log_patrocinador = $patrocinador_id;
	elseif ($template_id) $log->log_template = $template_id;
	elseif ($painel_id) $log->log_painel = $painel_id;
	elseif ($painel_odometro_id) $log->log_painel_odometro = $painel_odometro_id;
	elseif ($painel_composicao_id) $log->log_painel_composicao = $painel_composicao_id;
	elseif ($tr_id) $log->log_tr = $tr_id;
	elseif ($me_id) $log->log_me = $me_id;
	elseif ($plano_acao_item_id) $log->log_acao_item = $plano_acao_item_id;
	elseif ($beneficio_id) $log->log_beneficio = $beneficio_id;
	elseif ($painel_slideshow_id) $log->log_painel_slideshow = $painel_slideshow_id;
	elseif ($projeto_viabilidade_id) $log->log_projeto_viabilidade = $projeto_viabilidade_id;
	elseif ($projeto_abertura_id) $log->log_projeto_abertura = $projeto_abertura_id;
	elseif ($pg_id) $log->log_plano_gestao = $pg_id;
	elseif ($ssti_id) $log->log_ssti=$ssti_id;
	elseif ($laudo_id) $log->log_laudo=$laudo_id;
	elseif ($trelo_id) $log->log_trelo=$trelo_id;
	elseif ($trelo_cartao_id) $log->log_trelo_cartao=$trelo_cartao_id;
	elseif ($pdcl_id) $log->log_pdcl=$pdcl_id;
	elseif ($pdcl_item_id) $log->log_pdcl_item=$pdcl_item_id;
	}

$RefRegistroTarefa = getSisValor('RefRegistroTarefa');

$log_data = new CData($log->log_data);


if ($tarefa_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_tarefa($tarefa_id), 'log.png', $m, $m.'.'.$a);
elseif ($projeto_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_projeto($projeto_id), 'log.png', $m, $m.'.'.$a);
elseif ($pg_perspectiva_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_perspectiva($pg_perspectiva_id), 'log.png', $m, $m.'.'.$a);
elseif ($tema_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_tema($tema_id), 'log.png', $m, $m.'.'.$a);
elseif ($objetivo_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_objetivo($objetivo_id), 'log.png', $m, $m.'.'.$a);
elseif ($fator_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_fator($fator_id), 'log.png', $m, $m.'.'.$a);
elseif ($pg_estrategia_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_estrategia($pg_estrategia_id), 'log.png', $m, $m.'.'.$a);
elseif ($pg_meta_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_meta($pg_meta_id), 'log.png', $m, $m.'.'.$a);
elseif ($pratica_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_pratica($pratica_id), 'log.png', $m, $m.'.'.$a);
elseif ($pratica_indicador_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_indicador($pratica_indicador_id), 'log.png', $m, $m.'.'.$a);
elseif ($plano_acao_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_acao($plano_acao_id), 'log.png', $m, $m.'.'.$a);
elseif ($canvas_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_canvas($canvas_id), 'log.png', $m, $m.'.'.$a);
elseif ($risco_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_risco($risco_id), 'log.png', $m, $m.'.'.$a);
elseif ($risco_resposta_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_risco_resposta($risco_resposta_id), 'log.png', $m, $m.'.'.$a);
elseif ($calendario_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_calendario($calendario_id), 'log.png', $m, $m.'.'.$a);
elseif ($monitoramento_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_monitoramento($monitoramento_id), 'log.png', $m, $m.'.'.$a);
elseif ($ata_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_ata_pro($ata_id), 'log.png', $m, $m.'.'.$a);
elseif ($mswot_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_mswot($mswot_id), 'log.png', $m, $m.'.'.$a);
elseif ($swot_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_swot($swot_id), 'log.png', $m, $m.'.'.$a);
elseif ($operativo_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_operativo($operativo_id), 'log.png', $m, $m.'.'.$a);
elseif ($instrumento_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_instrumento($instrumento_id), 'log.png', $m, $m.'.'.$a);
elseif ($recurso_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_recurso($recurso_id), 'log.png', $m, $m.'.'.$a);
elseif ($problema_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_problema($problema_id), 'log.png', $m, $m.'.'.$a);
elseif ($demanda_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_demanda($demanda_id), 'log.png', $m, $m.'.'.$a);
elseif ($programa_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_programa($programa_id), 'log.png', $m, $m.'.'.$a);
elseif ($licao_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_licao($licao_id), 'log.png', $m, $m.'.'.$a);
elseif ($evento_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_evento($evento_id), 'log.png', $m, $m.'.'.$a);
elseif ($link_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_link($link_id), 'log.png', $m, $m.'.'.$a);
elseif ($avaliacao_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_avaliacao($avaliacao_id), 'log.png', $m, $m.'.'.$a);
elseif ($tgn_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_tgn($tgn_id), 'log.png', $m, $m.'.'.$a);
elseif ($brainstorm_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_brainstorm($brainstorm_id), 'log.png', $m, $m.'.'.$a);
elseif ($gut_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_gut($gut_id), 'log.png', $m, $m.'.'.$a);
elseif ($causa_efeito_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_causa_efeito($causa_efeito_id), 'log.png', $m, $m.'.'.$a);
elseif ($arquivo_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_arquivo($arquivo_id), 'log.png', $m, $m.'.'.$a);
elseif ($forum_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_forum($forum_id), 'log.png', $m, $m.'.'.$a);
elseif ($checklist_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_checklist($checklist_id), 'log.png', $m, $m.'.'.$a);
elseif ($agenda_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_agenda($agenda_id), 'log.png', $m, $m.'.'.$a);
elseif ($agrupamento_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_agrupamento($agrupamento_id), 'log.png', $m, $m.'.'.$a);
elseif ($patrocinador_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_patrocinador($patrocinador_id), 'log.png', $m, $m.'.'.$a);
elseif ($template_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_template($template_id), 'log.png', $m, $m.'.'.$a);
elseif ($painel_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_painel($painel_id), 'log.png', $m, $m.'.'.$a);
elseif ($painel_odometro_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_painel_odometro($painel_odometro_id), 'log.png', $m, $m.'.'.$a);
elseif ($painel_composicao_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_painel_composicao($painel_composicao_id), 'log.png', $m, $m.'.'.$a);
elseif ($tr_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_tr($tr_id), 'log.png', $m, $m.'.'.$a);
elseif ($me_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_me($me_id), 'log.png', $m, $m.'.'.$a);
elseif ($plano_acao_item_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_acao_item($plano_acao_item_id), 'log.png', $m, $m.'.'.$a);
elseif ($beneficio_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_beneficio($beneficio_id), 'log.png', $m, $m.'.'.$a);
elseif ($painel_slideshow_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_painel_slideshow($painel_slideshow_id), 'log.png', $m, $m.'.'.$a);
elseif ($projeto_viabilidade_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_viabilidade($projeto_viabilidade_id), 'log.png', $m, $m.'.'.$a);
elseif ($projeto_abertura_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_termo_abertura($projeto_abertura_id), 'log.png', $m, $m.'.'.$a);
elseif ($pg_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_plano_gestao($pg_id), 'log.png', $m, $m.'.'.$a);
elseif ($ssti_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_ssti($ssti_id), 'log.png', $m, $m.'.'.$a);
elseif ($laudo_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_laudo($laudo_id), 'log.png', $m, $m.'.'.$a);
elseif ($trelo_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_trelo($trelo_id), 'log.png', $m, $m.'.'.$a);
elseif ($trelo_cartao_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_trelo_cartao($trelo_cartao_id), 'log.png', $m, $m.'.'.$a);
elseif ($pdcl_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_pdcl($pdcl_id), 'log.png', $m, $m.'.'.$a);
elseif ($pdcl_item_id) $botoesTitulo = new CBlocoTitulo('Registro de Ocorrência de '.link_pdcl_item($pdcl_item_id), 'log.png', $m, $m.'.'.$a);


$botoesTitulo->mostrar();



echo '<a name="log"></a>';
echo '<form name="env" method="post" enctype="multipart/form-data" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="log_fazer_sql" />';


echo '<input type="hidden" name="log_tarefa" value="'.($log_id ? $log->log_tarefa  : $tarefa_id).'" />';
echo '<input type="hidden" name="log_projeto" value="'.($log_id ? $log->log_projeto : $projeto_id).'" />';
echo '<input type="hidden" name="log_perspectiva" value="'.($log_id ? $log->log_perspectiva: $pg_perspectiva_id).'" />';
echo '<input type="hidden" name="log_tema" value="'.($log_id ? $log->log_tema : $tema_id).'" />';
echo '<input type="hidden" name="log_objetivo" value="'.($log_id ? $log->log_objetivo : $objetivo_id).'" />';
echo '<input type="hidden" name="log_fator" value="'.($log_id ? $log->log_fator : $fator_id).'" />';
echo '<input type="hidden" name="log_estrategia" value="'.($log_id ? $log->log_estrategia : $pg_estrategia_id).'" />';
echo '<input type="hidden" name="log_meta" value="'.($log_id ? $log->log_meta: $pg_meta_id).'" />';
echo '<input type="hidden" name="log_pratica" value="'.($log_id ? $log->log_pratica  : $pratica_id).'" />';
echo '<input type="hidden" name="log_indicador" value="'.($log_id ? $log->log_indicador  : $pratica_indicador_id).'" />';
echo '<input type="hidden" name="log_acao" value="'.($log_id ? $log->log_acao : $plano_acao_id).'" />';
echo '<input type="hidden" name="log_canvas" value="'.($log_id ? $log->log_canvas : $canvas_id).'" />';
echo '<input type="hidden" name="log_risco" value="'.($log_id ? $log->log_risco : $risco_id).'" />';
echo '<input type="hidden" name="log_risco_resposta" value="'.($log_id ? $log->log_risco_resposta: $risco_resposta_id).'" />';
echo '<input type="hidden" name="log_calendario" value="'.($log_id ? $log->log_calendario: $calendario_id).'" />';
echo '<input type="hidden" name="log_monitoramento" value="'.($log_id ? $log->log_monitoramento: $monitoramento_id).'" />';
echo '<input type="hidden" name="log_ata" value="'.($log_id ? $log->log_ata: $ata_id).'" />';
echo '<input type="hidden" name="log_mswot" value="'.($log_id ? $log->log_mswot: $mswot_id).'" />';
echo '<input type="hidden" name="log_swot" value="'.($log_id ? $log->log_swot: $swot_id).'" />';
echo '<input type="hidden" name="log_operativo" value="'.($log_id ? $log->log_operativo: $operativo_id).'" />';
echo '<input type="hidden" name="log_instrumento" value="'.($log_id ? $log->log_instrumento: $instrumento_id).'" />';
echo '<input type="hidden" name="log_recurso" value="'.($log_id ? $log->log_recurso: $recurso_id).'" />';
echo '<input type="hidden" name="log_problema" value="'.($log_id ? $log->log_problema: $problema_id).'" />';
echo '<input type="hidden" name="log_demanda" value="'.($log_id ? $log->log_demanda: $demanda_id).'" />';
echo '<input type="hidden" name="log_programa" value="'.($log_id ? $log->log_programa: $programa_id).'" />';
echo '<input type="hidden" name="log_licao" value="'.($log_id ? $log->log_licao: $licao_id).'" />';
echo '<input type="hidden" name="log_evento" value="'.($log_id ? $log->log_evento: $evento_id).'" />';
echo '<input type="hidden" name="log_link" value="'.($log_id ? $log->log_link: $link_id).'" />';
echo '<input type="hidden" name="log_avaliacao" value="'.($log_id ? $log->log_avaliacao: $avaliacao_id).'" />';
echo '<input type="hidden" name="log_tgn" value="'.($log_id ? $log->log_tgn: $tgn_id).'" />';
echo '<input type="hidden" name="log_brainstorm" value="'.($log_id ? $log->log_brainstorm: $brainstorm_id).'" />';
echo '<input type="hidden" name="log_gut" value="'.($log_id ? $log->log_gut: $gut_id).'" />';
echo '<input type="hidden" name="log_causa_efeito" value="'.($log_id ? $log->log_causa_efeito: $causa_efeito_id).'" />';
echo '<input type="hidden" name="log_arquivo" value="'.($log_id ? $log->log_arquivo: $arquivo_id).'" />';
echo '<input type="hidden" name="log_forum" value="'.($log_id ? $log->log_forum: $forum_id).'" />';
echo '<input type="hidden" name="log_checklist" value="'.($log_id ? $log->log_checklist: $checklist_id).'" />';
echo '<input type="hidden" name="log_agenda" value="'.($log_id ? $log->log_agenda: $agenda_id).'" />';
echo '<input type="hidden" name="log_agrupamento" value="'.($log_id ? $log->log_agrupamento: $agrupamento_id).'" />';
echo '<input type="hidden" name="log_patrocinador" value="'.($log_id ? $log->log_patrocinador: $patrocinador_id).'" />';
echo '<input type="hidden" name="log_template" value="'.($log_id ? $log->log_template: $template_id).'" />';
echo '<input type="hidden" name="log_painel" value="'.($log_id ? $log->log_painel: $painel_id).'" />';
echo '<input type="hidden" name="log_painel_odometro" value="'.($log_id ? $log->log_painel_odometro : $painel_odometro_id).'" />';
echo '<input type="hidden" name="log_painel_composicao" value="'.($log_id ? $log->log_painel_composicao : $painel_composicao_id).'" />';
echo '<input type="hidden" name="log_tr" value="'.($log_id ? $log->log_tr : $tr_id).'" />';
echo '<input type="hidden" name="log_me" value="'.($log_id ? $log->log_me : $me_id).'" />';
echo '<input type="hidden" name="log_acao_item" value="'.($log_id ? $log->log_acao_item : $plano_acao_item_id).'" />';
echo '<input type="hidden" name="log_beneficio" value="'.($log_id ? $log->log_beneficio : $beneficio_id).'" />';
echo '<input type="hidden" name="log_painel_slideshow" value="'.($log_id ? $log->log_painel_slideshow : $painel_slideshow_id).'" />';
echo '<input type="hidden" name="log_projeto_viabilidade" value="'.($log_id ? $log->log_projeto_viabilidade : $projeto_viabilidade_id).'" />';
echo '<input type="hidden" name="log_projeto_abertura" value="'.($log_id ? $log->log_projeto_abertura : $projeto_abertura_id).'" />';
echo '<input type="hidden" name="log_plano_gestao" value="'.($log_id ? $log->log_plano_gestao : $pg_id).'" />';
echo '<input type="hidden" name="log_ssti" value="'.($log_id ? $log->log_ssti : $ssti_id).'" />';
echo '<input type="hidden" name="log_laudo" value="'.($log_id ? $log->log_laudo : $laudo_id).'" />';
echo '<input type="hidden" name="log_trelo" value="'.($log_id ? $log->log_trelo : $trelo_id).'" />';
echo '<input type="hidden" name="log_trelo_cartao" value="'.($log_id ? $log->log_trelo_cartao : $trelo_cartao_id).'" />';
echo '<input type="hidden" name="log_pdcl" value="'.($log_id ? $log->log_pdcl : $pdcl_id).'" />';
echo '<input type="hidden" name="log_pdcl_item" value="'.($log_id ? $log->log_pdcl_item : $pdcl_item_id).'" />';


echo '<input type="hidden" name="uuid" id="uuid" value="'.($log_id ? null : uuid()).'" />';
echo '<input type="hidden" name="log_id" id="log_id" value="'.$log->log_id.'" />';
echo '<input type="hidden" name="log_criador" value="'.($log->log_criador == 0 ? $Aplic->usuario_id : $log->log_criador).'" />';


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';

echo '<tr><td align="right" width=144>'.dica('Nome', 'Escreva um texto curto que exprima o motivo deste registro.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="log_nome" value="'.$log->log_nome.'" maxlength="255" style="width:395px;" /></td></tr>';
echo '<tr><td align="right" valign="top">'.dica('Descrição', 'Escreva uma descrição pormenorizada sobre este registro.').'Descrição:'.dicaF().'</td><td><table width=399 cellspacing=0><tr><td><textarea data-gpweb-cmp="ckeditor" name="log_descricao" class="textarea" style="width:399px;" rows="6">'.$log->log_descricao.'</textarea></td></tr></table></td></tr>';

echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro de ocorrência.').'Data:'.dicaF().'</td><td style="white-space: nowrap"><input type="hidden" name="log_data" id="log_data" value="'.$log_data->format('%Y%m%d').'" /><input type="text" name="log_date" id="log_date" onchange="setData(\'env\', \'log_date\');" value="'.$log_data->format('%d/%m/%Y').'" class="texto" style="width:65px;" />'.dica('Data do Registro', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';


$logTipo = getSisValor('logTipo');
echo '<tr><td align="right">'.dica('Tipo de Registro', 'Definir ao tipo de registro de ocorrência.').'Tipo de registro:'.dicaF().'</td><td>'.selecionaVetor($logTipo, 'log_tipo', 'style="width:400px;" size="1" class="texto"', $log->log_tipo).'</td></tr>';

if ($projeto_id){
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_portfolio, projeto_status, projeto_fase');
	$sql->adOnde('projeto_id='.(int)$projeto_id);
	$projeto=$sql->Linha();
	$sql->limpar();
	
	echo '<input name="projeto_status_antigo" type="hidden" value="'.$projeto['projeto_status'].'" />';
	echo '<input name="projeto_fase_antigo" type="hidden" value="'.$projeto['projeto_fase'].'" />';
	
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'projeto\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

	if ($exibir2['projeto_fase']){
		$projetoFase = getSisValor('projetoFase');
		echo '<tr><td align="right">'.dica('Fase d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'Definir a fase d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Fase d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).':'.dicaF().'</td><td>'.selecionaVetor($projetoFase, 'log_reg_mudanca_fase', 'style="width:400px;" size="1" class="texto"', ($log_id ? $log->log_reg_mudanca_fase : $projeto['projeto_fase'])).'</td></tr>';
		}
	
	$projStatus = getSisValor('StatusProjeto');
	echo '<tr><td align="right">'.dica('Status', 'Definir o Status d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Status:'.dicaF().'</td><td>'.selecionaVetor($projStatus, 'log_reg_mudanca_status', 'style="width:400px;" size="1" class="texto"', ($log_id ? $log->log_reg_mudanca_status : $projeto['projeto_status'])).'</td></tr>';
	}


echo '<tr><td align="right">'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro será marcado como de problema.<br><br>Ele se diferenciará dos outros registros por ter um fundo vermelho no sumário para chamar a atenção.').'Problema:'.dicaF().'</td><td style="white-space: nowrap"><input type="checkbox" value="1" name="log_corrigir" id="log_corrigir" '.($log->log_corrigir ? 'checked="checked"' : '').' onclick="if (env.log_corrigir.checked) {env.log_corrigir.checked=true; document.getElementById(\'tipo_problema\').style.display=\'\';} else {document.getElementById(\'tipo_problema\').style.display=\'none\';}" /></td></tr>';

$logTipoProblema = getSisValor('logTipoProblema');
echo '<tr '.($log->log_corrigir ? ' style="display:"' : ' style="display:none"').' id="tipo_problema"><td align="right" valign="middle">'.dica('Motivo', 'Escolha de que forma chegou aos dados que aqui estão registrados.').'Motivo:'.dicaF().'</td><td valign="middle">'.selecionaVetor($logTipoProblema, 'log_tipo_problema', 'size="1" class="texto"', $log->log_tipo_problema).'</td></tr>';







$sql->adTabela('log');
$sql->adCampo('log_id, concatenar_tres(formatar_data(log_data, "%d/%m/%Y"), \' - \', log_nome) AS nome');
$sql->adOnde('log_corrigir=1');
if ($log->log_tarefa) $sql->adOnde('log_tarefa   = '.(int)$log->log_tarefa);
elseif ($log->log_projeto) $sql->adOnde('log_projeto  = '.(int)$log->log_projeto);
elseif ($log->log_perspectiva) $sql->adOnde('log_perspectiva = '.(int)$log->log_perspectiva);
elseif ($log->log_tema) $sql->adOnde('log_tema  = '.(int)$log->log_tema);
elseif ($log->log_objetivo) $sql->adOnde('log_objetivo  = '.(int)$log->log_objetivo);
elseif ($log->log_fator) $sql->adOnde('log_fator  = '.(int)$log->log_fator);
elseif ($log->log_estrategia) $sql->adOnde('log_estrategia  = '.(int)$log->log_estrategia);
elseif ($log->log_meta) $sql->adOnde('log_meta = '.(int)$log->log_meta);
elseif ($log->log_pratica) $sql->adOnde('log_pratica   = '.(int)$log->log_pratica);
elseif ($log->log_acao) $sql->adOnde('log_acao  = '.(int)$log->log_acao);
elseif ($log->log_canvas) $sql->adOnde('log_canvas  = '.(int)$log->log_canvas);
elseif ($log->log_risco) $sql->adOnde('log_risco  = '.(int)$log->log_risco);
elseif ($log->log_risco_resposta) $sql->adOnde('log_risco_resposta = '.(int)$log->log_risco_resposta);
elseif ($log->log_indicador) $sql->adOnde('log_indicador = '.(int)$log->log_indicador);
elseif ($log->log_calendario) $sql->adOnde('log_calendario = '.(int)$log->log_calendario);
elseif ($log->log_monitoramento) $sql->adOnde('log_monitoramento = '.(int)$log->log_monitoramento);
elseif ($log->log_ata) $sql->adOnde('log_ata = '.(int)$log->log_ata);
elseif ($log->log_mswot) $sql->adOnde('log_mswot = '.(int)$log->log_mswot);
elseif ($log->log_swot) $sql->adOnde('log_swot = '.(int)$log->log_swot);
elseif ($log->log_operativo) $sql->adOnde('log_operativo = '.(int)$log->log_operativo);
elseif ($log->log_instrumento) $sql->adOnde('log_instrumento = '.(int)$log->log_instrumento);
elseif ($log->log_recurso) $sql->adOnde('log_recurso = '.(int)$log->log_recurso);
elseif ($log->log_problema) $sql->adOnde('log_problema = '.(int)$log->log_problema);
elseif ($log->log_demanda) $sql->adOnde('log_demanda = '.(int)$log->log_demanda);
elseif ($log->log_programa) $sql->adOnde('log_programa = '.(int)$log->log_programa);
elseif ($log->log_licao) $sql->adOnde('log_licao = '.(int)$log->log_licao);
elseif ($log->log_evento) $sql->adOnde('log_evento = '.(int)$log->log_evento);
elseif ($log->log_link) $sql->adOnde('log_link = '.(int)$log->log_link);
elseif ($log->log_avaliacao) $sql->adOnde('log_avaliacao = '.(int)$log->log_avaliacao);
elseif ($log->log_tgn) $sql->adOnde('log_tgn = '.(int)$log->log_tgn);
elseif ($log->log_brainstorm) $sql->adOnde('log_brainstorm = '.(int)$log->log_brainstorm);
elseif ($log->log_gut) $sql->adOnde('log_gut = '.(int)$log->log_gut);
elseif ($log->log_causa_efeito) $sql->adOnde('log_causa_efeito = '.(int)$log->log_causa_efeito);
elseif ($log->log_arquivo) $sql->adOnde('log_arquivo = '.(int)$log->log_arquivo);
elseif ($log->log_forum) $sql->adOnde('log_forum = '.(int)$log->log_forum);
elseif ($log->log_checklist) $sql->adOnde('log_checklist = '.(int)$log->log_checklist);
elseif ($log->log_agenda) $sql->adOnde('log_agenda = '.(int)$log->log_agenda);
elseif ($log->log_agrupamento) $sql->adOnde('log_agrupamento = '.(int)$log->log_agrupamento);
elseif ($log->log_patrocinador) $sql->adOnde('log_patrocinador = '.(int)$log->log_patrocinador);
elseif ($log->log_template) $sql->adOnde('log_template = '.(int)$log->log_template);
elseif ($log->log_painel) $sql->adOnde('log_painel = '.(int)$log->log_painel);
elseif ($log->log_painel_odometro) $sql->adOnde('log_painel_odometro = '.(int)$log->log_painel_odometro);
elseif ($log->log_painel_composicao) $sql->adOnde('log_painel_composicao = '.(int)$log->log_painel_composicao);
elseif ($log->log_tr) $sql->adOnde('log_tr = '.(int)$log->log_tr);
elseif ($log->log_me) $sql->adOnde('log_me = '.(int)$log->log_me);
elseif ($log->log_acao_item) $sql->adOnde('log_acao_item = '.(int)$log->log_acao_item);
elseif ($log->log_beneficio) $sql->adOnde('log_beneficio = '.(int)$log->log_beneficio);
elseif ($log->log_painel_slideshow) $sql->adOnde('log_painel_slideshow = '.(int)$log->log_painel_slideshow);
elseif ($log->log_projeto_viabilidade) $sql->adOnde('log_projeto_viabilidade = '.(int)$log->log_projeto_viabilidade);
elseif ($log->log_projeto_abertura) $sql->adOnde('log_projeto_abertura = '.(int)$log->log_projeto_abertura);
elseif ($log->log_plano_gestao) $sql->adOnde('log_plano_gestao = '.(int)$log->log_plano_gestao);
elseif ($log->log_ssti) $sql->adOnde('log_ssti = '.(int)$log->log_ssti);
elseif ($log->log_laudo) $sql->adOnde('log_laudo = '.(int)$log->log_laudo);
elseif ($log->log_trelo) $sql->adOnde('log_trelo = '.(int)$log->log_trelo);
elseif ($log->log_trelo_cartao) $sql->adOnde('log_trelo_cartao = '.(int)$log->log_trelo_cartao);
elseif ($log->log_pdcl) $sql->adOnde('log_pdcl = '.(int)$log->log_pdcl);
elseif ($log->log_pdcl_item) $sql->adOnde('log_pdcl_item = '.(int)$log->log_pdcl_item);

$sql->adOrdem('log_data');
$linhas=array(null=>'')+$sql->listaVetorChave('log_id','nome');
$sql->limpar();
if (count($linhas) > 1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Solução de problema', 'Caso este registro de ocorrência seja a correção de problema apontado em outro registro, informe qual registro está sendo solucionado.').'Solução de problema:'.dicaF().'</td><td>'.selecionaVetor($linhas, 'log_correcao', 'class="texto" style="width:400px;"', $log->log_correcao).'</td></tr>';
else echo '<input type="hidden" name="log_correcao" id="log_correcao" value="">';


echo '<tr><td align="right" valign="middle">'.dica('Referência', 'Escolha de que forma chegou aos dados que aqui estão registrados.').'Referência:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'log_referencia', 'size="1" class="texto" style="width:400px;"', $log->log_referencia).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', 'O registro de ocorrência pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td>'.selecionaVetor($niveis_acesso, 'log_acesso', 'class="texto" style="width:400px;"', ($log_id ? $log->log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right">'.dica('Endereço Eletrônico desta Referência', 'Escreva, caso exista, um link para página ou arquivo na rede que faz referência a este registro tal como visualiza na tela no Navegador Web.').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="log_url_relacionada" value="'.($log->log_url_relacionada).'" style="width:395px;" maxlength="255" /></td></tr>';









require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('log', $log->log_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_custos\').style.display) document.getElementById(\'apresentar_custos\').style.display=\'\'; else document.getElementById(\'apresentar_custos\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Custo/Gasto</b></a></td></tr>';
echo '<tr id="apresentar_custos" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';


$sql->adTabela('pi');
if ($log->log_projeto) $sql->adOnde('pi_projeto  = '.(int)$log->log_projeto);
elseif ($log->log_perspectiva) $sql->adOnde('pi_perspectiva = '.(int)$log->log_perspectiva);
elseif ($log->log_tema) $sql->adOnde('pi_tema  = '.(int)$log->log_tema);
elseif ($log->log_objetivo) $sql->adOnde('pi_objetivo  = '.(int)$log->log_objetivo);
elseif ($log->log_fator) $sql->adOnde('pi_fator  = '.(int)$log->log_fator);
elseif ($log->log_estrategia) $sql->adOnde('pi_estrategia  = '.(int)$log->log_estrategia);
elseif ($log->log_meta) $sql->adOnde('pi_meta = '.(int)$log->log_meta);
elseif ($log->log_pratica) $sql->adOnde('pi_pratica   = '.(int)$log->log_pratica);
elseif ($log->log_acao) $sql->adOnde('pi_acao  = '.(int)$log->log_acao);
elseif ($log->log_canvas) $sql->adOnde('pi_canvas  = '.(int)$log->log_canvas);
elseif ($log->log_risco) $sql->adOnde('pi_risco  = '.(int)$log->log_risco);
elseif ($log->log_risco_resposta) $sql->adOnde('pi_risco_resposta = '.(int)$log->log_risco_resposta);
elseif ($log->log_indicador) $sql->adOnde('pi_indicador = '.(int)$log->log_indicador);
elseif ($log->log_calendario) $sql->adOnde('pi_calendario = '.(int)$log->log_calendario);
elseif ($log->log_monitoramento) $sql->adOnde('pi_monitoramento = '.(int)$log->log_monitoramento);
elseif ($log->log_ata) $sql->adOnde('pi_ata = '.(int)$log->log_ata);
elseif ($log->log_mswot) $sql->adOnde('pi_mswot = '.(int)$log->log_mswot);
elseif ($log->log_swot) $sql->adOnde('pi_swot = '.(int)$log->log_swot);
elseif ($log->log_operativo) $sql->adOnde('pi_operativo = '.(int)$log->log_operativo);
elseif ($log->log_instrumento) $sql->adOnde('pi_instrumento = '.(int)$log->log_instrumento);
elseif ($log->log_recurso) $sql->adOnde('pi_recurso = '.(int)$log->log_recurso);
elseif ($log->log_problema) $sql->adOnde('pi_problema = '.(int)$log->log_problema);
elseif ($log->log_demanda) $sql->adOnde('pi_demanda = '.(int)$log->log_demanda);
elseif ($log->log_programa) $sql->adOnde('pi_programa = '.(int)$log->log_programa);
elseif ($log->log_licao) $sql->adOnde('pi_licao = '.(int)$log->log_licao);
elseif ($log->log_evento) $sql->adOnde('pi_evento = '.(int)$log->log_evento);
elseif ($log->log_link) $sql->adOnde('pi_link = '.(int)$log->log_link);
elseif ($log->log_avaliacao) $sql->adOnde('pi_avaliacao = '.(int)$log->log_avaliacao);
elseif ($log->log_tgn) $sql->adOnde('pi_tgn = '.(int)$log->log_tgn);
elseif ($log->log_brainstorm) $sql->adOnde('pi_brainstorm = '.(int)$log->log_brainstorm);
elseif ($log->log_gut) $sql->adOnde('pi_gut = '.(int)$log->log_gut);
elseif ($log->log_causa_efeito) $sql->adOnde('pi_causa_efeito = '.(int)$log->log_causa_efeito);
elseif ($log->log_arquivo) $sql->adOnde('pi_arquivo = '.(int)$log->log_arquivo);
elseif ($log->log_forum) $sql->adOnde('pi_forum = '.(int)$log->log_forum);
elseif ($log->log_checklist) $sql->adOnde('pi_checklist = '.(int)$log->log_checklist);
elseif ($log->log_agenda) $sql->adOnde('pi_agenda = '.(int)$log->log_agenda);
elseif ($log->log_agrupamento) $sql->adOnde('pi_agrupamento = '.(int)$log->log_agrupamento);
elseif ($log->log_patrocinador) $sql->adOnde('pi_patrocinador = '.(int)$log->log_patrocinador);
elseif ($log->log_template) $sql->adOnde('pi_template = '.(int)$log->log_template);
elseif ($log->log_painel) $sql->adOnde('pi_painel = '.(int)$log->log_painel);
elseif ($log->log_painel_odometro) $sql->adOnde('pi_painel_odometro = '.(int)$log->log_painel_odometro);
elseif ($log->log_painel_composicao) $sql->adOnde('pi_painel_composicao = '.(int)$log->log_painel_composicao);
elseif ($log->log_tr) $sql->adOnde('pi_tr = '.(int)$log->log_tr);
elseif ($log->log_me) $sql->adOnde('pi_me = '.(int)$log->log_me);
elseif ($log->log_acao_item) $sql->adOnde('pi_acao_item = '.(int)$log->log_acao_item);
elseif ($log->log_beneficio) $sql->adOnde('pi_beneficio = '.(int)$log->log_beneficio);
elseif ($log->log_painel_slideshow) $sql->adOnde('pi_painel_slideshow = '.(int)$log->log_painel_slideshow);
elseif ($log->log_projeto_viabilidade) $sql->adOnde('pi_projeto_viabilidade = '.(int)$log->log_projeto_viabilidade);
elseif ($log->log_projeto_abertura) $sql->adOnde('pi_projeto_abertura = '.(int)$log->log_projeto_abertura);
elseif ($log->log_plano_gestao) $sql->adOnde('pi_plano_gestao = '.(int)$log->log_plano_gestao);

elseif ($log->log_ssti) $sql->adOnde('pi_ssti = '.(int)$log->log_ssti);
elseif ($log->log_laudo) $sql->adOnde('pi_laudo = '.(int)$log->log_laudo);
elseif ($log->log_trelo) $sql->adOnde('pi_trelo = '.(int)$log->log_trelo);
elseif ($log->log_trelo_cartao) $sql->adOnde('pi_trelo_cartao = '.(int)$log->log_trelo_cartao);
elseif ($log->log_pdcl) $sql->adOnde('pi_pdcl = '.(int)$log->log_pdcl);
elseif ($log->log_pdcl_item) $sql->adOnde('pi_pdcl_item = '.(int)$log->log_pdcl_item);

$sql->adCampo('pi_pi');
$sql->adOrdem('pi_ordem');
$pi=array(''=>'')+$sql->listaVetorChave('pi_pi','pi_pi');
$sql->limpar();

$sql->adTabela('ptres');
if ($log->log_projeto) $sql->adOnde('ptres_projeto  = '.(int)$log->log_projeto);
elseif ($log->log_perspectiva) $sql->adOnde('ptres_perspectiva = '.(int)$log->log_perspectiva);
elseif ($log->log_tema) $sql->adOnde('ptres_tema  = '.(int)$log->log_tema);
elseif ($log->log_objetivo) $sql->adOnde('ptres_objetivo  = '.(int)$log->log_objetivo);
elseif ($log->log_fator) $sql->adOnde('ptres_fator  = '.(int)$log->log_fator);
elseif ($log->log_estrategia) $sql->adOnde('ptres_estrategia  = '.(int)$log->log_estrategia);
elseif ($log->log_meta) $sql->adOnde('ptres_meta = '.(int)$log->log_meta);
elseif ($log->log_pratica) $sql->adOnde('ptres_pratica   = '.(int)$log->log_pratica);
elseif ($log->log_acao) $sql->adOnde('ptres_acao  = '.(int)$log->log_acao);
elseif ($log->log_canvas) $sql->adOnde('ptres_canvas  = '.(int)$log->log_canvas);
elseif ($log->log_risco) $sql->adOnde('ptres_risco  = '.(int)$log->log_risco);
elseif ($log->log_risco_resposta) $sql->adOnde('ptres_risco_resposta = '.(int)$log->log_risco_resposta);
elseif ($log->log_indicador) $sql->adOnde('ptres_indicador = '.(int)$log->log_indicador);
elseif ($log->log_calendario) $sql->adOnde('ptres_calendario = '.(int)$log->log_calendario);
elseif ($log->log_monitoramento) $sql->adOnde('ptres_monitoramento = '.(int)$log->log_monitoramento);
elseif ($log->log_ata) $sql->adOnde('ptres_ata = '.(int)$log->log_ata);
elseif ($log->log_mswot) $sql->adOnde('ptres_mswot = '.(int)$log->log_mswot);
elseif ($log->log_swot) $sql->adOnde('ptres_swot = '.(int)$log->log_swot);
elseif ($log->log_operativo) $sql->adOnde('ptres_operativo = '.(int)$log->log_operativo);
elseif ($log->log_instrumento) $sql->adOnde('ptres_instrumento = '.(int)$log->log_instrumento);
elseif ($log->log_recurso) $sql->adOnde('ptres_recurso = '.(int)$log->log_recurso);
elseif ($log->log_problema) $sql->adOnde('ptres_problema = '.(int)$log->log_problema);
elseif ($log->log_demanda) $sql->adOnde('ptres_demanda = '.(int)$log->log_demanda);
elseif ($log->log_programa) $sql->adOnde('ptres_programa = '.(int)$log->log_programa);
elseif ($log->log_licao) $sql->adOnde('ptres_licao = '.(int)$log->log_licao);
elseif ($log->log_evento) $sql->adOnde('ptres_evento = '.(int)$log->log_evento);
elseif ($log->log_link) $sql->adOnde('ptres_link = '.(int)$log->log_link);
elseif ($log->log_avaliacao) $sql->adOnde('ptres_avaliacao = '.(int)$log->log_avaliacao);
elseif ($log->log_tgn) $sql->adOnde('ptres_tgn = '.(int)$log->log_tgn);
elseif ($log->log_brainstorm) $sql->adOnde('ptres_brainstorm = '.(int)$log->log_brainstorm);
elseif ($log->log_gut) $sql->adOnde('ptres_gut = '.(int)$log->log_gut);
elseif ($log->log_causa_efeito) $sql->adOnde('ptres_causa_efeito = '.(int)$log->log_causa_efeito);
elseif ($log->log_arquivo) $sql->adOnde('ptres_arquivo = '.(int)$log->log_arquivo);
elseif ($log->log_forum) $sql->adOnde('ptres_forum = '.(int)$log->log_forum);
elseif ($log->log_checklist) $sql->adOnde('ptres_checklist = '.(int)$log->log_checklist);
elseif ($log->log_agenda) $sql->adOnde('ptres_agenda = '.(int)$log->log_agenda);
elseif ($log->log_agrupamento) $sql->adOnde('ptres_agrupamento = '.(int)$log->log_agrupamento);
elseif ($log->log_patrocinador) $sql->adOnde('ptres_patrocinador = '.(int)$log->log_patrocinador);
elseif ($log->log_template) $sql->adOnde('ptres_template = '.(int)$log->log_template);
elseif ($log->log_painel) $sql->adOnde('ptres_painel = '.(int)$log->log_painel);
elseif ($log->log_painel_odometro) $sql->adOnde('ptres_painel_odometro = '.(int)$log->log_painel_odometro);
elseif ($log->log_painel_composicao) $sql->adOnde('ptres_painel_composicao = '.(int)$log->log_painel_composicao);
elseif ($log->log_tr) $sql->adOnde('ptres_tr = '.(int)$log->log_tr);
elseif ($log->log_me) $sql->adOnde('ptres_me = '.(int)$log->log_me);
elseif ($log->log_acao_item) $sql->adOnde('ptres_acao_item = '.(int)$log->log_acao_item);
elseif ($log->log_beneficio) $sql->adOnde('ptres_beneficio = '.(int)$log->log_beneficio);
elseif ($log->log_painel_slideshow) $sql->adOnde('ptres_painel_slideshow = '.(int)$log->log_painel_slideshow);
elseif ($log->log_projeto_viabilidade) $sql->adOnde('ptres_projeto_viabilidade = '.(int)$log->log_projeto_viabilidade);
elseif ($log->log_projeto_abertura) $sql->adOnde('ptres_projeto_abertura = '.(int)$log->log_projeto_abertura);
elseif ($log->log_plano_gestao) $sql->adOnde('ptres_plano_gestao = '.(int)$log->log_plano_gestao);

elseif ($log->log_ssti) $sql->adOnde('ptres_ssti = '.(int)$log->log_ssti);
elseif ($log->log_laudo) $sql->adOnde('ptres_laudo = '.(int)$log->log_laudo);
elseif ($log->log_trelo) $sql->adOnde('ptres_trelo = '.(int)$log->log_trelo);
elseif ($log->log_trelo_cartao) $sql->adOnde('ptres_trelo_cartao = '.(int)$log->log_trelo_cartao);
elseif ($log->log_pdcl) $sql->adOnde('ptres_pdcl = '.(int)$log->log_pdcl);
elseif ($log->log_pdcl_item) $sql->adOnde('ptres_pdcl_item = '.(int)$log->log_pdcl_item);

$sql->adCampo('ptres_ptres');
$sql->adOrdem('ptres_ordem');
$ptres=array(''=>'')+$sql->listaVetorChave('ptres_ptres','ptres_ptres');
$sql->limpar();

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0>';

echo '<input type="hidden" name="custo_id" id="custo_id" value="" />';
echo '<input type="hidden" name="apoio1" id="apoio1" value="" />';
echo '<input type="hidden" name="antigo_gasto" id="antigo_gasto" value="" />';

echo '<tr><td align="right" style="white-space: nowrap" width=144>'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="custo_nome" id="custo_nome" value="" maxlength="255" style="width:391px;" /></td></tr>';
$custo_gasto=array(0=>'Custo', 1=>'Gasto');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo', 'O item é um custo planjado ou um gasto efetuado.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($custo_gasto, 'custo_gasto', 'class=texto size=1 style="width:395px;"').'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida deste item.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'custo_tipo', 'class=texto size=1 style="width:395px;"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="custo_quantidade" id="custo_quantidade" value="" maxlength="255" style="width:391px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Unitário', 'Insira o valor deste item.').'Valor unitário ('.$config['simbolo_moeda'].'):'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="custo_custo" id="custo_custo" value="" style="width:391px;" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda utilizada neste item.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'custo_moeda', 'class=texto size=1 style="width:395px;" onchange="mudar_moeda(this.value)"', 1).'</td></tr>';
echo '<tr id="combo_data_moeda"><td align="right">'.dica('Data da Cotação','Data da cotação da moeda.').'Data da cotação:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="custo_data_moeda" id="custo_data_moeda" value="'.($data_texto ? $data_texto->format('%Y%m%d') : '').'" /><input type="text" name="data2_texto"  id="data2_texto" style="width:70px;" onchange="setData(\'env\', \'data2_texto\', \'custo_data_moeda\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data da Cotação', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data da cotação da moeda estrangeira.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário2" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';

if ($config['bdi']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%):'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="custo_bdi" id="custo_bdi" value="" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_bdi" id="custo_bdi" value="0" />';

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'custo_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="env.custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'custo_grupo_despesa', 'class=texto size=1 style="width:395px;"  onchange="env.custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'custo_modalidade_aplicacao', 'class=texto size=1 style="width:395px;"  onchange="env.custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
$nd=vetor_nd('', null, null, 3 ,'', '');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</div></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descrição do Item', 'Insira a descrição deste item.').'Descrição do item:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="5" class="textarea" name="custo_descricao" id="custo_descricao" style="width:395px;"></textarea></td></tr>';

if (isset($exibir['codigo']) && $exibir['codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['codigo_valor']), 'Insira '.$config['genero_codigo_valor'].' '.$config['codigo_valor'].' deste item.').ucfirst($config['codigo_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="custo_codigo" id="custo_codigo" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_codigo" id="custo_codigo" value="" />';

if (isset($exibir['fonte']) && $exibir['fonte']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fonte_valor']), 'Insira '.$config['genero_fonte_valor'].' '.$config['fonte_valor'].' deste item.').ucfirst($config['fonte_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="custo_fonte" id="custo_fonte" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_fonte" id="custo_fonte" value="" />';

if (isset($exibir['regiao']) && $exibir['regiao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['regiao_valor']), 'Insira '.$config['genero_regiao_valor'].' '.$config['regiao_valor'].' deste item.').ucfirst($config['regiao_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="custo_regiao" id="custo_regiao" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_regiao" id="custo_regiao" value="" />';	


if (count($pi)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('PI', 'Escolha o Plano Interno deste item.').'PI:'.dicaF().'</td><td>'.selecionaVetor($pi, 'custo_pi', 'class=texto size=1 style="width:395px;"').'</td></tr>';
else echo '<input type="hidden" name="custo_pi" id="custo_pi" value="" />';

if (count($ptres)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('PTRES', 'Escolha o Plano de Trabalho Resumido deste item.').'PTRES:'.dicaF().'</td><td>'.selecionaVetor($ptres, 'custo_ptres', 'class=texto size=1 style="width:395px;"').'</td></tr>';
else echo '<input type="hidden" name="custo_ptres" id="custo_ptres" value="" />';

$data_limite = new CData();
echo '<tr><td align="right">'.dica('Data para Recebimento','Data limite para o recebimento do ítem.').'Data para recebimento:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="custo_data_limite" id="custo_data_limite" value="'.($data_limite ? $data_limite->format('%Y%m%d') : '').'" /><input type="text" name="data_texto" id="data_texto" style="width:65px;" onchange="setData(\'env\', \'data_texto\', \'custo_data_limite\');" value="'.($data_limite ? $data_limite->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite para o recebimento do ítem.').'<a href="javascript: void(0);" ><img id="f_btn3" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Total', 'O valor total do item.').'Total:'.dicaF().'</td><td><div id="total"></div></td></tr>';

echo '</table></td>';
echo '<td id="adicionar_custo" style="display:"><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar_g.png').' para incluir o item.').'</a></td>';
echo '<td id="confirmar_custo" style="display:none"><a href="javascript: void(0);" onclick="limpar_custo();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do item.').'</a><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do item.').'</a></td>';
echo '</tr></table></td></tr>';

//planilha de custo
$sql->adTabela('custo');
$sql->adCampo('custo.*, ((custo_quantidade*custo_custo)*((100+custo_bdi)/100)) AS valor ');
$sql->adOnde('custo_log ='.(int)$log->log_id);
$sql->adOnde('custo_gasto !=1');
$sql->adOrdem('custo_ordem');
$linhas= $sql->Lista();
$qnt=0;

$ptres=0;
$pi=0;
foreach($linhas as $linha){
	if ($linha['custo_ptres']) $ptres++;
	if ($linha['custo_pi']) $pi++;
	}

echo '<tr><td></td><td colspan=19><div id="combo_custo">';
if (count($linhas)){
	echo '<table '.($dialogo ? 'width=1080' : '').' cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th colspan=20>Planilha de Custos Estimados</th></tr>';
		echo '<tr>'.(!$dialogo ? '<th></th>' : '').'
	<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
	'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	($pi ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
	($ptres ? '<th>'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').
	(!$dialogo ? '<th></th>' : '').'</tr>';
	}
$total=array();
$custo=array();
foreach ($linhas as $linha) {
	echo '<tr align="center">';

	if (!$dialogo) {
		echo '<td width="40" align="right">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverPrimeiro\', false);"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaCima\', false);"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaBaixo\', false);"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverUltimo\', false);"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}

	echo '<td align="left">'.++$qnt.' - '.$linha['custo_nome'].'</td>';
	echo '<td align="left">'.($linha['custo_descricao'] ? $linha['custo_descricao'] : '&nbsp;').'</td>';
	echo '<td>'.$unidade[$linha['custo_tipo']].'</td><td>'.number_format($linha['custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['custo_bdi'], 2, ',', '.').'</td>';
	$nd=($linha['custo_categoria_economica'] && $linha['custo_grupo_despesa'] && $linha['custo_modalidade_aplicacao'] ? $linha['custo_categoria_economica'].'.'.$linha['custo_grupo_despesa'].'.'.$linha['custo_modalidade_aplicacao'].'.' : '').$linha['custo_nd'];
	echo '<td>'.$nd.'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo '<td align="center">'.($linha['custo_codigo'] ? $linha['custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo '<td align="center">'.($linha['custo_fonte'] ? $linha['custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo '<td align="center">'.($linha['custo_regiao'] ? $linha['custo_regiao'] : '&nbsp;').'</td>'; 
	
	
	
	echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['custo_usuario'],'','','esquerda').'</td>';
	echo '<td>'.($linha['custo_data_limite']? retorna_data($linha['custo_data_limite'],false) : '&nbsp;').'</td>';
	if ($pi) echo '<td align="center">'.$linha['custo_pi'].'</td>';
	if ($ptres) echo '<td align="center">'.$linha['custo_ptres'].'</td>';
	
	if (!$dialogo) {
		echo '<td width="32">';
		echo dica('Editar Item', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['custo_id'].', false);">'.imagem('icones/editar.gif').'</a>'.dicaF();
		echo dica('Excluir Item', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['custo_id'].', false);">'.imagem('icones/remover.png').'</a>'.dicaF();
		echo '</td>';
		}
	echo '</tr>';
	
	if (isset($custo[$linha['custo_moeda']][$nd])) $custo[$linha['custo_moeda']][$nd] += (float)$linha['valor'];
	else $custo[$linha['custo_moeda']][$nd]=(float)$linha['valor'];
	
	if (isset($total[$linha['custo_moeda']])) $total[$linha['custo_moeda']]+=$linha['valor'];
	else $total[$linha['custo_moeda']]=$linha['valor']; 
	
	}

$tem_total=false;
foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
	
if ($tem_total) {
	foreach ($custo as $tipo_moeda => $linha) {
		echo '<tr><td colspan="'.($config['bdi'] ? 8 : 7).'" class="std" align="right">';
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}		
if (count($linhas)) echo '</table>';

echo '</div></td></tr>';






//planilha de gasto
$sql->adTabela('custo');
$sql->adCampo('custo.*, ((custo_quantidade*custo_custo)*((100+custo_bdi)/100)) AS valor ');
$sql->adOnde('custo_log ='.(int)$log->log_id);
$sql->adOnde('custo_gasto=1');
$sql->adOrdem('custo_ordem');
$linhas= $sql->Lista();
$qnt=0;

$ptres=0;
$pi=0;
foreach($linhas as $linha){
	if ($linha['custo_ptres']) $ptres++;
	if ($linha['custo_pi']) $pi++;
	}

echo '<tr><td></td><td colspan=19><div id="combo_gasto">';
if (count($linhas)){
	echo '<table '.($dialogo ? 'width=1080' : '').' cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th colspan=20>Planilha de Gastos Efetuados</th></tr>';
	echo '<tr>'.(!$dialogo ? '<th></th>' : '').'
	<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
	'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	($pi ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
	($ptres ? '<th>'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').
	(!$dialogo ? '<th></th>' : '').'</tr>';
	}
$total=array();
$custo=array();
foreach ($linhas as $linha) {
	echo '<tr align="center">';
	if (!$dialogo) {
		echo '<td width="40" align="right">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverPrimeiro\', true);"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaCima\', true);"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaBaixo\', true);"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverUltimo\', true);"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td align="left">'.++$qnt.' - '.$linha['custo_nome'].'</td>';
	echo '<td align="left">'.($linha['custo_descricao'] ? $linha['custo_descricao'] : '&nbsp;').'</td>';
	echo '<td>'.$unidade[$linha['custo_tipo']].'</td><td>'.number_format($linha['custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['custo_bdi'], 2, ',', '.').'</td>';
	$nd=($linha['custo_categoria_economica'] && $linha['custo_grupo_despesa'] && $linha['custo_modalidade_aplicacao'] ? $linha['custo_categoria_economica'].'.'.$linha['custo_grupo_despesa'].'.'.$linha['custo_modalidade_aplicacao'].'.' : '').$linha['custo_nd'];
	echo '<td>'.$nd.'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo '<td align="center">'.($linha['custo_codigo'] ? $linha['custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte'])  echo '<td align="center">'.($linha['custo_fonte'] ? $linha['custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo '<td align="center">'.($linha['custo_regiao'] ? $linha['custo_regiao'] : '&nbsp;').'</td>'; 
	
	echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['custo_usuario'],'','','esquerda').'</td>';
	echo '<td>'.($linha['custo_data_limite']? retorna_data($linha['custo_data_limite'],false) : '&nbsp;').'</td>';
	if ($pi) echo '<td align="center">'.$linha['custo_pi'].'</td>';
	if ($ptres) echo '<td align="center">'.$linha['custo_ptres'].'</td>';
	if (!$dialogo) {
		echo '<td width="32">';
		echo dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['custo_id'].', true);">'.imagem('icones/editar.gif').'</a>'.dicaF();
		echo dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['custo_id'].', true);">'.imagem('icones/remover.png').'</a>'.dicaF();
		echo '</td>';
		}
	echo '</tr>';
	if (isset($custo[$nd])) $custo[$nd] += (float)$linha['valor'];
	else $custo[$nd] =(float)$linha['valor'];
	$total+=$linha['valor'];
	}
if ($total) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';
		foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';
		}
if (count($linhas)) echo '</table>';

echo '</div></td></tr>';

echo '</table></td></tr>';


echo '<tr><td height=2></td><tr>';


echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificacao\').style.display) document.getElementById(\'apresentar_notificacao\').style.display=\'\'; else document.getElementById(\'apresentar_notificacao\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificação</b></a></td></tr>';
echo '<tr id="apresentar_notificacao" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';



echo '<tr><td align="right" valign="top" width=144>'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($log_id > 0 ? 'modificação' : 'criação').' do registro.').'Notificar:'.dicaF().'</td><td><table><tr><td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável.').'<label for="email_responsavel">Responsável</label>'.dicaF().'</br>';
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados.').'<label for="email_designados">Designados</label>'.dicaF().'</br>';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro.','','popEmailContatos()');
echo ($config['email_ativo'] ? ''.($config['email_ativo'] ? dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" />' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />');
echo '</td></tr></table></td></tr>';


echo '</table></td></tr>';



echo '<tr><td>&nbsp;</td><td align="left"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a este registro de ocorrência.<br>Caso necessite anexar múltiplos arquivos basta clicar aqui sucessivamente para criar os campos necessários.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
echo '<tr><td colspan="20" align="left"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';
//arquivo anexo
echo '<tr><td colspan="2"><div id="combo_arquivos"><table cellspacing=0 cellpadding=0>';

$sql->adTabela('log_arquivo');
$sql->adCampo('log_arquivo_id, log_arquivo_usuario, log_arquivo_data, log_arquivo_ordem, log_arquivo_nome, log_arquivo_endereco');
$sql->adOnde('log_arquivo_log='.(int)$log_id);
$sql->adOrdem('log_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos)) echo '<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
foreach ($arquivos as $arquivo) {
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=perspectivas&a=log_download&sem_cabecalho=1&log_arquivo_id='.$arquivo['log_arquivo_id'].'\');">'.$arquivo['log_arquivo_nome'].'</a></td>';
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_arquivo('.$arquivo['log_arquivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr></table></td></tr>';
	}
echo '</table></div></td></tr>';

echo '<tr><td colspan=20><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','salvar()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';

echo '</table>';

echo '</form>';

echo estiloFundoCaixa();
?>
<script type="text/javascript">

function mudar_moeda(moeda){
	//if (moeda > 1) document.getElementById('combo_data_moeda').style.display='';
	//else document.getElementById('combo_data_moeda').style.display='none';
	}
	
function editar_custo(custo_id, gasto){
	xajax_editar_custo(custo_id);
	<?php if ($Aplic->profissional) { ?>
		CKEDITOR.instances['custo_descricao'].setData(document.getElementById('apoio1').value);

	<?php } ?>
	document.getElementById('antigo_gasto').value=gasto;
	document.getElementById('adicionar_custo').style.display="none";
	document.getElementById('confirmar_custo').style.display="";
	}


function limpar_custo(){
	CKEDITOR.instances['custo_descricao'].setData('');
	document.getElementById('custo_nome').value='';
	document.getElementById('custo_descricao').value='';
	document.getElementById('custo_quantidade').value='';
	document.getElementById('custo_custo').value='';
	document.getElementById('custo_id').value=null;
	document.getElementById('adicionar_custo').style.display='';
	document.getElementById('confirmar_custo').style.display='none';
	document.getElementById('custo_gasto').disabled=false;
	document.getElementById('total').innerHTML='';
	}

function incluir_custo(edicao){
	xajax_incluir_custo(
		document.getElementById('log_id').value,
		document.getElementById('uuid').value,
		document.getElementById('custo_id').value,
		document.getElementById('custo_nome').value,
		document.getElementById('custo_tipo').value,
		document.getElementById('custo_quantidade').value,
		document.getElementById('custo_custo').value,
		CKEDITOR.instances['custo_descricao'].getData(),
		document.getElementById('custo_nd').value,
		document.getElementById('custo_categoria_economica').value,
		document.getElementById('custo_grupo_despesa').value,
		document.getElementById('custo_modalidade_aplicacao').value,
		document.getElementById('custo_data_limite').value,
		document.getElementById('custo_codigo').value,
		document.getElementById('custo_fonte').value,
		document.getElementById('custo_regiao').value,
		document.getElementById('custo_bdi').value,
		document.getElementById('custo_ptres').value,
		document.getElementById('custo_pi').value,
		document.getElementById('custo_gasto').value,
		document.getElementById('custo_moeda').value,
		document.getElementById('custo_data_moeda').value
		);
	__buildTooltip();
	limpar_custo();
	}

function excluir_custo(custo_id, gasto){
	if (confirm('Tem certeza que deseja excluir?')) {
		xajax_excluir_custo(custo_id, document.getElementById('log_id').value, document.getElementById('uuid').value, gasto);
		__buildTooltip();
		}
	}

function mudar_posicao_custo(ordem, custo_id, direcao, gasto){
	xajax_mudar_posicao_custo(ordem, custo_id, direcao, document.getElementById('log_id').value, document.getElementById('uuid').value, gasto);
	__buildTooltip();
	}


function mudar_nd(){
	xajax_mudar_nd(env.custo_nd.value, 'custo_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.custo_categoria_economica.value, env.custo_grupo_despesa.value, env.custo_modalidade_aplicacao.value);
	}


function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}



function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}


function valor(){
	var custo=moeda2float(document.getElementById('custo_custo').value);
	var qnt=moeda2float(document.getElementById('custo_quantidade').value);
	var bdi=moeda2float(document.getElementById('custo_bdi').value);
	if (custo=='') custo=0;
	if (valor=='') valor=0;
	if (bdi=='') bdi=0;
	document.getElementById('total').innerHTML ='<b>'+float2moeda((custo*qnt)*((100+bdi)/100))+'</b>';
	}



function excluir_arquivo(log_arquivo_id){
	xajax_excluir_arquivo(log_arquivo_id, document.getElementById('log_id').value);
	}

function mudar_posicao_arquivo(log_arquivo_ordem, log_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(log_arquivo_ordem, log_arquivo_id, direcao, document.getElementById('log_id').value);
	}


function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');

	var ta = document.createTextNode(' Arquivo:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'arquivo[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=80;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}



function mudar_nd(){
	xajax_mudar_nd(env.custo_nd.value, 'custo_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.custo_categoria_economica.value, env.custo_grupo_despesa.value, env.custo_modalidade_aplicacao.value);
	}

function salvar() {
	var descricao=CKEDITOR.instances['log_descricao'].getData();
	var f = document.env;
	if (descricao.length < 1) {
		alert( 'Por favor, insira uma descrição à ocorrência.' );
		f.log_descricao.focus();
		}
	else {
		f.submit();
		}
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}

function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_date").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("log_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide();
	}
});

var cal2 = Calendario.setup({
  	trigger    : "f_btn2",
    inputField : "custo_data_moeda",
  	date :  <?php echo $data_texto->format("%Y%m%d")?>,
  	selection: <?php echo $data_texto->format("%Y%m%d")?>,
    onSelect: function(cal2) {
    var date = cal2.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data2_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("custo_data_moeda").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide();
  	}
  });  



var cal3 = Calendario.setup({
  	trigger    : "f_btn3",
    inputField : "custo_data_limite",
  	date :  <?php echo $data_limite->format("%Y%m%d")?>,
  	selection: <?php echo $data_limite->format("%Y%m%d")?>,
    onSelect: function(cal3) {
    var date = cal3.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("custo_data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal3.hide();
  	}
  }); 

function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	parent.gpwebApp.popUp("Contatos", 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+email_outro.value, setEmailContatos, window);
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}

</script>




