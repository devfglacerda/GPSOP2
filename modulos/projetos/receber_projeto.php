<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ReceberProjetoTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ReceberProjetoTab') !== null ? $Aplic->getEstado('ReceberProjetoTab') : 1);

$onde=getParam($_REQUEST, 'onde', '');


$botoesTitulo = new CBlocoTitulo('Envio e Recebimento de '. ucfirst($config['projetos']), 'receber_projeto.png');
$botoesTitulo->mostrar();
$caixaTab = new CTabBox('m=projetos&a=receber_projeto', BASE_DIR.'/modulos/projetos/', $tab);
$caixaTab->adicionar('lista_projeto_receber', 'Recebidos Pendente',null,null,'Recebidos Pendente','Clique nesta aba para visualizar os '.$config['projetos'].' recebidos de outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que anda não foram confirmados como aceitos ou recusados.');
$caixaTab->adicionar('lista_projeto_receber', 'Recebidos Aceitos',null,null,'Recebidos','Clique nesta aba para visualizar os '.$config['projetos'].' recebidos de outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que foram confirmados como aceitos ou recusados.');
$caixaTab->adicionar('lista_projeto_receber', 'Enviados Pendentes',null,null,'Enviados Pendentes', 'Clique nesta aba para visualizar os '.$config['projetos'].' enviados para outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que ainda não foram confirmados como aceitos ou recusados.');
$caixaTab->adicionar('lista_projeto_receber', 'Enviados Aceitos',null,null,'Enviados','Clique nesta aba para visualizar os '.$config['projetos'].' enviados para outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].' que foram confirmados como aceitos ou recusados.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>