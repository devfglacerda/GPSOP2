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

global $Aplic, $tarefa_id;
$q = new BDConsulta;
$q->adTabela('foruns');
$q->esqUnir('projetos', 'p', 'projeto_id = forum_projeto');
$q->adCampo('forum_id, forum_projeto, forum_descricao, forum_dono, forum_nome, forum_contagem_msg, forum_ultima_data,	projeto_nome, projeto_cor, projeto_id');
$q->adOnde('forum_tarefa = '.(int)$tarefa_id);
$q->adOrdem('forum_projeto, forum_nome');
$rc=$q->Lista();
$q->limpar();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr><th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap">'.dica('Assunto', 'Assunto do fórum.').'Assunto'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do fórum.').'Descrição'.dicaF().'</th><th style="white-space: nowrap">'.dica('Número de '.ucfirst($config['mensagens']).'', 'Número de mensagens contídas neste fórum.').'Msg'.dicaF().'</th><th style="white-space: nowrap">'.dica('Última Postagem', 'Última postagem de mensagem neste fórum.').'Última Postagem'.dicaF().'</th></tr>';


$qnt=0;
foreach ($rc as $linha)	{  
	$qnt++;
	$data = new CData($linha['forum_ultima_data']); 
	echo '<tr><td style="white-space: nowrap" align="center" width="10">'.( $linha["forum_dono"] == $Aplic->usuario_id ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');"><img src="'.acharImagem('icones/editar.gif').'" alt="expandir forum" border=0 width=12 height=12></a>' : '').'</td>';
	echo '<td style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha["forum_id"].'\');">'.$linha['forum_nome'].'</a></td><td>'.$linha['forum_descricao'].'</td>';
	echo '<td style="white-space: nowrap">'.$linha['forum_contagem_msg'].'</td>';
	echo '<td style="white-space: nowrap">'.(intval($linha['forum_ultima_data']) > 0 ? $data->format('%d/%m/%Y %H:%M') : 'n/d').'</td></tr>';
	}
if (!$qnt) echo '<tr><td colspan="7"><p>Nenhum fórum encontrado.</p></td></tr>';
echo '</table>';
?>
