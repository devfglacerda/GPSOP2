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

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


if (!$Aplic->usuario_super_admin)	$Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();

$campo_formulario_tipo=getParam($_REQUEST, 'campo_formulario_tipo', 'projeto');

$sql = new BDConsulta;


if (getParam($_REQUEST, 'salvar', null)){
	$campo=getParam($_REQUEST, 'campo', array());
	
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao');
	$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista as $linha) {
		$sql->adTabela('campo_formulario');
		$sql->adAtualizar('campo_formulario_ativo', (isset($campo[$linha['campo_formulario_campo']]) ? 1 : 0));
		$sql->adOnde('campo_formulario_campo = "'.$linha['campo_formulario_campo'].'"');
		$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\'');
		$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
		$sql->exec();
		$sql->limpar();
		}
	ver2('Opções de campos foram salvas.');
	}




$excessao=array();

if (!$Aplic->modulo_ativo('agrupamento')) {
	$excessao[]="'agrupamento'";
	$excessao[]="'agrupamentos'";
	}
if (!$Aplic->modulo_ativo('instrumento')) {
	$excessao[]="'instrumento'";
	$excessao[]="'instrumentos'";
	}
if (!$Aplic->modulo_ativo('swot')) {
	$excessao[]="'mswot'";
	$excessao[]="'mswots'";
	$excessao[]="'swot'";
	$excessao[]="'swots'";
	}
if (!$Aplic->modulo_ativo('problema')) {
	$excessao[]="'problema'";
	$excessao[]="'problemas'";
	}
if (!$Aplic->modulo_ativo('tr')) {
	$excessao[]="'tr'";
	$excessao[]="'trs'";
	}
$excessao=implode(',', $excessao);




$sql->adTabela('campo_formulario');
$sql->adCampo('DISTINCT campo_formulario_tipo');
if ($excessao) $sql->adOnde('campo_formulario_tipo NOT IN ('.$excessao.')');
$lista = $sql->carregarColuna();
$sql->limpar();
$tipos=array();

foreach($lista as $linha) $tipos[$linha]=ucfirst($linha);


$tipos['abertura']='Termo de abertura'; 
$tipos['acao']=ucfirst($config['acao']);
$tipos['acoes']=ucfirst($config['acao']).' (Lista)'; 
$tipos['agenda']='Compromisso';
if ($Aplic->modulo_ativo('agrupamento')) $tipos['agrupamento']='Agrupamento de '.ucfirst($config['projetos']);
if ($Aplic->modulo_ativo('agrupamento')) $tipos['agrupamentos']='Agrupamento (Lista)'; 
$tipos['ata']='Ata de Reunião';
$tipos['atas']='Ata de Reunião (Lista)'; 
$tipos['avaliacao']='Avaliação de Indicadores';
$tipos['brainstorm']='Brainstorm';
$tipos['calendario']='Agenda';
if ($Aplic->profissional) $tipos['canvas']=ucfirst($config['canvas']);
if ($Aplic->profissional) $tipos['canvass']=ucfirst($config['canvas']).' (Lista)'; 
$tipos['causa_efeito']='Diagrama de Causa-Efeito';
$tipos['checklist']='Checklist';
$tipos['checklists']='Checklist (Lista)'; 
$tipos['cias']=ucfirst($config['organizacao']).' (Lista)'; 
$tipos['demanda']='Demanda';
$tipos['estrategia']=ucfirst($config['iniciativa']); 
$tipos['estrategias']=ucfirst($config['iniciativa']).' (Lista)';  
$tipos['evento']='Evento';
$tipos['fator']=ucfirst($config['fator']); 
$tipos['fatores']=ucfirst($config['fator']).' (Lista)'; 
$tipos['forum']='Fórum';
$tipos['gut']='GUT';
$tipos['indicador']='Indicador';
$tipos['indicadores']='Indicador (Lista)'; 
if ($Aplic->modulo_ativo('instrumento')) $tipos['instrumento']=ucfirst($config['instrumento']);
$tipos['licao']=ucfirst($config['licao']);
$tipos['link']='Link';
if ($Aplic->profissional) $tipos['me']=ucfirst($config['me']);
if ($Aplic->profissional) $tipos['mes']=ucfirst($config['me']).' (Lista)'; 
$tipos['meta']=ucfirst($config['meta']);
$tipos['metas']=ucfirst($config['meta']).' (Lista)'; 
$tipos['monitoramento']='Monitoramento';
if ($Aplic->profissional && $Aplic->modulo_ativo('swot')) $tipos['mswot']='Matriz SWOT';
if ($Aplic->profissional && $Aplic->modulo_ativo('swot')) $tipos['mswots']='Matriz SWOT (Lista)';
$tipos['objetivo']=ucfirst($config['objetivo']); 
$tipos['objetivos']=ucfirst($config['objetivo']).' (Lista)'; 
$tipos['operativo']='Plano Operativo';
if ($Aplic->profissional) $tipos['painel']='Painel de indicador';
if ($Aplic->profissional) $tipos['painel_composicao']='Composição de painéis de indicadores';
if ($Aplic->profissional) $tipos['painel_odometro']='Odômetro';
$tipos['patrocinador']=ucfirst($config['patrocinador']);
$tipos['perspectiva']=ucfirst($config['perspectiva']); 
$tipos['perspectivas']=ucfirst($config['perspectiva']).' (Lista)'; 
$tipos['planejamento']='Planejamento estratégico';
$tipos['pratica']=ucfirst($config['pratica']);
$tipos['praticas']=ucfirst($config['pratica']).' (Lista)'; 
if ($Aplic->profissional && $Aplic->modulo_ativo('problema')) $tipos['problema']=ucfirst($config['problema']);
if ($Aplic->profissional && $Aplic->modulo_ativo('problema')) $tipos['problemas']=ucfirst($config['problema']).' (Lista)'; 
$tipos['programa']=ucfirst($config['programa']);
$tipos['programas']=ucfirst($config['programa']).' (Lista)';
$tipos['projeto']=ucfirst($config['projeto']); 
$tipos['projetos']=ucfirst($config['projeto']).' (Lista)'; 
$tipos['recurso']=ucfirst($config['recurso']);
$tipos['recursos']=ucfirst($config['recurso']).' (Lista)'; 
if ($Aplic->profissional) $tipos['risco']=ucfirst($config['risco']);
if ($Aplic->profissional) $tipos['riscos']=ucfirst($config['risco']).' (Lista)'; 
if ($Aplic->profissional) $tipos['risco_resposta']=ucfirst($config['risco_resposta']);
if ($Aplic->profissional) $tipos['risco_respostas']=ucfirst($config['risco_resposta']).' (Lista)'; 
if ($Aplic->profissional && $Aplic->modulo_ativo('swot')) $tipos['swot']='Campo SWOT';
if ($Aplic->profissional && $Aplic->modulo_ativo('swots')) $tipos['swot']='Campo SWOT (Lista)';
$tipos['tarefa']=ucfirst($config['tarefa']); 
$tipos['tarefas_projeto']='Colunas d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).' na aba de '.ucfirst($config['projeto']); 
$tipos['tema']=ucfirst($config['tema']); 
$tipos['temas']=ucfirst($config['tema']).' (Lista)'; 
$tipos['template']='Modelo de '.ucfirst($config['projeto']); 
$tipos['templates']='Modelo de '.ucfirst($config['projeto']).' (Lista)'; 
if ($Aplic->profissional) $tipos['tgn']=ucfirst($config['tgn']);
if ($Aplic->profissional) $tipos['tgns']=ucfirst($config['tgn']).' (Lista)'; 
if ($Aplic->profissional && $Aplic->modulo_ativo('tr')) $tipos['tr']=ucfirst($config['tr']);
$tipos['valor']='Valor inserido nos diversos objetos';
$tipos['viabilidade']='Estudo de viabilidade'; 
if ($Aplic->profissional) $tipos['tr']=ucfirst($config['tr']);
if ($Aplic->profissional) $tipos['trs']=ucfirst($config['tr']).' (Lista)'; 
$tipos['causa_efeitos']='Diagrama de Causa-Efeito (Lista)'; 
$tipos['arquivos']='Arquivo (Lista)'; 
$tipos['arquivo_pastas']='Pasta de Arquivo (Lista)'; 
$tipos['avisos']='Aviso (Lista)'; 
$tipos['brainstorms']='Brainstorm (Lista)'; 
$tipos['demandas']='Demanda (Lista)'; 
$tipos['foruns']='Fórum (Lista)'; 
$tipos['guts']='GUT (Lista)'; 
if ($Aplic->modulo_ativo('instrumento')) $tipos['instrumentos']='Instrumento (Lista)'; 
$tipos['licoes']='Lição aprendida (Lista)'; 
$tipos['links']='Link (Lista)'; 
$tipos['monitoramentos']='Monitoramento (Lista)'; 
$tipos['operativos']='Plano Operativo (Lista)'; 
if ($Aplic->profissional) $tipos['paineis']='Painel de indicador (Lista)'; 
if ($Aplic->profissional) $tipos['paineis_composicao']='Composição de painéis de indicadores (Lista)'; 
if ($Aplic->profissional) $tipos['paineis_odometro']='Odômetro (Lista)'; 
if ($Aplic->profissional) $tipos['paineis_slideshow']='Slideshow de composições (Lista)'; 
$tipos['patrocinadores']=ucfirst($config['patrocinador']).' (Lista)'; 
$tipos['projeto_aberturas']='Termo de abertura (Lista)'; 
$tipos['projeto_viabilidades']='Estudo de viabilidade (Lista)'; 
$tipos['planos_gestao']='Planejamento estratético (Lista)'; 


asort($tipos);

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao');
$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$lista = $sql->lista();
$sql->limpar();




$botoesTitulo = new CBlocoTitulo('Campos dos Formulários', 'config-sistema.png', $m);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();



echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input name="a" type="hidden" value="campo_formulario" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="salvar" type="hidden" value="" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%" align="center">';

echo '<tr><td colspan=2><table><tr><td>'.dica('Módulo', 'Escolha na caixa de opção à direita o módulo no qual deseja marcar quais campos serão preenchidos quando da criação e edição de objetos do tipo específico..').'Módulo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'campo_formulario_tipo', 'class="texto" size="1" onchange="env.submit();"', $campo_formulario_tipo).'</td></tr></table></td></tr>';

foreach($lista as $linha) echo '<tr><td width=16 ><input class="texto" type="checkbox" name="campo['.$linha['campo_formulario_campo'].']" value="1" '.($linha['campo_formulario_ativo'] ? 'checked="checked"': '').'  /></td><td>'.$linha['campo_formulario_descricao'].'</td></tr>';


echo '<tr><td colspan="2">'.botao('salvar', 'Salvar', 'Salvar as configurações.','','env.salvar.value=1; env.submit();').'</td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>