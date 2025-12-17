<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if(!$Aplic->checarModulo('praticas', 'acesso', $Aplic->usuario_id, 'meta')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

$selecao=getParam($_REQUEST, 'selecao', null);
$chamarVolta=getParam($_REQUEST, 'chamarVolta', null);
$selecionado=getParam($_REQUEST, 'selecionado', null);
$edicao=getParam($_REQUEST, 'edicao', null);
if (!$dialogo && !$selecao) $Aplic->salvarPosicao();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['envolvimento']))	$Aplic->setEstado('envolvimento', getParam($_REQUEST, 'envolvimento', null));
$envolvimento = ($Aplic->getEstado('envolvimento') !== null ? $Aplic->getEstado('envolvimento') : null);

if (isset($_REQUEST['filtro_prioridade_meta']))	$Aplic->setEstado('filtro_prioridade_meta', getParam($_REQUEST, 'filtro_prioridade_meta', null));
$filtro_prioridade_meta = $Aplic->getEstado('filtro_prioridade_meta') !== null ? $Aplic->getEstado('filtro_prioridade_meta') : null;

$houve_filtro=getParam($_REQUEST, 'houve_filtro', null);
$sql->adTabela('favorito_trava');
$sql->adCampo('favorito_trava_campo');
$sql->adOnde('favorito_trava_meta=1');
$sql->adOnde('favorito_trava_usuario='.(int)$Aplic->usuario_id);
$favorito_trava_campo=$sql->Resultado();
$sql->limpar();
if (!$houve_filtro && $favorito_trava_campo) {
	$vetor_auxiliar=array();
	$vetor_filtro=explode('&', $favorito_trava_campo);
	foreach($vetor_filtro as $valor){
		$resultado=explode('=', $valor);
		if (isset($resultado[0]) && isset($resultado[1]) && $resultado[0]!='tab' && !stripos($resultado[0], '[]')) $_REQUEST[$resultado[0]]=$resultado[1];
		elseif (isset($resultado[0]) && isset($resultado[1]) && $resultado[0]!='tab' && stripos($resultado[0], '[]')) {
			$vetor_auxiliar[str_replace('[]' , '', $resultado[0])][]=$resultado[1];
			}
		}
	foreach($vetor_auxiliar as $chave => $valor) $_REQUEST[$chave]=$valor;
	}

if (isset($_REQUEST['metatextobusca'])) $Aplic->setEstado('metatextobusca',getParam($_REQUEST, 'metatextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('metatextobusca') ? $Aplic->getEstado('metatextobusca') : '');

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if (isset($_REQUEST['filtro_pg_id'])) $Aplic->setEstado('filtro_pg_id', getParam($_REQUEST, 'filtro_pg_id', null));
$filtro_pg_id = ($Aplic->getEstado('filtro_pg_id') !== null ? $Aplic->getEstado('filtro_pg_id') : null);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaMetaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaMetaTab') !== null ? $Aplic->getEstado('ListaMetaTab') : 0);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : null;
if ($dept_id) $ver_subordinadas = null;

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}

if (isset($_REQUEST['favorito_id'])) $Aplic->setEstado('meta_favorito', getParam($_REQUEST, 'favorito_id', null));
$favorito_id = $Aplic->getEstado('meta_favorito') !== null ? $Aplic->getEstado('meta_favorito') : 0;

$sql->adTabela('favorito');
$sql->adCampo('favorito_id, favorito_nome');
$sql->adOnde('favorito_geral!=1');
$sql->adOnde('favorito_ativo=1');
$sql->adOnde('favorito_meta=1');
$sql->adOnde('favorito_usuario='.$Aplic->usuario_id);
$vetor_favoritos=$sql->ListaChave();
$sql->limpar();

$sql->adTabela('favorito');
$sql->esqUnir('favorito_usuario', 'favorito_usuario', 'favorito_usuario_favorito= favorito.favorito_id');
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('favorito_dept','favorito_dept', 'favorito_dept_favorito=favorito.favorito_id');
	$sql->adOnde('favorito_dept='.(int)$dept_id.' OR favorito_dept_dept='.(int)$dept_id.' OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('favorito_dept','favorito_dept', 'favorito_dept_favorito=favorito.favorito_id');
	$sql->adOnde('favorito_dept IN ('.$lista_depts.') OR favorito_dept_dept IN ('.$lista_depts.') OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('favorito_cia', 'favorito_cia', 'favorito.favorito_id=favorito_cia_favorito');
	$sql->adOnde('favorito_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR favorito_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
	}		
elseif ($cia_id && !$lista_cias) $sql->adOnde('favorito_cia='.(int)$cia_id.' OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
elseif ($lista_cias) $sql->adOnde('favorito_cia IN ('.$lista_cias.') OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
$sql->adOnde('favorito_acesso=1');
$sql->adOnde('favorito_geral=1');
$sql->adOnde('favorito_ativo=1');
$sql->adOnde('favorito_meta=1');
$sql->adCampo('favorito_id, favorito_nome');
$vetor_favoritos1=$sql->ListaChave();
$sql->limpar();

$sql->adTabela('favorito');
$sql->esqUnir('favorito_usuario', 'favorito_usuario', 'favorito_usuario_favorito= favorito.favorito_id');
$sql->adOnde('favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
$sql->adOnde('favorito_acesso=2');
$sql->adOnde('favorito_geral=1');
$sql->adOnde('favorito_ativo=1');
$sql->adOnde('favorito_meta=1');
$sql->adCampo('favorito_id, favorito_nome');
$vetor_favoritos2=$sql->ListaChave();
$sql->limpar();

$vetor_favoritos=$vetor_favoritos+$vetor_favoritos1+$vetor_favoritos2;

$favoritos='';
if (count($vetor_favoritos)) {
	$vetor_favoritos[0]='';
	if (!isset($vetor_favoritos[(int)$favorito_id])) $favorito_id=0;
	$favoritos='<tr><td align="right" style="white-space: nowrap">'.dica('Favorito', 'Escolha um favorito.').'Favorito:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($vetor_favoritos, 'favorito_id', 'class="texto" style="width:250px;" onchange="document.env.submit()"', $favorito_id).'</td></tr>';
	}
else $favorito_id=null;

$sql->adTabela('plano_gestao');
$sql->esqUnir('plano_gestao_cia', 'plano_gestao_cia', 'plano_gestao_cia_plano=plano_gestao.pg_id');
$sql->adCampo('DISTINCT pg_id, pg_nome');
if ($ver_subordinadas) $sql->adOnde('pg_cia IN ('.$lista_cias.') OR plano_gestao_cia_cia IN ('.$lista_cias.')');
else $sql->adOnde('pg_cia ='.(int)$cia_id.' OR plano_gestao_cia_cia='.(int)$cia_id);
if ($ver_dept_subordinados && $lista_depts) $sql->adOnde('pg_dept IN ('.$lista_depts.')');
elseif ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
$sql->adOrdem('pg_nome ASC');
$planos=array(0=>'')+$sql->listaVetorChave('pg_id','pg_nome');
$sql->limpar();




if (isset($_REQUEST['tarefa_id'])) $Aplic->setEstado('tarefa_id', getParam($_REQUEST,'tarefa_id', null));
$tarefa_id  = $Aplic->getEstado('tarefa_id', null);

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST,'projeto_id', null));
$projeto_id  = $Aplic->getEstado('projeto_id', null);

if (isset($_REQUEST['pg_perspectiva_id'])) $Aplic->setEstado('pg_perspectiva_id', getParam($_REQUEST,'pg_perspectiva_id', null));
$pg_perspectiva_id  = $Aplic->getEstado('pg_perspectiva_id', null);

if (isset($_REQUEST['tema_id'])) $Aplic->setEstado('tema_id', getParam($_REQUEST,'tema_id', null));
$tema_id  = $Aplic->getEstado('tema_id', null);

if (isset($_REQUEST['objetivo_id'])) $Aplic->setEstado('objetivo_id', getParam($_REQUEST,'objetivo_id', null));
$objetivo_id  = $Aplic->getEstado('objetivo_id', null);

if (isset($_REQUEST['fator_id'])) $Aplic->setEstado('fator_id', getParam($_REQUEST,'fator_id', null));
$fator_id  = $Aplic->getEstado('fator_id', null);

if (isset($_REQUEST['pg_estrategia_id'])) $Aplic->setEstado('pg_estrategia_id', getParam($_REQUEST,'pg_estrategia_id', null));
$pg_estrategia_id = $Aplic->getEstado('pg_estrategia_id', null);

if (isset($_REQUEST['pg_meta_id'])) $Aplic->setEstado('pg_meta_id', getParam($_REQUEST,'pg_meta_id', null));
$pg_meta_id  = $Aplic->getEstado('pg_meta_id', null);

if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('pratica_id', getParam($_REQUEST,'pratica_id', null));
$pratica_id  = $Aplic->getEstado('pratica_id', null);

if (isset($_REQUEST['pratica_indicador_id'])) $Aplic->setEstado('pratica_indicador_id', getParam($_REQUEST,'pratica_indicador_id', null));
$pratica_indicador_id  = $Aplic->getEstado('pratica_indicador_id', null);

if (isset($_REQUEST['plano_acao_id'])) $Aplic->setEstado('plano_acao_id', getParam($_REQUEST,'plano_acao_id', null));
$plano_acao_id  = $Aplic->getEstado('plano_acao_id', null);

if (isset($_REQUEST['canvas_id'])) $Aplic->setEstado('canvas_id', getParam($_REQUEST,'canvas_id', null));
$canvas_id  = $Aplic->getEstado('canvas_id', null);

if (isset($_REQUEST['risco_id'])) $Aplic->setEstado('risco_id', getParam($_REQUEST,'risco_id', null));
$risco_id = $Aplic->getEstado('risco_id', null);

if (isset($_REQUEST['risco_resposta_id'])) $Aplic->setEstado('risco_resposta_id', getParam($_REQUEST,'risco_resposta_id', null));
$risco_resposta_id = $Aplic->getEstado('risco_resposta_id', null);

if (isset($_REQUEST['calendario_id'])) $Aplic->setEstado('calendario_id', getParam($_REQUEST,'calendario_id', null));
$calendario_id  = $Aplic->getEstado('calendario_id', null);

if (isset($_REQUEST['monitoramento_id'])) $Aplic->setEstado('monitoramento_id', getParam($_REQUEST,'monitoramento_id', null));
$monitoramento_id  = $Aplic->getEstado('monitoramento_id', null);

if (isset($_REQUEST['ata_id'])) $Aplic->setEstado('ata_id', getParam($_REQUEST,'ata_id', null));
$ata_id  = $Aplic->getEstado('ata_id', null);

if (isset($_REQUEST['mswot_id'])) $Aplic->setEstado('mswot_id', getParam($_REQUEST,'mswot_id', null));
$mswot_id  = $Aplic->getEstado('mswot_id', null);

if (isset($_REQUEST['swot_id'])) $Aplic->setEstado('swot_id', getParam($_REQUEST,'swot_id', null));
$swot_id  = $Aplic->getEstado('swot_id', null);

if (isset($_REQUEST['operativo_id'])) $Aplic->setEstado('operativo_id', getParam($_REQUEST,'operativo_id', null));
$operativo_id = $Aplic->getEstado('operativo_id', null);

if (isset($_REQUEST['instrumento_id'])) $Aplic->setEstado('instrumento_id', getParam($_REQUEST,'instrumento_id', null));
$instrumento_id = $Aplic->getEstado('instrumento_id', null);

if (isset($_REQUEST['recurso_id'])) $Aplic->setEstado('recurso_id', getParam($_REQUEST,'recurso_id', null));
$recurso_id = $Aplic->getEstado('recurso_id', null);

if (isset($_REQUEST['problema_id'])) $Aplic->setEstado('problema_id', getParam($_REQUEST,'problema_id', null));
$problema_id = $Aplic->getEstado('problema_id', null);

if (isset($_REQUEST['demanda_id'])) $Aplic->setEstado('demanda_id', getParam($_REQUEST,'demanda_id', null));
$demanda_id = $Aplic->getEstado('demanda_id', null);

if (isset($_REQUEST['programa_id'])) $Aplic->setEstado('programa_id', getParam($_REQUEST,'programa_id', null));
$programa_id = $Aplic->getEstado('programa_id', null);

if (isset($_REQUEST['licao_id'])) $Aplic->setEstado('licao_id', getParam($_REQUEST,'licao_id', null));
$licao_id = $Aplic->getEstado('licao_id', null);

if (isset($_REQUEST['evento_id'])) $Aplic->setEstado('evento_id', getParam($_REQUEST,'evento_id', null));
$evento_id = $Aplic->getEstado('evento_id', null);

if (isset($_REQUEST['link_id'])) $Aplic->setEstado('link_id', getParam($_REQUEST,'link_id', null));
$link_id = $Aplic->getEstado('link_id', null);

if (isset($_REQUEST['avaliacao_id'])) $Aplic->setEstado('avaliacao_id', getParam($_REQUEST,'avaliacao_id', null));
$avaliacao_id = $Aplic->getEstado('avaliacao_id', null);

if (isset($_REQUEST['tgn_id'])) $Aplic->setEstado('tgn_id', getParam($_REQUEST,'tgn_id', null));
$tgn_id = $Aplic->getEstado('tgn_id', null);

if (isset($_REQUEST['brainstorm_id'])) $Aplic->setEstado('brainstorm_id', getParam($_REQUEST,'brainstorm_id', null));
$brainstorm_id = $Aplic->getEstado('brainstorm_id', null);

if (isset($_REQUEST['gut_id'])) $Aplic->setEstado('gut_id', getParam($_REQUEST,'gut_id', null));
$gut_id = $Aplic->getEstado('gut_id', null);

if (isset($_REQUEST['causa_efeito_id'])) $Aplic->setEstado('causa_efeito_id', getParam($_REQUEST,'causa_efeito_id', null));
$causa_efeito_id = $Aplic->getEstado('causa_efeito_id', null);

if (isset($_REQUEST['arquivo_id'])) $Aplic->setEstado('arquivo_id', getParam($_REQUEST,'arquivo_id', null));
$arquivo_id = $Aplic->getEstado('arquivo_id', null);

if (isset($_REQUEST['forum_id'])) $Aplic->setEstado('forum_id', getParam($_REQUEST,'forum_id', null));
$forum_id = $Aplic->getEstado('forum_id', null);

if (isset($_REQUEST['checklist_id'])) $Aplic->setEstado('checklist_id', getParam($_REQUEST,'checklist_id', null));
$checklist_id = $Aplic->getEstado('checklist_id', null);

if (isset($_REQUEST['agenda_id'])) $Aplic->setEstado('agenda_id', getParam($_REQUEST,'agenda_id', null));
$agenda_id = $Aplic->getEstado('agenda_id', null);

if (isset($_REQUEST['agrupamento_id'])) $Aplic->setEstado('agrupamento_id', getParam($_REQUEST,'agrupamento_id', null));
$agrupamento_id = $Aplic->getEstado('agrupamento_id', null);

if (isset($_REQUEST['patrocinador_id'])) $Aplic->setEstado('patrocinador_id', getParam($_REQUEST,'patrocinador_id', null));
$patrocinador_id = $Aplic->getEstado('patrocinador_id', null);

if (isset($_REQUEST['template_id'])) $Aplic->setEstado('template_id', getParam($_REQUEST,'template_id', null));
$template_id = $Aplic->getEstado('template_id', null);

if (isset($_REQUEST['painel_id'])) $Aplic->setEstado('painel_id', getParam($_REQUEST,'painel_id', null));
$painel_id = $Aplic->getEstado('painel_id', null);

if (isset($_REQUEST['painel_odometro_id'])) $Aplic->setEstado('painel_odometro_id', getParam($_REQUEST,'painel_odometro_id', null));
$painel_odometro_id = $Aplic->getEstado('painel_odometro_id', null);

if (isset($_REQUEST['painel_composicao_id'])) $Aplic->setEstado('painel_composicao_id', getParam($_REQUEST,'painel_composicao_id', null));
$painel_composicao_id = $Aplic->getEstado('painel_composicao_id', null);

if (isset($_REQUEST['tr_id'])) $Aplic->setEstado('tr_id', getParam($_REQUEST,'tr_id', null));
$tr_id = $Aplic->getEstado('tr_id', null);

if (isset($_REQUEST['me_id'])) $Aplic->setEstado('me_id', getParam($_REQUEST,'me_id', null));
$me_id = $Aplic->getEstado('me_id', null);

if (isset($_REQUEST['plano_acao_item_id'])) $Aplic->setEstado('plano_acao_item_id', getParam($_REQUEST,'plano_acao_item_id', null));
$plano_acao_item_id = $Aplic->getEstado('plano_acao_item_id', null);

if (isset($_REQUEST['beneficio_id'])) $Aplic->setEstado('beneficio_id', getParam($_REQUEST,'beneficio_id', null));
$beneficio_id = $Aplic->getEstado('beneficio_id', null);

if (isset($_REQUEST['painel_slideshow_id'])) $Aplic->setEstado('painel_slideshow_id', getParam($_REQUEST,'painel_slideshow_id', null));
$painel_slideshow_id = $Aplic->getEstado('painel_slideshow_id', null);

if (isset($_REQUEST['projeto_viabilidade_id'])) $Aplic->setEstado('projeto_viabilidade_id', getParam($_REQUEST,'projeto_viabilidade_id', null));
$projeto_viabilidade_id = $Aplic->getEstado('projeto_viabilidade_id', null);

if (isset($_REQUEST['projeto_abertura_id'])) $Aplic->setEstado('projeto_abertura_id', getParam($_REQUEST,'projeto_abertura_id', null));
$projeto_abertura_id = $Aplic->getEstado('projeto_abertura_id', null);

if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST,'pg_id', null));
$pg_id = $Aplic->getEstado('pg_id', null);

if (isset($_REQUEST['ssti_id'])) $Aplic->setEstado('ssti_id', getParam($_REQUEST,'ssti_id', null));
$ssti_id = $Aplic->getEstado('ssti_id', null);

if (isset($_REQUEST['laudo_id'])) $Aplic->setEstado('laudo_id', getParam($_REQUEST,'laudo_id', null));
$laudo_id = $Aplic->getEstado('laudo_id', null);

if (isset($_REQUEST['trelo_id'])) $Aplic->setEstado('trelo_id', getParam($_REQUEST,'trelo_id', null));
$trelo_id = $Aplic->getEstado('trelo_id', null);

if (isset($_REQUEST['trelo_cartao_id'])) $Aplic->setEstado('trelo_cartao_id', getParam($_REQUEST,'trelo_cartao_id', null));
$trelo_cartao_id = $Aplic->getEstado('trelo_cartao_id', null);

if (isset($_REQUEST['pdcl_id'])) $Aplic->setEstado('pdcl_id', getParam($_REQUEST,'pdcl_id', null));
$pdcl_id = $Aplic->getEstado('pdcl_id', null);

if (isset($_REQUEST['pdcl_item_id'])) $Aplic->setEstado('pdcl_item_id', getParam($_REQUEST,'pdcl_item_id', null));
$pdcl_item_id = $Aplic->getEstado('pdcl_item_id', null);




echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="filtro_prioridade_meta" id="filtro_prioridade_meta" value="'.$filtro_prioridade_meta.'" />';
echo '<input type="hidden" name="houve_filtro" id="houve_filtro" value="1" />';

echo '<input type="hidden" name="selecao" id="selecao" value="'.$selecao.'" />';
echo '<input type="hidden" name="chamarVolta" id="chamarVolta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="selecionado" id="selecionado" value="'.$selecionado.'" />';
echo '<input type="hidden" name="edicao" id="edicao" value="'.$edicao.'" />';

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


if (!$dialogo || $selecao){
	
	
	$tipos=array(''=>'');

	if ($Aplic->checarModulo('projetos', 'acesso', null, 'projetos_lista')) $tipos['popProjeto']=ucfirst($config['projeto']);
	if ($Aplic->checarModulo('tarefas', 'acesso', null, null)) $tipos['popTarefa']=ucfirst($config['tarefa']);
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'perspectiva')) $tipos['popPerspectiva']=ucfirst($config['perspectiva']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'tema')) $tipos['popTema']=ucfirst($config['tema']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'objetivo')) $tipos['popObjetivo']=ucfirst($config['objetivo']); 
	if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) $tipos['popFator']=ucfirst($config['fator']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'iniciativa')) $tipos['popEstrategia']=ucfirst($config['iniciativa']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'meta')) $tipos['popMeta']=ucfirst($config['meta']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $tipos['popAcao']=ucfirst($config['acao']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) $tipos['popPratica']=ucfirst($config['pratica']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $tipos['popIndicador']='Indicador'; 
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso', null, null)) $tipos['popAta']='Ata de reunião';	
	if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$tipos['popMSWOT']='Matriz SWOT';
		$tipos['popSWOT']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'acesso', null, null)) $tipos['popOperativo']='Plano operativo';
	if ($Aplic->checarModulo('agenda', 'acesso', null, null)) $tipos['popCalendario']='Agenda';
	if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'acesso', null, null)) $tipos['popInstrumento']=ucfirst($config['instrumento']);
	if ($Aplic->checarModulo('recursos', 'acesso', null, null)) $tipos['popRecurso']=ucfirst($config['recurso']);
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) $tipos['popDemanda']='Demanda';
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'licao')) $tipos['popLicao']=ucfirst($config['licao']);
	if ($Aplic->checarModulo('eventos', 'acesso', null, null)) $tipos['popEvento']='Evento';
	if ($Aplic->checarModulo('links', 'acesso', null, null)) $tipos['popLink']='Link';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'avaliacao_indicador')) $tipos['popAvaliacao']='Avaliação';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'brainstorm')) $tipos['popBrainstorm']='Brainstorm';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'gut')) $tipos['popGut']='Matriz GUT';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'causa_efeito')) $tipos['popCausa_efeito']='Diagrama de causa-efeito';
	if ($Aplic->checarModulo('arquivos', 'acesso', null,  null)) $tipos['popArquivo']='Arquivo';
	if ($Aplic->checarModulo('foruns', 'acesso', null, null)) $tipos['popForum']='Fórum';	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'checklist')) $tipos['popChecklist']='Checklist';
	if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'acesso', null, null)) $tipos['popPatrocinador']=ucfirst($config['patrocinador']);
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao_item')) $tipos['popAcaoItem']='Item de '.$config['acao'];
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'viabilidade')) $tipos['popViabilidade']='Estudo de viabilidade';
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'abertura')) $tipos['popAbertura']='Termo de abertura';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento')) $tipos['popPlanejamento']='Planejamento estratégico';					
	if ($Aplic->profissional)  {
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'canvas')) $tipos['popCanvas']=ucfirst($config['canvas']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $tipos['popRisco']=ucfirst($config['risco']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'resposta_risco')) $tipos['popRiscoResposta']=ucfirst($config['risco_resposta']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'monitoramento')) $tipos['popMonitoramento']='Monitoramento';
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso', null, null)) $tipos['popProblema']=ucfirst($config['problema']);
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'programa')) $tipos['popPrograma']=ucfirst($config['programa']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'tgn')) $tipos['popTgn']=ucfirst($config['tgn']);
		$tipos['popAgenda']='Compromisso';
		if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'acesso', null, null)) $tipos['popAgrupamento']='Agrupamento';
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'modelo')) $tipos['popTemplate']='Modelo';
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'painel_indicador')) $tipos['popPainel']='Painel de indicador';
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'odometro_indicador')) $tipos['popOdometro']='Odômetro de indicador';
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'composicao_painel')) $tipos['popComposicaoPaineis']='Composição de painéis';
		if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso', null, null)) $tipos['popTR']=ucfirst($config['tr']);
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) $tipos['popMe']=ucfirst($config['me']);	
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'beneficio')) $tipos['popBeneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'slideshow_painel')) $tipos['popSlideshow']='Slideshow de painéis';
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso', null, 'ssti')) $tipos['popSSTI']=ucfirst($config['ssti']);
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso', null, 'laudo')) $tipos['popLaudo']=ucfirst($config['laudo']);
		if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'acesso', null, null)) {
			$tipos['popTrelo']=ucfirst($config['trelo']);
			$tipos['popTreloCartao']=ucfirst($config['trelo_cartao']);
			}
		if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'acesso', null, null)) {
			$tipos['popPDCL']=ucfirst($config['pdcl']);
			$tipos['popPDCLItem']=ucfirst($config['pdcl_item']);
			}
		}
	asort($tipos);

	
	if($plano_acao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Filtrar pel'.$config['genero_acao'].' '.$config['acao'].' que se relacionam.').ucfirst($config['acao']).':'.dicaF();
		$nome=nome_acao($plano_acao_id);
		}
	elseif($pratica_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Filtrar pel'.$config['genero_pratica'].' '.$config['pratica'].' que se relacionam.').ucfirst($config['pratica']).':'.dicaF();
		$nome=nome_pratica($pratica_id);
		}
	elseif($calendario_id){
		$legenda_filtro=dica('Filtrar pela Agenda', 'Filtrar pela agenda que se relacionam.').'Agenda:'.dicaF();
		$nome=nome_calendario($calendario_id);
		}
	elseif($projeto_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtrar pel'.$config['genero_projeto'].' '.$config['projeto'].' que se relacionam.').ucfirst($config['projeto']).':'.dicaF();
		$nome=nome_projeto($projeto_id);
		}
	elseif($tarefa_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Filtrar pel'.$config['genero_tarefa'].' '.$config['tarefa'].' que se relacionam.').ucfirst($config['tarefa']).':'.dicaF();
		$nome=nome_tarefa($tarefa_id);
		}	
	elseif($pratica_indicador_id){
		$legenda_filtro=dica('Filtrar pelo Indicador', 'Filtrar pelo indicador que se relacionam.').'Indicador:'.dicaF();
		$nome=nome_indicador($pratica_indicador_id);
		}
	elseif($objetivo_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Filtrar pel'.$config['genero_objetivo'].' '.$config['objetivo'].' que se relacionam.').''.ucfirst($config['objetivo']).':'.dicaF();
		$nome=nome_objetivo($objetivo_id);
		}
	elseif($tema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Filtrar pel'.$config['genero_tema'].' '.$config['tema'].' que se relacionam.').ucfirst($config['tema']).':'.dicaF();
		$nome=nome_tema($tema_id);
		}
	elseif($pg_estrategia_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Filtrar pel'.$config['genero_iniciativa'].' '.$config['iniciativa'].' que se relacionam.').ucfirst($config['iniciativa']).':'.dicaF();
		$nome=nome_estrategia($pg_estrategia_id);
		}
	elseif($pg_perspectiva_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Filtrar pel'.$config['genero_perspectiva'].' '.$config['perspectiva'].' que se relacionam.').ucfirst($config['perspectiva']).':'.dicaF();
		$nome=nome_perspectiva($pg_perspectiva_id);
		}
	elseif($canvas_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Filtrar pel'.$config['genero_canvas'].' '.$config['canvas'].' que se relacionam.').ucfirst($config['canvas']).':'.dicaF();
		$nome=nome_canvas($canvas_id);
		}
	elseif($fator_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Filtrar pel'.$config['genero_fator'].' '.$config['fator'].' que se relacionam.').ucfirst($config['fator']).':'.dicaF();
		$nome=nome_fator($fator_id);
		}
	elseif($pg_meta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Filtrar pel'.$config['genero_meta'].' '.$config['meta'].' que se relacionam.').ucfirst($config['meta']).':'.dicaF();
		$nome=nome_meta($pg_meta_id);
		}	
	elseif($risco_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Filtrar pel'.$config['genero_risco'].' '.$config['risco'].' que se relacionam.').ucfirst($config['risco']).':'.dicaF();
		$nome=nome_risco($risco_id);
		}
	elseif($risco_resposta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Filtrar pel'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' que se relacionam.').ucfirst($config['risco_resposta']).':'.dicaF();
		$nome=nome_risco_resposta($risco_resposta_id);
		}	
	elseif($monitoramento_id){
		$legenda_filtro=dica('Filtrar pelo Monitoramento', 'Filtrar pelo monitoramento que se relacionam.').'Monitoramento:'.dicaF();
		$nome=nome_monitoramento($monitoramento_id);
		}		
	elseif($ata_id){
		$legenda_filtro=dica('Filtrar pela Ata de Reunião', 'Filtrar pela ata de reunião a qual estão relacionados.').'Ata:'.dicaF();
		$nome=nome_ata($ata_id);
		}		
	elseif($mswot_id){
		$legenda_filtro=dica('Filtrar pela Matriz SWOT', 'Filtrar pela matriz SWOT que se relacionam.').'Matriz SWOT:'.dicaF();
		$nome=nome_mswot($mswot_id);
		}	
	elseif($swot_id){
		$legenda_filtro=dica('Filtrar pelo Campo de Matriz SWOT', 'Filtrar pelo campo de matriz SWOT que se relacionam.').'Campo SWOT:'.dicaF();
		$nome=nome_swot($swot_id);
		}		
	elseif($operativo_id){
		$legenda_filtro=dica('Filtrar pelo Plano Operativo', 'Filtrar pelo plano operativo que se relacionam.').'Plano Operativo:'.dicaF();
		$nome=nome_operativo($operativo_id);
		}			
	elseif($instrumento_id){
		$legenda_filtro=dica('Filtrar pelo Instrumento Jurídico', 'Filtrar pelo instrumento jurídico que se relacionam.').'Instrumento Jurídico:'.dicaF();
		$nome=nome_instrumento($instrumento_id);
		}	
	elseif($recurso_id){
		$legenda_filtro=dica('Filtrar pelo Recurso', 'Filtrar pelo recurso que se relacionam.').'Recurso:'.dicaF();
		$nome=nome_recurso($recurso_id);
		}	
	elseif($problema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Filtrar pel'.$config['genero_problema'].' '.$config['problema'].' que se relacionam.').ucfirst($config['problema']).':'.dicaF();
		$nome=nome_problema($problema_id);
		}	
	elseif($demanda_id){
		$legenda_filtro=dica('Filtrar pela Demanda', 'Filtrar pela demanda que se relacionam.').'Demanda:'.dicaF();
		$nome=nome_demanda($demanda_id);
		}		
	elseif($programa_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Filtrar pel'.$config['genero_programa'].' '.$config['programa'].' que se relacionam.').ucfirst($config['programa']).':'.dicaF();
		$nome=nome_programa($programa_id);
		}	
	elseif($licao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Filtrar pel'.$config['genero_licao'].' '.$config['licao'].' que se relacionam.').ucfirst($config['licao']).':'.dicaF();
		$nome=nome_licao($licao_id);
		}	
	elseif($evento_id){
		$legenda_filtro=dica('Filtrar pelo Evento', 'Filtrar pelo evento que se relacionam.').'Evento:'.dicaF();
		$nome=nome_evento($evento_id);
		}		
	elseif($link_id){
		$legenda_filtro=dica('Filtrar pelo Link', 'Filtrar pelo link que se relacionam.').'Link:'.dicaF();
		$nome=nome_link($link_id);
		}
	elseif($avaliacao_id){
		$legenda_filtro=dica('Filtrar pela Avaliação', 'Filtrar pela avaliação que se relacionam.').'Avaliação:'.dicaF();
		$nome=nome_avaliacao($avaliacao_id);
		}
	elseif($tgn_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Filtrar pel'.$config['genero_tgn'].' '.$config['tgn'].' que se relacionam.').ucfirst($config['tgn']).':'.dicaF();
		$nome=nome_tgn($tgn_id);
		}	
	elseif($brainstorm_id){
		$legenda_filtro=dica('Filtrar pelo Brainstorm', 'Filtrar pelo brainstorm que se relacionam.').'Brainstorm:'.dicaF();
		$nome=nome_brainstorm($brainstorm_id);
		}	
	elseif($gut_id){
		$legenda_filtro=dica('Filtrar pela Matriz GUT', 'Filtrar pela matriz GUT que se relacionam.').'Matriz GUT:'.dicaF();
		$nome=nome_gut($gut_id);
		}		
	elseif($causa_efeito_id){
		$legenda_filtro=dica('Filtrar pelo Diagrama de Causa-Efeito', 'Filtrar pelo diagrama de causa-efeito que se relacionam.').'Diagrama de Causa-Efeito:'.dicaF();
		$nome=nome_causa_efeito($causa_efeito_id);
		}		
	elseif($arquivo_id){
		$legenda_filtro=dica('Filtrar pelo Arquivo', 'Filtrar pelo arquivo que se relacionam.').'Arquivo:'.dicaF();
		$nome=nome_arquivo($arquivo_id);
		}	
	elseif($forum_id){
		$legenda_filtro=dica('Filtrar pelo Fórum', 'Filtrar pelo fórum que se relacionam.').'Fórum:'.dicaF();
		$nome=nome_forum($forum_id);
		}	
	elseif($checklist_id){
		$legenda_filtro=dica('Filtrar pelo Checklist', 'Filtrar pelo checklist que se relacionam.').'Checklist:'.dicaF();
		$nome=nome_checklist($checklist_id);
		}	
	elseif($agenda_id){
		$legenda_filtro=dica('Filtrar pelo Compromisso', 'Filtrar pelo compromisso que se relacionam.').'Compromisso:'.dicaF();
		$nome=nome_compromisso($agenda_id);
		}	
	elseif($agrupamento_id){
		$legenda_filtro=dica('Filtrar pelo Agrupamento', 'Filtrar pelo agrupamento que se relacionam.').'Agrupamento:'.dicaF();
		$nome=nome_agrupamento($agrupamento_id);
		}
	elseif($patrocinador_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Filtrar pel'.$config['genero_patrocinador'].' '.$config['patrocinador'].' que se relacionam.').ucfirst($config['patrocinador']).':'.dicaF();
		$nome=nome_patrocinador($patrocinador_id);
		}
	elseif($template_id){
		$legenda_filtro=dica('Filtrar pelo Modelo', 'Filtrar pelo modelo que se relacionam.').'Modelo:'.dicaF();
		$nome=nome_template($template_id);
		}	
	elseif($painel_id){
		$legenda_filtro=dica('Filtrar pelo Painel', 'Filtrar pelo painel de indicador relacionado.').'Painel:'.dicaF();
		$nome=nome_painel($painel_id);
		}		
	elseif($painel_odometro_id){
		$legenda_filtro=dica('Filtrar pelo Odômetro', 'Filtrar pelo odômetro de indicador relacionado.').'Odômetro:'.dicaF();
		$nome=nome_painel_odometro($painel_odometro_id);
		}		
	elseif($painel_composicao_id){
		$legenda_filtro=dica('Filtrar pela Composição de Painéis', 'Filtrar pela composição de painéis relacionada.').'Composição de Painéis:'.dicaF();
		$nome=nome_painel_composicao($painel_composicao_id);
		}	
	elseif($tr_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Filtrar pel'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).':'.dicaF();
		$nome=nome_tr($tr_id);
		}	
	elseif($me_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_me'].' '.ucfirst($config['me']), 'Filtrar pel'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).':'.dicaF();
		$nome=nome_me($me_id);
		}	
	elseif($plano_acao_item_id){
		$legenda_filtro=dica('Filtrar pelo Item d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Filtrar pelo item d'.$config['genero_acao'].' '.$config['acao'].' relacionado.').'Item d'.$config['genero_acao'].' '.ucfirst($config['acao']).':'.dicaF();
		$nome=nome_acao_item($plano_acao_item_id);
		}	
	elseif($beneficio_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_beneficio'].' '.ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Filtrar pel'.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_beneficio'].'.').ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.ucfirst($config['programa']).':'.dicaF();
		$nome=nome_beneficio($beneficio_id);
		}	
	elseif($painel_slideshow_id){
		$legenda_filtro=dica('Filtrar pelo Slideshow de Composições', 'Filtrar pelo slideshow de composições relacionado.').'Slideshow de Composições:'.dicaF();
		$nome=nome_painel_slideshow($painel_slideshow_id);
		}	
	elseif($projeto_viabilidade_id){
		$legenda_filtro=dica('Filtrar pelo Estudo de Viabilidade', 'Filtrar pelo estudo de viabilidade relacionado.').'Estudo de Viabilidade:'.dicaF();
		$nome=nome_viabilidade($projeto_viabilidade_id);
		}	
	elseif($projeto_abertura_id){
		$legenda_filtro=dica('Filtrar pelo Termo de Abertura', 'Filtrar pelo termo de abertura relacionado.').'Termo de Abertura:'.dicaF();
		$nome=nome_termo_abertura($projeto_abertura_id);
		}	
	elseif($pg_id){
		$legenda_filtro=dica('Filtrar pelo Planejamento Estratégico', 'Filtrar pelo planejamento estratégico relacionado.').'Planejamento Estratégico:'.dicaF();
		$nome=nome_plano_gestao($pg_id);
		}		
	elseif($ssti_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Filtrar pel'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).':'.dicaF();
		$nome=nome_ssti($ssti_id);
		}		
	elseif($laudo_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Filtrar pel'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).':'.dicaF();
		$nome=nome_laudo($laudo_id);
		}	
	elseif($trelo_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Filtrar pel'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).':'.dicaF();
		$nome=nome_trelo($trelo_id);
		}	
	elseif($trelo_cartao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Filtrar pel'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF();
		$nome=nome_trelo_cartao($trelo_cartao_id);
		}		
	elseif($pdcl_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Filtrar pel'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF();
		$nome=nome_pdcl($pdcl_id);
		}		
	elseif($pdcl_item_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Filtrar pel'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF();
		$nome=nome_pdcl_item($pdcl_item_id);
		}							
	else{
		$nome='';
		$legenda_filtro=dica('Filtrar', 'Selecione um campo para filtrar.').'Filtro:'.dicaF();
		}
		
	$botoesTitulo = new CBlocoTitulo('Lista de '.ucfirst($config['metas']), 'meta.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/meta_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';


	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	
	$vetor_envolvimento=array(null=>'Incluir '.$config['organizacoes'].' envolvid'.$config['genero_organizacao'].'s', 1=>'Somente '.$config['genero_organizacao'].' '.$config['organizacao'].' responsável');
	$procurar_envolvido=($Aplic->profissional ? '<tr><td align="right">'.dica('Envolvimento', 'Escolha na caixa de opção à direita se '.$config['genero_organizacao'].' '.$config['organizacao'].' envolvid'.$config['genero_organizacao'].'s serão considerad'.$config['genero_organizacao'].'s.').'Envolvimento:'.dicaF().'</td><td>'.selecionaVetor($vetor_envolvimento, 'envolvimento', 'class="texto" style="width:250px;" size="1"', $envolvimento).'</td></tr>' : '');
	
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="metatextobusca" onChange="document.env.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=meta_lista&tab='.$tab.'&metatextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	$selecionar_planejamento='<tr><td align="right" style="white-space: nowrap">'.dica('Planejamentos Estratégico', 'Utilize esta opção para filtrar pelo planejamentos estratégico que está vinculad'.$config['genero_fator'].'.').'Planejamentos Estratégico:</td><td>'.dicaF().selecionaVetor($planos, 'filtro_pg_id', 'onchange="env.submit();" style="width:250px;" class="texto"', $filtro_pg_id).'</td></tr>';
	$procurar_usuario='<tr><td align=right style="white-space: nowrap">'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

	$botao_favorito='';
	$botao_trava='';
	$nova_meta=($Aplic->checarModulo('praticas', 'adicionar', null, 'meta') ? '<tr><td style="white-space: nowrap"><a href="javascript: void(0)" onclick="javascript:env.a.value=\'meta_editar\'; env.submit();" >'.imagem('icones/meta_novo.png', 'Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Clique neste ícone '.imagem('icones/meta_novo.png').' para criar uma nov'.$config['genero_meta'].' '.$config['meta'].'.').'</a></td></tr>' : '');
	$imprimir='<tr><td align="right" style="white-space: nowrap">'.dica('Imprimir '.ucfirst($config['metas']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de '.$config['metas'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=meta_lista&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';

	if($filtro_prioridade_meta) $botao_prioridade=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="priorizacao(0);">'.imagem('icones/priorizacao_nao_p.png', 'Cancelar a Priorização' , 'Clique neste ícone '.imagem('icones/priorizacao_nao_p.png').' para cancelar a priorização.').'</a>'.dicaF().'</td></tr>' : '');
	else $botao_prioridade=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="priorizacao(1);">'.imagem('icones/priorizacao_p.png', 'Priorização' , 'Clique neste ícone '.imagem('icones/priorizacao_p.png').' para priorizar.').'</a>'.dicaF().'</td></tr>' : '');

	if ($Aplic->profissional){
		$botao_campos='<tr><td><a href="javascript: void(0)" onclick="popCamposExibir();">'.imagem('icones/campos_p.gif', 'Campos' , 'Clique neste ícone '.imagem('campos_p.gif').' para escolha quais campos deseja exibir.').'</a>'.dicaF().'</td></tr>';

		$popFiltro='<tr><td align="right" style="white-space: nowrap">'.dica('Relacionad'.$config['genero_meta'], 'A que área '.$config['genero_meta'].' '.$config['meta'].' está relacionad'.$config['genero_meta'].'.').'Relacionad'.$config['genero_meta'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:250px;" class="texto" onchange="popRelacao(this.value)"').'</td></tr>';
		$icone_limpar='<td><a href="javascript:void(0);" onclick="limpar_tudo(); env.submit();">'.imagem('icones/limpar_p.gif','Cancelar Filtro', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para cancelar o filtro aplicado.').'</a></td>';
		$filtros=($nome ? '<tr><td align="right" style="white-space: nowrap">'.$legenda_filtro.'</td><td><input type="text" id="nome" name="nome" value="'.$nome.'" style="width:250px;" class="texto" READONLY /></td>'.$icone_limpar.'</tr>' : '');
		
		$botao_favorito='<tr><td style="white-space: nowrap">'.dica('Favoritos', 'Criar ou editar um grupo de planos de ação favoritos, para uma rápida filtragem.').'<a href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&meta=1\');"><img src="'.acharImagem('favorito_p.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>';
		$botao_trava='<tr id="combo_travado" '.($favorito_trava_campo ? '' : 'style="display:none"').'><td style="white-space: nowrap"><a href="javascript: void(0)" onclick="travar(0);">'.imagem('cadeado_fechado.png', 'Destravar Filtros', 'Clique neste ícone '.imagem('cadeado_fechado.png').' para destravar os filtros.').'</a>'.dicaF().'</td></tr><tr id="combo_destravado" '.(!$favorito_trava_campo ? '' : 'style="display: none"').'><td style="white-space: nowrap"><a href="javascript: void(0)" onclick="travar(1);">'.imagem('cadeado_aberto.png', 'Travar Filtros', 'Clique neste ícone '.imagem('cadeado_aberto.png').' para travar os filtros.').'</a>'.dicaF().'</td></tr>';
		}
	else {
		$botao_campos='';
		$popFiltro='';
		$filtros='';
		}	
	$saida.='<tr><td valign=top><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_envolvido.$procurar_usuario.$selecionar_planejamento.$procuraBuffer.$favoritos.$popFiltro.$filtros.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.(!$selecao ? $nova_meta.$imprimir.$botao_prioridade.$botao_favorito.$botao_trava.$botao_campos : '').'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif($Aplic->profissional){
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $cia_id;
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
	echo 	'<font size="4"><center>Lista de '.ucfirst($config['metas']).'</center></font>';
	}

	


if($Aplic->profissional) $Aplic->carregarComboMultiSelecaoJS();
$caixaTab = new CTabBox('m='.$m.'&a='.$a, '', $tab);
if (!$dialogo || $selecao){
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', 'Ativ'.$config['genero_meta'].'s',null,null,'Ativ'.$config['genero_meta'].'s','Visualizar '.$config['genero_meta'].'s '.$config['metas'].' ativ'.$config['genero_meta'].'s.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', 'Inativ'.$config['genero_meta'].'s',null,null,'Inativ'.$config['genero_meta'].'s','Visualizar '.$config['genero_meta'].'s '.$config['metas'].' inativ'.$config['genero_meta'].'s.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', 'Tod'.$config['genero_meta'].'s',null,null,'Tod'.$config['genero_meta'].'s','Visualizar tod'.$config['genero_meta'].'s '.$config['genero_meta'].'s '.$config['metas'].'.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else {
	include_once(BASE_DIR.'/modulos/praticas/metas_ver_idx.php');
	if (!($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
	}
echo '</form>';

?>
<script type="text/javascript">
	
function popCamposExibir(){
	parent.gpwebApp.popUp('Campos', 500, 500, 'm=publico&a=campos&dialogo=1&campo_formulario_tipo=metas', window.setCamposExibir, window);
	}
	
function setCamposExibir(){
	url_passar(0, 'm=praticas&a=meta_lista');
	}		
	
	
function travar(tipo){
	xajax_travar(tipo);
	if (tipo){
		document.getElementById('combo_travado').style.display="";
		document.getElementById('combo_destravado').style.display="none";
		}
	else {
		document.getElementById('combo_travado').style.display="none";
		document.getElementById('combo_destravado').style.display="";
		}
	}	

function priorizacao() {
	parent.gpwebApp.popUp("Priorização", 400, 300, 'm=publico&a=filtro_priorizacao_pro&dialogo=1&meta=1&filtro_prioridade='+env.filtro_prioridade_meta.value, window.setFiltroPriorizacao, window);
	}

function setFiltroPriorizacao(filtro_prioridade_meta){
	env.filtro_prioridade_meta.value=filtro_prioridade_meta;
	env.submit();
	}	

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	env.submit();
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	env.submit();
	}
	
	

	
function popRelacao(relacao){
	if(relacao) eval(relacao+'()'); 
	env.tipo_relacao.value='';
	}
	
function limpar_tudo(){
	document.env.projeto_id.value = null;
	document.env.tarefa_id.value = null;
	document.env.pg_perspectiva_id.value = null;
	document.env.tema_id.value = null;
	document.env.objetivo_id.value = null;
	document.env.fator_id.value = null;
	document.env.pg_estrategia_id.value = null;
	document.env.pg_meta_id.value = null;
	document.env.pratica_id.value = null;
	document.env.plano_acao_id.value = null;
	document.env.canvas_id.value = null;
	document.env.risco_id.value = null;
	document.env.risco_resposta_id.value = null;
	document.env.pratica_indicador_id.value = null;
	document.env.calendario_id.value = null;
	document.env.monitoramento_id.value = null;
	document.env.ata_id.value = null;
	document.env.mswot_id.value = null;
	document.env.swot_id.value = null;
	document.env.operativo_id.value = null;
	document.env.instrumento_id.value = null;
	document.env.recurso_id.value = null;
	document.env.problema_id.value = null;
	document.env.demanda_id.value = null;
	document.env.programa_id.value = null;
	document.env.licao_id.value = null;
	document.env.evento_id.value = null;
	document.env.link_id.value = null;
	document.env.avaliacao_id.value = null;
	document.env.tgn_id.value = null;
	document.env.brainstorm_id.value = null;
	document.env.gut_id.value = null;
	document.env.causa_efeito_id.value = null;
	document.env.arquivo_id.value = null;
	document.env.forum_id.value = null;
	document.env.checklist_id.value = null;
	document.env.agenda_id.value = null;
	document.env.agrupamento_id.value = null;
	document.env.patrocinador_id.value = null;
	document.env.template_id.value = null;
	document.env.painel_id.value = null;
	document.env.painel_odometro_id.value = null;
	document.env.painel_composicao_id.value = null;
	document.env.tr_id.value = null;
	document.env.me_id.value = null;
	document.env.plano_acao_item_id.value = null;
	document.env.beneficio_id.value = null;
	document.env.painel_slideshow_id.value = null;
	document.env.projeto_viabilidade_id.value = null;
	document.env.projeto_abertura_id.value = null;
	document.env.pg_id.value = null;	
	document.env.ssti_id.value = null;
	document.env.laudo_id.value = null;
	document.env.trelo_id.value = null;
	document.env.trelo_cartao_id.value = null;
	document.env.pdcl_id.value = null;
	document.env.pdcl_item_id.value = null;	
	}


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&selecao=2&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.projeto_id.value = chave;
	env.submit();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.tarefa_id.value = chave;
	env.submit();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&selecao=2&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pg_perspectiva_id.value = chave;
	env.submit();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&selecao=2&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.tema_id.value = chave;
	env.submit();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&selecao=2&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.objetivo_id.value = chave;
	env.submit();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&selecao=2&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.fator_id.value = chave;
	env.submit();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&selecao=2&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pg_estrategia_id.value = chave;
	env.submit();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&selecao=2&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pg_meta_id.value = chave;
	env.submit();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&selecao=2&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_id.value = chave;
	env.submit();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&selecao=2&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_id.value = chave;
	env.submit();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&selecao=2&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.plano_acao_id.value = chave;
	env.submit();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&selecao=2&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.canvas_id.value = chave;
	env.submit();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&selecao=2&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.risco_id.value = chave;
	env.submit();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&selecao=2&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.risco_resposta_id.value = chave;
	env.submit();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&selecao=2&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.calendario_id.value = chave;
	env.submit();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&selecao=2&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.monitoramento_id.value = chave;
	env.submit();
	}	

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&selecao=2&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.ata_id.value = chave;
	env.submit();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&selecao=2&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('cia_id').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.mswot_id.value = chave;
	env.submit();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Campo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&selecao=2&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.swot_id.value = chave;
	env.submit();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&selecao=2&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.operativo_id.value = chave;
	env.submit();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&selecao=2&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jurídico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.instrumento_id.value = chave;
	env.submit();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=2&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.recurso_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&selecao=2&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.problema_id.value = chave;
	env.submit();
	}
<?php } ?>


<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&selecao=2&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.programa_id.value = chave;
	env.submit();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&selecao=2&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.licao_id.value = chave;
	env.submit();
	}

function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&selecao=2&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.evento_id.value = chave;
	env.submit();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&selecao=2&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.link_id.value = chave;
	env.submit();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&selecao=2&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avaliação','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_id.value = chave;
	env.submit();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=tgn_pro_lista&dialogo=1&selecao=2&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.tgn_id.value = chave;
	env.submit();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&selecao=2&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.brainstorm_id.value = chave;
	env.submit();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&selecao=2&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.gut_id.value = chave;
	env.submit();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&selecao=2&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.causa_efeito_id.value = chave;
	env.submit();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&selecao=2&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.arquivo_id.value = chave;
	env.submit();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&selecao=2&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'Fórum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.forum_id.value = chave;
	env.submit();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&selecao=2&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.checklist_id.value = chave;
	env.submit();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&selecao=2&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.agenda_id.value = chave;
	env.submit();
	}
	
<?php  if ($Aplic->profissional) { ?>
	
	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&selecao=2&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.agrupamento_id.value = chave;
		env.submit();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&selecao=2&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.patrocinador_id.value = chave;
		env.submit();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&selecao=2&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.template_id.value = chave;
		env.submit();
		}	
		
	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&selecao=2&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPainel(chave, valor){
		limpar_tudo();
		document.env.painel_id.value = chave;
		env.submit();
		}		
		
	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&selecao=2&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Odômetro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.painel_odometro_id.value = chave;
		env.submit();
		}			
		
	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&selecao=2&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composição de Painéis','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.painel_composicao_id.value = chave;
		env.submit();
		}	
		
	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&selecao=2&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.tr_id.value = chave;
		env.submit();
		}	
			
	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&selecao=2&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.me_id.value = chave;
		env.submit();
		}		
		
	function popDemanda() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&selecao=2&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setDemanda(chave, valor){
		limpar_tudo();
		document.env.demanda_id.value = chave;
		env.submit();
		}		

	function popAcaoItem() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&selecao=2&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, window.setAcaoItem, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setAcaoItem(chave, valor){
		limpar_tudo();
		document.env.plano_acao_item_id.value = chave;
		env.submit();
		}		
	
	function popBeneficio() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&selecao=2&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, window.setBeneficio, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setBeneficio(chave, valor){
		limpar_tudo();
		document.env.beneficio_id.value = chave;
		env.submit();
		}	

	function popSlideshow() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&selecao=2&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, window.setSlideshow, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, 'Slideshow de Composições','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setSlideshow(chave, valor){
		limpar_tudo();
		document.env.painel_slideshow_id.value = chave;
		env.submit();
		}	

	function popViabilidade() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&selecao=2&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, window.setViabilidade, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setViabilidade(chave, valor){
		limpar_tudo();
		document.env.projeto_viabilidade_id.value = chave;
		env.submit();
		}	
		
	function popAbertura() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&selecao=2&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, window.setAbertura, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setAbertura(chave, valor){
		limpar_tudo();
		document.env.projeto_abertura_id.value = chave;
		env.submit();
		}		
		
	function popPlanejamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&selecao=2&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, window.setPlanejamento, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, 'Planejamento Estratégico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setPlanejamento(chave, valor){
		limpar_tudo();
		document.env.pg_id.value = chave;
		env.submit();
		}		
	
	function popSSTI() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&selecao=2&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('cia_id').value, window.setSSTI, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setSSTI(chave, valor){
		limpar_tudo();
		document.env.ssti_id.value = chave;
		env.submit();
		}	
					
	function popLaudo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&selecao=2&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('cia_id').value, window.setLaudo, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setLaudo(chave, valor){
		limpar_tudo();
		document.env.laudo_id.value = chave;
		env.submit();
		}		
		
	function popTrelo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&selecao=2&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('cia_id').value, window.setTrelo, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTrelo(chave, valor){
		limpar_tudo();
		document.env.trelo_id.value = chave;
		env.submit();
		}	
		
	function popTreloCartao() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&selecao=2&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('cia_id').value, window.setTreloCartao, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTreloCartao(chave, valor){
		limpar_tudo();
		document.env.trelo_cartao_id.value = chave;
		env.submit();
		}	
		
	function popPDCL() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&selecao=2&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('cia_id').value, window.setPDCL, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPDCL(chave, valor){
		limpar_tudo();
		document.env.pdcl_id.value = chave;
		env.submit();
		}				
		
	function popPDCLItem() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&selecao=2&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('cia_id').value, window.setPDCLItem, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPDCLItem(chave, valor){
		limpar_tudo();
		document.env.pdcl_item_id.value = chave;
		env.submit();
		}	
		
<?php } ?>		
</script>