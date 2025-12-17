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
if (!$dialogo) $Aplic->salvarPosicao();

$usuario_id=$Aplic->usuario_id;

$projeto_status_aguardando = 4;
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TabParaFazerTarefa', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TabParaFazerTarefa') !== null ? $Aplic->getEstado('TabParaFazerTarefa') : 0;

//$evento_filtro = $Aplic->getEstado('IdxFiltro' , $Aplic->usuario_prefs['filtroevento']);
if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('IdxDept', getParam($_REQUEST, 'dept_id', null));

$escolhe_projeto='';

//$evento_filtro_lista = array('meu' => 'Meus eventos', 'dono' => 'Eventos que eu criei', 'todos' => 'Todos os eventos');
$evento_filtro='todos';

$sql = new BDConsulta;

if (!isset($ver_min) || !$ver_min) {
	$botoesTitulo = new CBlocoTitulo('A Fazer', 'afazer.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	}
if ($a == 'parafazer') {
	$podeAcessar_email=$Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso');
	$podeAcessar_calendario=$Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso');
	$podeAcessar_tarefas=$Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso');
	$podeAcessar_praticas=$Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso');
	$caixaTab = new CTabBox('m=tarefas&a=parafazer', '', $tab);
	
	
	$total=0;

	//quantidade Eventos
	$sql->adTabela('eventos', 'e');
	$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
	$sql->adOnde('(evento_dono IN ('.$Aplic->usuario_lista_grupo.') OR (evento_participante_usuario IN ('.$Aplic->usuario_lista_grupo.') AND (evento_participante_aceito=1)))');		
	$sql->adOnde('evento_fim >= \''.date('Y-m-d H:i:s').'\'');
	$sql->adCampo('count(DISTINCT e.evento_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	$evento_filtro='todos_aceitos';
	if ($podeAcessar_calendario && $qnt) {
		if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/evento_lista_idx_pro', 'Eventos ('.$qnt.')',null,null,'Eventos','Visualizar os eventos em que esteja envolvido.');
		else $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.eventos', 'Eventos ('.$qnt.')',null,null,'Eventos','Visualizar os eventos em que esteja envolvido.');
		$total++;
		}

	$sql->adTabela('eventos', 'e');
	$sql->esqUnir('evento_participante', 'evento_participante', 'evento_participante_evento = e.evento_id');
	$sql->adOnde('evento_dono NOT IN ('.$Aplic->usuario_lista_grupo.') AND (evento_participante_usuario IN ('.$Aplic->usuario_lista_grupo.') AND evento_participante_aceito=0)');		
	$sql->adOnde('evento_fim >= \''.date('Y-m-d H:i:s').'\'');
	$sql->adCampo('count(DISTINCT e.evento_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	//$evento_filtro='todos_pendentes';
	if ($podeAcessar_calendario && $qnt) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/calendario/convite', '<span id="qnt_confirmar">Confirmar ('.$qnt.')</span>',null,null,'Confirmar Eventos','Visualizar os eventos em que se necessite confirmar presença.');
		$total++;
		}	
		
		
		
	$sql->adTabela('agenda');
	$sql->esqUnir('agenda_usuarios', 'agenda_usuarios', 'agenda_usuarios.agenda_id = agenda.agenda_id');
	$sql->adOnde('(agenda_dono IN ('.$Aplic->usuario_lista_grupo.') OR (agenda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND (agenda_usuarios.aceito=1 || agenda_usuarios.aceito=0)))');		
	$sql->adOnde('agenda_inicio >= \''.date('Y-m-d H:i:s').'\'');
	$sql->adCampo('count(DISTINCT agenda.agenda_id)');
	$qnt = $sql->Resultado();
	$sql->limpar();
	if ($podeAcessar_email && $qnt) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.compromissos', 'Compromissos ('.$qnt.')',null,null,'Compromissos','Visualizar os compromissos em que esteja envolvido.');
		$total++;
		}
	
	if ($podeAcessar_tarefas){
		$sql->adTabela('tarefas', 'ta');
		$sql->esqUnir('projetos', 'pr','pr.projeto_id=tarefa_projeto');
		$sql->esqUnir('tarefa_designados', 'td','td.tarefa_id = ta.tarefa_id');
		$sql->adCampo('count(DISTINCT ta.tarefa_id)');
		$sql->adOnde('projeto_template = 0 OR projeto_template IS NULL');
		$sql->adOnde('ta.tarefa_percentagem < 100 OR ta.tarefa_percentagem IS NULL');
		$sql->adOnde('projeto_ativo = 1');
		$sql->adOnde('td.usuario_id IN ('.$Aplic->usuario_lista_grupo.') OR tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/parafazer_tarefas_sub', ucfirst($config['tarefas']).' ('.$qnt.')',null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) {
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		$sql->esqUnir('pratica_indicador_usuarios','pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) { 
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores ('.$qnt.')',null,null,'Indicadores','Visualizar os indicadores que seja responsável ou foi designado.');
			$total++;
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) {
		$sql->adTabela('praticas');
		$sql->esqUnir('pratica_usuarios', 'pratica_usuarios', 'pratica_usuarios.pratica_id=praticas.pratica_id');
		$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR pratica_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adCampo('count(DISTINCT praticas.pratica_id)');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_praticas', ucfirst($config['praticas']).' ('.$qnt.')',null,null,ucfirst($config['praticas']),'Visualizar '.$config['genero_pratica'].'s '.$config['praticas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}
	
	
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {
		$sql->adTabela('plano_acao');
		$sql->adCampo('count(DISTINCT plano_acao.plano_acao_id) as soma');
		$sql->esqUnir('plano_acao_usuario', 'plano_acao_usuario', 'plano_acao_usuario_acao = plano_acao.plano_acao_id');
		$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR plano_acao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('plano_acao_percentagem < 100');
		$sql->adOnde('plano_acao_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']).' ('.$qnt.')',null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' que seja responsável ou foi designado.');
			$total++;
			}
		$sql->adTabela('plano_acao_item');
		$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
		$sql->adCampo('count(DISTINCT plano_acao_item.plano_acao_item_id) as soma');
		$sql->esqUnir('plano_acao_item_usuario', 'plano_acao_item_usuario', 'plano_acao_item_usuario_item = plano_acao_item.plano_acao_item_id');
		$sql->adOnde('plano_acao_item_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR plano_acao_item_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('plano_acao_item_percentagem < 100');
		$sql->adOnde('plano_acao_ativo = 1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt){ 
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_itens_idx', 'Itens de '.ucfirst($config['acoes']).' ('.$qnt.')',null,null,'Itens de '.ucfirst($config['acoes']),'Visualizar os itens de '.$config['genero_acao'].'s '.$config['acoes'].' que seja responsável ou foi designado.');
			$total++;
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela('ata_acao');
		$sql->esqUnir('ata','ata','ata_acao_ata = ata.ata_id');
		$sql->adCampo('count(DISTINCT ata_acao.ata_acao_id)');
		$sql->esqUnir('ata_acao_usuario','ata_acao_usuario','ata_acao_usuario_acao=ata_acao.ata_acao_id');	
	 	$sql->adOnde('ata_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR ata_acao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.')'); 	
		$sql->adOnde('ata_acao_percentagem < 100');
		$sql->adOnde('ata_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/atas/acao_tabela', 'Ações de Atas'.' ('.$qnt.')',null,null,'Ações de Atas de Reunião','Visualizar as ações de atas de reunião que seja responsável ou foi designado.');
			$total++;
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema');
		$sql->adCampo('count(DISTINCT problema.problema_id)');
		$sql->esqUnir('problema_usuarios','problema_usuarios','problema_usuarios.problema_id=problema.problema_id');
		$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('problema_percentagem < 100');
		$sql->adOnde('problema_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']).' ('.$qnt.')',null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' que seja responsável ou foi designado.');
			$total++;
			}
		}
	
	if ($Aplic->profissional) {
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id)');
		$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
		$sql->adOnde('assinatura_data IS NULL');
		$sql->adOnde('assinatura_uuid IS NULL');
		$sql->adOnde('assinatura_bloqueado!=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_assinaturas_pro', 'Assinaturas'.' ('.$qnt.')',null,null,'Assinaturas','Visualizar os módulos em que necessita ainda aprovar.');
			$total++;
			}
		}
		
		
	
	
	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'acesso')) {
		$sql->adTabela('instrumento');
		$sql->adCampo('count(DISTINCT instrumento.instrumento_id)');
		$sql->esqUnir('instrumento_designados','instrumento_designados','instrumento_designados.instrumento_id=instrumento.instrumento_id');
		$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR instrumento_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
		$sql->adOnde('instrumento_porcentagem < 100');
		$sql->adOnde('instrumento_ativo=1');
		$qnt = $sql->Resultado();
		$sql->limpar();
		if ($qnt) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/instrumento/instrumento_lista_idx', ucfirst($config['instrumentos']).' ('.$qnt.')',null,null,ucfirst($config['instrumentos']),'Visualizar '.$config['genero_instrumento'].'s '.$config['instrumentos'].' que seja responsável ou foi designado.');
			$total++;
			}
		}
	
	
	
	
	
	
	
	
	
	
	
		
		
		
	if ($total) $caixaTab->mostrar('','','','',true);
	else {
		
	echo estiloTopoCaixa();
	echo '<table cellspacing=1 cellpadding=0 width="100%" class="std"><tr><td>Não há nada a ser feito</td></tr></table>';
		}
	echo '</td></tr></table>';
	}
else include BASE_DIR.'/modulos/tarefas/parafazer_tarefas_sub.php';
if ($m !='calendario') echo estiloFundoCaixa();




?>
<script type="text/javascript">

function popLog(tarefa_id) {
	if(window.parent && window.parent.gpwebApp)	window.parent.gpwebApp.popUp('Registro',800, 465,'m=tarefas&a=ver_log_atualizar&dialogo=1&tarefa_id='+tarefa_id,window.retornoLog, window);
	else window.open('./index.php?m=tarefas&a=ver_log_atualizar&dialogo=1&tarefa_id='+tarefa_id, 'Registro','height=820,width=820px,resizable,scrollbars=no');
	}

function retornoLog(update){
	if(update){
		url_passar(false,'m=tarefas&a=parafazer');
	}
}

function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	document.escolherFiltro.submit();
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>