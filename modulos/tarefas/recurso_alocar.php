<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$tarefa_id = intval(getParam($_REQUEST, 'tarefa_id', 0));
$projeto_id=getParam($_REQUEST, 'projeto_id', null);
$Aplic->carregarCalendarioJS();
$Aplic->carregarCKEditorJS();
if (!$tarefa_id) {
	$Aplic->setMsg('ID d'.$config['genero_tarefa'].' '.$config['tarefa'].' não foi passado', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index');
	exit();
	}

$sql = new BDConsulta;

$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();




$data_texto = new CData();

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', intval(getParam($_REQUEST, 'cia_id', 0)));
$cia_id = $Aplic->getEstado('cia_id', $Aplic->usuario_cia);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['recurso_responsavel'])) $Aplic->setEstado('recurso_responsavel', intval(getParam($_REQUEST, 'recurso_responsavel', 0)));
$recurso_responsavel = ($Aplic->getEstado('recurso_responsavel')!== null ? $Aplic->getEstado('recurso_responsavel') : 0);

if (isset($_REQUEST['tipo_recurso'])) $Aplic->setEstado('tipo_recurso', intval(getParam($_REQUEST, 'tipo_recurso', 5)));
$tipo_recurso = ($Aplic->getEstado('tipo_recurso')!== null ? $Aplic->getEstado('tipo_recurso') : 5);

if (isset($_REQUEST['recurso_ano'])) $Aplic->setEstado('recurso_ano', getParam($_REQUEST, 'recurso_ano', ''));
$recurso_ano = ($Aplic->getEstado('recurso_ano')!== null ? $Aplic->getEstado('recurso_ano') : '');

if (isset($_REQUEST['recurso_ugr'])) $Aplic->setEstado('recurso_ugr', getParam($_REQUEST, 'recurso_ugr', ''));
$recurso_ugr = ($Aplic->getEstado('recurso_ugr')!== null ? $Aplic->getEstado('recurso_ugr') : '');

if (isset($_REQUEST['recurso_ptres'])) $Aplic->setEstado('recurso_ptres', getParam($_REQUEST, 'recurso_ptres', ''));
$recurso_ptres = ($Aplic->getEstado('recurso_ptres')!== null ? $Aplic->getEstado('recurso_ptres') : '');

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : 0);
if ($dept_id) $ver_subordinadas = null;

if (isset($_REQUEST['recurso_credito_adicional'])) $Aplic->setEstado('recurso_credito_adicional', getParam($_REQUEST, 'recurso_credito_adicional', null));
$recurso_credito_adicional = ($Aplic->getEstado('recurso_credito_adicional') !== null ? $Aplic->getEstado('recurso_credito_adicional') : '');

if (isset($_REQUEST['recurso_movimentacao_orcamentaria'])) $Aplic->setEstado('recurso_movimentacao_orcamentaria',getParam($_REQUEST, 'recurso_movimentacao_orcamentaria', null));
$recurso_movimentacao_orcamentaria = ($Aplic->getEstado('recurso_movimentacao_orcamentaria') !== null ? $Aplic->getEstado('recurso_movimentacao_orcamentaria') : '');

if (isset($_REQUEST['recurso_identificador_uso'])) $Aplic->setEstado('recurso_identificador_uso', getParam($_REQUEST, 'recurso_identificador_uso', null));
$recurso_identificador_uso = ($Aplic->getEstado('recurso_identificador_uso') !== null ? $Aplic->getEstado('recurso_identificador_uso') : '');

if (isset($_REQUEST['recurso_pesquisa'])) $Aplic->setEstado('recurso_pesquisa', getParam($_REQUEST, 'recurso_pesquisa', null));
$recurso_pesquisa = ($Aplic->getEstado('recurso_pesquisa') !== null ? $Aplic->getEstado('recurso_pesquisa') : '');

if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}
else $lista_cias=$cia_id;

$listaTipo=array(''=>'')+getSisValor('TipoRecurso');


$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ano');
$sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$anos = $sql->listaVetorChave('recurso_ano','recurso_ano');
$sql->limpar();
$anos =array(''=>'')+$anos;


$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ugr');
$sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$lista_ugrs = $sql->listaVetorChave('recurso_ugr','recurso_ugr');
$sql->limpar();
$lista_ugrs =array(''=>'')+$lista_ugrs;

$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ptres');
$sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$listaPtres = $sql->listaVetorChave('recurso_ptres','recurso_ptres');
$sql->limpar();
$listaPtres =array(''=>'')+$listaPtres;


$MovimentacaoOrcamentaria=array(''=>'')+getSisValor('MovimentacaoOrcamentaria');
$CreditoAdicional=array(''=>'')+getSisValor('CreditoAdicional');
$IdentificadorUso=array(''=>'')+getSisValor('IdentificadorUso');

$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
$sql->adCampo('tarefa_inicio, tarefa_fim, tarefa_duracao, projeto_cia, tarefa_nome');
$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
$tarefa=$sql->linha();
$sql->limpar();

$recurso_tipos = getSisValor('TipoRecurso');
$sql->adTabela('recursos');
$sql->adCampo('recurso_id, recurso_nome, recurso_tipo, recurso_nivel_acesso');
if ($tipo_recurso) $sql->adOnde('recurso_tipo='.(int)$tipo_recurso);
$sql->adOnde('recurso_cia='.(int)$tarefa['projeto_cia']);
$sql->adOrdem('recurso_tipo', 'recurso_nome');
$res = $sql->Lista();
$sql->limpar();
$todos_recursos = array();
foreach ($res as $linha) {
	if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $todos_recursos[$linha['recurso_id']] = $linha['recurso_nome'];
	}


$recursos = array();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="salvar" value="0" />';
echo '<input type="hidden" name="dialogo" value="'.$dialogo.'" />';
echo '<input type="hidden" name="recurso_tipo" id="recurso_tipo" value="" />';
echo '<input type="hidden" name="ver_subordinadas" id="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="lista_cias" id="lista_cias" value="'.$lista_cias.'" />';

echo '<input type="hidden" id="dept_id" name="dept_id" value="'.$dept_id.'" />';



echo '<input type="hidden" id="texto_apoio_obs" name="texto_apoio_obs" value="" />';
echo '<input type="hidden" id="texto_apoio_custo_descricao" name="texto_apoio_custo_descricao" value="" />';

echo '<input type="hidden" id="tarefa_id" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" id="qnt_maxima" name="qnt_maxima" value="" />';
echo '<input type="hidden" id="recurso_id" name="recurso_id" value="" />';
echo '<input type="hidden" id="uuid" name="uuid" value="" />';

$lista_tipo=array(''=>'')+$recurso_tipos;

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
$GrupoND=array(''=>'')+getSisValor('GrupoND');
$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');

$botoesTitulo = new CBlocoTitulo('Alocar Recurso', 'recursos.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();



echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="std">';
echo '<tr><td colspan=20 align=center><h1>'.$tarefa['tarefa_nome'].'</h1></td></tr>';
echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr><td><table cellpadding=0 cellspacing=0>';
echo '<tr><td align="right">'.dica('Tipo', 'Selecione qual o tipo de recurso.').'Tipo:'.dicaF().'</td><td align="left">'.selecionaVetor($listaTipo, 'tipo_recurso', 'style="width:350px;" onchange="document.getElementById(\'inserir_trabalho\').style.display=\'none\'; ver_orcamentario(); ver_recursos();" class="texto"', $tipo_recurso).'</td></tr>';
echo '<tr><td align=right>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:350px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinadas','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinadas','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').'<td id="combo_dept2" '.(!$dept_id ? '' : 'style="display:none"').'><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif','Filtrar pel'.$config['genero_dept'].' '.$config['departamento'],'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].'.').'</a></td></tr></table></td></tr>';

echo '<tr id="combo_dept" '.($dept_id ? '' : 'style="display:none"').'><td align=right>'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['dept']).':</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" name="nome_dept" id="nome_dept" value="'.nome_dept($dept_id).'" style="width:350px;"></td><td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif','Filtrar pel'.$config['genero_dept'].' '.$config['departamento'],'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].'.').'</a></td></tr></table></td></tr>';

echo '<tr><td align=right width=120>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="hidden" id="recurso_responsavel" name="recurso_responsavel" value="'.$recurso_responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($recurso_responsavel).'" style="width:350px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
echo '<tr id="identificador" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" style="white-space: nowrap">'.dica('Identificador de Uso', 'O uso deste recurso.').'Identificador:'.dicaF().'</td><td>'.selecionaVetor($IdentificadorUso, 'recurso_identificador_uso', 'class=texto size=1 style="width:350px;"', $recurso_identificador_uso).'</td></tr>';
echo '<tr id="credito_adicional" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" style="white-space: nowrap">'.dica('Crédito Adicional', 'Caso seja monetário, seleciona o crédito adicional deste recurso, se for o caso.').'Crédito adicional:'.dicaF().'</td><td>'.selecionaVetor($CreditoAdicional, 'recurso_credito_adicional', 'style="width:350px;" class="texto"', $recurso_credito_adicional).'</td></tr>';
echo '<tr id="movimentacao" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" style="white-space: nowrap">'.dica('Movimentação Orcamentária', 'Caso seja monetário, seleciona a movimentação orcamentária deste recurso, se for o caso.').'Movimentação:'.dicaF().'</td><td>'.selecionaVetor($MovimentacaoOrcamentaria, 'recurso_movimentacao_orcamentaria', 'style="width:350px;" class="texto"', $recurso_movimentacao_orcamentaria).'</td></tr>';
echo '<tr id="ptres" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" style="white-space: nowrap">'.dica('Plano de Trabalho Resumido', 'Insira o plano de trabalho resumido deste recurso.').'PTRES:'.dicaF().'</td><td>'.selecionaVetor($listaPtres, 'recurso_ptres', 'style="width:350px;" class="texto"', $recurso_ptres).'</td></tr>';
echo '<tr id="combo_ano" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" >'.dica('Ano', 'Insira o ano deste recurso.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'recurso_ano', 'style="width:350px;" class="texto"', $recurso_ano).'</td></tr>';
echo '<tr id="ugrs" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" >'.dica('Unidade Gestora do Recurso', 'A unidade gestora do recurso.').'UGR:'.dicaF().'</td><td>'.selecionaVetor($lista_ugrs, 'recurso_ugr', 'style="width:350px;" class="texto"', $recurso_ugr).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td colspan=2><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" style="width:350px;" id="recurso_pesquisa" name="recurso_pesquisa" value="'.$recurso_pesquisa.'" /></td><td><a href="javascript:void(0);" onclick="env.recurso_pesquisa.value=\'\';">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td></tr>';
echo '</table></td>';
echo '<td valign="middle"><a href="javascript:void(0);" onclick="ver_recursos();">'.imagem('icones/recursos_p.gif','Atualizar Recursos','Clique neste ícone '.imagem('icones/recursos_p.gif').' para atualizar a lista de recursos pelos parâmetros selecionados.').'</a></td>';
echo '</tr></table></td></tr>';




echo '<tr><td align=right width=120>'.dica('Recurso', 'Resurso a ser alocado. Importante salientar que à <i>priori</i> todos os recursos ainda não designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' aparecerão aqui, por isso é importante verificar se o recurso designado já não está envolvido em um número excessivo de  '.$config['tarefas'].'.'). 'Recurso:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_recursos">'.selecionaVetor($todos_recursos, 'mat_recursos', 'style="width:350px;" size="5" class="texto" onchange="selecionar_recurso(this.value);"', null).'</div></td>';
echo '<td align="left"><span id="disponibilidade"><a href="javascript: void(0);" onclick="alocacao()">'.imagem('icones/calendario_p.png', 'Disponibilidade','Visualizara disponibilidade, por dia, do recurso selecionado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' em que já esteja designado.').'</a></span></td></tr></table></td></tr>';


echo '<input type=hidden name="recurso_tarefa_id" id="recurso_tarefa_id" value="">';























echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0 width="100%">';




$data_inicio = new CData($tarefa['tarefa_inicio']);
$data_fim = new CData($tarefa['tarefa_fim']);


$inicio = 0;
$fim = 24;
$inc=1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = '0'.$atual;
	else $chave_atual = $atual;
	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;
$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');
echo '<tr><td colspan=20 align="left" style="white-space: nowrap"><table style="display:none" id="inserir_trabalho" cellpadding=0 cellspacing=0><tr><td><table cellpadding=0 cellspacing=0 style="width:474px;">';
echo '<tr><td align="right" style="white-space: nowrap"><div id="combo_titulo" style="white-space: nowrap"></div></td><td style="white-space: nowrap"><div id="combo_projeto"></div></td></tr>';
echo '<tr id="campo_inicio"><td align="right" style="white-space: nowrap" width=120>'.dica('Data de Início', 'Digite ou escolha no calendário a data provável de início do período trabalhado.').'Início:'.dicaF().'</td><td style="white-space: nowrap"><table cellpadding=0 cellspacing=0><tr><td><input type="hidden" id="data_inicio_real" name="data_inicio_real"  value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_inicio\', \'data_inicio_real\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" /><a href="javascript: void(0);">'.dica('Data de Início', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data provável de início do período trabalhado.').'<img id="f_btn3" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a>'.dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio do período trabalhado.'). selecionaVetor($horas, 'hora_inicio', 'size="1" onchange="data_ajax();" class="texto" ', ($data_inicio ? $data_inicio->getHour() : $inicio)).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do ínicio do período trabalhado.').selecionaVetor($minutos, 'minuto_inicio', 'size="1" class="texto" onchange="data_ajax();" ', ($data_inicio ? $data_inicio->getMinute() : '00')).'</td></table></td></tr>';
echo '<tr id="campo_fim"><td align="right" style="white-space: nowrap">'.dica('Data de Término', 'Digite ou escolha no calendário a data provável de término do período trabalhado.').'Término:'.dicaF().'</td><td style="white-space: nowrap"><table cellpadding=0 cellspacing=0><tr><td><input type="hidden" id="data_fim_real" name="data_fim_real" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_fim\',  \'data_fim_real\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" /><a href="javascript: void(0);">'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de término do período trabalhado.').'<img id="f_btn4" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a>'.dica('Hora do Término', 'Selecione na caixa de seleção a hora do término do período trabalhado.').selecionaVetor($horas, 'hora_fim', 'size="1" onchange="horas_ajax();" class="texto" ', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término do período trabalhado.').selecionaVetor($minutos, 'minuto_fim', 'size="1" class="texto" onchange="horas_ajax();" ', $data_fim ? $data_fim->getMinute() : '00').'</td></tr></table></td></tr>';
echo '<tr id="campo_duracao"><td align="right" style="white-space: nowrap">'.dica('Duração', 'Selecionando o número de horas a serem trabalhadas.').'Duração:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" onchange="data_ajax();" class="texto" name="duracao" onkeypress="return somenteFloat(event)" id="duracao" style="width:70px;" value="'.float_brasileiro($tarefa['tarefa_duracao']).'" />&nbsp;horas</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap" width=120>'.dica('Quantidade', 'Insira a quantidade do recurso a ser utilizado.').'<div id="tipo_qnt">Quantidade:<div>'.dicaF().'</td><td style="white-space: nowrap"><input type="text" class="texto" name="quantidade" id="quantidade" onkeypress="return somenteFloat(event)" style="width:70px;" value="0" /></td></tr>';
echo '<tr id="campo_percentual_alocado" width=120><td align="right" style="white-space: nowrap">'.dica('Percentual de Alocação', 'O porcentual de alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].' pode ir de 0% até 100%.').'Percentual:'.dicaF().'</td><td style="white-space: nowrap">'.selecionaVetor($percentual, 'percentual_alocado', 'size="1" class="texto"', 100).'% </td></tr>';
echo '<tr id="campo_valor_hora" width=120><td align="right" style="white-space: nowrap">'.dica('Valor da Hora', 'Insira o valor da hora de alocação.').'Valor hora:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" class="texto" name="valor_hora" onkeypress="return entradaNumerica(event, this, true, true);" id="valor_hora" style="width:70px;" value="0" /></td></tr>';
echo '<tr id="campo_custo" width=120><td align="right" style="white-space: nowrap">'.dica('Valor Unitário', 'Insira o valor unitário do recurso.').'Valor unitário:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" class="texto" name="custo" onkeypress="return entradaNumerica(event, this, true, true);" id="custo" style="width:70px;" value="0" /></td></tr>';



echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda utilizada neste item.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'recurso_tarefa_moeda', 'class=texto size=1 style="width:395px;"', 1).'</td></tr>';
echo '<tr id="combo_data_moeda"><td align="right">'.dica('Data da Cotação','Data da cotação da moeda.').'Data da cotação:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="recurso_tarefa_data_moeda" id="recurso_tarefa_data_moeda" value="'.($data_texto ? $data_texto->format('%Y%m%d') : '').'" /><input type="text" name="data7_texto"  id="data7_texto" style="width:70px;" onchange="setData(\'env\', \'data7_texto\', \'recurso_tarefa_data_moeda\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data da Cotação', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data da cotação da moeda estrangeira.').'<a href="javascript: void(0);" ><img id="f_btn7" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário7" /></a>'.dicaF().'</td></tr></table></td></tr>';

//bdi novo
if ($config['bdi']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%):'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="recurso_tarefa_bdi" id="recurso_tarefa_bdi" value="" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_bdi" id="recurso_tarefa_bdi" value="0" />';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'recurso_tarefa_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="mudar_nd1();"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'recurso_tarefa_grupo_despesa', 'class=texto size=1 style="width:395px;" onchange="mudar_nd1();"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'recurso_tarefa_modalidade_aplicacao', 'class=texto size=1 style="width:395px;" onchange="mudar_nd1();"').'</td></tr>';
$nd=array();
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd1">'.selecionaVetor($nd, 'recurso_tarefa_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd1();"').'</div></td></tr>';
if (isset($exibir['codigo']) && $exibir['codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['codigo_valor']), 'Insira '.$config['genero_codigo_valor'].' '.$config['codigo_valor'].' deste item.').ucfirst($config['codigo_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="recurso_tarefa_codigo" id="recurso_tarefa_codigo" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_codigo" id="recurso_tarefa_codigo" value="" />';
if (isset($exibir['fonte']) && $exibir['fonte']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fonte_valor']), 'Insira '.$config['genero_fonte_valor'].' '.$config['fonte_valor'].' deste item.').ucfirst($config['fonte_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="recurso_tarefa_fonte" id="recurso_tarefa_fonte" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_fonte" id="recurso_tarefa_fonte" value="" />';
if (isset($exibir['regiao']) && $exibir['regiao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['regiao_valor']), 'Insira '.$config['genero_regiao_valor'].' '.$config['regiao_valor'].' deste item.').ucfirst($config['regiao_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="recurso_tarefa_regiao" id="recurso_tarefa_regiao" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_regiao" id="recurso_tarefa_regiao" value="" />';	










echo '<tr><td align="right" style="white-space: nowrap" width=120>'.dica('Observação', 'Insira uma observação sobre o período a ser trabalhado.').'Observação:'.dicaF().'</td><td style="white-space: nowrap"><textarea name="observacao" id="observacao" data-gpweb-cmp="ckeditor" style="width:395px;" rows="5" class="textarea"></textarea></td></tr>';
echo '<tr id="campo_corrido"><td align="right" style="white-space: nowrap" width=120>'.dica('Tempo Corrido', 'Caso esteja marcado, será ignorado os calendários quando do cálculo das horas trabalhadas, assim como as restrições de início e término previstos d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Tempo corrido:'.dicaF().'</td><td><input type="checkbox" onchange="horas_ajax();" name="tempo_corrido_ponto" id="tempo_corrido_ponto" value="1" '.($config['tempo_corrido_ponto'] ? 'checked' : '').' /></td></tr>';






















echo '</table></td>';

echo '<td><span style="display:" id="adicionar_trabalho"><a href="javascript: void(0);" onclick="incluir_trabalho();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir este período trabalhado.').'</a></span><span style="display:none" id="confirmar_trabalho"><a href="javascript: void(0);" onclick="confirmar_atualizacao_trabalho();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do custo.').'</a><a href="javascript: void(0);" onclick="cancelar_atualizacao_trabalho();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do custo.').'</a></span></td>';
echo '<td><div id="detalhes_recurso"></td>';
echo '</tr></table></tr>';

echo '<tr><td colspan=20 id="combo_alerta"></td></tr>';
if ($tarefa_id) {
	$sql->adTabela('recurso_tarefa');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_tarefa_recurso');
	$sql->adOnde('recurso_tarefa_tarefa = '.(int)$tarefa_id);
	$sql->adCampo('recurso_tarefa_id, recursos.recurso_id, recurso_tipo, recurso_nome, recurso_tarefa_ordem, recurso_tarefa_id, recurso_tarefa_obs, recurso_tarefa_quantidade, recurso_tarefa_percentual, recurso_tarefa_valor_hora, recurso_tarefa_custo, formatar_data(recurso_tarefa_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(recurso_tarefa_fim, \'%d/%m/%Y %H:%i\') AS fim, recurso_tarefa_duracao, recurso_tarefa_aprovou, formatar_data(recurso_tarefa_data, \'%d/%m/%Y %H:%i\') AS data_aprovou');
	$sql->adOrdem('recurso_tarefa_ordem');
	$recurso=$sql->Lista();
	$sql->limpar();
	}
else $recurso=null;
echo '<tr><td colspan=20 align=left><div id="lista_recursos">';
if (count($recurso)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left width=100%><tr>
	<th></th>
	<th>'.dica('Recurso', 'O nome do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Recurso'.dicaF().'</th>
	<th>'.dica('Início', 'A data de início de alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Início'.dicaF().'</th>
	<th>'.dica('Término', 'A data de término de alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Término'.dicaF().'</th>
	<th>'.dica('Horas', 'Total de horas úteis na alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Horas'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Qnt.'.dicaF().'</th>
	<th>'.dica('Percentagem', 'A percentagem de uso do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'%'.dicaF().'</th>
	<th>'.dica('Valor/Hora', 'O valor da hora de alocação do recurso.').'Valor/hora'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor unitário do recurso.').'Valor/unit.'.dicaF().'</th>
	<th>'.dica('Aprovado', 'Se a alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].' se encontra aprovado pelo responsável pelo recurso.').'Aprov.'.dicaF().'</th>
	<th>'.dica('Data da Aprovação', 'A data em que alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].' foi provada pelo responsável pelo recurso.').'DA'.dicaF().'</th>
	<th>'.dica('Observação', 'Observação sobre o recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Obs.'.dicaF().'</th>
	<th></th></tr>';
	foreach ($recurso as $linha) {
		echo '<tr align="center">';
		echo '<td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.(int)$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.(int)$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.(int)$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.(int)$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left">'.($linha['recurso_tipo']< 4 ? '<a href="javascript:void(0);" onclick="ver_gastos('.$linha['recurso_tarefa_id'].')">' : '').$linha['recurso_nome'].($linha['recurso_tipo'] < 4 ? '</a>' : '').'</td>';
		echo '<td align=center style="white-space: nowrap" width=110>'.($linha['recurso_tipo']!=5 ? $linha['inicio'] : '').'</td>';
		echo '<td align=center style="white-space: nowrap" width=110>'.($linha['recurso_tipo']!=5 ? $linha['fim'] : '').'</td>';
		echo '<td align=right style="white-space: nowrap" width=50>'.($linha['recurso_tipo']!=5 ? number_format($linha['recurso_tarefa_duracao'], 2, ',', '.') : '').'</td>';
    echo '<td align=right style="white-space: nowrap" width=50>'.number_format($linha['recurso_tarefa_quantidade'], 2, ',', '.').'</td>';
    echo '<td align=right style="white-space: nowrap" width=30>'.($linha['recurso_tipo'] < 4 ? $linha['recurso_tarefa_percentual'] : '').'</td>';
		echo '<td align=right style="white-space: nowrap" width=70>'.($linha['recurso_tipo'] < 4 ? number_format($linha['recurso_tarefa_valor_hora'], 2, ',', '.') : '').'</td>';
		echo '<td align=right style="white-space: nowrap" width=70>'.($linha['recurso_tipo']==4 ? number_format($linha['recurso_tarefa_custo'], 2, ',', '.') : '').'</td>';
		echo '<td align=center style="white-space: nowrap" width=25>'.($linha['recurso_tarefa_aprovou'] ? 'Sim' : 'Não').'</td>';
		echo '<td style="white-space: nowrap" width=25>'.($linha['data_aprovou'] ? $linha['data_aprovou'] : '&nbsp;').'</td>';
		echo '<td>'.($linha['recurso_tarefa_obs'] ? $linha['recurso_tarefa_obs'] : '&nbsp;').'</td>';
		echo '<td style="white-space: nowrap" width=16>'.($linha['recurso_tarefa_aprovou']!=1 ? '<a href="javascript: void(0);" onclick="editar_trabalho('.$linha['recurso_tarefa_id'].');">'.imagem('icones/editar.gif').'</a><a href="javascript: void(0);" onclick="if (confirm(\''.'Tem certeza que deseja excluir este período trabalhado?'.'\')) {excluir_trabalho('.$linha['recurso_tarefa_id'].');}">'.imagem('icones/remover.png').'</a>' : '').'</td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';
echo '</table>';




















echo '<tr><td colspan=20 style="display:none" id="editar_custo"><fieldset><legend class=texto style="color: black;">'.dica('Custos','Lista de custos vinculados a este bloco de alocação.').'&nbsp;<b>Custos</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
$data = new CData(date("Y-m-d H:i:s"));
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
$unidade= getSisValor('TipoUnidade');
echo '<tr><td align="right" style="white-space: nowrap"><input type="hidden" name="recurso_tarefa_custo_id" id="recurso_tarefa_custo_id" value="" />'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="recurso_tarefa_custo_nome" id="recurso_tarefa_custo_nome" value="" maxlength="255" size="40" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida deste item.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'recurso_tarefa_custo_tipo', 'class=texto size=1').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();"name="recurso_tarefa_custo_quantidade" id="recurso_tarefa_custo_quantidade" value="" maxlength="255" style="width:70px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Unitário', 'Insira o valor deste item.').'Valor unitário:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="recurso_tarefa_custo_valor" id="recurso_tarefa_custo_valor" value="" size="40" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda utilizada neste item.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'recurso_tarefa_custo_moeda', 'class=texto size=1 style="width:395px;"', 1).'</td></tr>';
echo '<tr id="combo_data_moeda"><td align="right">'.dica('Data da Cotação','Data da cotação da moeda.').'Data da cotação:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="recurso_tarefa_custo_data_moeda" id="recurso_tarefa_custo_data_moeda" value="'.($data_texto ? $data_texto->format('%Y%m%d') : '').'" /><input type="text" name="data6_texto"  id="data6_texto" style="width:70px;" onchange="setData(\'env\', \'data6_texto\', \'recurso_tarefa_custo_data_moeda\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data da Cotação', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data da cotação da moeda estrangeira.').'<a href="javascript: void(0);" ><img id="f_btn6" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário2" /></a>'.dicaF().'</td></tr></table></td></tr>';

//bdi novo
if ($config['bdi']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%):'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="recurso_tarefa_custo_bdi" id="recurso_tarefa_custo_bdi" value="" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_custo_bdi" id="recurso_tarefa_custo_bdi" value="0" />';




echo '<tr><td align="right" style="white-space: nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'recurso_tarefa_custo_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'recurso_tarefa_custo_grupo_despesa', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'recurso_tarefa_custo_modalidade_aplicacao', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</td></tr>';
$nd=vetor_nd((isset($atual['recurso_tarefa_custo_nd']) ? $atual['recurso_tarefa_custo_nd'] : ''), null, null, 3 ,(isset($atual['recurso_tarefa_custo_categoria_economica']) ?  $atual['recurso_tarefa_custo_categoria_economica'] : ''), (isset($atual['recurso_tarefa_custo_grupo_despesa']) ?  $atual['recurso_tarefa_custo_grupo_despesa'] : ''), (isset($atual['recurso_tarefa_custo_modalidade_aplicacao']) ?  $atual['recurso_tarefa_custo_modalidade_aplicacao'] : ''));
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'recurso_tarefa_custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</div></td></tr>';

if (isset($exibir['codigo']) && $exibir['codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['codigo_valor']), 'Insira '.$config['genero_codigo_valor'].' '.$config['codigo_valor'].' deste item.').ucfirst($config['codigo_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="recurso_tarefa_custo_codigo" id="recurso_tarefa_custo_codigo" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_custo_codigo" id="recurso_tarefa_custo_codigo" value="" />';

if (isset($exibir['fonte']) && $exibir['fonte']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fonte_valor']), 'Insira '.$config['genero_fonte_valor'].' '.$config['fonte_valor'].' deste item.').ucfirst($config['fonte_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="recurso_tarefa_custo_fonte" id="recurso_tarefa_custo_fonte" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_custo_fonte" id="recurso_tarefa_custo_fonte" value="" />';

if (isset($exibir['regiao']) && $exibir['regiao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['regiao_valor']), 'Insira '.$config['genero_regiao_valor'].' '.$config['regiao_valor'].' deste item.').ucfirst($config['regiao_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="recurso_tarefa_custo_regiao" id="recurso_tarefa_custo_regiao" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="recurso_tarefa_custo_regiao" id="recurso_tarefa_custo_regiao" value="" />';	


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Total', 'O valor total do item.').'Total:'.dicaF().'</td><td><div id="total_custo"></div></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descrição', 'Insira a descrição deste item.').'Descrição:'.dicaF().'</td><td><textarea style="width:395px;" rows="5" class="textarea" data-gpweb-cmp="ckeditor" name="recurso_tarefa_custo_descricao" id="recurso_tarefa_custo_descricao"></textarea></td></tr>';






echo '<tr><td align="right">'.dica('Data','Data a ser efetuado o custo.').'Data:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="recurso_tarefa_custo_data_limite" id="recurso_tarefa_custo_data_limite" value="'.($data_texto ? $data_texto->format('%Y-%m-%d') : '').'" /><input type="text" name="data_texto"  id="data_texto" style="width:70px;" onchange="setData(\'env\', \'data_texto\', \'recurso_tarefa_custo_data_limite\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite para o recebimento do ítem.').'<a href="javascript: void(0);" ><img id="f_btn5" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" /></a>'.dicaF().'</td></tr></table></td></tr>';

echo '</table></td><td id="adicionar_custo" style="display:" align=left><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/adicionar_g.png','Incluir Custo','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o custo.').'</a></td>';
echo '<td id="confirmar_custo" style="display:none" align=left><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do custo.').'</a><a href="javascript: void(0);" onclick="cancelar_gasto();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do custo .').'</a></td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan=20 align=center><div id="combo_custos"></div></td></tr>';
echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20 style="display:none" id="editar_arquivos"></td></tr>';
/*
echo '<tr><td colspan=20 style="display:none" id="editar_arquivos"><fieldset><legend class=texto style="color: black;">'.dica('arquivos','Lista de arquivos vinculados a este bloco de alocação.').'&nbsp;<b>Arquivos</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
echo '<tr><td colspan=20 align=left><div id="combo_arquivos"></div></td></tr>';
echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste botão para enviar arquivo e salvar o mesmo no sistema.','','env.gravar_arquivo.value=1; env.submit()').'</td></tr></table></td></tr>';
echo '</table></fieldset></td></tr>';
*/


echo '<tr><td>'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';

?>

<script type="text/javascript">

function ver_recursos(){
	var tipo=document.getElementById('tipo_recurso').value;
	var cia_id=document.getElementById('cia_id').value;
	var dept_id=document.getElementById('dept_id').value;
	var ver_subordinadas=document.getElementById('ver_subordinadas').value;
	var recurso_responsavel=document.getElementById('recurso_responsavel').value;
	var recurso_ano=document.getElementById('recurso_ano').value;
	var recurso_ugr=document.getElementById('recurso_ugr').value;
	var recurso_ptres=document.getElementById('recurso_ptres').value;
	var recurso_credito_adicional=document.getElementById('recurso_credito_adicional').value;
	var recurso_movimentacao_orcamentaria=document.getElementById('recurso_movimentacao_orcamentaria').value;
	var recurso_identificador_uso=document.getElementById('recurso_identificador_uso').value;
	var recurso_pesquisa=document.getElementById('recurso_pesquisa').value;
	xajax_ver_recursos(
		cia_id, 
		ver_subordinadas, 
		dept_id, 
		tipo, 
		recurso_responsavel, 
		recurso_ano, 
		recurso_ugr, 
		recurso_ptres, 
		recurso_credito_adicional, 
		recurso_movimentacao_orcamentaria, 
		recurso_identificador_uso, 
		recurso_pesquisa
		);
	}



function selecionar_recurso(recurso_id){
	xajax_recurso_tipo(recurso_id);
	
	
	xajax_detalhes_recurso(recurso_id, document.getElementById('tarefa_id').value, 'detalhes_recurso');
	
	document.getElementById('recurso_id').value=recurso_id;
	
	
	if (recurso_id > 0) document.getElementById('inserir_trabalho').style.display='';
	else document.getElementById('inserir_trabalho').style.display='none';
	
	if(document.getElementById('recurso_tipo').value==5) {
		document.getElementById("tipo_qnt").innerHTML="<?php echo $config['simbolo_moeda']?>:";
		document.getElementById('disponibilidade').style.display='none';
		
		
		document.getElementById('campo_percentual_alocado').style.display='none';
		document.getElementById('campo_valor_hora').style.display='none';
		document.getElementById('campo_custo').style.display='none';
		
		
		document.getElementById('campo_inicio').style.display='none';
		document.getElementById('campo_fim').style.display='none';
		document.getElementById('campo_duracao').style.display='none';
		document.getElementById('campo_corrido').style.display='none';
		}
		
	else if(document.getElementById('recurso_tipo').value==4) {
		document.getElementById("tipo_qnt").innerHTML="Quantidade:";
		document.getElementById('disponibilidade').style.display='none';
		
		document.getElementById('campo_percentual_alocado').style.display='none';
		document.getElementById('campo_valor_hora').style.display='none';
		document.getElementById('campo_custo').style.display='';
		
		document.getElementById('campo_inicio').style.display='';
		document.getElementById('campo_fim').style.display='';
		document.getElementById('campo_duracao').style.display='';
		document.getElementById('campo_corrido').style.display='';
		}	
		
		
		
	else{
		document.getElementById("tipo_qnt").innerHTML="Quantidade:";
		document.getElementById('disponibilidade').style.display='';
		
		document.getElementById('campo_percentual_alocado').style.display='';
		document.getElementById('campo_valor_hora').style.display='';
		document.getElementById('campo_custo').style.display='none';
		
		document.getElementById('campo_inicio').style.display='';
		document.getElementById('campo_fim').style.display='';
		document.getElementById('campo_duracao').style.display='';
		document.getElementById('campo_corrido').style.display='';
		}
	}
	
	

	
function alocacao(){
	if (!document.getElementById('mat_recursos').value) alert ('É necessário escolher primeiramente um recurso.');
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('Alocação', 820, 500, 'm=recursos&a=alocacao&dialogo=1&cia_id=<?php echo $tarefa["projeto_cia"] ?>&recurso_id='+document.getElementById('mat_recursos').options[document.getElementById('mat_recursos').selectedIndex].value+'&editar=1', window.setResponsavel, window);
	else window.open('./index.php?m=recursos&a=alocacao&dialogo=1&cia_id=<?php echo $tarefa["projeto_cia"] ?>&recurso_id='+document.getElementById('mat_recursos').options[document.getElementById('mat_recursos').selectedIndex].value+'&editar=1', 'Alocação', 'height=620,width=820,resizable,scrollbars=yes');
	}	
	
















//recurso
function mudar_posicao_recurso(ordem, recurso_tarefa_id, direcao){
	xajax_mudar_posicao_recurso(ordem, recurso_tarefa_id, direcao, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
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




function ver_orcamentario(){
	if (document.getElementById('tipo_recurso').value==5){
		document.getElementById('combo_ano').style.display='';
		document.getElementById('identificador').style.display='';
		document.getElementById('credito_adicional').style.display='';
		document.getElementById('movimentacao').style.display='';
		document.getElementById('ptres').style.display='';
		document.getElementById('ugrs').style.display='';
		}
	else {
		document.getElementById('combo_ano').style.display='none';
		document.getElementById('identificador').style.display='none';
		document.getElementById('credito_adicional').style.display='none';
		document.getElementById('movimentacao').style.display='none';
		document.getElementById('ptres').style.display='none';
		document.getElementById('ugrs').style.display='none';
		}
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:350px;" onchange="javascript:mudar_om();"');
	}


function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia, deptartamento, nome){
	env.dept_id.value=deptartamento;
	env.nome_dept.value=nome;

	if (deptartamento > 0) {
		document.getElementById('combo_dept').style.display='';
		document.getElementById('combo_dept2').style.display='none';
		}
	else {
		document.getElementById('combo_dept').style.display='none';
		document.getElementById('combo_dept2').style.display='';
		}
	}


function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('recurso_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('recurso_responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('recurso_responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	ver_recursos();
	}



























	
function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}		
	
function mudar_nd(){
	xajax_mudar_nd_ajax(document.getElementById('recurso_tarefa_custo_nd').value, 'recurso_tarefa_custo_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.recurso_tarefa_custo_categoria_economica.value, env.recurso_tarefa_custo_grupo_despesa.value, env.recurso_tarefa_custo_modalidade_aplicacao.value);
	}	

function mudar_nd1(){
	xajax_mudar_nd_ajax(document.getElementById('recurso_tarefa_nd').value, 'recurso_tarefa_nd', 'combo_nd1','class=texto size=1 style="width:395px;" onchange="mudar_nd1();"', 3, env.recurso_tarefa_categoria_economica.value, env.recurso_tarefa_grupo_despesa.value, env.recurso_tarefa_modalidade_aplicacao.value);
	}	

	
function atualizar_recursos(){
	xajax_atualizar_recursos(
		document.getElementById('incluir_concluidas').checked,
		document.getElementById('projeto_id').value,
		document.getElementById('tarefa_id').value,
		document.getElementById('recurso_id').value
		);

	xajax_atualizar_lista(
		null,
		null,
		document.getElementById('recurso_id').value,
		document.getElementById('projeto_id').value,
		document.getElementById('tarefa_id').value,
		document.getElementById('incluir_concluidas').checked
		);
	}

function mudar_recurso(){
	document.getElementById('editar_custo').style.display='none';
	document.getElementById('editar_arquivos').style.display='none';
	document.getElementById('combo_alerta').innerHTML='';
	xajax_selecionar_tarefa(document.getElementById('tarefa_id').value, document.getElementById("recurso_id").value, document.getElementById('tempo_corrido_ponto').checked);
	document.getElementById('inserir_trabalho').style.display='';
		
	}


function editar_trabalho(recurso_tarefa_id){
	xajax_editar_trabalho(recurso_tarefa_id);
	
	CKEDITOR.instances['observacao'].setData(document.getElementById('texto_apoio_obs').value);
	
	
	document.getElementById('editar_custo').style.display='none';
	document.getElementById('editar_arquivos').style.display='none';
	document.getElementById('combo_alerta').innerHTML='';
	document.getElementById('inserir_trabalho').style.display='';
	
	document.getElementById('adicionar_trabalho').style.display='none';
	document.getElementById('confirmar_trabalho').style.display='';
	}

function  cancelar_atualizacao_trabalho(){
	document.getElementById('adicionar_trabalho').style.display='';
	document.getElementById('confirmar_trabalho').style.display='none';
	}

function confirmar_atualizacao_trabalho(){
  var max = parseFloat(document.getElementById('qnt_maxima').value);
	var qt = moeda2float(document.getElementById('quantidade').value);

  if (max < qt){
      alert('A quantidade máxima deste recurso é de '+float2moeda(max));
      return false;
      }

	var f=document.env;
	var inicio=f.data_inicio_real.value+' '+f.hora_inicio.value+':'+f.minuto_inicio.value+':00';
	var fim =f.data_fim_real.value+' '+f.hora_fim.value+':'+f.minuto_fim.value+':00';
	xajax_atualizacao_trabalho(
		document.getElementById('recurso_tarefa_id').value, 
		inicio, 
		fim, 
		document.getElementById('duracao').value, 
		document.getElementById('tarefa_id').value, 
		document.env.recurso_id.value, 
		document.getElementById('valor_hora').value, 
		document.getElementById('custo').value,
		CKEDITOR.instances['observacao'].getData(),
		document.getElementById('quantidade').value, 
		document.getElementById('tempo_corrido_ponto').checked,
		document.getElementById('percentual_alocado').value
		);
		
	CKEDITOR.instances['observacao'].setData('');	
		
	document.getElementById('adicionar_trabalho').style.display='';
	document.getElementById('confirmar_trabalho').style.display='none';
	}



function excluir_arquivo(recurso_tarefa_arquivo_id){
	xajax_excluir_arquivo(recurso_tarefa_arquivo_id, document.getElementById('recurso_tarefa_id').value);
	}

function mudar_posicao_arquivo(recurso_tarefa_arquivo_ordem, recurso_tarefa_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(recurso_tarefa_arquivo_ordem, recurso_tarefa_arquivo_id, direcao, document.getElementById('recurso_tarefa_id').value);
	}

function ver_gastos(recurso_tarefa_id){
	document.getElementById('recurso_tarefa_id').value=recurso_tarefa_id;
	xajax_exibir_custo(recurso_tarefa_id);
	xajax_exibir_arquivo(recurso_tarefa_id);
	document.getElementById('editar_custo').style.display='';
	document.getElementById('editar_arquivos').style.display='';
	document.getElementById('recurso_tarefa_id').value=recurso_tarefa_id;
	}

function excluir_trabalho(recurso_tarefa_id){
	xajax_excluir_trabalho(recurso_tarefa_id, document.env.recurso_id.value, document.getElementById('tarefa_id').value);
	}


function incluir_trabalho(){
	
  var max = parseFloat(document.getElementById('qnt_maxima').value), qt = moeda2float(document.getElementById('quantidade').value);

	if (max < qt){
		alert('A quantidade máxima deste recurso é de '+float2moeda(max));
		return false;
		}
	var f=document.env;
	var inicio=f.data_inicio_real.value+' '+f.hora_inicio.value+':'+f.minuto_inicio.value+':00';
	var fim =f.data_fim_real.value+' '+f.hora_fim.value+':'+f.minuto_fim.value+':00';

	xajax_incluir_trabalho(
	inicio, 
	fim, 
	document.env.recurso_id.value, 
	document.getElementById('tipo_recurso').value,
	document.getElementById('tarefa_id').value, 
	document.getElementById('duracao').value, 
	document.getElementById('valor_hora').value, 
	document.getElementById('custo').value,
	CKEDITOR.instances['observacao'].getData(),
	document.getElementById('quantidade').value,
	document.getElementById('tempo_corrido_ponto').checked, 
	document.getElementById('percentual_alocado').value,
	
	document.getElementById('recurso_tarefa_nd').value,
	document.getElementById('recurso_tarefa_categoria_economica').value,
	document.getElementById('recurso_tarefa_grupo_despesa').value,
	document.getElementById('recurso_tarefa_modalidade_aplicacao').value,
	document.getElementById('recurso_tarefa_codigo').value,
	document.getElementById('recurso_tarefa_fonte').value,
	document.getElementById('recurso_tarefa_regiao').value,
	document.getElementById('recurso_tarefa_bdi').value,
	document.getElementById('recurso_tarefa_moeda').value,
	document.getElementById('recurso_tarefa_data_moeda').value
	);
	}


function selecionar_tarefa(){
	if(document.getElementById('tarefa_id').value) {
		document.getElementById('editar_custo').style.display='none';
		document.getElementById('editar_arquivos').style.display='none';
		document.getElementById('combo_alerta').innerHTML='';
		xajax_selecionar_tarefa(document.getElementById('tarefa_id').value, document.env.recurso_id.value, document.getElementById('tempo_corrido_ponto').checked);
		document.getElementById('inserir_trabalho').style.display='';
		}
	}

function horas_ajax(){
	var f=document.env;
	var inicio=f.data_inicio_real.value+' '+f.hora_inicio.value+':'+f.minuto_inicio.value+':00';
	var fim =f.data_fim_real.value+' '+f.hora_fim.value+':'+f.minuto_fim.value+':00';
	xajax_calcular_duracao(
		inicio, 
		fim, 
		document.env.recurso_id.value, 
		document.getElementById('tarefa_id').value,
		document.getElementById('tempo_corrido_ponto').checked,
		document.getElementById('percentual_alocado').value
		);
	}


function data_ajax(){
	var f=document.env;
	var inicio=f.data_inicio_real.value+' '+f.hora_inicio.value+':'+f.minuto_inicio.value+':00';
	var horas=f.duracao.value;
	xajax_data_final_periodo(
		inicio, 
		horas, 
		document.env.recurso_id.value, 
		document.getElementById('tarefa_id').value,
		document.getElementById('tempo_corrido_ponto').checked, 
		document.getElementById('percentual_alocado').value
		);
	}



function valor(){
	var custo=moeda2float(document.getElementById('recurso_tarefa_custo_valor').value);
	var qnt=moeda2float(document.getElementById('recurso_tarefa_custo_quantidade').value);
	var bdi=moeda2float(document.getElementById('recurso_tarefa_custo_bdi').value);
	if (bdi=='') bdi=0;
	if (custo=='') custo=0;
	if (valor=='') valor=0;
	document.getElementById('total_custo').innerHTML ='<b>'+float2moeda((custo*qnt)*((100+bdi)/100))+'</b>';
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



function mudar_posicao_custo(recurso_tarefa_custo_ordem, recurso_tarefa_custo_id, direcao){
	xajax_mudar_posicao_custo(recurso_tarefa_custo_ordem, recurso_tarefa_custo_id, direcao, document.getElementById('recurso_tarefa_id').value);
	}

function editar_custo(recurso_tarefa_custo_id){
	xajax_editar_custo(recurso_tarefa_custo_id);
	CKEDITOR.instances['recurso_tarefa_custo_descricao'].setData(document.getElementById('texto_apoio_custo_descricao').value);
	document.getElementById('adicionar_custo').style.display="none";
	document.getElementById('confirmar_custo').style.display="";
	}

function incluir_custo(){
	if (document.getElementById('recurso_tarefa_custo_nome').value!=''){
		
		xajax_incluir_custo(
		document.getElementById('recurso_tarefa_id').value,
		document.getElementById('recurso_tarefa_custo_id').value,
		document.getElementById('recurso_tarefa_custo_tipo').value,
		document.getElementById('recurso_tarefa_custo_nome').value,
		document.getElementById('recurso_tarefa_custo_data_limite').value,
		document.getElementById('recurso_tarefa_custo_quantidade').value,
		document.getElementById('recurso_tarefa_custo_valor').value,
		CKEDITOR.instances['recurso_tarefa_custo_descricao'].getData(),
		document.getElementById('recurso_tarefa_custo_nd').value,
		document.getElementById('recurso_tarefa_custo_categoria_economica').value,
		document.getElementById('recurso_tarefa_custo_grupo_despesa').value,
		document.getElementById('recurso_tarefa_custo_modalidade_aplicacao').value,
		document.getElementById('recurso_tarefa_custo_codigo').value,
		document.getElementById('recurso_tarefa_custo_fonte').value,
		document.getElementById('recurso_tarefa_custo_regiao').value,
		document.getElementById('recurso_tarefa_custo_bdi').value,
		document.getElementById('recurso_tarefa_custo_moeda').value,
		document.getElementById('recurso_tarefa_custo_data_moeda').value
		);

		document.getElementById('recurso_tarefa_custo_id').value=null;
		document.getElementById('recurso_tarefa_custo_tipo').value='';
		document.getElementById('recurso_tarefa_custo_nome').value='';
		document.getElementById('recurso_tarefa_custo_quantidade').value='';
		document.getElementById('recurso_tarefa_custo_valor').value='';
		CKEDITOR.instances['recurso_tarefa_custo_descricao'].setData('');
		document.getElementById('recurso_tarefa_custo_nd').value='';
		document.getElementById('recurso_tarefa_custo_categoria_economica').value='';
		document.getElementById('recurso_tarefa_custo_grupo_despesa').value='';
		document.getElementById('recurso_tarefa_custo_modalidade_aplicacao').value='';

		document.getElementById('recurso_tarefa_custo_codigo').value='';
		document.getElementById('recurso_tarefa_custo_fonte').value='';
		document.getElementById('recurso_tarefa_custo_regiao').value='';

		document.getElementById('total_custo').innerHTML='';
		document.getElementById('adicionar_custo').style.display='';
		document.getElementById('confirmar_custo').style.display='none';
		}
	else {
		alert('Insira um nome para o custo.');
		document.getElementById('recurso_tarefa_custo_nome').focus();
		}
	}

function cancelar_gasto(){
	document.getElementById('recurso_tarefa_custo_id').value=null;
	document.getElementById('recurso_tarefa_custo_tipo').value='';
	document.getElementById('recurso_tarefa_custo_nome').value='';
	document.getElementById('recurso_tarefa_custo_quantidade').value='';
	document.getElementById('recurso_tarefa_custo_valor').value='';
	CKEDITOR.instances['recurso_tarefa_custo_descricao'].setData('');
	document.getElementById('recurso_tarefa_custo_nd').value='';
	document.getElementById('recurso_tarefa_custo_categoria_economica').value='';
	document.getElementById('recurso_tarefa_custo_grupo_despesa').value='';
	document.getElementById('recurso_tarefa_custo_modalidade_aplicacao').value='';
	document.getElementById('total_custo').innerHTML='';
	document.getElementById('adicionar_custo').style.display='';
	document.getElementById('confirmar_custo').style.display='none';
	}

function excluir_custo(recurso_tarefa_custo_id){
	xajax_excluir_custo(recurso_tarefa_custo_id, document.getElementById('recurso_tarefa_id').value);
	}






function CompararDatasFiltro(){
	var str1 = document.getElementById('data_inicio_filtro').value;
  var str2 = document.getElementById('data_fim_filtro').value;
  var dt1  = parseInt(str1.substring(0,2),10);
  var mon1 = parseInt(str1.substring(3,5),10);
  var yr1  = parseInt(str1.substring(6,10),10);
  var dt2  = parseInt(str2.substring(0,2),10);
  var mon2 = parseInt(str2.substring(3,5),10);
  var yr2  = parseInt(str2.substring(6,10),10);
  var date1 = new Date(yr1, mon1, dt1);
  var date2 = new Date(yr2, mon2, dt2);

  if(date2 < date1){
    document.getElementById('data_fim_filtro').value=document.getElementById('data_inicio_filtro').value;
    document.getElementById('data_fim_filtro_real').value=document.getElementById('data_inicio_filtro_real').value;
  	}
 }


function setData(frm_nome, f_data, data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			CompararDatasHoras();
			}
		}
	else campo_data_real.value = '';
	}


function CompararDatasHoras(){
	var str1 = document.getElementById('data_inicio').value;
  var str2 = document.getElementById('data_fim').value;
  var dt1  = parseInt(str1.substring(0,2),10);
  var mon1 = parseInt(str1.substring(3,5),10);
  var yr1  = parseInt(str1.substring(6,10),10);
  var dt2  = parseInt(str2.substring(0,2),10);
  var mon2 = parseInt(str2.substring(3,5),10);
  var yr2  = parseInt(str2.substring(6,10),10);

  hora2=document.getElementById('hora_fim').value;
  minuto2=document.getElementById('minuto_fim').value;

  hora1=document.getElementById('hora_inicio').value;
  minuto1=document.getElementById('minuto_inicio').value;

  var date1 = new Date(yr1, mon1, dt1, hora1, minuto1);
  var date2 = new Date(yr2, mon2, dt2, hora2, minuto2);

  if(date2 < date1){
    document.getElementById('data_fim').value=document.getElementById('data_inicio').value;
    document.getElementById('data_fim_real').value=document.getElementById('data_inicio_real').value;
    document.getElementById('hora_fim').value=document.getElementById('hora_inicio').value;
    document.getElementById('minuto_fim').value=document.getElementById('minuto_inicio').value;
  	}
 }


<?php if (!$projeto_id){ ?>

	var cal1 = Calendario.setup({
		trigger    : "f_btn1",
	  inputField : "data_inicio_filtro_real",
		date :  <?php echo $data_inicio->format('%Y-%m-%d')?>,
		selection: <?php echo $data_inicio->format('%Y-%m-%d')?>,
	  onSelect: function(cal1) {
	    var date = cal1.selection.get();
	    if (date){
	    	date = Calendario.intToDate(date);
	      document.getElementById("data_inicio_filtro").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("data_inicio_filtro_real").value = Calendario.printDate(date, "%Y-%m-%d");
	      CompararDatasFiltro();
	      }
	  	cal1.hide();
	  	}
	 });

	var cal2 = Calendario.setup({
		trigger : "f_btn2",
	  inputField : "data_fim_filtro_real",
		date : <?php echo $data_fim->format('%Y-%m-%d')?>,
		selection : <?php echo $data_fim->format('%Y-%m-%d')?>,
	  onSelect : function(cal2) {
	    var date = cal2.selection.get();
	    if (date){
	      date = Calendario.intToDate(date);
	      document.getElementById("data_fim_filtro").value = Calendario.printDate(date, "%d/%m/%Y");
	      document.getElementById("data_fim_filtro_real").value = Calendario.printDate(date, "%Y-%m-%d");
	      CompararDatasFiltro();
	      }
	  	cal2.hide();
	  	}
		});


<?php } ?>


	var cal6 = Calendario.setup({
  	trigger    : "f_btn6",
    inputField : "recurso_tarefa_custo_data_moeda",
  	date :  <?php echo $data_texto->format("%Y%m%d")?>,
  	selection: <?php echo $data_texto->format("%Y%m%d")?>,
    onSelect: function(cal6) {
    var date = cal6.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data6_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("recurso_tarefa_custo_data_moeda").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal6.hide();
  	}
  });  


var cal7 = Calendario.setup({
  	trigger    : "f_btn7",
    inputField : "recurso_tarefa_data_moeda",
  	date :  <?php echo $data_texto->format("%Y%m%d")?>,
  	selection: <?php echo $data_texto->format("%Y%m%d")?>,
    onSelect: function(cal7) {
    var date = cal7.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data7_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("recurso_tarefa_data_moeda").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal6.hide();
  	}
  });  





function setDataFiltro(frm_nome, f_data, data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			CompararDatasFiltro();
			}
		}
	else campo_data_real.value = '';
	}


var cal3 = Calendario.setup({
	trigger    : "f_btn3",
  inputField : "data_inicio_real",
	date :  <?php echo $data_inicio->format('%Y-%m-%d')?>,
	selection: <?php echo $data_inicio->format('%Y-%m-%d')?>,
  onSelect: function(cal3) {
    var date = cal3.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("data_inicio_real").value = Calendario.printDate(date, "%Y-%m-%d");
      CompararDatasHoras();
      data_ajax();
      }
  	cal3.hide();
  	}
 });

var cal4 = Calendario.setup({
	trigger : "f_btn4",
  inputField : "data_fim_real",
	date : <?php echo $data_fim->format('%Y-%m-%d')?>,
	selection : <?php echo $data_fim->format('%Y-%m-%d')?>,
  onSelect : function(cal4) {
    var date = cal4.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("data_fim_real").value = Calendario.printDate(date, "%Y-%m-%d");
      CompararDatasHoras();
      horas_ajax();
      }
  	cal4.hide();
  	}
	});


var cal5 = Calendario.setup({
	trigger    : "f_btn5",
  inputField : "recurso_tarefa_custo_data_limite",
	date :  <?php echo $data_texto->format('%Y-%m-%d')?>,
	selection: <?php echo $data_texto->format('%Y-%m-%d')?>,
  onSelect: function(cal5) {
    var date = cal5.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("recurso_tarefa_custo_data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal5.hide();
  	}
 });
</script>
