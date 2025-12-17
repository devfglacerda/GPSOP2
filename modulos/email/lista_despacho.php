<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['tab'])) $Aplic->setEstado('DespachosListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('DespachosListaTab') !== null ? $Aplic->getEstado('DespachosListaTab') : 0);


if (isset($_REQUEST['despachostextobusca'])) $Aplic->setEstado('despachostextobusca', getParam($_REQUEST, 'despachostextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('despachostextobusca') ? $Aplic->getEstado('despachostextobusca') : '');

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

$onde=getParam($_REQUEST, 'onde', '');


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';



$botoesTitulo = new CBlocoTitulo('Despachos de '.ucfirst($config['mensagem']), 'despacho.gif');

$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
$saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/despacho_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
$saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
$saida.='<table cellspacing=0 cellpadding=0>';
$vazio='<tr><td colspan=2>&nbsp;</td></tr>';


$procurar_usuario='<tr><td align=right style="white-space: nowrap">'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="despachostextobusca" onChange="document.env.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&despachostextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
		
$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_usuario.$procuraBuffer.'</table></td></tr></table>';
$saida.= '</div></div>';
$botoesTitulo->adicionaCelula($saida);
$botoesTitulo->mostrar();




$caixaTab = new CTabBox('m=email&a=lista_despacho', BASE_DIR.'/modulos/email/', $tab);
$caixaTab->adicionar('despachos_enviados', 'Recebidos sem resposta',null,null,'Recebidos Sem Resposta','Clique nesta aba para visualizar os despachos recebidos que anda não foram respondidos.');
$caixaTab->adicionar('despachos_enviados', 'Enviados sem resposta',null,null,'Enviados Sem Resposta','Clique nesta aba para visualizar os despachos enviados que anda não foram respondidos.');
$caixaTab->adicionar('despachos_enviados', 'Recebidos já respondidos',null,null,'Recebidos Já Respondidos','Clique nesta aba para visualizar os despachos recebidos que já foram respondidos.');
$caixaTab->adicionar('despachos_enviados', 'Enviados já respondidos',null,null,'Enviados Já Respondidos','Clique nesta aba para visualizar os despachos enviados que já foram respondidos.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
echo '</form>';
?>

<script type="text/javascript">
function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	env.submit();
	}
</script>	